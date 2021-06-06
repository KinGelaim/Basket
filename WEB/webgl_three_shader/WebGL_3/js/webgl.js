document.addEventListener('DOMContentLoaded', function(){
	InitWebGl();
});

var InitWebGl = function(){
	var VSText, FSText;
	loadTextResource('shaders/vertexShader.glsl')
		.then(function(result){
			VSText = result;
			return loadTextResource('shaders/fragmentShader.glsl')
		})
		.then(function(result){
			FSText = result;
			return StartWebGL(VSText, FSText);
		})
		.catch(function(error){
			alert('Error in log!');
			console.log(error);
		})
}

var gl, program, vertexArray = [];
var StartWebGL = function(vertexShaderText, fragmentShaderText){
	console.log('This is working!');
	
	// Получение канваса со страницы
	var canvas = document.getElementById('example-canvas');
	
	// Контекст для рисования на канвасе
	gl = canvas.getContext('webgl');
	
	if(!gl){
		console.log('WebGL not supported, falling back on experimental-webgl');
		gl = canvas.getContext('experimental-webgl');
	}
	
	if(!gl){
		alert('Your browser does not support WebGL');
	}
	
	// Размеры холста
	canvas.height = gl.canvas.clientHeight;
	canvas.width = gl.canvas.clientWidth;
	
	// Добавим отслеживание нажатие клавиши мыши по канвасу
	canvas.addEventListener('mousedown', function(event){
		onmousedown(event, canvas);
	});
	
	// Размеры вьюпорта
	gl.viewport(0, 0, gl.canvas.clientWidth, gl.canvas.clientHeight);
	
	// Создание шейдеров
	var vertexShader = createShader(gl, gl.VERTEX_SHADER, vertexShaderText);
	var fragmentShader = createShader(gl, gl.FRAGMENT_SHADER, fragmentShaderText);
	
	// Создание программы
	program = createProgram(gl, vertexShader, fragmentShader);
	
	draw();
};

var draw = function(){
	//Создание буфера
	var triangleVertexBufferObject = gl.createBuffer();
	gl.bindBuffer(gl.ARRAY_BUFFER, triangleVertexBufferObject);
	gl.bufferData(gl.ARRAY_BUFFER, new Float32Array(vertexArray), gl.STATIC_DRAW);
	
	var vericesNumber = vertexArray.length / 2;
	
	var positionAttribLocation = gl.getAttribLocation(program, 'vertPosition');	// Получаем ссылку на атрибут
	gl.vertexAttribPointer(
		positionAttribLocation,	// ссылка на атрибут
		2,	// Кол-во элементов на интерацию
		gl.FLOAT,	// Тип данных
		gl.FALSE,	// Нужна ли нормализация данных
		2 * Float32Array.BYTES_PER_ELEMENT,	// Кол-во элементов массива на одну вершину
		0 * Float32Array.BYTES_PER_ELEMENT	// Отступ для каждой итерации
	);
	gl.enableVertexAttribArray(positionAttribLocation);
	
	// *** Main render loop ***
	gl.clearColor(0.75, 0.85, 0.8, 1.0);
	gl.clear(gl.COLOR_BUFFER_BIT | gl.DEPTH_BUFFER_BIT);
	gl.useProgram(program);
	//gl.drawArrays(gl.POINTS, 0, vericesNumber);			// Точки (нужно задать gl_PointSize в vertexShader)
	//gl.drawArrays(gl.LINE_STRIP, 0, vericesNumber);		// Непрерывная линия
	//gl.drawArrays(gl.LINE_LOOP, 0, vericesNumber);		// Последняя точка соединяется с предыдущей + с первой
	//gl.drawArrays(gl.LINES, 0, vericesNumber);			// Точки соединяются по 2
	//gl.drawArrays(gl.TRIANGLE_STRIP, 0, vericesNumber);	// Последняя точка соединяется с двумя предыдущими образуя треугольник
	//gl.drawArrays(gl.TRIANGLE_FAN, 0, vericesNumber);		// Соединяются последняя, предпоследняя и самая первая точка
	//gl.drawArrays(gl.TRIANGLES, 0, vericesNumber);		// Фигура, отступ, кол-во вершин всего
	
	gl.drawArrays(gl.POINTS, 0, vericesNumber);
	gl.drawArrays(gl.TRIANGLES, 0, vericesNumber);
}

function onmousedown(event, canvas){
	var x = event.clientX;
	var y = event.clientY;
	
	var middleX = gl.canvas.width / 2;
	var middleY = gl.canvas.height / 2;
	
	var rect = canvas.getBoundingClientRect();
	
	x = ((x - rect.left) - middleX) / middleX;
	y = (middleY - (y - rect.top)) / middleY;
	
	vertexArray.push(x);
	vertexArray.push(y);
	
	draw();
}