using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading;

namespace ConsoleSnake
{
    // Имеем массив змейки и перерисовываем массив змейки каждый раз

    class Game_1
    {
        public static bool isGameStart = false;     //Переменная для определения идет ли игра? (может пауза, а может мертвы)
        public static bool isDie = false;           //Переменная для определения проиграли ли?
        private static int score = 0;               //Количество очков
        private static int hightScore = 0;          //Лучшее количество очков
        private static int widthWall = 50;          //Ширина стены
        private static int heightWall = 22;         //Высота стены

        private static List<Point> snake;           //Массив для хранения позиций змейки
        private static Point apple;                 //Переменная для хранения позиции яблока

        //public static KeyPress.Direction keyDirection = KeyPress.Direction.right;    //Куда нажата клавиша
        public static KeyPress.Direction snakeDirection = KeyPress.Direction.right;  //Куда смотрит змея

        //Функция для старта игры
        public static void StartGame()
        {
            BeginSettingsGame();
            BeginGame();
        }

        //Применение начальных настроек
        private static void BeginSettingsGame()
        {
            Console.Clear();
            score = 0;
            isGameStart = false;
            CreateWall();
            CreateSnake();
            PrintSnake();
            CreateApple();
            PrinApple();
            //keyDirection = KeyPress.Direction.right;
            snakeDirection = KeyPress.Direction.right;
        }

        //Создание и отрисовка стены
        private static void CreateWall()
        {
            Console.ForegroundColor = ConsoleColor.Yellow;
            for (int i = 0; i < heightWall; i++)
            {
                Console.CursorTop = i;
                for (int j = 0; j < widthWall; j++)
                {
                    Console.CursorLeft = j;
                    if (i == 0)
                        Console.Write("♦");
                    else if ( i < heightWall - 1)
                    {
                        if (j == 0)
                            Console.Write("♦");
                        if(j == widthWall - 1)
                            Console.Write("♦");
                    }
                    else
                        Console.Write("♦");
                }
            }
        }

        //Создание змеи
        private static void CreateSnake()
        {
            Point headPoint = new Point(7, 7);
            Point bodyPoint = new Point(6, 7);
            Point tailPoint = new Point(5, 7);
            snake = new List<Point>();
            snake.Add(headPoint);
            snake.Add(bodyPoint);
            snake.Add(tailPoint);
        }

        //Отрисовка змеи
        private static void PrintSnake()
        {
            Console.ForegroundColor = ConsoleColor.Green;
            foreach (Point point in snake)
            {
                Console.SetCursorPosition(point.PosX, point.PosY);
                Console.Write("♦");
            }
        }

        //Создание (TODO: потом создание можно в некст поток, чтобы можно было генирировать сразу же следующее яблоко, пока это еще не съедено)
        private static void CreateApple()
        {
            Random rand = new Random();
            bool isCheckApple = false;      //Проверка на то, что яблоко не создалось внутри змеи
            do
            {
                Point applePoint = new Point(rand.Next(1,widthWall - 1), rand.Next(1, heightWall - 1));
                apple = applePoint;
                //TODO: проверка
            } while (isCheckApple);
        }

        //Отрисовка яблока
        private static void PrinApple()
        {
            Console.ForegroundColor = ConsoleColor.Red;
            Console.SetCursorPosition(apple.PosX, apple.PosY);
            Console.Write("♦");
        }

        //Начало самой игры
        private static void BeginGame()
        {
            isGameStart = true;
            isDie = false;
            KeyPress.StartThreadGame1();
            //Пока идет игра - двигаем змейку и чистим поле
            while (!isDie)
                while (isGameStart)
                {
                    PrinApple();
                    MoveSnake();
                    PrintSnake();
                    PrintMenu();
                    Thread.Sleep(100);
                    //ClearStage();
                }
        }

        //Перемещение змейки
        private static void MoveSnake()
        {
            //Перемещаем голову в сторону, в которую смотрит змейка, а все остальное тело - на следующий кусочек тела
            int prHeadX = snake[0].PosX;
            int prHeadY = snake[0].PosY;
            switch (snakeDirection)
            {
                case KeyPress.Direction.up:
                    snake[0].PosY--;
                    break;
                case KeyPress.Direction.down:
                    snake[0].PosY++;
                    break;
                case KeyPress.Direction.left:
                    snake[0].PosX--;
                    break;
                case KeyPress.Direction.right:
                    snake[0].PosX++;
                    break;
            }
            //Проверка на стену
            if (snake[0].PosX == 0 || snake[0].PosY == 0 || snake[0].PosX == widthWall - 1 || snake[0].PosY == heightWall - 1)
            {
                isGameStart = false;
                isDie = true;
            }
            //Проверка на наезд на себя
            foreach(Point point in snake)
                if(snake[0] != point && snake[0].PosX == point.PosX && snake[0].PosY == point.PosY)
                {
                    isGameStart = false;
                    isDie = true;
                }
            //Проверка на поглощение яблока
            if (snake[0].PosX == apple.PosX && snake[0].PosY == apple.PosY)
            {
                snake.Add(new Point(apple.PosX, apple.PosY));
                CreateApple();
                score += 10;
                if (score > hightScore)
                    hightScore = score;
            }
            //Двигаем все остальное тело
            for (int i = snake.Count-1; i > 0; i--)
            {
                if (i == snake.Count - 1)
                {
                    //Зачищаем хвостик
                    Console.SetCursorPosition(snake[i].PosX, snake[i].PosY);
                    Console.Write(" ");
                }

                if (i - 1 > 0)
                {
                    snake[i].PosX = snake[i - 1].PosX;
                    snake[i].PosY = snake[i - 1].PosY;
                }
                else
                {
                    snake[i].PosX = prHeadX;
                    snake[i].PosY = prHeadY;
                }
            }
        }

        //Печатаем меню
        private static void PrintMenu()
        {
            Console.ForegroundColor = ConsoleColor.Gray;

            //Количество очков
            Console.SetCursorPosition(widthWall + 3, 1);
            Console.Write("Счет: " + score);
            Console.SetCursorPosition(widthWall + 3, 2);
            Console.Write("Лучший счет: " + hightScore);

            //Менюшка
            Console.SetCursorPosition(5, heightWall);
            Console.Write("Для управления нажимайте клавиши: ↑ ↓ → ← ");
            Console.SetCursorPosition(5, heightWall + 1);
            Console.Write("Для паузы нажмите \"Пробел\"");

            //Информация о проигрыше
            if(isDie)
            {
                Console.SetCursorPosition(widthWall + 3, 5);
                Console.Write("Увы, Вы проиграли!");
                Console.SetCursorPosition(widthWall + 3, 7);
                Console.Write("Пробел для рестарта!");
            }
        }
    }
}
