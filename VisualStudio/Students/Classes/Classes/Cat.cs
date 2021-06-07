using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

namespace Classes
{
    //Кот
    class Cat
    {
        public string name { get; set; }       //Кличка
        public double weight { get; set; }     //Вес
        public double height { get; set; }     //Рост
        public double width { get; set; }      //Ширина
        private int countPaw { get; set; }      //Количество лап (делаем приватным и реализуем свойтсво для доступа из вне)
        public bool isTail { get; set; }       //Наличие хвоста
        public double lvlMeow { get; set; }    //Уровень громкости мяуканья

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

        public Cat() { }

        public Cat(string name, double weight, double height, double width, int countPaw, bool isTail = true, int lvlMeow = 0)
        {
            this.name = name;
            this.weight = weight;
            this.height = height;
            this.width = width;
            CountPaw = countPaw;
            this.isTail = isTail;
            this.lvlMeow = lvlMeow;
        }

        public string PrintInformation()
        {
            return "\n---------Информация о коте---------" +
                "\nКличка кота: " + this.name +
                "\nВес кота: " + this.weight +
                "\nРост кота: " + this.height +
                "\nШирина кота: " + this.width +
                "\nКоличество лапок: " + CountPaw +
                "\nНаличие хвоста: " + (isTail ? "Присутствует" : "Отсутствует") +
                "\nУровень громкости мяуканья: " + lvlMeow;
        }
    }
}
