using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

namespace ARMNewViewReports
{
    class MesplPlanIsp
    {
        public string kodel { get; set; }   //Код элемента
        public string snhert { get; set; }  //Чертёж элемента
        public string snindiz { get; set; } //Индекс элемента
        public string snnaim { get; set; }  //Наименование элемента
        public string kodotr { get; set; }  //Код завода
        public string nzav { get; set; }  //Наименованеи завода
        public string inn { get; set; }  //ИНН завода
        public string shvid { get; set; }   //Код вида испытаний
        public string nvid { get; set; }   //Наименование вида испытаний
        public string npart { get; set; }   //Номер партии
        public string vist { get; set; }    //Количество выстрелов
        public string dpost { get; set; }    //Дата поставки
        public string preg1 { get; set; }   //Код преграды 1
        public string preg2 { get; set; }   //Код преграды 2
        public string npreg1 { get; set; }
        public string npreg2 { get; set; }
        public string shsis { get; set; }   //Код мат части (берётся из форматок)
        public string nsis { get; set; }
        public string shstv { get; set; }
        public string nstv { get; set; }
        public string shstd { get; set; }
        public string nstd { get; set; }
        public string mesac { get; set; }    //Месяц
        public string godr { get; set; }    //Год
        public string prim { get; set; }    //Примечание

        //Для комплектации
        public string kodeiz { get; set; }
        public string naimeiz { get; set; }
        public string naik { get; set; }

        public List<MesplPlanIsp> agList = new List<MesplPlanIsp>();
    }
}
