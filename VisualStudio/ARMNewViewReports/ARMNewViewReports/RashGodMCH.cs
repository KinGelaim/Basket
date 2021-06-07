using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

namespace ARMNewViewReports
{
    class RashGodMCH
    {
        public string kodel { get; set; }   //Код элемента
        public string shvid { get; set; }   //Вид испытания
        public string kodotr { get; set; }  //Код завода заказчика (или поставщика? :D)
        public string pol { get; set; }     //Полигон
        public string vispg { get; set; }   //Кол-во испытаний в год
        public string visp1 { get; set; }   //1 квартал
        public string visp2 { get; set; }   //2 квартал
        public string visp3 { get; set; }   //3 квартал
        public string visp4 { get; set; }   //4 квартал
        public string shsis { get; set; }   //Трёх значный код системы
        public string shstv { get; set; }   //Трёх значный код ствола
        public string shstd { get; set; }   //Трёх значный код стенда
        public string potsisg { get; set; } //Потребность в системе годовая
        public string potsis1 { get; set; } //Потребность в системе за 1 квартал
        public string potsis2 { get; set; } //Потребность в системе за 2 квартал
        public string potsis3 { get; set; } //Потребность в системе за 3 квартал
        public string potsis4 { get; set; } //Потребность в системе за 4 квартал
        public string potstvg { get; set; } //Потребность в стволе годовая
        public string potstv1 { get; set; } //Потребность в стволе за 1 квартал
        public string potstv2 { get; set; } //Потребность в стволе за 2 квартал
        public string potstv3 { get; set; } //Потребность в стволе за 3 квартал
        public string potstv4 { get; set; } //Потребность в стволе за 4 квартал
        public string potstdg { get; set; } //Потребность в стенде годовая
        public string potstd1 { get; set; } //Потребность в стенде за 1 квартал
        public string potstd2 { get; set; } //Потребность в стенде за 2 квартал
        public string potstd3 { get; set; } //Потребность в стенде за 3 квартал
        public string potstd4 { get; set; } //Потребность в стенде за 4 квартал
        public string rp { get; set; }
        public string kvp { get; set; }     //Количество выстрелов от партии
        public string klg { get; set; }     //КЛГ
        public string kvs { get; set; }     //КВС
        public string givsis { get; set; }  //Живучесть системы
        public string givstv { get; set; }  //Живучесть ствола
        public string givstd { get; set; }  //Живучесть стенда
        public string plprt { get; set; }   //Скорее всего нумерация из плана
        public string plprp { get; set; }   //-||-
    }
}
