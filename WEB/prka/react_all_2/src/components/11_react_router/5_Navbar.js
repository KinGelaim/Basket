const ReactRouterNavbar = () => {
	return (
		<nav>
			<ul>
				<li>
					<ReactRouterDOM.Link to='/prka/react_all_2/'>Home</ReactRouterDOM.Link>
				</li>
				<li>
					<ReactRouterDOM.Link to='/prka/react_all_2/about'>About</ReactRouterDOM.Link>
				</li>
				<li>
					<ReactRouterDOM.Link to='/prka/react_all_2/people'>People</ReactRouterDOM.Link>
				</li>
			</ul>
		</nav>
	);
};