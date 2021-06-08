using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading;

namespace MiniSnake_v2._1
{
    class GameSnake
    {
        //Настройки
        private int weightWindow;
        private int heightWindow;

        private bool isGameStart;
        private int score;
        //private Thread threadGame;
        private ReadKeyConsole rkc;

        private Level level;
        private Snake snake;
        private Apple apple;

        public GameSnake(int weightWindow, int heightWindow)
        {
            this.weightWindow = weightWindow;
            this.heightWindow = heightWindow;
        }

        //Метод для отображения меню
        public void ShowStartMenu()
        {
            #region ТЕКСТ МЕНЮ
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

            while (true)
            {
                Console.Clear();
                level.ShowLevel();
                apple.ShowApple();
                ShowScore();
                snake.ShowSnake();
                snake.MoveSnake();
                if(level.CheckSnakeDie(snake.GetHead()))
                {
                    StopGame();
                    break;
                }
                if(apple.CheckApple(snake.GetHead()))
                {
                    score++;
                    snake.AddPoint(apple.x, apple.y);
                    apple = new Apple(weightWindow - 1, heightWindow - 1);
                }
                Console.SetCursorPosition(0, heightWindow + 3);
                Thread.Sleep(500);
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
            Console.SetCursorPosition(0, heightWindow + 1);
            Console.Write("Кол-во набранных очков: " + score);
        }
    }
}
