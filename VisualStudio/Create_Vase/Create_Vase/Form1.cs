using System;
using System.Collections.Generic;
using System.Drawing;
using System.Windows.Forms;

namespace Create_Vase
{
    public partial class Form1 : Form
    {
        private List<Point> pointsList = new List<Point>();
        private bool isMouseDown = false;

        Graphics g;
        Bitmap bitmap;

        public Form1()
        {
            InitializeComponent();

            isMouseDown = false;

            bitmap = new Bitmap(this.Width, this.Height);
            BackgroundImage = bitmap;
            g = Graphics.FromImage(BackgroundImage);

            this.DoubleBuffered = true;

            g.Clear(Color.White);
        }

        private void Form1_Paint(object sender, PaintEventArgs e)
        {
            PaintPoints();
            MovePoints();
            this.Invalidate();
        }

        private void PaintPoints()
        {
            if (pointsList.Count > 0)
                foreach (Point point in pointsList.ToArray())
                {
                    if (bitmap.GetPixel(Convert.ToInt32(point.posX), Convert.ToInt32(point.posY)).R < 100 && bitmap.GetPixel(Convert.ToInt32(point.posX), Convert.ToInt32(point.posY)).G < 100 && bitmap.GetPixel(Convert.ToInt32(point.posX), Convert.ToInt32(point.posY)).B < 100)
                        g.FillRectangle(Brushes.Black, Convert.ToInt32(point.posX), Convert.ToInt32(point.posY), 4, 4);
                    else
                        g.FillRectangle(point.brush, Convert.ToInt32(point.posX), Convert.ToInt32(point.posY), 4, 4);
                }
        }

        private void MovePoints()
        {
            if (pointsList.Count > 0)
                foreach (Point point in pointsList.ToArray())
                {
                    if (point.isRight)
                        point.posX++;
                    else
                        point.posX--;
                    if (point.posX < this.Width / 2)
                        point.posY -= 0.2;
                    else
                        point.posY += 0.2;
                    CheckPath();
                }
        }

        private void CheckPath()
        {
            foreach (Point point in pointsList.ToArray())
            {
                if (point.posX >= (this.Width / 2 + (this.Width / 2 - point.bPosX)))
                {
                    point.isRight = false;
                    point.brush = Brushes.Black;
                }
                if(point.posX < point.bPosX)
                    pointsList.Remove(point);
            }
        }

        private void Form1_MouseDown(object sender, MouseEventArgs e)
        {
            isMouseDown = true;
        }

        private void Form1_MouseMove(object sender, MouseEventArgs e)
        {
            if(isMouseDown)
            {
                pointsList.Add(new Point(e.Location.X, e.Location.Y));
            }
        }

        private void Form1_MouseUp(object sender, MouseEventArgs e)
        {
            isMouseDown = false;
        }
    }
}
