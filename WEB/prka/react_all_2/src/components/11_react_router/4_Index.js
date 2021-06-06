const ReactRouterIndex = () => {
	return (
		<ReactRouterDOM.BrowserRouter>
			<ReactRouterNavbar />
			<ReactRouterDOM.Switch>
				<ReactRouterDOM.Route exact path='/prka/react_all_2/'>
					<ReactRouterHome />
				</ReactRouterDOM.Route>
				<ReactRouterDOM.Route path='/prka/react_all_2/about'>
					<ReactRouterAbout />
				</ReactRouterDOM.Route>
				<ReactRouterDOM.Route path='/prka/react_all_2/people'>
					<ReactRouterPeople />
				</ReactRouterDOM.Route>
				<ReactRouterDOM.Route path='/prka/react_all_2/person/:id' children={<ReactRouterPerson />}></ReactRouterDOM.Route>
				<ReactRouterDOM.Route path='*'>
					<ReactRouterError />
				</ReactRouterDOM.Route>
			</ReactRouterDOM.Switch>
		</ReactRouterDOM.BrowserRouter>
	);
};