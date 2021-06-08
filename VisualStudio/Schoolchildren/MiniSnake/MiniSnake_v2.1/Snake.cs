using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

namespace MiniSnake_v2._1
{
    class Snake
    {
        private Point[] snake;

        public Snake()
        {
            snake = new Point[3];
            snake[0] = new Point(12, 10);
            snake[1] = new Point(11, 10);
            snake[2] = new Point(10, 10);
        }

        public void ShowSnake()
        {
            Console.ForegroundColor = ConsoleColor.Green;
            for (int i = 0; i < snake.Length; i++)
            {
                Console.SetCursorPosition(snake[i].x, snake[i].y);
                Console.Write("♦");
            }
            Console.ForegroundColor = ConsoleColor.Gray;
        }

        public void MoveSnake()
        {
            for (int i = snake.Length - 1; i >= 0; i--)
            {
                if (i != 0)
                {
                    snake[i].x = snake[i - 1].x;
                    snake[i].y = snake[i - 1].y;
                }
                else
                {
                    switch (ReadKeyConsole.direction)
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
    }
}
