using System;
using System.Numerics;


namespace Functions
{
    public class Program
    {
        private static void Main(string[] args)
        {
            #region Обычные функции

            Summ(10, 21);
            double s = Summ(2.0, 4.0);
            Console.WriteLine("Сумма равна: {0}", s);
            //Console.WriteLine($"Сумма равна: {s}");

            #endregion

            #region Возврат нескольких значений (классы, структуры)

            Console.WriteLine("Вызываем функцию!");
            Point point = Summ();
            Console.WriteLine("Из функции получили:");
            Console.WriteLine("Число 1: " + point.X);
            Console.WriteLine("Число 2: " + point.Y);
            Console.WriteLine("Сумма: " + point.Summ);

            #endregion

            #region Возврат нескольких значений (модификаторы)

            double a;
            double b = 0;

            Console.WriteLine("Вызываем функцию!");
            double k = Summ(out a, ref b);

            Console.WriteLine("Из функции получили:");
            Console.WriteLine("Число 1: " + a);
            Console.WriteLine("Число 2: " + b);
            Console.WriteLine("Сумма: " + k);

            #endregion

            #region Факториал (время)

            Console.WriteLine("Число факториал:");
            long f = Convert.ToInt32(Console.ReadLine());

            // Холостой
            System.Diagnostics.Stopwatch sw = new System.Diagnostics.Stopwatch();
            sw.Reset();
            sw.Start();
            long fFirst = Factorial(f);
            sw.Stop();
            TimeSpan d1 = sw.Elapsed;

            // Тест
            sw.Reset();
            sw.Start();
            long f1 = Factorial(f);
            sw.Stop();
            d1 = sw.Elapsed;

            // Холостой
            sw.Reset();
            sw.Start();
            fFirst = FactorialRec(f);
            sw.Stop();
            TimeSpan d2 = sw.Elapsed;

            // Тест
            sw.Reset();
            sw.Start();
            long f2 = FactorialRec(f);
            sw.Stop();
            d2 = sw.Elapsed;

            Console.WriteLine("Факториал равен: " + f1);
            Console.WriteLine("Затраченное время: " + d1);
            Console.WriteLine("Факториал через рекурсию равен: " + f2);
            Console.WriteLine("Затраченное время: " + d2);

            #endregion

            #region Факториал (память)

            // Тест
            long kbAtExecution = GC.GetTotalMemory(false);
            BigInteger f3 = FactorialMemory(f);
            long kbAfter1 = GC.GetTotalMemory(false);
            long kbAfter2 = GC.GetTotalMemory(true);

            Console.WriteLine("Факториал равен: " + f3);
            Console.WriteLine("Память в байтах до старта: " + kbAtExecution);
            Console.WriteLine("Память в байтах после функции: " + kbAfter1);
            Console.WriteLine("Память в байтах выделенная: " + (kbAfter1 - kbAtExecution));
            Console.WriteLine("Память в байтах после функции, после сборки: " + kbAfter2);
            Console.WriteLine("Память в байтах выделенная, после сборки: " + (kbAfter2 - kbAfter1));

            // Тест
            kbAtExecution = GC.GetTotalMemory(false);
            BigInteger f4 = FactorialRecMemory(f);
            kbAfter1 = GC.GetTotalMemory(false);
            kbAfter2 = GC.GetTotalMemory(true);
            Console.WriteLine("Факториал равен рекурсия: " + f4);
            Console.WriteLine("Память в байтах до старта: " + kbAtExecution);
            Console.WriteLine("Память в байтах после функции: " + kbAfter1);
            Console.WriteLine("Память в байтах выделенная: " + (kbAfter1 - kbAtExecution));
            Console.WriteLine("Память в байтах после функции, после сборки: " + kbAfter2);
            Console.WriteLine("Память в байтах выделенная, после сборки: " + (kbAfter2 - kbAfter1));

            #endregion

            #region Checked and unchecked (byte)

            Console.ReadKey();

            byte byt = 0;
            Console.WriteLine(byt.ToString());
            byt = 255;
            Console.WriteLine(byt.ToString());
            //byt = 256;




            int integer = 20;
            double d = Convert.ToDouble(integer);
            d = (double)integer;




            int i = 10;
            byt = Convert.ToByte(i);
            Console.WriteLine(byt.ToString());
            i = 401;
            //byt = Convert.ToByte(i);
            byt = (byte)i;
            Console.WriteLine(byt.ToString());

            #endregion

            #region Checked and unchecked (byte 2)

            try
            {
                byt = checked((byte)i);
                Console.WriteLine(byt.ToString());
            }
            catch(Exception ex)
            {
                Console.WriteLine("Error: " + ex.Message);
            }

            byt = unchecked((byte)i);
            Console.WriteLine(byt.ToString());

            #endregion

            #region int16, int32, int64

            string h = "16";

            short l1 = Convert.ToInt16(h);
            int l2 = Convert.ToInt32(h);
            long l3 = Convert.ToInt64(h);

            short maxShort = 32764;
            //short bigMaxShort = 32769;

            #endregion

            Console.ReadKey();
        }

        #region Methods

        private static void Summ(int a, int b)
        {
            Console.WriteLine("Сумма равна: " + (a + b));
        }

        private static double Summ(double a, double b)
        {
            return a + b;
        }

        private static Point Summ()
        {
            Console.WriteLine("Введите число 1:");
            double a = Convert.ToDouble(Console.ReadLine());
            Console.WriteLine("Введите число 2:");
            double b = Convert.ToDouble(Console.ReadLine());
            Point newPoint = new Point();
            newPoint.X = a;
            newPoint.Y = b;
            newPoint.Summ = a + b;
            return newPoint;
        }

        #region Спойлер

        private static double Summ(out double a, ref double b)
        {
            Console.WriteLine("Введите число 1:");
            a = Convert.ToDouble(Console.ReadLine());
            Console.WriteLine("Введите число 2:");
            b = Convert.ToDouble(Console.ReadLine());
            return a + b;
        }

        #endregion

        //[BenchMark]
        private static long Factorial(long f)
        {
            long res = f;
            while (f > 1)
            {
                res *= --f;
            }
            return res;
        }

        //[BenchMark]
        private static long FactorialRec(long f)
        {
            long res = f;
            if(f > 1)
            {
                res *= FactorialRec(f - 1);
            }
            return res;
        }

        //[BenchMark]
        private static BigInteger FactorialMemory(BigInteger f)
        {
            BigInteger res = f;
            while (f > 1)
            {
                res *= --f;
            }
            return res;
        }

        //[BenchMark]
        private static BigInteger FactorialRecMemory(BigInteger f)
        {
            BigInteger res = f;
            if (f > 1)
            {
                res *= FactorialRecMemory(f - 1);
            }
            return res;
        }
        

        #endregion
    }
}
