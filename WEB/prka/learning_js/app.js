// ---------Prototype---------
const person = {
	name: 'Misha',
	age: 25,
	happy(){
		console.log('Happy!');
	}
};

const person2 = new Object({
	name: 'Misha',
	age: 25,
	happy(){
		console.log('Happy!');
	}
});

Object.prototype.sayHello = function() {
	console.log('Hello');
}

console.log(person);
person.sayHello();
console.log(person2);

const lena = Object.create(person);	//person стал прототипом для lena
console.log(lena);
lena.name = 'Elena';
console.log(lena);


// ---------Context---------
function hello() {
	console.log('Hello', this);
}

const person3 = {
	name: 'Misha',
	age: 25,
	sayHello: hello,
	sayHelloWindow: hello.bind(window),
	logInfo: function(phone) {
		console.group(`${this.name} info:`);
		console.log('Name: ' + this.name);
		console.log('Age: ' + this.age);
		console.log('Phone: ' + phone);
		console.groupEnd();
	}
};

hello();	// Конекстом является window
window.hello();	// Конекстом является window
person3.sayHelloWindow();	// Конекстом является window
person3.sayHello();	// Конекстом является person
person3.logInfo('8 (800) 555 35 35');
person3.logInfo.bind(lena, '43-43-43')();


// ---------Замыкание---------
function createCalcFunction(n){
	return function(){
		console.log(1000*n);
	};
};

const calc = createCalcFunction(3);
calc();



function logPerson() {
	console.log(`Person: ${this.name}, ${this.age}, ${this.job}`);
}

const personObject1 = {name: 'Михаил', age: 25, job: 'Backend'};
const personObject2 = {name: 'Лера', age: 22, job: 'Designer'};

function bind(context, fn){
	return function(...args){
		fn.apply(context, args);
	}
}

bind(personObject1, logPerson)();
bind(personObject2, logPerson)();


// ---------Асинхронность---------
console.log('Start');

console.log('Start 2');

window.setTimeout(()=>{
	console.log('After 3 seconds');
}, 3000);

console.log('End');

// Start, End, Inside (хоть и стоит 0, но пока он закинет в Web API, потом в очередь Callback и только потом в Stack)
console.log('Start');
window.setTimeout(()=>{
	console.log('Inside 0 timeout!');
}, 0);
console.log('End');


// ---------Промисы---------
window.setTimeout(()=>{
	console.log('Request data...');
	setTimeout(()=>{
		console.log('Preparing data...');

		const backendData = {
			server: 'aws',
			port: 2000,
			status: 'working'
		}
		
		setTimeout(()=>{
			backendData.modified = true;
			console.log('Data recived', backendData);
		}, 2000);
	}, 3000);
}, 3000);

// CallbackHELL =^_^=

const p = new Promise(function(resolve, reject){
	setTimeout(()=>{
		console.log('Request data 2...');
		resolve();
	}, 9000);
});

p.then(()=>{
	return new Promise(function(resolve, reject){
		setTimeout(()=>{
			console.log('Preparing data...');
			const backendData = {
				server: 'aws',
				port: 2000,
				status: 'working'
			}
			resolve(backendData);
		}, 2000);
	});
}).then(data => {
	return new Promise(function(resolve, reject){
		setTimeout(()=>{
			data.modified = true;
			resolve(data);
		}, 2000);
	});
}).then(clientData => {
	console.log('Data received ', clientData);
	clientData.fromPromise = true;
	return clientData;
}).then(data => {
	console.log('Modified', data);
})
.catch(err => console.error('Error: ', err))
.finally(()=>console.log('Finally'));


const sleep = ms => {
	return new Promise(resolve => {
		setTimeout(() => resolve(), ms);
	});	
};

sleep(14000).then(()=>console.log('Agter 14 seconds'));

Promise.all([sleep(2000), sleep(15000)]).then(()=>{
	console.log('All promise end');	// Только после выполнения всех промисов
});

Promise.race([sleep(16000), sleep(20000)]).then(()=>{
	console.log('Race promise');	// Как только выполнится 1
});


sleep(17000).then(()=> {


	// ---------Objects---------
	const newPerson = Object.create(
		{
			// Прототип
		},
		{
			name: {
				value: 'Misha',
				enumerable: true,	// Чтобы отобразить в for in
				writable: true,		// Чтобы можно было изменять поле
				configurable: true,	// Можно удалять ключ
			},
			birthYear: {
				value: 1996
			},
			age: {
				get() {
					return new Date().getFullYear() - this.birthYear;
				},
				set(value) {
					console.log('Set age ', value);
				}
			}
		}
	);

	console.log(newPerson);
	console.log(newPerson.age);

	
	// ---------Classes---------
	class Animal {
	
		//static type = 'ANIMAL';
	
		constructor(options) {
			this.name = options.name;
			this.age = options.age;
			this.hasTail = options.hasTail;
		}
		
		voice() {
			console.log('I am animal!');
		}
	}
	
	const animal = new Animal({
		name: 'Animal',
		age: 5,
		hasTail: true
	});
	
	console.log(animal);
	animal.voice();
	
	//console.log(Animal.type);
	
	class Cat extends Animal {
		constructor(options) {
			super(options);
			this.color = options.color;
		}
		
		// Перетерается
		voice() {
			super.voice();
			console.log('I am cat!');
		}
		
		get ageInfo() {
			return this.age * 7;
		}
		
		set ageInfo(newAge) {
			this.age = newAge;
		}
	}
	
	const cat = new Cat({
		name: 'Cat',
		age: 7,
		hasTail: true,
		color: 'black'
	});
	
	console.log(cat);
	cat.voice();
	console.log(cat.ageInfo);
	
	
	class Component {
		constructor(selector){
			this.$el = document.querySelector(selector);	// $ - часть дома обычно обозначают
		}
		
		hide() {
			this.$el.style.display = 'none';
		}
		
		show() {
			this.$el.style.display = 'block';
		}
	}
	
	class Box extends Component {
		constructor(options) {
			super(options.selector);
			this.$el.style.width = this.$el.style.height = options.size + "px";
			this.$el.style.backgroundColor = options.color;
		}
	}
	
	var box1 = new Box({
		selector: '#box1',
		size: 100,
		color: 'red'
	});
	
	console.log(box1);
	
	
	// ---------Assync, await---------
	const url = 'server.php';
	
	function fetchTodos() {
		console.log('Request data...');
		return sleep(2000).then(()=>{
			return fetch(url);	// Аналог Ajax, возвращающий Promis
		})
		.then(response => response.json());
	}
	
	fetchTodos()
		.then(data => {
			console.log('Data', data);
		})
		.catch(e => console.log('Error: ', e));
		
	// Теперь тоже самое, но с Asunc Await (прилетело из babel)
	async function fetchAsyncTodos() {
		console.log('Request async data...');
		try {
			await sleep(3000);	// Не перейдём к некст строчке, пока не выполнится
			const response = await fetch(url);
			const data = await response.json();
			console.log('Data', data);
		} catch (e) {
			console.error(e);
		}
	}
	
	fetchAsyncTodos();	// async, await - в последствии оборачивается в Promise (синтактический сахар просто)
	
	
	// ---------Proxy---------
	const unProxyObject = {
		name: 'Misha',
		age: 24
	}
	const op = new Proxy(unProxyObject, {
		get(target, prop) {
			console.log('Target', target);
			console.log('Prop', prop);
			if(!(prop in target)){
				return prop
					.split('_')
					.map(part => target[part])
					.join(' ');
			}
			return target[prop];
		},
		set(target, prop, value) {
			if(prop in targe){
				target[prop] = value;
			} else {
				throw new Error(`No ${prop} field in target`);
			}
		}
	});
	console.log('Name: ', op.name);
	console.log(op.name_age);
	try{
		op.age = 25;
		op.hasHouse = true;
	} catch(e){
		console.error('Error', e);
	}
	console.log(op);
	
	
	const unProxyFunction = text => `Log: ${text}`;
	console.log(unProxyFunction('asd'));
	
	const fp = new Proxy(unProxyFunction, {
		apply(target, thisArg, args){
			console.log('Calling fn...');
			return target.apply(thisArg, args).toUpperCase();
		}
	});
	
	
	class Person{
		constructor(name, age){
			this.name = name;
			this.age = age;
		}
	}
	
	const PersonProxy = new Proxy(Person, {
		construct(target, args){
			console.log('Create new person...');
			return new target(...args);
		}
	});
	
	const cp = new PersonProxy('Misha', 24);
	console.log(cp);
	
	
	// ---------Генераторы---------
	function* strGenerator(){
		yield 'H';
		yield 'e';
		yield 'l';
		yield 'l';
		yield 'o';
	}
	const str = strGenerator();
	console.log(str);
	console.log(str.next());
	console.log(str.next());
	console.log(str.next());
	console.log(str.next());
	console.log(str.next());
	console.log(str.next());
	
	function* numberGen(n = 10) {
		for(let i = 0; i < n; i++) {
			yield i;
		}
	}
	const number = numberGen(7);
	console.log(number.next());
	console.log(number.next());
	
	
	const iterator = {
		gen(n = 10) {
			let i = 0;
			
			return {
				next(){
					if(i < n) {
						return {
							value: i++,
							done: false
						}
					}
					return {
						value: void 0,
						done: true
					}
				}
			}
		}
	}
	const itr = iterator.gen(4);
	console.log(itr.next());
	console.log(itr.next());
	console.log(itr.next());
	console.log(itr.next());
	console.log(itr.next());
	
	
	for (let k of 'Hello') {
		console.log(k);
	}
	
	for(let k of strGenerator()){
		console.log(k);
	}
	
	
	// ---------Запросы к серверу (fetch, xhr, ajax)---------
	sleep(5000).then(()=> {
		console.log('requests on server:');
		const requestServerUsers = 'serverUsers.php';
		
		
		// XML HTTP REQUEST (xhr) (полная поддержка)
		let xhr = new XMLHttpRequest();
		xhr.open('GET', requestServerUsers);	//Открываем запрос
		//xhr.responseType = 'json';	// Сказать, что нужно распарсить в JSON полученные данные
		xhr.onload = () => {
			if (xhr.status >= 400)
				console.error('Error: ');
			console.log(xhr.response);
			console.log(JSON.parse(xhr.response));
		};
		xhr.onerror = () => {
			console.error('Error: ', xhr.response);
		};
		xhr.send();	//Отправка запроса
		
		// Универсальная функция
		function sendRequestXHR(method, url, data = null) {
			console.log(method + ' send request...');
			return new Promise((resolve, reject)=>{
				let xhr = new XMLHttpRequest();
				xhr.open(method, url);
				
				xhr.responseType = 'json';
				xhr.setRequestHeader('Content-Type', 'application/json');
				
				xhr.onload = () => {
					if (xhr.status >= 400)
						reject('Error: ');
					else
						resolve(xhr.response);
				};
				
				xhr.onerror = () => {
					reject('Error: ', xhr.response);
				};
				
				xhr.send(JSON.stringify(data));
			});
		}
		
		sendRequestXHR('GET', requestServerUsers)
			.then(data => console.log(data))
			.catch(err => console.log(err));
		
		let postData = {
			name: 'Mihail'
		};
		sendRequestXHR('POST', requestServerUsers, postData)
			.then(data => console.log(data))
			.catch(err => console.log(err));
		
		
		// Fetch (более свежий API браузера)
		function sendRequestFetch(method, url, data = null) {
			//return fetch(url)	// Возвращает промис
			
			//return fetch(url).then(response => {
			//	return response.text();
			//});
			
			return fetch(url, {
				method: method,
				body: JSON.stringify(data),
				headers: {
					'Content-Type': 'application/json'
				}
			}).then(response => {
				if (response.ok) {
					console.log('123');
					return response.json();
				}
				return response.json().then(error => {
					const e = new Error('Что-то пошло не так!');
					e.data = error;
					throw e;
				});
			});
		}
		
		//sendRequestFetch('GET', requestServerUsers)
		//	.then(data => console.log(data))
		//	.catch(err => console.log(err));
		
		postData = {
			name: 'Mihail'
		};
		//sendRequestFetch('POST', requestServerUsers, data)
		//	.then(data => console.log(data))
		//	.catch(err => console.log(err));
		

		// ---------Spread (...) и Rest---------
		sleep(3000).then(()=> {
			const citiesRussia = ['Нижний Тагил', 'Екатеринбург', 'Москва'];
			const citiesEurope = ['Париж', 'Берлин', 'Люксенбург'];
			
			console.log('Spread: ');
			console.log(citiesRussia);
			console.log(...citiesRussia);
			
			const allCities = [...citiesRussia, 'Новосибирск', ...citiesEurope];	// Клонирование массива и объединение массивов
			console.log(allCities);
			
			const citiesRussiaWithPopulation = {
				Moscow: 20,
				Kazan: 5,
				Novosibirsk: 3
			}
			const citiesEuropeWithPopulation = {
				Pris: 2,
				Berlin: 3,
				Lyks: 1
			}
			
			console.log(citiesRussiaWithPopulation);
			console.log({...citiesRussiaWithPopulation});	// Копирование объекта
			console.log({...citiesRussiaWithPopulation, ...citiesEuropeWithPopulation});	// Склеивание объектов
			
			console.log('Rest: ');
			function sum(a, b, ...rest) {	// Это rest
				console.log(rest);
				return a + b + rest.reduce((a, i) => a + i ,0);
			}
			const numbers = [1,2,3,4,5];
			console.log(sum(...numbers));	// Spread!!!
			
			const [n1, n2, n3, ...nOther] = numbers;
			console.log(n1, n2, n3, nOther);
			
			
			// ---------Деструктуризация---------
			function calcValues(a, b) {
				return [
					a + b,
					a - b,
					a * b,
					a / b
				]
			}
			
			const resultCalc = calcValues(42, 10);
			//const resultSumm = resultCalc[0];
			//const resultSub = resultCalc[1];
			const [resultSumm,,resultMult,...other] = resultCalc;
			console.log(resultSumm, resultMult, other);
			
			const {Moscow: moscowPopulation, Kazan, city = 'Нет такого поля!'} = citiesRussiaWithPopulation;
			console.log(moscowPopulation, Kazan, city);
			
			function logInformation({Moscow: city, NTagil: city2 = 'Тагила нет в объекте'}){
				console.log(city + ' ' + city2);
			}
			
			logInformation(citiesRussiaWithPopulation);
			
			
			// ---------LocalStorage---------	(больше, чем куки и куки летают с запросами, а стораге нет!)
			const myNumber = 24;
			
			localStorage.removeItem('number');
			console.log(localStorage.getItem('number'));
			localStorage.setItem('number', myNumber.toString());
			console.log(localStorage.getItem('number'));
			//localStorage.clear();
		});
	});
});