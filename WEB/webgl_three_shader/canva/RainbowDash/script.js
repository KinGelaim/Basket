var colors = ["#000000", "#0000AA", "#00AA00", "#00AAAA", "#AA0000", "#AA00AA", "#AA5500", "#AAAAAA", 
"#555555", "#5555FF", "#55FF55", "#55FFFF", "#FF5555", "#FF55FF", "#FFFF55", "#FFFFFF"];
function setcolor(colorIndex)
{
	ctx.strokeStyle = colors[colorIndex];
}
function line(x1, y1, x2, y2)
{
	ctx.beginPath();
	ctx.moveTo(x1, y1);
	ctx.lineTo(x2, y2);
	ctx.stroke();
	ctx.closePath();
}
function setlinestyle(p, t, width)
{
	ctx.lineWidth = width;
}

function setfillstyle(t, colorIndex)
{
	ctx.fillStyle = colors[colorIndex];
}

function ellipse(x, y, st, end, xrad, yrad)
{
	ctx.save();
	ctx.translate(x, y);
	ctx.scale(xrad, -yrad);
	ctx.beginPath();
	ctx.arc(0, 0, 1, st * Math.PI / 180.0, end * Math.PI / 180.0, false);	
	ctx.restore();
	ctx.stroke();
}

function fillellipse(x, y, xrad, yrad)
{
	ctx.save();
	ctx.translate(x, y);
	ctx.scale(xrad, yrad);
	ctx.beginPath();
	ctx.arc(0, 0, 1, 0, Math.PI * 2, true);
	ctx.fill();
	ctx.closePath();
	ctx.restore();
}

const canvas = document.getElementById('canvas');
canvas.width = 700;
canvas.height = 500;
const ctx = canvas.getContext('2d');

//setInterval(function(){
	Draw();
//}, 2000);

function Draw(){
	// Фон
	ctx.fillStyle = '#000000';
	ctx.fillRect(0,0,canvas.width,canvas.height);

	// Молния
	setcolor (1);
	line (330,249,335,231);
	setcolor (14);
	line (337,232,332,246);
	setcolor (4);
	line (330,249,340,232);
	line (340,232,342,237);
	line (342,237,344,235);

	// Хвост
	setcolor (11);
	ellipse (275,205,95,175,80,60);
	ellipse (226,203,95,175,30,30);
	ellipse (228,310,99,170,25,140);
	ellipse (178,273,279,350,25,70);
	ellipse (178,273,279,330,39,70);
	ellipse (143,291,279,350,70,100);
	ellipse (155,291,260,310,75,100);
	ellipse (190,200,270,300,30,190);
	ellipse (189,290,275,340,60,100);
	ellipse (218,340,300,20,30,50);
	ellipse (220,280,285,352,55,107);
	ellipse (280,289,190,280,5,25);
	ellipse (310,340,99,170,30,150);
	setcolor (4);
	ellipse (295,338,99,160,32,150);
	ellipse (236,243,285,340,30,130);
	setcolor (12);
	ellipse (280,338,95,160,32,150);
	ellipse (221,243,320,340,30,130);
	setcolor (14);
	ellipse (267,337,97,160,32,160);
	ellipse (209,233,285,340,30,150);
	setcolor (2);
	ellipse (290,215,90,170,53,60);
	ellipse (199,220,284,340,32,150);
	line (229,272,237,205);
	setcolor (5);
	ellipse (280,215,95,170,55,66);
	ellipse (206,146,284,340,20,170);

	// Грива (низ)
	setcolor (11);
	ellipse (425,193,270,0,10,50);
	line (426,215,425,243);
	ellipse (386,209,280,350,40,50);
	ellipse (389,209,280,350,13,50);
	ellipse (432,220,100,180,30,45);
	setcolor (5);
	ellipse (399,205,280,330,12,50);
	ellipse (420,255,100,150,12,50);

	// Задние лапы
	setcolor (11);
	ellipse (410,360,130,200,60,89);
	ellipse (421,392,128,179,109,151);
	ellipse (334,389,180,340,21,5);

	ellipse (429,400,128,180,120,160);
	ellipse (289,400,180,340,22,5);
	ellipse (388,400,141,180,120,160);
	ellipse (328,291,110,184,20,73);
	ellipse (303,332,79,115,22,35);

	// Переднии лапы
	setcolor (11);
	ellipse (500,291,170,260,30,115);
	ellipse (460,325,160,250,20,85);
	ellipse (474,402,180,340,22,5);

	ellipse (439,381,110,205,30,100);
	ellipse (428,410,120,185,60,140);
	ellipse (390,421,180,340,22,5);

	// Левое крыло
	setcolor (11);
	ellipse (404,137,200,250,45,75);
	ellipse (308,323,45,95,75,230);
	ellipse (301,110,100,180,3,15);
	ellipse (340,38,225,252,60,100);
	line (322,133,330,150);
	ellipse (238,244,45,81,130,130);
	ellipse (255,132,80,160,5,15);
	ellipse (343,36,225,252,130,130);
	line (303,160,316,170);
	ellipse (291,199,70,110,70,31);
	ellipse (313,179,160,200,50,25);
	ellipse (268,249,60,90,125,60);
	ellipse (377,214,139,270,60,24);

	ellipse (375,224,115,250,30,15);
	line (360,210,332,190);
	ellipse (333,179,150,260,10,10);
	ellipse (333,179,90,160,10,9);
	line (334,170,358,187);
	ellipse (369,126,200,250,30,65);
	ellipse (338,213,50,85,36,65);

	// Правое крыло
	setcolor (11);
	ellipse (589,209,80,130,91,70);
	ellipse (598,148,300,50,11,10);
	ellipse (602,267,90,115,109,110);
	ellipse (555,185,40,90,85,17);
	ellipse (614,182,300,50,11,10);
	ellipse (605,201,81,135,80,10);
	ellipse (540,244,20,80,51,50);
	ellipse (574,223,290,350,15,15);
	ellipse (526,270,42,90,70,50);
	ellipse (523,239,350,70,9,20);
	ellipse (513,229,220,310,30,20);
	ellipse (494,225,260,30,15,15);
	ellipse (510,200,263,70,30,15);
	ellipse (510,170,285,60,40,15);

	// Тело
	setcolor (11);
	line (511,207,470,270);
	ellipse (392,235,290,330,90,70);
	ellipse (395,256,175,265,45,40);
	ellipse (385,220,45,100,25,25);

	// Лицо
	setcolor (11);
	line (410,100,413,90);
	ellipse (421,151,175,295,16,19);
	line (427,169,432,164);
	ellipse (423,174,280,45,13,13);
	setcolor (12);
	ellipse (423,174,280,28,4,13);
	setcolor (11);
	ellipse (457,166,230,300,52,29);
	ellipse (450,135,320,360,80,110);
	ellipse (420,153,240,300,6,9);
	setcolor (12);
	ellipse (432,183,80,220,3,3);

	// Ухо
	setcolor (11);
	ellipse (545,115,100,150,40,70);
	ellipse (518,86,295,50,30,53);
	ellipse (515,84,310,13,23,33);

	// Грива верх
	setcolor (11);
	ellipse (496,120,75,150,67,48);
	ellipse (482,124,120,150,50,60);
	ellipse (385,10,264,305,120,80);
	ellipse (367,10,280,330,69,80);
	ellipse (419,70,80,160,30,20);
	ellipse (442,80,80,160,55,50);
	ellipse (416,70,50,100,55,50);
	ellipse (443,74,15,120,80,60);
	setcolor (12);
	ellipse (499,100,73,152,60,50);
	setcolor (4);
	ellipse (499,102,75,150,60,50);
	setcolor (12);
	ellipse (483,188,81,112,165,150);
	setcolor (14);
	ellipse (483,186,82,114,165,150);

	// Глаза
	setcolor (11);
	ellipse (470,130,150,20,30,40);
	ellipse (415,124,311,28,15,30);
	ellipse (419,125,135,240,15,30);
	ellipse (415,147,200,340,10,5);
	setlinestyle (0,0,1);
	setcolor (7);
	ellipse (471,130,20,150,30,40);
	line (500,119,513,119);
	line (497,108,510,102);
	line (488,99,500,89);
	ellipse (417,124,28,124,14,27);
	ellipse (419,124,134,135,15,30);

	// Зрачки
	setlinestyle (0,0,1);
	setcolor (13);
	setfillstyle (1,13);
	fillellipse (460,130,15,21);
	fillellipse (420,125,8,16);
	setcolor (6);
	setfillstyle (1,6);
	fillellipse (456,130,10,15);
	fillellipse (423,124,5,12);
	setfillstyle (1,15);
	setcolor (15);
	fillellipse (459,133,3,3);
	fillellipse (464,122,5,6);
	fillellipse (422,129,1,2);
	fillellipse (423,119,3,4);
}