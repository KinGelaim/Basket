using System;

namespace MiniAirplane
{
    class Enemy
    {
        public int posX { get; set; }

        public int posY { get; set; }

        public Enemy()
        {

        }

        public Enemy(int posX, int posY)
        {
            this.posX = posX;
            this.posY = posY;
        }
    }
}