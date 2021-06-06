const scene = new THREE.Scene();
const camera = new THREE.PerspectiveCamera(70, window.innerWidth / window.innerHeight);		// fov, область захвата
const renderer = new THREE.WebGLRenderer();

scene.background = new THREE.Color(0x000000);
camera.position.z = 5;
renderer.setSize(window.innerWidth, window.innerHeight);
document.body.appendChild(renderer.domElement);

const points = [
	new THREE.Vector2(0,0),
	new THREE.Vector2(1,1),
	new THREE.Vector2(2,-1)
]

const material = new THREE.LineBasicMaterial({color: 0x00ff00});
const geometryLine = new THREE.BufferGeometry().setFromPoints(points);
const line = new THREE.Line(geometryLine, material);
scene.add(line);

//const material2 = new THREE.MeshBasicMaterial({color: 0xdddddd, envMap: []});
const material2 = new THREE.MeshBasicMaterial({color: 0xdddddd});
const cubeGeometry = new THREE.BoxGeometry(1, 1, 1);
const cube = new THREE.Mesh(cubeGeometry, material2);
scene.add(cube);

function animate() {
	requestAnimationFrame(animate);
	renderer.render(scene, camera);
	
	cube.rotation.x += 0.01;
	cube.rotation.y += 0.01;
}

animate();