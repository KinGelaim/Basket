precision mediump float;

attribute vec3 vertPosition;
attribute vec2 a_texcoord;

varying vec2 v_texcoord;
varying vec3 fragColor;

void main() {
	gl_Position = vec4(vertPosition, 1.0);

	// Pass the texcoord to the fragment shader.
	v_texcoord = a_texcoord;
}