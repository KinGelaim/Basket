//import React from 'react'

const styles2 = {
	li: {
		display: 'flex',
		justifyContent: 'space-between',
		alignItems: 'center',
		padding: '.5rem 1rem',
		border: '1px solid #ccc',
		borderRadius: '4px',
		marginBottom: '.5rem'
	},
	input: {
		marginRight: '1rem'
	}
}

function TodoItem({todo, index, myOnChange}) {
	const {removeTodo} = React.useContext(Context);
	
	const classes = [];
	if(todo.completed){
		classes.push('done');
	}
	
	return (
		<li style={styles2.li}>
			<span className={classes.join(' ')}>
				<input
					type="checkbox"
					checked={todo.completed}
					style={styles2.input}
					onChange={()=>myOnChange(todo.id)}
				/>
				<strong>{index + 1}</strong>
				)&nbsp;
				{todo.title}
			</span>
			
			<button className='rm' onClick={()=>removeTodo(todo.id)}>&times;</button>
		</li>
	);
}

//export default function TodoItem