const Product = ({item}) => {
	const price = item.price && item.price.cost;
	return(
		<div className='item'>
			<h4>{item.name}</h4>
			<p>{price || 0}</p>
		</div>
	);
}

Product.propTypes = {
	item: PropTypes.object.isRequired
	//name: PropTypes.object.isRequired,
	//price: PropTypes.object.isRequired,
	//price.cost: PropTypes.number.isRequired
}

Product.defaultProps = {
	item: {},
	//name: 'Default name',
	//price.cost: 0
}