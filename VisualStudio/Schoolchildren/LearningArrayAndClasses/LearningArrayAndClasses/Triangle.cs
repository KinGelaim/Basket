using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

namespace LearningArrayAndClasses
{
    public sealed class Triangle
    {
        public int a;
        public int b;
        public int c;
        public double S;
        public double P;
        public bool Ravnb;
        public bool Ravns;

        public Triangle() { }

        public Triangle(int a, int b, int c)
        {
            this.a = a;
            this.b = b;
            this.c = c;
        }

        public void CalculatedS()
        {
            int t1 = (a + b + c) / 2 - a;
            int t2 = (a + b + c) / 2 - b;
            int t3 = (a + b + c) / 2 - c;

            int t4 = t1 * t2 * t3;

            int t5 = (a + b + c) / 2 * t4;

            S =  Math.Sqrt(t5);
        }

        public void CalculatedP()
        {
            P = a + b + c;
        }

        public void CheckRavnb()
        {
            Ravnb = a == b || b == c || c == a;
        }

        public void CheckRavns()
        {
            Ravns = a == b && b == c && c == a;
        }

        public void PrintInfo()
        {
            Console.WriteLine("Информация о треугольнике:");
            Console.WriteLine("\tСторона А: " + a);
            Console.WriteLine("\tСторона B: " + b);
            Console.WriteLine("\tСторона C: " + c);
            Console.WriteLine("\tПлощадь: " + S);
            Console.WriteLine("\tПериметр: " + P);
            Console.WriteLine("\tРавнобедренный: " + Ravnb);
            Console.WriteLine("\tРавносторонний: " + Ravns);
        }
    }
}