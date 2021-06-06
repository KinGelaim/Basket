// Работа с DOM

//window.alert('1');
//window.prompt('Как тебя зовут?');
//window.confirm('Согласен?');

const heading = document.getElementById('hello');
console.log(heading);
//console.dir(heading);

console.log(heading.textContent);

setTimeout(()=>{
	addStylesTo(heading, 'Changed from JS');
}, 2000);

function addStylesTo(node, text, color='red', fontSize) {
	node.textContent = text;
	node.style.color = color;
	node.style.textAlign = 'center';
	node.style.backgroundColor = 'black';
	node.style.padding = '2rem';
	// falsy: '', undefined, null, 0, false
	if(fontSize){
		node.style.fontSize = fontSize;
	}
}

//const heading2 = document.getElementsByTagName('h2');
//const heading2 = document.getElementsByClassName('h2-class');
const heading2 = document.querySelector('.h2-class');
console.log(heading2);

//const heading3 = heading2.nextElementSibling;
const heading3 = document.querySelectorAll('h2')[1];
console.log(heading3);

setTimeout(()=>{
	addStylesTo(heading3, 'Anotherik h2', 'purple', '3rem');
}, 3000);

// События
heading.onclick = () => {
	if(heading.style.color == 'red'){
		heading.style.color = 'yellow';
	}else{
		heading.style.color = 'red';
	}
};

heading3.addEventListener('dblclick', ()=>{
	if(heading3.style.backgroundColor == 'black'){
		heading3.style.backgroundColor = '#fff';
	}else{
		heading3.style.backgroundColor = 'black';
	}
});