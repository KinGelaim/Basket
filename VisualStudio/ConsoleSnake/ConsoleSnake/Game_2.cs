using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading;

namespace ConsoleSnake
{
    // Имеем массив всего поля и перерисовываем все поле каждый раз

    class Game_2
    {
        public static bool isGameStart = false;
        public static bool isDie = false;
        private static int score = 0;
        private static int hightScore = 0;

        private const int widthWall = 50;
        private const int heightWall = 22;

        public static KeyPress.Direction snakeDirection = KeyPress.Direction.right;

        private static Point headSnake;     //Позиция головы змеи
        private static Point tailSnake;     //Позиция хвостика змеи

        private static List<Point> snake = new List<Point>();         //Необходим массив для стирания хвостика, увы :(  

        //Массив для содержания всего уровня!
        // 0 - пустая клетка
        // 1 - стена
        // 2 - яблоко
        // 3 - змейка
        // 4 - голова змейки
        public static int[,] levelMap = new int[heightWall, widthWall] { 
                                                {1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1} , 
                                                {1,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,1} ,
                                                {1,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,1} ,
                                                {1,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,1} ,
                                                {1,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,1} ,
                                                {1,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,1} ,
                                                {1,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,1} ,
                                                {1,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,1} ,
                                                {1,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,1} ,
                                                {1,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,1} ,
                                                {1,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,1} ,
                                                {1,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,1} ,
                                                {1,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,1} ,
                                                {1,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,1} ,
                                                {1,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,1} ,
                                                {1,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,1} ,
                                                {1,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,1} ,
                                                {1,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,1} ,
                                                {1,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,1} ,
                                                {1,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,1} ,
                                                {1,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,1} ,
                                                {1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1} };

        public static int[,] map;

        public static void StartGame()
        {
            BeginSettingsGame();
            BeginGame();
        }

        private static void BeginSettingsGame()
        {
            Console.Clear();
            map = new int[heightWall, widthWall];
            for(int i = 0; i < heightWall; i++)
                for (int j = 0; j < widthWall; j++)
                {
                    map[i, j] = levelMap[i, j];
                }
            score = 0;
            isGameStart = false;
            CreateSnake();
            CreateApple();
            snakeDirection = KeyPress.Direction.right;
        }

        private static void CreateSnake()
        {
            headSnake = new Point(7, 7);
            map[headSnake.PosX, headSnake.PosY] = 4;
            Point bodySnake = new Point(7, 6);
            map[bodySnake.PosX, bodySnake.PosY] = 3;
            tailSnake = new Point(7, 5);
            map[tailSnake.PosX, tailSnake.PosY] = 3;
            snake.Add(tailSnake);
            snake.Add(bodySnake);
            snake.Add(headSnake);
        }

        private static void CreateApple()
        {
            Random rand = new Random();
            bool isCheckApple = true;      //Проверка на то, что яблоко не создалось внутри змеи
            do
            {
                Point applePoint = new Point(rand.Next(1, heightWall - 1), rand.Next(1, widthWall - 1));
                if (map[applePoint.PosX, applePoint.PosY] == 0)
                {
                    map[applePoint.PosX, applePoint.PosY] = 2;
                    isCheckApple = false;
                }
            } while (isCheckApple);
        }

        private static void BeginGame()
        {
            isGameStart = true;
            isDie = false;
            KeyPress.StartThreadGame2();
            while (!isDie)
                while (isGameStart)
                {
                    PrintMap();
                    MoveSnake();
                    PrintMenu();
                    Thread.Sleep(100);
                }
        }

        private static void PrintMap()
        {
            Console.Clear();
            for(int i = 0; i < heightWall; i++)
                for(int j = 0; j < widthWall; j++)
                {
                    Console.SetCursorPosition(j, i);
                    switch (map[i, j])
                    {
                        case 0:
                            Console.Write(" ");
                            break;
                        case 1:
                            Console.ForegroundColor = ConsoleColor.Yellow;
                            Console.Write("♦");
                            break;
                        case 2:
                            Console.ForegroundColor = ConsoleColor.Red;
                            Console.Write("♦");
                            break;
                        case 3:
                        case 4:
                            Console.ForegroundColor = ConsoleColor.Green;
                            Console.Write("♦");
                            break;
                        default: break;
                    }
                }
        }

        private static void MoveSnake()
        {
            switch (snakeDirection)
            {
                case KeyPress.Direction.up:
                    headSnake.PosX--;
                    break;
                case KeyPress.Direction.down:
                    headSnake.PosX++;
                    break;
                case KeyPress.Direction.left:
                    headSnake.PosY--;
                    break;
                case KeyPress.Direction.right:
                    headSnake.PosY++;
                    break;
            }
            
            //Проверка на стену
            if (map[headSnake.PosX, headSnake.PosY] == 1)
            {
                isGameStart = false;
                isDie = true;
            }
            //Проверка на поглощение яблока
            if (map[headSnake.PosX, headSnake.PosY] == 2)
            {
                CreateApple();
                score += 10;
                if (score > hightScore)
                    hightScore = score;
            }
            else
            {
                map[snake[0].PosX, snake[0].PosY] = 0;
                snake.RemoveAt(0);
            }
            //Проверка на наезд на себя
            if (map[headSnake.PosX, headSnake.PosY] == 3 || map[headSnake.PosX, headSnake.PosY] == 4)
            {
                isGameStart = false;
                isDie = true;
            }
            snake.Add(headSnake);
            map[headSnake.PosX, headSnake.PosY] = 4;
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
            Console.Write("Для паузы нажмите \"Пробел\" ");

            //Информация о проигрыше
            if (isDie)
            {
                Console.SetCursorPosition(widthWall + 3, 5);
                Console.Write("Увы, Вы проиграли!");
                Console.SetCursorPosition(widthWall + 3, 7);
                Console.Write("Пробел для рестарта!");
            }
        }
    }
}
