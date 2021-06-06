let canvas = document.getElementById('canvas');
let image = '../krosi.jpg';

let fragmentShader = document.getElementById('2d-fragment-shader').innerHTML;
let vertexShader = document.getElementById('2d-vertex-shader').innerHTML;

let scene = new THREE.Scene();
let camera = new THREE.OrthographicCamera(
	canvas.offsetWidth / -2.0,
	canvas.offsetWidth / 2.0,
	canvas.offsetHeight / 2.0,
	canvas.offsetHeight / -2.0,
	1.0,
	1000
);
camera.position.z = 1.0;

let renderer = new THREE.WebGLRenderer({ antialias: false });
renderer.setPixelRatio(window.devicePixelRatio);
renderer.setClearColor(0xffffff, 0.0);
renderer.setSize(canvas.offsetWidth, canvas.offsetHeight);

canvas.appendChild(renderer.domElement);

let loader = new THREE.TextureLoader();
loader.setCrossOrigin('');

let render = () => {
	renderer.render(scene, camera);
};

let texture = loader.load(image, render);
texture.minFilter = THREE.LinearFilter; // LinearMipMapLinearFilter

let material = new THREE.ShaderMaterial({
	fragmentShader: fragmentShader,
	uniforms: {
		texture: { type: 't', value: texture },
		time: { value: 1.0 },
	},
	vertexShader: vertexShader
});

let geometry = new THREE.PlaneBufferGeometry(
	canvas.offsetWidth,
	canvas.offsetHeight,
	1.0
);

let mesh = new THREE.Mesh(geometry, material);

scene.add(mesh);

let time = 0;
let animate = () => {
	time += 0.01;
	material.uniforms.time.value += time;
	material.needsUpdate = true;
	renderer.render(scene, camera);
	
	requestAnimationFrame(() => {
		animate();
	});
};

animate();