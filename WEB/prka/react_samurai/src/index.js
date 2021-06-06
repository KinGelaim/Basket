let state = {
	profilePage: {
		posts: [
			{id: 1, title: 'Mixa', date: '02.12.2020', text: 'Textik'},
			{id: 2, title: 'Title 2', date: '02.12.2020', text: 'Textik asd asd qw dwq'},
			{id: 3, title: 'Title 3', date: '07.12.2020', text: 'You are cool!'}
		],
		newPostMessage: ''
	}
}

function addPost() {
	state.profilePage.posts.push({id: new Date().getTime().toString(), title: 'Mixa', date: '08.12.2020', text: state.profilePage.newPostMessage});
	rerender();
}

function updatePostMessage(newTextMessage) {
	state.profilePage.newPostMessage = newTextMessage;
	rerender();
}

const rerender = () => {
	ReactDOM.render(
	  <App state={state} addPost={addPost} updatePostMessage={updatePostMessage} />,
	  document.getElementById('root')
	);
};

rerender();