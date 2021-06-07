using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

namespace ARMNewViewReports
{
    class MesplKE
    {
        public string kodel { get; set; }   //Код элемента
        public string snhert { get; set; }  //Чертёж элемента
        public string snindiz { get; set; } //Индекс элемента
        public string snnaim { get; set; }  //Наименование элемента
        public string kodeiz { get; set; }  //Код ед. измерения
        public string naimeiz { get; set; }
        public string naik { get; set; }
        public string qpotr { get; set; }   //Код вида испытаний
        public string mesac { get; set; }
        public string godr { get; set; }
        public string priz { get; set; }

        public List<MesplPlanIsp> soList = new List<MesplPlanIsp>();
    }
}
