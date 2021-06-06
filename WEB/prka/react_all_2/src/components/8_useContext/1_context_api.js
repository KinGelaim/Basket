const PersonContext = React.createContext();	//two components - Provider, Consumer

const ContextAPI = () => {
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
		<PersonContext.Provider value={{ removePerson, people }}>
			<h3>Context API / React.useContext</h3>
			<List2 />
		</PersonContext.Provider>
	);
};

const List2 = () => {
	const mainData = React.useContext(PersonContext);
	return (
		<React.Fragment>
			{mainData.people.map((person) => {
				return <SinglePerson2 key={person.id} id={person.id} name={person.name} />
			})}
		</React.Fragment>
	);
};

const SinglePerson2 = ({ id, name }) => {
	const {removePerson} = React.useContext(PersonContext);
	return (
		<div className='item'>
			<h4>{name}</h4>
			<button onClick={ () => removePerson(id) }>Remove</button>
		</div>
	);
};