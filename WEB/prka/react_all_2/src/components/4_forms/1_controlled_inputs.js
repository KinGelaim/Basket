const ControlledInputs = () => {
	const [firstName, setFirstName] = React.useState('');
	const [email, setEmail] = React.useState('');
	const [people, setPeople] = React.useState([]);

	const handlerSubmit = (event) => {
		event.preventDefault();
		
		if(firstName && email) {
			const person = { id: new Date().getTime().toString(), firstName, email };
			setPeople((people) => {
				return [...people, person];
			});
			setFirstName('');
			setEmail('');
		} else {
			console.log('Empty values');
		}
	};

	return (
		<article>
			<form className='form' onSubmit={handlerSubmit}>
				<div className='form-control'>
					<label htmlFor='firstName'>Name: </label>
					<input
						type='text'
						id='firstName'
						name='firstName'
						value={firstName}
						onChange={(e)=>setFirstName(e.target.value)}
					/>
				</div>
				<div className='form-control'>
					<label htmlFor='email'>Email: </label>
					<input
						type='text'
						id='email'
						name='email'
						value={email}
						onChange={(e)=>setEmail(e.target.value)}
					/>
				</div>
				<button type='submit'>Register</button>
			</form>
			{people.map((person, index) => {
				const {id, firstName, email} = person;
				return (
					<div className='item' key={id}>
						<h4>{firstName}</h4>
						<p>{email}</p>
					</div>
				);
			})}
		</article>
	);
};