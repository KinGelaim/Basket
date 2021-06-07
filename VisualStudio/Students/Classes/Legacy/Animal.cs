using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

namespace legacy
{
    class Animal
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

        public Animal() { }

        public Animal(string name, double weight, double height, double width, int countPaw, bool isTail = true)
        {
            this.name = name;
            this.weight = weight;
            this.height = height;
            this.width = width;
            CountPaw = countPaw;
            this.isTail = isTail;
        }

        public string PrintInformation(string strMain = "животном", string strSecond = "животного")
        {
            return "\n---------Информация о " + strMain + "---------" +
                "\nКличка " + strSecond + ": " + this.name +
                "\nВес " + strSecond + ": " + this.weight +
                "\nРост " + strSecond + ": " + this.height +
                "\nШирина " + strSecond + ": " + this.width +
                "\nКоличество лапок: " + CountPaw +
                "\nНаличие хвоста: " + (isTail ? "Присутствует" : "Отсутствует");
        }
    }
}
