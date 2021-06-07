using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Windows.Forms;
using Excel = Microsoft.Office.Interop.Excel;
using System.IO;
using System.IO.Compression;

namespace ReportsOnGOZ
{
    public partial class Form1 : Form
    {
        string excelReader = "";
        string nameSignPath = "";

        public Form1()
        {
            InitializeComponent();

            button2.Enabled = false;
            button3.Enabled = false;
        }

        //Выбрать файл отчета (exel)
        private void button1_Click(object sender, EventArgs e)
        {
            OpenFileDialog opf = new OpenFileDialog();
            opf.Filter = "Все файлы (*.*)|*.*|Excel (*.XLS)|*.XLS|Excelx (*.XLSX)|*.XLSX";
            opf.ShowDialog();
            if (File.Exists(opf.FileName))
            {
                excelReader = opf.FileName;
                button2.Enabled = true;
            }
            progressBar1.Value = 0;
        }

        //Создаем xml файл на основе excel файла
        private void button2_Click(object sender, EventArgs e)
        {
            if (excelReader.Length > 0)
            {
                //Проверка на наличие директории
                if (Directory.Exists("Отчеты"))
                {
                    bool printProverka = true;
                    string numberContract = "";
                    Excel.Application excel = new Excel.Application();
                    Excel.Sheets excelSheets;
                    Excel.Worksheet excelWorkSheets;
                    excel.Visible = false;
                    string text = "<?xml version=" + return_str("1.0", false) + " encoding=" + return_str("utf-8", false) + "?>\n";
                    try
                    {
                        //Открытие документа
                        excel.Workbooks.Open(excelReader);
                        excelSheets = excel.Worksheets;
                        excelWorkSheets = (Excel.Worksheet)excelSheets.get_Item(1);
                        Excel.Range excelRange = excelWorkSheets.UsedRange;
                        //Проверяем версию Excel
                        if (Convert.ToString(excelRange.Cells[1, 1].Value2) == "Сведения отчета")
                        {
                            int rowsPositionBegin = Convert.ToInt32(numericUpDown1.Value);
                            int rowsPositionEnd = excelRange.Rows.Count;
                            if (numericUpDown2.Value >= numericUpDown1.Value)
                                rowsPositionEnd = Convert.ToInt32(numericUpDown2.Value);
                            text += "<ДанныеРаздельногоУчета" + " xmlns=" + return_str("http://mil.ru/discreteAccounting", false) + " ИННОрганизации=" + return_str(Convert.ToString(excelRange.Cells[rowsPositionBegin, 1].Value2), false) + " КППОрганизации="
                                + return_str(Convert.ToString(excelRange.Cells[rowsPositionBegin, 2].Value2), false) + " НаименованиеОрганизации=" + return_str(Convert.ToString(excelRange.Cells[rowsPositionBegin, 3].Value2), false)
                                + " ДатаФормирования=" + return_str(Convert.ToString(excelRange.Cells[rowsPositionBegin, 4].Value2), false) + " ГенераторОтчета="
                                + return_str(Convert.ToString(excelRange.Cells[rowsPositionBegin, 5].Value2), false) + ">\n";
                            progressBar1.Value = 0;
                            int k = Convert.ToInt32(87 / (rowsPositionEnd - rowsPositionBegin + 1));
                            for (int r = rowsPositionBegin; r <= rowsPositionEnd; r++)
                            {
                                text += "\t<Контракт ИГК=" + return_str(Convert.ToString(excelRange.Cells[r, 6].Value2), false) + " НомерОтдельногоСчета="
                                    + return_str(Convert.ToString(excelRange.Cells[r, 7].Value2), false) + " ДатаСоставленияОтчета="
                                    + return_str(Convert.ToString(excelRange.Cells[r, 8].Value2), false) + " НомерКонтракта="
                                    + return_str(Convert.ToString(excelRange.Cells[r, 9].Value2), false)
                                    + " ДатаЗаключенияКонтракта=" + return_str(Convert.ToString(excelRange.Cells[r, 10].Value2), false)
                                    + " ПлановаяДатаИсполнения=" + return_str(Convert.ToString(excelRange.Cells[r, 11].Value2), false) + ">\n";
                                text += "\t\t<ГруппаФинансированиеКонтракта ЦелевойОбъемФинансирования=" + return_str(Convert.ToString(excelRange.Cells[r, 12].Value2))
                                    + " СальдоОпераций=" + return_str(Convert.ToString(excelRange.Cells[r, 13].Value2))
                                    + " ПроцентВыполнения=" + return_str(Convert.ToString(excelRange.Cells[r, 14].Value2)) + ">\n";
                                text += "\t\t\t<ДенежныеСредстваЗаказчика ЦенаКонтракта=" + return_str(Convert.ToString(excelRange.Cells[r, 15].Value2))
                                    + " ПроцентВыполнения=" + return_str(Convert.ToString(excelRange.Cells[r, 16].Value2))
                                    + " СальдоОпераций=" + return_str(Convert.ToString(excelRange.Cells[r, 17].Value2))
                                    + " ВозвращеноЗаказчику=" + return_str(Convert.ToString(excelRange.Cells[r, 18].Value2))
                                    + " ВозвращеноЗаказчикуСобственныеСредства=" + return_str(Convert.ToString(excelRange.Cells[r, 19].Value2))
                                    + " ПолученоОтЗаказчика=" + return_str(Convert.ToString(excelRange.Cells[r, 20].Value2)) + "/>\n";
                                text += "\t\t\t<БанковскиеКредиты ПлановыйОбъемКредитования=" + return_str(Convert.ToString(excelRange.Cells[r, 21].Value2))
                                    + " ПроцентВыполнения=" + return_str(Convert.ToString(excelRange.Cells[r, 22].Value2))
                                    + " СальдоОпераций=" + return_str(Convert.ToString(excelRange.Cells[r, 23].Value2))
                                    + " ПогашеноТелаКредита=" + return_str(Convert.ToString(excelRange.Cells[r, 24].Value2))
                                    + " ПогашеноТелаКредитаСобственныеСредства=" + return_str(Convert.ToString(excelRange.Cells[r, 25].Value2))
                                    + " ПривлеченоКредитов=" + return_str(Convert.ToString(excelRange.Cells[r, 26].Value2)) + "/>\n";
                                text += "\t\t\t<ЗадолженностьПоПроцентамКредитов СальдоОпераций=" + return_str(Convert.ToString(excelRange.Cells[r, 27].Value2))
                                    + " ПогашеноПроцентов=" + return_str(Convert.ToString(excelRange.Cells[r, 28].Value2))
                                    + " ПогашеноПроцентовСобственныеСредства=" + return_str(Convert.ToString(excelRange.Cells[r, 29].Value2))
                                    + " НачисленоПроцентов=" + return_str(Convert.ToString(excelRange.Cells[r, 30].Value2)) + "/>\n";
                                text += "\t\t\t<ЗадолженностьПоставщикам СальдоОпераций=" + return_str(Convert.ToString(excelRange.Cells[r, 31].Value2))
                                    + " ОплаченоПоставщикам=" + return_str(Convert.ToString(excelRange.Cells[r, 32].Value2))
                                    + " ОплаченоПоставщикамСредстваДругихКонтрактов=" + return_str(Convert.ToString(excelRange.Cells[r, 33].Value2))
                                    + " ОплаченоПоставщикамСобственныеСредства=" + return_str(Convert.ToString(excelRange.Cells[r, 34].Value2))
                                    + " СуммарнаяЗадолженность=" + return_str(Convert.ToString(excelRange.Cells[r, 35].Value2)) + "/>\n";
                                text += "\t\t</ГруппаФинансированиеКонтракта>\n";
                                text += "\t\t<ГруппаРаспределениеРесурсовКонтракта СальдоОпераций=" + return_str(Convert.ToString(excelRange.Cells[r, 36].Value2)) + ">\n";
                                text += "\t\t\t<ГруппаДенежныеСредства СальдоОпераций=" + return_str(Convert.ToString(excelRange.Cells[r, 37].Value2))
                                    + " ДенежныеАктивы=" + return_str(Convert.ToString(excelRange.Cells[r, 38].Value2))
                                    + " ДенежныеАктивыСредстваДругихКонтрактов=" + return_str(Convert.ToString(excelRange.Cells[r, 39].Value2))
                                    + " ДенежныеАктивыСобственныеСредства=" + return_str(Convert.ToString(excelRange.Cells[r, 40].Value2))
                                    + " ИспользованиеРесурсов=" + return_str(Convert.ToString(excelRange.Cells[r, 41].Value2))
                                    + " ИспользованиеРесурсовДругиеКонтракты=" + return_str(Convert.ToString(excelRange.Cells[r, 42].Value2))
                                    + " ИспользованиеРесурсовСобственныеСредства=" + return_str(Convert.ToString(excelRange.Cells[r, 43].Value2)) + ">\n";
                                text += "\t\t\t\t<ДенежныеСредстваОтдельныйСчет СальдоОпераций=" + return_str(Convert.ToString(excelRange.Cells[r, 44].Value2))
                                    + " ЗачисленоИсполнениеКонтракта=" + return_str(Convert.ToString(excelRange.Cells[r, 45].Value2))
                                    + " ЗачисленоИное=" + return_str(Convert.ToString(excelRange.Cells[r, 46].Value2))
                                    + " СписаноИсполнениеКонтракта=" + return_str(Convert.ToString(excelRange.Cells[r, 47].Value2))
                                    + " СписаноДругиеКонтракты=" + return_str(Convert.ToString(excelRange.Cells[r, 48].Value2))
                                    + " СписаноРасходыОрганизации=" + return_str(Convert.ToString(excelRange.Cells[r, 49].Value2)) + "/>\n";
                                text += "\t\t\t\t<БанковскиеДепозиты СальдоОпераций=" + return_str(Convert.ToString(excelRange.Cells[r, 50].Value2))
                                    + " ПеречисленоНаДепозит=" + return_str(Convert.ToString(excelRange.Cells[r, 51].Value2))
                                    + " ВозвращеноСДепозита=" + return_str(Convert.ToString(excelRange.Cells[r, 52].Value2)) + "/>\n";
                                text += "\t\t\t\t<АвансыВыданные СальдоОпераций=" + return_str(Convert.ToString(excelRange.Cells[r, 53].Value2))
                                    + " АвансыИсполнениеКонтракта=" + return_str(Convert.ToString(excelRange.Cells[r, 54].Value2))
                                    + " АвансыСредстваДругихКонтрактов=" + return_str(Convert.ToString(excelRange.Cells[r, 55].Value2))
                                    + " АвансыСобственныеСредства=" + return_str(Convert.ToString(excelRange.Cells[r, 56].Value2))
                                    + " ЗачтеноАвансов=" + return_str(Convert.ToString(excelRange.Cells[r, 57].Value2))
                                    + " СписаноЗадолженностиКооперации=" + return_str(Convert.ToString(excelRange.Cells[r, 58].Value2)) + "/>\n";
                                text += "\t\t\t</ГруппаДенежныеСредства>\n";
                                text += "\t\t\t<ГруппаЗапасы СальдоОпераций=" + return_str(Convert.ToString(excelRange.Cells[r, 59].Value2))
                                    + " СформированоЗапасов=" + return_str(Convert.ToString(excelRange.Cells[r, 60].Value2))
                                    + " СформированоЗапасовСредстваДругихКонтрактов=" + return_str(Convert.ToString(excelRange.Cells[r, 61].Value2))
                                    + " СформированоЗапасовСобственныеСредства=" + return_str(Convert.ToString(excelRange.Cells[r, 62].Value2))
                                    + " ИспользованоЗапасов=" + return_str(Convert.ToString(excelRange.Cells[r, 63].Value2))
                                    + " ИспользованоЗапасовНаДругиеКонтракты=" + return_str(Convert.ToString(excelRange.Cells[r, 64].Value2))
                                    + " ИспользованоЗапасовНуждыОрганизации=" + return_str(Convert.ToString(excelRange.Cells[r, 65].Value2)) + ">\n";
                                text += "\t\t\t\t<МатериалыНаСкладах СальдоОпераций=" + return_str(Convert.ToString(excelRange.Cells[r, 66].Value2))
                                    + " ПоступилоМатериалов=" + return_str(Convert.ToString(excelRange.Cells[r, 67].Value2))
                                    + " ПоступилоМатериаловСредстваДругихКонтрактов=" + return_str(Convert.ToString(excelRange.Cells[r, 68].Value2))
                                    + " ПоступилоМатериаловСобственныеСредства=" + return_str(Convert.ToString(excelRange.Cells[r, 69].Value2))
                                    + " ИспользованоМатериалов=" + return_str(Convert.ToString(excelRange.Cells[r, 70].Value2))
                                    + " ИспользованоМатериаловНаДругиеКонтракты=" + return_str(Convert.ToString(excelRange.Cells[r, 71].Value2))
                                    + " ИспользованоМатериаловНуждыОрганизации=" + return_str(Convert.ToString(excelRange.Cells[r, 72].Value2)) + "/>\n";
                                text += "\t\t\t\t<НДСПоПриобретеннымЦенностям СальдоОпераций=" + return_str(Convert.ToString(excelRange.Cells[r, 73].Value2))
                                    + " Выделено=" + return_str(Convert.ToString(excelRange.Cells[r, 74].Value2))
                                    + " ВключеноВСтоимостьЗапасов=" + return_str(Convert.ToString(excelRange.Cells[r, 75].Value2))
                                    + " ПринятоКВычету=" + return_str(Convert.ToString(excelRange.Cells[r, 76].Value2)) + "/>\n";
                                text += "\t\t\t\t<ПолуфабрикатыНаСкладах СальдоОпераций=" + return_str(Convert.ToString(excelRange.Cells[r, 77].Value2))
                                    + " ПоступилоПолуфабрикатов=" + return_str(Convert.ToString(excelRange.Cells[r, 78].Value2))
                                    + " ПоступилоПолуфабрикатовСредстваДругихКонтрактов=" + return_str(Convert.ToString(excelRange.Cells[r, 79].Value2))
                                    + " ПоступилоПолуфабрикатовСобственныеСредства=" + return_str(Convert.ToString(excelRange.Cells[r, 80].Value2))
                                    + " ИспользованоПолуфабрикатов=" + return_str(Convert.ToString(excelRange.Cells[r, 81].Value2))
                                    + " ИспользованоПолуфабрикатовНаДругиеКонтракты=" + return_str(Convert.ToString(excelRange.Cells[r, 82].Value2))
                                    + " ИспользованоПолуфабрикатовНуждыОрганизации=" + return_str(Convert.ToString(excelRange.Cells[r, 83].Value2)) + "/>\n";
                                text += "\t\t\t\t<МатериалыПереданныеВПереработку СальдоОпераций=" + return_str(Convert.ToString(excelRange.Cells[r, 84].Value2))
                                    + " ПереданоСтороннемуИсполнителю=" + return_str(Convert.ToString(excelRange.Cells[r, 85].Value2))
                                    + " ПринятоИзПереработки=" + return_str(Convert.ToString(excelRange.Cells[r, 86].Value2))
                                    + " ПринятоИзПереработкиНуждыОрганизации=" + return_str(Convert.ToString(excelRange.Cells[r, 87].Value2)) + "/>\n";
                                text += "\t\t\t\t<РасходыБудущихПериодов СальдоОпераций=" + return_str(Convert.ToString(excelRange.Cells[r, 88].Value2))
                                    + " НачисленоРБП=" + return_str(Convert.ToString(excelRange.Cells[r, 89].Value2))
                                    + " СписаноРБП=" + return_str(Convert.ToString(excelRange.Cells[r, 90].Value2)) + "/>\n";
                                text += "\t\t\t\t<СредстваПроизводства СальдоОпераций=" + return_str(Convert.ToString(excelRange.Cells[r, 91].Value2))
                                    + " ПоступилоСредствПроизводства=" + return_str(Convert.ToString(excelRange.Cells[r, 92].Value2))
                                    + " ПоступилоСредствПроизводстваСредстваДругихКонтрактов=" + return_str(Convert.ToString(excelRange.Cells[r, 93].Value2))
                                    + " ПоступилоСредствПроизводстваСобственныеСредства=" + return_str(Convert.ToString(excelRange.Cells[r, 94].Value2))
                                    + " ВыбылоСредствПроизводства=" + return_str(Convert.ToString(excelRange.Cells[r, 95].Value2))
                                    + " ВыбылоСредствПроизводстваНаДругиеКонтракты=" + return_str(Convert.ToString(excelRange.Cells[r, 96].Value2))
                                    + " ВыбылоСредствПроизводстваНуждыОрганизации=" + return_str(Convert.ToString(excelRange.Cells[r, 97].Value2)) + "/>\n";
                                text += "\t\t\t</ГруппаЗапасы>\n";
                                text += "\t\t\t<ГруппаПроизводство СальдоОпераций=" + return_str(Convert.ToString(excelRange.Cells[r, 98].Value2))
                                    + " ПроизводственныеЗатраты=" + return_str(Convert.ToString(excelRange.Cells[r, 99].Value2))
                                    + " ПроизводственныеЗатратыДругихКонтрактов=" + return_str(Convert.ToString(excelRange.Cells[r, 100].Value2))
                                    + " ПроизводственныеЗатратыСобственные=" + return_str(Convert.ToString(excelRange.Cells[r, 101].Value2))
                                    + " Выпуск=" + return_str(Convert.ToString(excelRange.Cells[r, 102].Value2))
                                    + " ВыпускНаДругиеКонтракты=" + return_str(Convert.ToString(excelRange.Cells[r, 103].Value2))
                                    + " ВыпускНуждыОрганизации=" + return_str(Convert.ToString(excelRange.Cells[r, 104].Value2)) + ">\n";
                                text += "\t\t\t\t<МатериальныеЗатраты ЦелевойПоказатель=" + return_str(Convert.ToString(excelRange.Cells[r, 105].Value2))
                                    + " ПроцентВыполнения=" + return_str(Convert.ToString(excelRange.Cells[r, 106].Value2))
                                    + " СальдоОпераций=" + return_str(Convert.ToString(excelRange.Cells[r, 107].Value2))
                                    + " СписаноНаЗатраты=" + return_str(Convert.ToString(excelRange.Cells[r, 108].Value2))
                                    + " СписаноЗатратДругихКонтрактов=" + return_str(Convert.ToString(excelRange.Cells[r, 109].Value2))
                                    + " СписаноСобственныхЗатрат=" + return_str(Convert.ToString(excelRange.Cells[r, 110].Value2))
                                    + " ИсключеноИзЗатрат=" + return_str(Convert.ToString(excelRange.Cells[r, 111].Value2))
                                    + " ОтнесеноНаДругиеКонтракты=" + return_str(Convert.ToString(excelRange.Cells[r, 112].Value2))
                                    + " ОтнесеноНаСобственныеЗатраты=" + return_str(Convert.ToString(excelRange.Cells[r, 113].Value2)) + "/>\n";
                                text += "\t\t\t\t<ЗатратыФОТ ЦелевойПоказатель=" + return_str(Convert.ToString(excelRange.Cells[r, 114].Value2))
                                    + " ПроцентВыполнения=" + return_str(Convert.ToString(excelRange.Cells[r, 115].Value2))
                                    + " СальдоОпераций=" + return_str(Convert.ToString(excelRange.Cells[r, 116].Value2))
                                    + " ЗарплатаИсполнителей=" + return_str(Convert.ToString(excelRange.Cells[r, 117].Value2)) + "/>\n";
                                text += "\t\t\t\t<ПрочиеПроизводственныеЗатраты ЦелевойПоказатель=" + return_str(Convert.ToString(excelRange.Cells[r, 118].Value2))
                                    + " ПроцентВыполнения=" + return_str(Convert.ToString(excelRange.Cells[r, 119].Value2))
                                    + " СальдоОпераций=" + return_str(Convert.ToString(excelRange.Cells[r, 120].Value2))
                                    + " СписаноНаЗатраты=" + return_str(Convert.ToString(excelRange.Cells[r, 121].Value2))
                                    + " СписаноЗатратДругихКонтрактов=" + return_str(Convert.ToString(excelRange.Cells[r, 122].Value2))
                                    + " СписаноСобственныхЗатрат=" + return_str(Convert.ToString(excelRange.Cells[r, 123].Value2))
                                    + " ИсключеноИзЗатрат=" + return_str(Convert.ToString(excelRange.Cells[r, 124].Value2))
                                    + " ОтнесеноНаДругиеКонтракты=" + return_str(Convert.ToString(excelRange.Cells[r, 125].Value2))
                                    + " ОтнесеноНаСобственныеЗатраты=" + return_str(Convert.ToString(excelRange.Cells[r, 126].Value2)) + "/>\n";
                                text += "\t\t\t\t<ОбщепроизводственныеЗатраты ЦелевойПоказатель=" + return_str(Convert.ToString(excelRange.Cells[r, 127].Value2))
                                    + " ПроцентВыполнения=" + return_str(Convert.ToString(excelRange.Cells[r, 128].Value2))
                                    + " СальдоОпераций=" + return_str(Convert.ToString(excelRange.Cells[r, 129].Value2))
                                    + " РазмерЗатрат=" + return_str(Convert.ToString(excelRange.Cells[r, 130].Value2)) + "/>\n";
                                text += "\t\t\t\t<ОбщехозяйственныеЗатраты ЦелевойПоказатель=" + return_str(Convert.ToString(excelRange.Cells[r, 131].Value2))
                                    + " ПроцентВыполнения=" + return_str(Convert.ToString(excelRange.Cells[r, 132].Value2))
                                    + " СальдоОпераций=" + return_str(Convert.ToString(excelRange.Cells[r, 133].Value2))
                                    + " РазмерЗатрат=" + return_str(Convert.ToString(excelRange.Cells[r, 134].Value2)) + "/>\n";
                                text += "\t\t\t\t<ПолуфабрикатыВнутренниеРаботы СальдоОпераций=" + return_str(Convert.ToString(excelRange.Cells[r, 135].Value2))
                                    + " СписаноНаЗатраты=" + return_str(Convert.ToString(excelRange.Cells[r, 136].Value2)) + "/>\n";
                                text += "\t\t\t\t<ВыпускПолуфабрикатовВнутреннихРабот СальдоОпераций=" + return_str(Convert.ToString(excelRange.Cells[r, 137].Value2))
                                    + " Выпущено=" + return_str(Convert.ToString(excelRange.Cells[r, 138].Value2)) + "/>\n";
                                text += "\t\t\t\t<ВыпускПродукции СальдоОпераций=" + return_str(Convert.ToString(excelRange.Cells[r, 139].Value2))
                                    + " Выпущено=" + return_str(Convert.ToString(excelRange.Cells[r, 140].Value2)) + "/>\n";
                                text += "\t\t\t</ГруппаПроизводство>\n";
                                text += "\t\t\t<ГотоваяПродукция СальдоОпераций=" + return_str(Convert.ToString(excelRange.Cells[r, 141].Value2))
                                    + " Выпущено=" + return_str(Convert.ToString(excelRange.Cells[r, 142].Value2))
                                    + " ИспользованоСДругихКонтрактов=" + return_str(Convert.ToString(excelRange.Cells[r, 143].Value2))
                                    + " ИспользованоСобственной=" + return_str(Convert.ToString(excelRange.Cells[r, 144].Value2))
                                    + " Отгружено=" + return_str(Convert.ToString(excelRange.Cells[r, 145].Value2))
                                    + " ОтгруженоНаДругиеКонтракты=" + return_str(Convert.ToString(excelRange.Cells[r, 146].Value2))
                                    + " ОтгруженоНаНуждыОрганизации=" + return_str(Convert.ToString(excelRange.Cells[r, 147].Value2)) + "/>\n";
                                text += "\t\t</ГруппаРаспределениеРесурсовКонтракта>\n";
                                text += "\t\t<ГруппаОтгрузкаПродукцииВыполнениеРабот ЦелевойПоказатель=" + return_str(Convert.ToString(excelRange.Cells[r, 148].Value2))
                                    + " ПроцентВыполнения=" + return_str(Convert.ToString(excelRange.Cells[r, 149].Value2))
                                    + " СальдоОпераций=" + return_str(Convert.ToString(excelRange.Cells[r, 150].Value2)) + ">\n";
                                text += "\t\t\t<СебестоимостьПродаж ЦелевойПоказатель=" + return_str(Convert.ToString(excelRange.Cells[r, 151].Value2))
                                    + " ПроцентВыполнения=" + return_str(Convert.ToString(excelRange.Cells[r, 152].Value2))
                                    + " СальдоОпераций=" + return_str(Convert.ToString(excelRange.Cells[r, 153].Value2))
                                    + " СебестоимостьКонтракт=" + return_str(Convert.ToString(excelRange.Cells[r, 154].Value2))
                                    + " СебестоимостьНеКонтракт=" + return_str(Convert.ToString(excelRange.Cells[r, 155].Value2)) + "/>\n";
                                text += "\t\t\t<АУР ЦелевойПоказатель=" + return_str(Convert.ToString(excelRange.Cells[r, 156].Value2))
                                    + " ПроцентВыполнения=" + return_str(Convert.ToString(excelRange.Cells[r, 157].Value2))
                                    + " СальдоОпераций=" + return_str(Convert.ToString(excelRange.Cells[r, 158].Value2))
                                    + " РазмерЗатрат=" + return_str(Convert.ToString(excelRange.Cells[r, 159].Value2)) + "/>\n";
                                text += "\t\t\t<КоммерческиеРасходы ЦелевойПоказатель=" + return_str(Convert.ToString(excelRange.Cells[r, 160].Value2))
                                    + " ПроцентВыполнения=" + return_str(Convert.ToString(excelRange.Cells[r, 161].Value2))
                                    + " СальдоОпераций=" + return_str(Convert.ToString(excelRange.Cells[r, 162].Value2))
                                    + " РазмерЗатрат=" + return_str(Convert.ToString(excelRange.Cells[r, 163].Value2)) + "/>\n";
                                text += "\t\t\t<ПроцентыПоБанковскимКредитам ЦелевойПоказатель=" + return_str(Convert.ToString(excelRange.Cells[r, 164].Value2))
                                    + " ПроцентВыполнения=" + return_str(Convert.ToString(excelRange.Cells[r, 165].Value2))
                                    + " СальдоОпераций=" + return_str(Convert.ToString(excelRange.Cells[r, 166].Value2))
                                    + " РазмерЗатрат=" + return_str(Convert.ToString(excelRange.Cells[r, 167].Value2)) + "/>\n";
                                text += "\t\t\t<НДСПродажи СальдоОпераций=" + return_str(Convert.ToString(excelRange.Cells[r, 168].Value2))
                                    + " СуммаНДС=" + return_str(Convert.ToString(excelRange.Cells[r, 169].Value2)) + "/>\n";
                                text += "\t\t\t<Прибыль ЦелевойПоказатель=" + return_str(Convert.ToString(excelRange.Cells[r, 170].Value2))
                                    + " ПроцентВыполнения=" + return_str(Convert.ToString(excelRange.Cells[r, 171].Value2))
                                    + " СальдоОпераций=" + return_str(Convert.ToString(excelRange.Cells[r, 172].Value2)) + "/>\n";
                                text += "\t\t</ГруппаОтгрузкаПродукцииВыполнениеРабот>\n";
                                text += "\t\t<ПеренаправлениеПривлечение СальдоОпераций=" + return_str(Convert.ToString(excelRange.Cells[r, 173].Value2))
                                    + " ПривлеченоСредствДругихКонтрактов=" + return_str(Convert.ToString(excelRange.Cells[r, 174].Value2))
                                    + " ПривлеченоСобственныхСредств=" + return_str(Convert.ToString(excelRange.Cells[r, 175].Value2))
                                    + " ИспользованоНаДругиеКонтракты=" + return_str(Convert.ToString(excelRange.Cells[r, 176].Value2))
                                    + " ИспользованоНаСобственныеНужды=" + return_str(Convert.ToString(excelRange.Cells[r, 177].Value2)) + "/>\n";
                                text += "\t\t<СписаноСредств ЦелевойПоказатель=" + return_str(Convert.ToString(excelRange.Cells[r, 178].Value2))
                                    + " ПроцентВыполнения=" + return_str(Convert.ToString(excelRange.Cells[r, 179].Value2))
                                    + " СальдоОпераций=" + return_str(Convert.ToString(excelRange.Cells[r, 180].Value2)) + "/>\n";
                                text += "\t</Контракт>\n";
                                if (progressBar1.Value + k < 100)
                                    progressBar1.Value += k;
                            }
                            progressBar1.Value = 90;
                            excel.Workbooks[1].Close();
                            text += "</ДанныеРаздельногоУчета>";
                            //Создание отчета
                            string strSaveName = Convert.ToString(DateTime.Now);
                            strSaveName = strSaveName.Replace(':', '-');
                            File.WriteAllText("Отчеты/" + strSaveName + ".xml", text);
                            progressBar1.Value = 100;
                        }
                        else if (Convert.ToString(excelRange.Cells[1, 1].Value2) == "Исполнение контракта ГОЗ, контракта")
                        {
                            text += "<ДанныеРаздельногоУчета" + " xmlns=" + return_str("http://mil.ru/discreteAccounting", false) + " ИННОрганизации=" + '"' + Properties.Settings.Default.INN +'"' + " КППОрганизации="
                                + '"' + Properties.Settings.Default.KPP + '"' + " НаименованиеОрганизации=" + '"' + Properties.Settings.Default.name + '"'
                                + " ДатаФормирования=" + '"' + Convert.ToString(DateTime.Now.ToString("yyyy-MM-ddTHH:mm:ss")) + '"' + " ГенераторОтчета="
                                + "\"Microsoft Excel\"" + ">\n";
                            progressBar1.Value = 0;
                            //Определяем кол-во листов
                            int countItem = excelSheets.Count;
                            int k = Convert.ToInt32(87 / countItem);
                            for (int item = 1; item <= countItem; item++)
                            {
                                excelWorkSheets = (Excel.Worksheet)excelSheets.get_Item(item);
                                excelRange = excelWorkSheets.UsedRange;
                                numberContract = Convert.ToString(excelRange.Cells[5, 3].Value2);
                                text += "\t<Контракт ИГК=" + return_str(Convert.ToString(excelRange.Cells[3, 4].Value2), false) + " НомерОтдельногоСчета="
                                    + return_number(Convert.ToString(excelRange.Cells[7, 10].Value2)) + " ДатаСоставленияОтчета="
                                    + return_str(new DateTime(1899, 12, 30).AddDays(excelRange.Cells[9, 3].Value2).ToString("yyyy-MM-dd"), false) + " НомерКонтракта="
                                    + return_str(Convert.ToString(excelRange.Cells[5, 3].Value2), false)
                                    + " ДатаЗаключенияКонтракта=" + return_str(new DateTime(1899, 12, 30).AddDays(excelRange.Cells[7, 3].Value2).ToString("yyyy-MM-dd"), false)
                                    + " ПлановаяДатаИсполнения=" + return_str(new DateTime(1899, 12, 30).AddDays(excelRange.Cells[7, 6].Value2).ToString("yyyy-MM-dd"), false) + ">\n";
                                text += "\t\t<ГруппаФинансированиеКонтракта ЦелевойОбъемФинансирования=" + return_str(Convert.ToString(excelRange.Cells[14, 3].Value2))
                                    + " СальдоОпераций=" + return_str(Convert.ToString(excelRange.Cells[14, 5].Value2))
                                    + " ПроцентВыполнения=" + return_str(Convert.ToString(excelRange.Cells[14, 4].Value2)) + ">\n";
                                text += "\t\t\t<ДенежныеСредстваЗаказчика ЦенаКонтракта=" + return_str(Convert.ToString(excelRange.Cells[15, 3].Value2))
                                    + " ПроцентВыполнения=" + return_str(Convert.ToString(excelRange.Cells[15, 4].Value2))
                                    + " СальдоОпераций=" + return_str(Convert.ToString(excelRange.Cells[15, 5].Value2))
                                    + " ВозвращеноЗаказчику=" + return_str(Convert.ToString(excelRange.Cells[15, 6].Value2))
                                    + " ВозвращеноЗаказчикуСобственныеСредства=" + return_str(Convert.ToString(excelRange.Cells[15, 8].Value2))
                                    + " ПолученоОтЗаказчика=" + return_str(Convert.ToString(excelRange.Cells[15, 9].Value2)) + "/>\n";
                                text += "\t\t\t<БанковскиеКредиты ПлановыйОбъемКредитования=" + return_str(Convert.ToString(excelRange.Cells[16, 3].Value2))
                                    + " ПроцентВыполнения=" + return_str(Convert.ToString(excelRange.Cells[16, 4].Value2))
                                    + " СальдоОпераций=" + return_str(Convert.ToString(excelRange.Cells[16, 5].Value2))
                                    + " ПогашеноТелаКредита=" + return_str(Convert.ToString(excelRange.Cells[16, 6].Value2))
                                    + " ПогашеноТелаКредитаСобственныеСредства=" + return_str(Convert.ToString(excelRange.Cells[16, 8].Value2))
                                    + " ПривлеченоКредитов=" + return_str(Convert.ToString(excelRange.Cells[16, 9].Value2)) + "/>\n";
                                text += "\t\t\t<ЗадолженностьПоПроцентамКредитов СальдоОпераций=" + return_str(Convert.ToString(excelRange.Cells[17, 5].Value2))
                                    + " ПогашеноПроцентов=" + return_str(Convert.ToString(excelRange.Cells[17, 6].Value2))
                                    + " ПогашеноПроцентовСобственныеСредства=" + return_str(Convert.ToString(excelRange.Cells[17, 8].Value2))
                                    + " НачисленоПроцентов=" + return_str(Convert.ToString(excelRange.Cells[17, 9].Value2)) + "/>\n";
                                text += "\t\t\t<ЗадолженностьПоставщикам СальдоОпераций=" + return_str(Convert.ToString(excelRange.Cells[18, 5].Value2))
                                    + " ОплаченоПоставщикам=" + return_str(Convert.ToString(excelRange.Cells[18, 6].Value2))
                                    + " ОплаченоПоставщикамСредстваДругихКонтрактов=" + return_str(Convert.ToString(excelRange.Cells[18, 7].Value2))
                                    + " ОплаченоПоставщикамСобственныеСредства=" + return_str(Convert.ToString(excelRange.Cells[18, 8].Value2))
                                    + " СуммарнаяЗадолженность=" + return_str(Convert.ToString(excelRange.Cells[18, 9].Value2)) + "/>\n";
                                text += "\t\t</ГруппаФинансированиеКонтракта>\n";
                                text += "\t\t<ГруппаРаспределениеРесурсовКонтракта СальдоОпераций=" + return_str(Convert.ToString(excelRange.Cells[19, 5].Value2)) + ">\n";
                                text += "\t\t\t<ГруппаДенежныеСредства СальдоОпераций=" + return_str(Convert.ToString(excelRange.Cells[20, 5].Value2))
                                    + " ДенежныеАктивы=" + return_str(Convert.ToString(excelRange.Cells[20, 6].Value2))
                                    + " ДенежныеАктивыСредстваДругихКонтрактов=" + return_str(Convert.ToString(excelRange.Cells[20, 7].Value2))
                                    + " ДенежныеАктивыСобственныеСредства=" + return_str(Convert.ToString(excelRange.Cells[20, 8].Value2))
                                    + " ИспользованиеРесурсов=" + return_str(Convert.ToString(excelRange.Cells[20, 9].Value2))
                                    + " ИспользованиеРесурсовДругиеКонтракты=" + return_str(Convert.ToString(excelRange.Cells[20, 10].Value2))
                                    + " ИспользованиеРесурсовСобственныеСредства=" + return_str(Convert.ToString(excelRange.Cells[20, 11].Value2)) + ">\n";
                                text += "\t\t\t\t<ДенежныеСредстваОтдельныйСчет СальдоОпераций=" + return_str(Convert.ToString(excelRange.Cells[21, 5].Value2))
                                    + " ЗачисленоИсполнениеКонтракта=" + return_str(Convert.ToString(excelRange.Cells[21, 6].Value2))
                                    + " ЗачисленоИное=" + return_str(Convert.ToString(excelRange.Cells[21, 8].Value2))
                                    + " СписаноИсполнениеКонтракта=" + return_str(Convert.ToString(excelRange.Cells[21, 9].Value2))
                                    + " СписаноДругиеКонтракты=" + return_str(Convert.ToString(excelRange.Cells[21, 10].Value2))
                                    + " СписаноРасходыОрганизации=" + return_str(Convert.ToString(excelRange.Cells[21, 11].Value2)) + "/>\n";
                                text += "\t\t\t\t<БанковскиеДепозиты СальдоОпераций=" + return_str(Convert.ToString(excelRange.Cells[22, 5].Value2))
                                    + " ПеречисленоНаДепозит=" + return_str(Convert.ToString(excelRange.Cells[22, 6].Value2))
                                    + " ВозвращеноСДепозита=" + return_str(Convert.ToString(excelRange.Cells[22, 9].Value2)) + "/>\n";
                                text += "\t\t\t\t<АвансыВыданные СальдоОпераций=" + return_str(Convert.ToString(excelRange.Cells[23, 5].Value2))
                                    + " АвансыИсполнениеКонтракта=" + return_str(Convert.ToString(excelRange.Cells[23, 6].Value2))
                                    + " АвансыСредстваДругихКонтрактов=" + return_str(Convert.ToString(excelRange.Cells[23, 7].Value2))
                                    + " АвансыСобственныеСредства=" + return_str(Convert.ToString(excelRange.Cells[23, 8].Value2))
                                    + " ЗачтеноАвансов=" + return_str(Convert.ToString(excelRange.Cells[23, 9].Value2))
                                    + " СписаноЗадолженностиКооперации=" + return_str(Convert.ToString(excelRange.Cells[23, 11].Value2)) + "/>\n";
                                text += "\t\t\t</ГруппаДенежныеСредства>\n";
                                text += "\t\t\t<ГруппаЗапасы СальдоОпераций=" + return_str(Convert.ToString(excelRange.Cells[24, 5].Value2))
                                    + " СформированоЗапасов=" + return_str(Convert.ToString(excelRange.Cells[24, 6].Value2))
                                    + " СформированоЗапасовСредстваДругихКонтрактов=" + return_str(Convert.ToString(excelRange.Cells[24, 7].Value2))
                                    + " СформированоЗапасовСобственныеСредства=" + return_str(Convert.ToString(excelRange.Cells[24, 8].Value2))
                                    + " ИспользованоЗапасов=" + return_str(Convert.ToString(excelRange.Cells[24, 9].Value2))
                                    + " ИспользованоЗапасовНаДругиеКонтракты=" + return_str(Convert.ToString(excelRange.Cells[24, 10].Value2))
                                    + " ИспользованоЗапасовНуждыОрганизации=" + return_str(Convert.ToString(excelRange.Cells[24, 11].Value2)) + ">\n";
                                text += "\t\t\t\t<МатериалыНаСкладах СальдоОпераций=" + return_str(Convert.ToString(excelRange.Cells[25, 5].Value2))
                                    + " ПоступилоМатериалов=" + return_str(Convert.ToString(excelRange.Cells[25, 6].Value2))
                                    + " ПоступилоМатериаловСредстваДругихКонтрактов=" + return_str(Convert.ToString(excelRange.Cells[25, 7].Value2))
                                    + " ПоступилоМатериаловСобственныеСредства=" + return_str(Convert.ToString(excelRange.Cells[25, 8].Value2))
                                    + " ИспользованоМатериалов=" + return_str(Convert.ToString(excelRange.Cells[25, 9].Value2))
                                    + " ИспользованоМатериаловНаДругиеКонтракты=" + return_str(Convert.ToString(excelRange.Cells[25, 10].Value2))
                                    + " ИспользованоМатериаловНуждыОрганизации=" + return_str(Convert.ToString(excelRange.Cells[25, 11].Value2)) + "/>\n";
                                text += "\t\t\t\t<НДСПоПриобретеннымЦенностям СальдоОпераций=" + return_str(Convert.ToString(excelRange.Cells[26, 5].Value2))
                                    + " Выделено=" + return_str(Convert.ToString(excelRange.Cells[26, 6].Value2))
                                    + " ВключеноВСтоимостьЗапасов=" + return_str(Convert.ToString(excelRange.Cells[26, 9].Value2))
                                    + " ПринятоКВычету=" + return_str(Convert.ToString(excelRange.Cells[26, 11].Value2)) + "/>\n";
                                text += "\t\t\t\t<ПолуфабрикатыНаСкладах СальдоОпераций=" + return_str(Convert.ToString(excelRange.Cells[27, 5].Value2))
                                    + " ПоступилоПолуфабрикатов=" + return_str(Convert.ToString(excelRange.Cells[27, 6].Value2))
                                    + " ПоступилоПолуфабрикатовСредстваДругихКонтрактов=" + return_str(Convert.ToString(excelRange.Cells[27, 7].Value2))
                                    + " ПоступилоПолуфабрикатовСобственныеСредства=" + return_str(Convert.ToString(excelRange.Cells[27, 8].Value2))
                                    + " ИспользованоПолуфабрикатов=" + return_str(Convert.ToString(excelRange.Cells[27, 9].Value2))
                                    + " ИспользованоПолуфабрикатовНаДругиеКонтракты=" + return_str(Convert.ToString(excelRange.Cells[27, 10].Value2))
                                    + " ИспользованоПолуфабрикатовНуждыОрганизации=" + return_str(Convert.ToString(excelRange.Cells[27, 11].Value2)) + "/>\n";
                                text += "\t\t\t\t<МатериалыПереданныеВПереработку СальдоОпераций=" + return_str(Convert.ToString(excelRange.Cells[28, 5].Value2))
                                    + " ПереданоСтороннемуИсполнителю=" + return_str(Convert.ToString(excelRange.Cells[28, 6].Value2))
                                    + " ПринятоИзПереработки=" + return_str(Convert.ToString(excelRange.Cells[28, 9].Value2))
                                    + " ПринятоИзПереработкиНуждыОрганизации=" + return_str(Convert.ToString(excelRange.Cells[28, 11].Value2)) + "/>\n";
                                text += "\t\t\t\t<РасходыБудущихПериодов СальдоОпераций=" + return_str(Convert.ToString(excelRange.Cells[29, 5].Value2))
                                    + " НачисленоРБП=" + return_str(Convert.ToString(excelRange.Cells[29, 6].Value2))
                                    + " СписаноРБП=" + return_str(Convert.ToString(excelRange.Cells[29, 9].Value2)) + "/>\n";
                                text += "\t\t\t\t<СредстваПроизводства СальдоОпераций=" + return_str(Convert.ToString(excelRange.Cells[30, 5].Value2))
                                    + " ПоступилоСредствПроизводства=" + return_str(Convert.ToString(excelRange.Cells[30, 6].Value2))
                                    + " ПоступилоСредствПроизводстваСредстваДругихКонтрактов=" + return_str(Convert.ToString(excelRange.Cells[30, 7].Value2))
                                    + " ПоступилоСредствПроизводстваСобственныеСредства=" + return_str(Convert.ToString(excelRange.Cells[30, 8].Value2))
                                    + " ВыбылоСредствПроизводства=" + return_str(Convert.ToString(excelRange.Cells[30, 9].Value2))
                                    + " ВыбылоСредствПроизводстваНаДругиеКонтракты=" + return_str(Convert.ToString(excelRange.Cells[30, 10].Value2))
                                    + " ВыбылоСредствПроизводстваНуждыОрганизации=" + return_str(Convert.ToString(excelRange.Cells[30, 11].Value2)) + "/>\n";
                                text += "\t\t\t</ГруппаЗапасы>\n";
                                text += "\t\t\t<ГруппаПроизводство СальдоОпераций=" + return_str(Convert.ToString(excelRange.Cells[31, 5].Value2))
                                    + " ПроизводственныеЗатраты=" + return_str(Convert.ToString(excelRange.Cells[31, 6].Value2))
                                    + " ПроизводственныеЗатратыДругихКонтрактов=" + return_str(Convert.ToString(excelRange.Cells[31, 7].Value2))
                                    + " ПроизводственныеЗатратыСобственные=" + return_str(Convert.ToString(excelRange.Cells[31, 8].Value2))
                                    + " Выпуск=" + return_str(Convert.ToString(excelRange.Cells[31, 9].Value2))
                                    + " ВыпускНаДругиеКонтракты=" + return_str(Convert.ToString(excelRange.Cells[31, 10].Value2))
                                    + " ВыпускНуждыОрганизации=" + return_str(Convert.ToString(excelRange.Cells[31, 11].Value2)) + ">\n";
                                text += "\t\t\t\t<МатериальныеЗатраты ЦелевойПоказатель=" + return_str(Convert.ToString(excelRange.Cells[32, 3].Value2))
                                    + " ПроцентВыполнения=" + return_str(Convert.ToString(excelRange.Cells[32, 4].Value2))
                                    + " СальдоОпераций=" + return_str(Convert.ToString(excelRange.Cells[32, 5].Value2))
                                    + " СписаноНаЗатраты=" + return_str(Convert.ToString(excelRange.Cells[32, 6].Value2))
                                    + " СписаноЗатратДругихКонтрактов=" + return_str(Convert.ToString(excelRange.Cells[32, 7].Value2))
                                    + " СписаноСобственныхЗатрат=" + return_str(Convert.ToString(excelRange.Cells[32, 8].Value2))
                                    + " ИсключеноИзЗатрат=" + return_str(Convert.ToString(excelRange.Cells[32, 9].Value2))
                                    + " ОтнесеноНаДругиеКонтракты=" + return_str(Convert.ToString(excelRange.Cells[32, 10].Value2))
                                    + " ОтнесеноНаСобственныеЗатраты=" + return_str(Convert.ToString(excelRange.Cells[32, 11].Value2)) + "/>\n";
                                text += "\t\t\t\t<ЗатратыФОТ ЦелевойПоказатель=" + return_str(Convert.ToString(excelRange.Cells[33, 3].Value2))
                                    + " ПроцентВыполнения=" + return_str(Convert.ToString(excelRange.Cells[33, 4].Value2))
                                    + " СальдоОпераций=" + return_str(Convert.ToString(excelRange.Cells[33, 5].Value2))
                                    + " ЗарплатаИсполнителей=" + return_str(Convert.ToString(excelRange.Cells[33, 8].Value2)) + "/>\n";
                                text += "\t\t\t\t<ПрочиеПроизводственныеЗатраты ЦелевойПоказатель=" + return_str(Convert.ToString(excelRange.Cells[34, 3].Value2))
                                    + " ПроцентВыполнения=" + return_str(Convert.ToString(excelRange.Cells[34, 4].Value2))
                                    + " СальдоОпераций=" + return_str(Convert.ToString(excelRange.Cells[34, 5].Value2))
                                    + " СписаноНаЗатраты=" + return_str(Convert.ToString(excelRange.Cells[34, 6].Value2))
                                    + " СписаноЗатратДругихКонтрактов=" + return_str(Convert.ToString(excelRange.Cells[34, 7].Value2))
                                    + " СписаноСобственныхЗатрат=" + return_str(Convert.ToString(excelRange.Cells[34, 8].Value2))
                                    + " ИсключеноИзЗатрат=" + return_str(Convert.ToString(excelRange.Cells[34, 9].Value2))
                                    + " ОтнесеноНаДругиеКонтракты=" + return_str(Convert.ToString(excelRange.Cells[34, 10].Value2))
                                    + " ОтнесеноНаСобственныеЗатраты=" + return_str(Convert.ToString(excelRange.Cells[34, 11].Value2)) + "/>\n";
                                text += "\t\t\t\t<ОбщепроизводственныеЗатраты ЦелевойПоказатель=" + return_str(Convert.ToString(excelRange.Cells[35, 3].Value2))
                                    + " ПроцентВыполнения=" + return_str(Convert.ToString(excelRange.Cells[35, 4].Value2))
                                    + " СальдоОпераций=" + return_str(Convert.ToString(excelRange.Cells[35, 5].Value2))
                                    + " РазмерЗатрат=" + return_str(Convert.ToString(excelRange.Cells[35, 8].Value2)) + "/>\n";
                                text += "\t\t\t\t<ОбщехозяйственныеЗатраты ЦелевойПоказатель=" + return_str(Convert.ToString(excelRange.Cells[36, 3].Value2))
                                    + " ПроцентВыполнения=" + return_str(Convert.ToString(excelRange.Cells[36, 4].Value2))
                                    + " СальдоОпераций=" + return_str(Convert.ToString(excelRange.Cells[36, 5].Value2))
                                    + " РазмерЗатрат=" + return_str(Convert.ToString(excelRange.Cells[36, 8].Value2)) + "/>\n";
                                text += "\t\t\t\t<ПолуфабрикатыВнутренниеРаботы СальдоОпераций=" + return_str(Convert.ToString(excelRange.Cells[37, 5].Value2))
                                    + " СписаноНаЗатраты=" + return_str(Convert.ToString(excelRange.Cells[37, 6].Value2)) + "/>\n";
                                text += "\t\t\t\t<ВыпускПолуфабрикатовВнутреннихРабот СальдоОпераций=" + return_str(Convert.ToString(excelRange.Cells[38, 5].Value2))
                                    + " Выпущено=" + return_str(Convert.ToString(excelRange.Cells[38, 9].Value2)) + "/>\n";
                                text += "\t\t\t\t<ВыпускПродукции СальдоОпераций=" + return_str(Convert.ToString(excelRange.Cells[39, 5].Value2))
                                    + " Выпущено=" + return_str(Convert.ToString(excelRange.Cells[39, 9].Value2)) + "/>\n";
                                text += "\t\t\t</ГруппаПроизводство>\n";
                                text += "\t\t\t<ГотоваяПродукция СальдоОпераций=" + return_str(Convert.ToString(excelRange.Cells[40, 5].Value2))
                                    + " Выпущено=" + return_str(Convert.ToString(excelRange.Cells[40, 6].Value2))
                                    + " ИспользованоСДругихКонтрактов=" + return_str(Convert.ToString(excelRange.Cells[40, 7].Value2))
                                    + " ИспользованоСобственной=" + return_str(Convert.ToString(excelRange.Cells[40, 8].Value2))
                                    + " Отгружено=" + return_str(Convert.ToString(excelRange.Cells[40, 9].Value2))
                                    + " ОтгруженоНаДругиеКонтракты=" + return_str(Convert.ToString(excelRange.Cells[40, 10].Value2))
                                    + " ОтгруженоНаНуждыОрганизации=" + return_str(Convert.ToString(excelRange.Cells[40, 11].Value2)) + "/>\n";
                                text += "\t\t</ГруппаРаспределениеРесурсовКонтракта>\n";
                                text += "\t\t<ГруппаОтгрузкаПродукцииВыполнениеРабот ЦелевойПоказатель=" + return_str(Convert.ToString(excelRange.Cells[41, 3].Value2))
                                    + " ПроцентВыполнения=" + return_str(Convert.ToString(excelRange.Cells[41, 4].Value2))
                                    + " СальдоОпераций=" + return_str(Convert.ToString(excelRange.Cells[41, 5].Value2)) + ">\n";
                                text += "\t\t\t<СебестоимостьПродаж ЦелевойПоказатель=" + return_str(Convert.ToString(excelRange.Cells[42, 3].Value2))
                                    + " ПроцентВыполнения=" + return_str(Convert.ToString(excelRange.Cells[42, 4].Value2))
                                    + " СальдоОпераций=" + return_str(Convert.ToString(excelRange.Cells[42, 5].Value2))
                                    + " СебестоимостьКонтракт=" + return_str(Convert.ToString(excelRange.Cells[42, 6].Value2))
                                    + " СебестоимостьНеКонтракт=" + return_str(Convert.ToString(excelRange.Cells[42, 8].Value2)) + "/>\n";
                                text += "\t\t\t<АУР ЦелевойПоказатель=" + return_str(Convert.ToString(excelRange.Cells[43, 3].Value2))
                                    + " ПроцентВыполнения=" + return_str(Convert.ToString(excelRange.Cells[43, 4].Value2))
                                    + " СальдоОпераций=" + return_str(Convert.ToString(excelRange.Cells[43, 5].Value2))
                                    + " РазмерЗатрат=" + return_str(Convert.ToString(excelRange.Cells[43, 8].Value2)) + "/>\n";
                                text += "\t\t\t<КоммерческиеРасходы ЦелевойПоказатель=" + return_str(Convert.ToString(excelRange.Cells[44, 3].Value2))
                                    + " ПроцентВыполнения=" + return_str(Convert.ToString(excelRange.Cells[44, 4].Value2))
                                    + " СальдоОпераций=" + return_str(Convert.ToString(excelRange.Cells[44, 5].Value2))
                                    + " РазмерЗатрат=" + return_str(Convert.ToString(excelRange.Cells[44, 6].Value2)) + "/>\n";
                                text += "\t\t\t<ПроцентыПоБанковскимКредитам ЦелевойПоказатель=" + return_str(Convert.ToString(excelRange.Cells[45, 3].Value2))
                                    + " ПроцентВыполнения=" + return_str(Convert.ToString(excelRange.Cells[45, 4].Value2))
                                    + " СальдоОпераций=" + return_str(Convert.ToString(excelRange.Cells[45, 5].Value2))
                                    + " РазмерЗатрат=" + return_str(Convert.ToString(excelRange.Cells[45, 6].Value2)) + "/>\n";
                                text += "\t\t\t<НДСПродажи СальдоОпераций=" + return_str(Convert.ToString(excelRange.Cells[46, 5].Value2))
                                    + " СуммаНДС=" + return_str(Convert.ToString(excelRange.Cells[46, 8].Value2)) + "/>\n";
                                text += "\t\t\t<Прибыль ЦелевойПоказатель=" + return_str(Convert.ToString(excelRange.Cells[47, 3].Value2))
                                    + " ПроцентВыполнения=" + return_str(Convert.ToString(excelRange.Cells[47, 4].Value2))
                                    + " СальдоОпераций=" + return_str(Convert.ToString(excelRange.Cells[47, 5].Value2)) + "/>\n";
                                text += "\t\t</ГруппаОтгрузкаПродукцииВыполнениеРабот>\n";
                                text += "\t\t<ПеренаправлениеПривлечение СальдоОпераций=" + return_str(Convert.ToString(excelRange.Cells[48, 5].Value2))
                                    + " ПривлеченоСредствДругихКонтрактов=" + return_str(Convert.ToString(excelRange.Cells[48, 7].Value2))
                                    + " ПривлеченоСобственныхСредств=" + return_str(Convert.ToString(excelRange.Cells[48, 8].Value2))
                                    + " ИспользованоНаДругиеКонтракты=" + return_str(Convert.ToString(excelRange.Cells[48, 10].Value2))
                                    + " ИспользованоНаСобственныеНужды=" + return_str(Convert.ToString(excelRange.Cells[48, 11].Value2)) + "/>\n";
                                text += "\t\t<СписаноСредств ЦелевойПоказатель=" + return_str(Convert.ToString(excelRange.Cells[49, 3].Value2))
                                    + " ПроцентВыполнения=" + return_str(Convert.ToString(excelRange.Cells[49, 4].Value2))
                                    + " СальдоОпераций=" + return_str(Convert.ToString(excelRange.Cells[49, 5].Value2)) + "/>\n";
                                text += "\t</Контракт>\n";
                                if (progressBar1.Value + k < 100)
                                    progressBar1.Value += k;
                            }
                            progressBar1.Value = 90;
                            excel.Workbooks[1].Close();
                            text += "</ДанныеРаздельногоУчета>";
                            //Создание отчета
                            string strSaveName = Convert.ToString(DateTime.Now);
                            strSaveName = strSaveName.Replace(':', '-');
                            File.WriteAllText("Отчеты/" + strSaveName + ".xml", text);
                            progressBar1.Value = 100;
                            nameSignPath = strSaveName;
                        }
                        else
                            MessageBox.Show("Неверный формат представления данных в Excel документе!", "Ошибка!", MessageBoxButtons.OK, MessageBoxIcon.Error);
                    }
                    catch
                    {
                        try
                        {
                            MessageBox.Show("Ошибка при считавании контракта " + numberContract);
                            printProverka = false;
                        }
                        catch
                        {
                            MessageBox.Show("Ошибка в ошибке!");
                            printProverka = false;
                        }
                    }
                    finally
                    {
                        excel.Quit();
                        if (printProverka)
                        {
                            MessageBox.Show("Отчет сформирован!", "Информация", MessageBoxButtons.OK, MessageBoxIcon.Information);
                            button3.Enabled = true;
                            excelReader = "";
                        }
                    }
                }
                else
                {
                    //Желаете создать директорию для сохранения отчетов?
                    if (DialogResult.Yes == MessageBox.Show("Не найдена директория для создания отчетов!\nЖелаете ее создать?", "Подтверждение", MessageBoxButtons.YesNo, MessageBoxIcon.Question))
                        Directory.CreateDirectory("Отчеты");
                }
            }
            else
            {
                button2.Enabled = false;
            }
        }


        //Подписываем файл
        private void button3_Click(object sender, EventArgs e)
        {
            if (nameSignPath.Length > 0)
            {
                if (File.Exists("Отчеты/" + nameSignPath + ".xml"))
                {
                    if (File.Exists("sign.bat"))
                    {
                        if (!Directory.Exists("Отчеты/К отправке"))
                            Directory.CreateDirectory(@"Отчеты/К отправке");
                        string pathEnd = "Отчеты/К отправке/" + Convert.ToString(DateTime.Now.ToShortDateString());
                        if (!Directory.Exists(pathEnd))
                            Directory.CreateDirectory(pathEnd);
                        File.Copy("Отчеты/" + nameSignPath + ".xml", pathEnd + "/message.xml", true);
                        System.Diagnostics.Process.Start("sign.bat", "\"" + Directory.GetCurrentDirectory() + "\\Отчеты\\К отправке\\" + Convert.ToString(DateTime.Now.ToShortDateString()) + "\\message.xml\"" + " " + "\"" + Directory.GetCurrentDirectory() + "\\Отчеты\\К отправке\\" + Convert.ToString(DateTime.Now.ToShortDateString()) + "\\message.sign\"" + " " + Properties.Settings.Default.nameSign);
                        nameSignPath = "";
                    }
                    else
                        if (DialogResult.Yes == MessageBox.Show("Не найден исполняющий файл для создания подписи!\nСоздать его?", "Подтверждение", MessageBoxButtons.YesNo, MessageBoxIcon.Question))
                        {
                            byte[] array = Properties.Resources.sign;
                            FileStream fs = new FileStream("sign.bat", FileMode.Create);
                            fs.Write(array, 0, array.Length);
                            fs.Close();
                        }
                }
                else
                    MessageBox.Show("Не обнаружен файл для подписи!", "Ошибка!", MessageBoxButtons.OK, MessageBoxIcon.Error);
            }
            else
                button3.Enabled = false;
        }

        //Формируем архив
        private void button4_Click(object sender, EventArgs e)
        {
            string pathToDir = "Отчеты/К отправке/" + Convert.ToString(DateTime.Now.ToShortDateString());
            //Проверка на наличие обоих файлов
            if (!File.Exists(pathToDir + "/message.xml"))
            {
                MessageBox.Show("Отсутсвует файл отчета!", "Ошибка!", MessageBoxButtons.OK, MessageBoxIcon.Error);
                return;
            }
            if (!File.Exists(pathToDir + "/message.sign"))
            {
                MessageBox.Show("Отсутсвует файл с подписью!", "Ошибка!", MessageBoxButtons.OK, MessageBoxIcon.Error);
                return;
            }

            //Формирование архива
            string nameArchive = "6668000472" + "_" + DateTime.Now.ToString("yyyyMMdd") + "_" + DateTime.Now.ToString("yyyyMMdd") + "_0" + (Directory.EnumerateFiles(pathToDir, "*.zip").ToList<string>().Count + 1);
            Directory.CreateDirectory(pathToDir + '/' + nameArchive);
            File.Move(pathToDir + "/message.xml", pathToDir + "/" + nameArchive + "/message.xml");
            File.Move(pathToDir + "/message.sign", pathToDir + "/" + nameArchive + "/message.sign");
            ZipFile.CreateFromDirectory(pathToDir + '/' + nameArchive, pathToDir + '/' + nameArchive + ".zip", CompressionLevel.NoCompression, false);

            //Удаление лишних файлов
            File.Delete(pathToDir + "/message.xml");
            File.Delete(pathToDir + "/message.sign");
            Directory.Delete(pathToDir + '/' + nameArchive, true);
            MessageBox.Show("Архив сформирован!", "Информация", MessageBoxButtons.OK, MessageBoxIcon.Information);
        }

        private string return_str(string str, bool isZero=true)
        {
            try
            {
                str = '"' + str + '"';
                if (str == "\"\"" || str == "\"0\"")
                    str = "\"0,00\"";
                if (str.Split(',').Length > 1)
                    if (str.Split(',')[1].Length == 2)
                    {
                        string prStr = str.Split(',')[0] + ',' + str.Split(',')[1][0].ToString() + "0\"";
                        str = prStr;
                    }else
                        if (str.Split(',')[1].Length == 3)
                        {
                            string prStr = str.Split(',')[0] + ',' + str.Split(',')[1][0].ToString() + str.Split(',')[1][1].ToString() + '"';
                            str = prStr;
                        }
                        else
                        {
                            int prThird = Convert.ToInt32(Convert.ToString(str.Split(',')[1][2]));
                            int prSecond = Convert.ToInt32(Convert.ToString(str.Split(',')[1][1]));
                            int prFirst = Convert.ToInt32(Convert.ToString(str.Split(',')[1][0]));
                            int prMain = Convert.ToInt32(Convert.ToString(str.Split(',')[0].Remove(0,1)));
                            if (prThird >= 5)
                                prSecond++;
                            string prStr = '"' + prMain.ToString() + ',' + prFirst.ToString() + prSecond.ToString() + '"';
                            if (prSecond >= 10)
                            {
                                prFirst++;
                                prStr = '"' + prMain.ToString() + ',' + prFirst.ToString() + "0" + '"';
                            }
                            if (prFirst >= 10)
                            {
                                prMain++;
                                prStr = '"' + prMain.ToString() + ',' + "00" + '"';
                            }
                            str = prStr;
                        }
                else
                    if (isZero)
                        str = str.Substring(0, str.Length - 1) + ",00\"";
                str = str.Replace(',', '.');
                return str;
            }
            catch
            {
                MessageBox.Show("Ошибка при преобразованиях!");
            }
            return "\"0,00\"";
        }

        //Номер отдельного счета
        private string return_number(string str)
        {
            try
            {
                str = '"' + str + '"';
                if (str == "\"\"" || str == "\"0\"")
                    str = "\"00000000000000000000\"";
                str = str.Replace(',', '.');
                return str;
            }
            catch
            {
                MessageBox.Show("Ошибка при преобразованиях!");
            }
            return "\"00000000000000000000\"";
        }

        private string return_date(int val)
        {
            MessageBox.Show(val.ToString());
            string str = new DateTime(1899, 12, 30).AddDays(val).ToString("yyyy-MM-dd");
            return str;
        }

        //Открыть проводник с каталогом данной программы
        private void openToolStripMenuItem_Click(object sender, EventArgs e)
        {
            System.Diagnostics.Process.Start("explorer", Directory.GetCurrentDirectory());
        }

        //Создать шаблон
        private void createToolStripMenuItem_Click(object sender, EventArgs e)
        {
            //Проверка на наличие директории
            if (Directory.Exists("Отчеты"))
            {
                byte[] array = Properties.Resources.shablon;
                FileStream fs = new FileStream("Отчеты/Шаблон для отчетов.xlsx", FileMode.Create);
                fs.Write(array, 0, array.Length);
                fs.Close();
            }
            else
                if (DialogResult.Yes == MessageBox.Show("Не найдена директория для создания отчетов!\nЖелаете ее создать?", "Подтверждение", MessageBoxButtons.YesNo, MessageBoxIcon.Question))
                    Directory.CreateDirectory("Отчеты");
                else
                    return;
        }

        //Выход
        private void exitToolStripMenuItem_Click(object sender, EventArgs e)
        {
            Application.Exit();
        }

        //Настройки
        private void settingsToolStripMenuItem_Click(object sender, EventArgs e)
        {
            FormSettings formSettings = new FormSettings();
            formSettings.ShowDialog();
        }

        //О программе
        private void aboutProgrammToolStripMenuItem_Click(object sender, EventArgs e)
        {
            FormAboutProgramm formAboutProgramm = new FormAboutProgramm();
            formAboutProgramm.ShowDialog();
        }
    }
}
