using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

namespace HW_Monday_3
{
    class Program
    {
        static void Main(string[] args)
        {
            //Задание 1
            int a = ReadChislo(" 1");
            int b = ReadChislo(" 2");
            CompaerTwoNumbers(a, b);

            //Задание 2
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

            //Задание 3
            int x = ReadChislo(" 1");
            int y = ReadChislo(" 2");
            int z = ReadChislo("", "Введите шаг");

            if (x > y)
            {
                for (int i = x; i > y; i-=z)
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

            //Задание 4
            int[] arr = new int[9] { 7, 97, -475, 200, 318, -4, 0, 2497, -17 };

            int amount = 0;
            Console.Write("Массив: ");
            for (int i = 0; i < arr.Length; i++)
            {
                Console.Write(arr[i] + " ");
                amount += arr[i];
            }
            Console.WriteLine();
            Console.WriteLine("Сумма элементов массива: " + amount);
            
            //Среднее значение
            double s = Convert.ToDouble(amount) / Convert.ToDouble(arr.Length);
            Console.WriteLine("Среднее значение массива: " + s);

            //Минимум и максимум
            //Вариант первый (очень плохой) (ноль использовать нельзя, т.к. его может не быть)
            int minArr = 999999;
            int maxArr = -999999;
            for (int i = 0; i < arr.Length; i++)
            {
                if (minArr > arr[i])
                    minArr = arr[i];
                if (maxArr < arr[i])
                    maxArr = arr[i];
            }
            Console.WriteLine("Минимальное значение массива (вариант 1): " + minArr + "\nМаксимальное значение массива (вариант 2): " + maxArr);

            //Вариант второй (более правильный)
            minArr = arr[0];
            maxArr = arr[0];
            for (int i = 0; i < arr.Length; i++)
            {
                if (minArr > arr[i])
                    minArr = arr[i];
                if (maxArr < arr[i])
                    maxArr = arr[i];
            }
            Console.WriteLine("Минимальное значение массива (вариант 1): " + minArr + "\nМаксимальное значение массива (вариант 2): " + maxArr);

            //Вариант третий (самый правильный)
            if (arr.Length > 0)
            {
                minArr = arr[0];
                maxArr = arr[0];
                for (int i = 0; i < arr.Length; i++)
                {
                    if (minArr > arr[i])
                        minArr = arr[i];
                    if (maxArr < arr[i])
                        maxArr = arr[i];
                }
                Console.WriteLine("Минимальное значение массива (вариант 1): " + minArr + "\nМаксимальное значение массива (вариант 2): " + maxArr);
            }
            else
            {
                Console.WriteLine("Массив пустой!");
            }

            //Задание 5
            string text = "Некий текст!";   //Некий текст
            char c = 'к';                   //Искомая буква
            CountChar(text, c);

            //Задание 6
            string stroka = "Это некий первоначльный текст, с которым мы будем работать!";
            Console.WriteLine("Исходная строка: " + stroka);
            Console.WriteLine("Заглавная строка: " + stroka.ToUpper());
            Console.WriteLine("Строчная строка: " + stroka.ToLower());
            Console.WriteLine("Замена , на . в строке: " + stroka.Replace(',','.'));
            Console.WriteLine("Удаляем подтекст 'мы будем' в строке: " + stroka.Replace("мы будем",""));

            //Задание 7
            //Калькулятор в отдельном файле
        }

        static void CountChar(string text, char c)
        {
            int res = 0;
            for (int i = 0; i < text.Length; i++)
            {
                if (c == text[i])
                {
                    res++;
                }
            }
            Console.WriteLine("В тексте: " + res);
        }

        static int ReadChislo(string s = "", string text = "Введите число")
        {
            Console.Write(text + s + ": ");
            string str = Console.ReadLine();
            int x = Convert.ToInt32(str);
            return x;
        }

        static void CompaerTwoNumbers(int a, int b)
        {
            if (a > b)
            {
                Console.WriteLine("Первое число больше второго числа!");
            }
            else if (a < b)
            {
                Console.WriteLine("Второе число больше первого числа");
            }
            else
            {
                Console.WriteLine("Числа равны!");
            }
        }
    }
}
