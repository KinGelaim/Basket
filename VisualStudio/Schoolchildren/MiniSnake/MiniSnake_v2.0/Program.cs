using System;
using System.Collections.Generic;
using System.Threading;

//К старой змейке добавил отображение очков + меню + класс Point переехал в основной код
namespace MiniSnake_v2._0
{
    class Program
    {
        static string direction = "right";     //Куда змея направлена (куда мы её будем двигать)

        static void Main(string[] args)
        {
            //Заголовк окна
            Console.Title = "Змейка ~~~~~~~";

            //Чтобы каретку не было видно
            Console.CursorVisible = false;

            #region МЕНЮ
            //Главное меню
            string[] incoming = new string[] { "\t                                                        ",
                                               "\t                                                        ",
                                               "\t--------------------------------------------------------",
                                               "\t-                             #                  ####  -",
                                               "\t-   ####     #   #     #### #   # #  #     #     ####  -",
                                               "\t-      #    # # # #    #    #  ## # #     # #    ####  -",
                                               "\t-   ####   #   #   #   #### # # # ##     #####         -",
                                               "\t-      #  #         #  #    ##  # # #   #     #  ####  -",
                                               "\t-   #### #           # #### #   # #  # #       # ####  -",
                                               "\t-                                                      -",
                                               "\t--------------------------------------------------------"};

            int callStr = 1;
            for (int i = 0; i < incoming.Length; i++)
            {
                for (int j = i; j >= 0; j--)
                {
                    Console.SetCursorPosition(0, j);
                    Console.Write(incoming[incoming.Length - callStr]);
                    callStr++;
                }
                callStr = 1;
                Thread.Sleep(500);
            }
            int posMenu = 1;
            while (true)
            {
                Console.SetCursorPosition(11, incoming.Length + 4);
                Console.Write(" ");
                Console.SetCursorPosition(11, incoming.Length + 7);
                Console.Write(" ");
                if (posMenu == 1)
                {
                    Console.SetCursorPosition(11, incoming.Length + 4);
                    Console.Write("♦");
                    Console.ForegroundColor = ConsoleColor.Green;
                }
                Console.SetCursorPosition(12, incoming.Length + 4);
                Console.Write("Старт гейм");
                Console.ForegroundColor = ConsoleColor.Gray;
                if (posMenu == 2)
                {
                    Console.SetCursorPosition(11, incoming.Length + 7);
                    Console.Write("♦");
                    Console.ForegroundColor = ConsoleColor.Green;
                }
                Console.SetCursorPosition(12, incoming.Length + 7);
                Console.WriteLine("Выход");
                Console.ForegroundColor = ConsoleColor.Gray;
                ConsoleKey key = Console.ReadKey().Key;
                if (key == ConsoleKey.UpArrow)
                {
                    posMenu = 1;
                }
                else if (key == ConsoleKey.DownArrow)
                {
                    posMenu = 2;
                }
                else if (key == ConsoleKey.Enter)
                {
                    if (posMenu == 1)
                        break;
                    else
                        return;
                }
            }
            #endregion

            //Параметры стены
            int weightWall = 40;
            int heightWall = 20;

            //Создаём змею
            Point[] snake = new Point[3];
            snake[0] = new Point(12, 10);
            snake[1] = new Point(11, 10);
            snake[2] = new Point(10, 10);

            //Очки
            int score = 0;

            //Создаём яблоко
            Point apple = new Point(15, 10);

            Thread threadReadKey = new Thread(() =>
            {
                ReadKeyConsole();
            });
            threadReadKey.Start();

            while (true)
            {
                Console.Clear();

                //Рисуем стену
                Console.SetCursorPosition(0, 0);
                Console.ForegroundColor = ConsoleColor.Yellow;
                for (int i = 0; i < weightWall; i++)
                {
                    Console.Write("♦");
                }
                for (int i = 0; i < heightWall; i++)
                {
                    Console.SetCursorPosition(0, i);
                    Console.Write("♦");
                    Console.SetCursorPosition(weightWall - 1, i);
                    Console.Write("♦");
                }
                Console.Write('\r');
                for (int i = 0; i < weightWall; i++)
                {
                    Console.Write("♦");
                }
                Console.ForegroundColor = ConsoleColor.Gray;
                Console.WriteLine();

                //Рисуем яблоко
                Console.SetCursorPosition(apple.x, apple.y);
                Console.ForegroundColor = ConsoleColor.Red;
                Console.Write("♥");
                Console.ForegroundColor = ConsoleColor.Gray;

                //Рисуем очки
                Console.SetCursorPosition(0, heightWall + 1);
                Console.Write("Кол-во набранных очков: " + score);

                //Рисуем змею
                Console.ForegroundColor = ConsoleColor.Green;
                for (int i = 0; i < snake.Length; i++)
                {
                    Console.SetCursorPosition(snake[i].x, snake[i].y);
                    Console.Write("♦");
                }
                Console.ForegroundColor = ConsoleColor.Gray;

                //Двигаем змею
                for (int i = snake.Length - 1; i >= 0; i--)
                {
                    if (i != 0)
                    {
                        snake[i].x = snake[i - 1].x;
                        snake[i].y = snake[i - 1].y;
                    }
                    else
                    {
                        switch (direction)
                        {
                            case "left":
                                snake[0].x--;
                                break;
                            case "right":
                                snake[0].x++;
                                break;
                            case "up":
                                snake[0].y--;
                                break;
                            case "down":
                                snake[0].y++;
                                break;
                        }
                    }
                }

                //Проверяем на стену
                if (snake[0].x <= 0 || snake[0].y <= 0 || snake[0].x >= weightWall - 1 || snake[0].y >= heightWall - 1)
                {
                    break;
                }

                //Проверяем на то, съели мы его или нет
                if (snake[0].x == apple.x && snake[0].y == apple.y)
                {
                    //Увеличиваем кол-во очков
                    score++;

                    //Добавляем ячейку в массив
                    Point[] prArr = new Point[snake.Length + 1];
                    for (int i = 0; i < snake.Length; i++)
                    {
                        prArr[i] = snake[i];
                    }
                    prArr[prArr.Length - 1] = new Point(apple.x, apple.y);
                    snake = prArr;

                    //Генерируем новое яблоко
                    Random rand = new Random();
                    apple.x = rand.Next(1, weightWall - 1);
                    apple.y = rand.Next(1, heightWall - 1);
                }
                Console.SetCursorPosition(0, heightWall + 3);
                Thread.Sleep(500);
            }
            if (threadReadKey.IsAlive)
            {
                threadReadKey.Abort();
            }
            Console.SetCursorPosition(0, heightWall + 3);
            Console.WriteLine("Игра окончена");
            Console.ReadKey();
        }

        static void ReadKeyConsole()
        {
            while (true)
            {
                ConsoleKeyInfo key = Console.ReadKey();
                switch (key.Key)
                {
                    case ConsoleKey.UpArrow:
                    case ConsoleKey.W:
                        direction = "up";
                        break;
                    case ConsoleKey.DownArrow:
                    case ConsoleKey.S:
                        direction = "down";
                        break;
                    case ConsoleKey.LeftArrow:
                    case ConsoleKey.A:
                        direction = "left";
                        break;
                    case ConsoleKey.RightArrow:
                    case ConsoleKey.D:
                        direction = "right";
                        break;
                }
            }
        }
    }

    class Point
    {
        public int x;
        public int y;

        public Point(int x, int y)
        {
            this.x = x;
            this.y = y;
        }
    }
}