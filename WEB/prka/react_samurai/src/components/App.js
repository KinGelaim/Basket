function App(props) {
	return (
		<ReactRouterDOM.BrowserRouter>
			<div className='app-wrapper'>
				<Header />
				<SideBar />
				<ReactRouterDOM.Route exact path='/prka/react_samurai/'>
					<Content profilePage={props.state.profilePage} addPost={addPost} updatePostMessage={props.updatePostMessage} />
				</ReactRouterDOM.Route>
				<ReactRouterDOM.Route
					render={()=> <Content
						profilePage={props.state.profilePage}
						addPost={props.addPost}
						updatePostMessage={props.updatePostMessage}
					/>}
					path='/prka/react_samurai/profile'
				/>
				<ReactRouterDOM.Route component={Dialogs} path='/prka/react_samurai/dialogs' />
				<Footer />
			</div>
		</ReactRouterDOM.BrowserRouter>
	);
}