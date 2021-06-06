const UseStateArray = () => {
	let data = [
		{id: 1, name: 'john'},
		{id: 2, name: 'peter'},
		{id: 3, name: 'anna'},
		{id: 4, name: 'alisa'}
	];

	const [people, setPeople] = React.useState(data);
	
	function removeItem(id) {
		let newPeople = people.filter((person)=>person.id !== id);
		setPeople(newPeople);
	}
	
	return (
		<React.Fragment>
			{people.map((person) => {
				const {id, name} = person;
				return (
					<div key={id} className='item'>
						<h4>{name}</h4>
						<button onClick={()=>removeItem(id)}>Remove item</button>
					</div>
				);
			})}
			<button className='btn' onClick={()=>setPeople([])}>Clear items</button>
		</React.Fragment>
	);
};