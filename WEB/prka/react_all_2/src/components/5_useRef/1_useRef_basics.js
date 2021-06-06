const UseRefBasics = () => {
	const refContainer = React.useRef(null);
	
	const handleSubmit = (e) => {
		e.preventDefault();
		console.log(refContainer.current.value);
	};
	
	React.useEffect(() => {
		console.log(refContainer.current);
		refContainer.current.focus();
	});
	
	console.log('Render component');
	
	return (
		<React.Fragment>
			<form className='form' onSubmit={handleSubmit}>
				<div>
					<input type='text' ref={refContainer} />
					<button type='submit'>Submit</button>
				</div>
			</form>
		</React.Fragment>
	);
};