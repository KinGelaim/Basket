/*
function Greeting() {
	return <h4>This is my first component!</h4>;
}
*/

/*
const Greeting = () => {
	return React.createElement('h1', {}, 'Hello world!');
}
*/

/*
const Greeting = () => {
	return (
		<div>
			<h4>This is my first component!</h4>
		</div>
	);
}
*/

/*
const Greeting = () => {
	return React.createElement(
		'div',
		{},
		React.createElement('h1', {}, 'Hello world!')
	);
}
*/

ReactDOM.render(
  <Greeting />,
  document.getElementById('root')
);