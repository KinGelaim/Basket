using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

namespace ARMNewViewReports
{
    class RashGodPreg
    {
        //PREG.DBF
        public string preg { get; set; }    //3-х значный код преграды
        public string razm { get; set; }    //Размер преграды
        public string ves { get; set; }     //Вес 1 плиты (в кг)
        public string gipg { get; set; }    //Живучесть преграды
        public string qol { get; set; }     //Кол-во выстрелов
        public string qol1 { get; set; }    //Расчётная потребность в БП
        public string kodel { get; set; }   //Код элемента
        public string snhert { get; set; }  //Чертёж элемента
        public string snindiz { get; set; } //Индекс элемента
        public string snnaim { get; set; }  //Наименование элемента
        public string kodotr { get; set; }  //Код заказчика
        public string nzav { get; set; }
        public string inn { get; set; }
        public string shvid { get; set; }   //Вид испытания
        public string nvid { get; set; }
        public string shsis { get; set; }   //Система
        public string nsis { get; set; }
        public string pol { get; set; }     //Полигон
        public string npol { get; set; }
        //PREGR.DBF
        public string obem2 { get; set; }   //WTF???
        public string ves2 { get; set; }    //Вес в т. (ves)
        public string qol2 { get; set; }    //Округленная потребность
    }
}
