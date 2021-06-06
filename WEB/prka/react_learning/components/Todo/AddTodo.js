function AddTodo({onCreate}) {
	const input = useInputValue('');
	
	function submitHandler(event) {
		event.preventDefault();
		if(input.value().trim()) {
			onCreate(input.value());
			input.clear();
		}
	}
	
	function useInputValue(defaultValue = '') {
		const [value, setValue] = React.useState(defaultValue);
		return {
			bind: {
				value,
				onChange: event => setValue(event.target.value)
			},
			clear: () => setValue(''),
			value: () => value
		}
	}
	
	return (
		<form style={{marginBottom: '1rem'}} onSubmit={submitHandler}>
			<input {...input.bind} />
			<button type="submit">Add todo</button>
		</form>
	);
}