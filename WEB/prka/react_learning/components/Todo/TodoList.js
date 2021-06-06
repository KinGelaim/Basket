//import React from 'react'

const styles = {
	ul: {
		listStyle: 'none',
		margin: 0,
		padding: 0
	}
}

//export default function TodoList(){return (<ul><li>1</li><li>2</li></ul>); }

function TodoList(props) {
	return (
		<ul style={styles.ul}>
			{ props.todos.map((todo, index) => {
				return (
					<TodoItem
						todo={todo}
						key={todo.id}
						index={index}
						myOnChange={props.onToggle}
					/>
				)
			})}
		</ul>
	);
}