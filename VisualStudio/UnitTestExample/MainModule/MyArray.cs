using System;


namespace MainModule
{
    public class MyArray
    {
        private int[] arr;

        public MyArray(int[] arr)
        {
            this.arr = arr;
        }

        public int FindMax()
        {
            if (arr == null)
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
    }
}
