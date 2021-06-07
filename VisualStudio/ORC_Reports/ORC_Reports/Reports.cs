using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using Word = Microsoft.Office.Interop.Word;
using Excel = Microsoft.Office.Interop.Excel;
using System.IO;
using System.Net;
using Newtonsoft.Json;

namespace ORC_Reports
{
    static class Reports
    {
        static string server = ClassBD.ServerName;

        static public void CreateReports()
        {

        }

        //---------Второй отдел---------
        #region ВТОРОЙ ОТДЕЛ
        //Сводный отчет по договорам
        static public string SecondDepartmentPrintReport1(string typeDocument, string userSaveName, out string savePath, bool isVisibleGenerate = false, string viewWork = "", string year = "")
        {
            savePath = null;

            string command = "http://" + server + "/orc_new/second_department_print_reports?real_name_table=Сводный отчет по договорам&view_work="+viewWork+"&year="+year;
            if (userSaveName == "")
                userSaveName = "Отчет";
            //Запрос
            dynamic json = null;
            WebRequest request = WebRequest.Create(command);
            WebResponse response = request.GetResponse();
            using (Stream stream = response.GetResponseStream())
            {
                using (StreamReader reader = new StreamReader(stream))
                {
                    json = JsonConvert.DeserializeObject(reader.ReadToEnd());
                }
            }

            //Создание доков
            try
            {
                if (typeDocument == "Word")
                {
                    //Создаем документ
                    Word.Application oWord = new Word.Application();
                    oWord.Visible = false;
                    if (isVisibleGenerate)
                        oWord.Visible = true;
                    Object template = Type.Missing;
                    Object newTemplate = false;
                    Object documentType = Word.WdNewDocumentType.wdNewBlankDocument;
                    //Object visible = true;
                    oWord.Documents.Add(ref template, ref newTemplate, ref documentType);
                    Word.Document wordDocument = (Word.Document)oWord.Documents.get_Item(1);
                    //Альбомная ориентация
                    wordDocument.PageSetup.Orientation = Word.WdOrientation.wdOrientLandscape;
                    //Добавление шапки в файл
                    wordDocument.Paragraphs.Add();
                    var range = wordDocument.Paragraphs[1].Range;
                    range.Text = "Сводный отчет по договорам";
                    range.ParagraphFormat.Alignment = Word.WdParagraphAlignment.wdAlignParagraphCenter;
                    wordDocument.Paragraphs.Add();
                    range = wordDocument.Paragraphs[2].Range;
                    range.Text = "по состоянию на " + DateTime.Now.ToShortDateString() + " г.";
                    range.ParagraphFormat.Alignment = Word.WdParagraphAlignment.wdAlignParagraphRight;
                    //Добавление в файл таблицы
                    wordDocument.Paragraphs.Add();
                    Object autoFitBehavior = Word.WdAutoFitBehavior.wdAutoFitWindow;
                    range = wordDocument.Paragraphs[wordDocument.Paragraphs.Count].Range;
                    wordDocument.Tables.Add(range, 1, 8, autoFitBehavior);
                    Word.Table oTable = wordDocument.Tables[1];
                    string[] nameHeaderTable = { "№ дог.", "Контрагент", "Вид договора", "Наименование работ", "ГОЗ, экспорт", "Срок исполнения", "Состояние", "Сумма (оконч.)" };
                    for (int i = 0; i < oTable.Columns.Count; i++)
                    {
                        Word.Range wordCellRange = oTable.Cell(1, i + 1).Range;
                        wordCellRange.ParagraphFormat.Alignment = Word.WdParagraphAlignment.wdAlignParagraphCenter;
                        wordCellRange.Text = nameHeaderTable[i];
                    }
                    if (json != null)
                    {
                        int posRows = 2;
                        foreach (dynamic d in json)
                        {
                            oTable.Rows.Add();
                            Word.Range wordCellRange = oTable.Cell(posRows, 1).Range;
                            wordCellRange.Text = Convert.ToString(d["number_contract"]);
                            wordCellRange = oTable.Cell(posRows, 2).Range;
                            wordCellRange.Text = Convert.ToString(d["name_counterpartie_contract"]);
                            wordCellRange = oTable.Cell(posRows, 3).Range;
                            wordCellRange.Text = Convert.ToString(d["name_view_contract"]);
                            wordCellRange = oTable.Cell(posRows, 4).Range;
                            wordCellRange.Text = Convert.ToString(d["item_contract"]);
                            wordCellRange = oTable.Cell(posRows, 5).Range;
                            wordCellRange.Text = Convert.ToString(d["name_works_goz"]);
                            wordCellRange = oTable.Cell(posRows, 6).Range;
                            wordCellRange.Text = d["date_maturity_reestr"] != null ? Convert.ToString(d["date_maturity_reestr"]) : Convert.ToString(d["date_maturity_date_reestr"]);
                            wordCellRange = oTable.Cell(posRows, 7).Range;
                            wordCellRange.Text = Convert.ToString(d["name_state"]);
                            wordCellRange = oTable.Cell(posRows, 8).Range;
                            wordCellRange.Text = Convert.ToString(d["amount_contract_reestr"]);
                            posRows++;
                        }
                    }
                    //Сохранение
                    DateTime dateNowFileName = DateTime.Now;
                    string strSaveName = Convert.ToString(dateNowFileName);
                    strSaveName = strSaveName.Replace(':', '-');
                    //Console.WriteLine(storagePath + userSaveName + " " + strSaveName + ".docx");
                    savePath = Properties.Settings.Default.ReportPath + userSaveName + " " + strSaveName + ".docx";
                    wordDocument.SaveAs(savePath);
                    wordDocument.Close();
                    oWord.Quit();
                }
                else
                {
                    //Создание документа Excel
                    Excel.Application oExcel = new Excel.Application();
                    oExcel.Visible = false;
                    if (isVisibleGenerate)
                        oExcel.Visible = true;
                    oExcel.SheetsInNewWorkbook = 1;
                    oExcel.Workbooks.Add();
                    Excel.Workbooks excelWorkBooks = oExcel.Workbooks;
                    Excel.Workbook excelWorkBook = excelWorkBooks[1];
                    excelWorkBook.Saved = false;
                    Excel.Sheets excelSheets = oExcel.Worksheets;
                    Excel.Worksheet excelWorkSheets = excelSheets.get_Item(1);
                    Excel.Range excelCells;
                    //---типо Титульник---
                    //Строка 1
                    excelCells = excelWorkSheets.get_Range("A1", "H1");
                    excelCells.Merge();
                    excelCells.Value2 = "Сводный отчет по договорам";
                    excelCells.WrapText = true;
                    excelCells.Font.Bold = true;
                    excelCells.HorizontalAlignment = Excel.Constants.xlCenter;
                    excelCells.VerticalAlignment = Excel.Constants.xlCenter;
                    excelCells.RowHeight = 34;
                    //Строка 2
                    excelCells = excelWorkSheets.get_Range("A2", "H2");
                    excelCells.Merge();
                    excelCells.Value2 = "по состоянию на " + DateTime.Now.ToShortDateString() + " г.";
                    excelCells.HorizontalAlignment = Excel.Constants.xlRight;
                    excelCells.VerticalAlignment = Excel.Constants.xlCenter;
                    //Заполнения данными шапки
                    int positionInRows = 3;
                    excelCells = excelWorkSheets.get_Range("A" + positionInRows);
                    excelCells.Value2 = "№ дог.";
                    excelCells.ColumnWidth = 25;
                    excelCells = excelWorkSheets.get_Range("B" + positionInRows);
                    excelCells.Value2 = "Контрагент";
                    excelCells.ColumnWidth = 35;
                    excelCells = excelWorkSheets.get_Range("C" + positionInRows);
                    excelCells.Value2 = "Вид договора";
                    excelCells.ColumnWidth = 18;
                    excelCells = excelWorkSheets.get_Range("D" + positionInRows);
                    excelCells.Value2 = "Наименование работ";
                    excelCells.ColumnWidth = 18;
                    excelCells = excelWorkSheets.get_Range("E" + positionInRows);
                    excelCells.Value2 = "ГОЗ, экспорт";
                    excelCells.ColumnWidth = 35;
                    excelCells = excelWorkSheets.get_Range("F" + positionInRows);
                    excelCells.Value2 = "Срок исполнения";
                    excelCells.ColumnWidth = 27;
                    excelCells = excelWorkSheets.get_Range("G" + positionInRows);
                    excelCells.Value2 = "Состояние";
                    excelCells.ColumnWidth = 40;
                    excelCells = excelWorkSheets.get_Range("H" + positionInRows);
                    excelCells.Value2 = "Сумма (оконч.)";
                    excelCells.ColumnWidth = 20;
                    //Настройка выравнивания и автопереноса
                    excelCells = excelWorkSheets.get_Range("A" + positionInRows, "H" + positionInRows);
                    excelCells.WrapText = true;
                    excelCells.HorizontalAlignment = Excel.Constants.xlCenter;
                    excelCells.VerticalAlignment = Excel.Constants.xlCenter;
                    positionInRows++;
                    //Настройки автоширины
                    //excelWorkSheets.Columns[1].EntireColumn.AutoFit();
                    if (json != null)
                        foreach (dynamic d in json)
                        {
                            excelCells = excelWorkSheets.get_Range("A" + positionInRows);
                            excelCells.Value2 = Convert.ToString(d["number_contract"]);
                            excelCells = excelWorkSheets.get_Range("B" + positionInRows);
                            excelCells.Value2 = Convert.ToString(d["name_counterpartie_contract"]);
                            excelCells = excelWorkSheets.get_Range("C" + positionInRows);
                            excelCells.Value2 = Convert.ToString(d["name_view_contract"]);
                            excelCells = excelWorkSheets.get_Range("D" + positionInRows);
                            excelCells.Value2 = Convert.ToString(d["item_contract"]);
                            excelCells = excelWorkSheets.get_Range("E" + positionInRows);
                            excelCells.Value2 = Convert.ToString(d["name_works_goz"]);
                            excelCells = excelWorkSheets.get_Range("F" + positionInRows);
                            excelCells.Value2 = d["date_maturity_reestr"] != null ? Convert.ToString(d["date_maturity_reestr"]) : "";
                            excelCells = excelWorkSheets.get_Range("G" + positionInRows);
                            excelCells.Value2 = Convert.ToString(d["name_state"]);
                            excelCells = excelWorkSheets.get_Range("H" + positionInRows);
                            excelCells.Value2 = Convert.ToString(d["amount_contract_reestr"]);
                            positionInRows++;
                        }
                    //Отображение сетки для таблицы, а также автоперенос и выравнивая по вертикали по центру
                    excelCells = excelWorkSheets.get_Range("A3", "H" + (positionInRows - 1));
                    excelCells.Borders.LineStyle = Excel.XlLineStyle.xlContinuous;
                    excelCells.Borders.Weight = Excel.XlBorderWeight.xlThin;
                    excelCells.WrapText = true;
                    excelCells.VerticalAlignment = Excel.Constants.xlCenter;
                    //Изменение шрифта
                    excelCells = excelWorkSheets.get_Range("A1", "H" + positionInRows);
                    excelCells.Font.Name = "Times New Roman";
                    excelCells.Font.Size = 12;
                    //Сохранение
                    DateTime dateNowFileName = DateTime.Now;
                    string strSaveName = Convert.ToString(dateNowFileName);
                    strSaveName = strSaveName.Replace(':', '-');
                    //Console.Write(storagePath + userSaveName + " " + strSaveName + ".xlsx");
                    savePath = Properties.Settings.Default.ReportPath + userSaveName + " " + strSaveName + ".xlsx";
                    excelWorkBook.SaveAs(savePath);
                    excelWorkBook.Close();
                    oExcel.Quit();
                }
                return ("Завершено!");
            }
            catch (Exception ex)
            {
                return ("error: " + ex.Message);
            }
        }

        //Поступление за период
        static public string SecondDepartmentPrintReport2(string typeDocument, string userSaveName, out string savePath, bool isVisibleGenerate = false, string date_begin = "", string date_end = "")
        {
            savePath = null;

            string command = "http://" + server + "/orc_new/second_department_print_reports?real_name_table=Поступление за период&date_begin=" + date_begin + "&date_end=" + date_end;
            if (userSaveName == "")
                userSaveName = "Отчет";
            //Запрос
            dynamic json = null;
            WebRequest request = WebRequest.Create(command);
            WebResponse response = request.GetResponse();
            using (Stream stream = response.GetResponseStream())
            {
                using (StreamReader reader = new StreamReader(stream))
                {
                    json = JsonConvert.DeserializeObject(reader.ReadToEnd());
                }
            }

            //Создание доков
            try
            {
                if (typeDocument == "Word")
                {
                    //Создаем документ
                    Word.Application oWord = new Word.Application();
                    oWord.Visible = false;
                    if (isVisibleGenerate)
                        oWord.Visible = true;
                    Object template = Type.Missing;
                    Object newTemplate = false;
                    Object documentType = Word.WdNewDocumentType.wdNewBlankDocument;
                    //Object visible = true;
                    oWord.Documents.Add(ref template, ref newTemplate, ref documentType);
                    Word.Document wordDocument = (Word.Document)oWord.Documents.get_Item(1);
                    //Альбомная ориентация
                    wordDocument.PageSetup.Orientation = Word.WdOrientation.wdOrientLandscape;
                    //Добавление шапки в файл
                    wordDocument.Paragraphs.Add();
                    var range = wordDocument.Paragraphs[1].Range;
                    range.Text = "Поступление за период с " + date_begin + " по " + date_end;
                    range.ParagraphFormat.Alignment = Word.WdParagraphAlignment.wdAlignParagraphCenter;
                    wordDocument.Paragraphs.Add();
                    range = wordDocument.Paragraphs[2].Range;
                    range.Text = "по состоянию на " + DateTime.Now.ToShortDateString() + " г.";
                    range.ParagraphFormat.Alignment = Word.WdParagraphAlignment.wdAlignParagraphRight;
                    //Добавление в файл таблицы
                    wordDocument.Paragraphs.Add();
                    Object autoFitBehavior = Word.WdAutoFitBehavior.wdAutoFitWindow;
                    range = wordDocument.Paragraphs[wordDocument.Paragraphs.Count].Range;
                    wordDocument.Tables.Add(range, 1, 21, autoFitBehavior);
                    Word.Table oTable = wordDocument.Tables[1];
                    string[] nameHeaderTable = { "Контрагент", "№ дог.", "№\nнаряда", "Изд-е", "Вид испытания", "Дата\nпоступления", "Кол-\nво", "Дата\nотработки", "Счетн.", "Пристр.", "Прогрев.", "Н/\nСЧ", "Отказ", "Результат", "№\nтелегр.", "Дата\nтелегр.", "№\nотчета", "Дата\nотчета", "№\nакта", "Дата акта", "Сумма\nакта" };
                    for (int i = 0; i < oTable.Columns.Count; i++)
                    {
                        Word.Range wordCellRange = oTable.Cell(1, i + 1).Range;
                        wordCellRange.ParagraphFormat.Alignment = Word.WdParagraphAlignment.wdAlignParagraphCenter;
                        wordCellRange.Text = nameHeaderTable[i];
                    }
                    if (json != null)
                    {
                        int posRows = 2;
                        foreach (dynamic d in json)
                        {
                            oTable.Rows.Add();
                            Word.Range wordCellRange = oTable.Cell(posRows, 1).Range;
                            wordCellRange.Text = Convert.ToString(d["name_counterpartie_contract"]);
                            wordCellRange = oTable.Cell(posRows, 2).Range;
                            wordCellRange.Text = Convert.ToString(d["number_contract"]);
                            wordCellRange = oTable.Cell(posRows, 3).Range;
                            wordCellRange.Text = Convert.ToString(d["number_duty"]);
                            wordCellRange = oTable.Cell(posRows, 4).Range;
                            wordCellRange.Text = Convert.ToString(d["name_element"]);
                            wordCellRange = oTable.Cell(posRows, 5).Range;
                            wordCellRange.Text = Convert.ToString(d["name_view_work_elements"]);
                            wordCellRange = oTable.Cell(posRows, 6).Range;
                            wordCellRange.Text = Convert.ToString(d["date_incoming"]);
                            wordCellRange = oTable.Cell(posRows, 7).Range;
                            wordCellRange.Text = Convert.ToString(d["count_elements"]) + " " + Convert.ToString(d["name_unit"]);
                            wordCellRange = oTable.Cell(posRows, 8).Range;
                            wordCellRange.Text = d["date_worked"] != null ? Convert.ToDateTime(Convert.ToString(d["date_worked"])).ToString("d") : "";
                            wordCellRange = oTable.Cell(posRows, 9).Range;
                            wordCellRange.Text = Convert.ToString(d["countable"]);
                            wordCellRange = oTable.Cell(posRows, 10).Range;
                            wordCellRange.Text = Convert.ToString(d["targeting"]);
                            wordCellRange = oTable.Cell(posRows, 11).Range;
                            wordCellRange.Text = Convert.ToString(d["warm"]);
                            wordCellRange = oTable.Cell(posRows, 12).Range;
                            wordCellRange.Text = Convert.ToString(d["uncountable"]);
                            wordCellRange = oTable.Cell(posRows, 13).Range;
                            wordCellRange.Text = Convert.ToString(d["renouncement"]);
                            wordCellRange = oTable.Cell(posRows, 14).Range;
                            wordCellRange.Text = Convert.ToString(d["name_result"]);
                            wordCellRange = oTable.Cell(posRows, 15).Range;
                            wordCellRange.Text = Convert.ToString(d["number_telegram"]);
                            wordCellRange = oTable.Cell(posRows, 16).Range;
                            wordCellRange.Text = Convert.ToString(d["date_telegram"]);
                            wordCellRange = oTable.Cell(posRows, 17).Range;
                            wordCellRange.Text = Convert.ToString(d["number_report"]);
                            wordCellRange = oTable.Cell(posRows, 18).Range;
                            wordCellRange.Text = Convert.ToString(d["date_report"]);
                            wordCellRange = oTable.Cell(posRows, 19).Range;
                            wordCellRange.Text = Convert.ToString(d["number_act"]);
                            wordCellRange = oTable.Cell(posRows, 20).Range;
                            wordCellRange.Text = Convert.ToString(d["date_act"]);
                            double pr;
                            wordCellRange = oTable.Cell(posRows, 21).Range;
                            wordCellRange.Text = Convert.ToString(double.TryParse(Convert.ToString(d["amount_acts"]), out pr) ? pr.ToString("N2") : d["amount_acts"]);
                            posRows++;
                        }
                    }
                    //Сохранение
                    DateTime dateNowFileName = DateTime.Now;
                    string strSaveName = Convert.ToString(dateNowFileName);
                    strSaveName = strSaveName.Replace(':', '-');
                    //Console.WriteLine(storagePath + userSaveName + " " + strSaveName + ".docx");
                    savePath = Properties.Settings.Default.ReportPath + userSaveName + " " + strSaveName + ".docx";
                    wordDocument.SaveAs(savePath);
                    wordDocument.Close();
                    oWord.Quit();
                }
                else
                {
                    //Создание документа Excel
                    Excel.Application oExcel = new Excel.Application();
                    oExcel.Visible = false;
                    if (isVisibleGenerate)
                        oExcel.Visible = true;
                    oExcel.SheetsInNewWorkbook = 1;
                    oExcel.Workbooks.Add();
                    Excel.Workbooks excelWorkBooks = oExcel.Workbooks;
                    Excel.Workbook excelWorkBook = excelWorkBooks[1];
                    excelWorkBook.Saved = false;
                    Excel.Sheets excelSheets = oExcel.Worksheets;
                    Excel.Worksheet excelWorkSheets = excelSheets.get_Item(1);
                    Excel.Range excelCells;
                    //---типо Титульник---
                    //Строка 1
                    excelCells = excelWorkSheets.get_Range("A1", "U1");
                    excelCells.Merge();
                    excelCells.Value2 = "Поступление за период с " + date_begin + " по " + date_end;
                    excelCells.WrapText = true;
                    excelCells.Font.Bold = true;
                    excelCells.HorizontalAlignment = Excel.Constants.xlCenter;
                    excelCells.VerticalAlignment = Excel.Constants.xlCenter;
                    excelCells.RowHeight = 34;
                    //Строка 2
                    excelCells = excelWorkSheets.get_Range("A2", "U2");
                    excelCells.Merge();
                    excelCells.Value2 = "по состоянию на " + DateTime.Now.ToShortDateString() + " г.";
                    excelCells.HorizontalAlignment = Excel.Constants.xlRight;
                    excelCells.VerticalAlignment = Excel.Constants.xlCenter;
                    //Заполнения даннами шапки
                    int positionInRows = 3;
                    excelCells = excelWorkSheets.get_Range("A" + positionInRows);
                    excelCells.Value2 = "Контрагент";
                    excelCells.ColumnWidth = 25;
                    excelCells = excelWorkSheets.get_Range("B" + positionInRows);
                    excelCells.Value2 = "№ дог.";
                    excelCells.ColumnWidth = 35;
                    excelCells = excelWorkSheets.get_Range("C" + positionInRows);
                    excelCells.Value2 = "№\nнаряда";
                    excelCells.ColumnWidth = 18;
                    excelCells = excelWorkSheets.get_Range("D" + positionInRows);
                    excelCells.Value2 = "Изд-е";
                    excelCells.ColumnWidth = 18;
                    excelCells = excelWorkSheets.get_Range("E" + positionInRows);
                    excelCells.Value2 = "Вид испытания";
                    excelCells.ColumnWidth = 35;
                    excelCells = excelWorkSheets.get_Range("F" + positionInRows);
                    excelCells.Value2 = "Дата\nпоступления";
                    excelCells.ColumnWidth = 27;
                    excelCells = excelWorkSheets.get_Range("G" + positionInRows);
                    excelCells.Value2 = "Кол-\nво";
                    excelCells.ColumnWidth = 40;
                    excelCells = excelWorkSheets.get_Range("H" + positionInRows);
                    excelCells.Value2 = "Дата\nотработки";
                    excelCells.ColumnWidth = 20;
                    excelCells = excelWorkSheets.get_Range("I" + positionInRows);
                    excelCells.Value2 = "Счетн.";
                    excelCells = excelWorkSheets.get_Range("J" + positionInRows);
                    excelCells.Value2 = "Пристр.";
                    excelCells = excelWorkSheets.get_Range("K" + positionInRows);
                    excelCells.Value2 = "Прогрев.";
                    excelCells = excelWorkSheets.get_Range("L" + positionInRows);
                    excelCells.Value2 = "Н/СЧ";
                    excelCells = excelWorkSheets.get_Range("M" + positionInRows);
                    excelCells.Value2 = "Отказ";
                    excelCells = excelWorkSheets.get_Range("N" + positionInRows);
                    excelCells.Value2 = "Результат";
                    excelCells = excelWorkSheets.get_Range("O" + positionInRows);
                    excelCells.Value2 = "№\nтелегр.";
                    excelCells = excelWorkSheets.get_Range("P" + positionInRows);
                    excelCells.Value2 = "Дата\nтелегр.";
                    excelCells = excelWorkSheets.get_Range("Q" + positionInRows);
                    excelCells.Value2 = "№\nотчета";
                    excelCells = excelWorkSheets.get_Range("R" + positionInRows);
                    excelCells.Value2 = "Дата\nотчета";
                    excelCells = excelWorkSheets.get_Range("S" + positionInRows);
                    excelCells.Value2 = "№\nакта";
                    excelCells = excelWorkSheets.get_Range("T" + positionInRows);
                    excelCells.Value2 = "Дата акта";
                    excelCells = excelWorkSheets.get_Range("U" + positionInRows);
                    excelCells.Value2 = "Сумма акта";
                    //Настройка выравнивания и автопереноса
                    excelCells = excelWorkSheets.get_Range("A" + positionInRows, "U" + positionInRows);
                    excelCells.WrapText = true;
                    excelCells.HorizontalAlignment = Excel.Constants.xlCenter;
                    excelCells.VerticalAlignment = Excel.Constants.xlCenter;
                    positionInRows++;
                    //Настройки автоширины
                    //excelWorkSheets.Columns[1].EntireColumn.AutoFit();
                    if (json != null)
                        foreach (dynamic d in json)
                        {
                            excelCells = excelWorkSheets.get_Range("A" + positionInRows);
                            excelCells.Value2 = Convert.ToString(d["name_counterpartie_contract"]);
                            excelCells = excelWorkSheets.get_Range("B" + positionInRows);
                            excelCells.Value2 = Convert.ToString(d["number_contract"]);
                            excelCells = excelWorkSheets.get_Range("C" + positionInRows);
                            excelCells.Value2 = Convert.ToString(d["number_duty"]);
                            excelCells = excelWorkSheets.get_Range("D" + positionInRows);
                            excelCells.Value2 = Convert.ToString(d["name_element"]);
                            excelCells = excelWorkSheets.get_Range("E" + positionInRows);
                            excelCells.Value2 = Convert.ToString(d["name_view_work_elements"]);
                            excelCells = excelWorkSheets.get_Range("F" + positionInRows);
                            excelCells.Value2 = Convert.ToString(d["date_incoming"]);
                            excelCells = excelWorkSheets.get_Range("G" + positionInRows);
                            excelCells.Value2 = Convert.ToString(d["count_elements"]) + " " + Convert.ToString(d["name_unit"]);
                            excelCells = excelWorkSheets.get_Range("H" + positionInRows);
                            excelCells.Value2 = d["date_worked"] != null ? Convert.ToDateTime(Convert.ToString(d["date_worked"])).ToString("d") : "";
                            excelCells = excelWorkSheets.get_Range("I" + positionInRows);
                            excelCells.Value2 = Convert.ToString(d["countable"]);
                            excelCells = excelWorkSheets.get_Range("J" + positionInRows);
                            excelCells.Value2 = Convert.ToString(d["targeting"]);
                            excelCells = excelWorkSheets.get_Range("K" + positionInRows);
                            excelCells.Value2 = Convert.ToString(d["warm"]);
                            excelCells = excelWorkSheets.get_Range("L" + positionInRows);
                            excelCells.Value2 = Convert.ToString(d["uncountable"]);
                            excelCells = excelWorkSheets.get_Range("M" + positionInRows);
                            excelCells.Value2 = Convert.ToString(d["renouncement"]);
                            excelCells = excelWorkSheets.get_Range("N" + positionInRows);
                            excelCells.Value2 = Convert.ToString(d["name_result"]);
                            excelCells = excelWorkSheets.get_Range("O" + positionInRows);
                            excelCells.Value2 = Convert.ToString(d["number_telegram"]);
                            excelCells = excelWorkSheets.get_Range("P" + positionInRows);
                            excelCells.Value2 = Convert.ToString(d["date_telegram"]);
                            excelCells = excelWorkSheets.get_Range("Q" + positionInRows);
                            excelCells.Value2 = Convert.ToString(d["number_report"]);
                            excelCells = excelWorkSheets.get_Range("R" + positionInRows);
                            excelCells.Value2 = Convert.ToString(d["date_report"]);
                            excelCells = excelWorkSheets.get_Range("S" + positionInRows);
                            excelCells.Value2 = Convert.ToString(d["number_act"]);
                            excelCells = excelWorkSheets.get_Range("T" + positionInRows);
                            excelCells.Value2 = Convert.ToString(d["date_act"]);
                            double pr;
                            excelCells = excelWorkSheets.get_Range("U" + positionInRows);
                            excelCells.Value2 = Convert.ToString(double.TryParse(Convert.ToString(d["amount_acts"]), out pr) ? pr.ToString("N2") : d["amount_acts"]);
                            positionInRows++;
                        }
                    //Отображение сетки для таблицы, а также автоперенос и выравнивая по вертикали по центру
                    excelCells = excelWorkSheets.get_Range("A3", "U" + (positionInRows - 1));
                    excelCells.Borders.LineStyle = Excel.XlLineStyle.xlContinuous;
                    excelCells.Borders.Weight = Excel.XlBorderWeight.xlThin;
                    excelCells.WrapText = true;
                    excelCells.VerticalAlignment = Excel.Constants.xlCenter;
                    //Изменение шрифта
                    excelCells = excelWorkSheets.get_Range("A1", "U" + positionInRows);
                    excelCells.Font.Name = "Times New Roman";
                    excelCells.Font.Size = 12;
                    //Сохранение
                    DateTime dateNowFileName = DateTime.Now;
                    string strSaveName = Convert.ToString(dateNowFileName);
                    strSaveName = strSaveName.Replace(':', '-');
                    //Console.Write(storagePath + userSaveName + " " + strSaveName + ".xlsx");
                    savePath = Properties.Settings.Default.ReportPath + userSaveName + " " + strSaveName + ".xlsx";
                    excelWorkBook.SaveAs(savePath);
                    excelWorkBook.Close();
                    oExcel.Quit();
                }
                return ("Завершено!");
            }
            catch (Exception ex)
            {
                return ("error: " + ex.Message);
            }
        }

        //Выполнение за период
        static public string SecondDepartmentPrintReport3(string typeDocument, string userSaveName, out string savePath, bool isVisibleGenerate = false, string typeReport = "Общий", string counterpartieID = "", string date_begin = "", string date_end = "")
        {
            savePath = null;

            string command = "http://" + server + "/orc_new/second_department_print_reports?real_name_table=Выполнение за период&view_complete_report=" + typeReport + "&counterpartie=" + counterpartieID + "&date_begin=" + date_begin + "&date_end=" + date_end;
            if (userSaveName == "")
                userSaveName = "Отчет";
            //Запрос
            dynamic json = null;
            WebRequest request = WebRequest.Create(command);
            WebResponse response = request.GetResponse();
            using (Stream stream = response.GetResponseStream())
            {
                using (StreamReader reader = new StreamReader(stream))
                {
                    json = JsonConvert.DeserializeObject(reader.ReadToEnd());
                }
            }

            //Создание доков
            try
            {
                if (typeDocument == "Word")
                {
                    //Создаем документ
                    Word.Application oWord = new Word.Application();
                    oWord.Visible = false;
                    if (isVisibleGenerate)
                        oWord.Visible = true;
                    Object template = Type.Missing;
                    Object newTemplate = false;
                    Object documentType = Word.WdNewDocumentType.wdNewBlankDocument;
                    //Object visible = true;
                    oWord.Documents.Add(ref template, ref newTemplate, ref documentType);
                    Word.Document wordDocument = (Word.Document)oWord.Documents.get_Item(1);
                    //Альбомная ориентация
                    wordDocument.PageSetup.Orientation = Word.WdOrientation.wdOrientLandscape;
                    //Добавление шапки в файл
                    wordDocument.Paragraphs.Add();
                    var range = wordDocument.Paragraphs[1].Range;
                    switch (typeReport)
                    {
                        case "Общий":
                            range.Text = "Выполнение за период с " + date_begin + " по " + date_end;
                            break;
                        case "Испытания":
                            range.Text = "Выполнение за период (испытания) с " + date_begin + " по " + date_end;
                            break;
                        case "Сборка":
                            range.Text = "Выполнение за период (сборка) с " + date_begin + " по " + date_end;
                            break;
                        case "Услуги":
                            range.Text = "Выполнение за период (услуги) с " + date_begin + " по " + date_end;
                            break;
                    }
                    range.ParagraphFormat.Alignment = Word.WdParagraphAlignment.wdAlignParagraphCenter;
                    wordDocument.Paragraphs.Add();
                    range = wordDocument.Paragraphs[2].Range;
                    range.Text = "по состоянию на " + DateTime.Now.ToShortDateString() + " г.";
                    range.ParagraphFormat.Alignment = Word.WdParagraphAlignment.wdAlignParagraphRight;
                    //Добавление в файл таблицы
                    string[] nameHeaderTable = { "Контрагент", "№ дог.", "№ наряда", "Изд-е", "Вид испытания/работы", "Кол-во", "Дата отработки/сдачи", "№ акта", "Дата акта", "Сумма акта" };
                    switch (typeReport)
                    {
                        case "Испытания":
                            nameHeaderTable = new string[]{ "Контрагент", "№ дог.", "№\nнаряда", "Изд-е", "Вид испытания", "Дата\nпоступления", "Кол-\nво", "Дата\nотработки", "Счетн.", "Пристр.", "Прогрев.", "Н/\nСЧ", "Отказ", "Результат", "№\nтелегр.", "Дата\nтелегр.", "№\nотчета", "Дата\nотчета", "№\nакта", "Дата акта", "Сумма\nакта" };
                            break;
                        case "Сборка":
                            nameHeaderTable = new string[] { "Контрагент", "№ дог.", "№ наряда", "Изд-е", "Калибр", "Номер партии", "Вид работ", "Кол-во", "Дата сдачи", "№ формуляра", "Дата формуляра", "№ уведомления", "Дата уведомления", "№ акта", "Дата акта", "Сумма акта" };
                            break;
                        case "Услуги":
                            nameHeaderTable = new string[] { "Контрагент", "№ дог.", "№ наряда", "Вид работ", "Дата отработки", "№ отчёта-справки", "Дата отчёта-справки", "№ акта", "Дата акта", "Сумма акта" };
                            break;
                    }
                    wordDocument.Paragraphs.Add();
                    Object autoFitBehavior = Word.WdAutoFitBehavior.wdAutoFitWindow;
                    range = wordDocument.Paragraphs[wordDocument.Paragraphs.Count].Range;
                    wordDocument.Tables.Add(range, 1, nameHeaderTable.Length, autoFitBehavior);
                    Word.Table oTable = wordDocument.Tables[1];
                    for (int i = 0; i < oTable.Columns.Count; i++)
                    {
                        Word.Range wordCellRange = oTable.Cell(1, i + 1).Range;
                        wordCellRange.ParagraphFormat.Alignment = Word.WdParagraphAlignment.wdAlignParagraphCenter;
                        wordCellRange.Text = nameHeaderTable[i];
                    }
                    if (json != null)
                    {
                        int posRows = 2;
                        //Для итогов в испытаниях
                        int countDuty = 0;
                        int countCountable = 0;
                        int countTargeting = 0;
                        int countWarm = 0;
                        int countUncountable = 0;
                        int countRenouncement = 0;
                        double fullAmount = 0;
                        foreach (dynamic d in json)
                        {
                            countDuty++;
                            oTable.Rows.Add();
                            Word.Range wordCellRange = oTable.Cell(posRows, 1).Range;
                            wordCellRange.Text = Convert.ToString(d["name_counterpartie_contract"]);
                            wordCellRange = oTable.Cell(posRows, 2).Range;
                            wordCellRange.Text = Convert.ToString(d["number_contract"]);
                            wordCellRange = oTable.Cell(posRows, 3).Range;
                            wordCellRange.Text = Convert.ToString(d["number_duty"]);
                            switch (typeReport)
                            {
                                case "Общий":
                                    wordCellRange = oTable.Cell(posRows, 4).Range;
                                    if (Convert.ToString(d["type_contract"]) == "isp" || Convert.ToString(d["type_contract"]) == "sb")
                                        wordCellRange.Text = Convert.ToString(d["name_element"]);
                                    wordCellRange = oTable.Cell(posRows, 5).Range;
                                    if (Convert.ToString(d["type_contract"]) == "isp" || Convert.ToString(d["type_contract"]) == "sb")
                                        wordCellRange.Text = Convert.ToString(d["name_view_work_elements"]);
                                    else
                                        wordCellRange.Text = Convert.ToString(d["item_contract"]);
                                    wordCellRange = oTable.Cell(posRows, 6).Range;
                                    if (Convert.ToString(d["type_contract"]) == "isp" || Convert.ToString(d["type_contract"]) == "sb")
                                        wordCellRange.Text = Convert.ToString(d["count_elements"]) + " " + Convert.ToString(d["name_unit"]);
                                    wordCellRange = oTable.Cell(posRows, 7).Range;
                                    wordCellRange.Text = d["date_worked"] != null ? Convert.ToDateTime(Convert.ToString(d["date_worked"])).ToString("d") : "";
                                    break;
                                case "Испытания":
                                    wordCellRange = oTable.Cell(posRows, 4).Range;
                                    wordCellRange.Text = Convert.ToString(d["name_element"]);
                                    wordCellRange = oTable.Cell(posRows, 5).Range;
                                    wordCellRange.Text = Convert.ToString(d["name_view_work_elements"]);
                                    wordCellRange = oTable.Cell(posRows, 6).Range;
                                    wordCellRange.Text = Convert.ToString(d["date_incoming"]);
                                    wordCellRange = oTable.Cell(posRows, 7).Range;
                                    wordCellRange.Text = Convert.ToString(d["count_elements"]) + " " + Convert.ToString(d["name_unit"]);
                                    wordCellRange = oTable.Cell(posRows, 8).Range;
                                    wordCellRange.Text = d["date_worked"] != null ? Convert.ToDateTime(Convert.ToString(d["date_worked"])).ToString("d") : "";
                                    wordCellRange = oTable.Cell(posRows, 9).Range;
                                    wordCellRange.Text = Convert.ToString(d["countable"]);
                                    int tryInt = 0;
                                    int.TryParse(Convert.ToString(d["countable"]), out tryInt);
                                    countCountable += tryInt;
                                    wordCellRange = oTable.Cell(posRows, 10).Range;
                                    wordCellRange.Text = Convert.ToString(d["targeting"]);
                                    tryInt = 0;
                                    int.TryParse(Convert.ToString(d["targeting"]), out tryInt);
                                    countTargeting += tryInt;
                                    wordCellRange = oTable.Cell(posRows, 11).Range;
                                    wordCellRange.Text = Convert.ToString(d["warm"]);
                                    tryInt = 0;
                                    int.TryParse(Convert.ToString(d["warm"]), out tryInt);
                                    countWarm += tryInt;
                                    wordCellRange = oTable.Cell(posRows, 12).Range;
                                    wordCellRange.Text = Convert.ToString(d["uncountable"]);
                                    tryInt = 0;
                                    int.TryParse(Convert.ToString(d["uncountable"]), out tryInt);
                                    countUncountable += tryInt;
                                    wordCellRange = oTable.Cell(posRows, 13).Range;
                                    wordCellRange.Text = Convert.ToString(d["renouncement"]);
                                    tryInt = 0;
                                    int.TryParse(Convert.ToString(d["renouncement"]), out tryInt);
                                    countRenouncement += tryInt;
                                    break;
                                case "Сборка":
                                    wordCellRange = oTable.Cell(posRows, 4).Range;
                                    wordCellRange.Text = Convert.ToString(d["name_element"]);
                                    wordCellRange = oTable.Cell(posRows, 5).Range;
                                    wordCellRange.Text = Convert.ToString(d["name_caliber"]);
                                    wordCellRange = oTable.Cell(posRows, 6).Range;
                                    wordCellRange.Text = Convert.ToString(d["number_party"]);
                                    wordCellRange = oTable.Cell(posRows, 7).Range;
                                    wordCellRange.Text = Convert.ToString(d["name_view_work_elements"]);
                                    wordCellRange = oTable.Cell(posRows, 8).Range;
                                    wordCellRange.Text = Convert.ToString(d["count_elements"]) + " " + Convert.ToString(d["name_unit"]);
                                    wordCellRange = oTable.Cell(posRows, 9).Range;
                                    wordCellRange.Text = d["date_worked"] != null ? Convert.ToDateTime(Convert.ToString(d["date_worked"])).ToString("d") : "";
                                    wordCellRange = oTable.Cell(posRows, 10).Range;
                                    wordCellRange.Text = Convert.ToString(d["number_logbook"]);
                                    wordCellRange = oTable.Cell(posRows, 11).Range;
                                    wordCellRange.Text = Convert.ToString(d["date_logbook"]);
                                    wordCellRange = oTable.Cell(posRows, 12).Range;
                                    wordCellRange.Text = Convert.ToString(d["number_notification"]);
                                    wordCellRange = oTable.Cell(posRows, 13).Range;
                                    wordCellRange.Text = Convert.ToString(d["date_notification"]);
                                    break;
                                case "Услуги":
                                    wordCellRange = oTable.Cell(posRows, 4).Range;
                                    wordCellRange.Text = Convert.ToString(d["item_contract"]);
                                    wordCellRange = oTable.Cell(posRows, 5).Range;
                                    wordCellRange.Text = d["date_worked"] != null ? Convert.ToDateTime(Convert.ToString(d["date_worked"])).ToString("d") : "";
                                    wordCellRange = oTable.Cell(posRows, 6).Range;
                                    wordCellRange.Text = Convert.ToString(d["number_help_report"]);
                                    wordCellRange = oTable.Cell(posRows, 7).Range;
                                    wordCellRange.Text = Convert.ToString(d["date_help_report"]);
                                    break;
                            }
                            if (typeReport == "Испытания")
                            {
                                wordCellRange = oTable.Cell(posRows, 14).Range;
                                wordCellRange.Text = Convert.ToString(d["name_result"]);
                                wordCellRange = oTable.Cell(posRows, 15).Range;
                                wordCellRange.Text = Convert.ToString(d["number_telegram"]);
                                wordCellRange = oTable.Cell(posRows, 16).Range;
                                wordCellRange.Text = Convert.ToString(d["date_telegram"]);
                                wordCellRange = oTable.Cell(posRows, 17).Range;
                                wordCellRange.Text = Convert.ToString(d["number_report"]);
                                wordCellRange = oTable.Cell(posRows, 18).Range;
                                wordCellRange.Text = Convert.ToString(d["date_report"]);
                            }
                            switch (typeReport)
                            {
                                case "Общий":
                                    wordCellRange = oTable.Cell(posRows, 8).Range;
                                    break;
                                case "Испытания":
                                    wordCellRange = oTable.Cell(posRows, 19).Range;
                                    break;
                                case "Сборка":
                                    wordCellRange = oTable.Cell(posRows, 14).Range;
                                    break;
                                case "Услуги":
                                    wordCellRange = oTable.Cell(posRows, 8).Range;
                                    break;
                            }
                            wordCellRange.Text = Convert.ToString(d["number_act"]);
                            switch (typeReport)
                            {
                                case "Общий":
                                    wordCellRange = oTable.Cell(posRows, 9).Range;
                                    break;
                                case "Испытания":
                                    wordCellRange = oTable.Cell(posRows, 20).Range;
                                    break;
                                case "Сборка":
                                    wordCellRange = oTable.Cell(posRows, 15).Range;
                                    break;
                                case "Услуги":
                                    wordCellRange = oTable.Cell(posRows, 9).Range;
                                    break;
                            }
                            wordCellRange.Text = Convert.ToString(d["date_act"]);
                            double pr;
                            switch (typeReport)
                            {
                                case "Общий":
                                    wordCellRange = oTable.Cell(posRows, 10).Range;
                                    break;
                                case "Испытания":
                                    wordCellRange = oTable.Cell(posRows, 21).Range;
                                    break;
                                case "Сборка":
                                    wordCellRange = oTable.Cell(posRows, 16).Range;
                                    break;
                                case "Услуги":
                                    wordCellRange = oTable.Cell(posRows, 10).Range;
                                    break;
                            }
                            wordCellRange.Text = Convert.ToString(double.TryParse(Convert.ToString(d["amount_acts"]), out pr) ? pr.ToString("N2") : d["amount_acts"]);
                            fullAmount += pr;
                            posRows++;
                        }
                        if (typeReport == "Испытания")
                        {
                            Word.Range wordCellRange;
                            oTable.Rows.Add();
                            oTable.Cell(posRows, 1).Merge(oTable.Cell(posRows, 2));
                            wordCellRange = oTable.Cell(posRows, 1).Range;
                            wordCellRange.Text = "Всего нарядов: ";
                            wordCellRange = oTable.Cell(posRows, 2).Range;
                            wordCellRange.Text = Convert.ToString(countDuty);
                            oTable.Cell(posRows, 3).Merge(oTable.Cell(posRows, 7));
                            wordCellRange = oTable.Cell(posRows, 4).Range;
                            wordCellRange.Text = Convert.ToString(countCountable);
                            wordCellRange = oTable.Cell(posRows, 5).Range;
                            wordCellRange.Text = Convert.ToString(countTargeting);
                            wordCellRange = oTable.Cell(posRows, 6).Range;
                            wordCellRange.Text = Convert.ToString(countWarm);
                            wordCellRange = oTable.Cell(posRows, 7).Range;
                            wordCellRange.Text = Convert.ToString(countUncountable);
                            wordCellRange = oTable.Cell(posRows, 8).Range;
                            wordCellRange.Text = Convert.ToString(countRenouncement);
                            oTable.Cell(posRows, 9).Merge(oTable.Cell(posRows, 15));
                            wordCellRange = oTable.Cell(posRows, 10).Range;
                            double pr = 0;
                            wordCellRange.Text = Convert.ToString(double.TryParse(Convert.ToString(fullAmount), out pr) ? pr.ToString("N2") : fullAmount.ToString());
                            posRows++;
                        }
                    }
                    //Сохранение
                    DateTime dateNowFileName = DateTime.Now;
                    string strSaveName = Convert.ToString(dateNowFileName);
                    strSaveName = strSaveName.Replace(':', '-');
                    //Console.WriteLine(storagePath + userSaveName + " " + strSaveName + ".docx");
                    savePath = Properties.Settings.Default.ReportPath + userSaveName + " " + strSaveName + ".docx";
                    wordDocument.SaveAs(savePath);
                    wordDocument.Close();
                    oWord.Quit();
                }
                else
                {
                    //Создание документа Excel
                    Excel.Application oExcel = new Excel.Application();
                    oExcel.Visible = false;
                    if (isVisibleGenerate)
                        oExcel.Visible = true;
                    oExcel.SheetsInNewWorkbook = 1;
                    oExcel.Workbooks.Add();
                    Excel.Workbooks excelWorkBooks = oExcel.Workbooks;
                    Excel.Workbook excelWorkBook = excelWorkBooks[1];
                    excelWorkBook.Saved = false;
                    Excel.Sheets excelSheets = oExcel.Worksheets;
                    Excel.Worksheet excelWorkSheets = excelSheets.get_Item(1);
                    Excel.Range excelCells = excelWorkSheets.get_Range("A1", "A1"); ;
                    //---типо Титульник---
                    //Строка 1
                    switch (typeReport)
                    {
                        case "Испытания":
                            excelCells = excelWorkSheets.get_Range("A1", "U1");
                            break;
                        case "Сборка":
                            excelCells = excelWorkSheets.get_Range("A1", "Q1");
                            break;
                        case "Общий":
                        case "Услуги":
                            excelCells = excelWorkSheets.get_Range("A1", "J1");
                            break;
                    }
                    excelCells.Merge();
                    switch (typeReport)
                    {
                        case "Общий":
                            excelCells.Value2 = "Выполнение за период с " + date_begin + " по " + date_end;
                            break;
                        case "Испытания":
                            excelCells.Value2 = "Выполнение за период (испытания) с " + date_begin + " по " + date_end;
                            break;
                        case "Сборка":
                            excelCells.Value2 = "Выполнение за период (сборка) с " + date_begin + " по " + date_end;
                            break;
                        case "Услуги":
                            excelCells.Value2 = "Выполнение за период (услуги) с " + date_begin + " по " + date_end;
                            break;
                    }
                    excelCells.WrapText = true;
                    excelCells.Font.Bold = true;
                    excelCells.HorizontalAlignment = Excel.Constants.xlCenter;
                    excelCells.VerticalAlignment = Excel.Constants.xlCenter;
                    excelCells.RowHeight = 34;
                    //Строка 2
                    switch (typeReport)
                    {
                        case "Испытания":
                            excelCells = excelWorkSheets.get_Range("A2", "U2");
                            break;
                        case "Сборка":
                            excelCells = excelWorkSheets.get_Range("A2", "Q2");
                            break;
                        case "Общий":
                        case "Услуги":
                            excelCells = excelWorkSheets.get_Range("A2", "J2");
                            break;
                    }
                    excelCells.Merge();
                    excelCells.Value2 = "по состоянию на " + DateTime.Now.ToShortDateString() + " г.";
                    excelCells.HorizontalAlignment = Excel.Constants.xlRight;
                    excelCells.VerticalAlignment = Excel.Constants.xlCenter;
                    //Заполнения даннами шапки
                    int positionInRows = 3;
                    excelCells = excelWorkSheets.get_Range("A" + positionInRows);
                    excelCells.Value2 = "Контрагент";
                    excelCells.ColumnWidth = 25;
                    excelCells = excelWorkSheets.get_Range("B" + positionInRows);
                    excelCells.Value2 = "№ дог.";
                    excelCells.ColumnWidth = 25;
                    excelCells = excelWorkSheets.get_Range("C" + positionInRows);
                    excelCells.Value2 = "№ наряда";
                    excelCells.ColumnWidth = 18;
                    switch (typeReport)
                    {
                        case "Общий":
                            excelCells = excelWorkSheets.get_Range("D" + positionInRows);
                            excelCells.Value2 = "Изд-е";
                            excelCells.ColumnWidth = 18;
                            excelCells = excelWorkSheets.get_Range("E" + positionInRows);
                            excelCells.Value2 = "Вид испытания/работы";
                            excelCells.ColumnWidth = 25;
                            excelCells = excelWorkSheets.get_Range("F" + positionInRows);
                            excelCells.Value2 = "Кол-во";
                            excelCells.ColumnWidth = 27;
                            excelCells = excelWorkSheets.get_Range("G" + positionInRows);
                            excelCells.Value2 = "Дата отработки/сдачи";
                            excelCells.ColumnWidth = 40;
                            break;
                        case "Испытания":
                            excelCells = excelWorkSheets.get_Range("D" + positionInRows);
                            excelCells.Value2 = "Изд-е";
                            excelCells.ColumnWidth = 18;
                            excelCells = excelWorkSheets.get_Range("E" + positionInRows);
                            excelCells.Value2 = "Вид испытания";
                            excelCells.ColumnWidth = 35;
                            excelCells = excelWorkSheets.get_Range("F" + positionInRows);
                            excelCells.Value2 = "Дата поступления";
                            excelCells.ColumnWidth = 27;
                            excelCells = excelWorkSheets.get_Range("G" + positionInRows);
                            excelCells.Value2 = "Кол-во";
                            excelCells.ColumnWidth = 40;
                            excelCells = excelWorkSheets.get_Range("H" + positionInRows);
                            excelCells.Value2 = "Дата отработки";
                            excelCells.ColumnWidth = 20;
                            excelCells = excelWorkSheets.get_Range("I" + positionInRows);
                            excelCells.Value2 = "Счетн.";
                            excelCells = excelWorkSheets.get_Range("J" + positionInRows);
                            excelCells.Value2 = "Пристр.";
                            excelCells = excelWorkSheets.get_Range("K" + positionInRows);
                            excelCells.Value2 = "Прогрев.";
                            excelCells = excelWorkSheets.get_Range("L" + positionInRows);
                            excelCells.Value2 = "Н/СЧ";
                            excelCells = excelWorkSheets.get_Range("M" + positionInRows);
                            excelCells.Value2 = "Отказ";
                            excelCells = excelWorkSheets.get_Range("N" + positionInRows);
                            break;
                        case "Сборка":
                            excelCells = excelWorkSheets.get_Range("D" + positionInRows);
                            excelCells.Value2 = "Изд-е";
                            excelCells.ColumnWidth = 18;
                            excelCells = excelWorkSheets.get_Range("E" + positionInRows);
                            excelCells.Value2 = "Калибр";
                            excelCells.ColumnWidth = 25;
                            excelCells = excelWorkSheets.get_Range("F" + positionInRows);
                            excelCells.Value2 = "Номер партии";
                            excelCells.ColumnWidth = 27;
                            excelCells = excelWorkSheets.get_Range("G" + positionInRows);
                            excelCells.Value2 = "Вид работ";
                            excelCells.ColumnWidth = 40;
                            excelCells = excelWorkSheets.get_Range("H" + positionInRows);
                            excelCells.Value2 = "Кол-во";
                            excelCells.ColumnWidth = 20;
                            excelCells = excelWorkSheets.get_Range("I" + positionInRows);
                            excelCells.Value2 = "Дата сдачи";
                            excelCells = excelWorkSheets.get_Range("J" + positionInRows);
                            excelCells.Value2 = "№ формуляра";
                            excelCells = excelWorkSheets.get_Range("K" + positionInRows);
                            excelCells.Value2 = "Дата формуляра";
                            excelCells = excelWorkSheets.get_Range("L" + positionInRows);
                            excelCells.Value2 = "№ уведомления";
                            excelCells = excelWorkSheets.get_Range("M" + positionInRows);
                            excelCells.Value2 = "Дата уведомления";
                            excelCells = excelWorkSheets.get_Range("N" + positionInRows);
                            break;
                        case "Услуги":
                            excelCells = excelWorkSheets.get_Range("D" + positionInRows);
                            excelCells.Value2 = "Вид работ";
                            excelCells.ColumnWidth = 18;
                            excelCells = excelWorkSheets.get_Range("E" + positionInRows);
                            excelCells.Value2 = "Дата отработки";
                            excelCells.ColumnWidth = 25;
                            excelCells = excelWorkSheets.get_Range("F" + positionInRows);
                            excelCells.Value2 = "№ отчёта-справки";
                            excelCells.ColumnWidth = 27;
                            excelCells = excelWorkSheets.get_Range("G" + positionInRows);
                            excelCells.Value2 = "Дата отчёта-справки";
                            excelCells.ColumnWidth = 40;
                            break;
                    }
                    if (typeReport == "Испытания")
                    {
                        excelCells.Value2 = "Результат";
                        excelCells = excelWorkSheets.get_Range("O" + positionInRows);
                        excelCells.Value2 = "№ телегр.";
                        excelCells = excelWorkSheets.get_Range("P" + positionInRows);
                        excelCells.Value2 = "Дата телегр.";
                        excelCells = excelWorkSheets.get_Range("Q" + positionInRows);
                        excelCells.Value2 = "№ отчета";
                        excelCells = excelWorkSheets.get_Range("R" + positionInRows);
                        excelCells.Value2 = "Дата отчета";
                    }
                    switch (typeReport)
                    {
                        case "Испытания":
                            excelCells = excelWorkSheets.get_Range("S" + positionInRows);
                            break;
                        case "Сборка":
                            excelCells = excelWorkSheets.get_Range("O" + positionInRows);
                            break;
                        case "Общий":
                        case "Услуги":
                            excelCells = excelWorkSheets.get_Range("H" + positionInRows);
                            break;
                    }
                    excelCells.Value2 = "№ акта";
                    switch (typeReport)
                    {
                        case "Испытания":
                            excelCells = excelWorkSheets.get_Range("T" + positionInRows);
                            break;
                        case "Сборка":
                            excelCells = excelWorkSheets.get_Range("P" + positionInRows);
                            break;
                        case "Общий":
                        case "Услуги":
                            excelCells = excelWorkSheets.get_Range("I" + positionInRows);
                            break;
                    }
                    excelCells.ColumnWidth = 25;
                    excelCells.Value2 = "Дата акта";
                    switch (typeReport)
                    {
                        case "Испытания":
                            excelCells = excelWorkSheets.get_Range("U" + positionInRows);
                            break;
                        case "Сборка":
                            excelCells = excelWorkSheets.get_Range("Q" + positionInRows);
                            break;
                        case "Общий":
                        case "Услуги":
                            excelCells = excelWorkSheets.get_Range("J" + positionInRows);
                            break;
                    }
                    excelCells.ColumnWidth = 25;
                    excelCells.Value2 = "Сумма акта";
                    //Настройка выравнивания и автопереноса
                    switch (typeReport)
                    {
                        case "Испытания":
                            excelCells = excelWorkSheets.get_Range("A" + positionInRows, "U" + positionInRows);
                            break;
                        case "Сборка":
                            excelCells = excelWorkSheets.get_Range("A" + positionInRows, "Q" + positionInRows);
                            break;
                        case "Общий":
                        case "Услуги":
                            excelCells = excelWorkSheets.get_Range("A" + positionInRows, "J" + positionInRows);
                            break;
                    }
                    excelCells.WrapText = true;
                    excelCells.HorizontalAlignment = Excel.Constants.xlCenter;
                    excelCells.VerticalAlignment = Excel.Constants.xlCenter;
                    positionInRows++;
                    //Для итогов в испытаниях
                    int countDuty = 0;
                    int countCountable = 0;
                    int countTargeting = 0;
                    int countWarm = 0;
                    int countUncountable = 0;
                    int countRenouncement = 0;
                    double fullAmount = 0;
                    //Настройки автоширины
                    //excelWorkSheets.Columns[1].EntireColumn.AutoFit();
                    if (json != null)
                        foreach (dynamic d in json)
                        {
                            countDuty++;
                            excelCells = excelWorkSheets.get_Range("A" + positionInRows);
                            excelCells.Value2 = Convert.ToString(d["name_counterpartie_contract"]);
                            excelCells = excelWorkSheets.get_Range("B" + positionInRows);
                            excelCells.Value2 = Convert.ToString(d["number_contract"]);
                            excelCells = excelWorkSheets.get_Range("C" + positionInRows);
                            excelCells.Value2 = Convert.ToString(d["number_duty"]);
                            switch (typeReport)
                            {
                                case "Общий":
                                    excelCells = excelWorkSheets.get_Range("D" + positionInRows);
                                    if (Convert.ToString(d["type_contract"]) == "isp" || Convert.ToString(d["type_contract"]) == "sb")
                                        excelCells.Value2 = Convert.ToString(d["name_element"]);
                                    excelCells = excelWorkSheets.get_Range("E" + positionInRows);
                                    if (Convert.ToString(d["type_contract"]) == "isp" || Convert.ToString(d["type_contract"]) == "sb")
                                        excelCells.Value2 = Convert.ToString(d["name_view_work_elements"]);
                                    else
                                        excelCells.Value2 = Convert.ToString(d["item_contract"]);
                                    excelCells = excelWorkSheets.get_Range("F" + positionInRows);
                                    if (Convert.ToString(d["type_contract"]) == "isp" || Convert.ToString(d["type_contract"]) == "sb")
                                        excelCells.Value2 = Convert.ToString(d["count_elements"]) + " " + Convert.ToString(d["name_unit"]);
                                    excelCells = excelWorkSheets.get_Range("G" + positionInRows);
                                    excelCells.Value2 = d["date_worked"] != null ? Convert.ToDateTime(Convert.ToString(d["date_worked"])).ToString("d") : "";
                                    break;
                                case "Испытания":
                                    excelCells = excelWorkSheets.get_Range("D" + positionInRows);
                                    excelCells.Value2 = Convert.ToString(d["name_element"]);
                                    excelCells = excelWorkSheets.get_Range("E" + positionInRows);
                                    excelCells.Value2 = Convert.ToString(d["name_view_work_elements"]);
                                    excelCells = excelWorkSheets.get_Range("F" + positionInRows);
                                    excelCells.Value2 = Convert.ToString(d["date_incoming"]);
                                    excelCells = excelWorkSheets.get_Range("G" + positionInRows);
                                    excelCells.Value2 = Convert.ToString(d["count_elements"]) + " " + Convert.ToString(d["name_unit"]);
                                    excelCells = excelWorkSheets.get_Range("H" + positionInRows);
                                    excelCells.Value2 = d["date_worked"] != null ? Convert.ToDateTime(Convert.ToString(d["date_worked"])).ToString("d") : "";
                                    excelCells = excelWorkSheets.get_Range("I" + positionInRows);
                                    excelCells.Value2 = Convert.ToString(d["countable"]);
                                    int tryInt = 0;
                                    int.TryParse(Convert.ToString(d["countable"]), out tryInt);
                                    countCountable += tryInt;
                                    excelCells = excelWorkSheets.get_Range("J" + positionInRows);
                                    excelCells.Value2 = Convert.ToString(d["targeting"]);
                                    tryInt = 0;
                                    int.TryParse(Convert.ToString(d["targeting"]), out tryInt);
                                    countTargeting += tryInt;
                                    excelCells = excelWorkSheets.get_Range("K" + positionInRows);
                                    excelCells.Value2 = Convert.ToString(d["warm"]);
                                    tryInt = 0;
                                    int.TryParse(Convert.ToString(d["warm"]), out tryInt);
                                    countWarm += tryInt;
                                    excelCells = excelWorkSheets.get_Range("L" + positionInRows);
                                    excelCells.Value2 = Convert.ToString(d["uncountable"]);
                                    tryInt = 0;
                                    int.TryParse(Convert.ToString(d["uncountable"]), out tryInt);
                                    countUncountable += tryInt;
                                    excelCells = excelWorkSheets.get_Range("M" + positionInRows);
                                    excelCells.Value2 = Convert.ToString(d["renouncement"]);
                                    tryInt = 0;
                                    int.TryParse(Convert.ToString(d["renouncement"]), out tryInt);
                                    countRenouncement += tryInt;
                                    break;
                                case "Сборка":
                                    excelCells = excelWorkSheets.get_Range("D" + positionInRows);
                                    excelCells.Value2 = Convert.ToString(d["name_element"]);
                                    excelCells = excelWorkSheets.get_Range("E" + positionInRows);
                                    excelCells.Value2 = Convert.ToString(d["name_caliber"]);
                                    excelCells = excelWorkSheets.get_Range("F" + positionInRows);
                                    excelCells.Value2 = Convert.ToString(d["number_party"]);
                                    excelCells = excelWorkSheets.get_Range("G" + positionInRows);
                                    excelCells.Value2 = Convert.ToString(d["name_view_work_elements"]);
                                    excelCells = excelWorkSheets.get_Range("H" + positionInRows);
                                    excelCells.Value2 = Convert.ToString(d["count_elements"]) + " " + Convert.ToString(d["name_unit"]);
                                    excelCells = excelWorkSheets.get_Range("I" + positionInRows);
                                    excelCells.Value2 = d["date_worked"] != null ? Convert.ToDateTime(Convert.ToString(d["date_worked"])).ToString("d") : "";
                                    excelCells = excelWorkSheets.get_Range("J" + positionInRows);
                                    excelCells.Value2 = Convert.ToString(d["number_logbook"]);
                                    excelCells = excelWorkSheets.get_Range("K" + positionInRows);
                                    excelCells.Value2 = Convert.ToString(d["date_logbook"]);
                                    excelCells = excelWorkSheets.get_Range("L" + positionInRows);
                                    excelCells.Value2 = Convert.ToString(d["number_notification"]);
                                    excelCells = excelWorkSheets.get_Range("M" + positionInRows);
                                    excelCells.Value2 = Convert.ToString(d["date_notification"]);
                                    break;
                                case "Услуги":
                                    excelCells = excelWorkSheets.get_Range("D" + positionInRows);
                                    excelCells.Value2 = Convert.ToString(d["item_contract"]);
                                    excelCells = excelWorkSheets.get_Range("E" + positionInRows);
                                    excelCells.Value2 = d["date_worked"] != null ? Convert.ToDateTime(Convert.ToString(d["date_worked"])).ToString("d") : "";
                                    excelCells = excelWorkSheets.get_Range("F" + positionInRows);
                                    excelCells.Value2 = Convert.ToString(d["number_help_report"]);
                                    excelCells = excelWorkSheets.get_Range("G" + positionInRows);
                                    excelCells.Value2 = Convert.ToString(d["date_help_report"]);
                                    break;
                            }
                            if (typeReport == "Испытания")
                            {
                                excelCells = excelWorkSheets.get_Range("N" + positionInRows);
                                excelCells.Value2 = Convert.ToString(d["name_result"]);
                                excelCells = excelWorkSheets.get_Range("O" + positionInRows);
                                excelCells.Value2 = Convert.ToString(d["number_telegram"]);
                                excelCells = excelWorkSheets.get_Range("P" + positionInRows);
                                excelCells.Value2 = Convert.ToString(d["date_telegram"]);
                                excelCells = excelWorkSheets.get_Range("Q" + positionInRows);
                                excelCells.Value2 = Convert.ToString(d["number_report"]);
                                excelCells = excelWorkSheets.get_Range("R" + positionInRows);
                                excelCells.Value2 = Convert.ToString(d["date_report"]);
                            }
                            switch (typeReport)
                            {
                                case "Испытания":
                                    excelCells = excelWorkSheets.get_Range("S" + positionInRows);
                                    break;
                                case "Сборка":
                                    excelCells = excelWorkSheets.get_Range("O" + positionInRows);
                                    break;
                                case "Общий":
                                case "Услуги":
                                    excelCells = excelWorkSheets.get_Range("H" + positionInRows);
                                    break;
                            }
                            excelCells.Value2 = Convert.ToString(d["number_act"]);
                            switch (typeReport)
                            {
                                case "Испытания":
                                    excelCells = excelWorkSheets.get_Range("T" + positionInRows);
                                    break;
                                case "Сборка":
                                    excelCells = excelWorkSheets.get_Range("P" + positionInRows);
                                    break;
                                case "Общий":
                                case "Услуги":
                                    excelCells = excelWorkSheets.get_Range("I" + positionInRows);
                                    break;
                            }
                            excelCells.Value2 = Convert.ToString(d["date_act"]);
                            double pr;
                            switch (typeReport)
                            {
                                case "Испытания":
                                    excelCells = excelWorkSheets.get_Range("U" + positionInRows);
                                    break;
                                case "Сборка":
                                    excelCells = excelWorkSheets.get_Range("Q" + positionInRows);
                                    break;
                                case "Общий":
                                case "Услуги":
                                    excelCells = excelWorkSheets.get_Range("J" + positionInRows);
                                    break;
                            }
                            excelCells.Value2 = Convert.ToString(double.TryParse(Convert.ToString(d["amount_acts"]), out pr) ? pr.ToString("N2") : d["amount_acts"]);
                            fullAmount += pr;
                            positionInRows++;
                        }
                    if (typeReport == "Испытания")
                    {
                        excelCells = excelWorkSheets.get_Range("A" + positionInRows, "B" + positionInRows);
                        excelCells.Merge();
                        excelCells.Value2 = "Всего нарядов: ";
                        excelCells = excelWorkSheets.get_Range("C" + positionInRows);
                        excelCells.Value2 = Convert.ToString(countDuty);
                        excelCells = excelWorkSheets.get_Range("D" + positionInRows, "H" + positionInRows);
                        excelCells.Merge();
                        excelCells = excelWorkSheets.get_Range("I" + positionInRows);
                        excelCells.Value2 = Convert.ToString(countCountable);
                        excelCells = excelWorkSheets.get_Range("J" + positionInRows);
                        excelCells.Value2 = Convert.ToString(countTargeting);
                        excelCells = excelWorkSheets.get_Range("K" + positionInRows);
                        excelCells.Value2 = Convert.ToString(countWarm);
                        excelCells = excelWorkSheets.get_Range("L" + positionInRows);
                        excelCells.Value2 = Convert.ToString(countUncountable);
                        excelCells = excelWorkSheets.get_Range("M" + positionInRows);
                        excelCells.Value2 = Convert.ToString(countRenouncement);
                        excelCells = excelWorkSheets.get_Range("N" + positionInRows, "T" + positionInRows);
                        excelCells.Merge();
                        excelCells = excelWorkSheets.get_Range("U" + positionInRows);
                        double pr = 0;
                        excelCells.Value2 = Convert.ToString(double.TryParse(Convert.ToString(fullAmount), out pr) ? pr.ToString("N2") : fullAmount.ToString());
                        positionInRows++;
                    }
                    //Отображение сетки для таблицы, а также автоперенос и выравнивая по вертикали по центру
                    switch (typeReport)
                    {
                        case "Испытания":
                            excelCells = excelWorkSheets.get_Range("A3", "U" + (positionInRows - 1));
                            break;
                        case "Сборка":
                            excelCells = excelWorkSheets.get_Range("A3", "Q" + (positionInRows - 1));
                            break;
                        case "Общий":
                        case "Услуги":
                            excelCells = excelWorkSheets.get_Range("A3", "J" + (positionInRows - 1));
                            break;
                    }
                    excelCells.Borders.LineStyle = Excel.XlLineStyle.xlContinuous;
                    excelCells.Borders.Weight = Excel.XlBorderWeight.xlThin;
                    excelCells.WrapText = true;
                    excelCells.VerticalAlignment = Excel.Constants.xlCenter;
                    //Изменение шрифта
                    excelCells = excelWorkSheets.get_Range("A1", "U" + positionInRows);
                    excelCells.Font.Name = "Times New Roman";
                    excelCells.Font.Size = 12;
                    //Сохранение
                    DateTime dateNowFileName = DateTime.Now;
                    string strSaveName = Convert.ToString(dateNowFileName);
                    strSaveName = strSaveName.Replace(':', '-');
                    //Console.Write(storagePath + userSaveName + " " + strSaveName + ".xlsx");
                    savePath = Properties.Settings.Default.ReportPath + userSaveName + " " + strSaveName + ".xlsx";
                    excelWorkBook.SaveAs(savePath);
                    excelWorkBook.Close();
                    oExcel.Quit();
                }
                return ("Завершено!");
            }
            catch (Exception ex)
            {
                return ("error: " + ex.Message);
            }
        }

        //Оплата за период
        static public string SecondDepartmentPrintReport4(string typeDocument, string userSaveName, out string savePath, bool isVisibleGenerate = false, string date_begin = "", string date_end = "")
        {
            savePath = null;

            string command = "http://" + server + "/orc_new/second_department_print_reports?real_name_table=Оплата за период&date_begin=" + date_begin + "&date_end=" + date_end;
            if (userSaveName == "")
                userSaveName = "Отчет";
            //Запрос
            dynamic json = null;
            WebRequest request = WebRequest.Create(command);
            WebResponse response = request.GetResponse();
            using (Stream stream = response.GetResponseStream())
            {
                using (StreamReader reader = new StreamReader(stream))
                {
                    json = JsonConvert.DeserializeObject(reader.ReadToEnd());
                }
            }

            //Создание доков
            try
            {
                if (typeDocument == "Word")
                {
                    //Создаем документ
                    Word.Application oWord = new Word.Application();
                    oWord.Visible = false;
                    if (isVisibleGenerate)
                        oWord.Visible = true;
                    Object template = Type.Missing;
                    Object newTemplate = false;
                    Object documentType = Word.WdNewDocumentType.wdNewBlankDocument;
                    //Object visible = true;
                    oWord.Documents.Add(ref template, ref newTemplate, ref documentType);
                    Word.Document wordDocument = (Word.Document)oWord.Documents.get_Item(1);
                    //Альбомная ориентация
                    wordDocument.PageSetup.Orientation = Word.WdOrientation.wdOrientLandscape;
                    //Добавление шапки в файл
                    wordDocument.Paragraphs.Add();
                    var range = wordDocument.Paragraphs[1].Range;
                    range.Text = "Оплата за период с " + date_begin + " по " + date_end;
                    range.ParagraphFormat.Alignment = Word.WdParagraphAlignment.wdAlignParagraphCenter;
                    wordDocument.Paragraphs.Add();
                    range = wordDocument.Paragraphs[2].Range;
                    range.Text = "по состоянию на " + DateTime.Now.ToShortDateString() + " г.";
                    range.ParagraphFormat.Alignment = Word.WdParagraphAlignment.wdAlignParagraphRight;
                    //Добавление в файл таблицы
                    wordDocument.Paragraphs.Add();
                    Object autoFitBehavior = Word.WdAutoFitBehavior.wdAutoFitWindow;
                    range = wordDocument.Paragraphs[wordDocument.Paragraphs.Count].Range;
                    wordDocument.Tables.Add(range, 1, 7, autoFitBehavior);
                    Word.Table oTable = wordDocument.Tables[1];
                    string[] nameHeaderTable = { "Контрагент", "№ дог.", "№ платежн. поруч.", "Дата п/п", "Оплата", "Вид договора", "Наименование работ" };
                    for (int i = 0; i < oTable.Columns.Count; i++)
                    {
                        Word.Range wordCellRange = oTable.Cell(1, i + 1).Range;
                        wordCellRange.ParagraphFormat.Alignment = Word.WdParagraphAlignment.wdAlignParagraphCenter;
                        wordCellRange.Text = nameHeaderTable[i];
                    }
                    if (json != null)
                    {
                        int posRows = 2;
                        foreach (dynamic d in json)
                        {
                            oTable.Rows.Add();
                            Word.Range wordCellRange = oTable.Cell(posRows, 1).Range;
                            wordCellRange.Text = Convert.ToString(d["name_counterpartie_contract"]);
                            wordCellRange = oTable.Cell(posRows, 2).Range;
                            wordCellRange.Text = Convert.ToString(d["number_contract"]);
                            wordCellRange = oTable.Cell(posRows, 3).Range;
                            wordCellRange.Text = Convert.ToString(d["number_invoice"]);
                            wordCellRange = oTable.Cell(posRows, 4).Range;
                            wordCellRange.Text = d["date_invoice"] != null ? Convert.ToDateTime(Convert.ToString(d["date_invoice"])).ToString("d") : "";
                            double pr;
                            wordCellRange = oTable.Cell(posRows, 5).Range;
                            wordCellRange.Text = Convert.ToString(double.TryParse(Convert.ToString(d["amount_p_invoice"]), out pr) ? pr.ToString("N2") : d["amount_p_invoice"]);
                            wordCellRange = oTable.Cell(posRows, 6).Range;
                            wordCellRange.Text = Convert.ToString(d["name_view_contract"]);
                            wordCellRange = oTable.Cell(posRows, 7).Range;
                            wordCellRange.Text = Convert.ToString(d["item_contract"]);
                            posRows++;
                        }
                    }
                    //Сохранение
                    DateTime dateNowFileName = DateTime.Now;
                    string strSaveName = Convert.ToString(dateNowFileName);
                    strSaveName = strSaveName.Replace(':', '-');
                    //Console.WriteLine(storagePath + userSaveName + " " + strSaveName + ".docx");
                    savePath = Properties.Settings.Default.ReportPath + userSaveName + " " + strSaveName + ".docx";
                    wordDocument.SaveAs(savePath);
                    wordDocument.Close();
                    oWord.Quit();
                }
                else
                {
                    //Создание документа Excel
                    Excel.Application oExcel = new Excel.Application();
                    oExcel.Visible = false;
                    if (isVisibleGenerate)
                        oExcel.Visible = true;
                    oExcel.SheetsInNewWorkbook = 1;
                    oExcel.Workbooks.Add();
                    Excel.Workbooks excelWorkBooks = oExcel.Workbooks;
                    Excel.Workbook excelWorkBook = excelWorkBooks[1];
                    excelWorkBook.Saved = false;
                    Excel.Sheets excelSheets = oExcel.Worksheets;
                    Excel.Worksheet excelWorkSheets = excelSheets.get_Item(1);
                    Excel.Range excelCells;
                    //---типо Титульник---
                    //Строка 1
                    excelCells = excelWorkSheets.get_Range("A1", "G1");
                    excelCells.Merge();
                    excelCells.Value2 = "Оплата за период с " + date_begin + " по " + date_end;
                    excelCells.WrapText = true;
                    excelCells.Font.Bold = true;
                    excelCells.HorizontalAlignment = Excel.Constants.xlCenter;
                    excelCells.VerticalAlignment = Excel.Constants.xlCenter;
                    excelCells.RowHeight = 34;
                    //Строка 2
                    excelCells = excelWorkSheets.get_Range("A2", "G2");
                    excelCells.Merge();
                    excelCells.Value2 = "по состоянию на " + DateTime.Now.ToShortDateString() + " г.";
                    excelCells.HorizontalAlignment = Excel.Constants.xlRight;
                    excelCells.VerticalAlignment = Excel.Constants.xlCenter;
                    //Заполнения даннами шапки
                    int positionInRows = 3;
                    excelCells = excelWorkSheets.get_Range("A" + positionInRows);
                    excelCells.Value2 = "Контрагент";
                    excelCells.ColumnWidth = 25;
                    excelCells = excelWorkSheets.get_Range("B" + positionInRows);
                    excelCells.Value2 = "№ дог.";
                    excelCells.ColumnWidth = 35;
                    excelCells = excelWorkSheets.get_Range("C" + positionInRows);
                    excelCells.Value2 = "№ платежн. поруч.";
                    excelCells.ColumnWidth = 18;
                    excelCells = excelWorkSheets.get_Range("D" + positionInRows);
                    excelCells.Value2 = "Дата п/п";
                    excelCells.ColumnWidth = 18;
                    excelCells = excelWorkSheets.get_Range("E" + positionInRows);
                    excelCells.Value2 = "Оплата";
                    excelCells.ColumnWidth = 35;
                    excelCells = excelWorkSheets.get_Range("F" + positionInRows);
                    excelCells.Value2 = "Вид договора";
                    excelCells.ColumnWidth = 27;
                    excelCells = excelWorkSheets.get_Range("G" + positionInRows);
                    excelCells.Value2 = "Наименование работ";
                    excelCells.ColumnWidth = 40;
                    //Настройка выравнивания и автопереноса
                    excelCells = excelWorkSheets.get_Range("A" + positionInRows, "G" + positionInRows);
                    excelCells.WrapText = true;
                    excelCells.HorizontalAlignment = Excel.Constants.xlCenter;
                    excelCells.VerticalAlignment = Excel.Constants.xlCenter;
                    positionInRows++;
                    //Настройки автоширины
                    //excelWorkSheets.Columns[1].EntireColumn.AutoFit();
                    if (json != null)
                        foreach (dynamic d in json)
                        {
                            excelCells = excelWorkSheets.get_Range("A" + positionInRows);
                            excelCells.Value2 = Convert.ToString(d["name_counterpartie_contract"]);
                            excelCells = excelWorkSheets.get_Range("B" + positionInRows);
                            excelCells.Value2 = Convert.ToString(d["number_contract"]);
                            excelCells = excelWorkSheets.get_Range("C" + positionInRows);
                            excelCells.Value2 = Convert.ToString(d["number_invoice"]);
                            excelCells = excelWorkSheets.get_Range("D" + positionInRows);
                            excelCells.Value2 = d["date_invoice"] != null ? Convert.ToDateTime(Convert.ToString(d["date_invoice"])).ToString("d") : "";
                            double pr;
                            excelCells = excelWorkSheets.get_Range("E" + positionInRows);
                            excelCells.Value2 = Convert.ToString(double.TryParse(Convert.ToString(d["amount_p_invoice"]), out pr) ? pr.ToString("N2") : d["amount_p_invoice"]);
                            excelCells = excelWorkSheets.get_Range("F" + positionInRows);
                            excelCells.Value2 = Convert.ToString(d["name_view_contract"]);
                            excelCells = excelWorkSheets.get_Range("G" + positionInRows);
                            excelCells.Value2 = Convert.ToString(d["item_contract"]);
                            positionInRows++;
                        }
                    //Отображение сетки для таблицы, а также автоперенос и выравнивая по вертикали по центру
                    excelCells = excelWorkSheets.get_Range("A3", "G" + (positionInRows - 1));
                    excelCells.Borders.LineStyle = Excel.XlLineStyle.xlContinuous;
                    excelCells.Borders.Weight = Excel.XlBorderWeight.xlThin;
                    excelCells.WrapText = true;
                    excelCells.VerticalAlignment = Excel.Constants.xlCenter;
                    //Изменение шрифта
                    excelCells = excelWorkSheets.get_Range("A1", "G" + positionInRows);
                    excelCells.Font.Name = "Times New Roman";
                    excelCells.Font.Size = 12;
                    //Сохранение
                    DateTime dateNowFileName = DateTime.Now;
                    string strSaveName = Convert.ToString(dateNowFileName);
                    strSaveName = strSaveName.Replace(':', '-');
                    //Console.Write(storagePath + userSaveName + " " + strSaveName + ".xlsx");
                    savePath = Properties.Settings.Default.ReportPath + userSaveName + " " + strSaveName + ".xlsx";
                    excelWorkBook.SaveAs(savePath);
                    excelWorkBook.Close();
                    oExcel.Quit();
                }
                return ("Завершено!");
            }
            catch (Exception ex)
            {
                return ("error: " + ex.Message);
            }
        }

        //Предприятия за год (кратко)
        static public string SecondDepartmentPrintReport5(string typeDocument, string userSaveName, out string savePath, bool isVisibleGenerate = false, string year = "")
        {
            savePath = null;

            string command = "http://" + server + "/orc_new/second_department_print_reports?real_name_table=Предприятия за год к&year=" + year;
            if (userSaveName == "")
                userSaveName = "Отчет";
            //Запрос
            Dictionary<string,string[]> json = null;
            WebRequest request = WebRequest.Create(command);
            WebResponse response = request.GetResponse();
            using (Stream stream = response.GetResponseStream())
            {
                using (StreamReader reader = new StreamReader(stream))
                {
                    json = JsonConvert.DeserializeObject<Dictionary<string,string[]>>(reader.ReadToEnd());
                }
            }
            
            //Создание доков
            try
            {
                if (typeDocument == "Word")
                {
                    //Создаем документ
                    Word.Application oWord = new Word.Application();
                    oWord.Visible = false;
                    if (isVisibleGenerate)
                        oWord.Visible = true;
                    Object template = Type.Missing;
                    Object newTemplate = false;
                    Object documentType = Word.WdNewDocumentType.wdNewBlankDocument;
                    //Object visible = true;
                    oWord.Documents.Add(ref template, ref newTemplate, ref documentType);
                    Word.Document wordDocument = (Word.Document)oWord.Documents.get_Item(1);
                    //Альбомная ориентация
                    wordDocument.PageSetup.Orientation = Word.WdOrientation.wdOrientLandscape;
                    //Добавление шапки в файл
                    wordDocument.Paragraphs.Add();
                    var range = wordDocument.Paragraphs[1].Range;
                    range.Text = "Предприятия за " + year + " год";
                    range.ParagraphFormat.Alignment = Word.WdParagraphAlignment.wdAlignParagraphCenter;
                    wordDocument.Paragraphs.Add();
                    range = wordDocument.Paragraphs[2].Range;
                    range.Text = "по состоянию на " + DateTime.Now.ToShortDateString() + " г.";
                    range.ParagraphFormat.Alignment = Word.WdParagraphAlignment.wdAlignParagraphRight;
                    //Добавление в файл таблицы
                    wordDocument.Paragraphs.Add();
                    Object autoFitBehavior = Word.WdAutoFitBehavior.wdAutoFitWindow;
                    range = wordDocument.Paragraphs[wordDocument.Paragraphs.Count].Range;
                    wordDocument.Tables.Add(range, 1, 2, autoFitBehavior);
                    Word.Table oTable = wordDocument.Tables[1];
                    string[] nameHeaderTable = { "№ п/п", "Контрагент" };
                    for (int i = 0; i < oTable.Columns.Count; i++)
                    {
                        Word.Range wordCellRange = oTable.Cell(1, i + 1).Range;
                        wordCellRange.ParagraphFormat.Alignment = Word.WdParagraphAlignment.wdAlignParagraphCenter;
                        wordCellRange.Text = nameHeaderTable[i];
                    }
                    oTable.Cell(1, 1).Width = 50;
                    oTable.Cell(1, 2).Width = 700;
                    if (json != null)
                    {
                        int posRows = 2;
                        foreach (KeyValuePair<string,string[]> d in json)
                        {
                            oTable.Rows.Add();
                            Word.Range wordCellRange = oTable.Cell(posRows, 1).Range;
                            wordCellRange.Text = d.Key;
                            int oldPosRows = posRows;
                            posRows++;
                            int k = 1;
                            foreach (string in_d in d.Value)
                            {
                                oTable.Rows.Add();
                                wordCellRange = oTable.Cell(posRows, 1).Range;
                                wordCellRange.Text = Convert.ToString(k++);
                                wordCellRange = oTable.Cell(posRows, 2).Range;
                                wordCellRange.Text = Convert.ToString(in_d);
                                posRows++;
                            }
                            oTable.Cell(oldPosRows, 1).Merge(oTable.Cell(oldPosRows, 2));
                            wordCellRange = oTable.Cell(oldPosRows, 1).Range;
                            wordCellRange.ParagraphFormat.Alignment = Word.WdParagraphAlignment.wdAlignParagraphLeft;
                        }
                    }
                    //Сохранение
                    DateTime dateNowFileName = DateTime.Now;
                    string strSaveName = Convert.ToString(dateNowFileName);
                    strSaveName = strSaveName.Replace(':', '-');
                    //Console.WriteLine(storagePath + userSaveName + " " + strSaveName + ".docx");
                    savePath = Properties.Settings.Default.ReportPath + userSaveName + " " + strSaveName + ".docx";
                    wordDocument.SaveAs(savePath);
                    wordDocument.Close();
                    oWord.Quit();
                }
                else
                {
                    //Создание документа Excel
                    Excel.Application oExcel = new Excel.Application();
                    oExcel.Visible = false;
                    if (isVisibleGenerate)
                        oExcel.Visible = true;
                    oExcel.SheetsInNewWorkbook = 1;
                    oExcel.Workbooks.Add();
                    Excel.Workbooks excelWorkBooks = oExcel.Workbooks;
                    Excel.Workbook excelWorkBook = excelWorkBooks[1];
                    excelWorkBook.Saved = false;
                    Excel.Sheets excelSheets = oExcel.Worksheets;
                    Excel.Worksheet excelWorkSheets = excelSheets.get_Item(1);
                    Excel.Range excelCells;
                    //---типо Титульник---
                    //Строка 1
                    excelCells = excelWorkSheets.get_Range("A1", "B1");
                    excelCells.Merge();
                    excelCells.Value2 = "Предприятия за " + year + " год";
                    excelCells.WrapText = true;
                    excelCells.Font.Bold = true;
                    excelCells.HorizontalAlignment = Excel.Constants.xlCenter;
                    excelCells.VerticalAlignment = Excel.Constants.xlCenter;
                    excelCells.RowHeight = 34;
                    //Строка 2
                    excelCells = excelWorkSheets.get_Range("A2", "B2");
                    excelCells.Merge();
                    excelCells.Value2 = "по состоянию на " + DateTime.Now.ToShortDateString() + " г.";
                    excelCells.HorizontalAlignment = Excel.Constants.xlRight;
                    excelCells.VerticalAlignment = Excel.Constants.xlCenter;
                    //Заполнения даннами шапки
                    int positionInRows = 3;
                    excelCells = excelWorkSheets.get_Range("A" + positionInRows);
                    excelCells.Value2 = "№ п/п";
                    excelCells.ColumnWidth = 25;
                    excelCells = excelWorkSheets.get_Range("B" + positionInRows);
                    excelCells.Value2 = "Контрагент";
                    excelCells.ColumnWidth = 150;
                    //Настройка выравнивания и автопереноса
                    excelCells = excelWorkSheets.get_Range("A" + positionInRows, "B" + positionInRows);
                    excelCells.WrapText = true;
                    excelCells.HorizontalAlignment = Excel.Constants.xlCenter;
                    excelCells.VerticalAlignment = Excel.Constants.xlCenter;
                    positionInRows++;
                    //Настройки автоширины
                    //excelWorkSheets.Columns[1].EntireColumn.AutoFit();
                    if (json != null)
                        foreach (KeyValuePair<string, string[]> d in json)
                        {
                            excelCells = excelWorkSheets.get_Range("A" + positionInRows);
                            excelCells.Value2 = Convert.ToString(d.Key);
                            excelCells = excelWorkSheets.get_Range("A" + positionInRows, "B" + positionInRows);
                            excelCells.Merge();
                            positionInRows++;
                            int k = 1;
                            foreach (string str in d.Value)
                            {
                                excelCells = excelWorkSheets.get_Range("A" + positionInRows);
                                excelCells.Value2 = Convert.ToString(k);
                                excelCells = excelWorkSheets.get_Range("B" + positionInRows);
                                excelCells.Value2 = Convert.ToString(str);
                                positionInRows++;
                            }
                        }
                    //Отображение сетки для таблицы, а также автоперенос и выравнивая по вертикали по центру
                    excelCells = excelWorkSheets.get_Range("A3", "B" + (positionInRows - 1));
                    excelCells.Borders.LineStyle = Excel.XlLineStyle.xlContinuous;
                    excelCells.Borders.Weight = Excel.XlBorderWeight.xlThin;
                    excelCells.WrapText = true;
                    excelCells.VerticalAlignment = Excel.Constants.xlCenter;
                    //Изменение шрифта
                    excelCells = excelWorkSheets.get_Range("A1", "B" + positionInRows);
                    excelCells.Font.Name = "Times New Roman";
                    excelCells.Font.Size = 12;
                    //Сохранение
                    DateTime dateNowFileName = DateTime.Now;
                    string strSaveName = Convert.ToString(dateNowFileName);
                    strSaveName = strSaveName.Replace(':', '-');
                    //Console.Write(storagePath + userSaveName + " " + strSaveName + ".xlsx");
                    savePath = Properties.Settings.Default.ReportPath + userSaveName + " " + strSaveName + ".xlsx";
                    excelWorkBook.SaveAs(savePath);
                    excelWorkBook.Close();
                    oExcel.Quit();
                }
                return ("Завершено!");
            }
            catch (Exception ex)
            {
                return ("error: " + ex.Message);
            }
        }

        //Предприятия за год
        static public string SecondDepartmentPrintReport6(string typeDocument, string userSaveName, out string savePath, bool isVisibleGenerate = false, string year = "")
        {
            savePath = null;

            string command = "http://" + server + "/orc_new/second_department_print_reports?real_name_table=Предприятия за год&year=" + year;
            if (userSaveName == "")
                userSaveName = "Отчет";
            //Запрос
            Dictionary<string, Dictionary<string, string[]>> json = null;
            WebRequest request = WebRequest.Create(command);
            WebResponse response = request.GetResponse();
            using (Stream stream = response.GetResponseStream())
            {
                using (StreamReader reader = new StreamReader(stream))
                {
                    json = JsonConvert.DeserializeObject<Dictionary<string, Dictionary<string, string[]>>>(reader.ReadToEnd());
                }
            }

            //Создание доков
            try
            {
                if (typeDocument == "Word")
                {
                    //Создаем документ
                    Word.Application oWord = new Word.Application();
                    oWord.Visible = false;
                    if (isVisibleGenerate)
                        oWord.Visible = true;
                    Object template = Type.Missing;
                    Object newTemplate = false;
                    Object documentType = Word.WdNewDocumentType.wdNewBlankDocument;
                    //Object visible = true;
                    oWord.Documents.Add(ref template, ref newTemplate, ref documentType);
                    Word.Document wordDocument = (Word.Document)oWord.Documents.get_Item(1);
                    //Альбомная ориентация
                    wordDocument.PageSetup.Orientation = Word.WdOrientation.wdOrientLandscape;
                    //Добавление шапки в файл
                    wordDocument.Paragraphs.Add();
                    var range = wordDocument.Paragraphs[1].Range;
                    range.Text = "Предприятия за " + year + " год";
                    range.ParagraphFormat.Alignment = Word.WdParagraphAlignment.wdAlignParagraphCenter;
                    wordDocument.Paragraphs.Add();
                    range = wordDocument.Paragraphs[2].Range;
                    range.Text = "по состоянию на " + DateTime.Now.ToShortDateString() + " г.";
                    range.ParagraphFormat.Alignment = Word.WdParagraphAlignment.wdAlignParagraphRight;
                    //Добавление в файл таблицы
                    wordDocument.Paragraphs.Add();
                    Object autoFitBehavior = Word.WdAutoFitBehavior.wdAutoFitWindow;
                    range = wordDocument.Paragraphs[wordDocument.Paragraphs.Count].Range;
                    wordDocument.Tables.Add(range, 1, 4, autoFitBehavior);
                    Word.Table oTable = wordDocument.Tables[1];
                    string[] nameHeaderTable = { "№ п/п", "Контрагент", "Вид договора", "Наименование работ" };
                    for (int i = 0; i < oTable.Columns.Count; i++)
                    {
                        Word.Range wordCellRange = oTable.Cell(1, i + 1).Range;
                        wordCellRange.ParagraphFormat.Alignment = Word.WdParagraphAlignment.wdAlignParagraphCenter;
                        wordCellRange.Text = nameHeaderTable[i];
                    }
                    oTable.Cell(1, 1).Width = 50;
                    if (json != null)
                    {
                        int posRows = 2;
                        foreach (KeyValuePair<string, Dictionary<string, string[]>> d in json)
                        {
                            oTable.Rows.Add();
                            Word.Range wordCellRange = oTable.Cell(posRows, 1).Range;
                            wordCellRange.Text = (posRows-1).ToString();
                            wordCellRange = oTable.Cell(posRows, 2).Range;
                            wordCellRange.Text = d.Key;
                            string pr = "";
                            foreach (string viewStr in d.Value["view"])
                            {
                                pr += viewStr + "\n";
                            }
                            wordCellRange = oTable.Cell(posRows, 3).Range;
                            wordCellRange.Text = Convert.ToString(pr.TrimEnd('\n'));
                            pr = "";
                            foreach (string workStr in d.Value["name_work"])
                            {
                                pr += workStr + "\n";
                            }
                            wordCellRange = oTable.Cell(posRows, 4).Range;
                            wordCellRange.Text = Convert.ToString(pr.TrimEnd('\n'));
                            posRows++;
                        }
                    }
                    //Сохранение
                    DateTime dateNowFileName = DateTime.Now;
                    string strSaveName = Convert.ToString(dateNowFileName);
                    strSaveName = strSaveName.Replace(':', '-');
                    //Console.WriteLine(storagePath + userSaveName + " " + strSaveName + ".docx");
                    savePath = Properties.Settings.Default.ReportPath + userSaveName + " " + strSaveName + ".docx";
                    wordDocument.SaveAs(savePath);
                    wordDocument.Close();
                    oWord.Quit();
                }
                else
                {
                    //Создание документа Excel
                    Excel.Application oExcel = new Excel.Application();
                    oExcel.Visible = false;
                    if (isVisibleGenerate)
                        oExcel.Visible = true;
                    oExcel.SheetsInNewWorkbook = 1;
                    oExcel.Workbooks.Add();
                    Excel.Workbooks excelWorkBooks = oExcel.Workbooks;
                    Excel.Workbook excelWorkBook = excelWorkBooks[1];
                    excelWorkBook.Saved = false;
                    Excel.Sheets excelSheets = oExcel.Worksheets;
                    Excel.Worksheet excelWorkSheets = excelSheets.get_Item(1);
                    Excel.Range excelCells;
                    //---типо Титульник---
                    //Строка 1
                    excelCells = excelWorkSheets.get_Range("A1", "D1");
                    excelCells.Merge();
                    excelCells.Value2 = "Предприятия за " + year + " год";
                    excelCells.WrapText = true;
                    excelCells.Font.Bold = true;
                    excelCells.HorizontalAlignment = Excel.Constants.xlCenter;
                    excelCells.VerticalAlignment = Excel.Constants.xlCenter;
                    excelCells.RowHeight = 34;
                    //Строка 2
                    excelCells = excelWorkSheets.get_Range("A2", "D2");
                    excelCells.Merge();
                    excelCells.Value2 = "по состоянию на " + DateTime.Now.ToShortDateString() + " г.";
                    excelCells.HorizontalAlignment = Excel.Constants.xlRight;
                    excelCells.VerticalAlignment = Excel.Constants.xlCenter;
                    //Заполнения даннами шапки
                    int positionInRows = 3;
                    excelCells = excelWorkSheets.get_Range("A" + positionInRows);
                    excelCells.Value2 = "№ п/п";
                    excelCells.ColumnWidth = 10;
                    excelCells = excelWorkSheets.get_Range("B" + positionInRows);
                    excelCells.Value2 = "Контрагент";
                    excelCells.ColumnWidth = 125;
                    excelCells = excelWorkSheets.get_Range("C" + positionInRows);
                    excelCells.Value2 = "Вид договора";
                    excelCells.ColumnWidth = 25;
                    excelCells = excelWorkSheets.get_Range("D" + positionInRows);
                    excelCells.Value2 = "Наименование работ";
                    excelCells.ColumnWidth = 25;
                    //Настройка выравнивания и автопереноса
                    excelCells = excelWorkSheets.get_Range("A" + positionInRows, "D" + positionInRows);
                    excelCells.WrapText = true;
                    excelCells.HorizontalAlignment = Excel.Constants.xlCenter;
                    excelCells.VerticalAlignment = Excel.Constants.xlCenter;
                    positionInRows++;
                    //Настройки автоширины
                    //excelWorkSheets.Columns[1].EntireColumn.AutoFit();
                    int k = 1;
                    if (json != null)
                        foreach (KeyValuePair<string, Dictionary<string, string[]>> d in json)
                        {
                            excelCells = excelWorkSheets.get_Range("A" + positionInRows);
                            excelCells.Value2 = Convert.ToString(k);
                            excelCells = excelWorkSheets.get_Range("B" + positionInRows);
                            excelCells.Value2 = Convert.ToString(d.Key);
                            string pr = "";
                            foreach (string viewStr in d.Value["view"])
                            {
                                pr += viewStr + "\n";
                            }
                            excelCells = excelWorkSheets.get_Range("C" + positionInRows);
                            excelCells.Value2 = Convert.ToString(pr.TrimEnd('\n'));
                            pr = "";
                            foreach (string workStr in d.Value["name_work"])
                            {
                                pr += workStr + "\n";
                            }
                            excelCells = excelWorkSheets.get_Range("D" + positionInRows);
                            excelCells.Value2 = Convert.ToString(pr.TrimEnd('\n'));
                            positionInRows++;
                        }
                    //Отображение сетки для таблицы, а также автоперенос и выравнивая по вертикали по центру
                    excelCells = excelWorkSheets.get_Range("A3", "D" + (positionInRows - 1));
                    excelCells.Borders.LineStyle = Excel.XlLineStyle.xlContinuous;
                    excelCells.Borders.Weight = Excel.XlBorderWeight.xlThin;
                    excelCells.WrapText = true;
                    excelCells.VerticalAlignment = Excel.Constants.xlCenter;
                    //Изменение шрифта
                    excelCells = excelWorkSheets.get_Range("A1", "D" + positionInRows);
                    excelCells.Font.Name = "Times New Roman";
                    excelCells.Font.Size = 12;
                    //Сохранение
                    DateTime dateNowFileName = DateTime.Now;
                    string strSaveName = Convert.ToString(dateNowFileName);
                    strSaveName = strSaveName.Replace(':', '-');
                    //Console.Write(storagePath + userSaveName + " " + strSaveName + ".xlsx");
                    savePath = Properties.Settings.Default.ReportPath + userSaveName + " " + strSaveName + ".xlsx";
                    excelWorkBook.SaveAs(savePath);
                    excelWorkBook.Close();
                    oExcel.Quit();
                }
                return ("Завершено!");
            }
            catch (Exception ex)
            {
                return ("error: " + ex.Message);
            }
        }

        //Сводный отчёт по оплате
        static public string SecondDepartmentPrintReport7(string typeDocument, string userSaveName, out string savePath, bool isVisibleGenerate = false, string date_begin = "", string date_end = "")
        {
            savePath = null;

            string command = "http://" + server + "/orc_new/second_department_print_reports?real_name_table=Сводный отчет по договорам&date_begin=" + date_begin + "&date_end=" + date_end;
            if (userSaveName == "")
                userSaveName = "Отчет";
            //Запрос
            dynamic json = null;
            WebRequest request = WebRequest.Create(command);
            WebResponse response = request.GetResponse();
            using (Stream stream = response.GetResponseStream())
            {
                using (StreamReader reader = new StreamReader(stream))
                {
                    json = JsonConvert.DeserializeObject(reader.ReadToEnd());
                }
            }

            //Создание доков
            try
            {
                if (typeDocument == "Word")
                {
                    //Создаем документ
                    Word.Application oWord = new Word.Application();
                    oWord.Visible = false;
                    if (isVisibleGenerate)
                        oWord.Visible = true;
                    Object template = Type.Missing;
                    Object newTemplate = false;
                    Object documentType = Word.WdNewDocumentType.wdNewBlankDocument;
                    //Object visible = true;
                    oWord.Documents.Add(ref template, ref newTemplate, ref documentType);
                    Word.Document wordDocument = (Word.Document)oWord.Documents.get_Item(1);
                    //Альбомная ориентация
                    wordDocument.PageSetup.Orientation = Word.WdOrientation.wdOrientLandscape;
                    //Добавление шапки в файл
                    wordDocument.Paragraphs.Add();
                    var range = wordDocument.Paragraphs[1].Range;
                    range.Text = "Оплата за период с " + date_begin + " по " + date_end;
                    range.ParagraphFormat.Alignment = Word.WdParagraphAlignment.wdAlignParagraphCenter;
                    wordDocument.Paragraphs.Add();
                    range = wordDocument.Paragraphs[2].Range;
                    range.Text = "по состоянию на " + DateTime.Now.ToShortDateString() + " г.";
                    range.ParagraphFormat.Alignment = Word.WdParagraphAlignment.wdAlignParagraphRight;
                    //Добавление в файл таблицы
                    wordDocument.Paragraphs.Add();
                    Object autoFitBehavior = Word.WdAutoFitBehavior.wdAutoFitWindow;
                    range = wordDocument.Paragraphs[wordDocument.Paragraphs.Count].Range;
                    wordDocument.Tables.Add(range, 1, 7, autoFitBehavior);
                    Word.Table oTable = wordDocument.Tables[1];
                    string[] nameHeaderTable = { "Контрагент", "№ дог.", "№ платежн. поруч.", "Дата п/п", "Оплата", "Вид договора", "Наименование работ" };
                    for (int i = 0; i < oTable.Columns.Count; i++)
                    {
                        Word.Range wordCellRange = oTable.Cell(1, i + 1).Range;
                        wordCellRange.ParagraphFormat.Alignment = Word.WdParagraphAlignment.wdAlignParagraphCenter;
                        wordCellRange.Text = nameHeaderTable[i];
                    }
                    if (json != null)
                    {
                        int posRows = 2;
                        foreach (dynamic d in json)
                        {
                            oTable.Rows.Add();
                            Word.Range wordCellRange = oTable.Cell(posRows, 1).Range;
                            wordCellRange.Text = Convert.ToString(d["name_counterpartie_contract"]);
                            wordCellRange = oTable.Cell(posRows, 2).Range;
                            wordCellRange.Text = Convert.ToString(d["number_contract"]);
                            wordCellRange = oTable.Cell(posRows, 3).Range;
                            wordCellRange.Text = Convert.ToString(d["number_invoice"]);
                            wordCellRange = oTable.Cell(posRows, 4).Range;
                            wordCellRange.Text = d["date_invoice"] != null ? Convert.ToDateTime(Convert.ToString(d["date_invoice"])).ToString("d") : "";
                            double pr;
                            wordCellRange = oTable.Cell(posRows, 5).Range;
                            wordCellRange.Text = Convert.ToString(double.TryParse(Convert.ToString(d["amount_p_invoice"]), out pr) ? pr.ToString("N2") : d["amount_p_invoice"]);
                            wordCellRange = oTable.Cell(posRows, 6).Range;
                            wordCellRange.Text = Convert.ToString(d["name_view_contract"]);
                            wordCellRange = oTable.Cell(posRows, 7).Range;
                            wordCellRange.Text = Convert.ToString(d["item_contract"]);
                            posRows++;
                        }
                    }
                    //Сохранение
                    DateTime dateNowFileName = DateTime.Now;
                    string strSaveName = Convert.ToString(dateNowFileName);
                    strSaveName = strSaveName.Replace(':', '-');
                    //Console.WriteLine(storagePath + userSaveName + " " + strSaveName + ".docx");
                    savePath = Properties.Settings.Default.ReportPath + userSaveName + " " + strSaveName + ".docx";
                    wordDocument.SaveAs(savePath);
                    wordDocument.Close();
                    oWord.Quit();
                }
                else
                {
                    //Создание документа Excel
                    Excel.Application oExcel = new Excel.Application();
                    oExcel.Visible = false;
                    if (isVisibleGenerate)
                        oExcel.Visible = true;
                    oExcel.SheetsInNewWorkbook = 1;
                    oExcel.Workbooks.Add();
                    Excel.Workbooks excelWorkBooks = oExcel.Workbooks;
                    Excel.Workbook excelWorkBook = excelWorkBooks[1];
                    excelWorkBook.Saved = false;
                    Excel.Sheets excelSheets = oExcel.Worksheets;
                    Excel.Worksheet excelWorkSheets = excelSheets.get_Item(1);
                    Excel.Range excelCells;
                    //---типо Титульник---
                    //Строка 1
                    excelCells = excelWorkSheets.get_Range("A1", "G1");
                    excelCells.Merge();
                    excelCells.Value2 = "Оплата за период с " + date_begin + " по " + date_end;
                    excelCells.WrapText = true;
                    excelCells.Font.Bold = true;
                    excelCells.HorizontalAlignment = Excel.Constants.xlCenter;
                    excelCells.VerticalAlignment = Excel.Constants.xlCenter;
                    excelCells.RowHeight = 34;
                    //Строка 2
                    excelCells = excelWorkSheets.get_Range("A2", "G2");
                    excelCells.Merge();
                    excelCells.Value2 = "по состоянию на " + DateTime.Now.ToShortDateString() + " г.";
                    excelCells.HorizontalAlignment = Excel.Constants.xlRight;
                    excelCells.VerticalAlignment = Excel.Constants.xlCenter;
                    //Заполнения даннами шапки
                    int positionInRows = 3;
                    excelCells = excelWorkSheets.get_Range("A" + positionInRows);
                    excelCells.Value2 = "Контрагент";
                    excelCells.ColumnWidth = 25;
                    excelCells = excelWorkSheets.get_Range("B" + positionInRows);
                    excelCells.Value2 = "№ дог.";
                    excelCells.ColumnWidth = 35;
                    excelCells = excelWorkSheets.get_Range("C" + positionInRows);
                    excelCells.Value2 = "№ платежн. поруч.";
                    excelCells.ColumnWidth = 18;
                    excelCells = excelWorkSheets.get_Range("D" + positionInRows);
                    excelCells.Value2 = "Дата п/п";
                    excelCells.ColumnWidth = 18;
                    excelCells = excelWorkSheets.get_Range("E" + positionInRows);
                    excelCells.Value2 = "Оплата";
                    excelCells.ColumnWidth = 35;
                    excelCells = excelWorkSheets.get_Range("F" + positionInRows);
                    excelCells.Value2 = "Вид договора";
                    excelCells.ColumnWidth = 27;
                    excelCells = excelWorkSheets.get_Range("G" + positionInRows);
                    excelCells.Value2 = "Наименование работ";
                    excelCells.ColumnWidth = 40;
                    //Настройка выравнивания и автопереноса
                    excelCells = excelWorkSheets.get_Range("A" + positionInRows, "G" + positionInRows);
                    excelCells.WrapText = true;
                    excelCells.HorizontalAlignment = Excel.Constants.xlCenter;
                    excelCells.VerticalAlignment = Excel.Constants.xlCenter;
                    positionInRows++;
                    //Настройки автоширины
                    //excelWorkSheets.Columns[1].EntireColumn.AutoFit();
                    if (json != null)
                        foreach (dynamic d in json)
                        {
                            excelCells = excelWorkSheets.get_Range("A" + positionInRows);
                            excelCells.Value2 = Convert.ToString(d["name_counterpartie_contract"]);
                            excelCells = excelWorkSheets.get_Range("B" + positionInRows);
                            excelCells.Value2 = Convert.ToString(d["number_contract"]);
                            excelCells = excelWorkSheets.get_Range("C" + positionInRows);
                            excelCells.Value2 = Convert.ToString(d["number_invoice"]);
                            excelCells = excelWorkSheets.get_Range("D" + positionInRows);
                            excelCells.Value2 = d["date_invoice"] != null ? Convert.ToDateTime(Convert.ToString(d["date_invoice"])).ToString("d") : "";
                            double pr;
                            excelCells = excelWorkSheets.get_Range("E" + positionInRows);
                            excelCells.Value2 = Convert.ToString(double.TryParse(Convert.ToString(d["amount_p_invoice"]), out pr) ? pr.ToString("N2") : d["amount_p_invoice"]);
                            excelCells = excelWorkSheets.get_Range("F" + positionInRows);
                            excelCells.Value2 = Convert.ToString(d["name_view_contract"]);
                            excelCells = excelWorkSheets.get_Range("G" + positionInRows);
                            excelCells.Value2 = Convert.ToString(d["item_contract"]);
                            positionInRows++;
                        }
                    //Отображение сетки для таблицы, а также автоперенос и выравнивая по вертикали по центру
                    excelCells = excelWorkSheets.get_Range("A3", "G" + (positionInRows - 1));
                    excelCells.Borders.LineStyle = Excel.XlLineStyle.xlContinuous;
                    excelCells.Borders.Weight = Excel.XlBorderWeight.xlThin;
                    excelCells.WrapText = true;
                    excelCells.VerticalAlignment = Excel.Constants.xlCenter;
                    //Изменение шрифта
                    excelCells = excelWorkSheets.get_Range("A1", "G" + positionInRows);
                    excelCells.Font.Name = "Times New Roman";
                    excelCells.Font.Size = 12;
                    //Сохранение
                    DateTime dateNowFileName = DateTime.Now;
                    string strSaveName = Convert.ToString(dateNowFileName);
                    strSaveName = strSaveName.Replace(':', '-');
                    //Console.Write(storagePath + userSaveName + " " + strSaveName + ".xlsx");
                    savePath = Properties.Settings.Default.ReportPath + userSaveName + " " + strSaveName + ".xlsx";
                    excelWorkBook.SaveAs(savePath);
                    excelWorkBook.Close();
                    oExcel.Quit();
                }
                return ("Завершено!");
            }
            catch (Exception ex)
            {
                return ("error: " + ex.Message);
            }
        }

        #endregion



        //---------ОУД---------
        #region ОУД
        //Справка о согласованиях совершения крупных сделок
        static public string CreateReportOUD1(string userSaveName, string typeDocumet, bool isVisibleGenerate)
        {
            string command = "http://" + server + "/orc_new/public/unprotected/get_orc_report_oud";

            string storagePath = Properties.Settings.Default.ReportPath;

            try
            {
                if (typeDocumet == "Word")
                {
                    //Создаем документ
                    Word.Application oWord = new Word.Application();
                    oWord.Visible = false;
                    if (isVisibleGenerate)
                        oWord.Visible = true;
                    Object template = Type.Missing;
                    Object newTemplate = false;
                    Object documentType = Word.WdNewDocumentType.wdNewBlankDocument;
                    //Object visible = true;
                    oWord.Documents.Add(ref template, ref newTemplate, ref documentType);
                    Word.Document wordDocument = (Word.Document)oWord.Documents.get_Item(1);
                    //Альбомная ориентация
                    wordDocument.PageSetup.Orientation = Word.WdOrientation.wdOrientLandscape;
                    //Добавление шапки в файл
                    wordDocument.Paragraphs.Add();
                    var range = wordDocument.Paragraphs[1].Range;
                    range.Text = "Справка о согласованиях, полученных в текущем и предшествующем году, с указанием условий совершения соответствующих сделок";
                    range.ParagraphFormat.Alignment = Word.WdParagraphAlignment.wdAlignParagraphCenter;
                    wordDocument.Paragraphs.Add();
                    range = wordDocument.Paragraphs[2].Range;
                    range.Text = "за период: 01.01.2019 г. - 19.09.2019 г.";
                    wordDocument.Paragraphs.Add();
                    range = wordDocument.Paragraphs[3].Range;
                    range.Text = "по состоянию на 19.09.2019 г.";
                    range.ParagraphFormat.Alignment = Word.WdParagraphAlignment.wdAlignParagraphRight;
                    //Добавление в файл таблицы
                    wordDocument.Paragraphs.Add();
                    Object autoFitBehavior = Word.WdAutoFitBehavior.wdAutoFitWindow;
                    range = wordDocument.Paragraphs[wordDocument.Paragraphs.Count].Range;
                    wordDocument.Tables.Add(range, 1, 8, autoFitBehavior);
                    Word.Table oTable = wordDocument.Tables[1];
                    string[] nameHeaderTable = { "Дата и номер обращения о согласовании сделки (указывается входящий номер Минпромторга России)", "Предмет сделки", "Сумма (в руб., долларах США или иной валюте)", "Дата и номер договора по сделке", "Срок действия согласуемой сделки", "Номер и дата письма Минпромторга России, согласовывающего сделку", "Дата получения (кредита, векселя, кредитной линии, изменения условий сделки и др.)", "Дата исполнения обязательств по сделки" };
                    for (int i = 0; i < oTable.Columns.Count; i++)
                    {
                        Word.Range wordCellRange = oTable.Cell(1, i + 1).Range;
                        wordCellRange.ParagraphFormat.Alignment = Word.WdParagraphAlignment.wdAlignParagraphCenter;
                        wordCellRange.Text = nameHeaderTable[i];
                    }
                    //Запрос
                    dynamic json = null;

                    // GET
                    /*
                    using (WebClient wc = new WebClient())
                    {
                        System.Windows.Forms.MessageBox.Show("asd");
                        string response2 = wc.DownloadString(command);
                        json = JsonConvert.DeserializeObject(response2);
                        System.Windows.Forms.MessageBox.Show(json.ToString());
                    }
                     * */

                    // POST
                    using (WebClient wc = new WebClient())
                    {
                        var pars = new System.Collections.Specialized.NameValueCollection();
                        pars.Add("format", "json");

                        byte[] response2 = wc.UploadValues(command, "POST", pars);

                        Encoding en = Encoding.Default;
                        string byteToStr = en.GetString(response2, 0, response2.Length);

                        json = JsonConvert.DeserializeObject(byteToStr);
                    }

                    // GET
                    /*
                    WebRequest request = WebRequest.Create(command);
                    WebResponse response = request.GetResponse();
                    using (Stream stream = response.GetResponseStream())
                    {
                        using (StreamReader reader = new StreamReader(stream))
                        {
                            json = JsonConvert.DeserializeObject(reader.ReadToEnd());
                        }
                    }
                     * */

                    //json = null;
                    if (json != null)
                    {
                        int posRows = 2;
                        foreach (dynamic d in json)
                        {
                            oTable.Rows.Add();
                            Word.Range wordCellRange = oTable.Cell(posRows, 1).Range;
                            wordCellRange.Text = Convert.ToString(d["number_inquiry_reestr"]) + " " + Convert.ToString(d["date_inquiry_reestr"]);
                            wordCellRange = oTable.Cell(posRows, 2).Range;
                            wordCellRange.Text = Convert.ToString(d["item_contract"]);
                            wordCellRange = oTable.Cell(posRows, 3).Range;
                            wordCellRange.Text = Convert.ToString(d["amount_contract_reestr"]);
                            wordCellRange = oTable.Cell(posRows, 4).Range;
                            wordCellRange.Text = Convert.ToString(d["number_contract"]);
                            wordCellRange = oTable.Cell(posRows, 5).Range;
                            wordCellRange.Text = Convert.ToString(d["date_contract_reestr"]);
                            wordCellRange = oTable.Cell(posRows, 6).Range;
                            wordCellRange.Text = Convert.ToString(d["number_answer_reestr"]) + " " + Convert.ToString(d["date_answer_reestr"]);
                            wordCellRange = oTable.Cell(posRows, 8).Range;
                            wordCellRange.Text = Convert.ToString(d["date_maturity_reestr"]);
                            posRows++;
                        }
                    }
                    //Подпись руководителя
                    wordDocument.Paragraphs.Add();
                    range = wordDocument.Paragraphs[wordDocument.Paragraphs.Count].Range;
                    range.Text = "Начальник отдела управления договорами\t\t\t\t\t\tГуринова Н.М.";
                    range.ParagraphFormat.Alignment = Word.WdParagraphAlignment.wdAlignParagraphCenter;
                    //Сохранение
                    DateTime dateNowFileName = DateTime.Now;
                    string strSaveName = Convert.ToString(dateNowFileName);
                    strSaveName = strSaveName.Replace(':', '-');
                    //Console.WriteLine(storagePath + userSaveName + " " + strSaveName + ".docx");
                    wordDocument.SaveAs(storagePath + userSaveName + " " + strSaveName + ".docx");
                    wordDocument.Close();
                    oWord.Quit();
                }
                else
                {
                    //Создание документа Excel
                    Excel.Application oExcel = new Excel.Application();
                    oExcel.Visible = false;
                    if (isVisibleGenerate)
                        oExcel.Visible = true;
                    oExcel.SheetsInNewWorkbook = 1;
                    oExcel.Workbooks.Add();
                    Excel.Workbooks excelWorkBooks = oExcel.Workbooks;
                    Excel.Workbook excelWorkBook = excelWorkBooks[1];
                    excelWorkBook.Saved = false;
                    Excel.Sheets excelSheets = oExcel.Worksheets;
                    Excel.Worksheet excelWorkSheets = excelSheets.get_Item(1);
                    Excel.Range excelCells;
                    //---типо Титульник---
                    //Строка 1
                    excelCells = excelWorkSheets.get_Range("A1", "H1");
                    excelCells.Merge();
                    excelCells.Value2 = "Справка о согласованиях, полученных в текущем и предшествующем году, с указанием условий совершения соответствующих сделок";
                    excelCells.WrapText = true;
                    excelCells.Font.Bold = true;
                    excelCells.HorizontalAlignment = Excel.Constants.xlCenter;
                    excelCells.VerticalAlignment = Excel.Constants.xlCenter;
                    excelCells.RowHeight = 34;
                    //Строка 2
                    excelCells = excelWorkSheets.get_Range("A2", "H2");
                    excelCells.Merge();
                    excelCells.Value2 = "за период: 01.01.2019 г. - 19.09.2019 г.";
                    excelCells.HorizontalAlignment = Excel.Constants.xlCenter;
                    excelCells.VerticalAlignment = Excel.Constants.xlCenter;
                    //Строка 2
                    excelCells = excelWorkSheets.get_Range("A3", "H3");
                    excelCells.Merge();
                    excelCells.Value2 = "по состоянию на 19.09.2019 г. ";
                    excelCells.HorizontalAlignment = Excel.Constants.xlRight;
                    excelCells.VerticalAlignment = Excel.Constants.xlCenter;
                    //Заполнения даннами шапки
                    int positionInRows = 4;
                    excelCells = excelWorkSheets.get_Range("A" + positionInRows);
                    excelCells.Value2 = "Дата и номер обращения о согласовании сделки (указывается входящий номер Минпромторга России)";
                    excelCells.ColumnWidth = 25;
                    excelCells = excelWorkSheets.get_Range("B" + positionInRows);
                    excelCells.Value2 = "Предмет сделки";
                    excelCells.ColumnWidth = 35;
                    excelCells = excelWorkSheets.get_Range("C" + positionInRows);
                    excelCells.Value2 = "Сумма (в руб., долларах США или иной валюте)";
                    excelCells.ColumnWidth = 18;
                    excelCells = excelWorkSheets.get_Range("D" + positionInRows);
                    excelCells.Value2 = "Дата и номер договора по сделке";
                    excelCells.ColumnWidth = 18;
                    excelCells = excelWorkSheets.get_Range("E" + positionInRows);
                    excelCells.Value2 = "Срок действия согласуемой сделки";
                    excelCells.ColumnWidth = 35;
                    excelCells = excelWorkSheets.get_Range("F" + positionInRows);
                    excelCells.Value2 = "Номер и дата письма Минпромторга России, согласовывающего сделку";
                    excelCells.ColumnWidth = 27;
                    excelCells = excelWorkSheets.get_Range("G" + positionInRows);
                    excelCells.Value2 = "Дата получения (кредита, векселя, кредитной линии, изменения условий сделки и др.)";
                    excelCells.ColumnWidth = 20;
                    excelCells = excelWorkSheets.get_Range("H" + positionInRows);
                    excelCells.Value2 = "Дата исполнения обязательств по сделки";
                    excelCells.ColumnWidth = 50;
                    //Настройка выравнивания и автопереноса
                    excelCells = excelWorkSheets.get_Range("A" + positionInRows, "H" + positionInRows);
                    excelCells.WrapText = true;
                    excelCells.HorizontalAlignment = Excel.Constants.xlCenter;
                    excelCells.VerticalAlignment = Excel.Constants.xlCenter;
                    positionInRows++;
                    //Настройки автоширины
                    //excelWorkSheets.Columns[1].EntireColumn.AutoFit();
                    //Запрос
                    dynamic json = null;
                    WebRequest request = WebRequest.Create(command);
                    WebResponse response = request.GetResponse();
                    using (Stream stream = response.GetResponseStream())
                    {
                        using (StreamReader reader = new StreamReader(stream))
                        {
                            json = JsonConvert.DeserializeObject(reader.ReadToEnd());
                        }
                    }
                    if (json != null)
                        foreach (dynamic d in json)
                        {
                            excelCells = excelWorkSheets.get_Range("A" + positionInRows);
                            excelCells.Value2 = Convert.ToString(d["number_inquiry_reestr"]) + " " + Convert.ToString(d["date_inquiry_reestr"]);
                            excelCells = excelWorkSheets.get_Range("B" + positionInRows);
                            excelCells.Value2 = Convert.ToString(d["item_contract"]);
                            excelCells = excelWorkSheets.get_Range("C" + positionInRows);
                            excelCells.Value2 = Convert.ToString(d["amount_contract_reestr"]);
                            excelCells = excelWorkSheets.get_Range("D" + positionInRows);
                            excelCells.Value2 = Convert.ToString(d["number_contract"]);
                            excelCells = excelWorkSheets.get_Range("E" + positionInRows);
                            excelCells.Value2 = Convert.ToString(d["date_contract_reestr"]);
                            excelCells = excelWorkSheets.get_Range("F" + positionInRows);
                            excelCells.Value2 = Convert.ToString(d["number_answer_reestr"]) + " " + Convert.ToString(d["date_answer_reestr"]);
                            excelCells = excelWorkSheets.get_Range("H" + positionInRows);
                            excelCells.Value2 = Convert.ToString(d["date_maturity_reestr"]);
                            positionInRows++;
                        }
                    //Отображение сетки для таблицы, а также автоперенос и выравнивая по вертикали по центру
                    excelCells = excelWorkSheets.get_Range("A4", "H" + (positionInRows - 1));
                    excelCells.Borders.LineStyle = Excel.XlLineStyle.xlContinuous;
                    excelCells.Borders.Weight = Excel.XlBorderWeight.xlThin;
                    excelCells.WrapText = true;
                    excelCells.VerticalAlignment = Excel.Constants.xlCenter;
                    //Подпись руководителя
                    positionInRows++;
                    excelCells = excelWorkSheets.get_Range("A" + positionInRows, "H" + positionInRows);
                    excelCells.Merge();
                    excelCells.Value2 = "Начальник отдела управления договорами                          Гуринова Н.М.";
                    excelCells.HorizontalAlignment = Excel.Constants.xlCenter;
                    excelCells.VerticalAlignment = Excel.Constants.xlCenter;
                    //Изменение шрифта
                    excelCells = excelWorkSheets.get_Range("A1", "H" + positionInRows);
                    excelCells.Font.Name = "Times New Roman";
                    excelCells.Font.Size = 12;
                    //Бахаем какой-нибудь график в конце
                    excelCells = excelWorkSheets.get_Range("C5", "C" + (positionInRows - 2));
                    excelCells.Select();
                    Excel.Chart excelChart = (Excel.Chart)oExcel.Charts.Add();
                    excelChart.Activate();
                    excelChart.Select();
                    //Смена вида графика
                    //oExcel.ActiveChart.ChartType = Excel.XlChartType.xlLine;
                    //Создание заглавия
                    oExcel.ActiveChart.HasTitle = true;
                    oExcel.ActiveChart.ChartTitle.Text = "График зависимости суммы от порядкового номера";
                    //Подписываем ОСИ
                    //ОСЬ X
                    oExcel.ActiveChart.Axes(Excel.XlAxisType.xlCategory, Excel.XlAxisGroup.xlPrimary).HasTitle = true;
                    oExcel.ActiveChart.Axes(Excel.XlAxisType.xlCategory, Excel.XlAxisGroup.xlPrimary).AxisTitle.Text = "Порядковый номер";
                    //ОСЬ Y
                    oExcel.ActiveChart.Axes(Excel.XlAxisType.xlValue, Excel.XlAxisGroup.xlPrimary).HasTitle = true;
                    oExcel.ActiveChart.Axes(Excel.XlAxisType.xlValue, Excel.XlAxisGroup.xlPrimary).AxisTitle.Text = "Сумма денег";
                    //Отключаем отображение легенды
                    oExcel.ActiveChart.HasLegend = false;
                    //Перемещаем диаграмму на лист 1 (изначально для диаграммы создается свой лист)
                    oExcel.ActiveChart.Location(Excel.XlChartLocation.xlLocationAsObject, "Лист1");
                    //Выбираем первый лист
                    excelSheets = oExcel.Worksheets;
                    excelWorkSheets = excelSheets.get_Item(1);
                    //Перемещаем диаграмму в нужное место
                    excelWorkSheets.Shapes.Item(1).IncrementLeft(800);
                    excelWorkSheets.Shapes.Item(1).IncrementTop(-(float)100);
                    //Задаем размер диаграммы
                    excelWorkSheets.Shapes.Item(1).Height = 300;
                    excelWorkSheets.Shapes.Item(1).Width = 500;
                    //Сохранение
                    DateTime dateNowFileName = DateTime.Now;
                    string strSaveName = Convert.ToString(dateNowFileName);
                    strSaveName = strSaveName.Replace(':', '-');
                    //Console.Write(storagePath + userSaveName + " " + strSaveName + ".xlsx");
                    excelWorkBook.SaveAs(storagePath + userSaveName + " " + strSaveName + ".xlsx");
                    excelWorkBook.Close();
                    oExcel.Quit();
                }
                return ("Все оки!");
            }
            catch (Exception ex)
            {
                return ("error: " + ex.Message);
            }
        }

        static public string CreateReportOUD2(string typeDocument, string userSaveName, out string savePath, bool isVisibleGenerate = false, string departmentID = "", string date_begin = "", string date_end = "")
        {
            savePath = null;

            if (userSaveName == "")
                userSaveName = "Отчет";
            if (departmentID == "")
                departmentID = "Все подразделения";

            string command = "http://" + server + "/orc_new/public/unprotected/get_orc_report_oud";

            string storagePath = Properties.Settings.Default.ReportPath;

            try
            {
                if (typeDocument == "Word")
                {
                    //Создаем документ
                    Word.Application oWord = new Word.Application();
                    oWord.Visible = false;
                    if (isVisibleGenerate)
                        oWord.Visible = true;
                    Object template = Type.Missing;
                    Object newTemplate = false;
                    Object documentType = Word.WdNewDocumentType.wdNewBlankDocument;
                    //Object visible = true;
                    oWord.Documents.Add(ref template, ref newTemplate, ref documentType);
                    Word.Document wordDocument = (Word.Document)oWord.Documents.get_Item(1);
                    //Альбомная ориентация
                    wordDocument.PageSetup.Orientation = Word.WdOrientation.wdOrientLandscape;
                    //Добавление шапки в файл
                    wordDocument.Paragraphs.Add();
                    var range = wordDocument.Paragraphs[1].Range;
                    range.Text = "Справка: проекты Договоров/Контрактов на закуп";
                    range.ParagraphFormat.Alignment = Word.WdParagraphAlignment.wdAlignParagraphCenter;
                    wordDocument.Paragraphs.Add();
                    range = wordDocument.Paragraphs[2].Range;
                    range.Text = "за период: " + date_begin + " г. - " + date_end + " г.";
                    wordDocument.Paragraphs.Add();
                    range = wordDocument.Paragraphs[3].Range;
                    range.Text = "по состоянию на " + DateTime.Now.ToShortDateString() + " г.";
                    range.ParagraphFormat.Alignment = Word.WdParagraphAlignment.wdAlignParagraphRight;
                    //Добавление в файл таблицы
                    wordDocument.Paragraphs.Add();
                    Object autoFitBehavior = Word.WdAutoFitBehavior.wdAutoFitWindow;
                    range = wordDocument.Paragraphs[wordDocument.Paragraphs.Count].Range;
                    wordDocument.Tables.Add(range, 1, 8, autoFitBehavior);
                    Word.Table oTable = wordDocument.Tables[1];
                    string[] nameHeaderTable = { "Номер Дог./Контр. по Реестру, номер Контрагента", "Контрагент (сокращенное наименование)", "Предмет договора", "Срок исполнения", "Сумма Дог./Контр.", "Порядок расчетов", "Срок действия", "ПР, ПСР, ПУР и др.,ДС" };
                    for (int i = 0; i < oTable.Columns.Count; i++)
                    {
                        Word.Range wordCellRange = oTable.Cell(1, i + 1).Range;
                        wordCellRange.ParagraphFormat.Alignment = Word.WdParagraphAlignment.wdAlignParagraphCenter;
                        wordCellRange.Text = nameHeaderTable[i];
                    }
                    //Запрос
                    dynamic json = null;

                    // POST
                    using (WebClient wc = new WebClient())
                    {
                        var pars = new System.Collections.Specialized.NameValueCollection();
                        pars.Add("department", departmentID);
                        pars.Add("date_begin", date_begin);
                        pars.Add("date_end", date_end);
                        pars.Add("real_name_table", "проекты на закуп за период");

                        byte[] response2 = wc.UploadValues(command, "POST", pars);

                        Encoding en = Encoding.Default;
                        string byteToStr = en.GetString(response2, 0, response2.Length);

                        json = JsonConvert.DeserializeObject(byteToStr);
                    }

                    if (json != null)
                    {
                        int posRows = 2;
                        foreach (dynamic d in json["contracts"])
                        {
                            oTable.Rows.Add();
                            Word.Range wordCellRange = oTable.Cell(posRows, 1).Range;
                            wordCellRange.Text = Convert.ToString(d["number_contract"]) + "\n" + Convert.ToString(d["number_counterpartie_contract_reestr"]) + (d["date_contract_on_first_reestr"] != null ? ("от " + Convert.ToString(d["date_contract_on_first_reestr"])) : "");
                            wordCellRange = oTable.Cell(posRows, 2).Range;
                            wordCellRange.Text = Convert.ToString(d["counterpartie_name"]);
                            wordCellRange = oTable.Cell(posRows, 3).Range;
                            wordCellRange.Text = Convert.ToString(d["item_contract"]);
                            wordCellRange = oTable.Cell(posRows, 4).Range;
                            wordCellRange.Text = Convert.ToString(d["date_maturity_reestr"]);

                            double pr;
                            wordCellRange = oTable.Cell(posRows, 5).Range;
                            wordCellRange.Text = Convert.ToString(double.TryParse(Convert.ToString(d["amount_contract_reestr"]), out pr) ? pr.ToString("N2") : (double.TryParse(Convert.ToString(d["amount_reestr"]), out pr) ? pr.ToString("N2") : d["amount_reestr"]));

                            wordCellRange = oTable.Cell(posRows, 6).Range;
                            wordCellRange.Text = Convert.ToString(d["prepayment_order_reestr"]) + " " + Convert.ToString(d["score_order_reestr"]) + " " + Convert.ToString(d["payment_order_reestr"]);
                            wordCellRange = oTable.Cell(posRows, 7).Range;
                            wordCellRange.Text = Convert.ToString(d["date_contract_reestr"]);

                            wordCellRange = oTable.Cell(posRows, 8).Range;
                            string textProtocols = "";
                            if (d["protocols"] != null)
                            {
                                foreach(dynamic protocol in d["protocols"])
                                {
                                    textProtocols += protocol["name_protocol"] + " от " + protocol["date_on_first_protocol"];
                                }
                            }
                            textProtocols += "\n";
                            if (d["add_agreements"] != null)
                            {
                                foreach (dynamic add_agreement in d["add_agreements"])
                                {
                                    textProtocols += add_agreement["name_protocol"] + " от " + add_agreement["date_on_first_protocol"];
                                }
                            }
                            wordCellRange.Text = textProtocols;

                            posRows++;
                        }
                    }
                    //Подпись руководителя
                    wordDocument.Paragraphs.Add();
                    range = wordDocument.Paragraphs[wordDocument.Paragraphs.Count].Range;
                    range.Text = "Начальник отдела управления договорами\t\t\t\t\t\tГуринова Н.М.";
                    range.ParagraphFormat.Alignment = Word.WdParagraphAlignment.wdAlignParagraphCenter;
                    //Сохранение
                    DateTime dateNowFileName = DateTime.Now;
                    string strSaveName = Convert.ToString(dateNowFileName);
                    strSaveName = strSaveName.Replace(':', '-');
                    //Console.WriteLine(storagePath + userSaveName + " " + strSaveName + ".docx");
                    savePath = storagePath + userSaveName + " " + strSaveName + ".docx";
                    wordDocument.SaveAs(savePath);
                    wordDocument.Close();
                    oWord.Quit();
                }
                else
                {
                    //Создание документа Excel
                    Excel.Application oExcel = new Excel.Application();
                    oExcel.Visible = false;
                    if (isVisibleGenerate)
                        oExcel.Visible = true;
                    oExcel.SheetsInNewWorkbook = 1;
                    oExcel.Workbooks.Add();
                    Excel.Workbooks excelWorkBooks = oExcel.Workbooks;
                    Excel.Workbook excelWorkBook = excelWorkBooks[1];
                    excelWorkBook.Saved = false;
                    Excel.Sheets excelSheets = oExcel.Worksheets;
                    Excel.Worksheet excelWorkSheets = excelSheets.get_Item(1);
                    Excel.Range excelCells;
                    //---типо Титульник---
                    //Строка 1
                    excelCells = excelWorkSheets.get_Range("A1", "H1");
                    excelCells.Merge();
                    excelCells.Value2 = "Справка: проекты Договоров/Контрактов на закуп";
                    excelCells.WrapText = true;
                    excelCells.Font.Bold = true;
                    excelCells.HorizontalAlignment = Excel.Constants.xlCenter;
                    excelCells.VerticalAlignment = Excel.Constants.xlCenter;
                    excelCells.RowHeight = 34;
                    //Строка 2
                    excelCells = excelWorkSheets.get_Range("A2", "H2");
                    excelCells.Merge();
                    excelCells.Value2 = "за период: " + date_begin + " г. - " + date_end + " г.";
                    excelCells.HorizontalAlignment = Excel.Constants.xlCenter;
                    excelCells.VerticalAlignment = Excel.Constants.xlCenter;
                    //Строка 2
                    excelCells = excelWorkSheets.get_Range("A3", "H3");
                    excelCells.Merge();
                    excelCells.Value2 = "по состоянию на " + DateTime.Now.ToShortDateString() + " г.";
                    excelCells.HorizontalAlignment = Excel.Constants.xlRight;
                    excelCells.VerticalAlignment = Excel.Constants.xlCenter;
                    //Заполнения даннами шапки
                    int positionInRows = 4;
                    excelCells = excelWorkSheets.get_Range("A" + positionInRows);
                    excelCells.Value2 = "Номер Дог./Контр. по Реестру, номер Контрагента";
                    excelCells.ColumnWidth = 25;
                    excelCells = excelWorkSheets.get_Range("B" + positionInRows);
                    excelCells.Value2 = "Контрагент (сокращенное наименование)";
                    excelCells.ColumnWidth = 35;
                    excelCells = excelWorkSheets.get_Range("C" + positionInRows);
                    excelCells.Value2 = "Предмет договора";
                    excelCells.ColumnWidth = 18;
                    excelCells = excelWorkSheets.get_Range("D" + positionInRows);
                    excelCells.Value2 = "Срок исполнения";
                    excelCells.ColumnWidth = 18;
                    excelCells = excelWorkSheets.get_Range("E" + positionInRows);
                    excelCells.Value2 = "Сумма Дог./Контр.";
                    excelCells.ColumnWidth = 35;
                    excelCells = excelWorkSheets.get_Range("F" + positionInRows);
                    excelCells.Value2 = "Порядок расчетов";
                    excelCells.ColumnWidth = 27;
                    excelCells = excelWorkSheets.get_Range("G" + positionInRows);
                    excelCells.Value2 = "Срок действия";
                    excelCells.ColumnWidth = 20;
                    excelCells = excelWorkSheets.get_Range("H" + positionInRows);
                    excelCells.Value2 = "ПР, ПСР, ПУР и др.,ДС";
                    excelCells.ColumnWidth = 50;
                    //Настройка выравнивания и автопереноса
                    excelCells = excelWorkSheets.get_Range("A" + positionInRows, "H" + positionInRows);
                    excelCells.WrapText = true;
                    excelCells.HorizontalAlignment = Excel.Constants.xlCenter;
                    excelCells.VerticalAlignment = Excel.Constants.xlCenter;
                    positionInRows++;
                    //Настройки автоширины
                    //excelWorkSheets.Columns[1].EntireColumn.AutoFit();
                    //Запрос
                    dynamic json = null;

                    // POST
                    using (WebClient wc = new WebClient())
                    {
                        var pars = new System.Collections.Specialized.NameValueCollection();
                        pars.Add("department", departmentID);
                        pars.Add("date_begin", date_begin);
                        pars.Add("date_end", date_end);
                        pars.Add("real_name_table", "проекты на закуп за период");

                        byte[] response2 = wc.UploadValues(command, "POST", pars);

                        Encoding en = Encoding.Default;
                        string byteToStr = en.GetString(response2, 0, response2.Length);

                        json = JsonConvert.DeserializeObject(byteToStr);
                    }
                    if (json != null)
                        foreach (dynamic d in json["contracts"])
                        {
                            excelCells = excelWorkSheets.get_Range("A" + positionInRows);
                            excelCells.Value2 = Convert.ToString(d["number_contract"]) + "\n" + Convert.ToString(d["number_counterpartie_contract_reestr"]) + (d["date_contract_on_first_reestr"] != null ? ("от " + Convert.ToString(d["date_contract_on_first_reestr"])) : "");
                            excelCells = excelWorkSheets.get_Range("B" + positionInRows);
                            excelCells.Value2 = Convert.ToString(d["counterpartie_name"]);
                            excelCells = excelWorkSheets.get_Range("C" + positionInRows);
                            excelCells.Value2 = Convert.ToString(d["item_contract"]);
                            excelCells = excelWorkSheets.get_Range("D" + positionInRows);
                            excelCells.Value2 = Convert.ToString(d["date_maturity_reestr"]);

                            excelCells = excelWorkSheets.get_Range("E" + positionInRows);
                            double pr;
                            excelCells.Value2 = Convert.ToString(double.TryParse(Convert.ToString(d["amount_contract_reestr"]), out pr) ? pr.ToString("N2") : (double.TryParse(Convert.ToString(d["amount_reestr"]), out pr) ? pr.ToString("N2") : d["amount_reestr"]));

                            excelCells = excelWorkSheets.get_Range("F" + positionInRows);
                            excelCells.Value2 = Convert.ToString(d["prepayment_order_reestr"]) + " " + Convert.ToString(d["score_order_reestr"]) + " " + Convert.ToString(d["payment_order_reestr"]);
                            excelCells = excelWorkSheets.get_Range("G" + positionInRows);
                            excelCells.Value2 = Convert.ToString(d["date_contract_reestr"]);

                            excelCells = excelWorkSheets.get_Range("H" + positionInRows);
                            string textProtocols = "";
                            if (d["protocols"] != null)
                            {
                                foreach (dynamic protocol in d["protocols"])
                                {
                                    textProtocols += protocol["name_protocol"] + " от " + protocol["date_on_first_protocol"];
                                }
                            }
                            textProtocols += "\n";
                            if (d["add_agreements"] != null)
                            {
                                foreach (dynamic add_agreement in d["add_agreements"])
                                {
                                    textProtocols += add_agreement["name_protocol"] + " от " + add_agreement["date_on_first_protocol"];
                                }
                            }
                            excelCells.Value2 = textProtocols;

                            positionInRows++;
                        }
                    //Отображение сетки для таблицы, а также автоперенос и выравнивая по вертикали по центру
                    excelCells = excelWorkSheets.get_Range("A4", "H" + (positionInRows - 1));
                    excelCells.Borders.LineStyle = Excel.XlLineStyle.xlContinuous;
                    excelCells.Borders.Weight = Excel.XlBorderWeight.xlThin;
                    excelCells.WrapText = true;
                    excelCells.VerticalAlignment = Excel.Constants.xlCenter;
                    //Подпись руководителя
                    positionInRows++;
                    excelCells = excelWorkSheets.get_Range("A" + positionInRows, "H" + positionInRows);
                    excelCells.Merge();
                    excelCells.Value2 = "Начальник отдела управления договорами                          Гуринова Н.М.";
                    excelCells.HorizontalAlignment = Excel.Constants.xlCenter;
                    excelCells.VerticalAlignment = Excel.Constants.xlCenter;
                    //Изменение шрифта
                    excelCells = excelWorkSheets.get_Range("A1", "H" + positionInRows);
                    excelCells.Font.Name = "Times New Roman";
                    excelCells.Font.Size = 12;

                    //Сохранение
                    DateTime dateNowFileName = DateTime.Now;
                    string strSaveName = Convert.ToString(dateNowFileName);
                    strSaveName = strSaveName.Replace(':', '-');
                    //Console.Write(storagePath + userSaveName + " " + strSaveName + ".xlsx");
                    savePath = storagePath + userSaveName + " " + strSaveName + ".xlsx";
                    excelWorkBook.SaveAs(savePath);
                    excelWorkBook.Close();
                    oExcel.Quit();
                }
                return ("Все оки!");
            }
            catch (Exception ex)
            {
                return ("error: " + ex.Message);
            }
        }

        static public string CreateReportOUD3(string typeDocument, string userSaveName, out string savePath, bool isVisibleGenerate = false, string departmentID = "", string date_begin = "", string date_end = "")
        {
            savePath = null;

            if (userSaveName == "")
                userSaveName = "Отчет";
            if (departmentID == "")
                departmentID = "Все подразделения";

            string command = "http://" + server + "/orc_new/public/unprotected/get_orc_report_oud";

            string storagePath = Properties.Settings.Default.ReportPath;

            try
            {
                if (typeDocument == "Word")
                {
                    //Создаем документ
                    Word.Application oWord = new Word.Application();
                    oWord.Visible = false;
                    if (isVisibleGenerate)
                        oWord.Visible = true;
                    Object template = Type.Missing;
                    Object newTemplate = false;
                    Object documentType = Word.WdNewDocumentType.wdNewBlankDocument;
                    //Object visible = true;
                    oWord.Documents.Add(ref template, ref newTemplate, ref documentType);
                    Word.Document wordDocument = (Word.Document)oWord.Documents.get_Item(1);
                    //Альбомная ориентация
                    wordDocument.PageSetup.Orientation = Word.WdOrientation.wdOrientLandscape;
                    //Добавление шапки в файл
                    wordDocument.Paragraphs.Add();
                    var range = wordDocument.Paragraphs[1].Range;
                    range.Text = "Справка: проекты Договоров/Контрактов на сбыт";
                    range.ParagraphFormat.Alignment = Word.WdParagraphAlignment.wdAlignParagraphCenter;
                    wordDocument.Paragraphs.Add();
                    range = wordDocument.Paragraphs[2].Range;
                    range.Text = "за период: " + date_begin + " г. - " + date_end + " г.";
                    wordDocument.Paragraphs.Add();
                    range = wordDocument.Paragraphs[3].Range;
                    range.Text = "по состоянию на " + DateTime.Now.ToShortDateString() + " г.";
                    range.ParagraphFormat.Alignment = Word.WdParagraphAlignment.wdAlignParagraphRight;
                    //Добавление в файл таблицы
                    wordDocument.Paragraphs.Add();
                    Object autoFitBehavior = Word.WdAutoFitBehavior.wdAutoFitWindow;
                    range = wordDocument.Paragraphs[wordDocument.Paragraphs.Count].Range;
                    wordDocument.Tables.Add(range, 1, 8, autoFitBehavior);
                    Word.Table oTable = wordDocument.Tables[1];
                    string[] nameHeaderTable = { "Номер Дог./Контр. по Реестру, номер Контрагента", "Контрагент (сокращенное наименование)", "Предмет договора", "Срок исполнения", "Сумма Дог./Контр.", "Порядок расчетов", "Срок действия", "ПР, ПСР, ПУР и др.,ДС" };
                    for (int i = 0; i < oTable.Columns.Count; i++)
                    {
                        Word.Range wordCellRange = oTable.Cell(1, i + 1).Range;
                        wordCellRange.ParagraphFormat.Alignment = Word.WdParagraphAlignment.wdAlignParagraphCenter;
                        wordCellRange.Text = nameHeaderTable[i];
                    }
                    //Запрос
                    dynamic json = null;

                    // POST
                    using (WebClient wc = new WebClient())
                    {
                        var pars = new System.Collections.Specialized.NameValueCollection();
                        pars.Add("department", departmentID);
                        pars.Add("date_begin", date_begin);
                        pars.Add("date_end", date_end);
                        pars.Add("real_name_table", "проекты на сбыт за период");

                        byte[] response2 = wc.UploadValues(command, "POST", pars);

                        Encoding en = Encoding.Default;
                        string byteToStr = en.GetString(response2, 0, response2.Length);

                        json = JsonConvert.DeserializeObject(byteToStr);
                    }

                    if (json != null)
                    {
                        int posRows = 2;
                        foreach (dynamic d in json["contracts"])
                        {
                            oTable.Rows.Add();
                            Word.Range wordCellRange = oTable.Cell(posRows, 1).Range;
                            wordCellRange.Text = Convert.ToString(d["number_contract"]) + "\n" + Convert.ToString(d["number_counterpartie_contract_reestr"]) + (d["date_contract_on_first_reestr"] != null ? ("от " + Convert.ToString(d["date_contract_on_first_reestr"])) : "");
                            wordCellRange = oTable.Cell(posRows, 2).Range;
                            wordCellRange.Text = Convert.ToString(d["counterpartie_name"]);
                            wordCellRange = oTable.Cell(posRows, 3).Range;
                            wordCellRange.Text = Convert.ToString(d["item_contract"]);
                            wordCellRange = oTable.Cell(posRows, 4).Range;
                            wordCellRange.Text = Convert.ToString(d["date_maturity_reestr"]);

                            double pr;
                            wordCellRange = oTable.Cell(posRows, 5).Range;
                            wordCellRange.Text = Convert.ToString(double.TryParse(Convert.ToString(d["amount_contract_reestr"]), out pr) ? pr.ToString("N2") : (double.TryParse(Convert.ToString(d["amount_reestr"]), out pr) ? pr.ToString("N2") : d["amount_reestr"]));

                            wordCellRange = oTable.Cell(posRows, 6).Range;
                            wordCellRange.Text = Convert.ToString(d["prepayment_order_reestr"]) + " " + Convert.ToString(d["score_order_reestr"]) + " " + Convert.ToString(d["payment_order_reestr"]);
                            wordCellRange = oTable.Cell(posRows, 7).Range;
                            wordCellRange.Text = Convert.ToString(d["date_contract_reestr"]);

                            wordCellRange = oTable.Cell(posRows, 8).Range;
                            string textProtocols = "";
                            if (d["protocols"] != null)
                            {
                                foreach (dynamic protocol in d["protocols"])
                                {
                                    textProtocols += protocol["name_protocol"] + " от " + protocol["date_on_first_protocol"];
                                }
                            }
                            textProtocols += "\n";
                            if (d["add_agreements"] != null)
                            {
                                foreach (dynamic add_agreement in d["add_agreements"])
                                {
                                    textProtocols += add_agreement["name_protocol"] + " от " + add_agreement["date_on_first_protocol"];
                                }
                            }
                            wordCellRange.Text = textProtocols;

                            posRows++;
                        }
                    }
                    //Подпись руководителя
                    wordDocument.Paragraphs.Add();
                    range = wordDocument.Paragraphs[wordDocument.Paragraphs.Count].Range;
                    range.Text = "Начальник отдела управления договорами\t\t\t\t\t\tГуринова Н.М.";
                    range.ParagraphFormat.Alignment = Word.WdParagraphAlignment.wdAlignParagraphCenter;
                    //Сохранение
                    DateTime dateNowFileName = DateTime.Now;
                    string strSaveName = Convert.ToString(dateNowFileName);
                    strSaveName = strSaveName.Replace(':', '-');
                    //Console.WriteLine(storagePath + userSaveName + " " + strSaveName + ".docx");
                    savePath = storagePath + userSaveName + " " + strSaveName + ".docx";
                    wordDocument.SaveAs(savePath);
                    wordDocument.Close();
                    oWord.Quit();
                }
                else
                {
                    //Создание документа Excel
                    Excel.Application oExcel = new Excel.Application();
                    oExcel.Visible = false;
                    if (isVisibleGenerate)
                        oExcel.Visible = true;
                    oExcel.SheetsInNewWorkbook = 1;
                    oExcel.Workbooks.Add();
                    Excel.Workbooks excelWorkBooks = oExcel.Workbooks;
                    Excel.Workbook excelWorkBook = excelWorkBooks[1];
                    excelWorkBook.Saved = false;
                    Excel.Sheets excelSheets = oExcel.Worksheets;
                    Excel.Worksheet excelWorkSheets = excelSheets.get_Item(1);
                    Excel.Range excelCells;
                    //---типо Титульник---
                    //Строка 1
                    excelCells = excelWorkSheets.get_Range("A1", "H1");
                    excelCells.Merge();
                    excelCells.Value2 = "Справка: проекты Договоров/Контрактов на сбыт";
                    excelCells.WrapText = true;
                    excelCells.Font.Bold = true;
                    excelCells.HorizontalAlignment = Excel.Constants.xlCenter;
                    excelCells.VerticalAlignment = Excel.Constants.xlCenter;
                    excelCells.RowHeight = 34;
                    //Строка 2
                    excelCells = excelWorkSheets.get_Range("A2", "H2");
                    excelCells.Merge();
                    excelCells.Value2 = "за период: " + date_begin + " г. - " + date_end + " г.";
                    excelCells.HorizontalAlignment = Excel.Constants.xlCenter;
                    excelCells.VerticalAlignment = Excel.Constants.xlCenter;
                    //Строка 2
                    excelCells = excelWorkSheets.get_Range("A3", "H3");
                    excelCells.Merge();
                    excelCells.Value2 = "по состоянию на " + DateTime.Now.ToShortDateString() + " г.";
                    excelCells.HorizontalAlignment = Excel.Constants.xlRight;
                    excelCells.VerticalAlignment = Excel.Constants.xlCenter;
                    //Заполнения даннами шапки
                    int positionInRows = 4;
                    excelCells = excelWorkSheets.get_Range("A" + positionInRows);
                    excelCells.Value2 = "Номер Дог./Контр. по Реестру, номер Контрагента";
                    excelCells.ColumnWidth = 25;
                    excelCells = excelWorkSheets.get_Range("B" + positionInRows);
                    excelCells.Value2 = "Контрагент (сокращенное наименование)";
                    excelCells.ColumnWidth = 35;
                    excelCells = excelWorkSheets.get_Range("C" + positionInRows);
                    excelCells.Value2 = "Предмет договора";
                    excelCells.ColumnWidth = 18;
                    excelCells = excelWorkSheets.get_Range("D" + positionInRows);
                    excelCells.Value2 = "Срок исполнения";
                    excelCells.ColumnWidth = 18;
                    excelCells = excelWorkSheets.get_Range("E" + positionInRows);
                    excelCells.Value2 = "Сумма Дог./Контр.";
                    excelCells.ColumnWidth = 35;
                    excelCells = excelWorkSheets.get_Range("F" + positionInRows);
                    excelCells.Value2 = "Порядок расчетов";
                    excelCells.ColumnWidth = 27;
                    excelCells = excelWorkSheets.get_Range("G" + positionInRows);
                    excelCells.Value2 = "Срок действия";
                    excelCells.ColumnWidth = 20;
                    excelCells = excelWorkSheets.get_Range("H" + positionInRows);
                    excelCells.Value2 = "ПР, ПСР, ПУР и др.,ДС";
                    excelCells.ColumnWidth = 50;
                    //Настройка выравнивания и автопереноса
                    excelCells = excelWorkSheets.get_Range("A" + positionInRows, "H" + positionInRows);
                    excelCells.WrapText = true;
                    excelCells.HorizontalAlignment = Excel.Constants.xlCenter;
                    excelCells.VerticalAlignment = Excel.Constants.xlCenter;
                    positionInRows++;
                    //Настройки автоширины
                    //excelWorkSheets.Columns[1].EntireColumn.AutoFit();
                    //Запрос
                    dynamic json = null;

                    // POST
                    using (WebClient wc = new WebClient())
                    {
                        var pars = new System.Collections.Specialized.NameValueCollection();
                        pars.Add("department", departmentID);
                        pars.Add("date_begin", date_begin);
                        pars.Add("date_end", date_end);
                        pars.Add("real_name_table", "проекты на сбыт за период");

                        byte[] response2 = wc.UploadValues(command, "POST", pars);

                        Encoding en = Encoding.Default;
                        string byteToStr = en.GetString(response2, 0, response2.Length);

                        json = JsonConvert.DeserializeObject(byteToStr);
                    }
                    if (json != null)
                        foreach (dynamic d in json["contracts"])
                        {
                            excelCells = excelWorkSheets.get_Range("A" + positionInRows);
                            excelCells.Value2 = Convert.ToString(d["number_contract"]) + "\n" + Convert.ToString(d["number_counterpartie_contract_reestr"]) + (d["date_contract_on_first_reestr"] != null ? ("от " + Convert.ToString(d["date_contract_on_first_reestr"])) : "");
                            excelCells = excelWorkSheets.get_Range("B" + positionInRows);
                            excelCells.Value2 = Convert.ToString(d["counterpartie_name"]);
                            excelCells = excelWorkSheets.get_Range("C" + positionInRows);
                            excelCells.Value2 = Convert.ToString(d["item_contract"]);
                            excelCells = excelWorkSheets.get_Range("D" + positionInRows);
                            excelCells.Value2 = Convert.ToString(d["date_maturity_reestr"]);

                            excelCells = excelWorkSheets.get_Range("E" + positionInRows);
                            double pr;
                            excelCells.Value2 = Convert.ToString(double.TryParse(Convert.ToString(d["amount_contract_reestr"]), out pr) ? pr.ToString("N2") : (double.TryParse(Convert.ToString(d["amount_reestr"]), out pr) ? pr.ToString("N2") : d["amount_reestr"]));

                            excelCells = excelWorkSheets.get_Range("F" + positionInRows);
                            excelCells.Value2 = Convert.ToString(d["prepayment_order_reestr"]) + " " + Convert.ToString(d["score_order_reestr"]) + " " + Convert.ToString(d["payment_order_reestr"]);
                            excelCells = excelWorkSheets.get_Range("G" + positionInRows);
                            excelCells.Value2 = Convert.ToString(d["date_contract_reestr"]);

                            excelCells = excelWorkSheets.get_Range("H" + positionInRows);
                            string textProtocols = "";
                            if (d["protocols"] != null)
                            {
                                foreach (dynamic protocol in d["protocols"])
                                {
                                    textProtocols += protocol["name_protocol"] + " от " + protocol["date_on_first_protocol"];
                                }
                            }
                            textProtocols += "\n";
                            if (d["add_agreements"] != null)
                            {
                                foreach (dynamic add_agreement in d["add_agreements"])
                                {
                                    textProtocols += add_agreement["name_protocol"] + " от " + add_agreement["date_on_first_protocol"];
                                }
                            }
                            excelCells.Value2 = textProtocols;

                            positionInRows++;
                        }
                    //Отображение сетки для таблицы, а также автоперенос и выравнивая по вертикали по центру
                    excelCells = excelWorkSheets.get_Range("A4", "H" + (positionInRows - 1));
                    excelCells.Borders.LineStyle = Excel.XlLineStyle.xlContinuous;
                    excelCells.Borders.Weight = Excel.XlBorderWeight.xlThin;
                    excelCells.WrapText = true;
                    excelCells.VerticalAlignment = Excel.Constants.xlCenter;
                    //Подпись руководителя
                    positionInRows++;
                    excelCells = excelWorkSheets.get_Range("A" + positionInRows, "H" + positionInRows);
                    excelCells.Merge();
                    excelCells.Value2 = "Начальник отдела управления договорами                          Гуринова Н.М.";
                    excelCells.HorizontalAlignment = Excel.Constants.xlCenter;
                    excelCells.VerticalAlignment = Excel.Constants.xlCenter;
                    //Изменение шрифта
                    excelCells = excelWorkSheets.get_Range("A1", "H" + positionInRows);
                    excelCells.Font.Name = "Times New Roman";
                    excelCells.Font.Size = 12;

                    //Сохранение
                    DateTime dateNowFileName = DateTime.Now;
                    string strSaveName = Convert.ToString(dateNowFileName);
                    strSaveName = strSaveName.Replace(':', '-');
                    //Console.Write(storagePath + userSaveName + " " + strSaveName + ".xlsx");
                    savePath = storagePath + userSaveName + " " + strSaveName + ".xlsx";
                    excelWorkBook.SaveAs(savePath);
                    excelWorkBook.Close();
                    oExcel.Quit();
                }
                return ("Все оки!");
            }
            catch (Exception ex)
            {
                return ("error: " + ex.Message);
            }
        }

        #endregion


        //---------ПЭО---------
        #region ПЭО

        // Отчёт ПЭО (в стадии выполнения)
        static public string CreateReportPEO2(string typeDocument, string userSaveName, out string savePath, bool isVisibleGenerate = false, string counterpartieID = "", List<string> departmentName = null)
        {
            savePath = null;

            if (userSaveName == "")
                userSaveName = "Отчет";

            string command = "http://" + server + "/orc_new/public/unprotected/get_orc_report_peo_no_execute";

            string storagePath = Properties.Settings.Default.ReportPath;

            try
            {
                if (typeDocument == "Word")
                {
                    return "Отчёт ещё не готов!";
                    //Создаем документ
                    Word.Application oWord = new Word.Application();
                    oWord.Visible = false;
                    if (isVisibleGenerate)
                        oWord.Visible = true;
                    Object template = Type.Missing;
                    Object newTemplate = false;
                    Object documentType = Word.WdNewDocumentType.wdNewBlankDocument;
                    //Object visible = true;
                    oWord.Documents.Add(ref template, ref newTemplate, ref documentType);
                    Word.Document wordDocument = (Word.Document)oWord.Documents.get_Item(1);
                    //Альбомная ориентация
                    wordDocument.PageSetup.Orientation = Word.WdOrientation.wdOrientLandscape;
                    //Добавление шапки в файл
                    wordDocument.Paragraphs.Add();
                    var range = wordDocument.Paragraphs[1].Range;
                    range.Text = "Справка: проекты Договоров/Контрактов на сбыт";
                    range.ParagraphFormat.Alignment = Word.WdParagraphAlignment.wdAlignParagraphCenter;
                    wordDocument.Paragraphs.Add();
                    range = wordDocument.Paragraphs[2].Range;
                    wordDocument.Paragraphs.Add();
                    range = wordDocument.Paragraphs[3].Range;
                    range.Text = "по состоянию на " + DateTime.Now.ToShortDateString() + " г.";
                    range.ParagraphFormat.Alignment = Word.WdParagraphAlignment.wdAlignParagraphRight;
                    //Добавление в файл таблицы
                    wordDocument.Paragraphs.Add();
                    Object autoFitBehavior = Word.WdAutoFitBehavior.wdAutoFitWindow;
                    range = wordDocument.Paragraphs[wordDocument.Paragraphs.Count].Range;
                    wordDocument.Tables.Add(range, 1, 8, autoFitBehavior);
                    Word.Table oTable = wordDocument.Tables[1];
                    string[] nameHeaderTable = { "Номер Дог./Контр. по Реестру, номер Контрагента", "Контрагент (сокращенное наименование)", "Предмет договора", "Срок исполнения", "Сумма Дог./Контр.", "Порядок расчетов", "Срок действия", "ПР, ПСР, ПУР и др.,ДС" };
                    for (int i = 0; i < oTable.Columns.Count; i++)
                    {
                        Word.Range wordCellRange = oTable.Cell(1, i + 1).Range;
                        wordCellRange.ParagraphFormat.Alignment = Word.WdParagraphAlignment.wdAlignParagraphCenter;
                        wordCellRange.Text = nameHeaderTable[i];
                    }
                    //Запрос
                    dynamic json = null;

                    // POST
                    using (WebClient wc = new WebClient())
                    {
                        var pars = new System.Collections.Specialized.NameValueCollection();
                        //pars.Add("department", departmentID[0]);
                        pars.Add("real_name_table", "проекты на сбыт за период");

                        byte[] response2 = wc.UploadValues(command, "POST", pars);

                        Encoding en = Encoding.Default;
                        string byteToStr = en.GetString(response2, 0, response2.Length);

                        json = JsonConvert.DeserializeObject(byteToStr);
                    }

                    if (json != null)
                    {
                        int posRows = 2;
                        foreach (dynamic d in json["contracts"])
                        {
                            oTable.Rows.Add();
                            Word.Range wordCellRange = oTable.Cell(posRows, 1).Range;
                            wordCellRange.Text = Convert.ToString(d["number_contract"]) + "\n" + Convert.ToString(d["number_counterpartie_contract_reestr"]) + (d["date_contract_on_first_reestr"] != null ? ("от " + Convert.ToString(d["date_contract_on_first_reestr"])) : "");



                            posRows++;
                        }
                    }
                    wordDocument.Paragraphs.Add();
                    //Сохранение
                    DateTime dateNowFileName = DateTime.Now;
                    string strSaveName = Convert.ToString(dateNowFileName);
                    strSaveName = strSaveName.Replace(':', '-');
                    //Console.WriteLine(storagePath + userSaveName + " " + strSaveName + ".docx");
                    savePath = storagePath + userSaveName + " " + strSaveName + ".docx";
                    wordDocument.SaveAs(savePath);
                    wordDocument.Close();
                    oWord.Quit();
                }
                else
                {
                    //Создание документа Excel
                    Excel.Application oExcel = new Excel.Application();
                    oExcel.Visible = false;
                    if (isVisibleGenerate)
                        oExcel.Visible = true;
                    oExcel.SheetsInNewWorkbook = 1;
                    oExcel.Workbooks.Add();
                    Excel.Workbooks excelWorkBooks = oExcel.Workbooks;
                    Excel.Workbook excelWorkBook = excelWorkBooks[1];
                    excelWorkBook.Saved = false;
                    Excel.Sheets excelSheets = oExcel.Worksheets;
                    Excel.Worksheet excelWorkSheets = excelSheets.get_Item(1);
                    Excel.Range excelCells;
                    //---типо Титульник---
                    //Строка 1
                    excelCells = excelWorkSheets.get_Range("A1", "V1");
                    excelCells.Merge();
                    excelCells.Value2 = "Оперативный отчет о заключении договоров по оборонной продукции (услугам)";
                    excelCells.WrapText = true;
                    excelCells.Font.Bold = true;
                    excelCells.HorizontalAlignment = Excel.Constants.xlCenter;
                    excelCells.VerticalAlignment = Excel.Constants.xlCenter;
                    excelCells.RowHeight = 34;
                    //Строка 2
                    excelCells = excelWorkSheets.get_Range("A3", "V3");
                    excelCells.Merge();
                    excelCells.Value2 = "по состоянию на " + DateTime.Now.ToShortDateString() + " г.";
                    excelCells.HorizontalAlignment = Excel.Constants.xlRight;
                    excelCells.VerticalAlignment = Excel.Constants.xlCenter;
                    //Заполнения даннами шапки
                    int positionInRows = 4;
                    excelCells = excelWorkSheets.get_Range("A" + positionInRows);
                    excelCells.Value2 = "Номер договора";
                    excelCells.ColumnWidth = 12;
                    excelCells = excelWorkSheets.get_Range("A" + positionInRows, "A" + (positionInRows + 3));
                    excelCells.Merge();

                    excelCells = excelWorkSheets.get_Range("B" + positionInRows);
                    excelCells.Value2 = "Наименование работ";
                    excelCells.ColumnWidth = 15;
                    excelCells = excelWorkSheets.get_Range("B" + positionInRows, "B" + (positionInRows + 3));
                    excelCells.Merge();

                    excelCells = excelWorkSheets.get_Range("C" + positionInRows);
                    excelCells.Value2 = "Срок исполнения договора";
                    excelCells.ColumnWidth = 19;
                    excelCells = excelWorkSheets.get_Range("C" + positionInRows, "C" + (positionInRows + 3));
                    excelCells.Merge();

                    excelCells = excelWorkSheets.get_Range("D" + positionInRows);
                    excelCells.Value2 = "ГОЗ, экспорт";
                    excelCells.ColumnWidth = 8;
                    excelCells = excelWorkSheets.get_Range("D" + positionInRows, "D" + (positionInRows + 3));
                    excelCells.Merge();

                    excelCells = excelWorkSheets.get_Range("E" + positionInRows);
                    excelCells.Value2 = "Вид работ";
                    excelCells.ColumnWidth = 12;
                    excelCells = excelWorkSheets.get_Range("E" + positionInRows, "E" + (positionInRows + 3));
                    excelCells.Merge();

                    excelCells = excelWorkSheets.get_Range("F" + positionInRows);
                    excelCells.Value2 = "Всего договоров";
                    excelCells.ColumnWidth = 5;
                    excelCells = excelWorkSheets.get_Range("F" + positionInRows, "F" + (positionInRows + 3));
                    excelCells.Merge();

                    excelCells = excelWorkSheets.get_Range("G" + positionInRows);
                    excelCells.Value2 = "из них";
                    //excelCells.ColumnWidth = 20;
                    excelCells = excelWorkSheets.get_Range("G" + positionInRows, "L" + positionInRows);
                    excelCells.Merge();

                    excelCells = excelWorkSheets.get_Range("G" + (positionInRows + 1));
                    excelCells.Value2 = "Заключено";
                    excelCells = excelWorkSheets.get_Range("G" + (positionInRows + 1), "I" + (positionInRows + 1));
                    excelCells.Merge();

                    excelCells = excelWorkSheets.get_Range("G" + (positionInRows + 2));
                    excelCells.Value2 = "кол-во догов.";
                    excelCells.ColumnWidth = 7;
                    excelCells = excelWorkSheets.get_Range("G" + (positionInRows + 2), "G" + (positionInRows + 3));
                    excelCells.Merge();

                    excelCells = excelWorkSheets.get_Range("H" + (positionInRows + 2));
                    excelCells.Value2 = "сумма с НДС, тыс.руб.";
                    excelCells = excelWorkSheets.get_Range("H" + (positionInRows + 2), "I" + (positionInRows + 2));
                    excelCells.Merge();

                    excelCells = excelWorkSheets.get_Range("H" + (positionInRows + 3));
                    excelCells.Value2 = "всего по дог., тыс.руб.";
                    excelCells.ColumnWidth = 10;

                    excelCells = excelWorkSheets.get_Range("I" + (positionInRows + 3));
                    excelCells.Value2 = "в т.ч. на 2021г.";
                    excelCells.ColumnWidth = 9;

                    excelCells = excelWorkSheets.get_Range("J" + (positionInRows + 1));
                    excelCells.Value2 = "На оформлении";
                    excelCells = excelWorkSheets.get_Range("J" + (positionInRows + 1), "L" + (positionInRows + 1));
                    excelCells.Merge();

                    excelCells = excelWorkSheets.get_Range("J" + (positionInRows + 2));
                    excelCells.Value2 = "кол-во догов.";
                    excelCells = excelWorkSheets.get_Range("J" + (positionInRows + 2), "J" + (positionInRows + 3));
                    excelCells.Merge();
                    excelCells.ColumnWidth = 5;

                    excelCells = excelWorkSheets.get_Range("K" + (positionInRows + 2));
                    excelCells.Value2 = "сумма с НДС, тыс.руб.";
                    excelCells = excelWorkSheets.get_Range("K" + (positionInRows + 2), "L" + (positionInRows + 2));
                    excelCells.Merge();

                    excelCells = excelWorkSheets.get_Range("K" + (positionInRows + 3));
                    excelCells.Value2 = "всего по дог., тыс.руб.";
                    excelCells.ColumnWidth = 10;

                    excelCells = excelWorkSheets.get_Range("L" + (positionInRows + 3));
                    excelCells.Value2 = "в т.ч. на 2021г.";
                    excelCells.ColumnWidth = 10;

                    excelCells = excelWorkSheets.get_Range("M" + positionInRows);
                    excelCells.Value2 = "Состояние заключения договоров (где и когда)";
                    excelCells.ColumnWidth = 17;
                    excelCells = excelWorkSheets.get_Range("M" + positionInRows, "M" + (positionInRows + 3));
                    excelCells.Merge();

                    excelCells = excelWorkSheets.get_Range("N" + positionInRows);
                    excelCells.Value2 = "Выполнение";
                    excelCells = excelWorkSheets.get_Range("N" + positionInRows, "P" + positionInRows);
                    excelCells.Merge();

                    excelCells = excelWorkSheets.get_Range("N" + (positionInRows + 1));
                    excelCells.Value2 = "Сумма с НДС, тыс.руб.";
                    excelCells = excelWorkSheets.get_Range("N" + (positionInRows + 1), "O" + (positionInRows + 1));
                    excelCells.Merge();

                    excelCells = excelWorkSheets.get_Range("N" + (positionInRows + 2));
                    excelCells.Value2 = "всего по договору";
                    excelCells = excelWorkSheets.get_Range("N" + (positionInRows + 2), "N" + (positionInRows + 3));
                    excelCells.Merge();
                    excelCells.ColumnWidth = 10;

                    excelCells = excelWorkSheets.get_Range("O" + (positionInRows + 2));
                    excelCells.Value2 = "в т.ч. на 2021г.";
                    excelCells = excelWorkSheets.get_Range("O" + (positionInRows + 2), "O" + (positionInRows + 3));
                    excelCells.Merge();
                    excelCells.ColumnWidth = 10;

                    excelCells = excelWorkSheets.get_Range("P" + (positionInRows + 1));
                    excelCells.Value2 = "Выполнен в полном объеме / в стадии выполнения";
                    excelCells = excelWorkSheets.get_Range("P" + (positionInRows + 1), "P" + (positionInRows + 3));
                    excelCells.Merge();
                    excelCells.ColumnWidth = 17;

                    excelCells = excelWorkSheets.get_Range("Q" + positionInRows);
                    excelCells.Value2 = "Выставление счетов (служебная ПЭО)";
                    excelCells = excelWorkSheets.get_Range("Q" + positionInRows, "S" + positionInRows);
                    excelCells.Merge();

                    excelCells = excelWorkSheets.get_Range("Q" + (positionInRows + 1));
                    excelCells.Value2 = "Сумма с НДС, тыс. руб.";
                    excelCells = excelWorkSheets.get_Range("Q" + (positionInRows + 1), "S" + (positionInRows + 1));
                    excelCells.Merge();

                    excelCells = excelWorkSheets.get_Range("Q" + (positionInRows + 2));
                    excelCells.Value2 = "аванс";
                    excelCells = excelWorkSheets.get_Range("Q" + (positionInRows + 2), "R" + (positionInRows + 2));
                    excelCells.Merge();

                    excelCells = excelWorkSheets.get_Range("Q" + (positionInRows + 3));
                    excelCells.Value2 = "% аванс по договору";
                    excelCells.ColumnWidth = 6;

                    excelCells = excelWorkSheets.get_Range("R" + (positionInRows + 3));
                    excelCells.Value2 = "сумма";
                    excelCells.ColumnWidth = 9;

                    excelCells = excelWorkSheets.get_Range("S" + (positionInRows + 2));
                    excelCells.Value2 = "счет- фактура";

                    excelCells = excelWorkSheets.get_Range("S" + (positionInRows + 3));
                    excelCells.Value2 = "сумма";
                    excelCells.ColumnWidth = 9;

                    excelCells = excelWorkSheets.get_Range("T" + positionInRows);
                    excelCells.Value2 = "Оплата с НДС, тыс.руб.";
                    excelCells = excelWorkSheets.get_Range("T" + positionInRows, "V" + positionInRows);
                    excelCells.Merge();

                    excelCells = excelWorkSheets.get_Range("T" + (positionInRows + 1));
                    excelCells.Value2 = "Всего";
                    excelCells = excelWorkSheets.get_Range("T" + (positionInRows + 1), "T" + (positionInRows + 3));
                    excelCells.Merge();
                    excelCells.ColumnWidth = 9;

                    excelCells = excelWorkSheets.get_Range("U" + (positionInRows + 1));
                    excelCells.Value2 = "в том числе";
                    excelCells = excelWorkSheets.get_Range("U" + (positionInRows + 1), "V" + (positionInRows + 1));
                    excelCells.Merge();

                    excelCells = excelWorkSheets.get_Range("U" + (positionInRows + 2));
                    excelCells.Value2 = "аванс";

                    excelCells = excelWorkSheets.get_Range("U" + (positionInRows + 3));
                    excelCells.Value2 = "аванс";
                    excelCells.ColumnWidth = 9;

                    excelCells = excelWorkSheets.get_Range("V" + (positionInRows + 2));
                    excelCells.Value2 = "оплата";

                    excelCells = excelWorkSheets.get_Range("V" + (positionInRows + 3));
                    excelCells.Value2 = "окончат. расчет";
                    excelCells.ColumnWidth = 10;

                    //Настройка выравнивания и автопереноса
                    excelCells = excelWorkSheets.get_Range("A" + positionInRows, "V" + (positionInRows + 3));
                    excelCells.WrapText = true;
                    excelCells.HorizontalAlignment = Excel.Constants.xlCenter;
                    excelCells.VerticalAlignment = Excel.Constants.xlCenter;
                    excelCells.Font.Bold = true;

                    positionInRows += 4;

                    //Настройки автоширины
                    //excelWorkSheets.Columns[1].EntireColumn.AutoFit();
                    //Запрос
                    dynamic json = null;

                    // POST
                    using (WebClient wc = new WebClient())
                    {
                        var pars = new System.Collections.Specialized.NameValueCollection();
                        pars.Add("counterpartie", counterpartieID);
                        // Формируем виды работ
                        int numberView = 0;
                        foreach (string str in departmentName)
                        {
                            pars.Add("view[" + numberView++ + "]", str);
                        }

                        byte[] response2 = wc.UploadValues(command, "POST", pars);

                        Encoding en = Encoding.Default;
                        string byteToStr = en.GetString(response2, 0, response2.Length);

                        json = JsonConvert.DeserializeObject(byteToStr);
                    }

                    // Создание
                    if (json != null)
                    {
                        int resultAllContract = 0;
						int resultAllConcludedContract = 0;
						double resultAmountConcludedContract = 0;
						double resultYearAmountConcludedContract = 0;
						int resultAllFormalizationContract = 0;
						double resultAmountFormalizationContract = 0;
						double resultYearAmountFormalizationContract = 0;
						double resultAmountImplementationContract = 0;
						double resultYearAmountImplementationContract = 0;
						double resultAllPrepaymentContract = 0;
						double resultAllPaymentContract = 0;
						double resultAllPrepaymentReestr = 0;
						double resultAllInvoices = 0;

                        foreach (dynamic d in json["contracts"])
                        {
                            excelCells = excelWorkSheets.get_Range("A" + positionInRows);
                            excelCells.Value2 = Convert.ToString(d.Name);
                            excelCells.Font.Bold = true;
                            excelCells = excelWorkSheets.get_Range("A" + positionInRows, "B" + positionInRows);
                            excelCells.Merge();
                            positionInRows++;
                            foreach (dynamic zavod in d.Value)
                            {
                                excelCells = excelWorkSheets.get_Range("A" + positionInRows);
                                excelCells.Value2 = Convert.ToString(zavod.Name);
                                excelCells.Font.Bold = true;
                                excelCells = excelWorkSheets.get_Range("A" + positionInRows, "B" + positionInRows);
                                excelCells.Merge();
                                positionInRows++;

                                // Переменные для результатов
                                int allContract = 0;
                                int allConcludedContract = 0;
                                double amountConcludedContract = 0;
                                double yearAmountConcludedContract = 0;
                                int allFormalizationContract = 0;
                                double amountFormalizationContract = 0;
                                double yearAmountFormalizationContract = 0;
                                double amountImplementationContract = 0;
                                double yearAmountImplementationContract = 0;
                                double allPrepaymentReestr = 0;
                                double allInvoices = 0;
                                double allPrepaymentContract = 0;
                                double allPaymentContract = 0;

                                foreach (dynamic contracts in zavod)
                                {
                                    foreach (dynamic contract in contracts)
                                    {
                                        excelCells = excelWorkSheets.get_Range("A" + positionInRows);
                                        excelCells.Value2 = contract["number_contract"] != null ? Convert.ToString(contract["number_contract"]) : "";

                                        excelCells = excelWorkSheets.get_Range("B" + positionInRows);
                                        excelCells.Value2 = contract["item_contract"] != null ? Convert.ToString(contract["item_contract"]) : "";

                                        excelCells = excelWorkSheets.get_Range("C" + positionInRows);
                                        excelCells.Value2 = contract["date_maturity_reestr"] != null ? Convert.ToString(contract["date_maturity_reestr"]) : "";

                                        excelCells = excelWorkSheets.get_Range("D" + positionInRows);
                                        excelCells.Value2 = contract["name_works_goz"] != null ? Convert.ToString(contract["name_works_goz"]) : "";

                                        excelCells = excelWorkSheets.get_Range("E" + positionInRows);
                                        excelCells.Value2 = contract["name_view_contract"] != null ? Convert.ToString(contract["name_view_contract"]) : "";

                                        excelCells = excelWorkSheets.get_Range("F" + positionInRows);
                                        excelCells.Value2 = "1";

                                        // Заключено или на оформлении
                                        excelCells = excelWorkSheets.get_Range("G" + positionInRows);
                                        if (contract["states"] != null)
                                        {
                                            dynamic states = contract["states"];
                                            if (states.HasValues)
                                            {
                                                if (states.Last["name_state"] == "Заключен" || states.Last["name_state"] == "Заключён")
                                                {
                                                    allConcludedContract++;
                                                    excelCells.Value2 = "1";

                                                    if (contract["amount_reestr"] != null)
                                                    {
                                                        excelCells = excelWorkSheets.get_Range("H" + positionInRows);
                                                        double amountReestr = 0;
                                                        string strAmountReestr = Convert.ToString(contract["amount_reestr"]).Replace('.',',');
                                                        if (double.TryParse(strAmountReestr, out amountReestr))
                                                        {
                                                            amountConcludedContract += amountReestr;
                                                            excelCells.Value2 = (amountReestr / 1000).ToString("N2");
                                                        }
                                                        else
                                                            excelCells.Value2 = strAmountReestr;
                                                    }

                                                    if (contract["amount_year_reestr"] != null)
                                                    {
                                                        excelCells = excelWorkSheets.get_Range("I" + positionInRows);
                                                        double amountYearReestr = 0;
                                                        string strAmountYearReestr = Convert.ToString(contract["amount_year_reestr"]).Replace('.', ',');
                                                        if (double.TryParse(strAmountYearReestr, out amountYearReestr))
                                                        {
                                                            yearAmountConcludedContract += amountYearReestr;
                                                            excelCells.Value2 = (amountYearReestr / 1000).ToString("N2");
                                                        }
                                                        else
                                                            excelCells.Value2 = strAmountYearReestr;
                                                    }
                                                }
                                                else
                                                {
                                                    allFormalizationContract++;
                                                    excelCells = excelWorkSheets.get_Range("J" + positionInRows);
                                                    excelCells.Value2 = "1";

                                                    if (contract["amount_reestr"] != null)
                                                    {
                                                        excelCells = excelWorkSheets.get_Range("K" + positionInRows);
                                                        double amountReestr = 0;
                                                        string strAmountReestr = Convert.ToString(contract["amount_reestr"]).Replace('.', ',');
                                                        if (double.TryParse(strAmountReestr, out amountReestr))
                                                        {
                                                            amountFormalizationContract += amountReestr;
                                                            excelCells.Value2 = (amountReestr / 1000).ToString("N2");
                                                        }
                                                        else
                                                            excelCells.Value2 = strAmountReestr;
                                                    }

                                                    if (contract["amount_year_reestr"] != null)
                                                    {
                                                        excelCells = excelWorkSheets.get_Range("L" + positionInRows);
                                                        double amountYearReestr = 0;
                                                        string strAmountYearReestr = Convert.ToString(contract["amount_year_reestr"]).Replace('.', ',');
                                                        if (double.TryParse(strAmountYearReestr, out amountYearReestr))
                                                        {
                                                            yearAmountFormalizationContract += amountYearReestr;
                                                            excelCells.Value2 = (amountYearReestr / 1000).ToString("N2");
                                                        }
                                                        else
                                                            excelCells.Value2 = strAmountYearReestr;
                                                    }
                                                }

                                                // Состояние заключения
                                                excelCells = excelWorkSheets.get_Range("M" + positionInRows);
                                                excelCells.Value2 = Convert.ToString(states.Last["name_state"]) + "\n" + Convert.ToString(states.Last["comment_state"]);
                                            }
                                            else
                                            {
                                                allFormalizationContract++;
                                                excelCells = excelWorkSheets.get_Range("J" + positionInRows);
                                                excelCells.Value2 = "1";

                                                if (contract["amount_reestr"] != null)
                                                {
                                                    excelCells = excelWorkSheets.get_Range("K" + positionInRows);
                                                    double amountReestr = 0;
                                                    string strAmountReest = Convert.ToString(contract["amount_reestr"]).Replace('.',',');
                                                    if (double.TryParse(strAmountReest, out amountReestr))
                                                    {
                                                        amountFormalizationContract += amountReestr;
                                                        excelCells.Value2 = (amountReestr / 1000).ToString("N2");
                                                    }
                                                    else
                                                        excelCells.Value2 = strAmountReest;
                                                }

                                                if (contract["amount_year_reestr"] != null)
                                                {
                                                    excelCells = excelWorkSheets.get_Range("L" + positionInRows);
                                                    double amountYearReestr = 0;
                                                    string strAmountYearReestr = Convert.ToString(contract["amount_year_reestr"]).Replace('.', ',');
                                                    if (double.TryParse(strAmountYearReestr, out amountYearReestr))
                                                    {
                                                        yearAmountFormalizationContract += amountYearReestr;
                                                        excelCells.Value2 = (amountYearReestr / 1000).ToString("N2");
                                                    }
                                                    else
                                                        excelCells.Value2 = strAmountYearReestr;
                                                }
                                            }
                                        }
                                        else
                                        {
                                            allFormalizationContract++;
                                            excelCells = excelWorkSheets.get_Range("J" + positionInRows);
                                            excelCells.Value2 = "1";

                                            if (contract["amount_reestr"] != null)
                                            {
                                                excelCells = excelWorkSheets.get_Range("K" + positionInRows);
                                                double amountReestr = 0;
                                                string strAmountReestr = Convert.ToString(contract["amount_reestr"]).Replace('.', ',');
                                                if (double.TryParse(strAmountReestr, out amountReestr))
                                                {
                                                    amountFormalizationContract += amountReestr;
                                                    excelCells.Value2 = (amountReestr / 1000).ToString("N2");
                                                }
                                                else
                                                    excelCells.Value2 = strAmountReestr;
                                            }

                                            if (contract["amount_year_reestr"] != null)
                                            {
                                                excelCells = excelWorkSheets.get_Range("L" + positionInRows);
                                                double amountYearReestr = 0;
                                                string strAmountYearReestr = Convert.ToString(contract["amount_year_reestr"]).Replace('.', ',');
                                                if (double.TryParse(strAmountYearReestr, out amountYearReestr))
                                                {
                                                    yearAmountFormalizationContract += amountYearReestr;
                                                    excelCells.Value2 = (amountYearReestr / 1000).ToString("N2");
                                                }
                                                else
                                                    excelCells.Value2 = strAmountYearReestr;
                                            }
                                        }

                                        // Выполнение
                                        if (contract["amount_acts"] != null)
                                        {
                                            excelCells = excelWorkSheets.get_Range("N" + positionInRows);
                                            double amountActs = 0;
                                            string strAmountActs = Convert.ToString(contract["amount_acts"]).Replace('.', ',');
                                            if (double.TryParse(strAmountActs, out amountActs))
                                            {
                                                amountImplementationContract += amountActs;
                                                excelCells.Value2 = (amountActs / 1000).ToString("N2");
                                            }
                                            else
                                                excelCells.Value2 = strAmountActs;
                                        }
                                        if (contract["year_amount_acts"] != null)
                                        {
                                            excelCells = excelWorkSheets.get_Range("O" + positionInRows);
                                            double yearAmountActs = 0;
                                            string strYearAmountActs = Convert.ToString(contract["year_amount_acts"]).Replace('.', ',');
                                            if (double.TryParse(strYearAmountActs, out yearAmountActs))
                                            {
                                                yearAmountImplementationContract += yearAmountActs;
                                                excelCells.Value2 = (yearAmountActs / 1000).ToString("N2");
                                            }
                                            else
                                                excelCells.Value2 = strYearAmountActs;
                                        }

                                        // Стадия выполнения
                                        if (contract["work_states"] != null)
                                        {
                                            dynamic workStates = contract["work_states"];
                                            if (workStates.HasValues)
                                            {
                                                excelCells = excelWorkSheets.get_Range("P" + positionInRows);
                                                excelCells.Value2 = Convert.ToString(workStates.Last["name_state"]) + "\n" + Convert.ToString(workStates.Last["comment_state"]);
                                            }
                                        }

                                        // Выставление счетов
                                        excelCells = excelWorkSheets.get_Range("Q" + positionInRows);
                                        excelCells.Value2 = contract["percent_prepayment_reestr"] != null ? Convert.ToString(contract["percent_prepayment_reestr"]) : "";
                                        if (contract["prepayment_reestr"] != null)
                                        {
                                            excelCells = excelWorkSheets.get_Range("R" + positionInRows);
                                            double prepaymentReestr = 0;
                                            string strPrepaymentReestr = Convert.ToString(contract["prepayment_reestr"]).Replace('.',',');
                                            if (double.TryParse(strPrepaymentReestr, out prepaymentReestr))
                                            {
                                                allPrepaymentReestr += prepaymentReestr;
                                                excelCells.Value2 = (prepaymentReestr / 1000).ToString("N2");
                                            }
                                            else
                                                excelCells.Value2 = strPrepaymentReestr;
                                        }
                                        if (contract["invoices"] != null)
                                        {
                                            excelCells = excelWorkSheets.get_Range("S" + positionInRows);
                                            double invoices = 0;
                                            string strInvoices = Convert.ToString(contract["invoices"]).Replace('.', ',');
                                            if (double.TryParse(strInvoices, out invoices))
                                            {
                                                allInvoices += invoices;
                                                excelCells.Value2 = (invoices / 1000).ToString("N2");
                                            }
                                            else
                                                excelCells.Value2 = strInvoices;
                                        }

                                        // Оплата
                                        if (contract["prepayments"] != null && contract["payments"] != null)
                                        {
                                            excelCells = excelWorkSheets.get_Range("T" + positionInRows);
                                            double prepayments = 0;
                                            double payments = 0;

                                            if (double.TryParse(Convert.ToString(contract["prepayments"]).Replace('.',','), out prepayments))
                                            {
                                                if (double.TryParse(Convert.ToString(contract["payments"]).Replace('.', ','), out prepayments))
                                                {
                                                    excelCells.Value2 = ((prepayments + payments) / 1000).ToString("N2");
                                                }
                                            }
                                        }

                                        if (contract["prepayments"] != null)
                                        {
                                            excelCells = excelWorkSheets.get_Range("U" + positionInRows);
                                            double prepayments = 0;

                                            if (double.TryParse(Convert.ToString(contract["prepayments"]).Replace('.', ','), out prepayments))
                                            {
                                                allPrepaymentContract += prepayments;
                                                excelCells.Value2 = (prepayments / 1000).ToString("N2");
                                            }
                                        }

                                        if (contract["payments"] != null)
                                        {
                                            excelCells = excelWorkSheets.get_Range("V" + positionInRows);
                                            double payments = 0;

                                            if (double.TryParse(Convert.ToString(contract["payments"]).Replace('.', ','), out payments))
                                            {
                                                allPaymentContract += payments;
                                                excelCells.Value2 = (payments / 1000).ToString("N2");
                                            }
                                        }

                                        // Доп Соглашения
                                        if (contract["additional_agreements"] != null)
                                        {
                                            dynamic additionalAgreements = contract["additional_agreements"];
                                            if(additionalAgreements.HasValues)
                                            {
                                                foreach(dynamic aa in additionalAgreements)
                                                {
                                                    positionInRows++;
                                                    excelCells = excelWorkSheets.get_Range("A" + positionInRows);
                                                    excelCells.Value2 = Convert.ToString(aa["name_protocol"]);
                                                    excelCells = excelWorkSheets.get_Range("B" + positionInRows);
                                                    excelCells.Value2 = Convert.ToString(aa["name_work_protocol"]);

                                                    if (aa["states"] != null)
                                                    {
                                                        dynamic states = aa["states"];
                                                        if (states.HasValues)
                                                        {
                                                            excelCells = excelWorkSheets.get_Range("M" + positionInRows);
                                                            excelCells.Value2 = Convert.ToString(states.Last["name_state"]) + "\n" + Convert.ToString(states.Last["comment_state"]);
                                                        }
                                                    }
                                                }
                                            }
                                        }

                                        // Протоколы
                                        if (contract["protocols"] != null)
                                        {
                                            dynamic protocols = contract["protocols"];
                                            if (protocols.HasValues)
                                            {
                                                foreach (dynamic protocol in protocols)
                                                {
                                                    positionInRows++;
                                                    excelCells = excelWorkSheets.get_Range("A" + positionInRows);
                                                    excelCells.Value2 = Convert.ToString(protocol["name_protocol"]);
                                                    excelCells = excelWorkSheets.get_Range("B" + positionInRows);
                                                    excelCells.Value2 = Convert.ToString(protocol["name_work_protocol"]);

                                                    if (protocol["states"] != null)
                                                    {
                                                        dynamic states = protocol["states"];
                                                        if (states.HasValues)
                                                        {
                                                            excelCells = excelWorkSheets.get_Range("M" + positionInRows);
                                                            excelCells.Value2 = Convert.ToString(states.Last["name_state"]) + "\n" + Convert.ToString(states.Last["comment_state"]);
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                        positionInRows++;
                                        allContract++;
                                    }
                                }
                                // Итоги по всем контрактам
                                excelCells = excelWorkSheets.get_Range("A" + positionInRows);
                                excelCells.Value2 = "Итого";
                                excelCells = excelWorkSheets.get_Range("A" + positionInRows, "B" + positionInRows);
                                excelCells.Merge();

                                // Заключено и на оформлении
                                excelCells = excelWorkSheets.get_Range("F" + positionInRows);
                                excelCells.Value2 = Convert.ToString(allContract);
                                excelCells = excelWorkSheets.get_Range("G" + positionInRows);
                                excelCells.Value2 = Convert.ToString(allConcludedContract);
                                excelCells = excelWorkSheets.get_Range("H" + positionInRows);
                                excelCells.Value2 = (amountConcludedContract / 1000).ToString("N2");
                                excelCells = excelWorkSheets.get_Range("I" + positionInRows);
                                excelCells.Value2 = (yearAmountConcludedContract / 1000).ToString("N2");
                                excelCells = excelWorkSheets.get_Range("J" + positionInRows);
                                excelCells.Value2 = Convert.ToString(allFormalizationContract);
                                excelCells = excelWorkSheets.get_Range("K" + positionInRows);
                                excelCells.Value2 = (amountFormalizationContract / 1000).ToString("N2");
                                excelCells = excelWorkSheets.get_Range("L" + positionInRows);
                                excelCells.Value2 = (yearAmountFormalizationContract / 1000).ToString("N2");

                                // Выполнение
                                excelCells = excelWorkSheets.get_Range("N" + positionInRows);
                                excelCells.Value2 = (amountImplementationContract / 1000).ToString("N2");
                                excelCells = excelWorkSheets.get_Range("O" + positionInRows);
                                excelCells.Value2 = (yearAmountImplementationContract / 1000).ToString("N2");

                                // Выставление счетов
                                excelCells = excelWorkSheets.get_Range("R" + positionInRows);
                                excelCells.Value2 = (allPrepaymentReestr / 1000).ToString("N2");
                                excelCells = excelWorkSheets.get_Range("S" + positionInRows);
                                excelCells.Value2 = (allInvoices / 1000).ToString("N2");

                                // Оплата
                                excelCells = excelWorkSheets.get_Range("T" + positionInRows);
                                excelCells.Value2 = ((allPrepaymentContract + allPaymentContract) / 1000).ToString("N2");
                                excelCells = excelWorkSheets.get_Range("U" + positionInRows);
                                excelCells.Value2 = (allPrepaymentContract / 1000).ToString("N2");
                                excelCells = excelWorkSheets.get_Range("V" + positionInRows);
                                excelCells.Value2 = (allPaymentContract / 1000).ToString("N2");

                                excelCells = excelWorkSheets.get_Range("A" + positionInRows, "V" + positionInRows);
                                excelCells.Font.Bold = true;
                                positionInRows++;

                                // Для подведение полноценных итогов по всем заводам
								resultAllContract += allContract; 
								resultAllConcludedContract += allConcludedContract;
								resultAmountConcludedContract += amountConcludedContract;
								resultYearAmountConcludedContract += yearAmountConcludedContract;
								resultAllFormalizationContract += allFormalizationContract;
								resultAmountFormalizationContract += amountFormalizationContract;
								resultYearAmountFormalizationContract += yearAmountFormalizationContract;
								resultAmountImplementationContract += amountImplementationContract;
								resultYearAmountImplementationContract += yearAmountImplementationContract;
								resultAllPrepaymentContract += allPrepaymentContract;
								resultAllPaymentContract += allPaymentContract;
								resultAllPrepaymentReestr += allPrepaymentReestr;
								resultAllInvoices += allInvoices;
                            }
                            positionInRows++;
                        }
                        positionInRows++;

                        // Итоги по ГОЗ, Экспорт, Межзаводские
                        excelCells = excelWorkSheets.get_Range("A" + positionInRows);
                        excelCells.Value2 = "Всего";
                        // Заключено и на оформлении
                        excelCells = excelWorkSheets.get_Range("F" + positionInRows);
                        excelCells.Value2 = Convert.ToString(resultAllContract);
                        excelCells = excelWorkSheets.get_Range("G" + positionInRows);
                        excelCells.Value2 = Convert.ToString(resultAllConcludedContract);
                        excelCells = excelWorkSheets.get_Range("H" + positionInRows);
                        excelCells.Value2 = (resultAmountConcludedContract / 1000).ToString("N2");
                        excelCells = excelWorkSheets.get_Range("I" + positionInRows);
                        excelCells.Value2 = (resultYearAmountConcludedContract / 1000).ToString("N2");
                        excelCells = excelWorkSheets.get_Range("J" + positionInRows);
                        excelCells.Value2 = Convert.ToString(resultAllFormalizationContract);
                        excelCells = excelWorkSheets.get_Range("K" + positionInRows);
                        excelCells.Value2 = (resultAmountFormalizationContract / 1000).ToString("N2");
                        excelCells = excelWorkSheets.get_Range("L" + positionInRows);
                        excelCells.Value2 = (resultYearAmountFormalizationContract / 1000).ToString("N2");

                        // Выполнение
                        excelCells = excelWorkSheets.get_Range("N" + positionInRows);
                        excelCells.Value2 = (resultAmountImplementationContract / 1000).ToString("N2");
                        excelCells = excelWorkSheets.get_Range("O" + positionInRows);
                        excelCells.Value2 = (resultYearAmountImplementationContract / 1000).ToString("N2");

                        // Выставление счетов
                        excelCells = excelWorkSheets.get_Range("R" + positionInRows);
                        excelCells.Value2 = (resultAllPrepaymentReestr / 1000).ToString("N2");
                        excelCells = excelWorkSheets.get_Range("S" + positionInRows);
                        excelCells.Value2 = (resultAllInvoices / 1000).ToString("N2");

                        // Оплата
                        excelCells = excelWorkSheets.get_Range("T" + positionInRows);
                        excelCells.Value2 = ((resultAllPrepaymentContract + resultAllPaymentContract) / 1000).ToString("N2");
                        excelCells = excelWorkSheets.get_Range("U" + positionInRows);
                        excelCells.Value2 = (resultAllPrepaymentContract / 1000).ToString("N2");
                        excelCells = excelWorkSheets.get_Range("V" + positionInRows);
                        excelCells.Value2 = (resultAllPaymentContract / 1000).ToString("N2");

                        excelCells = excelWorkSheets.get_Range("A" + positionInRows, "V" + positionInRows);
                        excelCells.Font.Bold = true;

                        positionInRows++;

                        int countNameWork = 1;
                        foreach(dynamic d in json["itogs"])
                        {
                            excelCells = excelWorkSheets.get_Range("A" + positionInRows);
                            excelCells.Value2 = Convert.ToString(countNameWork);
                            excelCells = excelWorkSheets.get_Range("B" + positionInRows);
                            excelCells.Value2 = Convert.ToString(d.Name);
                            excelCells = excelWorkSheets.get_Range("F" + positionInRows);
                            excelCells.Value2 = Convert.ToString(d.Value["result_all_contract"]);
                            excelCells = excelWorkSheets.get_Range("G" + positionInRows);
                            excelCells.Value2 = Convert.ToString(d.Value["result_all_concluded_contract"]);
                            excelCells = excelWorkSheets.get_Range("H" + positionInRows);
                            excelCells.Value2 = (double.Parse(Convert.ToString(d.Value["result_amount_concluded_contract"]).Replace('.',',')) / 1000).ToString("N2");
                            excelCells = excelWorkSheets.get_Range("I" + positionInRows);
                            excelCells.Value2 = (double.Parse(Convert.ToString(d.Value["result_year_amount_concluded_contract"]).Replace('.',',')) / 1000).ToString("N2");
                            excelCells = excelWorkSheets.get_Range("J" + positionInRows);
                            excelCells.Value2 = Convert.ToString(d.Value["result_all_formalization_contract"]);
                            excelCells = excelWorkSheets.get_Range("K" + positionInRows);
                            excelCells.Value2 = (double.Parse(Convert.ToString(d.Value["result_amount_formalization_contract"]).Replace('.',',')) / 1000).ToString("N2");
                            excelCells = excelWorkSheets.get_Range("L" + positionInRows);
                            excelCells.Value2 = (double.Parse(Convert.ToString(d.Value["result_year_amount_formalization_contract"]).Replace('.',',')) / 1000).ToString("N2");
                            excelCells = excelWorkSheets.get_Range("N" + positionInRows);
                            excelCells.Value2 = (double.Parse(Convert.ToString(d.Value["result_amount_implementation_contract"]).Replace('.',',')) / 1000).ToString("N2");
                            excelCells = excelWorkSheets.get_Range("O" + positionInRows);
                            excelCells.Value2 = (double.Parse(Convert.ToString(d.Value["result_year_amount_implementation_contract"]).Replace('.',',')) / 1000).ToString("N2");
                            excelCells = excelWorkSheets.get_Range("R" + positionInRows);
                            excelCells.Value2 = (double.Parse(Convert.ToString(d.Value["result_all_prepayment_reestr"]).Replace('.',',')) / 1000).ToString("N2");
                            excelCells = excelWorkSheets.get_Range("S" + positionInRows);
                            excelCells.Value2 = (double.Parse(Convert.ToString(d.Value["result_all_invoices"]).Replace('.',',')) / 1000).ToString("N2");
                            excelCells = excelWorkSheets.get_Range("T" + positionInRows);
                            excelCells.Value2 = ((double.Parse(Convert.ToString(d.Value["result_all_prepayment_contract"]).Replace('.',',')) + double.Parse(Convert.ToString(d.Value["result_all_payment_contract"]).Replace('.',','))) / 1000).ToString("N2");
                            excelCells = excelWorkSheets.get_Range("U" + positionInRows);
                            excelCells.Value2 = (double.Parse(Convert.ToString(d.Value["result_all_prepayment_contract"]).Replace('.',',')) / 1000).ToString("N2");
                            excelCells = excelWorkSheets.get_Range("V" + positionInRows);
                            excelCells.Value2 = (double.Parse(Convert.ToString(d.Value["result_all_payment_contract"]).Replace('.', ',')) / 1000).ToString("N2");

                            countNameWork++;
                            positionInRows++;
                        }
                        positionInRows++;
                        excelCells = excelWorkSheets.get_Range("A" + positionInRows);
                        excelCells.Value2 = "В том числе:";
                        positionInRows++;
                        foreach (dynamic d in json["view_itogs"])
                        {
                            excelCells = excelWorkSheets.get_Range("A" + positionInRows);
                            excelCells.Value2 = Convert.ToString(d.Name);
                            excelCells = excelWorkSheets.get_Range("F" + positionInRows);
                            excelCells.Value2 = Convert.ToString(d.Value["result_all_contract"]);
                            excelCells = excelWorkSheets.get_Range("G" + positionInRows);
                            excelCells.Value2 = Convert.ToString(d.Value["result_all_concluded_contract"]);
                            excelCells = excelWorkSheets.get_Range("H" + positionInRows);
                            excelCells.Value2 = (d.Value["result_amount_concluded_contract"] / 1000).ToString("N2");
                            excelCells = excelWorkSheets.get_Range("I" + positionInRows);
                            excelCells.Value2 = (d.Value["result_year_amount_concluded_contract"] / 1000).ToString("N2");
                            excelCells = excelWorkSheets.get_Range("J" + positionInRows);
                            excelCells.Value2 = Convert.ToString(d.Value["result_all_formalization_contract"]);
                            excelCells = excelWorkSheets.get_Range("K" + positionInRows);
                            excelCells.Value2 = (d.Value["result_amount_formalization_contract"] / 1000).ToString("N2");
                            excelCells = excelWorkSheets.get_Range("L" + positionInRows);
                            excelCells.Value2 = (d.Value["result_year_amount_formalization_contract"] / 1000).ToString("N2");
                            excelCells = excelWorkSheets.get_Range("N" + positionInRows);
                            excelCells.Value2 = (d.Value["result_amount_implementation_contract"] / 1000).ToString("N2");
                            excelCells = excelWorkSheets.get_Range("O" + positionInRows);
                            excelCells.Value2 = (d.Value["result_year_amount_implementation_contract"] / 1000).ToString("N2");
                            excelCells = excelWorkSheets.get_Range("R" + positionInRows);
                            excelCells.Value2 = (d.Value["result_all_prepayment_reestr"] / 1000).ToString("N2");
                            excelCells = excelWorkSheets.get_Range("S" + positionInRows);
                            excelCells.Value2 = (d.Value["result_all_invoices"] / 1000).ToString("N2");
                            excelCells = excelWorkSheets.get_Range("T" + positionInRows);
                            excelCells.Value2 = ((d.Value["result_all_prepayment_contract"] + resultAllPaymentContract) / 1000).ToString("N2");
                            excelCells = excelWorkSheets.get_Range("U" + positionInRows);
                            excelCells.Value2 = (d.Value["result_all_prepayment_contract"] / 1000).ToString("N2");
                            excelCells = excelWorkSheets.get_Range("V" + positionInRows);
                            excelCells.Value2 = (d.Value["result_all_payment_contract"] / 1000).ToString("N2");

                            positionInRows++;
                        }
                    }
                    //Отображение сетки для таблицы, а также автоперенос и выравнивая по вертикали по центру
                    excelCells = excelWorkSheets.get_Range("A4", "V" + (positionInRows - 1));
                    excelCells.Borders.LineStyle = Excel.XlLineStyle.xlContinuous;
                    excelCells.Borders.Weight = Excel.XlBorderWeight.xlThin;
                    excelCells.WrapText = true;
                    excelCells.VerticalAlignment = Excel.Constants.xlCenter;
                    //Изменение шрифта
                    excelCells = excelWorkSheets.get_Range("A1", "V" + positionInRows);
                    excelCells.Font.Name = "Times New Roman";
                    excelCells.Font.Size = 10;

                    //Сохранение
                    DateTime dateNowFileName = DateTime.Now;
                    string strSaveName = Convert.ToString(dateNowFileName);
                    strSaveName = strSaveName.Replace(':', '-');
                    //Console.Write(storagePath + userSaveName + " " + strSaveName + ".xlsx");
                    savePath = storagePath + userSaveName + " " + strSaveName + ".xlsx";
                    excelWorkBook.SaveAs(savePath);
                    excelWorkBook.Close();
                    oExcel.Quit();
                }
                return ("Отчёт создан!");
            }
            catch (Exception ex)
            {
                return ("error: " + ex.Message);
            }
        }

        #endregion
    }
}
