using System;


namespace LearningArrayAndClasses
{
    public sealed class ArrayExample
    {
        public void Start()
        {
            // Создание массивов разными способами
            int[] arr1;
            arr1 = new int[3];
            arr1[0] = 1;
            arr1[1] = -2;
            arr1[2] = 4;
            PrintArray(arr1);

            int[] arr2 = new int[2] { 1, 7 };
            PrintArray(arr2);
            int[] arr3 = { 4, -8, 9 };
            PrintArray(arr3);

            Console.WriteLine("Введите массив через , : ");
            string input = Console.ReadLine();
            string[] prArr = input.Split(',');
            int[] arr4 = new int[prArr.Length];
            for (int i = 0; i < prArr.Length; i++)
                arr4[i] = Convert.ToInt32(prArr[i]);
            PrintArray(arr4);

            // Расширение и сужение массивов
            Console.WriteLine("Вводите значение, пока не введёте 0");
            int[] arr = new int[0];
            while(true)
            {
                string k = Console.ReadLine();
                if (k == "0")
                    break;
                int pr = Convert.ToInt32(k);
                int[] prIntArr = new int[arr.Length + 1];
                for (int i = 0; i < arr.Length; i++)
                    prIntArr[i] = arr[i];
                prIntArr[prIntArr.Length - 1] = pr;
                arr = prIntArr;
            }

            Console.WriteLine("Вы ввели:");
            PrintArray(arr);

            Console.WriteLine("Введите порядковый номер числа, который хотите удалить: ");
            int indexDelete = Convert.ToInt32(Console.ReadLine()) - 1;
            int[] delArr = new int[arr.Length - 1];
            for (int i = 0; i < arr.Length; i++)
                if (i < indexDelete)
                    delArr[i] = arr[i];
                else if (i > indexDelete)
                    delArr[i - 1] = arr[i];
            PrintArray(delArr);

            Console.WriteLine("Введите число, которое хотите удалить: ");
            int numberDelete = Convert.ToInt32(Console.ReadLine());
            delArr = new int[0];
            for (int i = 0; i < arr.Length; i++)
            {
                if(arr[i] != numberDelete)
                {
                    int[] prIntArr = new int[delArr.Length + 1];
                    for (int j = 0; j < delArr.Length; j++)
                        prIntArr[j] = delArr[j];
                    prIntArr[prIntArr.Length - 1] = arr[i];
                    delArr = prIntArr;
                }
            }
            PrintArray(delArr);
        }

        private void PrintArray(int[] arr)
        {
            for(int i = 0; i < arr.Length; i++)
            {
                Console.Write(arr[i] + " ");
            }
            Console.WriteLine();
        }
    }
}