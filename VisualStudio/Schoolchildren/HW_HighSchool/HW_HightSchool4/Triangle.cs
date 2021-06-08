using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

namespace HW_HightSchool4
{
    class Triangle
    {
        //Поля
        public double a;
        public double b;
        public double c;
        public double oneAngle;
        public string name;

        //Конструторы
        public Triangle()
        {

        }

        public Triangle(double a, double b, double c)
        {
            this.a = a;
            this.b = b;
            this.c = c;
            if (a == b || b == c || c == a)
            {
                name = "Ранвобедренный";
            }
            if(a == b && b == c && c == a)
            {
                name = "Равносторонний";
            }
        }

        public Triangle(double a, double b, double c, double oneAngle, string name = "")
            : this(a, b, c)
        {
            this.oneAngle = oneAngle;
            this.name = name;
            if(name == "")
            {
                this.CheckName();
            }
        }

        //Методы
        public void PrintInfo()
        {
            Console.WriteLine("Это треугольник и вот его параметры: ");
            if (name != null)
            {
                if (name.Length > 0)
                {
                    Console.WriteLine("--- Его имя: " + name);
                }
            }
            Console.WriteLine("--- Сторона A = " + a);
            Console.WriteLine("--- Сторона B = " + b);
            Console.WriteLine("--- Сторона C = " + c);
            Console.WriteLine("--- Один из его углов равен " + oneAngle);
        }

        public void CheckName()
        {
            if (a == b || b == c || c == a)
            {
                name = "Ранвобедренный";
            }
            if (a == b && b == c && c == a)
            {
                name = "Равносторонний";
            }
            if (oneAngle == 90)
            {
                name = "Прямоугольный";
            }
        }

        public double P()
        {
            double p = a + b + c;
            return p;
        }
    }
}
