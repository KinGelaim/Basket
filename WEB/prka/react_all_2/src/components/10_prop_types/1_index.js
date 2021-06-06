const PropTypes = () => {
	const url = 'src/components/10_prop_types/server.php';
	
	const { loading, products } = UseFetch(url);
	
	console.log(products);
	
	return (
		<div>
			<h2>{loading ? 'Loading...' : 'data'}</h2>
			{products.map((product) => {
				return <Product key={product.id} {...product} />
			})}
			<Product />
			<Product item={{name: 'Name'}} />
		</div>
	)
};