const clickMeButton = document.querySelector('#click-me__button');
const resultBlock = document.querySelector('#result');


// Пример callback
clickMeButton.addEventListener('click', ()=>makeRequest('test'));

function makeRequest(text) {
	resultBlock.innerHTML += '<p>' + text + '</p>';
}



// --------- JQUERY ---------
$.ajax('server.php');

$.ajax('server.php?count=3', {
	success: function(data){
		data.forEach(el => {
			makeRequest(el.title);
		});
	}
});

// Работа с сервером
function getToast(count, successCallback) {
	$.ajax(`server.php?count=${count}`, {
		success: (data) => successCallback(data)
	});
}

// Работа с UI
function successGetToasts(data) {
	data.forEach(el => {
		makeRequest(el.title);
	});
}

// Основной код программы
getToast(2, successGetToasts);



// --------- PROMISE ---------
function getToastPromise(count) {
	const promise = $.ajax(`server.php?count=${count}`);
	return promise;
}

function needToast() {
	const promise = getToastPromise(2);
	promise.then(successGetToasts);
}

needToast();



// --------- AXIOS ---------
function getToasAxios(count) {
	axios.get(`server.php?count=${count}`)
		.then((response)=>{
			successGetToasts(response.data);
		});
}

getToasAxios(2);

//axios.post('serv', { a: 1, b: 2 });
//axios.delete('serv?id=1');