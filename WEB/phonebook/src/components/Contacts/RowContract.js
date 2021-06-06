const RowContract = ({id, FIO, telephone, mobile, email, position, department, map, hoverColumn, setHoverColumn, changeImgBlock, changeModal}) => {	
	function mouseEnterColumn(column){
		if(hoverColumn !== column)
			setHoverColumn(column);
	}
	
	return (
		<tr className='contracts__table-row'>
			<td className={'contracts__table-td' + (hoverColumn==='column1' ? ' hover-column' : '') + (map ? map.imgHref && ' contracts_show_map' : '')} onClick={()=>map && map.imgHref ? changeModal(map.title, map.imgHref, map.x, map.y, true) : null} onMouseEnter={()=>mouseEnterColumn('column1')} onMouseMove={(e)=>changeImgBlock(e, id)} onMouseLeave={()=>changeImgBlock(null, null, false)}>{FIO}</td>
			<td className={'contracts__table-td' + (hoverColumn==='column2' ? ' hover-column' : '')} onMouseEnter={()=>mouseEnterColumn('column2')}>{telephone}</td>
			<td className={'contracts__table-td' + (hoverColumn==='column3' ? ' hover-column' : '')} onMouseEnter={()=>mouseEnterColumn('column3')}>{mobile}</td>
			<td className={'contracts__table-td' + (hoverColumn==='column4' ? ' hover-column' : '')} onMouseEnter={()=>mouseEnterColumn('column4')}>{email}</td>
			<td className={'contracts__table-td' + (hoverColumn==='column5' ? ' hover-column' : '')} onMouseEnter={()=>mouseEnterColumn('column5')}>{position}</td>
			<td className={'contracts__table-td' + (hoverColumn==='column6' ? ' hover-column' : '')} onMouseEnter={()=>mouseEnterColumn('column6')}>{department}</td>
		</tr>
	);
};