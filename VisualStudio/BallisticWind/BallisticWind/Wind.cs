using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

namespace BallisticWind
{
    class Wind
    {
        public string D { get; set; }
        public string G { get; set; }
        public string V { get; set; }
        public string GE { get; set; }
        public bool isError  //Для окраски строчки, в случае, если шарик начинает падать
        {
            get;
            set;
        }
    }
}
