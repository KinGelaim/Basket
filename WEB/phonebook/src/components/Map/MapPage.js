class MapPage extends React.Component {
	state = {
		chosenMapUpData : {
			title: '',
			imgHref: '',
			contacts: []
		},
		mapUpData: []
	};
	
	componentDidMount() {
		fetch('api/maps.php',
			{
				method: 'GET',
				headers: {
					'Content-Type': 'application/json'
				}
			})
			.then((response) => {
				return response.json();
			})
			.then(data => {
				this.setState({...this.state, mapUpData: data});
			})
			.catch(e => console.error('Error: ', e));
	}
	
	choseMap(idMap) {
		fetch('api/maps.php?id=' + idMap,
			{
				method: 'GET',
				headers: {
					'Content-Type': 'application/json'
				}
			})
			.then((response) => {
				return response.json();
			})
			.then(data => {
				this.setState({...this.state, chosenMapUpData: data});
			})
			.catch(e => console.error('Error: ', e));
	}
	
	render() {
		return (
			<React.Fragment>
				<div className='map-container'>
					<MapNavBar mapUpData={this.state.mapUpData} choseMap={this.choseMap.bind(this)} />
					<MapUP chosenMapUpData={this.state.chosenMapUpData} />
				</div>
			</React.Fragment>
		);
	}
};