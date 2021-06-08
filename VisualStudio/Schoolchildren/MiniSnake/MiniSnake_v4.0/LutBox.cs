using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

namespace MiniSnake_v4._0
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
