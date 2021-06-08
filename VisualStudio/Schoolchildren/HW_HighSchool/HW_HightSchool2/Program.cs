using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

namespace HW_HightSchool2
{
    class Program
    {
        static void Main(string[] args)
        {
            //--------- Домашка 2 ---------
            PrintHW("Домашка 2 (09.11.2019)");
            //Задание 1
            PrintQuest(1);
            int a = ReadChislo(" 1");
            int b = ReadChislo(" 2");
            CompaerTwoNumbers(a, b);

            /*
             * Задание 2
             * 
             * Построить таблицу (через операторы if и for)
             * 
             * |---------------------------------|
             * |                         |       |
             * |                         |       |
             * |                         |       |
             * |---------------------------------|
             * |1)                       |       |
             * |---------------------------------|
             * |2)                       |       |
             * |---------------------------------|
             * |3)                       |       |
             * |---------------------------------|
             * |4)                       |       |
             * |---------------------------------|
             * 
             * */
            PrintQuest(2);
            Console.Write("Введите количество строк: ");
            int k = Convert.ToInt32(Console.ReadLine());
            Console.WriteLine("|---------------------------------|");
            Console.WriteLine("|                         |       |");
            Console.WriteLine("|                         |       |");
            Console.WriteLine("|                         |       |");
            Console.WriteLine("|---------------------------------|");
            for (int i = 1; i <= k; i++)
            {
                if (i < 10)
                    Console.WriteLine("|{0})                       |       |", i);
                else if (i < 100)
                    Console.WriteLine("|{0})                      |       |", i);
                else if (i < 1000)
                    Console.WriteLine("|{0})                     |       |", i);
                Console.WriteLine("|---------------------------------|");
            }

            //Задание 3.1
            PrintQuest(3);
            Console.WriteLine("Первый вариант:");
            for (int i = 2; i < 10; i++)
            {
                for (int j = 2; j < 5; j++)
                {
                    Console.Write(j + " * " + i + " = " + (i*j) + "\t");
                }
                Console.WriteLine();
            }
            Console.WriteLine("\nВторой вариант:");

            //Задание 3.2
            for (int l = 5; l < 12; l+=3)
            {
                for (int i = 2; i < 10; i++)
                {
                    for (int j = l-3; j < l; j++)
                    {
                        Console.Write(j + " * " + i + " = " + (i * j) + "\t");
                    }
                    Console.WriteLine();
                }
                Console.WriteLine();
            }

            //Задание 4
            PrintQuest(4);
            //string prStr = Console.ReadLine();
            string prStr = "Это моя начальная строка, именно с ней я буду работать! В строке есть фраза: вырезаем строку. А также число: 123";
            Console.WriteLine("Начальная строка: " + prStr);
            Console.WriteLine("Все заглавные: " + prStr.ToUpper());
            Console.WriteLine("Все строчные: " + prStr.ToLower());
            Console.WriteLine("Вырезаем из строки строку (вырезаем строку): " + prStr.Replace("вырезаем строку", ""));
            Console.WriteLine("Заменяем запятые на точки: " + prStr.Replace(',', '.'));
            Console.WriteLine("Вырезаем из строки строку (123): " + prStr.Replace("123", ""));

            //Задание 5
            PrintQuest(5);
            int[] arr = new int[9] { 1, 5, 4, 9, -10, -40, 80, +322, 7 };
            int summ = 0;
            for (int i = 0; i < arr.Length; i++)
            {
                summ += arr[i];
            }
            Console.WriteLine("Сумма равна: " + summ);
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

            //Задание 6
            PrintQuest(6);
            string str = "Считаем сколько в этой строке символов о";
            int summO = 0;
            for (int i = 0; i < str.Length; i++)
            {
                if(str[i] == 'о')
                    summO++;
            }
            Console.WriteLine("Первый вариант");
            Console.WriteLine("Начальная строка: " + str);
            Console.WriteLine("Кол-во 'о' в строке: " + summO);

            Console.WriteLine("Второй вариант");
            CountCharacter("Это очередная какая-то строка! Дада строка!", 'я');

            //Задание 8
            //Читер со строкой
            PrintQuest(8);
            Console.WriteLine("Вариант 1:\nВводите числа (пока не прийдет 0)");
            string allStr = "";
            while (true)
            {
                string nextStr = Console.ReadLine();
                allStr += nextStr + "\n";
                if (nextStr == "0")
                    break;
            }
            Console.WriteLine("Все выводим:\n" + allStr);

            //Тут необходимо ощутить всю боль от добавления элемента в массив
            Console.WriteLine("Вариант 2:\nВводите числа (пока не прийдет 0)");
            //Создаем массив для хранения всех значений
            int[] allArr = new int[0];
            while (true)
            {
                //Считываем и конвертируем
                string nextStr = Console.ReadLine();
                int number = Convert.ToInt32(nextStr);

                //Создаем новый массив с расширением размера на единицу
                int[] prArr = new int[allArr.Length + 1];

                //Переписываем все старые значения в новый массив
                for (int i = 0; i < allArr.Length; i++)
                {
                    prArr[i] = allArr[i];
                }

                //Дописываем новый элемент в массив
                prArr[prArr.Length - 1] = number;

                //Присваиваем новый массив к старому
                allArr = prArr;

                if (number == 0)
                    break;
            }
            Console.WriteLine("Все выводим:");
            for (int i = 0; i < allArr.Length; i++)
            {
                Console.WriteLine(allArr[i]);
            }

            //Третий вариант (через коллекцию)
            Console.WriteLine("Вариант 3:\nВводите числа (пока не прийдет 0)");
            //Создаем массив для хранения всех значений
            List<int> allList = new List<int>();
            while (true)
            {
                //Считываем и конвертируем
                string nextStr = Console.ReadLine();
                int number = Convert.ToInt32(nextStr);

                //Добавляем элемент в массив
                allList.Add(number);

                if (number == 0)
                    break;
            }
            Console.WriteLine("Все выводим:");
            for (int i = 0; i < allList.Count; i++)
            {
                Console.WriteLine(allList[i]);
            }

            //Задание 9
            PrintQuest(9);
            Console.WriteLine("Кулькулятор в отдельном проекте!");

            //Задание 10
            PrintQuest(10);
            Console.WriteLine("Кулькулятор в отдельном проекте!");
        }

        //Функция для подсчета кол-ва символов
        static void CountCharacter(string str, char c)
        {
            int result = 0;
            for (int i = 0; i < str.Length; i++)
            {
                if (str[i] == c)
                    result++;
            }
            Console.WriteLine("Начальная строка: " + str);
            Console.WriteLine("Кол-во '" + c + "' в строке: " + result);
        }

        //Сравнение двух чисел и вывод большего
        static void CompaerTwoNumbers(int a, int b)
        {
            if (a > b)
            {
                Console.WriteLine("Первое число больше второго");
            }
            else if(b > a)
            {
                Console.WriteLine("Второе число больше первого");
            }
            else
            {
                Console.WriteLine("Оба числа равны");
            }
        }

        //Функция для считывания с консоли значений, конвертации в число и возвращения этого числа
        static int ReadChislo(string s = "")
        {
            Console.Write("Введите число " + s + ": ");
            string str = Console.ReadLine();
            int chislo = Convert.ToInt32(str);
            return chislo;
        }

        static void PrintHW(string text = "")
        {
            Console.WriteLine("-------------------------------------------------");
            Console.WriteLine("|                                               |");
            Console.WriteLine("|\t\t" + text + "\t\t|");
            Console.WriteLine("|                                               |");
            Console.WriteLine("-------------------------------------------------");
        }

        static void PrintQuest(int number)
        {
            Console.WriteLine("--------- Задание № " + number + " ---------");
        }
    }
}
