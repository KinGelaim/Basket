const UseStateObject = () => {
	const [person, setPerson] = React.useState({name:'elena', age: 22, message: 'random message'});
	
	const changeMessage = () => {
		setPerson({ ...person, message: 'asd' });
	};
	
	return (
		<React.Fragment>
			<h3>{person.name}</h3>
			<h3>{person.age}</h3>
			<h3>{person.message}</h3>
			<button className='btn' onClick={changeMessage}>
				Change message
			</button>
		</React.Fragment>
	);
};