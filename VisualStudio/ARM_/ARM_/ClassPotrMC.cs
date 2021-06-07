using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace ARM
{
    class ClassPotrMC
    {
        public string codeMC3 { get; set; }
        public string codeMC5 { get; set; }
        public string nameMC { get; set; }
        public string fullNameMC { get; set; }
        public string codeFactoryPost { get; set; }
        public string nameFactoryPost { get; set; }
        public string innFactoryPost { get; set; }
        public string codeEdIzm { get; set; }
        public string nameEdIzm { get; set; }
        public string cenaMC { get; set; }
        public string fondMC { get; set; }

        public List<string> vYearPotrMC = new List<string>();
        public List<string> vOnePotrMC = new List<string>();
        public List<string> vTwoPotrMC = new List<string>();
        public List<string> vThreePotrMC = new List<string>();
        public List<string> vThourPotrMC = new List<string>();

        public List<string> koefShot = new List<string>();

        public List<string> liveMC = new List<string>();

        public List<ClassPlIspKontrol> plIspList = new List<ClassPlIspKontrol>();
    }
}
