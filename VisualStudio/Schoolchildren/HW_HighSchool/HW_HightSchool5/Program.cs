using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

namespace HW_HightSchool5
{
    class Program
    {
        static void Main(string[] args)
        {
            //Задание 1
            Console.WriteLine("Введите Ваш вопрос: ");
            Console.ReadLine();

            Random rand = new Random();
            int shans = rand.Next(100);
            if (shans <= 25)
            {
                Console.WriteLine("Конечно!");
            }
            else if (shans > 25 && shans <= 50)
            {
                Console.WriteLine("Увы, но нет :(");
            }
            else if (shans > 50 && shans <= 75)
            {
                Console.WriteLine("Возможно");
            }
            else if (shans > 75 && shans <= 100)
            {
                Console.WriteLine("Обратитесь позже!");
            }

            //Задание 2
            int countColl = 3;
            int countRow = 3;
            int[,] arr = new int[countRow, countColl];
            
            //Запрашиваем
            for (int i = 0; i < countRow; i++)
            {
                for (int j = 0; j < countColl; j++)
                {
                    Console.Write("Введите элемент (" + i + "," + j + "): ");
                    int z = Convert.ToInt32(Console.ReadLine());
                    arr[i, j] = z;
                }
            }

            //Выводим
            for (int i = 0; i < countRow; i++)
            {
                for (int j = 0; j < countColl; j++)
                {
                    Console.Write(arr[i, j] + " ");
                }
                Console.WriteLine();
            }

            //Задание 3
            Console.Write("Введите размер: ");
            int count = Convert.ToInt32(Console.ReadLine());
            for (int i = 0; i <= count; i++)
            {
                for (int j = 0; j <= i; j++)
                {
                    Console.Write(j + " ");
                }
                Console.WriteLine();
            }

            for (int i = count; i >= 0; i--)
            {
                for (int j = 0; j < i; j++)
                {
                    Console.Write(j + " ");
                }
                Console.WriteLine();
            }

            //Задание 4
            //Кошки
            Cat cat1 = new Cat();
            cat1.name = "Барсик";
            cat1.year = 1.4;
            cat1.color = "Чёрный";
            cat1.countMouse = 3;
            cat1.PrintInfo();

            Cat cat2 = new Cat("Пушок", 7, "Персиковый", 2.4);

            string info = cat2.GetInfo();

            cat2.KinutTapkom();

            Cat cat3 = new Cat("Мышелов", 27, "Белый", 1.7);
            cat3.PrintMouseInfo();

            Console.WriteLine();

            //Собаки
            List<Dog> dogList = new List<Dog>();

            Dog dog1 = new Dog();
            dog1.name = "Тузик";

            Dog dog2 = new Dog("Барбос");
            Dog dog3 = new Dog("Мухтар");
            Dog dog4 = new Dog("Лайка");
            Dog dog5 = new Dog("Пёс");
            Dog dog6 = new Dog("Щенок");
            Dog dog7 = new Dog("Псина");

            dogList.Add(dog1);
            dogList.Add(dog2);
            dogList.Add(dog3);
            dogList.Add(dog4);
            dogList.Add(dog5);
            dogList.Add(dog6);
            dogList.Add(dog7);

            int countAnswerDogs = dog1.CallDog(dogList);
            Console.WriteLine("Всего псов в округе: " + dogList.Count);
            Console.WriteLine("Из них отозвались: " + countAnswerDogs);
        }
    }
}
