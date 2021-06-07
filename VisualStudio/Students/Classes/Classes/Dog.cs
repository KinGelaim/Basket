using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

namespace Classes
{
    //Собака
    class Dog
    {
        public string name { get; set; }       //Кличка
        public double weight { get; set; }     //Вес
        public double height { get; set; }     //Рост
        public double width { get; set; }      //Ширина
        private int countPaw { get; set; }     //Количество лап (делаем приватным и реализуем свойтсво для доступа из вне)
        public bool isTail { get; set; }       //Наличие хвоста

        public int CountPaw
        {
            get
            {
                return countPaw;
            }
            set
            {
                if (countPaw >= 0)
                    countPaw = value;
            }
        }

        public Dog() { }

        public Dog(string name, double weight, double height, double width, int countPaw, bool isTail = true)
        {
            this.name = name;
            this.weight = weight;
            this.height = height;
            this.width = width;
            CountPaw = countPaw;
            this.isTail = isTail;
        }

        public string PrintInformation()
        {
            return "\n---------Информация о собаке---------" +
                "\nКличка собаки: " + this.name +
                "\nВес собаки: " + this.weight +
                "\nРост собаки: " + this.height +
                "\nШирина собаки: " + this.width +
                "\nКоличество лапок: " + CountPaw +
                "\nНаличие хвоста: " + (isTail ? "Присутствует" : "Отсутствует");
        }
    }
}
