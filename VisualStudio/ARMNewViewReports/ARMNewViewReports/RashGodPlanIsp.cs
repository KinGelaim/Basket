using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

namespace ARMNewViewReports
{
    class RashGodPlanIsp : IElement
    {
        public string numberPos { get; set; }   //Порядковый номер в плане
        public string kodel { get; set; }   //Код элемента
        public string snhert { get; set; }  //Чертёж элемента
        public string snindiz { get; set; } //Индекс элемента
        public string snnaim { get; set; }  //Наименование элемента
        public string kodotr { get; set; }  //Код завода
        public string nzav { get; set; }  //Наименованеи завода
        public string inn { get; set; }  //ИНН завода
        public string shvid { get; set; }   //Код вида испытаний
        public string nvid { get; set; }   //Наименование вида испытаний
        public string pol { get; set; }
        public string npol { get; set; }
        public string kodeiz { get; set; } 
        public string qolg { get; set; }
        public string qol1 { get; set; }
        public string qol2 { get; set; }
        public string qol3 { get; set; }
        public string qol4 { get; set; }
        public string shsis { get; set; }
        public string nsis { get; set; }
        public string shsis2 { get; set; }
        public string nsis2 { get; set; }
        public string shsis3 { get; set; }
        public string nsis3 { get; set; }
        public string cena { get; set; }

        //Для комплектации
        public string naimeiz { get; set; }
        public string naik { get; set; }
        public string countOnOne { get; set; }

        public List<RashGodPlanIsp> agList = new List<RashGodPlanIsp>();
    }
}
