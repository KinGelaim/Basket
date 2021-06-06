function SideBar() {
	return (
		<nav className='nav'>
			<div className='nav__item'>
				<ReactRouterDOM.NavLink to='/prka/react_samurai/profile'>Profile</ReactRouterDOM.NavLink>
			</div>
			<div className='nav__item'>
				<ReactRouterDOM.NavLink to='/prka/react_samurai/dialogs'>Messages</ReactRouterDOM.NavLink>
			</div>
			<div className='nav__item'>
				<ReactRouterDOM.NavLink to='/prka/react_samurai/news'>News</ReactRouterDOM.NavLink>
			</div>
			<div className='nav__item'>
				<ReactRouterDOM.NavLink to='/prka/react_samurai/music'>Music</ReactRouterDOM.NavLink>
			</div>
			<div className='nav__item'>
				<ReactRouterDOM.NavLink to='/prka/react_samurai/settings'>Settings</ReactRouterDOM.NavLink>
			</div>
		</nav>
	);
}