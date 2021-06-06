const InformationBlock = (props) => {
	let styleInformationBlock = {
		left: props.dataMapInformation.x,
		top: props.dataMapInformation.y,
		display: props.dataMapInformation.isVisible
	};
	return (
		<div className='map-info-block' style={styleInformationBlock}>
			<div className='map-info-block__info'>
				<h3>{props.dataMapInformation.FIO}</h3>
				<p>{props.dataMapInformation.position}</p>
				<p>Тел. {props.dataMapInformation.telephone}</p>
			</div>
			<img className='map-info-block__image' src={props.dataMapInformation.imgHref || 'http://localhost/phonebook/src/image/photo/no_photo.png'} />
		</div>
	);
};