using MainModule;
using System;


namespace UnitTestExample
{
    class Program
    {
        static void Main(string[] args)
        {
            Console.WriteLine("Добро пожаловать в программу!");

            int[] arr1 = new int[3];
            arr1[0] = 1;
            arr1[1] = 2;
            arr1[2] = -2;

            int[] arr2 = new int[] { -2, 0, 40 };

            int[] arr3 = { 3, 4, 5, -1 };

            Console.WriteLine("Массив:");
            for (int i = 0; i < arr1.Length; i++)
                Console.Write(arr1[i] + " ");

            Console.WriteLine("\nМаксимальное значение: " + ArrayManager.FindMax(arr1));

            Console.WriteLine("Массив:");
            for (int i = 0; i < arr3.Length; i++)
                Console.Write(arr3[i] + " ");

            Console.Write("\nСреднее значение: ");
            double average = ArrayManager.FindAverage(arr3);
            Console.WriteLine(average);
        }
    }
}