using System;


namespace MiniSnake_v5._0
{
    class Level
    {
        private int weightLevel;
        private int heightLevel;

        public Level(int weightLevel, int heightLevel)
        {
            this.weightLevel = weightLevel;
            this.heightLevel = heightLevel;
        }

        public void ShowLevel()
        {
            Console.SetCursorPosition(0, 0);
            Console.ForegroundColor = ConsoleColor.Yellow;
            for (int i = 0; i < weightLevel; i++)
            {
                Console.Write("♦");
            }
            for (int i = 0; i < heightLevel; i++)
            {
                Console.SetCursorPosition(0, i);
                Console.Write("♦");
                Console.SetCursorPosition(weightLevel - 1, i);
                Console.Write("♦");
            }
            Console.Write('\r');
            for (int i = 0; i < weightLevel; i++)
            {
                Console.Write("♦");
            }
            Console.ForegroundColor = ConsoleColor.Gray;
            Console.WriteLine();
        }

        public bool CheckSnakeDie(Point head)
        {
            if (head.x <= 0 || head.y <= 0 || head.x >= weightLevel - 1 || head.y >= heightLevel - 1)
            {
                return true;
            }
            return false;
        }

        public bool CheckWinLevel(int snakeLen)
        {
            if (snakeLen == 500)
                return true;
            return false;
        }
    }
}