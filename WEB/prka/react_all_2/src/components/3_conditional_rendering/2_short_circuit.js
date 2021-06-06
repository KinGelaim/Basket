function ShortCircuit() {
	const [text, setText] = React.useState('');
	const firstValue = text || 'hello world';
	const secondValue = text && 'hello world';
	
	const [isError, setError] = React.useState(false);
	
	return (
		<div>
			<h1>{firstValue}</h1>
			<h2>value: {secondValue}</h2>
			<button className='btn' onClick={()=>setError(!isError)}>Toggle error</button>
			{isError && <h1>Error...</h1>}
			{isError ? (
				<p>there is an error...</p>
			) : (
				<div>
					<h2>there is no error</h2>
				</div>
			)}
		</div>
	)
}