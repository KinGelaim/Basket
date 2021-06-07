using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace ARM
{
    class ClassPKEMespl
    {
        public string codeEKElement { get; set; }
        public string pictureEKElement { get; set; }
        public string indexEKElement { get; set; }
        public string nameEKElement { get; set; }
        public string edIzmCode { get; set; }
        public string edIzmName { get; set; }
        public double potrKE { get; set; }
        public string sklad { get; set; }

        public List<ClassPlIspMespl> ispEl = new List<ClassPlIspMespl>();
    }
}
