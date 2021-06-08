using System;


namespace MiniSnake_v5._0
{
    class LutBox
    {
        public ConsoleColor Color { get; set; }
        public bool Unblock { get; set; }

        public LutBox(ConsoleColor color)
        {
            Color = color;
            Unblock = false;
        }
    }
}