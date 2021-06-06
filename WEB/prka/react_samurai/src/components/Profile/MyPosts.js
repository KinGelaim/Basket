function MyPosts({posts,newPostMessage,updatePostMessage,addPost}) {
	let postsArray = posts;

	let refNewPostElement = React.createRef();
	
	function changePostMessage() {
		newPostMessage = refNewPostElement.current.value;
		updatePostMessage(newPostMessage);
	}
	
	return (
		<div className='block-posts'>
			<div>
				<h2>My posts</h2>
			</div>
			<div>
				<textarea ref={refNewPostElement} value={newPostMessage} onChange={changePostMessage}></textarea>
			</div>
			<div>
				<button onClick={addPost}>Add post</button>
			</div>
			{postsArray.map((post) => {
				return <Post key={post.id} title={post.title} date={post.date} text={post.text} />;
			})}
		</div>
	);
}