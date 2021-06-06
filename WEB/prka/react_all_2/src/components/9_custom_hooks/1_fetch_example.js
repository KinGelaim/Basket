const FetchExample = () => {
	const url = 'src/components/9_custom_hooks/server.php';
	
	const { loading, products } = UseFetch(url);
	
	console.log(products);
	
	return (
		<div>
			<h2>{loading ? 'Loading...' : 'data'}</h2>
			{products.map((product) => {
				return (
					<div className='item' key={product.id}>
						<h4>{product.item.name}</h4>
						<p>{product.item.price.cost}</p>
					</div>
				);
			})}
		</div>
	)
};