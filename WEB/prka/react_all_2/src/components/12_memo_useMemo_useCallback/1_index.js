const calculateMostExpensive = (data) => {
	console.log('Hello');
	return data.reduce((total, item) => {
		const price = item.item.price;
		if(price >= total) {
			total = price;
		}
		return total;
	}, 0);
};

const UseMemoAndUseCallback = () => {
	const url = 'src/components/12_memo_useMemo_useCallback/server.php';
	
	const { products } = UseFetch(url);
	const [count, setCount] = React.useState(0);
	const [cart, setCart] = React.useState(0);
	
	const addToCart = React.useCallback(() => {
		setCart(cart + 1);
	}, [cart]);
	
	const mostExpensive = React.useMemo(() => calculateMostExpensive(products), [products]);
	
	return (
		<React.Fragment>
			<h1>Count: {count}</h1>
			<button className='btn' onClick={()=>setCount(count + 1)}>Click me!</button>
			<h1 style={{ marginTop: '3rem' }}>Cart: {cart}</h1>
			<h1>Most Expensive: { mostExpensive }</h1>
			<BigList products={products} addToCart={addToCart}/>
		</React.Fragment>
	);
};

const BigList = React.memo(({ products, addToCart }) => {
	React.useEffect(()=>{
		console.log('Effect from BigList');
	});
	return (
		<section className='products'>
			{products.map((product) => {
				return <SingleProduct key={product.id} {...product} addToCart={addToCart}></SingleProduct>
			})}
		</section>
	);
});

const SingleProduct = ({ item, addToCart }) => {
	let {name, price} = item;
	
	React.useEffect(()=>{
		console.count('Effect from SingleProduct');
	});
	
	return (
		<article className='product'>
			<h4>{name}</h4>
			<p style={{marginBottom: '0.4rem'}}>{price}</p>
			<button className='btn' onClick={addToCart} style={{marginTop: '0', marginBottom: '2rem'}}>Add Cart</button>
		</article>
	);
};