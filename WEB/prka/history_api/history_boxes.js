let boxes = Array.from(document.getElementsByClassName('box'));

function selectedBox(id) {
	boxes.forEach(b => {
		b.classList.toggle('selected', b.id === id);
	});
}

boxes.forEach( b => {
	let id = b.id;
	b.addEventListener('click', e => {
		history.pushState({id}, `Selected: ${id}`, `./selected=${id}`);	//data(state),title,url
		selectedBox(id);
	});
});

window.addEventListener('popstate', e => {
	if(e.state !== null)
		selectedBox(e.state.id);
	else
		selectedBox(null);
});

history.replaceState({id: null}, 'Default state', './');