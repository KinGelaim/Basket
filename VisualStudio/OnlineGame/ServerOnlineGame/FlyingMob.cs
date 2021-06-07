using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

namespace ServerOnlineGame
{
    class FlyingMob
    {
        public int life { get; set; }
        public int posX { get; set; }
        public int posY { get; set; }

        public List<Bullet> bulletsList = new List<Bullet>();

        static public List<FlyingMob> allMob = new List<FlyingMob>();

        public FlyingMob(int life, int posX, int posY)
        {
            this.life = life;
            this.posX = posX;
            this.posY = posY;
        }
    }
}
