using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

namespace Ant_Search_Algorithm
{
    public class Road
    {
        public City bCity { get; set; }
        public City eCity { get; set; }
        public double length { get; set; }
        public double pheramon { get; set; }

        public Road() { }

        public Road(City bCity, City eCity)
        {
            this.bCity = bCity;
            this.eCity = eCity;
        }

        public Road(City bCity, City eCity, double length)
        {
            this.bCity = bCity;
            this.eCity = eCity;
            this.length = length;
            this.pheramon = 0.01;
        }
    }
}
