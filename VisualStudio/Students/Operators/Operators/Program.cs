using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

namespace Operators
{
    class Program
    {
        static void Main(string[] args)
        {
            /*
             * Запросить слово с консоли
             * Запросить число с консоли
             * Вывести на консоль это слово столько раз, сколько задано числом
             * [Вывести в три столбика]
             * 
             * */
            NewLine("Задание 1");
            Console.Write("Введите слово: ");
            string word = Console.ReadLine();
            Console.WriteLine("Сколько раз вывести?");
            int count = 0;
            try
            {
                count = Convert.ToInt32(Console.ReadLine());
            }
            catch
            {
                Console.WriteLine("Ошибка!");
                Console.ReadKey();
                return;
            }
            for (int i = 0; i < count; i++)
                Console.WriteLine(word);
            //Чуть сложнее
            int k = 0;
            for (int i = 0; i < count; i++)
            {
                k++;
                if (k < 3)
                    Console.Write(word + " ");
                else
                {
                    Console.WriteLine(word);
                    k = 0;
                }
            }
            Console.WriteLine("");
            /*
             * Запросить с консоли число
             * Распределить это числопо группам
             * Спрашивать бесконечно, пока не будет введено ключевое слово (exit)
             * 
             * */
            NewLine("Задание 2");
            while (true)
            {
                Console.Write("Введите температуру на улице: ");
                string strTemp = Console.ReadLine();
                if (strTemp == "exit")
                    break;
                double temp = 0;
                try
                {
                    temp = Convert.ToDouble(strTemp);
                }
                catch
                {
                    Console.WriteLine("Ошибка при введении температуры!");
                    continue;
                }
                if (temp > 0)
                    Console.WriteLine("Наденьте панамку!");
                else if (temp < 0)
                    Console.WriteLine("Наденьте шапку!");
                else
                    Console.WriteLine("Спросите позже!");
            }
            /*
             * Создать свой шар предсказаний
             * 
             * */
            NewLine("Задание 3");
            Random rand = new Random();
            bool isQ = true;
            while (isQ)
            {
                Console.Write("Задайте свой вопрос: ");
                Console.ReadLine();
                int q = rand.Next(100);
                if (q < 25)
                    Console.WriteLine("Да");
                else if (q < 50)
                    Console.WriteLine("Нет");
                else if (q < 75)
                    Console.WriteLine("Возможно");
                else if (q < 100)
                    Console.WriteLine("Спросите позже");
                Console.WriteLine("Желаете задать еще вопрос? (Да/Нет)");
                int answer = 0;
                while (answer == 0)
                {
                    string reQ = Console.ReadLine();
                    switch (reQ)
                    {
                        case "Да":
                            answer = 1;
                            break;
                        case "Нет":
                            answer = 1;
                            isQ = false;
                            break;
                        default:
                            Console.Write("Я вас не понял! Повторите: ");
                            break;
                    }
                }
            }
            Console.ReadKey();
        }

        static void NewLine(string str = "")
        {
            Console.WriteLine("---------------------------" + str + "---------------------------");
        }
    }
}
