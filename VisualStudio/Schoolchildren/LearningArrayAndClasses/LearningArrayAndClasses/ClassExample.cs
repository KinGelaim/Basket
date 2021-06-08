using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

namespace LearningArrayAndClasses
{
    public sealed class ClassExample
    {
        public void Start()
        {
            #region Без класса

            int triangleA1 = 10;
            int triangleB1 = 3;
            int triangleC1 = 8;

            int triangleA2 = 4;
            int triangleB2 = 4;
            int triangleC2 = 4;

            int triangleA3 = 5;
            int triangleB3 = 1;
            int triangleC3 = 5;

            int triangleA4 = 11;
            int triangleB4 = 8;
            int triangleC4 = 4;

            double triangleS1 = TriangleS(triangleA1, triangleB1, triangleC1);
            double triangleP1 = TriangleP(triangleA1, triangleB1, triangleC1);
            bool triangleRavnb1 = TriangleRavnb(triangleA1, triangleB1, triangleC1);
            bool triangleRavns1 = TriangleRavns(triangleA1, triangleB1, triangleC1);
            PrintInfoTriangle(triangleA1, triangleB1, triangleC1, triangleS1, triangleP1, triangleRavnb1, triangleRavns1);

            double triangleS2 = TriangleS(triangleA2, triangleB2, triangleC2);
            double triangleP2 = TriangleP(triangleA2, triangleB2, triangleC2);
            bool triangleRavnb2 = TriangleRavnb(triangleA2, triangleB2, triangleC2);
            bool triangleRavns2 = TriangleRavns(triangleA2, triangleB2, triangleC2);
            PrintInfoTriangle(triangleA2, triangleB2, triangleC2, triangleS2, triangleP2, triangleRavnb2, triangleRavns2);

            double triangleS3 = TriangleS(triangleA3, triangleB3, triangleC3);
            double triangleP3 = TriangleP(triangleA3, triangleB3, triangleC3);
            bool triangleRavnb3 = TriangleRavnb(triangleA3, triangleB3, triangleC3);
            bool triangleRavns3 = TriangleRavns(triangleA3, triangleB3, triangleC3);
            PrintInfoTriangle(triangleA3, triangleB3, triangleC3, triangleS3, triangleP3, triangleRavnb3, triangleRavns3);

            double triangleS4 = TriangleS(triangleA4, triangleB4, triangleC4);
            double triangleP4 = TriangleP(triangleA4, triangleB4, triangleC4);
            bool triangleRavnb4 = TriangleRavnb(triangleA4, triangleB4, triangleC4);
            bool triangleRavns4 = TriangleRavns(triangleA4, triangleB4, triangleC4);
            PrintInfoTriangle(triangleA4, triangleB4, triangleC4, triangleS4, triangleP4, triangleRavnb4, triangleRavns4);

            int rectA1 = 20;
            int rectB1 = 10;
            int rectS1 = RectS(rectA1, rectB1);
            int rectP1 = RectP(rectA1, rectB1);
            bool rectIsSquare1 = RectIsSquare(rectA1, rectB1);
            PrintInfoRectangle(rectA1, rectB1, rectS1, rectP1, rectIsSquare1);

            int rectA2 = 20;
            int rectB2 = 20;
            int rectS2 = RectS(rectA2, rectB2);
            int rectP2 = RectP(rectA2, rectB2);
            bool rectIsSquare2 = RectIsSquare(rectA2, rectB2);
            PrintInfoRectangle(rectA2, rectB2, rectS2, rectP2, rectIsSquare2);

            #endregion

            #region Без класса через массивы



            #endregion

            #region С классами

            Triangle triangle1 = new Triangle();
            triangle1.a = 10;
            triangle1.b = 3;
            triangle1.c = 8;

            triangle1.CalculatedP();
            triangle1.CalculatedS();
            triangle1.CheckRavnb();
            triangle1.CheckRavns();
            triangle1.PrintInfo();

            Triangle triangle2 = new Triangle();
            triangle2.a = 4;
            triangle2.b = 4;
            triangle2.c = 4;

            triangle2.CalculatedP();
            triangle2.CalculatedS();
            triangle2.CheckRavnb();
            triangle2.CheckRavns();
            triangle2.PrintInfo();

            Triangle triangle3 = new Triangle();
            triangle3.a = 5;
            triangle3.b = 1;
            triangle3.c = 5;

            triangle3.CalculatedP();
            triangle3.CalculatedS();
            triangle3.CheckRavnb();
            triangle3.CheckRavns();
            triangle3.PrintInfo();

            #endregion

            #region С классами через массивы

            Triangle triangleCl1 = new Triangle(10, 3, 8);
            Triangle triangleCl2 = new Triangle(4, 4, 4);
            Triangle triangleCl3 = new Triangle(5, 1, 5);
            
            List<Triangle> triangles = new List<Triangle>() { triangleCl1, triangleCl2, triangleCl3 };
            for(int i = 0; i < triangles.Count; i++)
            {
                triangles[i].CalculatedS();
                triangles[i].CalculatedP();
                triangles[i].CheckRavnb();
                triangles[i].CheckRavns();
                triangles[i].PrintInfo();
            }

            #endregion
        }

        private double TriangleS(int a, int b, int c)
        {
            int t1 = (a + b + c) / 2 - a;
            int t2 = (a + b + c) / 2 - b;
            int t3 = (a + b + c) / 2 - c;

            int t4 = t1 * t2 * t3;

            int t5 = (a + b + c) / 2 * t4;

            return Math.Sqrt(t5);
        }

        private double TriangleP(int a, int b, int c)
        {
            return a + b + c;
        }

        private bool TriangleRavnb(int a, int b, int c)
        {
            return a == b || b == c || c == a;
        }

        private bool TriangleRavns(int a, int b, int c)
        {
            return a == b && b == c && c == a;
        }

        private void PrintInfoTriangle(int a, int b, int c, double s, double p, bool ravnb, bool ravns)
        {
            Console.WriteLine("Информация о треугольнике:");
            Console.WriteLine("\tСторона А: " + a);
            Console.WriteLine("\tСторона B: " + b);
            Console.WriteLine("\tСторона C: " + c);
            Console.WriteLine("\tПлощадь: " + s);
            Console.WriteLine("\tПериметр: " + p);
            Console.WriteLine("\tРавнобедренный: " + ravnb);
            Console.WriteLine("\tРавносторонний: " + ravns);
        }

        private int RectS(int a, int b)
        {
            return a * b;
        }

        private int RectP(int a, int b)
        {
            return a * 2 + b * 2;
        }

        private bool RectIsSquare(int a, int b)
        {
            return a == b;
        }

        private void PrintInfoRectangle(int a, int b, int s, int p, bool isSquare)
        {
            Console.WriteLine("Информация о Прямоугольнике:");
            Console.WriteLine("\tСторона А: " + a);
            Console.WriteLine("\tСторона B: " + b);
            Console.WriteLine("\tПлощадь: " + s);
            Console.WriteLine("\tПериметр: " + p);
            Console.WriteLine("\tЯвляется квадратом: " + isSquare);
        }
    }
}