using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

namespace HW_HightSchool6
{
    class Student
    {
        public string Name { get; set; }
        private int age;

        public int Age
        {
            private get
            {
                return age;
            }
            set
            {
                if (value > 0)
                    age = value;
            }
        }

        public House house { get; set; }

        public Student()
        {

        }

        public Student(string name, int age)
        {
            Name = name;
            Age = age;
        }

        public string GetInfo()
        {
            return "Имя: " + Name + "\nВозраст: " + Age;
        }
    }
}
