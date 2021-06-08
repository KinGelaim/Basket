using System;


namespace ExampleT
{
    public class Program
    {
        private static void Main(string[] args)
        {
            #region Example

            int a = 10;
            Console.WriteLine("---------------------------");
            Console.WriteLine("Вывожу число: ");
            Console.WriteLine(a);
            Console.WriteLine("---------------------------");

            /*
             * 
             * Куча куча куча кода
             * 
             * 
             * */

            string b = "asd";
            Console.WriteLine("---------------------------");
            Console.WriteLine("Вывожу строку: ");
            Console.WriteLine(b);
            Console.WriteLine("---------------------------");

            /*
             * 
             * Ещё одна куча кода
             * 
             * */
            double d = 20.5;
            Console.WriteLine("---------------------------");
            Console.WriteLine("Вывожу double: ");
            Console.WriteLine(d);
            Console.WriteLine("---------------------------");

            // Куча повторяющегося кода - не удобно и не красиво!

            #endregion


            #region Возможное решение

            Print(a);
            Print(b);
            Print(d);

            // Снова дублирующийся код

            #endregion


            #region Хорошее решение

            PrintT(a);
            PrintT(b);
            PrintT(d);

            #endregion
        }


        #region Methods

        private static void Print(int a)
        {
            Console.WriteLine("---------------------------");
            Console.WriteLine("Вывожу число: ");
            Console.WriteLine(a);
            Console.WriteLine("---------------------------");
        }

        private static void Print(string a)
        {
            Console.WriteLine("---------------------------");
            Console.WriteLine("Вывожу строку: ");
            Console.WriteLine(a);
            Console.WriteLine("---------------------------");
        }

        private static void Print(double a)
        {
            Console.WriteLine("---------------------------");
            Console.WriteLine("Вывожу double: ");
            Console.WriteLine(a);
            Console.WriteLine("---------------------------");
        }

        #endregion


        #region ExampleT

        private static void PrintT<T>(T a)
        {
            Console.WriteLine("---------------------------");
            Console.WriteLine("Вывожу {0}: ", typeof(T));
            Console.WriteLine(a);
            Console.WriteLine("---------------------------");
        }

        #endregion


        #region ExampleWhereT

        private static void PrintWhereT<T>(T a, T b, T c)
            where T : class
        {

        }

        private static void PrintWhereT<T>(T a, T b)
            where T : A
        {

        }

        private static void PrintWhereUT<U, T>(U a, T b)
            where U : A
            where T : B
        {

        }

        #endregion
    }

    public class A
    {

    }

    public class B
    {

    }
}
