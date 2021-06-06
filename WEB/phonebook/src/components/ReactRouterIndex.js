class ReactRouterIndex extends React.Component {
	state = {
		beginContractsData: []
	};
	
	constructor(props) {
		super(props);
		
		this.getData();
	}
	
	getData() {
		/*beginContractsData = [
			{id: 1, FIO: 'Котков Михаил Александрович', telephone: '42-73', mobile: '+79221679197', email: '*****', position: 'Инженер-программист', department: 'Отдел №22', imgHref: 'http://localhost/phonebook/src/image/photo/kotkov.jpg', map: {title: 'Здание СКБ (этаж 2)', imgHref:'http://localhost/phonebook/src/image/map/skb2.png',x:280,y:287}},
			{id: 2, FIO: 'Мясникова Татьяна Михайловна', telephone: '51-43', mobile: '*****', email: 'myasnikova@ntiim.ru', position: 'Начальник отдела', department: 'Отдел №22', imgHref: 'http://localhost/phonebook/src/image/photo/myasnikova.JPG', map: {title: 'Здание СКБ (этаж 2)', imgHref:'http://localhost/phonebook/src/image/map/skb2.png',x:239,y:387}},
			{id: 3, FIO: 'Вишняков Александр Николаевич', telephone: '51-42', mobile: '*****', email: 'vishnyakov@ntiim.ru', position: 'Начальник отдела', department: 'Отдел №15', map: {title: 'Здание администрации (этаж 4)', imgHref:'http://localhost/phonebook/src/image/map/3848b-11521017597.jpg',x:644,y:337}},
			{id: 4, FIO: 'Белозёров Вадим Анатольевич', telephone: '30-31, 51-30', mobile: '*****', email: '*****', position: 'Начальник отдела', department: 'Отдел №31'},
			{id: 5, FIO: 'Алексеев Михаил Юрьевич', telephone: '*****', mobile: '*****', email: '*****', position: 'Начальник отдела', department: 'Отдел ИТ', map: {title: 'Здание СКБ (этаж 2)', imgHref:'http://localhost/phonebook/src/image/map/skb2.png',x:211,y:72}},
			{id: 6, FIO: 'Марковкин Виктор Васильевич', telephone: '51-12', mobile: '*****', email: '*****', position: '*****', department: '*****'},
			{id: 7, FIO: 'Пелевин Артём Николаевич', telephone: '52-51', mobile: '*****', email: '*****', position: '*****', department: '*****'},
			{id: 8, FIO: 'Зубрилин Максим Викторович', telephone: '51-95', mobile: '*****', email: '*****', position: 'Начальник отдела', department: 'ГЗИ'},
			{id: 9, FIO: 'Сарапулов Александр Алексеевич', telephone: '43-95', mobile: '*****', email: '*****', position: '*****', department: 'ГЗИ'},
			{id: 10, FIO: 'ФИО', telephone: 'Рабочий телефон', mobile: 'Телефон', email: 'Почта', position: 'Должность', department: 'Подразделение'}
		];*/
		fetch('api/contracts.php',
			{
				method: 'GET',
				//body: JSON.stringify(data),
				headers: {
					'Content-Type': 'application/json'
				}
			})
			.then((response) => {
				return response.json();
			})
			.then(data => {
				this.setState({beginContractsData: data});
			})
			.catch(e => console.error('Error: ', e));
	};

	render() {
		return (
			<ReactRouterDOM.BrowserRouter>
				<Header />
				<ReactRouterDOM.Switch>
					<ReactRouterDOM.Route exact path='/phonebook/'>
						<Home />
					</ReactRouterDOM.Route>
					<ReactRouterDOM.Route path='/phonebook/contacts'>
						<Contacts beginContractsData={this.state.beginContractsData} />
					</ReactRouterDOM.Route>
					<ReactRouterDOM.Route render={()=><MapPage />} path='/phonebook/map' />
					<ReactRouterDOM.Route component={ErrorPage} path='*' />
				</ReactRouterDOM.Switch>
			</ReactRouterDOM.BrowserRouter>
		);
	}
};