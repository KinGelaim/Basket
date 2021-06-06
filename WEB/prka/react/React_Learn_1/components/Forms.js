class Forms extends React.Component{
	constructor(){
		super()
		this.state = {
			name: "",
			surname: "",
			isFrendly: true,
			gender: ""
		}
		
		this.handleChange = this.handleChange.bind(this)
	}
	
	handleChange(event){
		//const {name, value} = event.target
		const {name, value, type, checked} = event.target
		type === "checkbox" ? 
		this.setState({[name]: checked}) : 
		this.setState({
			[name]: event.target.value
		})
	}
	
	render(){
		return (
			<FormComponents 
				handleChange={this.handleChange}
				data={this.state}
			/>
		)
	}
}