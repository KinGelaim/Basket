// String
const name = 'Mihail';
console.log(typeof name);

const age = 25;
const out = 'Имя:' + name + ' возраст:' + getAge();
console.log(out);

let output = `Имя:${name}
Возраст:${getAge() > 0 ? getAge() : 'что-то пошло не так!'}
`

console.log(output);

function getAge() {
	return age;
}

console.log(name.length);
console.log(name.toUpperCase());
console.log(name.toLowerCase());
console.log(name.charAt(2));
console.log(name.indexOf('hai'));
console.log(name.startsWith('Mi'));
console.log(name.endsWith('!'));
console.log(name.repeat(3));
console.log('   password'.trim());

function logPerson(s,name,age) {
	console.log(s,name,age);
	if(age < 0) {
		age = 'Ещё не родился';
	}
	return `${s[0]}${name}${s[1]}${age}`;
}

output = logPerson`Имя: ${name}, Возраст: ${age}`
console.log(output);