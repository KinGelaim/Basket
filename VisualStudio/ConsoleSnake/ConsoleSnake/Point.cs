using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

namespace ConsoleSnake
{
    class Point
    {
        public int PosX { get; set; }

        private int posY;

        public int PosY
        {
            get 
            { 
                return posY; 
            }

            set 
            { 
                posY = value; 
            }
        }

        public Point() { }

        public Point(int posX, int posY)
        {
            this.PosX = posX;
            this.PosY = posY;
        }
    }
}
