using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace ARM
{
    class ClassPotrVBP
    {
        public string codeBP { get; set; }
        public string sizeBP { get; set; }
        public double vesBP { get; set; }

        public List<string> liveBP = new List<string>();

        public List<ClassPlIspKontrol> plIspList = new List<ClassPlIspKontrol>();
    }
}
