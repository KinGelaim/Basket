using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace ARM
{
    public class ClassVKMespl
    {
        public int id { get; set; }
        public int idFIMespl { get; set; }
        public string codeElementVKMespl { get; set; }
        public string pictureElementVKMespl { get; set; }
        public string indexElementVKMespl { get; set; }
        public string nameElementVKMespl { get; set; }
        public string codeVidIspVKMespl { get; set; }
        public string nameVidIspVKMespl { get; set; }
        public ClassVKMespl() { }

        public List<ClassVKMesplElements> listElements = new List<ClassVKMesplElements>(); 
    }
}
