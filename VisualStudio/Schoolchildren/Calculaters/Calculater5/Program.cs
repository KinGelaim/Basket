using System;
using System.Linq;

namespace Calculater5
{
    public class Program
    {
        private static void Main(string[] args)
        {
            //Calculater1();
            //Calculater2();      // Калькулятор на N чисел (без правильной арифметики с преобразованием в ())
            //Calculater3();
            //Calculater4();
            //Calculater5();
            //Calculater6();
            //Calculater7();
            //Calculater8();    // Калькулятор на N чисел в строку (с правильной последовательностью)
            Calculater9();      // Калькулятор на N чисел в строку (с правильной последовательностью и выделением ошибок)
            Console.ReadKey();
        }

        /// <summary>
        /// Кулькулятор на N чисел в строку с правильной последовательностью и выделению ошибок
        /// </summary>
        private static void Calculater9()
        {
            Console.WriteLine("--- Калькулятор на N чисел (с учетом действий) ---");
            while (true)
            {
                Console.Write("Введите пример: ");

                string input = Console.ReadLine().ToLower();
                if (input == "exit")
                    break;

                char[] trueSymbol = { '+', '-', '*', '/' };  // Список допустимых символов

                double[] arrNumbers = new double[0];    // Массив для чисел
                string[] arrSymbols = new string[0];    // Массив для действий (знаков)

                string previewNumber = string.Empty;
                string previewSymbol = string.Empty;

                bool error = false;
                string strError = string.Empty;

                // Разбираем строку и формируем строку ошибки
                for (int i = 0; i < input.Length; i++)
                {
                    int prInt = 0;
                    if (input[i] == ',' || input[i] == '.')
                        if (previewNumber.Length == 0)
                        {
                            error = true;
                            strError += "<" + input[i] + ">";
                        }
                        else
                            previewNumber += ',';
                    else if (int.TryParse(input[i].ToString(), out prInt))
                    {
                        previewNumber += prInt;
                        previewSymbol = string.Empty;
                    }
                    else if (trueSymbol.Contains(input[i]))
                    {
                        if (previewSymbol.Length == 0)
                        {
                            if (previewNumber.Length != 0)
                            {
                                previewSymbol = input[i].ToString();
                                arrSymbols = arrSymbols.Add(previewSymbol);

                                double parseDouble = 0;
                                if (double.TryParse(previewNumber, out parseDouble))
                                {
                                    arrNumbers = arrNumbers.Add(parseDouble);
                                    strError += previewNumber;
                                    previewNumber = "";
                                }
                                else
                                {
                                    error = true;
                                    strError += "<" + previewNumber + ">";
                                }
                                strError += previewSymbol;
                            }
                            else
                                if (input[i] == '-' || input[i] == '+')
                                    previewNumber = "-";
                                else
                                {
                                    error = true;
                                    strError += "<" + input[i] + ">";
                                }
                        }
                        else
                            if (previewSymbol.Length == 1 && (input[i] == '-' || input[i] == '+'))
                            {
                                previewNumber = previewNumber.Insert(0, input[i].ToString());   // Инсерт не нужен (только для примера и не забываем присвоить)
                            }
                            else
                            {
                                error = true;
                                strError += "<" + input[i] + ">";
                            }
                    }
                    else
                        strError += "<" + input[i] + ">";
                }
                double parseNumber = 0;
                if (double.TryParse(previewNumber, out parseNumber))
                {
                    arrNumbers = arrNumbers.Add(parseNumber);
                    strError += parseNumber;
                }
                else
                {
                    error = true;
                    strError += "<" + previewNumber + ">";
                }

                // Вывод ошибок
                if (error)
                {
                    Console.WriteLine("Ошибочка!");
                    for (int i = 0; i < strError.Length; i++ )
                    {
                        if (strError[i] == '<')
                        {
                            Console.ForegroundColor = ConsoleColor.Red;
                            continue;
                        }
                        if (strError[i] == '>')
                        {
                            Console.ForegroundColor = ConsoleColor.Gray;
                            continue;
                        }
                        Console.Write(strError[i]);
                    }
                    Console.WriteLine();
                }

                if (!error)
                {
                    // Расчёты
                    if (arrNumbers.Length >= 2)
                    {
                        //Счетчик, потому что изменяем массив по которому идём
                        int k = arrSymbols.Length;
                        //Избавляемся от знаков * и /
                        for (int z = 0; z < k; z++)
                        {
                            for (int i = 0; i < arrSymbols.Length; i++)
                            {
                                if (arrSymbols[i] == "*" || arrSymbols[i] == "/")
                                {
                                    //Выполняем действие
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
                                            if (arrSymbols[i] == "*")
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
            }
        }

        /// <summary>
        /// Кулькулятор на N чисел в строку с правильной последовательностью
        /// </summary>
        private static void Calculater8()
        {
            Console.WriteLine("--- Калькулятор на N чисел (с учетом действий) ---");
            while (true)
            {
                Console.Write("Введите пример: ");

                string input = Console.ReadLine().ToLower();
                if (input == "exit")
                    break;

                char[] trueSymbol = {'+','-','*','/'};  // Список допустимых символов

                double[] arrNumbers = new double[0];    // Массив для чисел
                string[] arrSymbols = new string[0];    // Массив для действий (знаков)

                string previewNumber = string.Empty;
                string previewSymbol = string.Empty;

                bool error = false;

                // Разбираем строку
                for (int i = 0; i < input.Length; i++)
                {
                    int prInt = 0;
                    if (input[i] == ',' || input[i] == '.')
                        if (previewNumber.Length == 0)
                            error = true;
                        else
                            previewNumber += ',';
                    else if (int.TryParse(input[i].ToString(), out prInt))
                    {
                        previewNumber += prInt;
                        previewSymbol = string.Empty;
                    }
                    else if(trueSymbol.Contains(input[i]))
                    {
                        if (previewSymbol.Length == 0)
                        {
                            previewSymbol = input[i].ToString();
                            arrSymbols = arrSymbols.Add(previewSymbol);

                            double parseDouble = 0;
                            if (double.TryParse(previewNumber, out parseDouble))
                            {
                                arrNumbers = arrNumbers.Add(parseDouble);
                                previewNumber = "";
                            }
                            else
                                error = true;
                        }
                        else
                            if (previewSymbol.Length == 1 && (input[i] == '-' || input[i] == '+'))
                            {
                                previewNumber = previewNumber.Insert(0, input[i].ToString());   // Инсерт не нужен (только для примера и не забываем присвоить)
                            }
                            else
                                error = true;
                    }
                }
                double parseNumber = 0;
                if (double.TryParse(previewNumber, out parseNumber)) arrNumbers = arrNumbers.Add(parseNumber);
                else error = true;

                if (error)
                {
                    Console.WriteLine("Ошибочка!");
                    break;
                }

                if (arrNumbers.Length >= 2)
                {
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
                                //Выполняем действие
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
                                        if (arrSymbols[i] == "*")
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
        }

        /// <summary>
        /// Кулькулятор на N чисел в строку (не правильная последовательность)
        /// </summary>
        private static void Calculater7()
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
                if (i == 0)
                {
                    //Проверка на правильность начала примера
                    if (primer[i] == '*' || primer[i] == '/')
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

        /// <summary>
        /// Кулькулятор на N чисел, но с правильной последовательностью (ухх, ну и жесть (так думал, пока не начал))
        /// </summary>
        private static void Calculater6()
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
                    Console.Write("Введите число " + (i + 1) + ": ");
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
                                    if (arrSymbols[i] == "*")
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

        /// <summary>
        /// Калькулятор на 5 чисел через switch (с функциями) (без действий арифметики) (Количество строк: 68)
        /// </summary>
        private static void Calculater5()
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

        /// <summary>
        /// Калькулятор на 5 чисел через switch (без функции) (без действий арифметики) (Кол-во строк: 103)
        /// </summary>
        private static void Calculater4()
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

        /// <summary>
        /// Калькулятор на три числа (с правильной постановкой)
        /// </summary>
        private static void Calculater3()
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

        /// <summary>
        /// Калькулятор на N чисел, но решается все по порядку (без учёта арифметики)
        /// </summary>
        private static void Calculater2()
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

        /// <summary>
        /// Калькулятор на два числа через switch
        /// </summary>
        private static void Calculater1()
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
