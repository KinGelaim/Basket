let data = [
	{id: 1, name: 'john'},
	{id: 2, name: 'peter'},
	{id: 3, name: 'anna'},
	{id: 4, name: 'alisa'}
];

const reducer = (state,action) => {
	if(action.type === 'ADD_ITEM') {
		const newPeople = [...state.people, action.payload];
		return {
			...state,
			people: newPeople,
			isModalOpen: true,
			modalContent: 'Item added!'
		};
	}
	if(action.type === 'NO_VALUE') {
		return {
			...state,
			isModalOpen: true,
			modalContent: 'Please enter value!'
		}
	}
	if(action.type === 'CLOSE_MODAL') {
		return {
			...state,
			isModalOpen: false,
			modalContent: ''
		}
	}
	if(action.type === 'REMOVE_ITEM') {
		return {
			...state,
			people: state.people.filter((person)=>person.id !== action.payload)
		}
	}
	//return state;
	throw new Error('No matching action type!');
};

const defaultState = {
	people: [],
	isModalOpen: false,
	modalContent: 'Hello world!'
};

const UseReducer = () => {	
	const [name, setName] = React.useState('');
	//const [people, setPeople] = React.useState(data);
	//const [showModal, setShowModal] = React.useState(false);
	const [state, dispatch] = React.useReducer(reducer, defaultState);

	const handleSubmit = (e) => {
		e.preventDefault();
		if(name) {
			//setShowModal(true);
			//setPeople([...people, { id: new Date().getTime().toString(), name }]);
			//setName('');
			const newItem = { id: new Date().getTime().toString(), name };
			dispatch({ type: 'ADD_ITEM', payload: newItem });
			setName('');
		} else {
			//setShowModal(true);
			dispatch({ type: 'NO_VALUE' });
		}
	};
	
	const closeModal = () => {
		dispatch({ type: 'CLOSE_MODAL' });
	};
	
	return (
		<React.Fragment>
			{/* showModal && <UseReducerModal /> */}
			{state.isModalOpen && <UseReducerModal closeModal={closeModal} modalContent={state.modalContent} />}
			<form onSubmit={handleSubmit} className='form'>
				<div>
					<input
						type='text'
						value={name}
						onChange={(e)=>setName(e.target.value)}
					/>
				</div>
				<button type='submit'>Add</button>
			</form>
			{state.people.map((person) => {
				return (
					<div key={person.id} className='item'>
						<h4>{person.name}</h4>
						<button
							onClick={()=>{dispatch({ type: 'REMOVE_ITEM', payload: person.id })}}
						>
							Remove
						</button>
					</div>
				)
			})}
		</React.Fragment>
	);
};