using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

namespace Interface
{
    class BaseHouse : IProductionObject
    {
        protected int cost;

        public BaseHouse() { }

        public int GetProductionCost()
        {
            return cost;
        }
    }
}
