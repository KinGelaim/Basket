using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

namespace Calculaters
{
    class Program
    {
        static void Main(string[] args)
        {
            //Calculater1();
            //Calculater2();
            //Calculater3();
            //Calculater4();
            //Calculater5();
            //Calculater6();
            Calculater7();
        }

        //Кулькулятор на N чисел в строку (не правильная последовательность)
        static void Calculater7()
        {
            //Запрашиваем пример
            Console.Write("Введите строку примера: ");
            string primer = Console.ReadLine();

            //Переменная для результата
            double result = 0;
            //Переменная для хранения первого набора цифр
            string beginNumber = "";
            //Переменная для хранения предыдущего набора цифр
            string prevNumber = "";
            //Переменная для хранения предыдущего символа (используем именно стринг, чтобы была длина!)
            string prevSymbol = "";

            //Идем по примеру
            for (int i = 0; i < primer.Length; i++)
            {
                //Обрабатываем первый символ
                if(i == 0)
                {
                    //Проверка на правильность начала примера
                    if(primer[i] == '*' || primer[i] == '/')
                    {
                        Console.WriteLine("Начало примера неверное!");
                        return;
                    }

                    beginNumber += primer[i];
                }
                else
                {
                    //Проверка на символ
                    if (primer[i] == '+' || primer[i] == '-' || primer[i] == '*' || primer[i] == '/')
                    {
                        //Если это еще только первое действие!!!
                        if (prevSymbol.Length == 0)
                        {
                            prevSymbol = primer[i].ToString();
                        }
                        else
                        {
                            if (beginNumber.Length != 0)
                            {
                                double chislo1 = Convert.ToDouble(beginNumber);
                                double chislo2 = Convert.ToDouble(prevNumber);
                                result = ResultSwitch(chislo1, prevSymbol, chislo2, result);
                                beginNumber = "";
                                prevNumber = "";
                            }
                            else
                            {
                                double chislo2 = Convert.ToDouble(prevNumber);
                                result = ResultSwitch(result, prevSymbol, chislo2, result);
                                prevNumber = "";
                            }
                            prevSymbol = primer[i].ToString();
                        }

                    }
                    else
                    {
                        if (prevSymbol.Length == 0)
                        {
                            beginNumber += primer[i];
                        }
                        else
                        {
                            prevNumber += primer[i];
                        }
                    }
                }
            }
            double endChislo = Convert.ToDouble(prevNumber);
            result = ResultSwitch(result, prevSymbol, endChislo, result);

            Console.WriteLine("Результат: " + result);
        }

        //Кулькулятор на N чисел, но с правильной последовательностью (ухх, ну и жесть (так думал, пока не начал))
        static void Calculater6()
        {
            Console.WriteLine("--- Калькулятор на N чисел (с учетом действий) ---");
            Console.Write("Введите количество чисел: ");
            int count = Convert.ToInt32(Console.ReadLine());
            if (count >= 2)
            {
                //Массивы для примера
                double[] arrNumbers = new double[count];
                string[] arrSymbols = new string[count - 1];

                Console.Write("Введите число 1: ");
                string str1 = Console.ReadLine();
                double ch1 = Convert.ToDouble(str1);
                arrNumbers[0] = ch1;

                //Считываем пример
                for (int i = 1; i < count; i++)
                {
                    Console.Write("Введите символ между числом " + i + " и числом " + (i + 1) + ": ");
                    string prSymbStr = Console.ReadLine();
                    arrSymbols[i - 1] = prSymbStr;
                    Console.Write("Введите число "+(i+1)+": ");
                    string prStr = Console.ReadLine();
                    double prCh = Convert.ToDouble(prStr);
                    arrNumbers[i] = prCh;
                }

                //Массив для хранения индекса элемента, который уже используется
                int[] indexArr = new int[0];
                //Массивы для вычисления только сложений и вычитаний
                //double[] trueArrNumbers = new double[0];
                //string[] trueArrSymbols = new string[0];

                //Счетчик, потому что изменяем массив по которому идём
                int k = arrSymbols.Length;
                //Избавляемся от знаков * и /
                for (int z = 0; z < k; z++)
                {
                    for (int i = 0; i < arrSymbols.Length; i++)
                    {
                        if (arrSymbols[i] == "*" || arrSymbols[i] == "/")
                        {
                            //Выполняеми действие
                            double[] prTrueArrNumbers = new double[arrNumbers.Length - 1];
                            //Переписываем старый массив (удалив второе значение и вместо первого записываем вычисления)
                            for (int j = 0; j < arrNumbers.Length - 1; j++)
                            {
                                if (i > j)
                                    prTrueArrNumbers[j] = arrNumbers[j];
                                if (i == j)
                                {
                                    //Считаем результат и записываем вместо первого (в данном действии)
                                    double res = 0;
                                    if(arrSymbols[i] == "*")
                                        res = arrNumbers[j] * arrNumbers[j + 1];
                                    if (arrSymbols[i] == "/")
                                        res = arrNumbers[j] / arrNumbers[j + 1];
                                    prTrueArrNumbers[j] = res;
                                }
                                if (i < j)
                                    prTrueArrNumbers[j] = arrNumbers[j + 1];
                            }
                            arrNumbers = prTrueArrNumbers;

                            //Создаём промежуточный массив для символов
                            string[] prTrueArrSymbols = new string[arrSymbols.Length - 1];
                            //Переписываем старый массив (удалив символ)
                            for (int j = 0; j < arrSymbols.Length; j++)
                            {
                                if (i > j)
                                    prTrueArrSymbols[j] = arrSymbols[j];
                                if (i < j)
                                    prTrueArrSymbols[j - 1] = arrSymbols[j];
                            }
                            //В наш массив символов присваиваем промежуточный массив символов
                            arrSymbols = prTrueArrSymbols;
                        }
                    }
                }
                //Считаем то, что осталось
                double result = arrNumbers[0];
                for (int i = 0; i < arrSymbols.Length; i++)
                {
                    if (arrSymbols[i] == "+")
                        result += arrNumbers[i + 1];
                    if (arrSymbols[i] == "-")
                        result -= arrNumbers[i + 1];
                }
                Console.WriteLine("Результат: " + result);
            }
            else
            {
                Console.WriteLine("И что мне дальше делать?");
            }
        }

        //Калькулятор на 5 чисел через switch (с функциями) (без действий арифметики) (Количество строк: 68)
        static void Calculater5()
        {
            //Спрашиваем два числа и символ между ними
            Console.Write("Введите число 1: ");
            string strCh1 = Console.ReadLine();
            double dCh1 = Convert.ToDouble(strCh1);
            Console.Write("Введите символ между числами 1 и 2: ");
            string strSymb1 = Console.ReadLine();
            Console.Write("Введите число 2: ");
            string strCh2 = Console.ReadLine();
            double dCh2 = Convert.ToDouble(strCh2);

            //Переменная для результата
            double fullResult = 0;

            //Промежуточный результат
            ResultSwitch(dCh1, strSymb1, dCh2, fullResult);

            //Запрашиваем второй символ и третье число
            Console.Write("Введите символ между числами 2 и 3: ");
            string strSymb2 = Console.ReadLine();
            Console.Write("Введите число 3: ");
            string strCh3 = Console.ReadLine();
            double dCh3 = Convert.ToDouble(strCh3);

            ResultSwitch(fullResult, strSymb2, dCh3, fullResult);

            //Запрашиваем третий символ и четвертое число
            Console.Write("Введите символ между числами 3 и 4: ");
            string strSymb3 = Console.ReadLine();
            Console.Write("Введите число 4: ");
            string strCh4 = Console.ReadLine();
            double dCh4 = Convert.ToDouble(strCh4);

            ResultSwitch(fullResult, strSymb3, dCh4, fullResult);

            //Запрашиваем четвертый символ и пятое число
            Console.Write("Введите символ между числами 4 и 5: ");
            string strSymb4 = Console.ReadLine();
            Console.Write("Введите число 5: ");
            string strCh5 = Console.ReadLine();
            double dCh5 = Convert.ToDouble(strCh5);

            ResultSwitch(fullResult, strSymb4, dCh5, fullResult);

            Console.WriteLine("Результат: " + fullResult);
        }

        //Функция для свича
        static double ResultSwitch(double a, string ch, double b, double result)
        {
            switch (ch)
            {
                case "+":
                    result = a + b;
                    break;
                case "-":
                    result = a - b;
                    break;
                case "*":
                    result = a * b;
                    break;
                case "/":
                    result = a / b;
                    break;
            }
            return result;
        }

        //Калькулятор на 5 чисел через switch (без функции) (без действий арифметики) (Кол-во строк: 103)
        static void Calculater4()
        {
            //Спрашиваем два числа и символ между ними
            Console.Write("Введите число 1: ");
            string strCh1 = Console.ReadLine();
            double dCh1 = Convert.ToDouble(strCh1);
            Console.Write("Введите символ между числами 1 и 2: ");
            string strSymb1 = Console.ReadLine();
            Console.Write("Введите число 2: ");
            string strCh2 = Console.ReadLine();
            double dCh2 = Convert.ToDouble(strCh2);

            //Переменная для результата
            double fullResult = 0;

            //Промежуточный результат
            switch (strSymb1)
            {
                case "+":
                    fullResult = dCh1 + dCh2;
                    break;
                case "-":
                    fullResult = dCh1 - dCh2;
                    break;
                case "*":
                    fullResult = dCh1 * dCh2;
                    break;
                case "/":
                    fullResult = dCh1 / dCh2;
                    break;
            }

            //Запрашиваем второй символ и третье число
            Console.Write("Введите символ между числами 2 и 3: ");
            string strSymb2 = Console.ReadLine();
            Console.Write("Введите число 3: ");
            string strCh3 = Console.ReadLine();
            double dCh3 = Convert.ToDouble(strCh3);

            switch (strSymb2)
            {
                case "+":
                    fullResult += dCh3;
                    break;
                case "-":
                    fullResult -= dCh3;
                    break;
                case "*":
                    fullResult *= dCh3;
                    break;
                case "/":
                    fullResult /= dCh3;
                    break;
            }

            //Запрашиваем третий символ и четвертое число
            Console.Write("Введите символ между числами 3 и 4: ");
            string strSymb3 = Console.ReadLine();
            Console.Write("Введите число 4: ");
            string strCh4 = Console.ReadLine();
            double dCh4 = Convert.ToDouble(strCh4);

            switch (strSymb3)
            {
                case "+":
                    fullResult += dCh4;
                    break;
                case "-":
                    fullResult -= dCh4;
                    break;
                case "*":
                    fullResult *= dCh4;
                    break;
                case "/":
                    fullResult /= dCh4;
                    break;
            }

            //Запрашиваем четвертый символ и пятое число
            Console.Write("Введите символ между числами 4 и 5: ");
            string strSymb4 = Console.ReadLine();
            Console.Write("Введите число 5: ");
            string strCh5 = Console.ReadLine();
            double dCh5 = Convert.ToDouble(strCh5);

            switch (strSymb4)
            {
                case "+":
                    fullResult += dCh5;
                    break;
                case "-":
                    fullResult -= dCh5;
                    break;
                case "*":
                    fullResult *= dCh5;
                    break;
                case "/":
                    fullResult /= dCh5;
                    break;
            }

            Console.WriteLine("Результат: " + fullResult);
        }

        //Калькулятор на три числа (с правильной постановкой)
        static void Calculater3()
        {
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
        
        //Калькулятор на N чисел, но решается все по порядку (без учёта арифметики)
        static void Calculater2()
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

        //Калькулятор на два числа через switch
        static void Calculater1()
        {
            Console.Write("Введите число 1:");
            string strCh1 = Console.ReadLine();
            int chislo1 = Convert.ToInt32(strCh1);
            Console.Write("Введите символ:");
            string strSymbol = Console.ReadLine();
            Console.Write("Введите число 2:");
            string strCh2 = Console.ReadLine();
            int chislo2 = Convert.ToInt32(strCh2);
            double res = 0;
            switch (strSymbol)
            {
                case "+":
                    res = chislo1 + chislo2;
                    Console.WriteLine("Результат: " + res);
                    break;
                case "-":
                    res = chislo1 - chislo2;
                    Console.WriteLine("Результат: " + res);
                    break;
                case "*":
                    res = chislo1 * chislo2;
                    Console.WriteLine("Результат: " + res);
                    break;
                case "/":
                    res = Convert.ToDouble(chislo1) / Convert.ToDouble(chislo2);
                    Console.WriteLine("Результат: " + res);
                    break;
                default:
                    Console.WriteLine("Ошибка при вводе знака!");
                    break;
            }
        }
    }
}
