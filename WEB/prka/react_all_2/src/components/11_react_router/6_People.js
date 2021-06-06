const ReactRouterPeople = () => {
	let data = [
		{id: 1, name: 'john'},
		{id: 2, name: 'peter'},
		{id: 3, name: 'anna'},
		{id: 4, name: 'alisa'}
	];
	
	const [people, setPeople] = React.useState(data);
	
	return (
		<div>
			<h2>People page</h2>
			{people.map((person) => {
				return (
					<div key={person.id} className='item'>
						<h4>{person.name}</h4>
						<ReactRouterDOM.Link to={`/prka/react_all_2/person/${person.id}`}>Learn More</ReactRouterDOM.Link>
					</div>
				);
			})}
		</div>
	);
};