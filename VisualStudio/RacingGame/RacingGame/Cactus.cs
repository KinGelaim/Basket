using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

namespace RacingGame
{
    class Cactus
    {
        public int posX { get; set; }
        public int posY { get; set; }
        public int width { get; set; }
        public int height { get; set; }
        public bool isLeft { get; set; }

        public List<Igolka> igolkaList = new List<Igolka>();

        public Cactus(int posX, int posY, int width, int height, bool isLeft = true)
        {
            this.posX = posX;
            this.posY = posY;
            this.width = width;
            this.height = height;
            this.isLeft = isLeft;
        }
    }

    class Igolka
    {
        public int bPosX { get; set; }
        public int bPosY { get; set; }
        public int ePosX { get; set; }
        public int ePosY { get; set; }

        public Igolka(Cactus cactus, Random rand)
        {
            bPosX = rand.Next(0, cactus.width);
            bPosY = rand.Next(0, cactus.height - 5);
            ePosX = bPosX < cactus.width / 2 ? rand.Next(-7, cactus.width / 2 - 7) : rand.Next(cactus.width / 2 + 7, cactus.width + 7);
            ePosY = bPosY - rand.Next(3, 20);
        }
    }
}
