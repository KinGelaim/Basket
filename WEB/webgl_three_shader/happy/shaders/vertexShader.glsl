precision mediump float;

attribute vec2 vertPosition;

varying vec3 fragColor;

void main()
{
	fragColor = vec3(vertPosition, 0.5);
	gl_Position = vec4(vertPosition, 0.0, 1.0);
	gl_PointSize = 7.0;
}