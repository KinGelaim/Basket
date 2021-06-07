using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

namespace BallisticWind
{
    class Result
    {
        public int N { get; set; }          //Порядковый номер
        public double H { get; set; }       //Высота
        public double WCR { get; set; }     //Скорость ветра
        public double NWCR { get; set; }    //Направление ветра
        public double HCT { get; set; }     //Высота бал. ветра
        public double WB { get; set; }      //Скорость бал. ветра
        public double NWB { get; set; }     //Направление бал. ветра
    }
}
