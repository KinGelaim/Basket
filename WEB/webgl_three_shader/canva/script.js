var 
	canv = document.getElementById('canva'),
	ctx = canv.getContext('2d'),
	isMouseDown = false,
	coords = [];

canv.width = window.innerWidth;
canv.height = window.innerHeight;

// Код

var radius = 10;
ctx.lineWidth = radius * 2;

canv.addEventListener('mousedown', function(){
	isMouseDown = true;
});

canv.addEventListener('mousemove', function(e){
	if(isMouseDown){
		coords.push([e.clientX, e.clientY]);
	
		ctx.lineTo(e.clientX, e.clientY);
		ctx.stroke();
		
		ctx.beginPath();
		ctx.arc(e.clientX, e.clientY, radius, 0, Math.PI * 2);
		ctx.fill();
		
		ctx.beginPath();
		ctx.moveTo(e.clientX, e.clientY);
	}
});

canv.addEventListener('mouseup', function(){
	isMouseDown = false;
	ctx.beginPath();
	coords.push('mouseup');
});

function save(){
	localStorage.setItem('coords', JSON.stringify(coords));
}

function reply(){
	var timer = setInterval(function(){
		if(!coords.length)
		{
			clearInterval(timer);
			ctx.beginPath();
			return;
		}
		
		var crd = coords.shift(), e = {
			clientX: crd['0'],
			clientY: crd['1']
		};
		
		ctx.lineTo(e.clientX, e.clientY);
		ctx.stroke();
		
		ctx.beginPath();
		ctx.arc(e.clientX, e.clientY, radius, 0, Math.PI * 2);
		ctx.fill();
		
		ctx.beginPath();
		ctx.moveTo(e.clientX, e.clientY);
	}, 20);
}

function clear(){
	ctx.fillStyle = 'white';
	ctx.fillRect(0, 0, canv.width, canv.height);
	
	ctx.beginPath();
	ctx.fillStyle = 'black';
}

document.addEventListener('keydown', function(e){
	// Клавиша S
	if(e.keyCode == 83){
		save();
		console.log('Сохранено');
	}
	
	// Клавиша R
	if(e.keyCode == 82){
		clear();
		console.log('Повторение');
		coords = JSON.parse(localStorage.getItem('coords'));
		reply();
	}
	
	// Клавиша C
	if(e.keyCode == 67){
		clear();
		console.log('Очищено');
	}
});


// Всякое баловство

//testAnimation();
//testDraw();

function testAnimation(){
	//ctx.fillStyle = '#000000';
	
	// Лучше request animation frame
	setInterval(function(){
		ctx.fillStyle = 'white';
		ctx.fillRect(0, 0, canva.width, canva.height);
		ctx.fillStyle = 'magenta';	// Цвет заливки
		ctx.fillRect(x, 50, 200, 300);	// Прямоугольник с заливкой
		x++;
	}, 20);
}

function testDraw(){
	var x = 50;
	// Прямоугольник не закрашенный
	ctx.strokeStyle = 'red';
	ctx.lineWidth = 10;
	ctx.strokeRect(x, 50, 300, 200);

	// Круг
	ctx.arc(canv.width  / 2, canv.height / 2, 100, 0, Math.PI * 2, false);
	ctx.stroke();
	ctx.fill();

	// Маштабируем
	ctx.scale(2, 2);
	ctx.rotate(0.1);	// В радианах поворот

	// Гоняем линию
	ctx.beginPath();
	ctx.moveTo(700, 50);
	ctx.lineTo(725, 100);
	ctx.lineTo(525, 100);
	ctx.stroke();

	ctx.scale(1, 1);
	ctx.rotate(-0.1);

	// Текст
	ctx.textAlign = 'center';
	ctx.font = '20px Roboto';

	var grad = ctx.createLinearGradient(0, 0, 500, 0);

	grad.addColorStop('0', 'magenta');
	grad.addColorStop('0.50', 'blue');
	grad.addColorStop('1', 'red');

	ctx.fillStyle = grad;

	ctx.fillText('Hello World', 50, 50);
}