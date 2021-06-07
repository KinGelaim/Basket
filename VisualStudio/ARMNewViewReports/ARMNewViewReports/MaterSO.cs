namespace ARMNewViewReports
{
    class MaterSO
    {
        public string kodm { get; set; }    // Код материала
        public string artic { get; set; }   // Гост, ост и тд
        public string namem { get; set; }   // Наименование материала
        public string razdel { get; set; }  // Влияет на расчёт потребности

        public string kodel { get; set; }   //Код элемента
        public string snhertel { get; set; }  //Чертёж элемента
        public string snindizel { get; set; } //Индекс элемента
        public string snnaimel { get; set; }  //Наименование элемента

        public string kodotr { get; set; }  // Заказчик
        public string nzav { get; set; }
        public string inn { get; set; }

        // Потребность
        public string qpmg { get; set; }
        public string qpm1 { get; set; }
        public string qpm2 { get; set; }
        public string qpm3 { get; set; }
        public string qpm4 { get; set; }

        // Выстрелы
        public string vispg { get; set; }
        public string visp1 { get; set; }
        public string visp2 { get; set; }
        public string visp3 { get; set; }
        public string visp4 { get; set; }
    }
}
