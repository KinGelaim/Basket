document.addEventListener('DOMContentLoaded', function(){
	InitWebGl();
});

// Получаем шейдеры
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

var StartWebGL = function(vertexShaderText, fragmentShaderText){
	// Получаем конву
	var canvas = document.getElementById('example-canvas');
	var gl = canvas.getContext('webgl');	//Контекст для рисования на канвасе
	
	// Проверяем на получение WEBGL
	if(!gl){
		console.log('WebGL not supported, falling back on experimental-webgl');
		gl = canvas.getContext('experimental-webgl');
	}
	
	if(!gl){
		alert('Your browser does not support WebGL');
	}
	
	// Размер канвы и вьюпорта
	canvas.height = gl.canvas.clientHeight;
	canvas.width = gl.canvas.clientWidth;
	gl.viewport(0, 0, gl.canvas.clientWidth, gl.canvas.clientHeight);
	
	// Создаём шейдеры
	var vertexShader = gl.createShader(gl.VERTEX_SHADER);
	var fragmentShader = gl.createShader(gl.FRAGMENT_SHADER);
	
	gl.shaderSource(vertexShader, vertexShaderText);
	gl.shaderSource(fragmentShader, fragmentShaderText);
	
	// Компилируем шейдеры
	gl.compileShader(vertexShader);
	if(!gl.getShaderParameter(vertexShader, gl.COMPILE_STATUS)){
		console.error('ERROR compile vertex shader!', gl.getShaderInfoLog(vertexShader));
		return;
	}
	
	gl.compileShader(fragmentShader);
	if(!gl.getShaderParameter(fragmentShader, gl.COMPILE_STATUS)){
		console.error('ERROR compile fragment shader!', gl.getShaderInfoLog(fragmentShader));
		return;
	}
	
	// Создаём программу
	var program = gl.createProgram();
	gl.attachShader(program, vertexShader);
	gl.attachShader(program, fragmentShader);
	gl.linkProgram(program);
	if(!gl.getProgramParameter(program, gl.LINK_STATUS)){
		console.error('ERROR linking program', gl.getProgramInfoLog(program));
		return;
	}
	
	gl.validateProgram(program);
	if(!gl.getProgramParameter(program, gl.VALIDATE_STATUS)){
		console.error('ERROR validating program', gl.getProgramInfoLog(program));
		return;
	}
	
	// Создаём геометрию
	var triangleVerties1 =
	[	//X    Y	Z	     R    G    B
		-0.7,  0.7, 0.0,    1.0, 1.0, 0.0,
		 0.7,  0.7, 0.0,    0.7, 0.0, 1.0,
		 0.7, -0.7, 0.0,    0.1, 1.0, 0.4,
		
		-0.7,  0.7, 0.0,    1.0, 1.0, 0.0,
		-0.7, -0.7, 0.0,    0.7, 0.0, 1.0,
		 0.7, -0.7, 0.0,    0.1, 1.0, 0.6,
	];
	
	var triangleVertexBufferObject = gl.createBuffer();
	gl.bindBuffer(gl.ARRAY_BUFFER, triangleVertexBufferObject);
	gl.bufferData(gl.ARRAY_BUFFER, new Float32Array(triangleVerties1), gl.STATIC_DRAW);
	
	var positionAttribLocation = gl.getAttribLocation(program, 'vertPosition');	// Получаем ссылку на атрибут
	gl.vertexAttribPointer(
		positionAttribLocation,	// ссылка на атрибут
		3,	// Кол-во элементов на интерацию
		gl.FLOAT,	// Тип данных
		gl.FALSE,	// Нужна ли нормализация данных
		6 * Float32Array.BYTES_PER_ELEMENT,	// Кол-во элементов массива на одну вершину
		0 * Float32Array.BYTES_PER_ELEMENT	// Отступ для каждой итерации
	);
	gl.enableVertexAttribArray(positionAttribLocation);
	
	// Грузим текстурки
	var texture = gl.createTexture();
	gl.bindTexture(gl.TEXTURE_2D, texture);
	// Fill the texture with a 1x1 blue pixel.
	gl.texImage2D(gl.TEXTURE_2D, 0, gl.RGBA, 1, 1, 0, gl.RGBA, gl.UNSIGNED_BYTE,
				new Uint8Array([0, 0, 255, 255]));
	
	var image = new Image();
	image.onload = function(){
		gl.bindTexture(gl.TEXTURE_2D, texture);
		console.log('ads');
	}
	image.src = '../texture/1.png';
	
	gl.bindTexture(gl.TEXTURE_2D, texture);
	gl.texParameteri(gl.TEXTURE_2D, gl.TEXTURE_WRAP_S, gl.CLAMP_TO_EDGE);
	gl.texParameteri(gl.TEXTURE_2D, gl.TEXTURE_WRAP_T, gl.CLAMP_TO_EDGE);
	gl.texParameteri(gl.TEXTURE_2D, gl.TEXTURE_MIN_FILTER, gl.LINEAR);
	gl.texParameteri(gl.TEXTURE_2D, gl.TEXTURE_MAG_FILTER, gl.LINEAR);
	
	// Очистка экрана
	gl.clearColor(0.75, 0.85, 0.8, 1.0);
	gl.clear(gl.COLOR_BUFFER_BIT | gl.DEPTH_BUFFER_BIT);
	
	// Отрисовка
	gl.enable(gl.DEPTH_TEST);	// Проверка глубины
	gl.useProgram(program);
	gl.drawArrays(gl.TRIANGLES, 0, 6);	// Фигура, отступ, кол-во вершин всего
};