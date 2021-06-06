const UseStateCounter = () => {
	const [value, setValue] = React.useState(0);
	
	const reset = () => {
		setValue(0);
	}
	
	function complexIncrease() {
		setTimeout(() => {
			setValue(value + 1);
			console.log(value);
		}, 3000);
	}
	
	function complexIncreaseTimeout() {
		setTimeout(() => {
			setValue((prevState) => {
				console.log(prevState);
				return prevState + 1;
			});
		}, 3000);
	}
	
	return (
		<React.Fragment>
			<section>
				<h2>Regular counter</h2>
				<h1>{value}</h1>
				<button className='btn' onClick={ ()=>setValue(value - 1) }>decrease</button>
				<button className='btn' onClick={ reset }>reset</button>
				<button className='btn' onClick={ ()=>setValue(value + 1) }>increase</button>
				
				<h2>More Complex Counter Freeze Value</h2>
				<h1>{value}</h1>
				<button className='btn' onClick={complexIncrease}>increase later</button>
				
				<h2>More Complex Counter</h2>
				<h1>{value}</h1>
				<button className='btn' onClick={complexIncreaseTimeout}>increase later</button>
			</section>
		</React.Fragment>
	);
};