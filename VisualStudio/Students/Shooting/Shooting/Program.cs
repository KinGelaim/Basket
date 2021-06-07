using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

namespace Shooting
{
    class Program
    {
        static void Main(string[] args)
        {
            /*
             * Задание 1
             * Проверка на фигуру: квадрат или нет? Какая сторона больше (if и else)
             * */
            Console.Write("*******Задание 1*******\nВведите сторону a: ");
            string a = Console.ReadLine();
            Console.Write("Введите сторону b: ");
            string b = Console.ReadLine();
            try
            {
                int x = Convert.ToInt32(a);
                int y = Convert.ToInt32(b);
                if (x == y)
                {
                    Console.WriteLine("Это квадрат!\nУ него стороны равны!");
                }else
                {
                    Console.WriteLine("Это не квадрат!");
                    if (x > y)
                    {
                        Console.WriteLine("Сторона a больше стороны b");
                    }
                    else
                    {
                        Console.WriteLine("Сторона b больше стороны a");
                    }
                }
            }
            catch
            {
                Console.WriteLine("Вводить нужно числа!");
            }
            Console.ReadKey();

            /*
             * Задание 2
             * Пирамидка (с помощью for и while)
             * */
            Console.Write("*******Задание 2*******\nВведите высоту пирамидки: ");
            string strHeight = Console.ReadLine();
            try
            {
                int k = 1;
                int intHeight = Convert.ToInt32(strHeight);
                while (k <= intHeight)
                {
                    for (int i = 1; i <= k; i++)
                        Console.Write(i);
                    k++;
                    Console.WriteLine();
                }
            }
            catch
            {
                Console.WriteLine("Введить нужно число!");
            }
            Console.ReadKey();

            /*
             * Задание 3
             * Попадание в цель (квадрат, круг, рандомные выстрелы в круг) центр фигуры помещаем в место пересечение осей (для самых жестких смещаем центр круга)
             * */
            Console.Write("*******Задание 3.1*******\nВведите сторону квадрата: ");
            try
            {
                int len = Convert.ToInt32(Console.ReadLine());
                Console.Write("Введите x: ");
                int x = Convert.ToInt32(Console.ReadLine());
                Console.Write("Введите y: ");
                int y = Convert.ToInt32(Console.ReadLine());
                if (x <= len / 2 && x >= -1 * len / 2 && y <= len / 2 && y >= -1 * len / 2)
                    Console.WriteLine("Вы попали в цель!");
                else
                    Console.WriteLine("Увы, вы промахнулись!");
            }
            catch
            {
                Console.WriteLine("Где-то косяк!");
            }
            Console.ReadKey();
            //Задание 3.2
            Console.Write("*******Задание 3.2*******\nВведите сторону квадрата: ");
            try
            {
                int len = Convert.ToInt32(Console.ReadLine());
                Console.Write("Введите количество выстрелов: ");
                int count = Convert.ToInt32(Console.ReadLine());
                Random rand = new Random();
                for (int i = 0; i < count; i++)
                {
                    int x = rand.Next(-2 * len, 2 * len);
                    int y = rand.Next(-2 * len, 2 * len);
                    Console.WriteLine("Выстрел x={0} и y={1}", x, y);
                    if (x <= len / 2 && x >= -1 * len / 2 && y <= len / 2 && y >= -1 * len / 2)
                        Console.WriteLine("Вы попали в цель!");
                    else
                        Console.WriteLine("Увы, вы промахнулись!");
                }
            }
            catch
            {
                Console.WriteLine("Где-то косяк!");
            }
            Console.ReadKey();
            //Задание 3.3
            Console.Write("*******Задание 3.3*******\nВведите радиус круга: ");
            try
            {
                int radius = Convert.ToInt32(Console.ReadLine());
                Console.Write("Введите x: ");
                int x = Convert.ToInt32(Console.ReadLine());
                Console.Write("Введите y: ");
                int y = Convert.ToInt32(Console.ReadLine());
                if (x*x + y*y <= radius*radius)
                    Console.WriteLine("Вы попали в цель!");
                else
                    Console.WriteLine("Увы, вы промахнулись!");
            }
            catch
            {
                Console.WriteLine("Где-то косяк!");
            }
            Console.ReadKey();
            //Задание 3.4
            Console.Write("*******Задание 3.4*******\nВведите радиус круга: ");
            try
            {
                int radius = Convert.ToInt32(Console.ReadLine());
                Console.Write("Введите количество выстрелов: ");
                int count = Convert.ToInt32(Console.ReadLine());
                Random rand = new Random();
                for (int i = 0; i < count; i++)
                {
                    int x = rand.Next(-2 * radius, 2 * radius);
                    int y = rand.Next(-2 * radius, 2 * radius);
                    Console.WriteLine("Выстрел x={0} и y={1}", x, y);
                    if (x * x + y * y <= radius * radius)
                        Console.WriteLine("Вы попали в цель!");
                    else
                        Console.WriteLine("Увы, вы промахнулись!");
                }
            }
            catch
            {
                Console.WriteLine("Где-то косяк!");
            }
            Console.ReadKey();
            //Задание 3.5
            Console.Write("*******Задание 3.5*******\nВведите радиус круга: ");
            try
            {
                int radius = Convert.ToInt32(Console.ReadLine());
                Console.Write("Введите координату центра по x: ");
                int xCenter = Convert.ToInt32(Console.ReadLine());
                Console.Write("Введите координату центра по y: ");
                int yCenter = Convert.ToInt32(Console.ReadLine());
                Console.Write("Введите количество выстрелов: ");
                int count = Convert.ToInt32(Console.ReadLine());
                Random rand = new Random();
                for (int i = 0; i < count; i++)
                {
                    int x = rand.Next(-2 * radius, 2 * radius);
                    int y = rand.Next(-2 * radius, 2 * radius);
                    Console.WriteLine("Выстрел x={0} и y={1}", x, y);
                    if ((x - xCenter) * (x - xCenter) + (y - yCenter) * (y - yCenter) <= radius * radius)
                        Console.WriteLine("Вы попали в цель!");
                    else
                        Console.WriteLine("Увы, вы промахнулись!");
                }
            }
            catch
            {
                Console.WriteLine("Где-то косяк!");
            }
            Console.ReadKey();
        }
    }
}