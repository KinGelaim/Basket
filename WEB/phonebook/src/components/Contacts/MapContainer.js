const MapContainer = ({dataMapContainer, changeModal}) => {
	let [styleMapMarker, setStyleMapMarker] = React.useState({ left: dataMapContainer.x,top: dataMapContainer.y });
	
	let refOnMap = React.createRef();
	function loadImage() {
		setStyleMapMarker({
			left: dataMapContainer.x + refOnMap.current.offsetLeft,
			top: dataMapContainer.y + refOnMap.current.offsetTop
		});
	}
	
	return (
		<div className='modal-container' onClick={()=>changeModal(null, null, null, null, false)}>
			<span onClick={()=>changeModal(null, null, null, null, false)} className='close-modal'>×</span>
			<h2 className='modal-title'>{dataMapContainer.title}</h2>
			<img className='map-image' src={dataMapContainer.imgHref} ref={refOnMap} onLoad={loadImage} />
			<img style={styleMapMarker} className='map-marker' src='http://localhost/phonebook/src/image/marker/QhzdX.png' />
		</div>
	);
};