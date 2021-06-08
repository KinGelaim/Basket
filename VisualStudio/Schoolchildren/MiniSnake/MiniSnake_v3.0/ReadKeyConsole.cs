using System;
using System.Threading;

namespace MiniSnake_v3._0
{
    class ReadKeyConsole
    {
        public static string keyLastDirection = "right";
        private Thread threadReadKeyConsole;

        public ReadKeyConsole()
        {
            threadReadKeyConsole = new Thread(() =>
            {
                MethodReadKeyConsole();
            });
        }

        public void Run()
        {
            threadReadKeyConsole.Start();
        }

        public void Stop()
        {
            if (threadReadKeyConsole.IsAlive)
            {
                threadReadKeyConsole.Abort();
            }
        }

        private void MethodReadKeyConsole()
        {
            while (true)
            {
                ConsoleKeyInfo key = Console.ReadKey(true);
                switch (key.Key)
                {
                    case ConsoleKey.UpArrow:
                    case ConsoleKey.W:
                        keyLastDirection = "up";
                        break;
                    case ConsoleKey.DownArrow:
                    case ConsoleKey.S:
                        keyLastDirection = "down";
                        break;
                    case ConsoleKey.LeftArrow:
                    case ConsoleKey.A:
                        keyLastDirection = "left";
                        break;
                    case ConsoleKey.RightArrow:
                    case ConsoleKey.D:
                        keyLastDirection = "right";
                        break;
                    case ConsoleKey.Spacebar:
                        GameSnake.isGameStart = !GameSnake.isGameStart;
                        break;
                    default:
                        break;
                }
            }
        }
    }
}
