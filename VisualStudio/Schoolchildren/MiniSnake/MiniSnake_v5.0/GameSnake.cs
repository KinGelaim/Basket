using System;
using System.Collections.Generic;
using System.Threading;


namespace MiniSnake_v5._0
{
    public class GameSnake
    {
        // Settings
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

        private List<LutBox> lutBox = new List<LutBox>();

        public GameSnake(int weightWindow, int heightWindow)
        {
            this.weightWindow = weightWindow;
            this.heightWindow = heightWindow;

            lutBox = new List<LutBox>()
            {
                new LutBox(ConsoleColor.Green),
                new LutBox(ConsoleColor.Blue),
                new LutBox(ConsoleColor.White)
            };
        }

        // Show Menu
        public void ShowStartMenu()
        {
            bool isExitApp = false;
            while (!isExitApp)
            {
                Console.Clear();

                #region Text Menu

                Console.ForegroundColor = ConsoleColor.Green;
                // Main Menu
                string[] incoming = new string[] { "\t                                                        ",
                                               "\t                                                        ",
                                               "\t--------------------------------------------------------",
                                               "\t-                                                      -",
                                               "\t-         SSSSS  S   S      S     S  S  SSSS           -",
                                               "\t-         S   S  SS  S     S S    S S   S              -",
                                               "\t-           SS   S S S    SsssS   SS    SSSS           -",
                                               "\t-          S  S  S  SS   S     S  S S   S              -",
                                               "\t-         SSSSS  S   S  S       S S  S  SSSS           -",
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
                    Console.SetCursorPosition(11, incoming.Length + 10);
                    Console.Write(" ");
                    if (posMenu == 1)
                    {
                        Console.SetCursorPosition(11, incoming.Length + 4);
                        Console.Write("♦");
                        Console.ForegroundColor = ConsoleColor.Red;
                    }
                    Console.SetCursorPosition(12, incoming.Length + 4);
                    Console.Write("New game");
                    Console.ForegroundColor = ConsoleColor.Gray;
                    if (posMenu == 2)
                    {
                        Console.SetCursorPosition(11, incoming.Length + 7);
                        Console.Write("♦");
                        Console.ForegroundColor = ConsoleColor.Red;
                    }
                    Console.SetCursorPosition(12, incoming.Length + 7);
                    Console.WriteLine("Color");
                    Console.ForegroundColor = ConsoleColor.Gray;
                    if (posMenu == 3)
                    {
                        Console.SetCursorPosition(11, incoming.Length + 10);
                        Console.Write("♦");
                        Console.ForegroundColor = ConsoleColor.Red;
                    }
                    Console.SetCursorPosition(12, incoming.Length + 10);
                    Console.WriteLine("Exit");
                    Console.ForegroundColor = ConsoleColor.Gray;
                    ConsoleKey key = Console.ReadKey(true).Key;
                    if (key == ConsoleKey.UpArrow || key == ConsoleKey.W)
                    {
                        posMenu--;
                        if (posMenu < 1)
                            posMenu = 1;

                        SoundManager.GetSoundManager().PlayChoise();
                    }
                    else if (key == ConsoleKey.DownArrow || key == ConsoleKey.S)
                    {
                        posMenu++;
                        if (posMenu > 3)
                            posMenu = 3;

                        SoundManager.GetSoundManager().PlayChoise();
                    }
                    else if (key == ConsoleKey.Enter || key == ConsoleKey.Spacebar)
                    {
                        if (posMenu == 1)
                        {
                            StartGame();
                            break;
                        }
                        else if (posMenu == 2)
                        {
                            ShowLutBoxMenu();
                        }
                        else
                        {
                            return;
                        }
                    }
                }
            }
        }

        // Menu Color
        private void ShowLutBoxMenu()
        {
            int topPosition = 10;
            int leftPosition = 3;
            int posMenu = 0;
            while (true)
            {
                Console.Clear();
                for (int i = 0; i < lutBox.Count; i++)
                {
                    Console.SetCursorPosition(leftPosition, topPosition);
                    if (posMenu == i)
                    {
                        Console.ForegroundColor = ConsoleColor.Red;
                        Console.Write("♦");
                        leftPosition++;
                    }
                    Console.ForegroundColor = lutBox[i].Color;
                    Console.Write("♦");
                    leftPosition++;
                    Console.Write("♦");
                    leftPosition++;
                    Console.Write("♦");
                    leftPosition += 3;
                }
                Console.ForegroundColor = ConsoleColor.Gray;
                leftPosition = 3;
                ConsoleKey key = Console.ReadKey(true).Key;
                if (key == ConsoleKey.LeftArrow || key == ConsoleKey.A)
                {
                    posMenu--;
                    if (posMenu < 0)
                        posMenu = 0;
                }
                else if (key == ConsoleKey.RightArrow || key == ConsoleKey.D)
                {
                    posMenu++;
                    if (posMenu > 2)
                        posMenu = 2;
                }
                else if (key == ConsoleKey.Enter || key == ConsoleKey.Spacebar)
                {
                    Snake.snakeColor = lutBox[posMenu].Color;
                    return;
                }
            }
        }

        // Start Game
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
                    SoundManager.GetSoundManager().PlaySnake();

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
                        SoundManager.GetSoundManager().PlayApple();

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

        // Pause
        private void StopGame()
        {
            SoundManager.GetSoundManager().PlayDead();
            
            isGameStart = false;
            rkc.Stop();
            Console.SetCursorPosition(0, heightWindow + 3);
            Console.WriteLine("Game over!");
            Console.ReadKey();
        }

        //Метод для отрисовки очков
        private void ShowScore()
        {
            Console.SetCursorPosition(0, heightWindow);
            Console.Write("Number of points: " + score);
        }

        //Метод для отрисовки информации о паузе
        private void ShowPause()
        {
            Console.SetCursorPosition(0, heightWindow + 2);
            if (isGameStart)
            {
                Console.Write("Press space to pause");
            }
            else
            {
                Console.Write("PAUSE! Press Space to Cancel!");
            }
        }
    }
}
