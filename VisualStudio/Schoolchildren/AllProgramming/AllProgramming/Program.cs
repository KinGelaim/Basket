using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading;

namespace AllProgramming
{
    class Program
    {
        static void Main(string[] args)
        {
            //Console.WriteLine("---------ТИПЫ ДАННЫХ---------");
            //Question11();
            //Question12();
            //Question13();
            //Question14();
            //Question15();
            //Question16();
            //Question17();
            //Console.WriteLine("\n---------ОПЕРАТОРЫ---------");
            //Question21();
            //Question22();
            //Question23();
            //Question24();
            //Console.WriteLine("\n---------ВЕТВЛЕНИЕ---------");
            //Question31();
            //Question32();
            //Question33();
            //Question34();
            //Console.WriteLine("\n---------ЦИКЛЫ---------");
            //Question41();
            //Question42();
            //Question43();
            //Question44();
            //Question45();
            //Question46();
            //Question47();
            //Question47();
            //Console.WriteLine("\n---------МАССИВЫ---------");
            //Question51();
            //Question52();
            //Question53();
            //Console.WriteLine("\n---------ФУНКЦИИ---------");
            //Question61();
            //Question62();
            //Question63();
            //Console.WriteLine("\n---------ПОТОКИ---------");
            //Question71();
            //Question72();
            Console.WriteLine("\n---------КЛАССЫ---------");
            Question81();
            //Console.WriteLine("\n---------ЗАДАЧКИ---------");
            //Question91();
            //Question92();

            Console.ReadKey();
        }

        #region ФУНКЦИИ ДЛЯ ТИПОВ ДАННЫХ
        static void Question11()
        {
            Console.WriteLine("\n         Задание 1         ");
            int x = 20;
            int y = 12;
            Console.WriteLine("Переменная 1: " + x);
            Console.WriteLine("Переменная 2: " + y);
            Console.WriteLine("Сложение: " + (x + y));
            Console.WriteLine("Вычитание: " + (x - y));
            Console.WriteLine("Умножение: " + x * y);
        }

        static void Question12()
        {
            Console.WriteLine("\n         Задание 2         ");
            double x = 2725.23;
            double y = x / 1000;
            Console.WriteLine("Расстояние в метрах: " + x);
            Console.WriteLine("Расстояние в км: " + y);
        }

        static void Question13()
        {
            Console.WriteLine("\n         Задание 3         ");
            char ch1 = 'd';
            char ch2 = '4';
            char ch3 = '^';
            char ch4 = '♫';
            Console.WriteLine("Символ 1: " + ch1);
            Console.WriteLine("Символ 2: " + ch2);
            Console.WriteLine("Символ 3: " + ch3);
            Console.WriteLine("Символ 4: " + ch4);
        }

        static void Question14()
        {
            Console.WriteLine("\n         Задание 4         ");
            bool a = true;
            bool b = false;
            Console.WriteLine("Значение переменной a = " + a);
            Console.WriteLine("Значение переменной b = " + b);
            Console.WriteLine("Значение 10 > 7 равно " + (10>7));
        }

        static void Question15()
        {
            Console.WriteLine("\n         Задание 5         ");
            string str1 = "Это первая строчка!";
            string str2 = "Вот тут вторая строка!";
            string str3 = "Бац и третья ^_^";
            Console.WriteLine("Строка 1: " + str1);
            Console.WriteLine("Строка 2: " + str2);
            Console.WriteLine("Строка 3: " + str3);
            string str4 = str1 + str2 + str3;
            Console.WriteLine("Строка 4: " + str4);
        }

        static void Question16()
        {
            Console.WriteLine("\n         Задание 6         ");
            Console.WriteLine("Первая строка\nВторая строка\nТретья строка\n");
            Console.WriteLine("Один\t\tДва\tТри\nЧетыре\t\tПять\tШесть\n");
            Console.WriteLine("\"Зачем тебе это?\", спросила она. \\О_о/");
        }

        static void Question17()
        {
            Console.WriteLine("\n         Задание 7         ");

            const double PI = 3.14;

            //PI = 7;   //Ошибка

            Console.WriteLine("Константа: " + PI);
        }
        #endregion

        #region ФУНКЦИИ ДЛЯ ОПЕРАТОРОВ
        static void Question21()
        {
            Console.WriteLine("\n         Задание 1         ");
            Console.Write("Введите первое число: ");
            string str1 = Console.ReadLine();
            double ch1 = Convert.ToDouble(str1);
            Console.Write("Введите второе число: ");
            string str2 = Console.ReadLine();
            double ch2 = Convert.ToDouble(str2);
            Console.WriteLine("Результат сложения: " + (ch1 + ch2));
            Console.WriteLine("Результат вычитания: " + (ch1 - ch2));
            Console.WriteLine("Результат умножения: " + (ch1 * ch2));
            Console.WriteLine("Результат деления: " + (ch1 / ch2));
        }
        static void Question22()
        {
            Console.WriteLine("\n         Задание 2         ");
            int x = 742156;
            short y = (short) x;
            Console.WriteLine("Целочисленно значение типа int: " + x);
            Console.WriteLine("Целочисленно значение типа short: " + y);
        }
        static void Question23()
        {
            Console.WriteLine("\n         Задание 3         ");
            double a, b, c, d;
            a = b = c = d = 49.9527863;
            Console.WriteLine("Значение a = " + a + "\nЗначение b = " + b + "\nЗначение c = " + c + "\nЗначение d = " + d);
        }
        static void Question24()
        {
            Console.WriteLine("\n         Задание 4         ");
            double x = 10;
            double y = 3;
            Console.WriteLine("Первое число: " + x);
            Console.WriteLine("Второе число: " + y);
            Console.WriteLine("Результат деления: " + x / y);
            Console.WriteLine("Остаток от деления: " + x % y);
        }
        #endregion

        #region ФУНКЦИИ ДЛЯ ВЕТВЛЕНИЯ
        static void Question31()
        {
            Console.WriteLine("\n         Задание 1         ");
            Console.Write("Введите первое число: ");
            string str1 = Console.ReadLine();
            double ch1 = Convert.ToDouble(str1);
            Console.Write("Введите второе число: ");
            string str2 = Console.ReadLine();
            double ch2 = Convert.ToDouble(str2);
            Console.Write("Введите третье число: ");
            string str3 = Console.ReadLine();
            double ch3 = Convert.ToDouble(str3);
            if (ch1 > ch2 && ch1 > ch3)
            {
                Console.WriteLine("Самое большое число: " + ch1);
            }
            if (ch2 > ch3 && ch2 > ch1)
            {
                Console.WriteLine("Самое большое число: " + ch2);
            }
            if (ch3 > ch2 && ch3 > ch1)
            {
                Console.WriteLine("Самое большое число: " + ch3);
            }

            if (ch1 == ch2 && ch2 == ch3)
            {
                Console.WriteLine("Все числа равны!");
            }
            else if (ch1 == ch2)
            {
                Console.WriteLine("Числа один и два равны!");
            }
            else if (ch2 == ch3)
            {
                Console.WriteLine("Числа два и три равны!");
            }
            else if (ch1 == ch3)
            {
                Console.WriteLine("Числа один и три равны!");
            }
        }
        static void Question32()
        {
            Console.WriteLine("\n         Задание 2         ");
            int result = 0;
            Console.WriteLine("Чему равна целая часть от числа PI?");
            Console.WriteLine("1) 4\t\t 2) 3\n3) 14\t\t 4) 2");
            Console.Write("Ваш ответ: ");
            string str = Console.ReadLine();
            if (str == "2")
                result++;
            Console.WriteLine("\nКто изобрёл лампочку?");
            Console.WriteLine("1) Томас Эддисон\t\t 2) Бэнджемен Франклин\n3) Александр Францев\t\t 4) Дарвин");
            Console.Write("Ваш ответ: ");
            str = Console.ReadLine();
            if (str == "1")
                result++;
            Console.WriteLine("\nНазвание мультфильма: Том и ... ?");
            Console.WriteLine("1) Артём\t\t 2) Батон\n3) Джерри\t\t 4) Том");
            Console.Write("Ваш ответ: ");
            str = Console.ReadLine();
            if (str == "3")
                result++;
            Console.WriteLine("\nЧто нужно сделать со вторым томом романа, если он не получился?");
            Console.WriteLine("1) Съесть его\t\t 2) Закопать его\n3) Продать его\t\t 4) Сжечь его");
            Console.Write("Ваш ответ: ");
            str = Console.ReadLine();
            if (str == "4")
                result++;
            Console.WriteLine("\nКак звали девочку, которая несла пирожки для бабушки?");
            Console.WriteLine("1) Красная Шапочка\t\t 2) Красная Уточка\n3) Красная Дурочка\t\t 4) Красная Девочка");
            Console.Write("Ваш ответ: ");
            str = Console.ReadLine();
            if (str == "1")
                result++;
            Console.WriteLine("\nВаша оценка: " + result);
            switch (result)
            {
                case 5:
                    Console.WriteLine("Вы молодец! Так держать!");
                    break;
                case 4:
                    Console.WriteLine("Неплохо, но есть куда расти!");
                    break;
                case 3:
                    Console.WriteLine("Можно было и лучше!");
                    break;
                case 2:
                    Console.WriteLine("Весьма печальный результат!");
                    break;
                case 1:
                    Console.WriteLine("Всё очень плохо!");
                    break;
                case 0:
                    Console.WriteLine("КАК ЖЕ ВСЁ ПЛОХО! РОДИТЕЛЕЙ В ШКОЛ!");
                    break;
                default:
                    Console.WriteLine("Да ты читер!");
                    break;
            }

        }
        static void Question33()
        {
            Console.WriteLine("\n         Задание 3         ");
            Console.Write("Введите скорость первого лыжник: ");
            double v1 = Convert.ToDouble(Console.ReadLine());
            Console.Write("Введите расстояние до финиша: ");
            double s1 = Convert.ToDouble(Console.ReadLine());
            Console.Write("Введите скорость второго лыжник: ");
            double v2 = Convert.ToDouble(Console.ReadLine());
            Console.Write("Введите расстояние до финиша: ");
            double s2 = Convert.ToDouble(Console.ReadLine());
            Console.Write("Введите скорость третьего лыжник: ");
            double v3 = Convert.ToDouble(Console.ReadLine());
            Console.Write("Введите расстояние до финиша: ");
            double s3 = Convert.ToDouble(Console.ReadLine());
            Console.Write("Введите скорость четвертого лыжник: ");
            double v4 = Convert.ToDouble(Console.ReadLine());
            Console.Write("Введите расстояние до финиша: ");
            double s4 = Convert.ToDouble(Console.ReadLine());

            double t1 = s1 / v1;
            double t2 = s2 / v2;
            double t3 = s3 / v3;
            double t4 = s4 / v4;

            /*Console.WriteLine("Время до финиша у первого лыжника: " + t1);
            Console.WriteLine("Время до финиша у первого лыжника: " + t2);
            Console.WriteLine("Время до финиша у первого лыжника: " + t3);
            Console.WriteLine("Время до финиша у первого лыжника: " + t4);*/

            if (t1 == t2 || t1 == t3 || t1 == t4 || t2 == t3 || t2 == t4 || t3 == t4)
            {
                Console.WriteLine("Несколько лыжников пришли одновременно! Будет переигровка!");
            }
            else
            {
                if (t1 < t2 && t1 < t3 && t1 < t4)
                {
                    Console.WriteLine("Побеждает первый лыжник!");
                }
                if (t2 < t1 && t2 < t3 && t2 < t4)
                {
                    Console.WriteLine("Побеждает второй лыжник!");
                }
                if (t3 < t2 && t3 < t1 && t3 < t4)
                {
                    Console.WriteLine("Побеждает третий лыжник!");
                }
                if (t4 < t2 && t4 < t3 && t4 < t1)
                {
                    Console.WriteLine("Побеждает четвёртый лыжник!");
                }
            }
        }
        static void Question34()
        {
            Console.WriteLine("\n         Задание 4         ");
            Console.Write("Введите Ваше настроение: ");
            string str = Console.ReadLine();
            switch (str)
            {
                case "Хорошее":
                    Console.WriteLine("Это хорошо, когда всё хорошо ^_^");
                    break;
                case "Плохое":
                    Console.WriteLine("Это всего лишь дождь! Всё пройдет!");
                    break;
                case "Злое":
                    Console.WriteLine("Не кипятись!");
                    break;
                case "Доброе":
                    Console.WriteLine("Миру мир!");
                    break;
                case "Веселое":
                    Console.WriteLine("Я так за Вас рад!");
                    break;
                case "Голодное":
                    Console.WriteLine("Приготовьте себе покушать :3");
                    break;
                case "Суперское":
                    Console.WriteLine("Вперёд спасать город!");
                    break;
                default:
                    Console.WriteLine("Мне, как роботу, не свойственен такой настрой Т.Т");
                    break;
            }
        }
        #endregion

        #region ФУНКЦИИ ДЛЯ ЦИКЛОВ
        static void Question41()
        {
            Console.WriteLine("\n         Задание 1         ");
            for (int i = 2; i < 10; i++)
            {
                Console.WriteLine(i + "\t" + i*i + "\t" + i*i*i);
            }
        }
        static void Question42()
        {
            Console.WriteLine("\n         Задание 2         ");
            Console.Write("Введите число 1: ");
            int x = Convert.ToInt32(Console.ReadLine());
            Console.Write("Введите число 2: ");
            int y = Convert.ToInt32(Console.ReadLine());
            int result = 0;
            if (x > y)
            {
                for (int i = y; i <= x; i++)
                {
                    result += i;
                }
            }
            else
            {
                for (int i = x; i <= y; i++)
                {
                    result += i;
                }
            }
            Console.WriteLine("Сумма чисел равна: " + result);

        }
        static void Question43()
        {
            Console.WriteLine("\n         Задание 3         ");
            Console.Write("Введите число 1: ");
            int x = Convert.ToInt32(Console.ReadLine());
            Console.Write("Введите число 2: ");
            int y = Convert.ToInt32(Console.ReadLine());
            if (x < y)
            {
                for (int i = x; i <= y; i++)
                {
                    if (i < 0)
                    {
                        Console.WriteLine(i + " число отрицательное");
                    }
                    if (i == 0)
                    {
                        Console.WriteLine(i + " число равно 0");
                    }
                    if (i > 0)
                    {
                        Console.WriteLine(i + " число положительное");
                    }
                }
            }
            else
            {
                for (int i = y; i <= x; i++)
                {
                    if (i < 0)
                    {
                        Console.WriteLine(i + " число отрицательное");
                    }
                    if (i == 0)
                    {
                        Console.WriteLine(i + " число равно 0");
                    }
                    if (i > 0)
                    {
                        Console.WriteLine(i + " число положительное");
                    }
                }
            }
        }
        static void Question44()
        {
            Console.WriteLine("\n         Задание 4         ");
            for (int i = 27; i >= 4; i--)
            {
                if (i == 24 || i == 16 || i == 15)
                {
                    continue;
                }
                if (i == 7)
                {
                    break;
                }
                Console.WriteLine(i);
            }
        }
        static void Question45()
        {
            Console.WriteLine("\n         Задание 5         ");
            for (int i = 0; i < 10; i++)
            {
                int result = 1;
                int k = i;
                while (k > 0)
                {
                    result *= 2;
                    k--;
                }
                Console.WriteLine("2 в степени " + i + " = " + result);
            }
        }
        static void Question46()
        {
            Console.WriteLine("\n         Задание 6         ");
            Console.Write("Введите число: ");
            int x = Convert.ToInt32(Console.ReadLine());

            //Вариант 1
            string str = Convert.ToString(x);
            int len = 0;
            for (int i = 0; i < str.Length; i++)
            {
                len++;
            }
            Console.WriteLine("Вариант 1) Длина числа: " + len);

            //Вариант 2
            len = 0;
            while (x > 0)
            {
                len++;
                x = x / 10;
            }
            Console.WriteLine("Вариант 2) Длина числа: " + len);
        }
        static void Question47()
        {
            Console.WriteLine("\n         Задание 7         ");
            Console.Write("Введите число: ");
            double d = Convert.ToDouble(Console.ReadLine());

            //Вариант 1
            Console.WriteLine("Вариант 1 (без DO)");
            double result = d / 2;
            Console.WriteLine("Результат: " + result);
            while (result > 2)
            {
                result /= 2;
                Console.WriteLine("Результат: " + result);
            }

            //Вариант 2
            Console.WriteLine("Вариант 2 (с DO)");
            do
            {
                d /= 2;
                Console.WriteLine("Результат: " + d);
            }
            while (d > 2);
        }
        #endregion
        
        #region ФУНКЦИИ ДЛЯ МАССИВОВ
        static void Question51()
        {
            Console.WriteLine("\n         Задание 1         ");
            Console.Write("Введите кол-во элементов: ");
            int count = Convert.ToInt32(Console.ReadLine());
            int[] arr = new int[count];
            for (int i = 0; i < arr.Length; i++)
            {
                Console.Write("Введите число: ");
                int d = Convert.ToInt32(Console.ReadLine());
                arr[i] = d;
            }
            //Находим наибольшее число
            int k = 0;
            int max = arr[k];
            for (int i = 0; i < arr.Length; i++)
            {
                if(max < arr[i])
                {
                    max = arr[i];
                    k = i;
                }
            }
            //Выводим число и порядковый номер
            Console.WriteLine("Максимальное значение в массиве под номером " + (k + 1) + " и равно " + max);
        }
        static void Question52()
        {
            Console.WriteLine("\n         Задание 2         ");
            Console.Write("Введите строку: ");
            string str = Console.ReadLine();
            string prStr = "";
            for (int i = str.Length - 1; i >= 0; i--)
            {
                prStr += str[i];
            }
            Console.WriteLine(prStr);
        }
        static void Question53()
        {
            Console.WriteLine("\n         Задание 3         ");
            //Заполняем первый массив, пока не придёт отрицательный элемент
            int[] arr = new int[0];
            bool isEnter = true;
            while (isEnter)
            {
                Console.Write("Введите число: ");
                int k = Convert.ToInt32(Console.ReadLine());
                if (k < 0)
                {
                    isEnter = false;
                    continue;
                }
                int[] prArr = new int[arr.Length + 1];
                for (int i = 0; i < arr.Length; i++)
                {
                    prArr[i] = arr[i];
                }
                prArr[prArr.Length - 1] = k;
                arr = prArr;
            }

            //Выводим
            double result = 0;
            for (int i = 0; i < arr.Length; i++)
            {
                Console.Write(arr[i] + " ");
                result += arr[i];
            }
            Console.WriteLine("\nСреднее значение: " + result / arr.Length);
            //Запрашиваем число на удаление
            Console.Write("Введите число, которое нужно удалить: ");
            int del = Convert.ToInt32(Console.ReadLine());
            int[] resultArr = new int[0];
            for (int i = 0; i < arr.Length; i++)
            {
                if (arr[i] != del)
                {
                    int[] prArr = new int[resultArr.Length + 1];
                    for (int j = 0; j < resultArr.Length; j++)
                    {
                        prArr[j] = resultArr[j];
                    }
                    prArr[prArr.Length - 1] = arr[i];
                    resultArr = prArr;
                }
            }
            //Выводим
            result = 0;
            for (int i = 0; i < resultArr.Length; i++)
            {
                Console.Write(resultArr[i] + " ");
                result += resultArr[i];
            }
            Console.WriteLine("\nСреднее значение: " + result / resultArr.Length);

        }
        #endregion

        #region ФУНКЦИИ ДЛЯ ФУНКЦИЙ
        static void Question61()
        {
            Console.WriteLine("\n         Задание 1         ");
            Console.Write("Введите число a: ");
            double a = Convert.ToInt32(Console.ReadLine());
            Console.Write("Введите число b: ");
            double b = Convert.ToInt32(Console.ReadLine());
            Console.Write("Введите число c: ");
            double c = Convert.ToInt32(Console.ReadLine());
            KvadratnoeUravnenie(a, b, c);
        }
        static void KvadratnoeUravnenie(double a, double b, double c = 0)
        {
            //Выводим уравнение
            string str = a + "x^2";
            if (b > 0)
            {
                str += "+" + b;
            }
            else if(b < 0)
            {
                str += b;
            }
            str += "x";
            if (c > 0)
            {
                str += "+" + c;
            }
            else if (c < 0)
            {
                str += c;
            }
            Console.WriteLine("Искомое уравнение: " + str);
            //Находим дискременант
            double d = Math.Pow(b, 2) - 4 * a * c;
            d = Math.Sqrt(d);

            double x1 = (-1 * b + d) / (2 * a);
            double x2 = (-1 * b - d) / (2 * a);
            Console.WriteLine("Корень уравнения 1: " + x1);
            Console.WriteLine("Корень уравнения 2: " + x2);
        }
        static void Question62()
        {
            Console.WriteLine("\n         Задание 2         ");
            Console.Write("Введите число 1: ");
            double x = Convert.ToDouble(Console.ReadLine());
            Console.Write("Введите число 2: ");
            double y = Convert.ToDouble(Console.ReadLine());
            Console.WriteLine(x + " " + CompaerChar(x, y) + " " + y);
        }
        static char CompaerChar(double a, double b)
        {
            if (a > b)
            {
                return '>';
            }
            if (a < b)
            {
                return '<';
            }
            return '=';
        }
        static void Question63()
        {
            Console.WriteLine("\n         Задание 3         ");
            Console.Write("Введите число 1: ");
            double x = Convert.ToDouble(Console.ReadLine());
            Console.Write("Введите число 2: ");
            double y = Convert.ToDouble(Console.ReadLine());
            if(CompaerBool(x, '>', y))
            {
                Console.WriteLine(x + ">" + y);
            }
            if (CompaerBool(x, '<', y))
            {
                Console.WriteLine(x + "<" + y);
            }
            if (CompaerBool(x, '=', y))
            {
                Console.WriteLine(x + "=" + y);
            }
        }
        static bool CompaerBool(double a, char ch, double b)
        {
            switch (ch)
            {
                case '>':
                    if (a > b)
                        return true;
                    break;
                case '<':
                    if (a < b)
                        return true;
                    break;
                case '=':
                    if (a == b)
                        return true;
                    break;
            }
            return false;
        }
        #endregion

        #region ФУНКЦИИ ДЛЯ ПОТОКОВ
        static void Question71()
        {
            Console.WriteLine("\n         Задание 1         ");
            Thread thread1 = new Thread(() => {
                ThreadMethod();
                Console.WriteLine("Поток 1 завершен!");
            });
            Thread thread2 = new Thread(() =>
            {
                ThreadMethod();
                Console.WriteLine("Поток 2 завершен!");
            });
            Thread thread3 = new Thread(() =>
            {
                ThreadMethod();
                Console.WriteLine("Поток 3 завершен!");
            });
            Console.WriteLine("Стартуем потоки!");
            thread1.Start();
            thread2.Start();
            thread3.Start();
            Console.WriteLine("Все потоки стартанули!");
            Console.WriteLine("Конец основного потока!");
        }
        static void ThreadMethod()
        {
            for (int i = 0; i < 100; i++)
            {
                Thread.Sleep(10);
            }
        }
        static void Question72()
        {
            Console.WriteLine("\n         Задание 2         ");
            bool isEndThread1 = false;
            bool isEndThread2 = false;
            bool isEndThread3 = false;
            Thread thread1 = new Thread(() =>
            {
                isEndThread1 = ThreadMethodBool();
                Console.WriteLine("Поток 1 завершен!");
            });
            Thread thread2 = new Thread(() =>
            {
                isEndThread2 = ThreadMethodBool();
                Console.WriteLine("Поток 2 завершен!");
            });
            Thread thread3 = new Thread(() =>
            {
                isEndThread3 = ThreadMethodBool();
                Console.WriteLine("Поток 3 завершен!");
            });
            Console.WriteLine("Стартуем потоки!");
            thread1.Start();
            thread2.Start();
            thread3.Start();
            Console.WriteLine("Все потоки стартанули!");
            Console.WriteLine("Ожидаем завершение потоков");
            while (!isEndThread1 || !isEndThread2 || !isEndThread3) ;
            //while (thread1.IsAlive || thread2.IsAlive || thread3.IsAlive) ;
            Console.WriteLine("Основной поток завершен!");
        }
        static bool ThreadMethodBool()
        {
            for (int i = 0; i < 1000; i++)
            {
                Thread.Sleep(10);
            }
            return true;
        }
        #endregion

        #region ФУНКЦИИ ДЛЯ КЛАССОВ
        static void Question81()
        {
            City city1 = new City();
            city1.name = "КулСити";
            city1.PrintInfo();

            City city2 = new City("СекондСити");
            city2.description = "Никто не знает точное появление этого города.\n" +
                "Может он был основан вторым в этом постапокалиптическом мире?\n" +
                "А может его основали любители одеваться в Секонд Хэнде!";
            city2.AddCountHuman(3007, 54789);
            city2.AddCountHuman(3008, 64789);
            city2.AddCountHuman(3009, 174789);
            city2.PrintInfo();
        }
        #endregion

        #region ФУНКЦИИ ДЛЯ МИНИ ЗАДАЧЕК
        static void Question91()
        {
            Console.WriteLine("\n         Задание 1         ");
            Console.Write("Введите кол-во элементов: ");
            int count = Convert.ToInt32(Console.ReadLine());
            int[] arr = new int[count];
            Random rand = new Random();
            //Массив для фиксации элементов, которые уже ввели
            int[] enterArr = new int[0];
            int k = 0;
            for (int i = 0; i < arr.Length; i++)
            {
                while (true)
                {
                    k = rand.Next(0, count);
                    bool isEnterInArr = false;
                    for (int j = 0; j < enterArr.Length; j++)
                    {
                        if (enterArr[j] == k)
                            isEnterInArr = true;
                    }
                    if (!isEnterInArr)
                    {
                        int[] prArr = new int[enterArr.Length + 1];
                        for (int j = 0; j < enterArr.Length; j++)
                        {
                            prArr[j] = enterArr[j];
                        }
                        prArr[prArr.Length - 1] = k;
                        enterArr = prArr;
                        break;
                    }
                }
                Console.Write("Введите число " + (k+1) + ": ");
                int ch = Convert.ToInt32(Console.ReadLine());
                arr[k] = ch;
            }
            int indexMin = 0;
            int indexMax = 0;
            int min = arr[indexMin];
            int max = arr[indexMax];
            double result = 0;
            for (int i = 0; i < arr.Length; i++)
            {
                if (min > arr[i])
                {
                    min = arr[i];
                    indexMin = i;
                }
                if (max < arr[i])
                {
                    max = arr[i];
                    indexMax = i;
                }
                result += arr[i];
            }
            Console.WriteLine("Минимальное значение под номером " + (indexMin + 1) + " и равно " + min);
            Console.WriteLine("Максимальное значение под номером " + (indexMax + 1) + " и равно " + max);
            Console.WriteLine("Сумма всех элементов равна " + result);
            Console.WriteLine("Среднее значение составляет " + result / arr.Length);
        }
        static void Question92()
        {
            Console.WriteLine("\n         Задание 2         ");
            Console.Write("Выберите режим работы (1-без проверки на совпадение, 2 - с проверкой): ");
            string strType = Console.ReadLine();
            bool isCheckType = false;
            if (strType == "2")
                isCheckType = true;
            string[] tasks = new string[0];
            while (true)
            {
                Console.Write("Введите Вашу задачу: ");
                string task = Console.ReadLine();
                
                //Проверяем на выход
                if (task == "!список")
                {
                    break;
                }
                
                //Проверяем на наличие уже такой задачи
                if (isCheckType)
                {
                    bool proverka = false;
                    for (int i = 0; i < tasks.Length; i++)
                    {
                        if (task == tasks[i])
                        {
                            proverka = true;
                        }
                    }
                    if (proverka)
                    {
                        Console.WriteLine("Такая задача уже записана!");
                        continue;
                    }
                }
                
                //Добавляем в массив
                string[] prTasks = new string[tasks.Length + 1];
                for (int i = 0; i < tasks.Length; i++)
                {
                    prTasks[i] = tasks[i];
                }
                prTasks[prTasks.Length - 1] = task;
                tasks = prTasks;
            }
            for (int i = 0; i < tasks.Length; i++)
            {
                Console.WriteLine((i + 1) + " " + tasks[i]);
            }
        }
        #endregion
    }
}