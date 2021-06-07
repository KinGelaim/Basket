using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace ARM
{
    public class ClassVKMesplElements
    {
        public int id { get; set; }
        public double countVKMespl { get; set; }
        public string codeEdIzmVKMespl { get; set; }
        public string nameEdIzmVKMespl { get; set; }
        public ClassElements el { get; set; }

        public List<ClassVKMespl> listElements = new List<ClassVKMespl>();
        public List<double> listElementsCount = new List<double>();
    }
}
