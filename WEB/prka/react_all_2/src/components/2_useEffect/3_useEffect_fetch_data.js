function UseEffectFetchData() {
	const url = 'src/components/2_useEffect/server.php';
	
	const [users, setUsers] = React.useState([]);
	
	const getUsers = async () => {
		const response = await fetch(url);
		const newUsers = await response.json();
		setUsers(newUsers);
	};
	
	React.useEffect(() => {
		getUsers();
		console.log('asd');
	}, []);
	
	return (
		<div>
			<h1>Users</h1>
			<ul className='users'>
				{users.map((person)=>{
					return <li>{person.login}</li>
				})}
			</ul>
		</div>
	);
}