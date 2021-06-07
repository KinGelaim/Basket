using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

namespace Ant_Search_Algorithm
{
    public class SaveLoadClass
    {
        public List<City> cityList { get; set; }
        public List<Road> roadList { get; set; }

        public SaveLoadClass() { }

        public SaveLoadClass(List<City> cityList, List<Road> roadList)
        {
            this.cityList = cityList;
            this.roadList = roadList;
        }
    }
}
