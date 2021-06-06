class StatePractice extends React.Component{
	constructor() {
		super()
		this.state = {
			name: "Александра",
			age: 21,
			count: 0
		}
		this.handleClick = this.handleClick.bind(this)
	}
	
	handleClick() {
		console.log('asd');
		this.setState((prevState)=>{
			return{
				count: prevState.count + 1
			}
		})
	}
	
	render(){
		return (
			<div>
				<div>
					<h1>{this.state.name}</h1>
					<h3>Возраст: {this.state.age}</h3>
				</div>
				<div>
					<h1>{this.state.count}</h1>
					<button onClick={this.handleClick}>Добавить 1</button>
				</div>
			</div>
		)
	}
}