var loadTextResource = function(url){
	return new Promise(function(resolve, reject){
		var request = new XMLHttpRequest();
		request.open('GET', url, true);
		request.onload = function(){
			if(request.status >= 200 && request.status < 300){
				resolve(request.responseText);
			}else{
				reject('Error: HTTP status - ' + request.status + ' on resource ' + url);
			}
		};
		request.send();
	});
}

var createShader = function(gl, type, source){
	
	var shader = gl.createShader(type);
	
	gl.shaderSource(shader, source);
	
	gl.compileShader(shader);
	
	if(!gl.getShaderParameter(shader, gl.COMPILE_STATUS)){
		console.error('ERROR compile vertex shader!', gl.getShaderInfoLog(shader));
		return false;
	}
	
	return shader;
}

var createProgram = function(gl, vertexShader, fragmentShader){

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
	
	return program;
}