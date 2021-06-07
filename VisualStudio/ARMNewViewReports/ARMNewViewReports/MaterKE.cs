namespace ARMNewViewReports
{
    class MaterKE
    {
        public string kodm { get; set; }    // Код материала
        public string artic { get; set; }   // Гост, ост и тд
        public string namem { get; set; }   // Наименование материала
        public string razdel { get; set; }  // Влияет на расчёт потребности
        public string kodeiz { get; set; }  // Код ед. изм
        public string naimeiz { get; set; } // Наименование ед. изм
        public string naik { get; set; }    // Краткое наименование ед. изм
        public string qpmg { get; set; }
        public string qpm1 { get; set; }
        public string qpm2 { get; set; }
        public string qpm3 { get; set; }
        public string qpm4 { get; set; }
        public string kodotr { get; set; }  // Поставщик
        public string nzav { get; set; }
        public string inn { get; set; }
    }
}
