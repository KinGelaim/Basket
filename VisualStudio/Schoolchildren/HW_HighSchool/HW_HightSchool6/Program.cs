using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

namespace HW_HightSchool6
{
    class Program
    {
        static void Main(string[] args)
        {
            //Задание 1
            int[] arr = new int[0];
            while (true)
            {
                Console.Write("Введите число: ");
                string str = Console.ReadLine();
                if(str == "end")
                {
                    break;
                }
                int pr = Convert.ToInt32(str);
                int[] prArr = new int[arr.Length + 1];
                for (int i = 0; i < arr.Length; i++)
                {
                    prArr[i] = arr[i];
                }
                prArr[prArr.Length - 1] = pr;
                arr = prArr;
            }
            Console.WriteLine("Ввод завершен! Полученный массив данных: ");
            int maxArr = arr[0];
            int minArr = arr[0];
            int summArr = 0;
            for (int i = 0; i < arr.Length; i++)
            {
                Console.Write(arr[i] + " ");
                if (maxArr < arr[i])
                    maxArr = arr[i];
                if (minArr > arr[i])
                    minArr = arr[i];
                summArr += arr[i];
            }
            Console.WriteLine("\nНаибольшее значение: " + maxArr);
            Console.WriteLine("Наименьшее значение: " + minArr);
            Console.WriteLine("Сумма набора данных: " + summArr);
            int srArr = summArr / arr.Length;
            Console.WriteLine("Среднее значение: " + srArr);
            Console.WriteLine("Сортировка по возрастанию:");
            for (int i = 0; i < arr.Length; i++)
            {
                for (int j = 0; j < arr.Length - 1; j++)
                {
                    if(arr[j] > arr[j+1])
                    {
                        int pr = arr[j];
                        arr[j] = arr[j + 1];
                        arr[j + 1] = pr;
                    }
                }
            }
            for (int i = 0; i < arr.Length; i++)
            {
                Console.Write(arr[i] + " ");
            }
            Console.WriteLine("\nСортировка по убыванию:");
            for (int i = 0; i < arr.Length; i++)
            {
                for (int j = 0; j < arr.Length - 1; j++)
                {
                    if (arr[j] < arr[j + 1])
                    {
                        int pr = arr[j];
                        arr[j] = arr[j + 1];
                        arr[j + 1] = pr;
                    }
                }
            }
            for (int i = 0; i < arr.Length; i++)
            {
                Console.Write(arr[i] + " ");
            }
            Console.WriteLine("\nСтатистика:");
            //Массив для хранения элементов, чтобы не повторялись
            int[] oldArr = new int[0];
            for (int i = 0; i < arr.Length; i++)
            {
                //Проверка на повторение
                bool proverka = true;
                for (int j = 0; j < oldArr.Length; j++)
                {
                    if (oldArr[j] == arr[i])
                    {
                        proverka = false;
                    }
                }

                if (proverka)
                {
                    int n = 0;
                    for (int j = 0; j < arr.Length; j++)
                    {
                        if (arr[i] == arr[j])
                        {
                            n++;
                        }
                    }
                    Console.WriteLine("Кол-во числа " + arr[i] + " в массиве равно " + n);

                    //Добавление текущего элемента в массив, что его статистика уже посчитана
                    int[] prArr = new int[oldArr.Length + 1];
                    for (int j = 0; j < oldArr.Length; j++)
                    {
                        prArr[j] = oldArr[j];
                    }
                    prArr[prArr.Length - 1] = arr[i];
                    oldArr = prArr;
                }
            }

            //Задание 2
            House house = new House();
            house.address = "улица Пушкина";

            //house.numberHouse = -19;

            house.SetNumberHouse(19);
            house.SetNumberHouse(-19);

            Console.WriteLine("Номер дома: " + house.GetNumberHouse());

            house.CountHumans = 100;

            Console.WriteLine("В этом доме живут: " + house.CountHumans);



            Student student = new Student("Александр", 19);
            student.Age = 18;
            student.house = house;
            Console.WriteLine("Информация о студенте\n" + student.GetInfo());
        }
    }
}
