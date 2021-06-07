using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading;

namespace LoadingConsole
{
    class Program
    {
        static void Main(string[] args)
        {
            Console.CursorVisible = false;

            Console.Title = "Loading SYSTEM!!!";

            Console.WriteLine("Loading... ");
            Print("Loading System...", 1000);
            Print("Loading PodSystem...", 500);
            Print("Loading Core...", 3000);

            int pos = Console.CursorTop;
            Console.SetCursorPosition(11, 0);
            Console.Write("OK");
            Console.SetCursorPosition(0, pos);

            var rand = new Random();

            for (int i = 0; i <= 100; i++)
            {
                if (i < 25)
                    Console.ForegroundColor = ConsoleColor.Magenta;
                else if (i < 50)
                    Console.ForegroundColor = ConsoleColor.Red;
                else if (i < 75)
                    Console.ForegroundColor = ConsoleColor.Cyan;
                else if (i < 100)
                    Console.ForegroundColor = ConsoleColor.Yellow;
                else
                    Console.ForegroundColor = ConsoleColor.Green;

                string pct = string.Format("{0, -30} {1,3}%", new string((char)0x2592, i * 30 / 100), i);
                Console.CursorLeft = 0;
                Console.Write(pct);
                Thread.Sleep(rand.Next(0, 500));
            }
            Console.WriteLine();
            Console.ResetColor();
        }

        static void Print(string message, int delay)
        {
            Console.WriteLine(message);
            Thread.Sleep(delay);
        }
    }
}
