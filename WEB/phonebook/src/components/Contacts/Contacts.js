const Contacts = (props) => {
	const [filterValue, setFilterValue] = React.useState('');
	
	let [newContractData, setNewContractsData] = React.useState(props.beginContractsData);
	
	let [oldHeaderNameSort, setOldHeaderNameSort] = React.useState('');
	
	if(newContractData.length == 0 && props.beginContractsData.length != 0) {
		setNewContractsData(props.beginContractsData);
	}
	
	return (
		<React.Fragment>
			<div>
				<h2>Phonebook</h2>
			</div>
			<div className='form'>
				<input className='filter__input' value={filterValue} onChange={(e)=>setFilterValue(e.target.value)} />
				<button type='button' onClick={()=>setFilterValue('')}>Clear</button>
			</div>
			<div>
				<Table contractsData={newContractData} filterValue={filterValue} oldHeaderNameSort={oldHeaderNameSort} setOldHeaderNameSort={setOldHeaderNameSort} setNewContractsData={setNewContractsData} />
			</div>
		</React.Fragment>
	);
};