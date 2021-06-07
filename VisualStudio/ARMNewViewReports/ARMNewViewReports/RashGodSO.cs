using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

namespace ARMNewViewReports
{
    class RashGodSO : IElement
    {
        public string kodel { get; set; }   //Код элемента
        public string snhertel { get; set; }  //Чертёж элемента
        public string snindizel { get; set; } //Индекс элемента
        public string snnaimel { get; set; }  //Наименование элемента
        public string kodil { get; set; }   //Код элемента
        public string snhertil { get; set; }  //Чертёж элемента
        public string snindizil { get; set; } //Индекс элемента
        public string snnaimil { get; set; }  //Наименование элемента
        public string kodotr { get; set; }  //Код завода
        public string nzavotr { get; set; }  //Наименованеи завода
        public string pol { get; set; }
        public string polz { get; set; }
        public string vispg { get; set; }
        public string visp1 { get; set; }
        public string visp2 { get; set; }
        public string visp3 { get; set; }
        public string visp4 { get; set; }
        public string qptg1 { get; set; }
        public string qptg { get; set; }
        public string qpt1 { get; set; }
        public string qpt2 { get; set; }
        public string qpt3 { get; set; }
        public string qpt4 { get; set; }
        public string kodpost { get; set; }  //Код завода поставщика
        public string nzavpost { get; set; }  //Наименованеи завода
        public string innpost { get; set; } //ИНН поставщика
        public string ost { get; set; }
        public string kodeiz { get; set; }  //Код ед изм элемента
        public string naimeiz { get; set; }
        public string naik { get; set; }
    }
}
