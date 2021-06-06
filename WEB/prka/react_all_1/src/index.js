//import './index.css';

const books = [
	{
		img: 'src/image/python_book.png',
		title: 'Python',
		author: 'Mark Lutz'
	},
	{
		img: 'src/image/python_book_2.png',
		title: 'Python',
		author: 'Mark Lutz'
	},
	{
		img: 'src/image/three_book.png',
		title: 'Webgl',
		author: 'Jos Dirsen'
	}
];

function BookList () {
	return (
		<section className='booklist'>
			{books.map((book,index) => {
				return <Book key={index} book={book} />;
			})}
		</section>
	);
}

const Book = (props) => {
	const { title, author } = props.book;
	
	const clickHandler = (author) => {
		alert(author);
	};
	
	return (
		<article className='book'>
			<Image img={props.book.img}></Image>
			<h1>{title}</h1>
			<Author author={author}/>
			<button type='button' onClick={()=>clickHandler(author)}>Example button</button>
		</article>
	);
}

const Image = (props) => (
	<img
		className='book__image'
		src={props.img}
		alt=''
	/>
);
const Author = ({author}) => <h4 style={{ color: '#617d98', fontSize: '0.75rem', marginTop: '0.25rem' }}>{author}</h4>;

ReactDOM.render(
  <BookList />,
  document.getElementById('root')
);