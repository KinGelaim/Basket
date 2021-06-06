function UseEffectCleanup() {
	const [size, setSize] = React.useState(window.innerWidth);
	
	const checkSize = () => {
		setSize(window.innerWidth);
	};
	
	React.useEffect(() => {
		console.log('Add event listener');
		window.addEventListener('resize', checkSize);
		return () => {
			console.log('cleanup');
			window.removeEventListener('resize', checkSize);
		}
	});
	console.log('render');
	return (
		<div>
			<h1>Window</h1>
			<h2>{size} PX</h2>
		</div>
	);
}