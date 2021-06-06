function FormComponents(props){
	return (
		<form onSubmit={props.handleSubmit}>
			<input
				type="text"
				value={props.data.name}
				name='name'
				placeholder="Имя"
				onChange={props.handleChange}
			/>
			<br/>
			<input
				type="text"
				value={props.data.surname}
				name='surname'
				placeholder="Фамилия"
				onChange={props.handleChange}
			/>
			<br/>
			<textarea
				value={"Текстик"}
				onChange={props.handleChange}
			/>
			<br/>
			<label>
				<input 
					type="checkbox"
					name="isFrendly"
					checked={props.data.isFrendly}
					onChange={props.handleChange}
				/> Ты дружелюбный?
			</label>
			<br/>
			<label>
				<input 
					type="radio"
					name="gender"
					value="male"
					checked={props.data.gender === "male"}
					onChange={props.handleChange}
				/> Мужик
			</label>
			<label>
				<input 
					type="radio"
					name="gender"
					value="female"
					checked={props.data.gender === "female"}
					onChange={props.handleChange}
				/> Женщина
			</label>
			{/* Тут комментарии */}
			<br/>
			<label>Любимый цвет: </label>
			<select
				value={props.data.favColor}
				onChange={props.handleChange}
				name="favColor"
			>
				<option></option>
				<option value="blue">Синий</option>
				<option value="green">Зелёный</option>
				<option value="red">Красный</option>
			</select>
			<h1>{props.data.surname} {props.data.name}</h1>
			<h2>Ваш любимый цвет: {props.data.favColor}</h2>
			<button>Отправить</button>
		</form>
	)
}