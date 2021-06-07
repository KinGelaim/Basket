using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

namespace Ant_Search_Algorithm
{
    //Класс для описания узлов ( от узла к узлу передвигаются муравьи)
    public class City
    {
        public int x { get; set; }
        public int y { get; set; }
        public int width { get; set; }
        public int height { get; set; }
        public bool firstCity { get; set; }
        public bool endCity { get; set; }
        public int numberOfCity { get; set; }
        public List<Road> roadInNextCity { get; set; }
        public static List<City> badCityList { get; set; }

        public City(int x, int y, int width, int height)
        {
            this.x = x;
            this.y = y;
            this.width = width;
            this.height = height;
            roadInNextCity = new List<Road>();
        }

        public static void CreateBadCityList(List<City> cityList, City endCity)
        {
            badCityList = new List<City>();
            foreach (City city in cityList)
                if (city.roadInNextCity.Count == 0 && city != endCity)
                    badCityList.Add(city);
        }
    }
}
