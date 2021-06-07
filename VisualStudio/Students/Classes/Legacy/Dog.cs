using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

namespace legacy
{
    class Dog : Animal
    {
        public Dog() { }

        public Dog(string name, double weight, double height, double width, int countPaw, bool isTail = true, int countBuild = 0)
            : base(name, weight, height, width, countPaw, isTail)
        {

        }

        public string PrintInformation()
        {
            return base.PrintInformation("собаке", "собаки");
        }
    }
}
