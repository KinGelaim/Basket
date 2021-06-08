using System;

namespace MiniSnake_v4._0
{
    class Snake
    {
        private Point[] snake;
        private Point tailSnake;
        private string direction = ReadKeyConsole.keyLastDirection;

        public static ConsoleColor snakeColor = ConsoleColor.Green;

        public Snake()
        {
            ReadKeyConsole.keyLastDirection = "right";
            direction = ReadKeyConsole.keyLastDirection;
            snake = new Point[3];
            snake[0] = new Point(12, 10);
            snake[1] = new Point(11, 10);
            snake[2] = new Point(10, 10);
        }

        public void ShowSnake()
        {
            Console.ForegroundColor = snakeColor;
            for (int i = 0; i < snake.Length; i++)
            {
                Console.SetCursorPosition(snake[i].x, snake[i].y);
                Console.Write("♦");
            }
            Console.ForegroundColor = ConsoleColor.Gray;
        }

        public void MoveSnake()
        {
            tailSnake = new Point();
            tailSnake.x = snake[snake.Length - 1].x;
            tailSnake.y = snake[snake.Length - 1].y;
            for (int i = snake.Length - 1; i >= 0; i--)
            {
                if (i != 0)
                {
                    snake[i].x = snake[i - 1].x;
                    snake[i].y = snake[i - 1].y;
                }
                else
                {
                    if (direction == "right" && ReadKeyConsole.keyLastDirection != "left" || direction == "down" && ReadKeyConsole.keyLastDirection != "up" || direction == "up" && ReadKeyConsole.keyLastDirection != "down" || direction == "left" && ReadKeyConsole.keyLastDirection != "right")
                    {
                        direction = ReadKeyConsole.keyLastDirection;
                    }
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
        }

        public void RemoveTailSnake()
        {
            Console.SetCursorPosition(tailSnake.x, tailSnake.y);
            Console.Write(" ");
        }

        public Point GetHead()
        {
            return snake[0];
        }

        public void AddPoint(int x, int y)
        {
            Point[] prArr = new Point[snake.Length + 1];
            for (int i = 0; i < snake.Length; i++)
            {
                prArr[i] = snake[i];
            }
            prArr[prArr.Length - 1] = new Point(x, y);
            snake = prArr;
        }

        //Метод для проверки, находится ли точка полученная в аргументе на самой змее
        public bool CheckOnSnake(Point point)
        {
            foreach (Point p in snake)
            {
                if (point.x == p.x && point.y == p.y)
                    return true;
            }
            return false;
        }

        //Метод для проверки, наехала ли голова змеи на змею
        public bool SnakeHeadOnSnake()
        {
            for (int i = 1; i < snake.Length; i++)
            {
                if (snake[i].x == snake[0].x && snake[i].y == snake[0].y)
                    return true;
            }
            return false;
        }

        //Метод возвращающий длину змеи
        public int SnakeLen()
        {
            return snake.Length;
        }
    }
}
