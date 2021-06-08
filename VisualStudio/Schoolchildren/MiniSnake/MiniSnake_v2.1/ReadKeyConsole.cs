using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading;

namespace MiniSnake_v2._1
{
    class ReadKeyConsole
    {
        public static string direction = "right";
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
}
