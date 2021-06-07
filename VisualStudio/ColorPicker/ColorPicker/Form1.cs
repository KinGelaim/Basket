using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Windows.Forms;
using System.Threading;

namespace ColorPicker
{
    public partial class Form1 : Form
    {
        Graphics g;
        Bitmap bitmap;
        int posMouseX, posMouseY;

        bool isMouseDown = false;

        public Form1()
        {
            InitializeComponent();
        }

        private void Form1_Load(object sender, EventArgs e)
        {
            bitmap = new Bitmap(pictureBox1.Width, pictureBox1.Height);
            g = Graphics.FromImage(bitmap);
            System.Drawing.Drawing2D.GraphicsPath path = new System.Drawing.Drawing2D.GraphicsPath();
            path.AddEllipse(0, 0, pictureBox1.Width, pictureBox1.Height);
            //path.AddEllipse(pictureBox1.Width / 4, pictureBox1.Height / 4, pictureBox1.Width / 2, pictureBox1.Height / 2);
            Region region = new Region(path);
            pictureBox1.Region = region;
            pictureBox1.BackColor = Color.White;
        }

        private void pictureBox1_Paint(object sender, PaintEventArgs e)
        {
            //Создаем колор пикер
            CreateColorPicker(g, pictureBox1.Width, pictureBox1.Height);
            //Загрузка изображения
            pictureBox1.Image = bitmap;
        }

        private void CreateColorPicker(Graphics g, int width, int height)
        {
            //Длина ячейки в одном секторе
            double lenSector = Convert.ToDouble(width) / 2 / 255;
            //Три параметра цвета для квадрата
            int colorRed = 0;
            int colorGreen = 0;
            int colorBlue = 0;
            float x1 = 0;
            float y1 = 0;
            float x2 = width / 2;
            float y2 = height / 2;
            float oldX1 = 0;
            float oldY1 = 0;
            //Закрашиваем большой градиентный квадрат квадрат
            for (double i = 0; i <= 255; i += lenSector)
            {
                x1 = oldX1 = width / 2 + Convert.ToInt32(Math.Ceiling(i));
                y1 = oldY1 = 0;
                g.DrawLine(new Pen(Color.FromArgb(0, 0, Convert.ToInt32(Math.Ceiling(i)))), x1, y1, x2, y2);
                if (posMouseX >= width / 2 && posMouseY <= height / 2)
                {
                    double pogreshnost = posMouseX - ((posMouseY - y1) * (x2 - x1) / (y2 - y1) + x1);
                    if (-0.7 < pogreshnost && pogreshnost < 0.7)
                    {
                        colorRed = 0;
                        colorGreen = 0;
                        colorBlue = Convert.ToInt32(Math.Ceiling(i));
                        //Полоска выбора
                        g.DrawLine(new Pen(Color.FromArgb(255, 255, 255)), x1, y1, x2, y2);
                    }
                }
            }
            for (double i = 0; i <= 255; i += lenSector)
            {
                x1 = width;
                y1 = Convert.ToInt32(Math.Ceiling(i));
                g.DrawLine(new Pen(Color.FromArgb(0, Convert.ToInt32(Math.Ceiling(i)), 255)), x1, y1, x2, y2);
                if (posMouseX >= width / 2 && posMouseY <= height / 2)
                {
                    double pogreshnost = posMouseY - ((posMouseX - x1) * (y2 - y1) / (x2 - x1) + y1);
                    if (-0.7 < pogreshnost && pogreshnost < 0.7)
                    {
                        colorRed = 0;
                        colorGreen = Convert.ToInt32(Math.Ceiling(i));
                        colorBlue = 255;
                        //Полоска выбора
                        g.DrawLine(new Pen(Color.FromArgb(255, 255, 255)), x1, y1, x2, y2);
                    }
                }
            }
            for (double i = 255; i >= 0; i -= lenSector)
            {
                x1 = width;
                y1 = height - Convert.ToInt32(Math.Ceiling(i));
                g.DrawLine(new Pen(Color.FromArgb(0, 255, Convert.ToInt32(Math.Ceiling(i)))), x1, y1, x2, y2);
                if (posMouseX >= width / 2 && posMouseY >= height / 2)
                {
                    double pogreshnost = posMouseY - ((posMouseX - x1) * (y2 - y1) / (x2 - x1) + y1);
                    if (-0.7 < pogreshnost && pogreshnost < 0.7)
                    {
                        colorRed = 0;
                        colorGreen = 255;
                        colorBlue = Convert.ToInt32(Math.Ceiling(i));
                        //Полоска выбора
                        g.DrawLine(new Pen(Color.FromArgb(255, 255, 255)), x1, y1, x2, y2);
                    }
                }
            }
            for (double i = 0; i <= 255; i += lenSector)
            {
                x1 = width - Convert.ToInt32(Math.Ceiling(i));
                y1 = height;
                g.DrawLine(new Pen(Color.FromArgb(Convert.ToInt32(Math.Ceiling(i)), 255, 0)), x1, y1, x2, y2);
                if (posMouseX >= width / 2 && posMouseY >= height / 2)
                {
                    double pogreshnost = posMouseX - ((posMouseY - y1) * (x2 - x1) / (y2 - y1) + x1);
                    if (-0.7 < pogreshnost && pogreshnost < 0.7)
                    {
                        colorRed = Convert.ToInt32(Math.Ceiling(i));
                        colorGreen = 255;
                        colorBlue = 0;
                        //Полоска выбора
                        g.DrawLine(new Pen(Color.FromArgb(255, 255, 255)), x1, y1, x2, y2);
                    }
                }
            }
            for (double i = 0; i <= 255; i += lenSector)
            {
                x1 = width / 2 - Convert.ToInt32(Math.Ceiling(i));
                y1 = height;
                g.DrawLine(new Pen(Color.FromArgb(255, 255, Convert.ToInt32(Math.Ceiling(i)))), x1, y1, x2, y2);
                if (posMouseX <= width / 2 && posMouseY >= height / 2)
                {
                    double pogreshnost = posMouseX - ((posMouseY - y1) * (x2 - x1) / (y2 - y1) + x1);
                    if (-0.7 < pogreshnost && pogreshnost < 0.7)
                    {
                        colorRed = 255;
                        colorGreen = 255;
                        colorBlue = Convert.ToInt32(Math.Ceiling(i));
                        //Полоска выбора
                        g.DrawLine(new Pen(Color.FromArgb(255, 255, 255)), x1, y1, x2, y2);
                    }
                }
            }
            for (double i = 0; i <= 255; i += lenSector)
            {
                x1 = 0;
                y1 = height / 2 + Convert.ToInt32(Math.Ceiling(i));
                g.DrawLine(new Pen(Color.FromArgb(255, Convert.ToInt32(Math.Ceiling(i)), 255)), x1, y1, x2, y2);
                if (posMouseX <= width / 2 && posMouseY >= height / 2)
                {
                    double pogreshnost = posMouseY - ((posMouseX - x1) * (y2 - y1) / (x2 - x1) + y1);
                    if (-0.7 < pogreshnost && pogreshnost < 0.7)
                    {
                        colorRed = 255;
                        colorGreen = Convert.ToInt32(Math.Ceiling(i));
                        colorBlue = 255;
                        //Полоска выбора
                        g.DrawLine(new Pen(Color.FromArgb(255, 255, 255)), x1, y1, x2, y2);
                    }
                }
            }
            for (double i = 0; i <= 255; i += lenSector)
            {
                x1 = 0;
                y1 = Convert.ToInt32(Math.Ceiling(i));
                g.DrawLine(new Pen(Color.FromArgb(255, 0, Convert.ToInt32(Math.Ceiling(i)))), x1, y1, x2, y2);
                if (posMouseX <= width / 2 && posMouseY <= height / 2)
                {
                    double pogreshnost = posMouseY - ((posMouseX - x1) * (y2 - y1) / (x2 - x1) + y1);
                    if (-0.7 < pogreshnost && pogreshnost < 0.7)
                    {
                        colorRed = 255;
                        colorGreen = 0;
                        colorBlue = Convert.ToInt32(Math.Ceiling(i));
                        //Полоска выбора
                        g.DrawLine(new Pen(Color.FromArgb(255, 255, 255)), x1, y1, x2, y2);
                    }
                }
            }
            for (double i = 0; i <= 255; i += lenSector)
            {
                x1 = width / 2 - Convert.ToInt32(Math.Ceiling(i));
                y1 = 0;
                g.DrawLine(new Pen(Color.FromArgb(Convert.ToInt32(Math.Ceiling(i)), 0, 0)), x1, y1, x2, y2);
                if (posMouseX <= width / 2 && posMouseY <= height / 2)
                {
                    double pogreshnost = posMouseX - ((posMouseY - y1) * (x2 - x1) / (y2 - y1) + x1);
                    if (-0.7 < pogreshnost && pogreshnost < 0.7)
                    {
                        colorRed = Convert.ToInt32(Math.Ceiling(i));
                        colorGreen = 0;
                        colorBlue = 0;
                        //Полоска выбора
                        g.DrawLine(new Pen(Color.FromArgb(255, 255, 255)), x1, y1, x2, y2);
                    }
                }
            }
            //Внутренний круг
            g.FillEllipse(Brushes.White, width / 4, height / 4, width / 2, height / 2);
            //Внешний круг
            //g.DrawEllipse(Pens.Black, 0, 0, width, height);
            //Центральный квадрат
            g.FillRectangle(new SolidBrush(Color.FromArgb(colorRed, colorGreen, colorBlue)), width / 3, height / 3, width / 3, height / 3);
        }

        private void Form1_ResizeEnd(object sender, EventArgs e)
        {
            System.Drawing.Drawing2D.GraphicsPath path = new System.Drawing.Drawing2D.GraphicsPath();
            path.AddEllipse(0, 0, pictureBox1.Width, pictureBox1.Height);
            Region region = new Region(path);
            pictureBox1.Region = region;
            pictureBox1.BackColor = Color.White;
            bitmap = new Bitmap(pictureBox1.Width, pictureBox1.Height);
            g = Graphics.FromImage(bitmap);
            CreateColorPicker(g, pictureBox1.Width, pictureBox1.Height);
            pictureBox1.Image = bitmap;
        }

        private void pictureBox1_MouseClick(object sender, MouseEventArgs e)
        {
            //g.DrawString(this.Size.Width + " " + this.Size.Height, new Font("Tahoma", 17, FontStyle.Regular), Brushes.Red, pictureBox1.Width / 2 - 40, pictureBox1.Height / 2 - 40);
            posMouseX = e.X;
            posMouseY = e.Y;
        }

        private void pictureBox1_MouseDown(object sender, MouseEventArgs e)
        {
            isMouseDown = true;
        }

        private void pictureBox1_MouseMove(object sender, MouseEventArgs e)
        {
            if (isMouseDown)
            {
                posMouseX = e.X;
                posMouseY = e.Y;
            }
        }

        private void pictureBox1_MouseUp(object sender, MouseEventArgs e)
        {
            isMouseDown = false;
        }
    }
}