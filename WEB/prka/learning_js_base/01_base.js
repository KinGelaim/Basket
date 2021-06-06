/*
Немного истории
Компы соединялись между собой и был разработан HTML, CSS
HTML поддерживал текст, ссылки и картинки
CSS разумеется был слабеньким (лучшее это hover)
И всё это перекидывалось по протоколу HTTP
Всё очень здоровски, но сплошная статика
Разумеется захотелось некой динамики и нужно было сделать некий язык
Появился LifeScript -> который ради хайпа переименовался -> JavaScript
Майкрософт сказала, что у неё есть супер браузер IE и поэтому она отказывается JavaScript
Создалась комиссия EcmaScript, которая что-то типо уравнивала разработку майков и java
(Поэтому IE всегда был в говне)
Где-то в 2000+ создаётся jQuery (это библиотечка, которая обеспечивала работу со всеми версиями java в том числе и IE)
В 2009 году один челик разработал NodeJS (взял ядро браузера, разобрал его, выкинул ненужное, накинул обёртку, дописал несколько функций и получили nodeJS)
В 2015 году майкрософт поняли, что обделались по крупному и согласились с JavaScript
И появился тот самый ES6 (последняя версия JS, самое крупное обновление)
(Появились классы, всякие другие штуковины, JSеры пускали пену изо рта и били друг другу рожи, ибо в мире нагрянули перемены, которые ознаменовали конец света и тд и тп)
Таким образом, одна из главных сфер применение JS - WEB, просто взлетела. Разработка стала не только функциональнее и мощнее, но и проще
А за счёт NodeJS появилась возможность писать бэкенд на серверах, мобильные приложение и даже декстопки
*/

// ****************************************************************
// ***        1 - Переменные, начало типы данных (~8)           ***
// ****************************************************************
var name = 'Mihail'; 				// variable (глобальная переменная, добавляет к контексту window)
console.log(typeof(window.name));

const lastName = 'Kotkov';			// constanta

let age = 24;						// Локальная переменная (не попадает в глобальный контекст, живёт в своей зоне видимости {}, позволяет избегать замыкания)
console.log(typeof(window.age));

// lastName = 'asd';	// Нельзя, поэтому она и константа
name = 'Miha';
age = 25;
console.log(age);

const isProgrammer = true;

// string, number, boolean

// ****************************************************************
// ***                    2 - Мутирование                       ***
// ****************************************************************
console.log('Имя человека: ' + name + ', возраст: ' + age);		//age.toString()

// Важный момент! Такие функции как console.log или alert - отсутствуют в языке JavaScript. Это часть API браузера, т.е. работает в WEB

//const d = prompt('Введите что-нибудь');	//Окошко для считывание
//console.log(d);

// ****************************************************************
// ***                    3 - Операторы                         ***
// ****************************************************************
let currentYear = 2020;
let birthYear = 1996;
age = currentYear - birthYear;
console.log(age);

const a = 10;
const b = 6;

console.log(a+b);
console.log(a-b);
console.log(a*b);
console.log(a/b);
console.log(a^b);
console.log(a%b);
console.log(currentYear++);
console.log(currentYear);
console.log(--currentYear);
currentYear += 3;
console.log(currentYear);

// ****************************************************************
// ***                    4 - Типы данных                       ***
// ****************************************************************

// Примитивные типы данных 
console.log(typeof name);	// string
console.log(typeof(isProgrammer));	// boolean
console.log(typeof a);	// number
let x;
console.log(typeof x);	// undefined
console.log(typeof null);	// null

// ****************************************************************
// ***              5 - Приоритет операторов                    ***
// ****************************************************************
let fullAge = 25;
// > < >= <= == === !=
// Приоритет операций можно посмотреть в mdn
const isFullAge = currentYear - birthYear >= fullAge;
console.log(isFullAge);

// ****************************************************************
// ***                6 - Условные операторы                    ***
// ****************************************************************
let status = 'pending';	//ready, fail, pending
if(status === 'ready') {
	console.log('Всё готово!');
}else if(status === 'pending') {
	console.log('В процессе разработки!');
}else {
	console.log('Что-то пошло не так!');
}

let num1 = 42;	//number
let num2 = '42';	//string
console.log(num1==num2);	//Приводит к одному типу данных
console.log(num1===num2);	//Проверяет в том типе, в котором записаны

let isReady = true;
isReady ? console.log('Всё готово!') : console.log('Что-то не готово!');

// ****************************************************************
// ***                   7 - Булевая логика                     ***
// ****************************************************************
let a1 = true && true;		//true
let a2 = true && false;		//false
let a3 = false && true;		//false
let a4 = false && false;	//false
let a5 = true || true;		//true
let a6 = true || false;		//true
let a7 = false || true;		//true
let a8 = false || false;	//false
let a9 = !true;				//false
let a10 = !!true;			//true


// ****************************************************************
// ***      8 - Функции и недостаток динамической типизации     ***
// ****************************************************************
function calculateAge(year) {
	return 2020 - year;
}

let myAge = calculateAge(1996);
console.log(myAge);
myAge = calculateAge(1995);
console.log(myAge);

function logInfo(name, year) {
	const age = calculateAge(year);
	if(age > 0) {
		console.log('Это ' + name + ' и сейчас имеет возраст ' + age);
	} else {
		console.log('Что-то явно пошло не так!');
	}
}

logInfo('Лера', 1998);
logInfo('Максим', 2025);

// Недостаток
const firstNumber = 24;
const secondNumber = '78';	// Эту переменную прислали

// *** Тут много кода ***

Summ(25,11);
Summ('Mihail ','Kotkov');
Summ(firstNumber, secondNumber);

function Summ(a, b){
	console.log(a+b);
}

// ****************************************************************
// ***                      9 - Массивы                         ***
// ****************************************************************
let cars = new Array('Машинка','Тачка','Крутая тачка');
console.log(cars);

cars = ['Машинка','Тачка','Крутая тачка'];
console.log(cars);

console.log(cars[1]);
cars[1] = 'Тачила';
console.log(cars[1]);

cars[cars.length] = 'Супер крутая тачка';	//Новый элемент
console.log(cars);

// ****************************************************************
// ***                       10 - Циклы                         ***
// ****************************************************************
for(let i=0; i < cars.length; i++) {
	console.log(cars[i]);
}
console.log('forof: ');
for(let car of cars) {
	console.log(car);
}

// ****************************************************************
// ***                     11 - Объекты                         ***
// ****************************************************************
let person = new Object({
	firstName: 'Mihail',
	lastName: 'Kotkov'
});

person = {
	firstName: 'Mihail',
	lastName: 'Kotkov',
	year: 1996,
	languages: ['ru', 'en', 'de'],
	isProgrammer: false,
	happy: function() {
		console.log('happy');
	}
};

console.log(person);
console.log(person.firstName);
console.log(person['lastName']);

let key = 'languages';
console.log(person[key]);
person.isProgrammer = true;
console.log(person.isProgrammer);

console.log(person.hasAnimal);
person.hasAnimal = false;
console.log(person.hasAnimal);



// ****************************************************************
// *** Как классы объявлялись раньше (через расширение объекта) ***
// ***    и достоинство JavaScript (поля не нужно объявлять)    ***
// ****************************************************************

const asd = {};
asd.name = 'asd';
asd.func = function(a,b){
	return a+b;
}
console.log(asd.name);
console.log(asd.func(4,5));