using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

namespace Interface
{
    class TransportShip : BaseShip
    {
        public TransportShip()
        {
            this.speed = 40;
            this.cost = 40000;
        }
    }
}
