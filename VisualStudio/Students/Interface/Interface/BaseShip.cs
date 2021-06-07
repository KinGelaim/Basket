using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

namespace Interface
{
    class BaseShip : ISpeedObject, IProductionObject
    {
        protected int speed;
        protected int cost;

        public BaseShip() { }

        public int GetSpeed()
        {
            return speed;
        }

        public int GetProductionCost()
        {
            return cost;
        }
    }
}
