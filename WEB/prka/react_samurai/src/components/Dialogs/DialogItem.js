const DialogItem = (props) => {
	return (
		<div className='dialogs-items__item'>
			<ReactRouterDOM.NavLink to={'/prka/react_samurai/dialogs/'+props.id}>{props.title}</ReactRouterDOM.NavLink>
		</div>
	);
};