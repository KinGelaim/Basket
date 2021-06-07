using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading;

namespace ConsoleSnake
{
    class KeyPress
    {
        //Перечисление для определения направления (куда смотрит змея и какая клавишу нажата последняя?)
        public enum Direction
        {
            up,
            down,
            left,
            right
        }

        //Поток для обработки нажатий клавиш в меню
        private static Thread keyPressThread;
        //Поток для обработки нажатий клавиш в игре
        private static Thread keyPressInGameThread;

        public static void StartThreadMenu()
        {
            keyPressThread = new Thread(ReadKeyConsole);
            keyPressThread.Start();
        }

        public static void StartThreadGame1()
        {
            keyPressInGameThread = new Thread(ReadKeyGame1Console);
            keyPressInGameThread.Start();
        }

        public static void StartThreadGame2()
        {
            keyPressInGameThread = new Thread(ReadKeyGame2Console);
            keyPressInGameThread.Start();
        }

        private static void ReadKeyConsole()
        {
            while (Program.isInMenu)
            {
                ConsoleKeyInfo key = Console.ReadKey();
                if (!Program.isInSubMenu)
                {
                    switch (key.Key.ToString())
                    {
                        case "UpArrow":
                        case "DownArrow":
                            if (Program.menu == 1)
                                Program.menu = 0;
                            else if (Program.menu == 0)
                                Program.menu = 1;
                            Program.CreateMenu();
                            break;
                        case "Spacebar":
                        case "Enter":
                            if (Program.menu == 0)
                            {
                                Program.subMenu = 0;
                                Program.isInSubMenu = true;
                                Program.CreateSubMenu();
                            }
                            else if (Program.menu == 1)
                                return;
                            break;
                        default:
                            break;
                    }
                }
                else
                {
                    switch (key.Key.ToString())
                    {
                        case "UpArrow":
                            if (Program.subMenu > 0)
                                Program.subMenu--;
                            else
                                Program.subMenu = 3;
                            Program.CreateSubMenu();
                            break;
                        case "DownArrow":
                            if (Program.subMenu < 3)
                                Program.subMenu++;
                            else
                                Program.subMenu = 0;
                            Program.CreateSubMenu();
                            break;
                        case "Spacebar":
                        case "Enter":
                            if (Program.subMenu == 0)
                                Program.StartGame(1);
                            if (Program.subMenu == 1)
                                Program.StartGame(2);
                            else if (Program.subMenu == 3)
                                return;
                            break;
                        default:
                            break;
                    }
                }
            }
        }

        private static void ReadKeyGame1Console()
        {
            while (true)
            {
                ConsoleKeyInfo key = Console.ReadKey();
                //Console.WriteLine(key.Key.ToString());
                switch (key.Key.ToString())
                {
                    case "UpArrow":
                        if (Game_1.snakeDirection != Direction.down)
                            Game_1.snakeDirection = Direction.up;
                        break;
                    case "DownArrow":
                        if (Game_1.snakeDirection != Direction.up)
                            Game_1.snakeDirection = Direction.down;
                        break;
                    case "LeftArrow":
                        if (Game_1.snakeDirection != Direction.right)
                            Game_1.snakeDirection = Direction.left;
                        break;
                    case "RightArrow":
                        if (Game_1.snakeDirection != Direction.left)
                            Game_1.snakeDirection = Direction.right;
                        break;
                    case "Spacebar":
                        if (!Game_1.isDie)
                            Game_1.isGameStart = !Game_1.isGameStart;
                        else
                            Game_1.StartGame();
                        break;
                    default:
                        break;
                }
            }
        }

        private static void ReadKeyGame2Console()
        {
            while (true)
            {
                ConsoleKeyInfo key = Console.ReadKey();
                //Console.WriteLine(key.Key.ToString());
                switch (key.Key.ToString())
                {
                    case "UpArrow":
                        if (Game_2.snakeDirection != Direction.down)
                            Game_2.snakeDirection = Direction.up;
                        break;
                    case "DownArrow":
                        if (Game_2.snakeDirection != Direction.up)
                            Game_2.snakeDirection = Direction.down;
                        break;
                    case "LeftArrow":
                        if (Game_2.snakeDirection != Direction.right)
                            Game_2.snakeDirection = Direction.left;
                        break;
                    case "RightArrow":
                        if (Game_2.snakeDirection != Direction.left)
                            Game_2.snakeDirection = Direction.right;
                        break;
                    case "Spacebar":
                        if (!Game_2.isDie)
                            Game_2.isGameStart = !Game_1.isGameStart;
                        else
                            Game_2.StartGame();
                        break;
                    default:
                        break;
                }
            }
        }

        public static void EndThread()
        {
            if (keyPressThread != null)
                if (keyPressThread.IsAlive)
                    keyPressThread.Abort();
            if (keyPressInGameThread != null)
                if (keyPressInGameThread.IsAlive)
                    keyPressInGameThread.Abort();
        }
    }
}
