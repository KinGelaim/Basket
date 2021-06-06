const UseFetch = (url) => {
	const [loading, setLoading] = React.useState(true);
	const [products, setProducts] = React.useState([]);
	
	const getProducts = async () => {
		const response = await fetch(url);
		const products = await response.json();
		setProducts(products);
		setLoading(false);
	};
	
	React.useEffect(() => {
		getProducts();
	}, [url]);
	
	return { loading, products };
};