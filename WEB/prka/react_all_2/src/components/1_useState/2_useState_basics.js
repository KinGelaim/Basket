const UseStateBasics = () => {
	// const value = React.useState(1)[0];
	// const handler = React.useState(1)[1];
	// console.log(value, handler);
	
	const [text, setText] = React.useState('random title');
	
	function handlerClick() {
		if(text === 'random title') {
			setText('hello world');
		}else{
			setText('random title');
		}
	}
	
	return (	
		<React.Fragment>
			<h1>{text}</h1>
			<button
				type='button'
				className='btn'
				onClick={handlerClick}
			>Change title</button>
		</React.Fragment>
	);
};