using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

namespace Analysis_Readiness
{
    class ClassSpravka
    {
        public int id { get; set; }
        public string code_spravka { get; set; }
        public string vid_spravka { get; set; }
        public string description_spravka { get; set; }
        public List<ClassPotr> listPotrInSpravka { get; set; }

        public ClassSpravka() { }

        public ClassSpravka(int id, string code_spravka, string vid_spravka, string description_spravka)
        {
            this.id = id;
            this.code_spravka = code_spravka;
            this.vid_spravka = vid_spravka;
            this.description_spravka = description_spravka;
            this.listPotrInSpravka = new List<ClassPotr>();
        }
    }
}
