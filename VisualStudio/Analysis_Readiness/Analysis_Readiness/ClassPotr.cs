using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

namespace Analysis_Readiness
{
    public class ClassPotr
    {
        public int id { get; set; }
        public int id_spravka { get; set; }
        public string code_potr { get; set; }
        public string vid_potr { get; set; }
        public int count_potr { get; set; }
        public string description_potr { get; set; }

        //Согласование
        public bool is_sogl { get; set; }
        public string text_sogl { get; set; }
        public bool is_niisu { get; set; }
        public string text_niisu { get; set; }
        public bool is_prikaz { get; set; }
        public string text_prikaz { get; set; }

        //Ссылка на справку к которой относится потребность в данном комплектующем
        public int positionInSpravka;
        public int positionInPotr;

        public ClassPotr() { }
        public ClassPotr(int id, int id_spravka, string code_potr, string vid_potr, int count_potr, string description_potr = "", bool is_sogl = false, string text_sogl = "", bool is_niisu = false, string text_niisu = "", bool is_prikaz = false, string text_prikaz = "")
        {
            this.id = id;
            this.id_spravka = id_spravka;
            this.code_potr = code_potr;
            this.vid_potr = vid_potr;
            this.count_potr = count_potr;
            this.description_potr = description_potr;
            this.is_sogl = is_sogl;
            this.text_sogl = text_sogl;
            this.is_niisu = is_niisu;
            this.text_niisu = text_niisu;
            this.is_prikaz = is_prikaz;
            this.text_prikaz = text_prikaz;
        }
        public ClassPotr(string code_potr, string vid_potr, int count_potr, string description_potr = "")
        {
            this.code_potr = code_potr;
            this.vid_potr = vid_potr;
            this.count_potr = count_potr;
            this.description_potr = description_potr;
        }
        public ClassPotr(ClassPotr potr)
        {
            this.id = potr.id;
            this.id_spravka = potr.id_spravka;
            this.code_potr = potr.code_potr;
            this.vid_potr = potr.vid_potr;
            this.count_potr = potr.count_potr;
            this.description_potr = potr.description_potr;
            this.is_sogl = potr.is_sogl;
            this.text_sogl = potr.text_sogl;
            this.is_niisu = potr.is_niisu;
            this.text_niisu = potr.text_niisu;
            this.is_prikaz = potr.is_prikaz;
            this.text_prikaz = potr.text_prikaz;
        }
    }
}
