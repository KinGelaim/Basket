function Post(props) {
	return (
		<div className='post'>
			<h2 className='post__title'>{props.title}</h2>
			<span className='post__date'><i>{props.date}</i></span>
			<p className='post__text'>{props.text}</p>
		</div>
	);
}