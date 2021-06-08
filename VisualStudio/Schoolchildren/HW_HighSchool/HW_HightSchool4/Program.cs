using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

namespace HW_HightSchool4
{
    class Program
    {
        static void Main(string[] args)
        {
            //--------- Домашка 4 ---------
            MainStaticClass.PrintHW("Домашка 4 (23.11.2019)");
            //Задание 1
            MainStaticClass.PrintQuest(1);
            Console.Write("Введите кол-во градусов: ");
            double temp = Convert.ToDouble(Console.ReadLine());
            if (temp > 0 && temp < 10)
            {
                Console.WriteLine("На улице прохладно! Одевайся средне!");
            }
            if (temp >= 10 && temp < 30)
            {
                Console.WriteLine("На улице тепло! Надевай шорты!");
            }
            if (temp >= 30)
            {
                Console.WriteLine("На улице пекло! Может не стоит вообще одеваться?");
            }

            if (temp <= 0 && temp > -10)
            {
                Console.WriteLine("На улице прохладно! Одевайся потеплее!");
            }
            if (temp <= -10 && temp > -30)
            {
                Console.WriteLine("На улице холодно! Накинь пухан!");
            }
            if (temp <= -30)
            {
                Console.WriteLine("На улице айс! Может не стоит выходить?");
            }

            //Задание 2
            MainStaticClass.PrintQuest(2);
            int x = ReadChislo(" 1");
            int y = ReadChislo(" 2");
            int z = ReadChislo("", "Введите шаг");

            if (x > y)
            {
                for (int i = x; i > y; i -= z)
                {
                    Console.Write(i + " ");
                }
            }
            else
            {
                for (int i = x; i < y; i += z)
                {
                    Console.Write(i + " ");
                }
            }
            Console.WriteLine();

            //Задание 3
            MainStaticClass.PrintQuest(3);

            //Вариант 1
            int firstNumber = 2;
            int secondNumber = 2;

            int score = 0;

            while (true)
            {
                Console.Write(firstNumber + " * " + secondNumber + " = ");
                string str = Console.ReadLine();

                if (str == "Exit" || str == "exit")
                {
                    break;
                }

                int res = Convert.ToInt32(str);
                if (res == firstNumber * secondNumber)
                {
                    score++;
                }
                else
                {
                    Console.WriteLine("Ошибка! Правильный ответ: " + firstNumber * secondNumber);
                    break;
                }

                secondNumber++;
                if (secondNumber > 10)
                {
                    secondNumber = 2;
                    firstNumber++;
                }
            }
            Console.WriteLine("Правильных ответов: " + score);

            //Вариант 2
            Random rand = new Random();
            score = 0;
            while (true)
            {
                firstNumber = rand.Next(2, 1000);
                secondNumber = rand.Next(2, 1000);

                Console.Write(firstNumber + " * " + secondNumber + " = ");
                string str = Console.ReadLine();

                if (str == "Exit" || str == "exit")
                {
                    break;
                }

                int res = Convert.ToInt32(str);
                if (res == firstNumber * secondNumber)
                {
                    score++;
                }
                else
                {
                    Console.WriteLine("Ошибка! Правильный ответ: " + firstNumber * secondNumber);
                    break;
                }
            }
            Console.WriteLine("Правильных ответов: " + score);

            //Задание 4
            MainStaticClass.PrintQuest(4);
            Triangle triangle1 = new Triangle();
            triangle1.a = 4;
            triangle1.b = 3;
            triangle1.c = 4;
            triangle1.PrintInfo();
            Console.WriteLine("Периметр равен " + triangle1.P());

            Reactangle react = new Reactangle(4, 7);
            react.PrintInfo();
            react.Print();

            Console.ReadKey();
        }

        static int ReadChislo(string s = "", string text = "Введите число")
        {
            Console.Write(text + s + ": ");
            string str = Console.ReadLine();
            int x = Convert.ToInt32(str);
            return x;
        }
    }
}
