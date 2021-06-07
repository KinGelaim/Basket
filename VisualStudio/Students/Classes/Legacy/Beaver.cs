using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

namespace legacy
{
    class Beaver : Animal
    {
        public int countBuild { get; set; }

        public Beaver() { }

        public Beaver(string name, double weight, double height, double width, int countPaw, bool isTail = true, int countBuild = 0) : base(name, weight, height, width, countPaw, isTail)
        {
            this.countBuild = countBuild;
        }

        public string PrintInformation()
        {
            return base.PrintInformation("бобре", "бобра") +
                "\nКоличество построек: " + countBuild;
        }
    }
}
