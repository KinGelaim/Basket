using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

namespace RacingGame
{
    class Enemy
    {
        public int posX { get; set; }
        public int posY { get; set; }
        public bool isLeft { get; set; }

        public Enemy(int posX, bool isLeft = true)
        {
            this.posX = posX;
            this.posY = -20;
            this.isLeft = isLeft;
        }
    }
}