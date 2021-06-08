using System;


namespace MiniSnake_v5._0
{
    class Apple : Point
    {
        public Apple()
        {
            x = 15;
            y = 10;
        }

        public Apple(int maxX, int maxY, Snake snake = null)
        {
            if (snake == null)
            {
                Random rand = new Random();
                x = rand.Next(1, maxX);
                y = rand.Next(1, maxY);
            }
            else
            {
                while (true)
                {
                    Random rand = new Random();
                    x = rand.Next(1, maxX);
                    y = rand.Next(1, maxY);
                    if (!snake.CheckOnSnake(this))
                    {
                        break;
                    }
                }
            }
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