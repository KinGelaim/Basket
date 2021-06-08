using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

namespace AllProgramming
{
    class City
    {
        //Поля
        public string name;             //Наименование города
        public string description;      //Описание города
        public double s;                //Площадь города
        public int[] countHuman;        //Количество людей по годам
        public int[] yearCountHuman;    //Годы

        //Конструкторы
        public City()
        {
            countHuman = new int[0];
            yearCountHuman = new int[0];
        }

        public City(string name)
        {
            this.name = name;
            countHuman = new int[0];
            yearCountHuman = new int[0];
        }

        //Методы
        public void PrintInfo()
        {
            Console.WriteLine("----Описание города----");
            Console.WriteLine("Название города: " + name);
            if (description != null)
            {
                Console.WriteLine("Описание города: " + description);
            }
            string infoCountHuman = TakeCountHumanInfo();
            Console.WriteLine(infoCountHuman);
            Console.WriteLine();
        }

        public void AddCountHuman(int year, int count)
        {
            int[] prCount = new int[countHuman.Length + 1];
            int[] prYear = new int[yearCountHuman.Length + 1];
            for (int i = 0; i < countHuman.Length; i++)
            {
                prCount[i] = countHuman[i];
                prYear[i] = yearCountHuman[i];
            }
            prCount[prCount.Length - 1] = count;
            prYear[prYear.Length - 1] = year;

            countHuman = prCount;
            yearCountHuman = prYear;
        }

        public string TakeCountHumanInfo()
        {
            string str = "";
            if(countHuman.Length > 0)
            {
                str = "Информация о численности населения:";
            }
            else
            {
                str = "Информация о численности населения отсутствует!";
            }
            for (int i = 0; i < countHuman.Length; i++)
            {
                str += "\nГод: " + yearCountHuman[i] + "\tЧисленость: " + countHuman[i];
            }
            return str;
        }
    }
}
