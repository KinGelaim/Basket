function MultipleReturns() {
	const [loading, setLoading] = React.useState(true);
	
	setTimeout(() => {
		setLoading(false);
	}, 5000);
	
	if(loading){
		return <h2>Loading...</h2>
	}
	
	return <h2>You are stupid!</h2>
}