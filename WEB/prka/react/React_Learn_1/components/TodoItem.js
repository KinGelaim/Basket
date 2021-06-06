function TodoItem(props){
	//console.log(props)
	const completedStyle = {
		fontStyle: "italic",
		color: "#cdcdcd",
		textDecoration: "line-through"
	}
	
	return (
		//<div className="todo-item" style={{display: props.text ? "block" : "none"}}>
		<div className="todo-item" style={{display: !props.text && "none"}}>
			<input
				type="checkbox"
				checked={props.completed}
				//onChange={()=>console.log("Change!")}
				onChange={props.handleChange ? (event)=>props.handleChange(props.id) : ()=>console.log("Change!")}
			/>
			<p style={props.completed ? completedStyle : null}>{props.text}</p>
		</div>
	)
}