const ReactRouterPerson = () => {
	console.log(ReactRouterDOM.useParams());
	const [name, setName] = React.useState('default name');
	const {id} = ReactRouterDOM.useParams();
	
	React.useEffect(() => {
		const newPerson = data.find((person) => person.id === parseInt(id));
		setName(newPerson.name);
	}, []);
	
	return (
		<div>
			<h2>{name}</h2>
			<ReactRouterDOM.Link to='/prka/react_all_2/people' className='btn'>
				Back to people
			</ReactRouterDOM.Link>
		</div>
	);
};