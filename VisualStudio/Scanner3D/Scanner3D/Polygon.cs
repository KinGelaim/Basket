using System;
using System.Drawing;

namespace Scanner3D
{
    public sealed class Polygon
    {
        private PointF[] points; //вершины нашего многоугольника
        private Triangle[] triangles; //треугольники, на которые разбит наш многоугольник
        private bool[] taken; //была ли рассмотрена i-ая вершина многоугольника
        private Point[] fragmentTriangles;

        public Polygon(float[] points, bool isLeftVectors = true) //points - х и y координаты
        {
            if (points.Length % 2 == 1 || points.Length < 6)
                throw new Exception("Походу не многоугольник или точек не чётное кол-во (x,y)!"); //ошибка, если не многоугольник

            this.points = new PointF[points.Length / 2]; //преобразуем координаты в вершины
            for (int i = 0; i < points.Length; i += 2)
                this.points[i / 2] = new PointF(points[i], points[i + 1]);

            triangulate(isLeftVectors); //триангуляция
        }

        // Триангуляция
        private void triangulate(bool isLeftVectors = true)
        {
            triangles = new Triangle[this.points.Length - 2];
            fragmentTriangles = new Point[this.points.Length - 2];

            taken = new bool[this.points.Length];

            int trainPos = 0; //
            int leftPoints = points.Length; //сколько осталось рассмотреть вершин

            //текущие вершины рассматриваемого треугольника
            int ai = findNextNotTaken(0);
            int bi = findNextNotTaken(ai + 1);
            int ci = findNextNotTaken(bi + 1);

            int count = 0; //количество шагов

            while (leftPoints > 3) //пока не остался один треугольник
            {
                if (isLeftVectors)
                {
                    if (isLeft(points[ai], points[bi], points[ci]) && canBuildTriangle(ai, bi, ci)) //если можно построить треугольник
                    {
                        fragmentTriangles[trainPos] = new Point() { x = ai + 1, y = bi + 1, z = ci + 1 };
                        triangles[trainPos++] = new Triangle(points[ai], points[bi], points[ci]); //новый треугольник
                        taken[bi] = true; //исключаем вершину b
                        leftPoints--;
                        bi = ci;
                        ci = findNextNotTaken(ci + 1); //берем следующую вершину
                    }
                    else
                    { //берем следущие три вершины
                        ai = findNextNotTaken(ai + 1);
                        bi = findNextNotTaken(ai + 1);
                        ci = findNextNotTaken(bi + 1);
                    }
                }
                else
                {
                    if (isRight(points[ai], points[bi], points[ci]) && canBuildTriangle(ai, bi, ci)) //если можно построить треугольник
                    {
                        fragmentTriangles[trainPos] = new Point() { x = ai + 1, y = bi + 1, z = ci + 1 };
                        triangles[trainPos++] = new Triangle(points[ai], points[bi], points[ci]); //новый треугольник
                        taken[bi] = true; //исключаем вершину b
                        leftPoints--;
                        bi = ci;
                        ci = findNextNotTaken(ci + 1); //берем следующую вершину
                    }
                    else
                    { //берем следущие три вершины
                        ai = findNextNotTaken(ai + 1);
                        bi = findNextNotTaken(ai + 1);
                        ci = findNextNotTaken(bi + 1);
                    }
                }

                if (count > points.Length * points.Length)
                { //если по какой-либо причине (например, многоугольник задан по часовой стрелке) триангуляцию провести невозможно, выходим
                    triangles = null;
                    break;
                }

                count++;
            }

            if (triangles != null) //если триангуляция была проведена успешно
            {
                fragmentTriangles[trainPos] = new Point() { x = ai + 1, y = bi + 1, z = ci + 1 };
                triangles[trainPos] = new Triangle(points[ai], points[bi], points[ci]);
            }
        }

        // Найти следущую нерассмотренную вершину
        private int findNextNotTaken(int startPos)
        {
            startPos %= points.Length;
            if (!taken[startPos])
                return startPos;

            int i = (startPos + 1) % points.Length;
            while (i != startPos)
            {
                if (!taken[i])
                    return i;
                i = (i + 1) % points.Length;
            }

            return -1;
        }

        // Левая ли тройка векторов
        private bool isLeft(PointF a, PointF b, PointF c)
        {
            float abX = b.X - a.X;
            float abY = b.Y - a.Y;
            float acX = c.X - a.X;
            float acY = c.Y - a.Y;
            bool check = abX * acY - acX * abY < 0;
            return check;
        }

        // Правая ли тройка векторов
        private bool isRight(PointF a, PointF b, PointF c)
        {
            float abX = b.X - a.X;
            float abY = b.Y - a.Y;
            float acX = c.X - a.X;
            float acY = c.Y - a.Y;
            bool check = abX * acY - acX * abY > 0;
            return check;
        }

        // Находится ли точка p внутри треугольника abc
        private bool isPointInside(PointF a, PointF b, PointF c, PointF p)
        {
            float ab = (a.X - p.X) * (b.Y - a.Y) - (b.X - a.X) * (a.Y - p.Y);
            float bc = (b.X - p.X) * (c.Y - b.Y) - (c.X - b.X) * (b.Y - p.Y);
            float ca = (c.X - p.X) * (a.Y - c.Y) - (a.X - c.X) * (c.Y - p.Y);

            return (ab >= 0 && bc >= 0 && ca >= 0) || (ab <= 0 && bc <= 0 && ca <= 0);
        }

        private bool canBuildTriangle(int ai, int bi, int ci) //false - если внутри есть вершина
        {
            for (int i = 0; i < points.Length; i++) //рассмотрим все вершины многоугольника
                if (i != ai && i != bi && i != ci) //кроме троих вершин текущего треугольника
                    if (isPointInside(points[ai], points[bi], points[ci], points[i]))
                        return false;
            return true;
        }

        public PointF[] getPoints() //возвращает вершины
        {
            return points;
        }

        public void StartTriangulate(bool isLeftTriangle = true)
        {
            triangulate(isLeftTriangle);
        }

        public Triangle[] getTriangles() //возвращает треугольники
        {
            return triangles;
        }

        public Point[] getFragmentTriangles()
        {
            return fragmentTriangles;
        }
    }
}