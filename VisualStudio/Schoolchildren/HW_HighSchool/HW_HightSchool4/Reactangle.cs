using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

namespace HW_HightSchool4
{
    class Reactangle
    {
        //Поля
        public double a, b;
        public string color;
        public string raskraska;
        public string name;

        //Конструктор
        public Reactangle()
        {

        }

        public Reactangle(double a, double b)
        {
            this.a = a;
            this.b = b;
            if (a == b)
            {
                name = "Квадрат";
            }
        }

        public Reactangle(double a, double b, string color, string raskraska, string name = "")
        {
            this.a = a;
            this.b = a;
            this.color = color;
            this.raskraska = raskraska;
            this.name = name;
        }

        //Методы
        public void PrintInfo()
        {
            if (a != b)
            {
                Console.WriteLine("Тут у нас прямоугольник:\nСо стороной A = " + a + "\nСо стороной B = " + b);
            }
            else
            {
                Console.WriteLine("Тут у нас квадрат:\nСо сторонами = " + a);
            }
        }

        public void CheckSq()
        {
            if (a == b)
            {
                Console.WriteLine("Это квадрат!");
            }
            else
            {
                Console.WriteLine("Это прямоугольник!");
            }
        }

        public void Print()
        {
            Console.Write("|");
            for (int i = 0; i < a - 2; i++)
            {
                Console.Write("-");
            }
            Console.WriteLine("|");
            for (int i = 0; i < b - 2; i++)
            {
                Console.Write("|");
                for (int j = 0; j < a - 2; j++)
                {
                    Console.Write(" ");
                }
                Console.WriteLine("|");
            }
            Console.Write("|");
            for (int i = 0; i < a - 2; i++)
            {
                Console.Write("-");
            }
            Console.WriteLine("|");
        }
    }
}
