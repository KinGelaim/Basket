//import './index.css';

const firstBook = {
	img: 'src/image/python_book.png',
	title: 'Python',
	author: 'Mark Lutz'
}

const secondBook = {
	img: 'src/image/python_book_2.png',
	title: 'Python',
	author: 'Mark Lutz'
}

const thirdBook = {
	img: 'src/image/three_book.png',
	title: 'Webgl',
	author: 'Jos Dirsen'
}

function BookList () {
	return (
		<section className='booklist'>
			<Book
				book={firstBook}
			>
				<p>
					It's a great book!
				</p>
			</Book>
			<Book
				book={secondBook}
			/>
			<Book book={thirdBook} />
		</section>
	);
}

const Book = (props) => {
	const { title, author } = props.book;
	return (
		<article className='book'>
			<Image img={props.book.img}></Image>
			<h1>{title}</h1>
			<Author author={author}/>
			{props.children}
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