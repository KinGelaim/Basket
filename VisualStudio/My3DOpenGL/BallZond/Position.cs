using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

namespace BallZond
{
    class Position
    {
        public double x { get; set; }
        public double y { get; set; }
        public double z { get; set; }

        public Position(double x, double y, double z)
        {
            this.x = x;
            this.y = y;
            this.z = z;
        }
    }
}
