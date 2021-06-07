using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

namespace QuestionsForOperators
{
    class Program
    {
        static void Main(string[] args)
        {
            try
            {
                /*
                 * Задание 1
                 * 
                 * Вывести на консоль числа от 7 до 18
                 * Затем от 4 до 0
                 * Затем запросить два числа и вывести от наименьшего до наибольшего
                 * Затем от первого до второго числа
                 * 
                 * */
                PrintString("Задание 1");
                for (int i = 7; i <= 18; i++)
                    Console.WriteLine(i);
                Console.WriteLine();

                for (int i = 4; i >= 0; i--)
                    Console.WriteLine(i);
                Console.WriteLine();

                Console.Write("Введите число a: ");
                int a = Convert.ToInt32(Console.ReadLine());
                Console.Write("Введите число b: ");
                int b = Convert.ToInt32(Console.ReadLine());
                if (a > b)
                    for (int i = b; i <= a; i++)
                        Console.WriteLine(i);
                else
                    for (int i = a; i <= b; i++)
                        Console.WriteLine(i);
                Console.WriteLine();

                if (a > b)
                    for (int i = a; i >= b; i--)
                        Console.WriteLine(i);
                else
                    for (int i = a; i <= b; i++)
                        Console.WriteLine(i);
                /*
                 * Задание 2
                 * 
                 * Считать с консоли число, затем степень и возвести
                 * 
                 * */
                PrintString("Задание 2");
                Console.Write("Введите число: ");
                a = Convert.ToInt32(Console.ReadLine());
                Console.Write("Введите степень: ");
                b = Convert.ToInt32(Console.ReadLine());
                Console.WriteLine("Результат: {0}", Math.Pow(a, b));

                /*
                 * Задание 3
                 * 
                 * Считать с консоли два числа и вывести какое больше (с помощью функции)
                 * 
                 * */
                PrintString("Задание 3");
                Console.Write("Введите a: ");
                a = Convert.ToInt32(Console.ReadLine());
                Console.Write("Введите b: ");
                b = Convert.ToInt32(Console.ReadLine());
                ComparerTwoNumber(a, b);

                /*
                 * Задание 4
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
                PrintString("Задание 4");
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

                /*
                 * Задание 5
                 * 
                 * Считать с консоли число и вывести его уменьшая на 7 с помощью for и while (в конце 0)
                 * 
                 * */
                PrintString("Задание 5");
                Console.Write("Введите число: ");
                k = Convert.ToInt32(Console.ReadLine());
                for (int i = k; i > 0; i -= 7)
                    Console.WriteLine(i);
                Console.WriteLine("0");
                while (k > 0)
                {
                    Console.WriteLine(k);
                    k -= 7;
                }
                Console.WriteLine("0");

                /*
                 * Задание 6
                 * 
                 * Вывести текст в три столбика с использованием специальных символов (специальные символы \n, \t, \r, \ )
                 * 
                 * */
                PrintString("Задание 6");
                Console.WriteLine("Вот этот текст я записал\tв одну строку\nИ это часто применимо! Поэтому \"запоминаем\"");

                /*
                 * Задание 7 (работа со строками)
                 * 
                 * Считываем с консоли строку и делаем все символы заглавные
                 * Делаем все символы маленькими
                 * Вырезаем из строки строку
                 * Заменяем запятые на точки
                 * 
                 * */
                PrintString("Задание 7");
                string str = Console.ReadLine();
                Console.WriteLine(str.ToUpper());
                Console.WriteLine(str.ToLower());
                try
                {
                    Console.WriteLine(str.Substring(3, 7));
                }
                catch
                {
                    Console.WriteLine("Ошибка! Слишком короткая строка!");
                }
                Console.WriteLine(str.Replace(',', '.'));

                /*
                 * Задание 8 (немного работы с массивом)
                 * 
                 * Создаем массив из 9 элементов и считая сумму этих элементов, среднее значение, находим минимум и максимум (вручную)
                 * 
                 * */
                PrintString("Задание 8");
                int[] arr = new int[9] {2,5,78,-27,104,17,1,9,64};
                int summ = 0;
                double middle = 0;
                int min = 0;
                int max = 0;
                Console.WriteLine("Массив: ");
                for(int i = 0; i < arr.Length; i++)
                {
                    summ += arr[i];
                    if (min > arr[i])
                        min = arr[i]; ;
                    if (max < arr[i])
                        max = arr[i];
                    Console.Write(arr[i] + " ");
                }
                middle = Convert.ToDouble(summ) / Convert.ToDouble(arr.Length);
                Console.WriteLine("\nСумма элементов: {0}\nСреднее значение: {1}\nМинимальное значение: {2}\nМаксимальное значение: {3}", summ, middle, min, max);

                /*
                 * Задание 9
                 * 
                 * Строки - это массивы символов
                 * Посчитать сколько в строке символов о
                 * 
                 * */
                PrintString("Задание 9");
                string strArr = "Здоров, Сиплый! Есть работа! На стреме постоишь?";
                Console.WriteLine(strArr);
                summ = 0;
                for (int i = 0; i < strArr.Length; i++)
                    if (strArr[i] == 'о')
                        summ++;
                Console.WriteLine("Количество букавок о: " + summ);

                /*
                 * Задание 10
                 * 
                 * Несколько условий
                 * Считываем три числа и если все числа положительные или все отрицательные или все равны 0, тогда выводим сообщения (делаем с помощью "и" и "или")
                 * 
                 * */
                PrintString("Задание 10");
                Console.Write("Введите число a: ");
                a = Convert.ToInt32(Console.ReadLine());
                Console.Write("Введите число b: ");
                b = Convert.ToInt32(Console.ReadLine());
                Console.Write("Введите число c: ");
                int c = Convert.ToInt32(Console.ReadLine());
                if (a > 0 && b > 0 && c > 0 || a < 0 && b < 0 && c < 0 || a == 0 && b == 0 && c == 0)
                    Console.WriteLine("Все три числа одного знака!");
                else
                    Console.WriteLine("Числа разных знаков!");

                /*
                 * Задание 11 (немного коллекции)
                 * 
                 * Считываем числа с консоли до тех пор, пока не прийдет 0
                 * Если пришел 0, то все числа выводим
                 * 
                 * */
                PrintString("Задание 11");
                List<double> allNumber = new List<double>();
                while (true)
                {
                    double prK = Convert.ToDouble(Console.ReadLine());
                    if (prK != 0)
                        allNumber.Add(prK);
                    else
                        break;
                }
                foreach (double number in allNumber)
                    Console.Write(number + " ");
                Console.WriteLine();

                /*
                 * Задание 12
                 * 
                 * Мутим свое поле чудес
                 * 
                 * */
                PrintString("Задание 12");
                //Информация о вопросе
                string text = "Вниманеи вопрос: 4 ноги, но не ноги\n"
                    + "в него входят и выходят и всем это нравится!";
                Console.WriteLine(text);
                string q = "АВТОМОБИЛЬ";            //Задание
                q = q.ToUpper();
                List<char> o = new List<char>();    //Ответы
                while (true)
                {
                    bool proverkaAllWord = true;
                    for (int i = 0; i < q.Length; i++)
                    {
                        bool proverka = true;
                        foreach(char ch in o)
                        {
                            if (ch == q[i])
                                proverka = false;
                        }
                        if (proverka)
                        {
                            Console.Write("* ");
                            proverkaAllWord = false;
                        }
                        else
                            Console.Write(q[i] + " ");
                    }
                    if (!proverkaAllWord)
                    {
                        Console.WriteLine();
                        Console.Write("Ваша буква: ");
                        string newStr = Console.ReadLine();
                        newStr = newStr.ToUpper();
                        char newC = Convert.ToChar(newStr);
                        o.Add(newC);
                    }
                    else
                    {
                        Console.WriteLine("\nПоздравляю! Вы выиграли ничего!");
                        break;
                    }
                }
            }
            catch (Exception e)
            {
                Console.WriteLine("Упс! Что-то пошло не так!");
                Console.WriteLine(e.Message);
            }
            Console.ReadKey();
        }

        //Функция сравнения двух чисел
        static void ComparerTwoNumber(int a, int b)
        {
            if (a > b)
                Console.WriteLine("a > b");
            else if (b > a)
                Console.WriteLine("a < b");
            else
                Console.WriteLine("a = b");
        }

        //Функция для вывода разделителя
        static void PrintString(string q, string str = "-", int k = 21)
        {
            for (int i = 0; i < k; i++)
                Console.Write(str);
            Console.Write(q);
            for (int i = 0; i < k; i++)
                Console.Write(str);
            Console.WriteLine();
        }
    }
}