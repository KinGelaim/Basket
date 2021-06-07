using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Windows.Forms;

namespace Search_Cursor
{
    public partial class Form1 : Form
    {
        int xBox;
        int yBox;

        int xMouse;
        int yMouse;

        Graphics g;
        Bitmap bitmap;

        Timer timer;

        public Form1()
        {
            InitializeComponent();
            bitmap = new Bitmap(pictureBox1.Width, pictureBox1.Height);
            g = Graphics.FromImage(bitmap);

            xBox = pictureBox1.Width / 2;
            yBox = pictureBox1.Height / 2;

            /*timer = new Timer();
            timer.Enabled = true;
            timer.Interval = 1;
            timer.Tick += timerTick;
            timer.Start();*/
        }

        private void pictureBox1_Paint(object sender, PaintEventArgs e)
        {
            DrawBox();
            MoveBox();
            pictureBox1.Image = bitmap;
        }

        private void pictureBox1_MouseMove(object sender, MouseEventArgs e)
        {
            xMouse = e.Location.X;
            yMouse = e.Location.Y;
        }

        private void DrawBox()
        {
            g.Clear(Color.White);
            g.FillRectangle(Brushes.Black, xBox, yBox, 30, 30);
        }

        private void MoveBox()
        {
            if (xBox - xMouse != 0)
            {
                double k = (Convert.ToDouble(yMouse) - Convert.ToDouble(yBox)) / (Convert.ToDouble(xMouse) - Convert.ToDouble(xBox));
                double b = Convert.ToDouble(yBox) - k * Convert.ToDouble(xBox);
                //double angle = (Math.Atan(Math.Tan(k * Convert.ToDouble(xMouse))) * 180 / Math.PI) % 360;
                //if(angle <= -30 && angle >= 30)
                    if (xMouse > xBox)
                    {
                        xBox++;
                        yBox = Convert.ToInt32(k * xBox + b);
                    }
                    else
                    {
                        xBox--;
                        yBox = Convert.ToInt32(k * xBox + b);
                    }
                /*else
                    if (yMouse > yBox)
                    {
                        yBox++;
                        xBox = Convert.ToInt32(Math.Ceiling((yBox - b) / k));
                    }
                    else
                    {
                        yBox--;
                        xBox = Convert.ToInt32(Math.Ceiling((yBox - b) / k));
                    }*/
            }
        }

        private void MoveBox2()
        {
            double atan = Math.Atan2(yMouse, xMouse);
            xBox = Convert.ToInt32(xBox - Math.Sin(atan));
            yBox = Convert.ToInt32(yBox - Math.Cos(atan));
        }

        private void timerTick(object sender, EventArgs e)
        {

        }
    }
}
