using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

namespace HW_Monday_2
{
    class Program
    {
        static void Main(string[] args)
        {
            //Задание 9
            string str = "Это исходня строка";
            char ch = 'о';
            int result = 0;
            for (int i = 0; i < str.Length; i++)
            {
                if(ch == str[i])
                {
                    result++;
                }
            }
            Console.WriteLine("Исходня строка: " + str);
            Console.WriteLine("В строке " + result + " символов '" + ch + "'");

            //Задание 10
            Console.Write("Введите число 1: ");
            string str1 = Console.ReadLine();
            int ch1 = Convert.ToInt32(str1);
            Console.Write("Введите число 2: ");
            string str2 = Console.ReadLine();
            int ch2 = Convert.ToInt32(str2);
            Console.Write("Введите число 3: ");
            string str3 = Console.ReadLine();
            int ch3 = Convert.ToInt32(str3);

            if (ch1 > 0 && ch2 > 0 && ch3 > 0 || ch1 < 0 && ch2 < 0 && ch3 < 0 || ch1 == 0 && ch2 == 0 && ch3 == 0)
            {
                Console.WriteLine("Все числа одного знака");
            }
            else
            {
                Console.WriteLine("Числа разных знаков");
            }

            //Задание 11
            //Варик 1
            string stroka = "";
            while (true)
            {
                Console.Write("Введите число: ");
                string prStr = Console.ReadLine();
                int prChislo = Convert.ToInt32(prStr);
                stroka += prChislo + "\n";
                if (prChislo == 0)
                {
                    break;
                }
            }
            Console.WriteLine(stroka);

            //Варик 2
            int[] arr = new int[0];
            while (true)
            {
                Console.Write("Введите число: ");
                string prStr = Console.ReadLine();
                int prChislo = Convert.ToInt32(prStr);

                int[] prArr = new int[arr.Length + 1];
                for (int i = 0; i < arr.Length; i++)
                {
                    prArr[i] = arr[i];
                }
                prArr[prArr.Length - 1] = prChislo;

                if (prChislo == 0)
                {
                    break;
                }
            }

            for (int i = 0; i < arr.Length; i++)
            {
                Console.WriteLine(arr[i]);
            }

            //Задание 11
            Console.Write("Введите число: ");
            str = Console.ReadLine();
            int chislo = Convert.ToInt32(str);

            for (int i = 1; i <= chislo; i++)
            {
                for (int j = 1; j <= i; j++)
                {
                    Console.Write(j);
                }
                Console.WriteLine();
            }
            Console.WriteLine();

            //Задание 12
            Calculater1();
        }

        //Калькулятор на N чисел, но решается все по порядку (без учёта арифметики)
        static void Calculater1()
        {
            Console.WriteLine("--- Калькулятор на N чисел ---");
            //Вариант со строками!!!
            Console.Write("Введите количество чисел: ");
            int count = Convert.ToInt32(Console.ReadLine());
            if (count >= 2)
            {
                //Для сохранения результата
                double resultCalculator = 0;
                //Для создания строки примера и примера, который можем сейчас решить
                string fullStr = "";
                string completeStr = "";
                //Запрашиваем первое число
                Console.Write("Введите число 1: ");
                string str1 = Console.ReadLine();
                double ch1 = Convert.ToDouble(str1);
                resultCalculator += ch1;
                fullStr += ch1;
                completeStr += ch1;
                for (int i = 1; i <= count; i++)
                {
                    //Проверяем: а будут ли там дальше еще числа?
                    if (i + 1 <= count)
                    {
                        Console.Write("Введите символ между " + i + " и " + (i + 1) + " числами: ");
                        string str2 = Console.ReadLine();
                        Console.Write("Введите число " + (i + 1) + ": ");
                        string str3 = Console.ReadLine();
                        double ch2 = Convert.ToDouble(str3);
                        switch (str2)
                        {
                            case "+":
                                resultCalculator += ch2;
                                fullStr += "+" + ch2;
                                completeStr = completeStr.Insert(0, "(");
                                completeStr += "+" + ch2 + ")";
                                break;
                            case "-":
                                resultCalculator -= ch2;
                                fullStr += "-" + ch2;
                                completeStr = completeStr.Insert(0, "(");
                                completeStr += "-" + ch2 + ")";
                                break;
                            case "*":
                                resultCalculator *= ch2;
                                fullStr += "*" + ch2;
                                completeStr += "*" + ch2;
                                break;
                            case "/":
                                resultCalculator /= ch2;
                                fullStr += "/" + ch2;
                                completeStr += "/" + ch2;
                                break;
                        }
                    }
                }
                Console.WriteLine("Пример: " + fullStr);
                Console.WriteLine("Решаемый пример: " + completeStr);
                Console.WriteLine("Результат: " + resultCalculator);
            }
            else
            {
                Console.WriteLine("И что мне дальше делать?");
            }
        }
    }
}
