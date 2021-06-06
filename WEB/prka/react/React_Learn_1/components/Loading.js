class Loading extends React.Component{
	constructor(){
		super()
		this.state = {
			isLoading: true
		}
	}
	
	componentDidMount(){
		setTimeout(() => {
			this.setState({
				isLoading: false
			})
		}, 2500)
	}
	
	render(){		
		return (
			<div>
				{this.state.isLoading ?
				<h1>Загрузка...</h1> :
				<h1>Загружено</h1>}
			</div>
		)
	}
}