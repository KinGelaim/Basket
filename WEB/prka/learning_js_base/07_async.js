// Асинхронность (в однопоточном языке :) )
// Event Loop

const timeout = setTimeout(()=>{
	console.log('Секунда');
}, 1000);

//clearTimeout(timeout);

const interval = setInterval(()=>{
	console.log('Каждые 2 секунды');
	clearInterval(interval);
}, 2000);


let delay = (callback, wait = 1000) => {
	setTimeout(callback, wait);
};

delay(()=>{
	console.log('Через 2 секунды!');
}, 2000);

// Промисы
delay = (wait = 1000) => {
	const promise = new Promise((resolve, reject)=>{
		setTimeout(()=>{
			resolve();
			//reject('Что-то пошло не так!');
		}, wait);
	});
	return promise;
};

delay(2500)
	.then(()=>{
		console.log('Через две с половиной секунды');
	})
	.catch((err)=>{
		console.log('Error: ', err);
	})
	.finally(()=>console.log('finally'));
	
const getData = () => new Promise(resolve => resolve([
	1, 1, 2, 3, 5
]));

getData().then(data => console.log(data));

async function asyncExample() {
	try {
		await delay(3000);
		const data = await getData();
		console.log(data);
	} catch(e) {
		console.log(e);
	} finally {
		console.log('Finally');
	}
}

asyncExample();