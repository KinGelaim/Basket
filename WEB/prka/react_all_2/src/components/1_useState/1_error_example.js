const ErrorExample = () => {
	let title = 'Random title';
	
	const handleClick = () => {
		console.log(title);
		title = 'Hello people!';
		console.log(title);
	};
	
	return (
		<React.Fragment>
			<h2>{title}</h2>
			<button type='button' className='btn' onClick={handleClick}>Change title</button>
		</React.Fragment>
	);
};