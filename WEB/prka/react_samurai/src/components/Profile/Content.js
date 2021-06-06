function Content(props) {
	return (
		<div className='content'>
			<ProfileInfo />			
			<MyPosts posts={props.profilePage.posts} newPostMessage={props.profilePage.newPostMessage} addPost={props.addPost} updatePostMessage={props.updatePostMessage} />
		</div>
	);
}