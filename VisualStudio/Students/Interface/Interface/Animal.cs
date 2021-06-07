using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

namespace Interface
{
    class Animal : ISpeedObject
    {
        protected int speed;

        public Animal() { }

        public int GetSpeed()
        {
            return speed;
        }
    }
}
