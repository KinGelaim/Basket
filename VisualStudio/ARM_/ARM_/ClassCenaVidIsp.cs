using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace ARM
{
    public class ClassCenaVidIsp
    {
        public int id { get; set; }
        public string poligonCenaVidIsp { get; set; }
        public string codeElementCenaVidIsp { get; set; }
        public string pictureElementCenaVidIsp { get; set; }
        public string indexElementCenaVidIsp { get; set; }
        public string nameElementCenaVidIsp { get; set; }

        public List<ClassCenaVidIspCombo> comboListCenaVidIsp = new List<ClassCenaVidIspCombo>();
    }
}
