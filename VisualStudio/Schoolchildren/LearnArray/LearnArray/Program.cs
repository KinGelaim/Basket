using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading;

namespace LearnArray
{
    class Program
    {
        static void Main(string[] args)
        {
            //Объявление массивов
            int[] arr;
            arr = new int[4];

            arr[0] = 12;
            arr[3] = -17;

            arr = new int[4] { 4, -7, 18, 0 };

            int[,] matrix = new int[10, 10];
            

            int[, , , ,] superMatrix = new int[10, 10, 10, 10, 10]; //Массив, который содержит массивы, которые содержат массивы ...

            //Строка это тоже массив
            string str = "asd";
            char[] arrCh = new char[3] { 'a', 's', 'd' };

            arrCh = "as".ToCharArray();
            Console.WriteLine(arrCh.Length);

            //Пробег по массивам
            for (int i = 0; i < str.Length; i++)
            {
                Console.WriteLine(str[i]);
            }

            //Жрём почти гиг памяти
            int countColumns = 20000;
            int countRows = 20000;
            int[,] bigArr = new int[countColumns, countRows];
            Thread.Sleep(2000);
            for (int i = 0; i < countColumns; i++)
            {
                for (int j = 0; j < countRows; j++)
                {
                    bigArr[i,j] = i;
                }
            }
            Thread.Sleep(1000);
            for (int i = 0; i < countColumns; i++)
            {
                for (int j = 0; j < countRows; j++)
                {
                    Console.WriteLine(bigArr[i,j]);
                }
            }
        }
    }
}
