function show(output) {
	document.getElementById('output').innerHTML += '<p>' + output + '</p>';
}

show('location.href: ' + location.href);
show('location.search: ' + location.search);
show('location.hash: ' + location.hash);

show('history.length: ' + history.length);