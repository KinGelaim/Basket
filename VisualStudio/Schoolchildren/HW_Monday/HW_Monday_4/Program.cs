using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

namespace HW_Monday_4
{
    class Program
    {
        static void Main(string[] args)
        {
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
            Triangle triangle1 = new Triangle();
            triangle1.a = 4;
            triangle1.b = 3;
            triangle1.c = 4;
            triangle1.PrintInfo();
            Console.WriteLine("Периметр равен " + triangle1.P());

            Rectangle react = new Rectangle(4, 7);
            react.PrintInfo();
            react.Print();

            Console.ReadKey();
        }
    }
}
