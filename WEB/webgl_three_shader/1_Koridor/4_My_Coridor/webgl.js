// ---------Старт программы---------
var gl;
function webGLStart() {
	var canvas = document.getElementById("canvas");
	initGL(canvas);
	initShaders();
	initTexture();
	loadWorld();

	gl.clearColor(0.0, 0.0, 0.0, 1.0);
	gl.enable(gl.DEPTH_TEST);

	document.onkeydown = handleKeyDown;
	document.onkeyup = handleKeyUp;
	
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

// ---------Инициализация текстуры---------
var floorTexture;
var ceilingTexture;
var wallTexture;
var doorTexture;
var door22Texture;
var doorITTexture;
function initTexture() {
	floorTexture = gl.createTexture();
	floorTexture.image = new Image();
	floorTexture.image.onload = function () {
		handleLoadedTexture(floorTexture)
	}

	floorTexture.image.src = "images/Pol.png";
	
	ceilingTexture = gl.createTexture();
	ceilingTexture.image = new Image();
	ceilingTexture.image.onload = function () {
		handleLoadedTexture(ceilingTexture)
	}

	ceilingTexture.image.src = "images/Potolok.png";
	
	wallTexture = gl.createTexture();
	wallTexture.image = new Image();
	wallTexture.image.onload = function () {
		handleLoadedTexture(wallTexture)
	}

	wallTexture.image.src = "images/Stena.png";
	
	doorTexture = gl.createTexture();
	doorTexture.image = new Image();
	doorTexture.image.onload = function () {
		handleLoadedTexture(doorTexture)
	}

	doorTexture.image.src = "images/Door.png";
	
	door22Texture = gl.createTexture();
	door22Texture.image = new Image();
	door22Texture.image.onload = function () {
		handleLoadedTexture(door22Texture)
	}

	door22Texture.image.src = "images/Door22.png";
	
	doorITTexture = gl.createTexture();
	doorITTexture.image = new Image();
	doorITTexture.image.onload = function () {
		handleLoadedTexture(doorITTexture)
	}

	doorITTexture.image.src = "images/DoorIT.png";
}

function handleLoadedTexture(texture) {
	gl.pixelStorei(gl.UNPACK_FLIP_Y_WEBGL, true);
	
	gl.bindTexture(gl.TEXTURE_2D, texture);
	gl.texImage2D(gl.TEXTURE_2D, 0, gl.RGBA, gl.RGBA, gl.UNSIGNED_BYTE, texture.image);
	gl.texParameteri(gl.TEXTURE_2D, gl.TEXTURE_MAG_FILTER, gl.LINEAR);
	gl.texParameteri(gl.TEXTURE_2D, gl.TEXTURE_MIN_FILTER, gl.LINEAR);

	gl.bindTexture(gl.TEXTURE_2D, null);
}

// ---------Инициализация мира---------
function loadWorld() {
	var request = new XMLHttpRequest();
	request.open("GET", "world.txt");
	request.onreadystatechange = function () {
		if (request.readyState == 4) {
			handleLoadedWorld(request.responseText);
		}
	}
	request.send();
}

var worldFloorVertexPositionBuffer = null;
var worldFloorVertexTextureCoordBuffer = null;
var worldCeilingVertexPositionBuffer = null;
var worldCeilingVertexTextureCoordBuffer = null;
var worldWallVertexPositionBuffer = null;
var worldWallVertexTextureCoordBuffer = null;
var worldDoorVertexPositionBuffer = null;
var worldDoorVertexTextureCoordBuffer = null;
var worldDoor22VertexPositionBuffer = null;
var worldDoor22VertexTextureCoordBuffer = null;
var worldDoorITVertexPositionBuffer = null;
var worldDoorITVertexTextureCoordBuffer = null;

function handleLoadedWorld(data) {
	var lines = data.split("\n");
	var vertexFloorCount = 0;
	var vertexFloorPositions = [];
	var vertexFloorTextureCoords = [];
	var vertexCeilingCount = 0;
	var vertexCeilingPositions = [];
	var vertexCeilingTextureCoords = [];
	var vertexWallCount = 0;
	var vertexWallPositions = [];
	var vertexWallTextureCoords = [];
	var vertexDoorCount = 0;
	var vertexDoorPositions = [];
	var vertexDoorTextureCoords = [];
	var vertexDoor22Count = 0;
	var vertexDoor22Positions = [];
	var vertexDoor22TextureCoords = [];
	var vertexDoorITCount = 0;
	var vertexDoorITPositions = [];
	var vertexDoorITTextureCoords = [];
	for (var i in lines) {
		var vals = lines[i].replace(/^\s+/, "").split(/\s+/);
		if (vals.length == 6 && vals[0] != "//") {
			if(vals[0] == "f") {
				// It is a line describing a vertex; get X, Y and Z first
				vertexFloorPositions.push(parseFloat(vals[1]));
				vertexFloorPositions.push(parseFloat(vals[2]));
				vertexFloorPositions.push(parseFloat(vals[3]));

				// And then the texture coords
				vertexFloorTextureCoords.push(parseFloat(vals[4]));
				vertexFloorTextureCoords.push(parseFloat(vals[5]));

				vertexFloorCount += 1;
			}
			if(vals[0] == "c") {
				vertexCeilingPositions.push(parseFloat(vals[1]));
				vertexCeilingPositions.push(parseFloat(vals[2]));
				vertexCeilingPositions.push(parseFloat(vals[3]));

				vertexCeilingTextureCoords.push(parseFloat(vals[4]));
				vertexCeilingTextureCoords.push(parseFloat(vals[5]));

				vertexCeilingCount += 1;
			}
			if(vals[0] == "w") {
				vertexWallPositions.push(parseFloat(vals[1]));
				vertexWallPositions.push(parseFloat(vals[2]));
				vertexWallPositions.push(parseFloat(vals[3]));

				vertexWallTextureCoords.push(parseFloat(vals[4]));
				vertexWallTextureCoords.push(parseFloat(vals[5]));

				vertexWallCount += 1;
			}
			if(vals[0] == "d") {
				vertexDoorPositions.push(parseFloat(vals[1]));
				vertexDoorPositions.push(parseFloat(vals[2]));
				vertexDoorPositions.push(parseFloat(vals[3]));

				vertexDoorTextureCoords.push(parseFloat(vals[4]));
				vertexDoorTextureCoords.push(parseFloat(vals[5]));

				vertexDoorCount += 1;
			}
			if(vals[0] == "d22") {
				vertexDoor22Positions.push(parseFloat(vals[1]));
				vertexDoor22Positions.push(parseFloat(vals[2]));
				vertexDoor22Positions.push(parseFloat(vals[3]));

				vertexDoor22TextureCoords.push(parseFloat(vals[4]));
				vertexDoor22TextureCoords.push(parseFloat(vals[5]));

				vertexDoor22Count += 1;
			}
			if(vals[0] == "dit") {
				vertexDoorITPositions.push(parseFloat(vals[1]));
				vertexDoorITPositions.push(parseFloat(vals[2]));
				vertexDoorITPositions.push(parseFloat(vals[3]));

				vertexDoorITTextureCoords.push(parseFloat(vals[4]));
				vertexDoorITTextureCoords.push(parseFloat(vals[5]));

				vertexDoorITCount += 1;
			}
		}
	}

	// Пол
	worldFloorVertexPositionBuffer = gl.createBuffer();
	gl.bindBuffer(gl.ARRAY_BUFFER, worldFloorVertexPositionBuffer);
	gl.bufferData(gl.ARRAY_BUFFER, new Float32Array(vertexFloorPositions), gl.STATIC_DRAW);
	worldFloorVertexPositionBuffer.itemSize = 3;
	worldFloorVertexPositionBuffer.numItems = vertexFloorCount;

	worldFloorVertexTextureCoordBuffer = gl.createBuffer();
	gl.bindBuffer(gl.ARRAY_BUFFER, worldFloorVertexTextureCoordBuffer);
	gl.bufferData(gl.ARRAY_BUFFER, new Float32Array(vertexFloorTextureCoords), gl.STATIC_DRAW);
	worldFloorVertexTextureCoordBuffer.itemSize = 2;
	worldFloorVertexTextureCoordBuffer.numItems = vertexFloorCount;
	
	// Потолок
	worldCeilingVertexPositionBuffer = gl.createBuffer();
	gl.bindBuffer(gl.ARRAY_BUFFER, worldCeilingVertexPositionBuffer);
	gl.bufferData(gl.ARRAY_BUFFER, new Float32Array(vertexCeilingPositions), gl.STATIC_DRAW);
	worldCeilingVertexPositionBuffer.itemSize = 3;
	worldCeilingVertexPositionBuffer.numItems = vertexCeilingCount;

	worldCeilingVertexTextureCoordBuffer = gl.createBuffer();
	gl.bindBuffer(gl.ARRAY_BUFFER, worldCeilingVertexTextureCoordBuffer);
	gl.bufferData(gl.ARRAY_BUFFER, new Float32Array(vertexCeilingTextureCoords), gl.STATIC_DRAW);
	worldCeilingVertexTextureCoordBuffer.itemSize = 2;
	worldCeilingVertexTextureCoordBuffer.numItems = vertexCeilingCount;
	
	// Стена
	worldWallVertexPositionBuffer = gl.createBuffer();
	gl.bindBuffer(gl.ARRAY_BUFFER, worldWallVertexPositionBuffer);
	gl.bufferData(gl.ARRAY_BUFFER, new Float32Array(vertexWallPositions), gl.STATIC_DRAW);
	worldWallVertexPositionBuffer.itemSize = 3;
	worldWallVertexPositionBuffer.numItems = vertexWallCount;

	worldWallVertexTextureCoordBuffer = gl.createBuffer();
	gl.bindBuffer(gl.ARRAY_BUFFER, worldWallVertexTextureCoordBuffer);
	gl.bufferData(gl.ARRAY_BUFFER, new Float32Array(vertexWallTextureCoords), gl.STATIC_DRAW);
	worldWallVertexTextureCoordBuffer.itemSize = 2;
	worldWallVertexTextureCoordBuffer.numItems = vertexWallCount;
	
	// Дверь
	worldDoorVertexPositionBuffer = gl.createBuffer();
	gl.bindBuffer(gl.ARRAY_BUFFER, worldDoorVertexPositionBuffer);
	gl.bufferData(gl.ARRAY_BUFFER, new Float32Array(vertexDoorPositions), gl.STATIC_DRAW);
	worldDoorVertexPositionBuffer.itemSize = 3;
	worldDoorVertexPositionBuffer.numItems = vertexDoorCount;

	worldDoorVertexTextureCoordBuffer = gl.createBuffer();
	gl.bindBuffer(gl.ARRAY_BUFFER, worldDoorVertexTextureCoordBuffer);
	gl.bufferData(gl.ARRAY_BUFFER, new Float32Array(vertexDoorTextureCoords), gl.STATIC_DRAW);
	worldDoorVertexTextureCoordBuffer.itemSize = 2;
	worldDoorVertexTextureCoordBuffer.numItems = vertexDoorCount;
	
	// Дверь 22
	worldDoor22VertexPositionBuffer = gl.createBuffer();
	gl.bindBuffer(gl.ARRAY_BUFFER, worldDoor22VertexPositionBuffer);
	gl.bufferData(gl.ARRAY_BUFFER, new Float32Array(vertexDoor22Positions), gl.STATIC_DRAW);
	worldDoor22VertexPositionBuffer.itemSize = 3;
	worldDoor22VertexPositionBuffer.numItems = vertexDoor22Count;

	worldDoor22VertexTextureCoordBuffer = gl.createBuffer();
	gl.bindBuffer(gl.ARRAY_BUFFER, worldDoor22VertexTextureCoordBuffer);
	gl.bufferData(gl.ARRAY_BUFFER, new Float32Array(vertexDoor22TextureCoords), gl.STATIC_DRAW);
	worldDoor22VertexTextureCoordBuffer.itemSize = 2;
	worldDoor22VertexTextureCoordBuffer.numItems = vertexDoor22Count;
	
	// Дверь IT
	worldDoorITVertexPositionBuffer = gl.createBuffer();
	gl.bindBuffer(gl.ARRAY_BUFFER, worldDoorITVertexPositionBuffer);
	gl.bufferData(gl.ARRAY_BUFFER, new Float32Array(vertexDoorITPositions), gl.STATIC_DRAW);
	worldDoorITVertexPositionBuffer.itemSize = 3;
	worldDoorITVertexPositionBuffer.numItems = vertexDoorITCount;

	worldDoorITVertexTextureCoordBuffer = gl.createBuffer();
	gl.bindBuffer(gl.ARRAY_BUFFER, worldDoorITVertexTextureCoordBuffer);
	gl.bufferData(gl.ARRAY_BUFFER, new Float32Array(vertexDoorITTextureCoords), gl.STATIC_DRAW);
	worldDoorITVertexTextureCoordBuffer.itemSize = 2;
	worldDoorITVertexTextureCoordBuffer.numItems = vertexDoorITCount;

	document.getElementById("loadingtext").textContent = "";
}

// ---------Вызов отрисовки и запрос на отрисовку для браузера---------
function tick() {
	requestAnimFrame(tick);
	handleKeys();
	drawScene();
	animate();
	checkDoor();
}

// ---------Отрисовка сцены---------
var pitch = 0;
var pitchRate = 0;

var yaw = 0;
var yawRate = 0;

var xPos = 0;
var yPos = 0.4;
var zPos = 0;

var speed = 0;
function drawScene() {
	gl.viewport(0, 0, gl.viewportWidth, gl.viewportHeight);
	gl.clear(gl.COLOR_BUFFER_BIT | gl.DEPTH_BUFFER_BIT);

	if (worldFloorVertexTextureCoordBuffer == null || worldFloorVertexPositionBuffer == null) {
		return;
	}

	mat4.perspective(45, gl.viewportWidth / gl.viewportHeight, 0.1, 100.0, pMatrix);

	mat4.identity(mvMatrix);

	// Перемещаем весь мир, чтобы смоделировать перемещение камеры (камера неподвижна)
	mat4.rotate(mvMatrix, degToRad(-pitch), [1, 0, 0]);
	mat4.rotate(mvMatrix, degToRad(-yaw), [0, 1, 0]);
	mat4.translate(mvMatrix, [-xPos, -yPos, -zPos]);

	// Floor
	gl.activeTexture(gl.TEXTURE0);
	gl.bindTexture(gl.TEXTURE_2D, floorTexture);
	gl.uniform1i(shaderProgram.samplerUniform, 0);

	gl.bindBuffer(gl.ARRAY_BUFFER, worldFloorVertexTextureCoordBuffer);
	gl.vertexAttribPointer(shaderProgram.textureCoordAttribute, worldFloorVertexTextureCoordBuffer.itemSize, gl.FLOAT, false, 0, 0);

	gl.bindBuffer(gl.ARRAY_BUFFER, worldFloorVertexPositionBuffer);
	gl.vertexAttribPointer(shaderProgram.vertexPositionAttribute, worldFloorVertexPositionBuffer.itemSize, gl.FLOAT, false, 0, 0);

	setMatrixUniforms();
	gl.drawArrays(gl.TRIANGLES, 0, worldFloorVertexPositionBuffer.numItems);
	
	// Ceiling
	gl.activeTexture(gl.TEXTURE0);
	gl.bindTexture(gl.TEXTURE_2D, ceilingTexture);
	gl.uniform1i(shaderProgram.samplerUniform, 0);

	gl.bindBuffer(gl.ARRAY_BUFFER, worldCeilingVertexTextureCoordBuffer);
	gl.vertexAttribPointer(shaderProgram.textureCoordAttribute, worldCeilingVertexTextureCoordBuffer.itemSize, gl.FLOAT, false, 0, 0);

	gl.bindBuffer(gl.ARRAY_BUFFER, worldCeilingVertexPositionBuffer);
	gl.vertexAttribPointer(shaderProgram.vertexPositionAttribute, worldCeilingVertexPositionBuffer.itemSize, gl.FLOAT, false, 0, 0);

	setMatrixUniforms();
	gl.drawArrays(gl.TRIANGLES, 0, worldCeilingVertexPositionBuffer.numItems);
	
	// Wall
	gl.activeTexture(gl.TEXTURE0);
	gl.bindTexture(gl.TEXTURE_2D, wallTexture);
	gl.uniform1i(shaderProgram.samplerUniform, 0);

	gl.bindBuffer(gl.ARRAY_BUFFER, worldWallVertexTextureCoordBuffer);
	gl.vertexAttribPointer(shaderProgram.textureCoordAttribute, worldWallVertexTextureCoordBuffer.itemSize, gl.FLOAT, false, 0, 0);

	gl.bindBuffer(gl.ARRAY_BUFFER, worldWallVertexPositionBuffer);
	gl.vertexAttribPointer(shaderProgram.vertexPositionAttribute, worldWallVertexPositionBuffer.itemSize, gl.FLOAT, false, 0, 0);

	setMatrixUniforms();
	gl.drawArrays(gl.TRIANGLES, 0, worldWallVertexPositionBuffer.numItems);
	
	// Door
	gl.activeTexture(gl.TEXTURE0);
	gl.bindTexture(gl.TEXTURE_2D, doorTexture);
	gl.uniform1i(shaderProgram.samplerUniform, 0);

	gl.bindBuffer(gl.ARRAY_BUFFER, worldDoorVertexTextureCoordBuffer);
	gl.vertexAttribPointer(shaderProgram.textureCoordAttribute, worldDoorVertexTextureCoordBuffer.itemSize, gl.FLOAT, false, 0, 0);

	gl.bindBuffer(gl.ARRAY_BUFFER, worldDoorVertexPositionBuffer);
	gl.vertexAttribPointer(shaderProgram.vertexPositionAttribute, worldDoorVertexPositionBuffer.itemSize, gl.FLOAT, false, 0, 0);

	setMatrixUniforms();
	gl.drawArrays(gl.TRIANGLES, 0, worldDoorVertexPositionBuffer.numItems);
	
	// Door22
	gl.activeTexture(gl.TEXTURE0);
	gl.bindTexture(gl.TEXTURE_2D, door22Texture);
	gl.uniform1i(shaderProgram.samplerUniform, 0);

	gl.bindBuffer(gl.ARRAY_BUFFER, worldDoor22VertexTextureCoordBuffer);
	gl.vertexAttribPointer(shaderProgram.textureCoordAttribute, worldDoor22VertexTextureCoordBuffer.itemSize, gl.FLOAT, false, 0, 0);

	gl.bindBuffer(gl.ARRAY_BUFFER, worldDoor22VertexPositionBuffer);
	gl.vertexAttribPointer(shaderProgram.vertexPositionAttribute, worldDoor22VertexPositionBuffer.itemSize, gl.FLOAT, false, 0, 0);

	setMatrixUniforms();
	gl.drawArrays(gl.TRIANGLES, 0, worldDoor22VertexPositionBuffer.numItems);
	
	// DoorIT
	gl.activeTexture(gl.TEXTURE0);
	gl.bindTexture(gl.TEXTURE_2D, doorITTexture);
	gl.uniform1i(shaderProgram.samplerUniform, 0);

	gl.bindBuffer(gl.ARRAY_BUFFER, worldDoorITVertexTextureCoordBuffer);
	gl.vertexAttribPointer(shaderProgram.textureCoordAttribute, worldDoorITVertexTextureCoordBuffer.itemSize, gl.FLOAT, false, 0, 0);

	gl.bindBuffer(gl.ARRAY_BUFFER, worldDoorITVertexPositionBuffer);
	gl.vertexAttribPointer(shaderProgram.vertexPositionAttribute, worldDoorITVertexPositionBuffer.itemSize, gl.FLOAT, false, 0, 0);

	setMatrixUniforms();
	gl.drawArrays(gl.TRIANGLES, 0, worldDoorITVertexPositionBuffer.numItems);
}

// ---------Движение объектов (анимация)---------
var lastTime = 0;
var joggingAngle = 0;

function animate() {
	var timeNow = new Date().getTime();
	if (lastTime != 0) {
		var elapsed = timeNow - lastTime;

		if (speed != 0) {
			xPos -= Math.sin(degToRad(yaw)) * speed * elapsed;
			zPos -= Math.cos(degToRad(yaw)) * speed * elapsed;

			joggingAngle += elapsed * 0.6;
			yPos = Math.sin(degToRad(joggingAngle)) / 20 + 0.4;
		}

		yaw += yawRate * elapsed;
		pitch += pitchRate * elapsed;

	}
	lastTime = timeNow;
}

// ---------Проверка дверей---------
var isVisibleInter22 = false;
function checkDoor() {
	if(xPos < -8 && xPos > -9.5 && zPos < -2.2 && zPos > -4 && !isVisibleInter22)
	{
		isVisibleInter22 = true;
		console.log(22);
	}
	if(isVisibleInter22)
		if(xPos > -8 || xPos < -9.5 || zPos > -2.2 || zPos < -4)
		{
			isVisibleInter22 = false;
			console.log('exit22');
		}
	if(xPos < -9.5 && xPos > -10.8 && zPos < -2.2 && zPos > -4)
		console.log('it');
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

// ---------Клавиатура---------
var currentlyPressedKeys = {};

function handleKeyDown(event) {
	currentlyPressedKeys[event.keyCode] = true;
}

function handleKeyUp(event) {
	currentlyPressedKeys[event.keyCode] = false;
}

function handleKeys() {
	if (currentlyPressedKeys[33]) {
		// Page Up
		pitchRate = 0.1;
	} else if (currentlyPressedKeys[34]) {
		// Page Down
		pitchRate = -0.1;
	} else {
		pitchRate = 0;
	}

	if (currentlyPressedKeys[37] || currentlyPressedKeys[65]) {
		// Left cursor key or A
		yawRate = 0.1;
	} else if (currentlyPressedKeys[39] || currentlyPressedKeys[68]) {
		// Right cursor key or D
		yawRate = -0.1;
	} else {
		yawRate = 0;
	}

	if (currentlyPressedKeys[38] || currentlyPressedKeys[87]) {
		// Up cursor key or W
		speed = 0.003;
	} else if (currentlyPressedKeys[40] || currentlyPressedKeys[83]) {
		// Down cursor key
		speed = -0.003;
	} else {
		speed = 0;
	}
}