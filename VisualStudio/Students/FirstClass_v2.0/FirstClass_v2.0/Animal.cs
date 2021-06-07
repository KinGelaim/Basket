using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

namespace FirstClass_v2._0
{
    public class Animal
    {
        //Поля
        public string name;                     //Кличка
        public string type;                     //Тип (кот, собачка)
        private double age;                     //Возраст
        public double length { get; set; }
        public string numberBox { get; set; }   //Клетка содержания

        //Конструкторы
        public Animal() { }

        public Animal(string n, string t, double a, /*double length,*/ string numberBox = "")
        {
            name = n;
            type = t;
            age = a;
            //this.length = length;
            this.numberBox = numberBox;
        }

        //Свойства
        public double Age
        {
            get
            {
                return age;
            }
            set
            {
                if (value > 0)
                    age = value;
            }
        }

        //Методы
        public string PrintInfo()
        {
            return type + " " + name + " " + age + " лет " + numberBox;
        }

        //Методы
        public string PrintAllInfo()
        {
            return "Тип животного: " + type + "\nКличка животного: " + name + "\nВозраст животного: " + age + "\nНомер коробки: " + numberBox;
        }
    }
}
