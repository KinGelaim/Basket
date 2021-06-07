using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

namespace legacy
{
    class Program
    {
        static void Main(string[] args)
        {
            int prHeight = Console.WindowHeight;
            try
            {
                Console.WindowHeight = 40;
            }
            catch
            {
                Console.WindowHeight = prHeight;
            }
            //Классы все
            Console.WriteLine("------------------Наследие------------------");
            //Создание котеек
            Console.WriteLine("Котейки");
            Cat cat1 = new Cat();
            cat1.name = "Муська";       //Кличка
            cat1.weight = 3;            //Вес
            cat1.height = 0.5;          //Рост
            cat1.width = 0.1;           //Ширина
            cat1.CountPaw = 4;          //Количество лапок
            cat1.isTail = true;         //Наличие хвостика
            cat1.lvlMeow = 2;           //Уровень громкости мяуканья
            Cat cat2 = new Cat("Барсик", 5, 0.3, 0.1, 4, true, 7);
            Cat cat3 = new Cat("Шрам", 10, 1, 0.3, 3, false, 8);
            Console.WriteLine(cat1.PrintInformation());
            Console.WriteLine(cat2.PrintInformation());
            Console.WriteLine(cat3.PrintInformation());
            Console.WriteLine();
            Console.ReadKey();

            //Создание бобров
            Console.WriteLine("Бобры");
            Beaver beaver1 = new Beaver();
            beaver1.name = "Бобрик 1";     //Кличка
            beaver1.weight = 7;            //Вес
            beaver1.height = 0.5;          //Рост
            beaver1.width = 0.2;           //Ширина
            beaver1.CountPaw = 4;          //Количество лапок
            beaver1.isTail = true;         //Наличие хвостика
            beaver1.countBuild = 7;        //Количество построек
            Beaver beaver2 = new Beaver("Бобрик 2", 2, 0.3, 0.1, 4, true, 1);
            Beaver beaver3 = new Beaver("Бобрик 3", 7, 1, 0.4, 3, true, 21);

            Console.WriteLine(beaver1.PrintInformation());
            Console.WriteLine(beaver2.PrintInformation());
            Console.WriteLine(beaver3.PrintInformation());
            Console.WriteLine();
            Console.ReadKey();

            //Создание собак
            Console.WriteLine("Собачки");
            Dog dog1 = new Dog();
            dog1.name = "Бобик";     //Кличка
            dog1.weight = 3;            //Вес
            dog1.height = 0.5;          //Рост
            dog1.width = 0.3;           //Ширина
            dog1.CountPaw = 4;          //Количество лапок
            dog1.isTail = true;         //Наличие хвостика
            Dog dog2 = new Dog("Шарик", 5, 0.4, 0.3, 3, true);
            Dog dog3 = new Dog("Голубой щенок", 9, 2, 0.3, 4, true);

            Console.WriteLine(dog1.PrintInformation());
            Console.WriteLine(dog2.PrintInformation());
            Console.WriteLine(dog3.PrintInformation());
            Console.WriteLine();
            Console.ReadKey();
        }
    }
}
