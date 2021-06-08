using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

namespace MiniSnake_v2._1
{
    class Apple : Point
    {
        public Apple()
        {
            x = 15;
            y = 10;
        }

        public Apple(int maxX, int maxY)
        {
            Random rand = new Random();
            x = rand.Next(1, maxX);
            y = rand.Next(1, maxY);
        }

        public void ShowApple()
        {
            Console.SetCursorPosition(x, y);
            Console.ForegroundColor = ConsoleColor.Red;
            Console.Write("♥");
            Console.ForegroundColor = ConsoleColor.Gray;
        }

        public bool CheckApple(Point head)
        {
            if (head.x == x && head.y == y)
            {
                return true;
            }
            return false;
        }
    }
}
