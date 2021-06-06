// Массивы
const cars = ['Форд', '4x4', 'Порше'];
const fib = [1, 1, 2, 3, 5, 8, 13, 21];

cars.push('Мазда');
cars.unshift('БМВ');
console.log(cars);

const firstItem = cars.shift();
console.log('shift', firstItem);
console.log(cars);

const lastItem = cars.pop();
console.log('pop', lastItem);
console.log(cars);

cars.reverse();
console.log(cars);

const text = 'Привет JavaScript';
const reverseText = text.split('').reverse().join('');

let index = cars.indexOf('4x4');
cars[index] = 'Лада';
console.log(cars);

const people = [
	{name: 'Misha', age: 25},
	{name: 'Lera', age: 22},
	{name: 'Den', age: 24},
	{name: 'Maks', age: 19}
]
index = people.findIndex(function(person){
	return person.age === 22;
});
console.log(people[index]);

let person = people.find(function(person){
	return person.age === 22;
});
console.log(person);

person = people.find(person => person.age === 22);
console.log(person);

console.log(cars.includes('Мазда'));

const upperCaseCars = cars.map((car)=>{
	return car.toUpperCase();
});
console.log(upperCaseCars);

const pow2 = num => num ** 2;
const pow2FibSqrt = fib.map(pow2).map(Math.sqrt);
console.log(pow2FibSqrt);


const pow2Fib = fib.map(pow2);
console.log(pow2Fib);

const pow2FibFilter = pow2Fib.filter(num => {
	return num >= 20;
});
console.log('filter', pow2FibFilter);


const summAge = people
	.filter(person => person.age > 20)
	.reduce(function(acc, person){
		acc += person.age;
		return acc;
	}, 0);
console.log(summAge);