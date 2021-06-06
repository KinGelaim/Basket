// ---------Старт программы---------
var gl;
function webGLStart() {
	var canvas = document.getElementById("canvas");
	initGL(canvas);
	initShaders();
	initBuffers();
	initTexture();

	gl.clearColor(0.0, 0.0, 0.0, 1.0);
	gl.enable(gl.DEPTH_TEST);

	canvas.onmousedown = handleMouseDown;
	document.onmouseup = handleMouseUp;
	document.onmousemove = handleMouseMove;
	
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

// ---------Инициализация шейдеров---------
var shaderProgram;
function initShaders() {
	var fragmentShader = getShader(gl, "shader-fs");
	var vertexShader = getShader(gl, "shader-vs");

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
	
	shaderProgram.textureCoordAttribute = gl.getAttribLocation(shaderProgram, "aTextureCoord");
	gl.enableVertexAttribArray(shaderProgram.textureCoordAttribute);

	shaderProgram.pMatrixUniform = gl.getUniformLocation(shaderProgram, "uPMatrix");
	shaderProgram.mvMatrixUniform = gl.getUniformLocation(shaderProgram, "uMVMatrix");
	shaderProgram.samplerUniform = gl.getUniformLocation(shaderProgram, "uSampler");
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

// ---------Инициализация буферов объектов---------
var cubeVertexPositionBuffer;
var cubeVertexTextureCoordBuffer;
var cubeVertexIndexBuffer;

function initBuffers() {
	// Геометрия куба
    cubeVertexPositionBuffer = gl.createBuffer();
    gl.bindBuffer(gl.ARRAY_BUFFER, cubeVertexPositionBuffer);
    vertices = [
		// Front face
		-1.0, -1.0,  1.0,
		1.0, -1.0,  1.0,
		1.0,  1.0,  1.0,
		-1.0,  1.0,  1.0,

		// Back face
		-1.0, -1.0, -1.0,
		-1.0,  1.0, -1.0,
		1.0,  1.0, -1.0,
		1.0, -1.0, -1.0,

		// Top face
		-1.0,  1.0, -1.0,
		-1.0,  1.0,  1.0,
		1.0,  1.0,  1.0,
		1.0,  1.0, -1.0,

		// Bottom face
		-1.0, -1.0, -1.0,
		1.0, -1.0, -1.0,
		1.0, -1.0,  1.0,
		-1.0, -1.0,  1.0,

		// Right face
		1.0, -1.0, -1.0,
		1.0,  1.0, -1.0,
		1.0,  1.0,  1.0,
		1.0, -1.0,  1.0,

		// Left face
		-1.0, -1.0, -1.0,
		-1.0, -1.0,  1.0,
		-1.0,  1.0,  1.0,
		-1.0,  1.0, -1.0,
    ];
    gl.bufferData(gl.ARRAY_BUFFER, new Float32Array(vertices), gl.STATIC_DRAW);
    cubeVertexPositionBuffer.itemSize = 3;
    cubeVertexPositionBuffer.numItems = 24;

	// Текстура куба
	cubeVertexTextureCoordBuffer = gl.createBuffer();
	gl.bindBuffer(gl.ARRAY_BUFFER, cubeVertexTextureCoordBuffer);
	var textureCoords = [
		// Front face
		0.0, 0.0,
		1.0, 0.0,
		1.0, 1.0,
		0.0, 1.0,

		// Back face
		1.0, 0.0,
		1.0, 1.0,
		0.0, 1.0,
		0.0, 0.0,

		// Top face
		0.0, 0.0,
		1.0, 0.0,
		1.0, 1.0,
		0.0, 1.0,

		// Bottom face
		0.0, 1.0,
		0.0, 0.0,
		1.0, 0.0,
		1.0, 1.0,

		// Right face
		1.0, 0.0,
		1.0, 1.0,
		0.0, 1.0,
		0.0, 0.0,

		// Left face
		0.0, 0.0,
		1.0, 0.0,
		1.0, 1.0,
		0.0, 1.0,
	];
	gl.bufferData(gl.ARRAY_BUFFER, new Float32Array(textureCoords), gl.STATIC_DRAW);
	cubeVertexTextureCoordBuffer.itemSize = 2;
	cubeVertexTextureCoordBuffer.numItems = 24;
	
	// Массив элементов буфера! Чтобы использовать в квадрате только четыре вершины, а не шесть
	// Помните, что каждое число в этом буфере - индекс в массивах координат и цветов вершин.
	// Поэтому, учитывая все вышесказанное, первая линия означает, что мы задаем треугольник вершинами 0, 1 и 2, затем следующий треугольник вершинами 0, 2 и 3. 
	// А так как оба треугольника имеют один цвет и прилегают друг к другу, мы получаем квадрат, определенный вершинами 0, 1, 2 и 3. Повторяем это для всех плоскостей - и куб готов!
    cubeVertexIndexBuffer = gl.createBuffer();
    gl.bindBuffer(gl.ELEMENT_ARRAY_BUFFER, cubeVertexIndexBuffer);
    var cubeVertexIndices = [
		0, 1, 2,      0, 2, 3,    // Front face
		4, 5, 6,      4, 6, 7,    // Back face
		8, 9, 10,     8, 10, 11,  // Top face
		12, 13, 14,   12, 14, 15, // Bottom face
		16, 17, 18,   16, 18, 19, // Right face
		20, 21, 22,   20, 22, 23  // Left face
    ]
    gl.bufferData(gl.ELEMENT_ARRAY_BUFFER, new Uint16Array(cubeVertexIndices), gl.STATIC_DRAW);
    cubeVertexIndexBuffer.itemSize = 1;
    cubeVertexIndexBuffer.numItems = 36;
}

// ---------Инициализация текстуры---------
var nxTexture;	// Задняя грань
var pxTexture;	// Передняя грань
var pyTexture;	// Верхняя грань
var nyTexture;	// Нижняя грань
var nzTexture;	// Правая грань
var pzTexture;	// Левая грань
function initTexture() {
	pxTexture = gl.createTexture();
	pxTexture.image = new Image();
	pxTexture.image.onload = function() {
		handleLoadedTexture(pxTexture)
	}

	pxTexture.image.src = "panorama_image/sand/px.png";
	
	nxTexture = gl.createTexture();
	nxTexture.image = new Image();
	nxTexture.image.onload = function() {
		handleLoadedTexture(nxTexture)
	}

	nxTexture.image.src = "panorama_image/sand/nx.png";
	
	pyTexture = gl.createTexture();
	pyTexture.image = new Image();
	pyTexture.image.onload = function() {
		handleLoadedTexture(pyTexture)
	}

	pyTexture.image.src = "panorama_image/sand/py.png";
	
	nyTexture = gl.createTexture();
	nyTexture.image = new Image();
	nyTexture.image.onload = function() {
		handleLoadedTexture(nyTexture)
	}

	nyTexture.image.src = "panorama_image/sand/ny.png";
	
	nzTexture = gl.createTexture();
	nzTexture.image = new Image();
	nzTexture.image.onload = function() {
		handleLoadedTexture(nzTexture)
	}

	nzTexture.image.src = "panorama_image/sand/nz.png";
	
	pzTexture = gl.createTexture();
	pzTexture.image = new Image();
	pzTexture.image.onload = function() {
		handleLoadedTexture(pzTexture)
	}

	pzTexture.image.src = "panorama_image/sand/pz.png";
}

function handleLoadedTexture(texture) {
	gl.bindTexture(gl.TEXTURE_2D, texture);
	gl.pixelStorei(gl.UNPACK_FLIP_Y_WEBGL, true);	// Переворачиваем по вертикали (потому что ось Y в GIF направлена вниз)
	// Помещаем загруженное изображение в пространство видеокарты с помощью texImage2D.
	// Тип используемого изображения, уровень детализации, формат хранения в видеокарте (указан дважды), 
	// размер каждого «канала» изображения (тип данных для хранения красного, зеленого и синего) 
	// и наконец само изображение.
	gl.texImage2D(gl.TEXTURE_2D, 0, gl.RGBA, gl.RGBA, gl.UNSIGNED_BYTE, texture.image);
	gl.texParameteri(gl.TEXTURE_2D, gl.TEXTURE_MAG_FILTER, gl.NEAREST);	// Тип растягивание изображения для подгона под текстуру
	gl.texParameteri(gl.TEXTURE_2D, gl.TEXTURE_MIN_FILTER, gl.NEAREST);	// Тип уменьшения изображения
	gl.bindTexture(gl.TEXTURE_2D, null);	// Убрали после себя, убрав текущего текстурку с которой работает WebGL
}

// ---------Вызов отрисовки и запрос на отрисовку для браузера---------
function tick() {
    requestAnimFrame(tick);
	drawScene();
}

// ---------Отрисовка сцены---------
var xRotate = 0;
var yRotate = 0;
var zRotate = 0;
function drawScene() {
	gl.viewport(0, 0, gl.viewportWidth, gl.viewportHeight);
	gl.clear(gl.COLOR_BUFFER_BIT | gl.DEPTH_BUFFER_BIT);

	mat4.perspective(70, gl.viewportWidth / gl.viewportHeight, 0.1, 100.0, pMatrix);
	
	mat4.identity(mvMatrix);
	
	// Квадрат
	mat4.translate(mvMatrix, [0.0, 0.0, 0.0]);

    mat4.rotate(mvMatrix, degToRad(xRotate), [1, 0, 0]);
    mat4.rotate(mvMatrix, degToRad(yRotate), [0, 1, 0]);
    mat4.rotate(mvMatrix, degToRad(zRotate), [0, 0, 1]);
	
	gl.bindBuffer(gl.ARRAY_BUFFER, cubeVertexPositionBuffer);
	gl.vertexAttribPointer(shaderProgram.vertexPositionAttribute, cubeVertexPositionBuffer.itemSize, gl.FLOAT, false, 0, 0);

	gl.bindBuffer(gl.ARRAY_BUFFER, cubeVertexTextureCoordBuffer);
	gl.vertexAttribPointer(shaderProgram.textureCoordAttribute, cubeVertexTextureCoordBuffer.itemSize, gl.FLOAT, false, 0, 0);
	
	// Буфер текстуры привязываем
    gl.activeTexture(gl.TEXTURE0);	// От TEXTURE0 до TEXTURE31
	gl.bindTexture(gl.TEXTURE_2D, pxTexture);
	gl.uniform1i(shaderProgram.samplerUniform, 0);

	gl.bindBuffer(gl.ELEMENT_ARRAY_BUFFER, cubeVertexIndexBuffer);
	setMatrixUniforms();
	gl.drawElements(gl.TRIANGLES, 6, gl.UNSIGNED_SHORT, 0);
	
	// Задняя сторона
	gl.activeTexture(gl.TEXTURE0);
	gl.bindTexture(gl.TEXTURE_2D, nxTexture);
	gl.bindBuffer(gl.ELEMENT_ARRAY_BUFFER, cubeVertexIndexBuffer);
	setMatrixUniforms();
	gl.drawElements(gl.TRIANGLES, 9, gl.UNSIGNED_SHORT, 6);

	// Верхняя сторона
	gl.activeTexture(gl.TEXTURE0);
	gl.bindTexture(gl.TEXTURE_2D, pyTexture);
	gl.bindBuffer(gl.ELEMENT_ARRAY_BUFFER, cubeVertexIndexBuffer);
	setMatrixUniforms();
	gl.drawElements(gl.TRIANGLES, 15, gl.UNSIGNED_SHORT, 6);
	
	// Нижняя сторона
	gl.activeTexture(gl.TEXTURE0);
	gl.bindTexture(gl.TEXTURE_2D, nyTexture);
	gl.bindBuffer(gl.ELEMENT_ARRAY_BUFFER, cubeVertexIndexBuffer);
	setMatrixUniforms();
	gl.drawElements(gl.TRIANGLES, 21, gl.UNSIGNED_SHORT, 6);
	
	// Правая сторона
	gl.activeTexture(gl.TEXTURE0);
	gl.bindTexture(gl.TEXTURE_2D, nzTexture);
	gl.bindBuffer(gl.ELEMENT_ARRAY_BUFFER, cubeVertexIndexBuffer);
	setMatrixUniforms();
	gl.drawElements(gl.TRIANGLES, 27, gl.UNSIGNED_SHORT, 6);
	
	// Левая сторона
	gl.activeTexture(gl.TEXTURE0);
	gl.bindTexture(gl.TEXTURE_2D, pzTexture);
	gl.bindBuffer(gl.ELEMENT_ARRAY_BUFFER, cubeVertexIndexBuffer);
	setMatrixUniforms();
	gl.drawElements(gl.TRIANGLES, 33, gl.UNSIGNED_SHORT, 6);
}

// ---------Матрицы преобразований (матрица вида и матрица перспективы)---------
var mvMatrix = mat4.create();
var pMatrix = mat4.create();

// Все изменения в переменной mvMatrix происходят только в пространстве JavaScript.Функция setMatrixUniforms, переносит эти изменения в видеокарту.
function setMatrixUniforms() {
	gl.uniformMatrix4fv(shaderProgram.pMatrixUniform, false, pMatrix);
	gl.uniformMatrix4fv(shaderProgram.mvMatrixUniform, false, mvMatrix);
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

// ---------Мышька---------
var mouseDown = false;
var lastMouseX = null;
var lastMouseY = null;

function handleMouseDown(event) {
	mouseDown = true;
	lastMouseX = event.clientX;
	lastMouseY = event.clientY;
}

function handleMouseUp(event) {
	mouseDown = false;
}

function handleMouseMove(event) {
	if (!mouseDown) {
		return;
	}
	var newX = event.clientX;
	var newY = event.clientY;

	var deltaX = newX - lastMouseX
	yRotate += deltaX / 3;

	var deltaY = newY - lastMouseY;
	xRotate += deltaY / 3;

	lastMouseX = newX
	lastMouseY = newY;
}