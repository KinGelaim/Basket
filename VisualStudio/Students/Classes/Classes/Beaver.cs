using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

namespace Classes
{
    //Бобер
    class Beaver
    {
        public string name { get; set; }       //Кличка
        public double weight { get; set; }     //Вес
        public double height { get; set; }     //Рост
        public double width { get; set; }      //Ширина
        private int countPaw { get; set; }      //Количество лап (делаем приватным и реализуем свойтсво для доступа из вне)
        public bool isTail { get; set; }       //Наличие хвоста
        public int countBuild { get; set; }    //Количество построек (хаты)

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

        public Beaver() { }

        public Beaver(string name, double weight, double height, double width, int countPaw, bool isTail = true, int countBuild = 0)
        {
            this.name = name;
            this.weight = weight;
            this.height = height;
            this.width = width;
            CountPaw = countPaw;
            this.isTail = isTail;
            this.countBuild = countBuild;
        }

        public string PrintInformation()
        {
            return "\n---------Информация о бобре---------" +
                "\nКличка бобра: " + this.name +
                "\nВес бобра: " + this.weight +
                "\nРост бобра: " + this.height +
                "\nШирина бобра: " + this.width +
                "\nКоличество лапок: " + CountPaw +
                "\nНаличие хвоста: " + (isTail ? "Присутствует" : "Отсутствует") +
                "\nКоличество построек: " + countBuild;
        }
    }
}
