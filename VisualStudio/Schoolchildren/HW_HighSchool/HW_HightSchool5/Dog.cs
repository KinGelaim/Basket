using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

namespace HW_HightSchool5
{
    class Dog
    {
        //Поля
        public string name;

        //Конструкторы
        public Dog()
        {

        }

        public Dog(string name)
        {
            this.name = name;
        }

        //Методы
        public int CallDog(List<Dog> dogList)
        {
            Console.WriteLine("Пёс " + name + " взывает к братьям!");
            int result = 0;
            for (int i = 0; i < dogList.Count; i++)
            {
                if (dogList[i] != this)
                {
                    if (dogList[i].AnswerTheCall())
                    {
                        result++;
                    }
                }
            }
            return result;
        }

        public bool AnswerTheCall()
        {
            Random rand = new Random();
            if (rand.Next(100) > 70)
            {
                return false;
            }
            Console.WriteLine("Пёс " + name + " отвечает!");
            return true;
        }
    }
}
