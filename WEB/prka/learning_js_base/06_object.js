// Объекты
const person = {
	name: 'Misha',
	age: 25,
	isProgrammer: true,
	languages: ['ru','en'],
	'complex key': 'Complex value',
	[1+3]: 'Computed key',
	happy() {
		console.log('Happy!');
	},
	info() {
		console.info('Имя: ', this.name);
	}
}

console.log(person);
console.log(person.name);
console.log(person['complex key']);
let key = 4;
console.log(person[key]);
person.happy();

person.age++;
person.languages.push('de');
person.hasAnimal = false;
console.log(person);

delete person.hasAnimal;
console.log(person);

// Деструкторизация
const {name = 'unknown', age: personAge, languages} = person;
console.log(name, personAge, languages);

for (let key in person) {
	if(person.hasOwnProperty(key)){	//Чтобы не бегать по прототипу
		console.log('key: ' + key);
		console.log('value: ' + person[key]);
	}
}

const keys = Object.keys(person);
keys.forEach((key)=>{
	console.log('key: ' + key);
	console.log('value: ' + person[key]);
});

// Context
person.info();

const logger = {
	keys() {
		console.log('Object keys:', Object.keys(this))
	},
	keysAndValues() {
		Object.keys(this).forEach(key => console.log('"'+key+'"', ':', this[key]));
		//Object.keys(this).forEach(function(key){console.log('"'+key+'"', ':', this[key])});			 // Ошибка, т.к. функция создаст свой контекст и this изменится
		//const self = this;
		//Object.keys(this).forEach(function(key){console.log('"'+key+'"', ':', self[key])});			 // Исправили
		//Object.keys(this).forEach(function(key){console.log('"'+key+'"', ':', this[key])}.bind(this)); // Исправили
	},
	withParameters(top = false, between = false, bottom = false) {
		if(top) {
			console.log('--------- Start ---------');
		}
		Object.keys(this).forEach((key,index,array) => {
			console.log('"'+key+'"', ':', this[key]);
			if(between && index !== array.length - 1) {
				console.log('-------------------');
			}
		});
		if(bottom) {
			console.log('--------- End ---------');
		}
	}
};

const bound = logger.keys.bind(person);	//Заменяем this
bound();
logger.keys();

logger.keys.call(person);
logger.keysAndValues.call(person);
logger.withParameters.call(person, true, true, true);
logger.withParameters.apply(person, [true, false, true]);