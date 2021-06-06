window.onload = function(){
	var width = window.innerWidth;
	var height = window.innerHeight;
	
	var canvas = document.getElementById("canvas");
	
	canvas.setAttribute("width", width);
	canvas.setAttribute("height", height);
	
	// Рендер
	var renderer = new THREE.WebGLRenderer({canvas: canvas});
	renderer.setClearColor(0x000000);
	
	// Сцена
	var scene = new THREE.Scene();
	
	// Камера
	var camera = new THREE.PerspectiveCamera(45, width / height, 0.1, 5000);	// Угол обзора, пропорция камеры, расстояние видимости объекта от и до (~px)
	camera.position.set(0, 0, 1000);
	
	// Источник света
	var light = new THREE.AmbientLight(0xffffff);	// Расеяный свет (белый)
	scene.add(light);
	
	// Мешы
	//var geometry = new THREE.PlaneGeometry(300, 300, 12, 12);	// Плоскость (ширина, высота, кол-во фрагментов)
	var geometry = new THREE.SphereGeometry(200, 17, 17);		// Сфера (радиус, полигоны)
	var material = new THREE.MeshBasicMaterial({color: 0x00ff00, wireframe: true});
	var mesh = new THREE.Mesh(geometry, material);
	mesh.position.x = 500;
	scene.add(mesh);
	
	var material2 = new THREE.MeshBasicMaterial({color: 0x00ff00, vertexColors: THREE.FaceColors});
	for(var i = 0; i < geometry.faces.length; i++){
		geometry.faces[i].color.setRGB(Math.random(), Math.random(), Math.random());
	}
	var mesh2 = new THREE.Mesh(geometry, material2);
	scene.add(mesh2);
	
	var material3 = new THREE.MeshBasicMaterial({color: 0xffffff, vertexColors: THREE.FaceColors});
	for(var i = 0; i < geometry.faces.length; i++){
		geometry.faces[i].color.setRGB(Math.random(), Math.random(), Math.random());
	}
	var mesh3 = new THREE.Mesh(geometry, material3);
	mesh3.position.x = -500;
	scene.add(mesh3);
	
	// Объект для изменений в GUI
	var ball = {
		rotationX: 0,
		rotationY: 0.01,
		rotationZ: 0
	}
	
	// GUI
	var gui = new dat.gui.GUI();
	gui.add(ball, 'rotationX').min(-0.2).max(0.2).step(0.001);
	gui.add(ball, 'rotationY').min(-0.2).max(0.2).step(0.001);
	gui.add(ball, 'rotationZ').min(-0.2).max(0.2).step(0.001);
	
	function loop(){
		mesh2.rotation.x += ball.rotationX;
		mesh2.rotation.y += ball.rotationY;
		mesh2.rotation.z += ball.rotationZ;
		renderer.render(scene, camera);
		requestAnimationFrame(function(){loop();});
	}
	
	loop();
}