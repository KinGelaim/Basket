//import React from 'react';

function MyInfo(){
	const firstName = "Иван"
	const secondName = "Иванов"
	
	const date = new Date();
	//const date = new Date(2020, 9, 30, 15);
	const hours = date.getHours();
	let welcomeTimeOfDay
	const styles = {
		fontSize: 30,
		color: "black"
	}
	
	if(hours < 12){
		welcomeTimeOfDay = 'Доброе утро'
		styles.color = "#04756F"
	}
	else if(hours >= 12 && hours < 17){
		welcomeTimeOfDay = 'Добрый день'
		styles.color = "#8914A3"
	}
	else{
		welcomeTimeOfDay = 'Доброй ночи'
		styles.color = "#D90000"
	}
	
	return (
		<div>
			<nav>
				<h1 style={styles}>{welcomeTimeOfDay}: {secondName + " " + firstName}</h1>
				<h1>Привет: {`${secondName} ${firstName}`}</h1>
				<h1 style={{color: "#FF8C00", backgroundColor: "rgb(45, 67, 225)"}}>Сейчас {date.getHours() % 12} часов</h1>
				<ul>
					<li>1</li>
					<li>2</li>
					<li>3</li>
				</ul>
			</nav>
			<main>
				<p>Это параграф обо мне</p>
			</main>
		</div>
	)
}

//export default MyInfo