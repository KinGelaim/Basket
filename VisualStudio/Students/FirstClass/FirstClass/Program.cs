using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

namespace FirstClass
{
    class Program
    {
        static void Main(string[] args)
        {
            Animal cat = new Animal();
            Animal dog = new Animal();

            cat.name = "Барсик";
            cat.type = "Кот";
            cat.Age = 3;

            dog.name = "Шарик";
            dog.type = "Собачка";
            dog.Age = 1;

            //Console.WriteLine("Животное зовут: " + cat.name);
            //Console.WriteLine(cat.PrintInfo());

            List<Animal> animalList = new List<Animal>();
            animalList.Add(cat);
            animalList.Add(dog);
            foreach (Animal animal in animalList)
                Console.WriteLine(animal.PrintInfo());

            /*
             * Программа для кого-нибудь питомника
             * Выводит список команд: список животных, добавить животное, удалить животное
             * 
             * */
            Console.WriteLine("------------------------------------------------------------");
            Console.WriteLine("Более менее рабочая программа!");
            Console.WriteLine("------------------------------------------------------------");
            Console.WriteLine("Добро пожаловать в программу для питомника!");
            animalList.Clear();
            PrintInfoMenu();
            while (true)
            {
                string command = Console.ReadLine();
                switch (command)
                {
                    case "list":
                        PrintAnimalList(animalList);
                        break;
                    case "cats":
                        PrintAnimalList(animalList, "cat");
                        break;
                    case "dogs":
                        PrintAnimalList(animalList, "dog");
                        break;
                    case "add":
                        Console.Write("Введите тип животного (cat/dog): ");
                        string type = Console.ReadLine();
                        Console.Write("Введите кличку животного: ");
                        string name = Console.ReadLine();
                        Console.Write("Введите возраст животного: ");
                        int age = Convert.ToInt32(Console.ReadLine());
                        Animal newAnimal = new Animal(name, type, age);
                        animalList.Add(newAnimal);
                        break;
                    case "delete":
                        PrintAnimalList(animalList);
                        Console.Write("Введите номер животного для удаления: ");
                        int strDelete = Convert.ToInt32(Console.ReadLine());
                        animalList.RemoveAt(strDelete - 1);
                        break;
                    case "help":
                        PrintInfoMenu();
                        return;
                    case "exit":
                        Console.Beep();
                        Console.ReadKey();
                        return;
                    default:
                        Console.WriteLine("Я не знаю такой команды!");
                        break;
                }
            }
        }

        static void PrintInfoMenu()
        {
            Console.WriteLine("Для вывода списка животных введите list\nДля вывода списка котов введите cats\nДля вывода списка собак введите dogs\nДля добавление нового животного введите add\nДля удаление животного из каталога введите delete\nДля вывода данного меню введите help\nДля выхода введите exit");
        }

        static void PrintAnimalList(List<Animal> animalList, string type = "")
        {
            int k = 1;
            foreach (Animal animal in animalList)
            {
                if (type == "" || type == animal.type)
                    Console.WriteLine("---------" + k + " ЗВЕРЕК---------\n" + animal.PrintInfo());
            }
        }
    }
}
