using System;


namespace MainModule
{
    public static class ArrayManager
    {
        public static int FindMax(int[] arr)
        {
            if(arr == null)
                throw new NullReferenceException("Массив не задан");

            if (arr.Length == 0)
                throw new Exception("Пустой массив!");

            int max = arr[0];

            for (int i = 0; i < arr.Length; i++)
            {
                if (arr[i] > max)
                    max = arr[i];
            }

            return max;
        }

        public static double FindAverage(int[] arr)
        {
            int sum = 0;

            for (int i = 0; i < arr.Length; i++)
            {
                sum += arr[i];
            }

            return (double)sum / arr.Length;
        }
    }
}
