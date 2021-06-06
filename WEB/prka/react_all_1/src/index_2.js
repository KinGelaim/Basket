function Greeting () {
	return (
		<div className="asd">
			<div>
				<Person />
				<ul>
					<li>
						<Message />
					</li>
				</ul>
			</div>
			<div>
			
			</div>
		</div>
	);
}

const Person = () => <h4>This is my second component!</h4>;
const Message = () => <p>Hello world!</p>;

ReactDOM.render(
  <Greeting />,
  document.getElementById('root')
);