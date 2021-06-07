using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

namespace ServerOnlineGame
{
    class Bullet
    {
        public int posX { get; set; }
        public int posY { get; set; }

        public Bullet(int posX, int posY)
        {
            this.posX = posX;
            this.posY = posY;
        }
    }
}
