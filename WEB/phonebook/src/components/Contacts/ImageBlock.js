const ImageBlock = (props) => {
	let styleImageBlock = {
		left: props.dataImageBlock.xPositionMouse,
		top: props.dataImageBlock.yPositionMouse,
		display: props.dataImageBlock.isVisible
	};
	return (
		<div className='user-photo' style={styleImageBlock}>
			<img className='user-photo__image' src={props.dataImageBlock.imgHref}/>
		</div>
	);
};