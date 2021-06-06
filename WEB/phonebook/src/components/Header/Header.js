const Header = () => {
	return (
		<header className='header'>
			<nav>
				<ul className='header-left'>
					<li className='header-left__item'>
						<ReactRouterDOM.Link to='/phonebook/'>Home</ReactRouterDOM.Link>
					</li>
					<li className='header-left__item'>
						<ReactRouterDOM.Link to='/phonebook/contacts'>Contacts</ReactRouterDOM.Link>
					</li>
					<li className='header-left__item'>
						<ReactRouterDOM.Link to='/phonebook/map'>Map</ReactRouterDOM.Link>
					</li>
				</ul>
				<ul>
					<li className='header-right__item'>
						<ReactRouterDOM.Link to='/phonebook/login'>Login</ReactRouterDOM.Link>
					</li>
				</ul>
			</nav>
		</header>
	);
};