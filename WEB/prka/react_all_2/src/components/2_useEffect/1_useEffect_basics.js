function UseEffectBasics() {
	const [value, setValue] = React.useState(0);
	
	React.useEffect(() => {
		console.log('use effect');
		if(value > 0) {
			document.title = `New messages (${value})`;
		}
	}, [value]);
	
	React.useEffect(()=> {
		console.log('use 2 effect');
	}, []);
	
	console.log('render component');
	return (
		<div>
			<h2>{value}</h2>
			<button className='btn' onClick={()=>setValue(value + 1)}>Click me!</button>
		</div>
	);
}