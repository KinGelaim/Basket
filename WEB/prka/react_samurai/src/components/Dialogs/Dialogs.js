function Dialogs() {
	let dialogsArray = [
		{id: 1, title: 'Andrey'},
		{id: 2, title: 'Lera'},
		{id: 3, title: 'Maksim'},
		{id: 4, title: 'Dariya'}
	];
	
	let messagesArray = [
		{id: 1, message: 'Hello!'},
		{id: 2, message: 'Hi!'},
		{id: 3, message: 'How are you?'},
		{id: 4, message: "I'm fine thank you!"}
	];

	return (
		<div className='dialogs'>
			<div className='dialogs-items'>
				{dialogsArray.map((person) => {
					return <DialogItem key={person.id} id={person.id} title={person.title} />
				})}
			</div>
			<div className='messages'>
				{messagesArray.map((message) => {
					return <Message key={message.id} message={message.message} />
				})}
			</div>
		</div>
	);
}