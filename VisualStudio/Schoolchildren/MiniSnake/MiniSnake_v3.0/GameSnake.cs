using System;
using System.Threading;

namespace MiniSnake_v3._0
{
    class GameSnake
    {
        //Настройки
        private int weightWindow;
        private int heightWindow;

        public static bool isGameStart;
        private int score;
        //private Thread threadGame;
        private ReadKeyConsole rkc;

        private Level level;
        private Snake snake;
        private Apple apple;

        private int speedGame = 300;

        public GameSnake(int weightWindow, int heightWindow)
        {
            this.weightWindow = weightWindow;
            this.heightWindow = heightWindow;
        }

        //Метод для отображения меню
        public void ShowStartMenu()
        {
            #region ТЕКСТ МЕНЮ
            Console.ForegroundColor = ConsoleColor.Green;
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
            Console.ForegroundColor = ConsoleColor.Gray;
            #endregion

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
                    Console.ForegroundColor = ConsoleColor.Red;
                }
                Console.SetCursorPosition(12, incoming.Length + 4);
                Console.Write("Старт гейм");
                Console.ForegroundColor = ConsoleColor.Gray;
                if (posMenu == 2)
                {
                    Console.SetCursorPosition(11, incoming.Length + 7);
                    Console.Write("♦");
                    Console.ForegroundColor = ConsoleColor.Red;
                }
                Console.SetCursorPosition(12, incoming.Length + 7);
                Console.WriteLine("Выход");
                Console.ForegroundColor = ConsoleColor.Gray;
                ConsoleKey key = Console.ReadKey(true).Key;
                if (key == ConsoleKey.UpArrow || key == ConsoleKey.W)
                {
                    posMenu = 1;
                }
                else if (key == ConsoleKey.DownArrow || key == ConsoleKey.S)
                {
                    posMenu = 2;
                }
                else if (key == ConsoleKey.Enter || key == ConsoleKey.Spacebar)
                {
                    if (posMenu == 1)
                    {
                        StartGame();
                        return;
                    }
                    else
                    {
                        return;
                    }
                }
            }
        }

        //Метод для старта игры
        private void StartGame()
        {
            level = new Level(weightWindow, heightWindow);
            apple = new Apple();
            snake = new Snake();
            score = 0;
            isGameStart = true;

            rkc = new ReadKeyConsole();
            rkc.Run();

            Console.Clear();
            while (true)
            {
                if (isGameStart)
                {
                    level.ShowLevel();
                    apple.ShowApple();
                    ShowScore();

                    snake.MoveSnake();
                    snake.RemoveTailSnake();
                    snake.ShowSnake();
                    if (level.CheckSnakeDie(snake.GetHead()))
                    {
                        StopGame();
                        break;
                    }
                    if (apple.CheckApple(snake.GetHead()))
                    {
                        score++;
                        snake.AddPoint(apple.x, apple.y);
                        apple = new Apple(weightWindow - 1, heightWindow - 1, snake);
                        if (score % 10 == 0)
                        {
                            speedGame -= 2;
                        }
                        if (level.CheckWinLevel(snake.SnakeLen()))
                        {
                            StopGame();
                            break;
                        }
                    }
                    else if (snake.SnakeHeadOnSnake())
                    {
                        StopGame();
                        break;
                    }
                    Console.SetCursorPosition(0, heightWindow + 3);
                    Thread.Sleep(speedGame);
                }
                ShowPause();
            }
        }

        //Метод для остановки игры
        private void StopGame()
        {
            isGameStart = false;
            rkc.Stop();
            Console.SetCursorPosition(0, heightWindow + 3);
            Console.WriteLine("Игра окончена");
        }

        //Метод для отрисовки очков
        private void ShowScore()
        {
            Console.SetCursorPosition(0, heightWindow);
            Console.Write("Кол-во набранных очков: " + score);
        }
        
        //Метод для отрисовки информации о паузе
        private void ShowPause()
        {
            Console.SetCursorPosition(0, heightWindow + 2);
            if (isGameStart)
            {
                Console.Write("Для паузы нажмите пробел");
            }
            else
            {
                Console.Write("Игра на ПАУЗЕ! Для отмены нажмите пробел");
            }
        }
    }
}
