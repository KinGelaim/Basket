class MemeGenerator extends React.Component{
	constructor(){
		super()
		this.state={
			topText: "",
			bottomText: "",
			randomImg: "http://localhost/prka/react/React_Learn_2/img/fiasco.png",
			allMemeImgs: []
		}
		
		this.handleChange = this.handleChange.bind(this)
		this.handleSubmit = this.handleSubmit.bind(this)
	}
	
	componentDidMount() {
		fetch("http://localhost/prka/react/React_Learn_2/php/get_memes.php")
			.then(response => response.json())
			.then(response => {
				this.setState({allMemeImgs: response})
			})
	}
	
	handleChange(event) {
		const {name, value} = event.target
		this.setState({
			[name]: value
		})
	}
	
	handleSubmit(event){
		event.preventDefault()
		const randNum = Math.floor(Math.random() * this.state.allMemeImgs.length)
		const randMemeImg = this.state.allMemeImgs[randNum].url
		this.setState({
			randomImg: randMemeImg
		})
	}
	
	render(){
		return(
			<div>
				<form className="meme-form" onSubmit={this.handleSubmit}>
					<input
						type="text"
						name="topText"
						placeholder="Тест сверху"
						value={this.state.topText}
						onChange={this.handleChange}
					/>
					<input
						type="text"
						name="bottomText"
						placeholder="Тест снизу"
						value={this.state.bottomText}
						onChange={this.handleChange}
					/>
					<button>Генерировать</button>
				</form>
				<div className="meme">
					<img src={this.state.randomImg} alt="" />
					<h2 className="top">{this.state.topText}</h2>
					<h2 className="bottom">{this.state.bottomText}</h2>
				</div>
			</div>
		)
	}
}