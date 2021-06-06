class MapNavBar extends React.Component {
	render() {
		return (
			<nav className='map-navbar'>
				<h3><i>Структура предприятия</i></h3>
				<ul>
					{Object.keys(this.props.mapUpData).map((building, index) => {
						let pr = this.props.mapUpData[building].floors.map((floor) => {
							return <li key={floor.id} className='map-navbar__li' onClick={()=>this.props.choseMap(floor.id)}>{floor.title}</li>
						});
						return (
							<React.Fragment>
								<li key={index}><b>{building}</b></li>
								{pr}
							</React.Fragment>
						);
					})}
				</ul>
				<button className='btn' type='button'>Add map</button>
			</nav>
		);
	}
}