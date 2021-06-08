using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

namespace HW_HightSchool5
{
    class Cat
    {
        //Поля
        public string name;
        public int countMouse = 0;
        public string color;
        public double year;

        //Конструтор
        public Cat()
        {

        }

        public Cat(string name, int countMouse, string color, double year)
        {
            this.name = name;
            this.countMouse = countMouse;
            this.color = color;
            this.year = year;
        }

        //Методы
        public void PrintInfo()
        {
            Console.WriteLine("Этого котика зовут: " + name +
                "\nЕго возраст: " + year +
                "\nОн цвета: " + color +
                "\nОн поймал мышек: " + countMouse);
        }

        public string GetInfo()
        {
            return "Этого котика зовут: " + name +
                "\nЕго возраст: " + year +
                "\nОн цвета: " + color +
                "\nОн поймал мышек: " + countMouse;
        }

        public void PrintMouseInfo()
        {
            Console.WriteLine("Котик " + name + " поймал " + countMouse + " мышей!");
        }

        public void KinutTapkom()
        {
            Console.WriteLine("Вы кинули тапком в котика!");
            Random rand = new Random();
            if (rand.Next(100) > 75)
            {
                Console.WriteLine("В котика прилетел тапок >.<");
            }
            else
            {
                Console.WriteLine("Котик увернулся ^_^");
            }
        }
    }
}
