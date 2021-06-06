// 1 - Функции
// Function Declaration
function hello(name) {
	console.log('Привет, ' + name + '!');
}

hello('Лера');

// Function Expression (нельзя обращаться до объявления функции)
const hello2 = function hello2(name) {
	console.log('Привет 2, ' + name + '!');
}

hello2('Лера');

console.log(typeof hello2);	// Напишу, что function, но такого типа даннахы нет в JS
console.dir(hello2);

// 2 - Анонимные функции
let counter = 1;
const interval = setInterval(function() {
	if(counter > 4)
		clearInterval(interval);
	console.log(counter++);
}, 1000);

// 3 - Стрелочные функции
const arrow = (name) => {
	console.log('Привет, ' + name);
};
arrow('Дэн');

const arrow2 = name => console.log('Привет, ' + name);
arrow2('Денчик');

// 4 - Параметры по умолчанию
const summ = (a,b=1) => a+b;
console.log(summ(41));

function summAll(...all){
	console.log(all);
}
summAll(1,2,3,4,7);

// 5 - Замыкание
function createMember(name){
	return function(lastName){
		console.log(name + lastName);
	}
}

const logWithLastName = createMember('Mihail');
console.log(logWithLastName('Kotkov'));
console.log(logWithLastName('asd'));