using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

namespace SecondOperators
{
    class Program
    {
        static void Main(string[] args)
        {
            Console.WriteLine("Это второй проект, но все в том же решении!");
            int[] arr1 = { 4, 7, 1 };
            int[] arr2;
            arr2 = new int[2] { 1, 2 };
            int[] arr3 = new int[3];
            arr3[0] = 1;
            arr3[1] = 2;
            arr3[2] = 3;
            //Двумерный массив
            int[,] arrDouble = new int[5,5] {   { 1, 2, 3, 4, 5 }, 
                                                { 7, 8, 9, 10, 11 }, 
                                                { 7, 5, 3, 1, 69 }, 
                                                { 47, 59, 68, 12, 32 }, 
                                                { 9, 5, 1, 4, 2 }
            };
            Console.WriteLine(arrDouble[0, 2]);
            //Трехмерный массив
            int[,,] arrThree = new int[3,3,3];
            
            //Foreach
            foreach (int k in arr1)
                Console.WriteLine("Значение: " + k);

            //Пузырьковая сортировка
            int[] sortArr = new int[100];
            Random rand = new Random();
            for (int i = 0; i < sortArr.Length; i++)
                sortArr[i] = rand.Next(100);
            Console.WriteLine("Массив до сортировки: ");
            PrintArr(sortArr);
            SortArr(sortArr);
            Console.WriteLine("Массив после сортировки: ");
            PrintArr(sortArr);
        }

        static void PrintArr(int[] arr)
        {
            foreach(int x in arr)
                Console.Write(x + " ");
            Console.WriteLine("");
        }

        static void SortArr(int[] arr)
        {
            for(int i = 0; i < arr.Length; i++)
                for(int j = 0; j < arr.Length; j++)
                    if(arr[i] < arr[j])
                    {
                        int tmp = arr[i];
                        arr[i] = arr[j];
                        arr[j] = tmp;
                    }
        }
    }
}