/*const MapUP = (props) => {
	let [arrayContractsMapMarker, setArrayContractsMapMarker] = React.useState([]);
	
	let refOnMap = React.createRef();
	function loadImage() {
		let newArrayContractsMapMarker = [...props.chosenMapUpData.contracts];
		newArrayContractsMapMarker = newArrayContractsMapMarker.map((person) => {
			return {...person, left: person.x + refOnMap.current.offsetLeft, top: person.y + refOnMap.current.offsetTop}
		});
		setArrayContractsMapMarker(newArrayContractsMapMarker);
	}

	const [dataMapInformation, setDataMapInformation] = React.useState({ FIO: '', position: '', telephone: '', imgHref: '', x: 0, y: 0, isVisible: 'none' });
	
	function changeInformationBlock(FIO, position, telephone, imgHref, x, y, isVisible) {
		if(isVisible) {
			setDataMapInformation({ FIO, position, telephone, imgHref, x, y, isVisible: 'block' });
		}else {
			setDataMapInformation({ FIO: '', position: '', telephone: '', imgHref: '', x: 0, y: 0, isVisible: 'none' });
		}
	}
	
	return (
		<div className='mapup'>
			{this.props.chosenMapUpData.href3D && <img className='mapup-map3d' src='http://localhost/phonebook/src/image/1002702.jpg' onClick={()=>window.open(this.props.chosenMapUpData.href3D, '_blank')} />}
			<h2 className='mapup-title'>{this.props.chosenMapUpData.title}</h2>
			<img className='mapup-image' src={this.props.chosenMapUpData.imgHref} ref={this.refOnMap} onLoad={this.loadImage.bind(this)} />
			{this.state.arrayContractsMapMarker.map((person) => {
				return <img 
					style={{left: person.left, top: person.top}}
					className='mapup-marker'
					src='http://localhost/phonebook/src/image/marker/QhzdX.png'
					onMouseMove={(e)=>this.changeInformationBlock(person.FIO, person.position, person.telephone, person.imgHref, e.clientX + 10, e.clientY + 10, true)}
					onMouseLeave={(e)=>this.changeInformationBlock(null, null, null, null, 0, 0, false)}
				/>;
			})}
			<InformationBlock dataMapInformation={this.state.dataMapInformation} />
		</div>
	);
};*/

class MapUP extends React.Component {
	state = {
		arrayContactsMapMarker: [],
		dataMapInformation: {}
	}
	
	constructor(props) {
		super(props);
		
		this.refOnMap = React.createRef();
	}
	
	loadImage() {
		let newArrayContactsMapMarker = [...this.props.chosenMapUpData.contacts];
		newArrayContactsMapMarker = newArrayContactsMapMarker.map((person) => {
			return {...person, left: person.x + this.refOnMap.current.offsetLeft, top: person.y + this.refOnMap.current.offsetTop};
		});
		this.setState({...this.state, arrayContactsMapMarker: newArrayContactsMapMarker});
	}
	
	changeInformationBlock(FIO, position, telephone, imgHref, x, y, isVisible) {
		if(isVisible) {
			this.setState({...this.state, dataMapInformation: { FIO, position, telephone, imgHref, x, y, isVisible: 'block' }});
		}else {
			this.setState({...this.state, dataMapInformation: { FIO: '', position: '', telephone: '', imgHref: '', x: 0, y: 0, isVisible: 'none' }});
		}
	}
	
	render() {
		return (
			<div className='mapup'>
				{this.props.chosenMapUpData.href3D && <img className='mapup-map3d' src='src/image/1002702.jpg' onClick={()=>window.open(this.props.chosenMapUpData.href3D, '_blank')} />}
				<h2 className='mapup-title'>{this.props.chosenMapUpData.title}</h2>
				<img className='mapup-image' src={this.props.chosenMapUpData.imgHref} ref={this.refOnMap} onLoad={this.loadImage.bind(this)} />
				{this.state.arrayContactsMapMarker.map((person) => {
					return <img 
						style={{left: person.left, top: person.top}}
						className='mapup-marker'
						src='src/image/marker/QhzdX.png'
						onMouseMove={(e)=>this.changeInformationBlock(person.FIO, person.position, person.telephone, person.imgHref, e.clientX + 10, e.clientY + 10, true)}
						onMouseLeave={(e)=>this.changeInformationBlock(null, null, null, null, 0, 0, false)}
					/>;
				})}
				<InformationBlock dataMapInformation={this.state.dataMapInformation} />
			</div>
		);
	}
}