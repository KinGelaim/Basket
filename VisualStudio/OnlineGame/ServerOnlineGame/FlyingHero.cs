using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

namespace ServerOnlineGame
{
    class FlyingHero
    {
        public int life { get; set; }
        public int posX { get; set; }
        public int posY { get; set; }
        public int score { get; set; }

        public List<Bullet> bulletsList = new List<Bullet>();

        public FlyingHero(int life, int posX, int posY)
        {
            this.life = life;
            this.posX = posX;
            this.posY = posY;
        }
    }
}
