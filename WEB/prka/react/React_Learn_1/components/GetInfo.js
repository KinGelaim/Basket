class GetInfo extends React.Component{
	constructor(){
		super()
		this.state = {
			isLoading: false,
			character: {}
		}
	}
	
	componentDidMount(){
		this.setState({isLoading: true})
		fetch("http://localhost/react/React_Learn_1/php/data.php")
			.then(response => response.json())
			.then(data => {
				console.log(data)
				this.setState({
					isLoading: false,
					character: data
				})
			})
	}
	
	render(){
		const text = this.state.isLoading ? "Загрузка..." : this.state.character.surname + " " + this.state.character.name
		return (
			<div>
				<h1>{text}</h1>
			</div>
		)
	}
}