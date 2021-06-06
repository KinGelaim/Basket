const MultipleInputs = () => {
	const [person, setPerson] = React.useState({ firstName: '', email: '', age: '' });
	const [people, setPeople] = React.useState([]);

	const handleChange = (e) => {
		const name = e.target.name;
		const value = e.target.value;
		
		setPerson({...person, [name]:value});
	};
	
	const handleSubmit = (e) => {
		e.preventDefault();
		
		if(person.firstName && person.email && person.age) {
			const newPerson = {...person, id: new Date().getTime().toString()};
			setPeople([...people, newPerson]);
			setPerson({ firstName: '', email: '', age: '' });
		} else {
			console.log('Empty values');
		}
	};
	
	return (
		<article>
			<form className='form'>
				<div className='form-control'>
					<label htmlFor='firstName'>Name: </label>
					<input
						type='text'
						id='firstName'
						name='firstName'
						value={person.firstName}
						onChange={handleChange}
					/>
				</div>
				<div className='form-control'>
					<label htmlFor='email'>Email: </label>
					<input
						type='text'
						id='email'
						name='email'
						value={person.email}
						onChange={handleChange}
					/>
				</div>
				<div className='form-control'>
					<label htmlFor='age'>Age: </label>
					<input
						type='text'
						id='age'
						name='age'
						value={person.age}
						onChange={handleChange}
					/>
				</div>
				<button type='submit' onClick={handleSubmit}>Register</button>
			</form>
			{people.map((person, index) => {
				const {id, firstName, email, age} = person;
				return (
					<div className='item' key={id}>
						<h4>{firstName}</h4>
						<p>{age}</p>
						<p>{email}</p>
					</div>
				);
			})}
		</article>
	);
};