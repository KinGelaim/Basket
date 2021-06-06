class TodoList extends React.Component{
	constructor(){
		super()
		this.state = {
			todos: todoData
		}
		this.handleChange = this.handleChange.bind(this)
	}
	
	handleChange(id){
		console.log("Change", id)
		this.setState(prevState => {
			const updatedTodos = prevState.todos.map(todo => {
				if(todo.id == id){
					todo.completed = !todo.completed
				}
				return todo
			})
			return {
				todos: updatedTodos
			}
		})
	}
	
	render(){
		/*
		const nums = [1,2,3,4,5,6,7];
		const doubled = nums.map(function(num) {
			return num * 2;
		})
		console.log(doubled)
		*/
		
		const todoComponents = this.state.todos.map(data => {
			return (
				<TodoItem key={data.id} id={data.id} text={data.text} completed={data.completed} handleChange={this.handleChange} />
			)
		});
		
		return (
			<div className="todo-list">
				<TodoItem
					key="1"
					text="Купить молока"
					completed="true"
				/>
				<TodoItem key="2" text="Купить батон"/>
				<TodoItem />
				<TodoItem />
				{todoComponents}
			</div>
		)
	}
}