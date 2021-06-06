const Table = (props) => {	
	let newFilterContractsData = props.filterValue ? props.contractsData.filter((contract)=>contract.FIO.indexOf(props.filterValue) !== -1 || contract.telephone.indexOf(props.filterValue) !== -1 || contract.mobile.indexOf(props.filterValue) !== -1 || contract.email.indexOf(props.filterValue) !== -1 || contract.position.indexOf(props.filterValue) !== -1 || contract.department.indexOf(props.filterValue) !== -1) : props.contractsData;
	
	function sortContractsData(nameHeader) {
		let newContractsData = [...props.contractsData];
		if(props.oldHeaderNameSort !== nameHeader) {
			newContractsData.sort((x,y) => x[nameHeader] > y[nameHeader]);
			props.setOldHeaderNameSort(nameHeader);
		}
		else {
			newContractsData.sort((x,y) => x[nameHeader] < y[nameHeader]);
			props.setOldHeaderNameSort('');
		}
		props.setNewContractsData(newContractsData);
	}
	
	const [hoverColumn, setHoverColumn] = React.useState('');
	
	const [dataImageBlock, setDataImageBlock] = React.useState({xPositionMouse: 0, yPositionMouse: 0, isVisible: 'none', imgHref: ''});
	
	function changeImgBlock(event, id, isVisible = true) {
		if(isVisible) {
			let imgHref = newFilterContractsData.find((c)=>c.id === id).imgHref;
			if(imgHref) {
				setDataImageBlock({
					xPositionMouse: event.clientX + 10,
					yPositionMouse: event.clientY + 10,
					isVisible: 'block',
					imgHref: imgHref
				});
			}
		}else{
				setDataImageBlock({
					xPositionMouse: 0,
					yPositionMouse: 0,
					isVisible: 'none',
					imgHref: ''
				});
		}
	}
	
	const [dataMapContainer, setDataMapContainer] = React.useState({ title: '', imgHref: '', x: 0, y: 0, isVisible: false });
	
	function changeModal(title, imgHref, x, y, isVisible) {
		if(isVisible) {
			setDataMapContainer({ title, imgHref, x, y, isVisible });
		}else {
			setDataMapContainer({ title: '', imgHref: '', x: 0, y: 0, isVisible: false });
		}
	}
	
	return (
		<React.Fragment>
			<ImageBlock dataImageBlock={dataImageBlock} />
			<div className='block-contracts-table'>
				<table className='contracts__table'>
					<thead>
						<tr>
							<th className='contracts__table-th' onClick={()=>sortContractsData('FIO')}>FIO</th>
							<th className='contracts__table-th' onClick={()=>sortContractsData('telephone')}>Telephone</th>
							<th className='contracts__table-th' onClick={()=>sortContractsData('mobile')}>Mobile</th>
							<th className='contracts__table-th' onClick={()=>sortContractsData('email')}>E-Mail</th>
							<th className='contracts__table-th' onClick={()=>sortContractsData('position')}>Position</th>
							<th className='contracts__table-th' onClick={()=>sortContractsData('department')}>Department</th>
						</tr>
					</thead>
					<tbody onMouseLeave={()=>setHoverColumn('')}>
						{newFilterContractsData.map((contract) => {
							return <RowContract key={contract.id} {...contract} hoverColumn={hoverColumn} setHoverColumn={setHoverColumn} changeImgBlock={changeImgBlock} changeModal={changeModal} />;
						})}
					</tbody>
				</table>
			</div>
			{dataMapContainer.isVisible && <MapContainer dataMapContainer={dataMapContainer} changeModal={changeModal} />}
		</React.Fragment>
	);
};