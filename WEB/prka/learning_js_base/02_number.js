// 1 - Number
const num1 = 42;		// integer
const num2 = 42.42;		// float
const pow = 4e3;		// 
const hex = 0xA;		// 16
console.log(hex);

console.log('MAX_SAFE_INTEGER', Number.MAX_SAFE_INTEGER);
console.log(Math.pow(2,53) - 1);

console.log('MAX_VALUE', Number.MAX_VALUE);
console.log('MIN_VALUE', Number.MIN_VALUE);

console.log('POSITIVE_INFINITY', Number.POSITIVE_INFINITY);
console.log('NEGATIVE_INFINITY', Number.NEGATIVE_INFINITY);

console.log('1 / 0', 1 / 0);

console.log('NaN', Number.NaN);	//Not A Number
const weird = 2 / undefined;
console.log(isNaN(weird));

const stringInt = '40';
console.log(Number.parseInt(stringInt) + 2);
console.log(parseInt(stringInt) + 2);
console.log(Number(stringInt) + 2);
console.log(+stringInt + 2);

const stringFloat = '40.42';
console.log(Number.parseInt(stringFloat) + 2);
console.log(Number.parseFloat(stringFloat) + 2);

console.log(0.4 + 0.2);		//0.6000000000000001
console.log(+((0.4 + 0.2).toFixed(4)));

// 2 - BigInt (работает только с BigInt)
//console.log(typeof 100n);


// 3 - Math
console.log(Math.E);	// Экспонента
console.log(Math.PI);	// ПИ

console.log(Math.sqrt(25));
console.log(Math.pow(5, 4));

console.log(Math.abs(-42));
console.log(Math.max(42,21,13,-2, 404, 10));
console.log(Math.min(42,21,13,-2, 404, 10));

console.log(Math.floor(4.9));	// В меньшую
console.log(Math.ceil(4.1));	// В большую
console.log(Math.round(4.3));	// Правильное округление
console.log(Math.trunc(4.9));	// Отбрасывает дробную часть

console.log(Math.random());		// Рандомное число от 0 до 1

function getRandomBetween(min, max) {
	return Math.floor(Math.random() * (max - min + 1) + min);
}

console.log(getRandomBetween(10, 42));