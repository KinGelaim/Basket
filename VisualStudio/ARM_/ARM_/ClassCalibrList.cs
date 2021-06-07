using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

namespace ARM
{
    class ClassCalibrList
    {
        public int posInRows { get; set; }
        public int posInCollumns { get; set; }
        public string codeCalibr { get; set; }

        public List<ClassElements> keList = new List<ClassElements>();

        public static List<string> calibrList = new List<string>();
    }
}
