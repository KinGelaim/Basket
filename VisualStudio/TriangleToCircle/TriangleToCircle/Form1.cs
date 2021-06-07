using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Windows.Forms;

namespace TriangleToCircle
{
    public partial class Form1 : Form
    {
        private Graphics g;
        private Bitmap bitmap;

        private bool isHaveTriangle = false;    //Создан ли уже треугольник?
        private bool isMouseDown = false;       //Нажата ли клавиша мыши по области

        private int beginX = 0;
        private int beginY = 0;

        private List<Point> points = new List<Point>();

        public Form1()
        {
            InitializeComponent();

            isHaveTriangle = false;

            bitmap = new Bitmap(this.Width, this.Height);
            g = Graphics.FromImage(bitmap);
        }

        private void pictureBox1_Paint(object sender, PaintEventArgs e)
        {
            g.Clear(Color.White);
            DrawPoints();

            // Треугольник
            Point[] a = points.ToArray();

            if (points.Count > 2)
            {

                g.DrawLine(Pens.Red, a[0], a[1]);
                g.DrawString("A", new Font("Times New Roman", 12), Brushes.Black, a[0]);
                g.DrawString("B", new Font("Times New Roman", 12), Brushes.Black, a[1]);
                g.DrawLine(Pens.Red, a[1], a[2]);
                g.DrawString("C", new Font("Times New Roman", 12), Brushes.Black, a[2]);
                g.DrawLine(Pens.Red, a[2], a[0]);

                // Длины сторон
                double s1 = SideLength(a[0].X, a[0].Y, a[1].X, a[1].Y);
                double s2 = SideLength(a[1].X, a[1].Y, a[2].X, a[2].Y);
                double s3 = SideLength(a[2].X, a[2].Y, a[0].X, a[0].Y);

                // Полупериметр
                double p = (Perimetr(s1, s2, s3)) / 2;

                // Площадь
                double s = Sqare(s1, s2, s3);

                // Радиусы
                double r1 = Radius1(s, p);
                double r2 = Radius2(s, s1, s2, s3);

                // Углы
                double ang1 = Angles(a[2], a[0], a[1]);
                double ang2 = Angles(a[0], a[1], a[2]);
                double ang3 = Angles(a[1], a[2], a[0]);

                // Середины сторон s1 и s2
                PointF m1 = new PointF();
                PointF m2 = new PointF();
                m1.X = (a[0].X + a[1].X) / 2;
                m1.Y = (a[0].Y + a[1].Y) / 2;
                m2.X = (a[1].X + a[2].X) / 2;
                m2.Y = (a[1].Y + a[2].Y) / 2;

                // Необходимо повернуть точки на 90 градусов
                // Поворачиваем точку a[2] вокруг середины стороны s
                double x1 = a[2].X;
                double y1 = a[2].Y;
                x1 -= m2.X;
                y1 -= m2.Y;
                PointF b1 = new PointF();
                b1.X = (float)(x1 * Math.Cos(Math.PI / 2) - y1 * Math.Sin(Math.PI / 2));
                b1.Y = (float)(x1 * Math.Sin(Math.PI / 2) + y1 * Math.Cos(Math.PI / 2));

                // Поворачиваем точку a[0] вокруг середины стороны s1
                x1 = a[0].X;
                y1 = a[0].Y;
                x1 -= m1.X;
                y1 -= m1.Y;
                PointF b2 = new PointF();
                b2.X = (float)(x1 * Math.Cos(Math.PI / 2) - y1 * Math.Sin(Math.PI / 2));
                b2.Y = (float)(x1 * Math.Sin(Math.PI / 2) + y1 * Math.Cos(Math.PI / 2));

                // Описанная окражность с центром в точке пересечения серединных перпендикуляров
                DrawCircle(r2, (int)m1.X, (int)m1.Y, (int)m2.X, (int)m2.Y, b2.X, b2.Y, b1.X, b1.Y);

                // Коэффициенты, указывающие направление поворота
                int coef1 = 1, coef2 = 2;

                // Вычисляем знак третьей координаты векторного произведения векторов, между которыми строится биссектриса
                double det = (a[1].X - a[0].X) * (a[2].Y - a[0].Y) - (a[2].X - a[0].X) * (a[1].Y - a[0].Y);
                if (det > 0)
                    coef1 = -1;
                det = (a[1].X - a[2].X) * (a[0].Y - a[2].Y) - (a[0].X - a[2].X) * (a[1].Y - a[2].Y);
                if (det > 0)
                    coef2 = -1;

                // Поворачиваем точку a[2] вокруг a[0] на угол равный половине угла ang2
                x1 = a[2].X;
                y1 = a[2].Y;
                x1 -= a[0].X;
                y1 -= a[0].Y;
                b1.X = (float)(x1 * Math.Cos(coef1 * ang2 / 2) - y1 * Math.Sin(coef1 * ang2 / 2));
                b1.Y = (float)(x1 * Math.Sin(coef1 * ang2 / 2) + y1 * Math.Cos(coef1 * ang2 / 2));

                x1 = a[0].X;
                y1 = a[0].Y;
                x1 -= a[2].X;
                y1 -= a[2].Y;
                b2.X = (float)(x1 * Math.Cos(coef2 * ang1 / 2) - y1 * Math.Sin(coef2 * ang1 / 2));
                b2.Y = (float)(x1 * Math.Sin(coef2 * ang1 / 2) + y1 * Math.Cos(coef2 * ang1 / 2));

                // Построение вписанной окружности в точки пересечения биссектрисс
                DrawCircle(r1, (int)a[2].X, (int)a[2].Y, (int)a[0].X, (int)a[0].Y, b2.X, b2.Y, b1.X, b1.Y);
            }

            pictureBox1.Image = bitmap;
        }

        private void pictureBox1_MouseDown(object sender, MouseEventArgs e)
        {
            if (isHaveTriangle)
            {
                isMouseDown = true;
                beginX = e.X;
                beginY = e.Y;
            }
        }

        private void pictureBox1_MouseMove(object sender, MouseEventArgs e)
        {
            if(isHaveTriangle)
                if(isMouseDown)
                {
                    int prX = beginX - e.X;
                    int prY = beginY - e.Y;
                    for (int i = 0; i < points.Count; i++)
                    {
                        points[i] = new Point((int)points[i].X - prX, (int)points[i].Y - prY);
                    }
                    beginX = e.X;
                    beginY = e.Y;
                }
        }

        private void pictureBox1_MouseUp(object sender, MouseEventArgs e)
        {
            if (!isHaveTriangle)
            {
                points.Add(new Point(e.X, e.Y));
                if (points.Count >= 3)
                {
                    isHaveTriangle = true;
                    CheckCircle();
                }
            }
            isMouseDown = false;
        }

        //Отрисовка точек
        private void DrawPoints()
        {
            for (int i = 0; i < points.Count; i++)
                if (i + 1 < points.Count)
                    g.DrawLine(Pens.Black, points[i], points[i + 1]);
                else
                    g.DrawLine(Pens.Black, points[i], points[0]);
        }

        //Отрисовка окружности
        private void DrawCircle(double R, int x01, int y01, int x02, int y02, double l1, double m1, double l2, double m2)
        {
            l1 = l1 == 0 ? 1 : l1;
            l2 = l2 == 0 ? 1 : l2;
            double x, y;
            if((l1 != 0) && ((m1 / l2) - (m2/l2)!= 0) && (l2 != 0))
            {
                x = ((m1 / l1) * x01 - y01 - (m2 / l2) * x02 + y02) / ((m1 / l1) - (m2 / l2));
                y = (m1 * x - m1 * x01 + l1 * y01) / l1;
                g.DrawEllipse(Pens.Black, (int)x, (int)y, 1, 1);  // Центр окружности
                g.DrawEllipse(Pens.Black, (int)x - (float)R, (int)y - (float)R, (float)R * 2, (float)R * 2); // Рисуем окружность
            }
        }

        #region Вспомогательные функции для треугольника

        // Нахождение длины треугольника
        private double SideLength(double x1, double y1, double x2, double y2)
        {
            double a = Math.Sqrt((x2 - x1) * (x2 - x1) + (y2 - y1) * (y2 - y1));
            return a;
        }

        // Нахождение углов треугольника
        private double Angles(Point p1, Point p2, Point p3)
        {
            double a = (p2.X - p1.X) * (p3.X - p1.X) + (p2.Y - p1.Y) * (p3.Y - p1.Y);
            double exp = a / SideLength(p1.X, p1.Y, p2.X, p2.Y) / SideLength(p1.X, p1.Y, p3.X, p3.Y);
            return Math.Acos(exp);
        }

        // Периметр треугольника
        private double Perimetr(double s1, double s2, double s3)
        {
            return s1 + s2 + s3;
        }

        // Площадь треугольника (формула Герона)
        private double Sqare(double s1, double s2, double s3)
        {
            double p = (Perimetr(s1, s2, s3)) / 2;
            return Math.Sqrt((p * (p - s1) * (p - s2) * (p - s3)));
        }

        // Радиусы вписанной и описанной окружности
        private double Radius1(double s, double p)
        {
            return s / p;
        }

        private double Radius2(double s, double s1, double s2, double s3)
        {
            return (s1 * s2 * s3) / (4 * s);
        }

        #endregion

        //Функция для проверки лежат ли переменные на окружности (окружность строится на основе первых 2 точек, а остальные подгоняются)
        private void CheckCircle()
        {
            if (points.Count > 2)
            {
                //Находим сам круг

                //Проверяем точки и если не лежат на окружности, то смещает с помощью отдельной функции (дальше пригодиться)

            }
        }

        //Проверка конкретной точки с кругом
        private bool CheckOnCircle(Point point, int R)
        {
            return false;
        }

        //Смещение точки на круг
        private void MovePointToCircle(Point point, int R)
        {

        }

        //Проверка на самое длинное растояние между точками (возвращается позиция последней точки)
        private int BigLenCircle()
        {
            return 0;
        }

        //Проверка на самое короткое растояние между точками (возвращается позиция последней точки)
        private int SmallLenCircle()
        {
            return 0;
        }
    }
}
