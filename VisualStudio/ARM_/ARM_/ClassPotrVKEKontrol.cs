using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace ARM
{
    public class ClassPotrVKEKontrol
    {
        public int id { get; set; }
        public string codeElementPotrVKEKontrol { get; set; }
        public string pictureElementPotrVKEKontrol { get; set; }
        public string indexElementPotrVKEKontrol { get; set; }
        public string nameElementPotrVKEKontrol { get; set; }
        public string codePoligonPotrVKEKontrol { get; set; }
        public string namePoligonPotrVKEKontrol { get; set; }
        public string codePoligonFactoryPotrVKEKontrol { get; set; }
        public string namePoligonFactoryPotrVKEKontrol { get; set; }
        public string codeFactoryPostPotrVKEKontrol { get; set; }
        public string nameFactoryPostPotrVKEKontrol { get; set; }
        public string innFactoryPostPotrVKEKontrol { get; set; }
        public string codeEdIzmPotrVKEKontrol { get; set; }
        public string nameEdIzmPotrVKEKontrol { get; set; }
        public double vYearPotrVKEKontrol { get; set; }
        public double vOnePotrVKEKontrol { get; set; }
        public double vTwoPotrVKEKontrol { get; set; }
        public double vThrePotrVKEKontrol { get; set; }
        public double vThourPotrVKEKontrol { get; set; }
        public int yearPotrVKEKontrol { get; set; }

        public List<ClassPlIspKontrol> ispEl = new List<ClassPlIspKontrol>();

        public List<double> countVK = new List<double>();
    }
}
