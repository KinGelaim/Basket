const PropDrilling = () => {
	let data = [
		{id: 1, name: 'john'},
		{id: 2, name: 'peter'},
		{id: 3, name: 'anna'},
		{id: 4, name: 'alisa'}
	];
	
	const [people, setPeople] = React.useState(data);
	
	const removePerson = (id) => {
		setPeople((people) => {
			return people.filter((person) => person.id !== id);
		});
	};
	
	return (
		<section>
			<h3>Prop drilling</h3>
			<List people={people} removePerson={removePerson} />
		</section>
	);
};

const List = (props) => {
	return (
		<React.Fragment>
			{props.people.map((person) => {
				return <SinglePerson key={person.id} id={person.id} name={person.name} removePerson={props.removePerson} />
			})}
		</React.Fragment>
	);
};

const SinglePerson = ({ id, name, removePerson }) => {
	return (
		<div className='item'>
			<h4>{name}</h4>
			<button onClick={ () => removePerson(id) }>Remove</button>
		</div>
	);
};