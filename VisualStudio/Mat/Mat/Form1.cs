using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Windows.Forms;

namespace Mat
{
    public partial class Form1 : Form
    {
        Bitmap bitmap;
        Graphics g;
        int red = 0, green = 0, blue = 0;
        bool isResize = true;

        public Form1()
        {
            InitializeComponent();
            bitmap = new Bitmap(pictureBox1.Width, pictureBox1.Height);
            g = Graphics.FromImage(bitmap);
        }

        private void pictureBox1_Paint(object sender, PaintEventArgs e)
        {
            if (isResize)
            {
                //Находим длину одного сектора
                double lenOneSector = Convert.ToDouble(pictureBox1.Width) / 4;
                //Находим коэфицент покрытия
                double koef = 255 / lenOneSector;
                red = 0;
                green = 0;
                blue = 0;
                for (int i = 0; i < pictureBox1.Width; i++)
                {
                    if (red == 0 && green == 0 && blue < 255)
                        blue += Convert.ToInt32(Math.Ceiling(koef));
                    if (red == 0 && green < 255 && blue >= 255)
                        green += Convert.ToInt32(Math.Ceiling(koef));
                    if (red == 0 && green >= 255 && blue > 0)
                        blue -= Convert.ToInt32(Math.Ceiling(koef));
                    if (red < 255 && green >= 255 && blue <= 0)
                        red += Convert.ToInt32(Math.Ceiling(koef));
                    ProverkaGranic();
                    int x = i, y = 0;
                    g.FillRectangle(new SolidBrush(Color.FromArgb(red, green, blue)), x, y, Convert.ToInt32(Math.Ceiling(koef)), Convert.ToInt32(Math.Ceiling(koef)));
                    while (x - Convert.ToInt32(Math.Ceiling(koef)) >= 0 && y + Convert.ToInt32(Math.Ceiling(koef)) <= pictureBox1.Height)
                    {
                        x -= Convert.ToInt32(Math.Ceiling(koef));
                        y += Convert.ToInt32(Math.Ceiling(koef));
                        g.FillRectangle(new SolidBrush(Color.FromArgb(red, green, blue)), x, y, Convert.ToInt32(Math.Ceiling(koef)), Convert.ToInt32(Math.Ceiling(koef)));
                    }
                }
                //Находим длину одного сектора (уже по высоте)
                lenOneSector = Convert.ToDouble(pictureBox1.Height) / 4;
                //Находим коэфицент покрытия
                koef = 255 / lenOneSector;
                for (int i = 0; i < pictureBox1.Height; i++)
                {
                    if (red >= 255 && green >= 255 && blue < 255)
                        blue += Convert.ToInt32(Math.Ceiling(koef));
                    if (red >= 255 && green > 0 && blue >= 255)
                        green -= Convert.ToInt32(Math.Ceiling(koef));
                    if (red >= 255 && green <= 0 && blue > 0)
                        blue -= Convert.ToInt32(Math.Ceiling(koef));
                    if (red > 0 && green <= 0 && blue <= 0)
                        red -= Convert.ToInt32(Math.Ceiling(koef));
                    ProverkaGranic();
                    int x = pictureBox1.Width - Convert.ToInt32(Math.Ceiling(koef));
                    int y = i;
                    g.FillRectangle(new SolidBrush(Color.FromArgb(red, green, blue)), x, y, Convert.ToInt32(Math.Ceiling(koef)), Convert.ToInt32(Math.Ceiling(koef)));
                    while (x - Convert.ToInt32(Math.Ceiling(koef)) >= 0 && y - Convert.ToInt32(Math.Ceiling(koef)) >= 0)
                    {
                        x -= Convert.ToInt32(Math.Ceiling(koef));
                        y += Convert.ToInt32(Math.Ceiling(koef));
                        g.FillRectangle(new SolidBrush(Color.FromArgb(red, green, blue)), x, y, Convert.ToInt32(Math.Ceiling(koef)), Convert.ToInt32(Math.Ceiling(koef)));
                    }
                }
                pictureBox1.Image = bitmap;
                isResize = false;
            }
        }

        private void ProverkaGranic()
        {
            if (red > 255)
                red = 255;
            if (blue > 255)
                blue = 255;
            if (green > 255)
                green = 255;
            if (red < 0)
                red = 0;
            if (blue < 0)
                blue = 0;
            if (green < 0)
                green = 0;
        }

        private void Form1_ResizeEnd(object sender, EventArgs e)
        {
            bitmap = new Bitmap(pictureBox1.Width, pictureBox1.Height);
            g = Graphics.FromImage(bitmap);
            isResize = true;
        }
    }
}