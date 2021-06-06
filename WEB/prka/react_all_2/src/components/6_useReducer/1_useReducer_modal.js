const UseReducerModal = ({ modalContent, closeModal }) => {
	React.useEffect(() => {
		setTimeout(() => {
			closeModal();
		}, 3000);
	});
	return (
		<div className='modal'>
			<p>{modalContent}</p>
		</div>
	);
};