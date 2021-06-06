function App() {
	const [todosArr, setTodos] = React.useState([]);
	const [loading, setLoading] = React.useState(true);
	
	React.useEffect(() => {
		fetch('php/server.php')
			.then(response => response.json())
			.then(todosArr => {
				setTimeout(()=>{
					setTodos(todosArr);
					setLoading(false);
				}, 2000);
			});
	}, []);
	
	function toggleTodo(id){
		setTodos(todosArr.map(todo => {
			if(todo.id == id){
				todo.completed = !todo.completed;
			}
			return todo;
		}));
	}
	
	function addTodo(title) {
		setTodos(todosArr.concat([{
			title,
			id: Date.now(),
			completed: false
		}]));
	}
	
	function removeTodo(id){
		setTodos(todosArr.filter(todo => todo.id != id));
	}
	
	return (
		<Context.Provider value={{removeTodo}}>
			<div className='wrapper'>
				<h1>Todos list</h1>
				<Modal />
				<AddTodo onCreate={addTodo} />
				{loading && <Loader />}
				{todosArr.length ? (
					<TodoList todos={todosArr} onToggle={toggleTodo} />
				) : (
					loading ? null : <p>No todos!</p>
				)}
			</div>
		</Context.Provider>
	);
}