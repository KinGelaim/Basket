// ---------Старт программы---------
var gl;
function webGLStart() {
	var canvas = document.getElementById("canvas");
	initGL(canvas);
	initShaders();
	initTextures();
	loadTeapot();

	gl.clearColor(0.0, 0.0, 0.0, 1.0);
	gl.enable(gl.DEPTH_TEST);

	tick();
}

// ---------Инициализация контекста WebGL---------
function initGL(canvas) {
	try {
		gl = canvas.getContext("webgl");
		gl.viewportWidth = canvas.width;
		gl.viewportHeight = canvas.height;
	} catch (e) {
	}
	if (!gl) {
		try {
		gl = canvas.getContext("experimental-webgl");
		gl.viewportWidth = canvas.width;
		gl.viewportHeight = canvas.height;
		} catch (e) {
		}
		if (!gl) {
			alert("Could not initialise WebGL, sorry :-(");
		}
	}
}

// ---------Инициализация шейдеров и программ---------
var shaderProgram;
function initShaders() {
	var fragmentShader = getShader(gl, "per-fragment-lighting-fs");
	var vertexShader = getShader(gl, "per-fragment-lighting-vs");

	shaderProgram = gl.createProgram();
	gl.attachShader(shaderProgram, vertexShader);
	gl.attachShader(shaderProgram, fragmentShader);
	gl.linkProgram(shaderProgram);

	if (!gl.getProgramParameter(shaderProgram, gl.LINK_STATUS)) {
		alert("Could not initialise shaders");
	}

	gl.useProgram(shaderProgram);

	shaderProgram.vertexPositionAttribute = gl.getAttribLocation(shaderProgram, "aVertexPosition");
	gl.enableVertexAttribArray(shaderProgram.vertexPositionAttribute);

	shaderProgram.vertexNormalAttribute = gl.getAttribLocation(shaderProgram, "aVertexNormal");
	gl.enableVertexAttribArray(shaderProgram.vertexNormalAttribute);

	shaderProgram.textureCoordAttribute = gl.getAttribLocation(shaderProgram, "aTextureCoord");
	gl.enableVertexAttribArray(shaderProgram.textureCoordAttribute);

	shaderProgram.pMatrixUniform = gl.getUniformLocation(shaderProgram, "uPMatrix");
	shaderProgram.mvMatrixUniform = gl.getUniformLocation(shaderProgram, "uMVMatrix");
	shaderProgram.nMatrixUniform = gl.getUniformLocation(shaderProgram, "uNMatrix");
	shaderProgram.samplerUniform = gl.getUniformLocation(shaderProgram, "uSampler");
	shaderProgram.materialShininessUniform = gl.getUniformLocation(shaderProgram, "uMaterialShininess");
	shaderProgram.showSpecularHighlightsUniform = gl.getUniformLocation(shaderProgram, "uShowSpecularHighlights");
	shaderProgram.useTexturesUniform = gl.getUniformLocation(shaderProgram, "uUseTextures");
	shaderProgram.useLightingUniform = gl.getUniformLocation(shaderProgram, "uUseLighting");
	shaderProgram.ambientColorUniform = gl.getUniformLocation(shaderProgram, "uAmbientColor");
	shaderProgram.pointLightingLocationUniform = gl.getUniformLocation(shaderProgram, "uPointLightingLocation");
	shaderProgram.pointLightingSpecularColorUniform = gl.getUniformLocation(shaderProgram, "uPointLightingSpecularColor");
	shaderProgram.pointLightingDiffuseColorUniform = gl.getUniformLocation(shaderProgram, "uPointLightingDiffuseColor");
}

// ---------Получение шейдеров---------
function getShader(gl, id) {
	var shaderScript = document.getElementById(id);
	if (!shaderScript) {
		return null;
	}

	var str = "";
	var k = shaderScript.firstChild;
	while (k) {
		if (k.nodeType == 3) {
			str += k.textContent;
		}
		k = k.nextSibling;
	}

	var shader;
	if (shaderScript.type == "x-shader/x-fragment") {
		shader = gl.createShader(gl.FRAGMENT_SHADER);
	} else if (shaderScript.type == "x-shader/x-vertex") {
		shader = gl.createShader(gl.VERTEX_SHADER);
	} else {
		return null;
	}

	gl.shaderSource(shader, str);
	gl.compileShader(shader);

	if (!gl.getShaderParameter(shader, gl.COMPILE_STATUS)) {
		alert(gl.getShaderInfoLog(shader));
		return null;
	}

	return shader;
}

// ---------Инициализация буфера (чайничка)---------
var teapotVertexPositionBuffer;
var teapotVertexNormalBuffer;
var teapotVertexTextureCoordBuffer;
var teapotVertexIndexBuffer;

function loadTeapot() {
	var request = new XMLHttpRequest();
	request.open("GET", "Teapot.json");
	request.onreadystatechange = function () {
		if (request.readyState == 4) {
			handleLoadedTeapot(JSON.parse(request.responseText));
		}
	}
	request.send();
}

function handleLoadedTeapot(teapotData) {
	teapotVertexNormalBuffer = gl.createBuffer();
	gl.bindBuffer(gl.ARRAY_BUFFER, teapotVertexNormalBuffer);
	gl.bufferData(gl.ARRAY_BUFFER, new Float32Array(teapotData.vertexNormals), gl.STATIC_DRAW);
	teapotVertexNormalBuffer.itemSize = 3;
	teapotVertexNormalBuffer.numItems = teapotData.vertexNormals.length / 3;

	teapotVertexTextureCoordBuffer = gl.createBuffer();
	gl.bindBuffer(gl.ARRAY_BUFFER, teapotVertexTextureCoordBuffer);
	gl.bufferData(gl.ARRAY_BUFFER, new Float32Array(teapotData.vertexTextureCoords), gl.STATIC_DRAW);
	teapotVertexTextureCoordBuffer.itemSize = 2;
	teapotVertexTextureCoordBuffer.numItems = teapotData.vertexTextureCoords.length / 2;

	teapotVertexPositionBuffer = gl.createBuffer();
	gl.bindBuffer(gl.ARRAY_BUFFER, teapotVertexPositionBuffer);
	gl.bufferData(gl.ARRAY_BUFFER, new Float32Array(teapotData.vertexPositions), gl.STATIC_DRAW);
	teapotVertexPositionBuffer.itemSize = 3;
	teapotVertexPositionBuffer.numItems = teapotData.vertexPositions.length / 3;

	teapotVertexIndexBuffer = gl.createBuffer();
	gl.bindBuffer(gl.ELEMENT_ARRAY_BUFFER, teapotVertexIndexBuffer);
	gl.bufferData(gl.ELEMENT_ARRAY_BUFFER, new Uint16Array(teapotData.indices), gl.STATIC_DRAW);
	teapotVertexIndexBuffer.itemSize = 1;
	teapotVertexIndexBuffer.numItems = teapotData.indices.length;

	document.getElementById("loadingtext").textContent = "";
}

// ---------Инициализация текстуры---------
var earthTexture;
var galvanizedTexture;
function initTextures() {
	earthTexture = gl.createTexture();
	earthTexture.image = new Image();
	earthTexture.image.onload = function () {
		handleLoadedTexture(earthTexture)
	}
	earthTexture.image.src = "earth.jpg";

	galvanizedTexture = gl.createTexture();
	galvanizedTexture.image = new Image();
	galvanizedTexture.image.onload = function () {
		handleLoadedTexture(galvanizedTexture)
	}
	galvanizedTexture.image.src = "arroway.de_metal+structure+06_d100_flat.jpg";
}

function handleLoadedTexture(texture) {
	gl.pixelStorei(gl.UNPACK_FLIP_Y_WEBGL, true);
	
	gl.bindTexture(gl.TEXTURE_2D, texture);
	gl.texImage2D(gl.TEXTURE_2D, 0, gl.RGBA, gl.RGBA, gl.UNSIGNED_BYTE, texture.image);
	gl.texParameteri(gl.TEXTURE_2D, gl.TEXTURE_MAG_FILTER, gl.LINEAR);
	gl.texParameteri(gl.TEXTURE_2D, gl.TEXTURE_MIN_FILTER, gl.LINEAR);

	gl.bindTexture(gl.TEXTURE_2D, null);
}

// ---------Вызов отрисовки и запрос на отрисовку для браузера---------
function tick() {
	requestAnimFrame(tick);
	drawScene();
	animate();
}

// ---------Отрисовка сцены---------
var teapotAngle = 180;
function drawScene() {
	gl.viewport(0, 0, gl.viewportWidth, gl.viewportHeight);
	gl.clear(gl.COLOR_BUFFER_BIT | gl.DEPTH_BUFFER_BIT);

	if (teapotVertexPositionBuffer == null || teapotVertexNormalBuffer == null || teapotVertexTextureCoordBuffer == null || teapotVertexIndexBuffer == null) {
		return;
	}

	mat4.perspective(45, gl.viewportWidth / gl.viewportHeight, 0.1, 100.0, pMatrix);

	var specularHighlights = document.getElementById("specular").checked;
	gl.uniform1i(shaderProgram.showSpecularHighlightsUniform, specularHighlights);

	var lighting = document.getElementById("lighting").checked;
	gl.uniform1i(shaderProgram.useLightingUniform, lighting);
	if (lighting) {
		gl.uniform3f(
			shaderProgram.ambientColorUniform,
			parseFloat(document.getElementById("ambientR").value),
			parseFloat(document.getElementById("ambientG").value),
			parseFloat(document.getElementById("ambientB").value)
		);

		gl.uniform3f(
			shaderProgram.pointLightingLocationUniform,
			parseFloat(document.getElementById("lightPositionX").value),
			parseFloat(document.getElementById("lightPositionY").value),
			parseFloat(document.getElementById("lightPositionZ").value)
		);

		gl.uniform3f(
			shaderProgram.pointLightingSpecularColorUniform,
			parseFloat(document.getElementById("specularR").value),
			parseFloat(document.getElementById("specularG").value),
			parseFloat(document.getElementById("specularB").value)
		);

		gl.uniform3f(
			shaderProgram.pointLightingDiffuseColorUniform,
			parseFloat(document.getElementById("diffuseR").value),
			parseFloat(document.getElementById("diffuseG").value),
			parseFloat(document.getElementById("diffuseB").value)
		);
	}

	var texture = document.getElementById("texture").value;
	gl.uniform1i(shaderProgram.useTexturesUniform, texture != "none");

	mat4.identity(mvMatrix);

	mat4.translate(mvMatrix, [0, 0, -40]);
	mat4.rotate(mvMatrix, degToRad(23.4), [1, 0, -1]);
	mat4.rotate(mvMatrix, degToRad(teapotAngle), [0, 1, 0]);

	gl.activeTexture(gl.TEXTURE0);
	if (texture == "earth") {
		gl.bindTexture(gl.TEXTURE_2D, earthTexture);
	} else if (texture == "galvanized") {
		gl.bindTexture(gl.TEXTURE_2D, galvanizedTexture);
	}
	gl.uniform1i(shaderProgram.samplerUniform, 0);

	gl.uniform1f(shaderProgram.materialShininessUniform, parseFloat(document.getElementById("shininess").value));

	gl.bindBuffer(gl.ARRAY_BUFFER, teapotVertexPositionBuffer);
	gl.vertexAttribPointer(shaderProgram.vertexPositionAttribute, teapotVertexPositionBuffer.itemSize, gl.FLOAT, false, 0, 0);

	gl.bindBuffer(gl.ARRAY_BUFFER, teapotVertexTextureCoordBuffer);
	gl.vertexAttribPointer(shaderProgram.textureCoordAttribute, teapotVertexTextureCoordBuffer.itemSize, gl.FLOAT, false, 0, 0);

	gl.bindBuffer(gl.ARRAY_BUFFER, teapotVertexNormalBuffer);
	gl.vertexAttribPointer(shaderProgram.vertexNormalAttribute, teapotVertexNormalBuffer.itemSize, gl.FLOAT, false, 0, 0);

	gl.bindBuffer(gl.ELEMENT_ARRAY_BUFFER, teapotVertexIndexBuffer);
	setMatrixUniforms();
	gl.drawElements(gl.TRIANGLES, teapotVertexIndexBuffer.numItems, gl.UNSIGNED_SHORT, 0);
}

// ---------Анимация---------
var lastTime = 0;
function animate() {
	var timeNow = new Date().getTime();
	if (lastTime != 0) {
		var elapsed = timeNow - lastTime;

		teapotAngle += 0.05 * elapsed;
	}
	lastTime = timeNow;
}

// ---------Матрицы преобразований (матрица вида и матрица перспективы)---------
var mvMatrix = mat4.create();
var pMatrix = mat4.create();

// Все изменения в переменной mvMatrix происходят только в пространстве JavaScript.Функция setMatrixUniforms, переносит эти изменения в видеокарту.
function setMatrixUniforms() {
	gl.uniformMatrix4fv(shaderProgram.pMatrixUniform, false, pMatrix);
	gl.uniformMatrix4fv(shaderProgram.mvMatrixUniform, false, mvMatrix);

	var normalMatrix = mat3.create();
	mat4.toInverseMat3(mvMatrix, normalMatrix);
	mat3.transpose(normalMatrix);
	gl.uniformMatrix3fv(shaderProgram.nMatrixUniform, false, normalMatrix);
}

var mvMatrixStack = [];
function mvPushMatrix() {
	var copy = mat4.create();
	mat4.set(mvMatrix, copy);
	mvMatrixStack.push(copy);
}

function mvPopMatrix() {
	if (mvMatrixStack.length == 0) {
		throw "Invalid popMatrix!";
	}
	mvMatrix = mvMatrixStack.pop();
}

// ---------Вспомогательные функции---------
// Градусы в радианы
function degToRad(degrees) {
	return degrees * Math.PI / 180;
}