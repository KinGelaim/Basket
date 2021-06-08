using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

namespace HW_HightSchool6
{
    class House
    {
        public string address;
        private int numberHouse;
        private int countHumans;

        public int CountHumans
        {
            get
            {
                return countHumans;
            }
            set
            {
                countHumans = value;
            }
        }

        public int GetNumberHouse()
        {
            return numberHouse;
        }

        public void SetNumberHouse(int value)
        {
            if(value > 0)
                numberHouse = value;
        }
    }
}
