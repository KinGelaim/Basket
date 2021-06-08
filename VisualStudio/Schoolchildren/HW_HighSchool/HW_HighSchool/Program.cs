using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

namespace HW_HighSchool
{
    class Program
    {
        static void Main(string[] args)
        {
            //--------- Домашка 1 ---------
            PrintHW("Домашка 1 (02.11.2019)");
            //Задание 1
            PrintQuest(1);
            for (int i = 7; i <= 18; i++)
            {
                Console.Write(i + " ");
            }
            Console.WriteLine();
            for (int i = 4; i >= 0; i--)
            {
                Console.Write(i + " ");
            }
            Console.WriteLine();
            Console.Write("Введите число 1: ");
            int chislo1 = Convert.ToInt32(Console.ReadLine());
            Console.Write("Введите число 2: ");
            int chislo2 = Convert.ToInt32(Console.ReadLine());
            Console.WriteLine("Выводим от наименьшего до наибольшего: ");
            if (chislo1 < chislo2)
            {
                for (int i = chislo1; i <= chislo2; i++)
                {
                    Console.Write(i);
                }
            }
            else
            {
                for (int i = chislo2; i <= chislo1; i++)
                {
                    Console.Write(i);
                }
            }
            Console.WriteLine();
            Console.WriteLine("Выводим от первого до второго: ");
            if (chislo1 < chislo2)
            {
                for (int i = chislo1; i <= chislo2; i++)
                {
                    Console.Write(i);
                }
            }
            else
            {
                for (int i = chislo1; i >= chislo2; i--)
                {
                    Console.Write(i);
                }
            }
            Console.WriteLine();
            //Задание 2
            PrintQuest(2);
            Console.Write("Введите число: ");
            chislo1 = Convert.ToInt32(Console.ReadLine());
            Console.Write("Введите степень: ");
            chislo2 = Convert.ToInt32(Console.ReadLine());
            Console.WriteLine("Результат 1: " + Math.Pow(chislo1, chislo2));
            int result = 0;
            if (chislo2 != 0)
            {
                for (int i = 1; i < chislo2; i++)
                {
                    if (result == 0)
                    {
                        result = chislo1 * chislo1;
                    }
                    else
                    {
                        result *= chislo1;
                    }
                }
            }
            else
            {
                result = 1;
            }
            Console.WriteLine("Результат 2: " + result);
            //Задание 3
            PrintQuest(3);
            Console.Write("Введите число 1: ");
            chislo1 = Convert.ToInt32(Console.ReadLine());
            Console.Write("Введите число 2: ");
            chislo2 = Convert.ToInt32(Console.ReadLine());
            if (chislo1 > chislo2)
            {
                Console.WriteLine("Первое число больше второго числа");
            }
            else if (chislo2 > chislo1)
            {
                Console.WriteLine("Второе число больше первого числа");
            }
            else
            {
                Console.WriteLine("Числа равны");
            }
            //Задание 4
            PrintQuest(4);
            Console.Write("Введите число: ");
            chislo1 = Convert.ToInt32(Console.ReadLine());
            Console.WriteLine("С помощью For: ");
            int count = chislo1;
            for (; count > 0; count -= 7)
            {
                Console.Write(count + " ");
            }
            Console.Write(0);
            Console.WriteLine();
            count = chislo1;
            while (count >= 0)
            {
                Console.Write(count + " ");
                count -= 7;
            }
            if (count != 0 && count + 7 != 0)
            {
                Console.Write(0);
            }
            Console.WriteLine();
            //Задание 5
            PrintQuest(5);
            Console.Write("Введите число 1: ");
            chislo1 = Convert.ToInt32(Console.ReadLine());
            Console.Write("Введите число 2: ");
            chislo2 = Convert.ToInt32(Console.ReadLine());
            Console.Write("Введите число 3: ");
            int chislo3 = Convert.ToInt32(Console.ReadLine());
            if (chislo1 < 0 && chislo2 < 0 && chislo3 < 0 || chislo1 > 0 && chislo2 > 0 && chislo3 > 0 || chislo1 == 0 && chislo2 == 0 && chislo3 == 0)
            {
                Console.WriteLine("Все числа одного знака!");
            }
            else
            {
                Console.WriteLine("Числа разных знаков");
            }
            //Задание 6
            PrintQuest(6);
            while (true)
            {
                string str = Console.ReadLine();
                if(str == "0")
                {
                    break;
                }
            }
            //Задание 7
            PrintQuest(7);
            Console.WriteLine("--- Калькулятор на N чисел ---");
            //Вариант со строками!!!
            Console.Write("Введите количество чисел: ");
            count = Convert.ToInt32(Console.ReadLine());
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
            //Вариант без строк!!!
            Console.Write("Введите количество чисел: ");
            count = Convert.ToInt32(Console.ReadLine());
            if (count >= 2)
            {
                //Для сохранения результата
                double resultCalculator = 0;
                //Запрашиваем первое число
                Console.Write("Введите число 1: ");
                string str1 = Console.ReadLine();
                double ch1 = Convert.ToDouble(str1);
                resultCalculator += ch1;
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
                                break;
                            case "-":
                                resultCalculator -= ch2;
                                break;
                            case "*":
                                resultCalculator *= ch2;
                                break;
                            case "/":
                                resultCalculator /= ch2;
                                break;
                        }
                    }
                }
                Console.WriteLine("Результат: " + resultCalculator);
            }
            else
            {
                Console.WriteLine("И что мне дальше делать?");
            }
            //Вариант на три числа!!!
            Console.Write("Введите число 1: ");
            string strCh1 = Console.ReadLine();
            double dCh1 = Convert.ToDouble(strCh1);
            Console.Write("Введите символ между числами 1 и 2: ");
            string strSymb1 = Console.ReadLine();
            Console.Write("Введите число 2: ");
            string strCh2 = Console.ReadLine();
            double dCh2 = Convert.ToDouble(strCh2);
            Console.Write("Введите символ между числами 2 и 3: ");
            string strSymb2 = Console.ReadLine();
            Console.Write("Введите число 3: ");
            string strCh3 = Console.ReadLine();
            double dCh3 = Convert.ToDouble(strCh3);
            //Бахаем строку примера
            string primer = strCh1 + strSymb1 + strCh2 + strSymb2 + strCh3;
            //Переменные для результата
            double pervoeChislo = dCh1;
            string perviySymb = strSymb1;
            double vtoroeChislo = dCh2;
            string vtoroySymb = strSymb2;
            double tretieChislo = dCh3;
            double res = 0;
            //Проверяем, какое сначало действие?
            if (strSymb2 == "*" || strSymb2 == "/")
            {
                pervoeChislo = dCh2;
                perviySymb = strSymb2;
                vtoroeChislo = dCh3;
                vtoroySymb = strSymb1;
                tretieChislo = dCh1;
            }
            switch (perviySymb)
            {
                case "+":
                    res = pervoeChislo + vtoroeChislo;
                    break;
                case "-":
                    res = pervoeChislo - vtoroeChislo;
                    break;
                case "*":
                    res = pervoeChislo * vtoroeChislo;
                    break;
                case "/":
                    res = pervoeChislo / vtoroeChislo;
                    break;
            }
            switch (vtoroySymb)
            {
                case "+":
                    res += tretieChislo;
                    break;
                case "-":
                    res -= tretieChislo;
                    break;
                case "*":
                    res *= tretieChislo;
                    break;
                case "/":
                    res /= tretieChislo;
                    break;
            }
            Console.WriteLine("Результат: " + primer + "=" + res);
            Console.ReadKey();
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
