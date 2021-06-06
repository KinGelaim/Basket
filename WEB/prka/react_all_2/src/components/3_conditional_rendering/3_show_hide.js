function ShowHide() {
	const [show, setShow] = React.useState(false);
	
	return (
		<div>
			<button className='btn' onClick={()=>setShow(!show)}>Show/Hide</button>
			{show && <Item />}
		</div>
	)
}

const Item = () => {
	return (
		<div style={{marginTop: '2rem'}}>
			<h1>Window</h1>
		</div>
	);
};