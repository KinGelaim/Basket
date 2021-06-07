using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

namespace legacy
{
    class Cat : Animal
    {
        public int lvlMeow { get; set; }

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
            return base.PrintInformation("коте", "кота") +
                "\nУровень громкости мяуканья: " + lvlMeow;
        }
    }
}
