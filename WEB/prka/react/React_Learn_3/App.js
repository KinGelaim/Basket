class App extends React.Component{
	//Такой себе код:
	/*
	constructor(){
		super()
		this.state = {
			name: ""
		}
		
		this.handleChange = this.handleChange.bind(this)
	}
	
	handleChange(event){
		const {name,value} = event.target
		this.setState({
			[name]: value
		})
	}
	
	render(){
		return(
			<main>
				<form>
					<input
						type="text"
						name="name"
						value={this.state.name}
						onChange={this.handleChange}
						placeholder="Введите имя"
					/>
				</form>
				<h1>{this.state.name}</h1>
			</main>
		)
	}
	*/
	
	//Код чутка пожесче
	state = {
		name: ""
	}
	
	handleChange = (event) => {
		const {name,value} = event.target
		this.setState({
			[name]: value
		})
	}
	
	render(){
		return(
			<main>
				<form>
					<input
						type="text"
						name="name"
						value={this.state.name}
						onChange={this.handleChange}
						placeholder="Введите имя"
					/>
				</form>
				<h1>{this.state.name}</h1>
			</main>
		)
	}
}