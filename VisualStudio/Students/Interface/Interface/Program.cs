using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

namespace Interface
{
    class Program
    {
        public struct Student : IComparable
        {
            public string Name { get; set; }
            public int Age { get; set; }

            public int CompareTo(object obj)
            {
                Student student = (Student)obj;

                if (this.Age > student.Age) return 1;   //
                if (this.Age < student.Age) return -1;  //

                return 0;   //Если свойства равны
            }
        }

        static void Main(string[] args)
        {
            //Небольшая проба пера интерфейсов
            Cat cat = new Cat();
            FightShip fightShip = new FightShip();
            Console.WriteLine("Скорость кота: " + Move(cat));
            Console.WriteLine("Скорость боевого корабля: " + Move(fightShip));

            Hotel hotel = new Hotel();
            TransportShip transportShip = new TransportShip();
            Console.WriteLine("Стоимость постройки отеля: " + Build(hotel));
            Console.WriteLine("Стоимость постройки транспортного корабля: " + Build(transportShip));

            //Пример использования интерфейса
            Console.WriteLine();
            Student student1 = new Student() { Name = "Миша", Age = 22 };
            Student student2 = new Student() { Name = "Вероника", Age = 19 };

            Student[] students = new Student[] { student1, student2 };

            Console.WriteLine("До сортировки:");
            foreach(Student student in students)
                Console.WriteLine("Имя: " + student.Name + "\tВозраст: " + student.Age);

            Array.Sort(students);

            Console.WriteLine("После сортировки (по возрасту):");
            foreach (Student student in students)
                Console.WriteLine("Имя: " + student.Name + "\tВозраст: " + student.Age);

            //Явная реализация интерфейса (много недостатков, но бывает такое)
            //Явная реализация интерфейса возможно только с уровнем доступа private, поэтому для его вызова необходимо преобразовывать к интерфейсу
            Console.WriteLine();
            TestClass test = new TestClass();
            Console.WriteLine(test.GetMenu());

            IWindow window = test;
            Console.WriteLine(window.GetMenu());

            IRestaurant restaurant = test;
            Console.WriteLine(restaurant.GetMenu());
        }

        static int Move(ISpeedObject subject)
        {
            return subject.GetSpeed();
        }

        static int Build(IProductionObject subject)
        {
            return subject.GetProductionCost();
        }
    }
}
