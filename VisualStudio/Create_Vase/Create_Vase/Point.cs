using System.Drawing;

namespace Create_Vase
{
    class Point
    {
        public double bPosX { get; set; }
        public double bPosY { get; set; }
        public double posX { get; set; }
        public double posY { get; set; }
        public Brush brush { get; set; }
        public bool isRight { get; set; }
        //public bool isStop { get; set; }

        public Point(int posX, int posY)
        {
            this.bPosX = this.posX = posX;
            this.bPosY = this.posY = posY;
            this.brush = Brushes.Gray;
            this.isRight = true;
            //this.isStop = false;
        }
    }
}
