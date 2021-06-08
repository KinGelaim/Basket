using Microsoft.Win32;
using System;
using System.Collections.Generic;
using System.IO;
using System.Linq;
using System.Reflection;
using System.Text;
using Word = Microsoft.Office.Interop.Word;
using Excel = Microsoft.Office.Interop.Excel;
using Newtonsoft.Json;
using System.Net;

namespace KontrolOrder_Reports
{
    class Program
    {
        //Сервер
        //static string server = "localhost";
        static string server = "192.168.55.16";
        
        //Путь к хранилищу
        static string storagePath = @"\Отчеты\";

        static void Main(string[] args)
        {
            //if (Properties.Settings.Default.ReportPath == "")
            //{
            //    Properties.Settings.Default.ReportPath = storagePath;
            //    Properties.Settings.Default.Save();
            //}

            //storagePath = Properties.Settings.Default.ReportPath;

            Console.WriteLine("Добро пожаловать в программу отчётов для контроля приказов!");
            Console.WriteLine("Сервер: " + server);


            CheckStorageDir();
            CheckFileJsonExist();
            CheckWindowsReestr();

            bool haveError = false;

            if (args.Length > 0)
            {
                Console.WriteLine("Обнаружены аргументы! Запущен анализ!");
                string[] query = args[0].Split('?');
                if (query.Length > 1)
                {
                    Console.WriteLine(query[1]);

                    string[] parametes = query[1].Split('&');
                    if (parametes.Length > 0)
                    {
                        string[] argsParametes = parametes[0].Split('=');
                        if(argsParametes.Length > 0)
                        {
                            if (argsParametes[0] == "name_table")
                            {
                                Console.WriteLine("Отправка запроса для создания УВЕДОМЛЕНИЯ");
                                string savePath = null;
                                Notifycation(query[1], out savePath, out haveError);
                                if (File.Exists(savePath))
                                    System.Diagnostics.Process.Start(savePath);
                                Console.WriteLine("Уведомления созданы!");
                            }
                            if (argsParametes[0] == "name_report")
                            {
                                Console.WriteLine("Отправка запроса для создания ОТЧЁТА");
                                string savePath = null;
                                if (argsParametes[1] == "no_complete")
                                    Report(1, query[1], out savePath, out haveError);
                                if (argsParametes[1] == "month")
                                    Report(2, query[1], out savePath, out haveError);
                                if (argsParametes[1] == "complete")
                                    Report(3, query[1], out savePath, out haveError);
                                if (File.Exists(savePath))
                                    System.Diagnostics.Process.Start(savePath);
                                Console.WriteLine("Отчёт создан!");
                            }
                        }
                    }
                }
            }

            //Чтоб не закрывалось
            if (haveError)
                Console.ReadKey();
        }


        #region Settings

        //Проверка наличия директории для отчетов
        private static bool CheckStorageDir()
        {
            Console.WriteLine();
            if (Directory.Exists(Directory.GetParent(Assembly.GetExecutingAssembly().Location) + storagePath))
                return true;
            else
            {
                Console.WriteLine("Не найдена директория для отчетов!\nЖелаете ее создать? (y/n)");
                string answer = Console.ReadLine();
                if (answer == "y")
                {
                    Directory.CreateDirectory(Directory.GetParent(Assembly.GetExecutingAssembly().Location) + storagePath);
                    Console.WriteLine("Директория создана!");
                }
            }
            return false;
        }

        //Проверка файла для обработки json объектов
        private static bool CheckFileJsonExist()
        {
            if (File.Exists(Path.GetDirectoryName(Assembly.GetExecutingAssembly().Location) + @"\Newtonsoft.Json.dll"))
                return true;
            else
            {
                Console.WriteLine("Не найдена дополнительная библиотека для создания отчетов!\nЖелаете ее распаковать? (y/n)");
                string answer = Console.ReadLine();
                if (answer == "y")
                {
                    byte[] array = Properties.Resources.Newtonsoft_Json;
                    FileStream fs = new FileStream("Newtonsoft.Json.dll", FileMode.Create);
                    fs.Write(array, 0, array.Length);
                    fs.Close();
                }
            }
            return false;
        }

        //Проверка наличия протокола в реестре
        private static void CheckWindowsReestr()
        {
            RegistryKey key;
            key = Registry.ClassesRoot.OpenSubKey("KontrolOrderReports");
            if (key != null)
            {

            }
            else
            {
                Console.WriteLine("Не найден протокол в реестре!\nЖелаете его создать? (y/n)");
                string answer = Console.ReadLine();
                if (answer == "y")
                {
                    key = Registry.ClassesRoot.CreateSubKey("KontrolOrderReports");
                    key.SetValue("", "URL: KontrolOrderReports Protocol");
                    key.SetValue("URL Protocol", "");

                    key = key.CreateSubKey("shell");
                    key = key.CreateSubKey("open");
                    key = key.CreateSubKey("command");
                    key.SetValue("", Assembly.GetExecutingAssembly().Location);
                    Console.WriteLine("Протокол прописан!");
                }
            }
        }

        #endregion


        #region Уведомления
        static private void Notifycation(string param, out string savePath, out bool haveError)
        {
            savePath = null;
            haveError = false;

            string command = "http://" + server + "/kontrol_order/report_notify?" + param;
            string userSaveName = "Уведомление";
            //Запрос
            dynamic json = null;
            try
            {
                WebRequest request = WebRequest.Create(command);
                WebResponse response = request.GetResponse();
                using (Stream stream = response.GetResponseStream())
                {
                    using (StreamReader reader = new StreamReader(stream))
                    {
                        json = JsonConvert.DeserializeObject(reader.ReadToEnd());
                    }
                }
            }
            catch (Exception ex)
            {
                haveError = true;
                Console.WriteLine("error в запросе: " + ex.Message);
                return;
            }

            //Создание доков
            try
            {
                //Создаем документ
                Word.Application oWord = new Word.Application();
                oWord.Visible = false;
                oWord.Visible = true;
                Object template = Type.Missing;
                Object newTemplate = false;
                Object documentType = Word.WdNewDocumentType.wdNewBlankDocument;
                //Object visible = true;
                oWord.Documents.Add(ref template, ref newTemplate, ref documentType);
                Word.Document wordDocument = (Word.Document)oWord.Documents.get_Item(1);
                //Книжная ориентация
                wordDocument.PageSetup.Orientation = Word.WdOrientation.wdOrientPortrait;
                wordDocument.PageSetup.TopMargin = 27f;
                wordDocument.PageSetup.LeftMargin = 27f;
                wordDocument.PageSetup.RightMargin = 27f;
                wordDocument.PageSetup.BottomMargin = 27f;
                var beginRange = wordDocument.Paragraphs[wordDocument.Paragraphs.Count].Range;
                beginRange.Font.Name = "Times New Roman";
                //Добавление в файл таблиц
                Object autoFitBehavior = Word.WdAutoFitBehavior.wdAutoFitWindow;
                if (json != null)
                {
                    int numberCard = 1;
                    foreach (dynamic d in json)
                    {
                        //Карточка учёта
                        var range = wordDocument.Paragraphs[wordDocument.Paragraphs.Count].Range;
                        range.Text = "Карточка учёта и исполнения директивного документа\t\t" + DateTime.Now.ToShortDateString();
                        range.FormattedText.Bold = 1;
                        range.ParagraphFormat.Alignment = Word.WdParagraphAlignment.wdAlignParagraphCenter;
                        wordDocument.Paragraphs.Add();

                        range = wordDocument.Paragraphs[wordDocument.Paragraphs.Count].Range;
                        range.FormattedText.Bold = 0;
                        wordDocument.Tables.Add(range, 9, 7, autoFitBehavior);
                        Word.Table oTable = wordDocument.Tables[numberCard];

                        Word.Range wordCellRange = oTable.Cell(1, 1).Range;
                        wordCellRange.Text = Convert.ToString(d["number_document"]);
                        wordCellRange = oTable.Cell(1, 2).Range;
                        wordCellRange.Text = "Документ";
                        wordCellRange.FormattedText.Bold = 1;
                        wordCellRange.ParagraphFormat.Alignment = Word.WdParagraphAlignment.wdAlignParagraphRight;
                        wordCellRange = oTable.Cell(1, 3).Range;
                        wordCellRange.Text = Convert.ToString(d["type_document"]);
                        wordCellRange.ParagraphFormat.Alignment = Word.WdParagraphAlignment.wdAlignParagraphLeft;
                        wordCellRange = oTable.Cell(1, 4).Range;
                        wordCellRange.Text = "Дата";
                        wordCellRange.FormattedText.Bold = 1;
                        wordCellRange = oTable.Cell(1, 5).Range;
                        wordCellRange.Text = Convert.ToString(d["date_order"]);
                        wordCellRange = oTable.Cell(1, 6).Range;
                        wordCellRange.Text = "Номер";
                        wordCellRange.FormattedText.Bold = 1;
                        wordCellRange = oTable.Cell(1, 7).Range;
                        wordCellRange.Text = Convert.ToString(d["number_order"]);
                        oTable.Cell(1, 1).SetWidth(50, Word.WdRulerStyle.wdAdjustProportional);
                        oTable.Cell(1, 2).SetWidth(70, Word.WdRulerStyle.wdAdjustProportional);

                        oTable.Cell(2, 1).Merge(oTable.Cell(2, 2));
                        wordCellRange = oTable.Cell(2, 1).Range;
                        wordCellRange.Text = "Организация";
                        wordCellRange.FormattedText.Bold = 1;
                        wordCellRange.ParagraphFormat.Alignment = Word.WdParagraphAlignment.wdAlignParagraphRight;
                        oTable.Cell(2, 2).Merge(oTable.Cell(2, 6));
                        wordCellRange = oTable.Cell(2, 2).Range;
                        wordCellRange.Text = Convert.ToString(d["counterpartie"]);
                        wordCellRange.ParagraphFormat.Alignment = Word.WdParagraphAlignment.wdAlignParagraphLeft;
                        oTable.Cell(2, 1).SetWidth(120, Word.WdRulerStyle.wdAdjustProportional);

                        oTable.Cell(3, 1).Merge(oTable.Cell(3, 2));
                        wordCellRange = oTable.Cell(3, 1).Range;
                        wordCellRange.Text = "Содержание документа";
                        wordCellRange.FormattedText.Bold = 1;
                        wordCellRange.ParagraphFormat.Alignment = Word.WdParagraphAlignment.wdAlignParagraphRight;
                        oTable.Cell(3, 2).Merge(oTable.Cell(3, 6));
                        wordCellRange = oTable.Cell(3, 2).Range;
                        wordCellRange.Text = Convert.ToString(d["short_maintenance"]);
                        wordCellRange.ParagraphFormat.Alignment = Word.WdParagraphAlignment.wdAlignParagraphLeft;
                        oTable.Cell(3, 1).SetWidth(120, Word.WdRulerStyle.wdAdjustProportional);

                        oTable.Cell(4, 1).Merge(oTable.Cell(4, 2));
                        wordCellRange = oTable.Cell(4, 1).Range;
                        wordCellRange.Text = "Содержание поручения";
                        wordCellRange.FormattedText.Bold = 1;
                        wordCellRange.ParagraphFormat.Alignment = Word.WdParagraphAlignment.wdAlignParagraphRight;
                        oTable.Cell(4, 2).Merge(oTable.Cell(4, 6));
                        wordCellRange = oTable.Cell(4, 2).Range;
                        wordCellRange.Text = Convert.ToString(d["maintenance_order"]);
                        wordCellRange.ParagraphFormat.Alignment = Word.WdParagraphAlignment.wdAlignParagraphLeft;
                        oTable.Cell(4, 1).SetWidth(120, Word.WdRulerStyle.wdAdjustProportional);

                        oTable.Cell(5, 1).Merge(oTable.Cell(5, 2));
                        wordCellRange = oTable.Cell(5, 1).Range;
                        wordCellRange.Text = "Отв. за контроль";
                        wordCellRange.FormattedText.Bold = 1;
                        wordCellRange.ParagraphFormat.Alignment = Word.WdParagraphAlignment.wdAlignParagraphRight;
                        oTable.Cell(5, 2).Merge(oTable.Cell(5, 6));
                        wordCellRange = oTable.Cell(5, 2).Range;
                        wordCellRange.Text = Convert.ToString(d["surname"]) + " " + Convert.ToString(d["name"])[0] + "." + Convert.ToString(d["patronymic"])[0] + ".";
                        wordCellRange.ParagraphFormat.Alignment = Word.WdParagraphAlignment.wdAlignParagraphLeft;
                        oTable.Cell(5, 1).SetWidth(120, Word.WdRulerStyle.wdAdjustProportional);

                        oTable.Cell(6, 1).Merge(oTable.Cell(6, 2));
                        wordCellRange = oTable.Cell(6, 1).Range;
                        wordCellRange.Text = "Соисполнители";
                        wordCellRange.FormattedText.Bold = 1;
                        wordCellRange.ParagraphFormat.Alignment = Word.WdParagraphAlignment.wdAlignParagraphRight;
                        oTable.Cell(6, 2).Merge(oTable.Cell(6, 6));
                        wordCellRange = oTable.Cell(6, 2).Range;
                        wordCellRange.Text = Convert.ToString(d["second_executor"]);
                        wordCellRange.ParagraphFormat.Alignment = Word.WdParagraphAlignment.wdAlignParagraphLeft;
                        oTable.Cell(6, 1).SetWidth(120, Word.WdRulerStyle.wdAdjustProportional);

                        oTable.Cell(7, 1).Merge(oTable.Cell(7, 2));
                        wordCellRange = oTable.Cell(7, 1).Range;
                        wordCellRange.Text = "Срок исполнения";
                        wordCellRange.FormattedText.Bold = 1;
                        wordCellRange.ParagraphFormat.Alignment = Word.WdParagraphAlignment.wdAlignParagraphRight;
                        wordCellRange = oTable.Cell(7, 2).Range;
                        wordCellRange.Text = Convert.ToString(d["date_maturity_format"]);
                        wordCellRange = oTable.Cell(7, 3).Range;
                        wordCellRange.Text = "Дата переноса";
                        wordCellRange.FormattedText.Bold = 1;
                        wordCellRange = oTable.Cell(7, 4).Range;
                        wordCellRange.Text = Convert.ToString(d["last_postponement_format"]);
                        wordCellRange = oTable.Cell(7, 5).Range;
                        wordCellRange.Text = "Осталось дней";
                        wordCellRange.FormattedText.Bold = 1;
                        wordCellRange = oTable.Cell(7, 6).Range;
                        wordCellRange.Text = Convert.ToString(d["out_days_format"]);
                        oTable.Cell(7, 1).SetWidth(120, Word.WdRulerStyle.wdAdjustProportional);

                        oTable.Cell(8, 1).Merge(oTable.Cell(8, 2));
                        wordCellRange = oTable.Cell(8, 1).Range;
                        wordCellRange.Text = "Уведомление от";
                        wordCellRange.FormattedText.Bold = 1;
                        wordCellRange.ParagraphFormat.Alignment = Word.WdParagraphAlignment.wdAlignParagraphRight;
                        oTable.Cell(8, 2).Merge(oTable.Cell(8, 6));
                        wordCellRange = oTable.Cell(8, 2).Range;
                        wordCellRange.Text = Convert.ToString(d["notifycation_list"]);
                        wordCellRange.ParagraphFormat.Alignment = Word.WdParagraphAlignment.wdAlignParagraphLeft;
                        oTable.Cell(8, 1).SetWidth(120, Word.WdRulerStyle.wdAdjustProportional);

                        oTable.Cell(9, 1).Merge(oTable.Cell(9, 2));
                        wordCellRange = oTable.Cell(9, 1).Range;
                        wordCellRange.Text = "Периодичность";
                        wordCellRange.FormattedText.Bold = 1;
                        wordCellRange.ParagraphFormat.Alignment = Word.WdParagraphAlignment.wdAlignParagraphRight;
                        oTable.Cell(9, 2).Merge(oTable.Cell(9, 6));
                        wordCellRange = oTable.Cell(9, 2).Range;
                        wordCellRange.Text = Convert.ToString(d["period_kontrol"]);
                        wordCellRange.ParagraphFormat.Alignment = Word.WdParagraphAlignment.wdAlignParagraphLeft;
                        oTable.Cell(9, 1).SetWidth(120, Word.WdRulerStyle.wdAdjustProportional);

                        //Итоги
                        //wordDocument.Paragraphs.Add();
                        range = wordDocument.Paragraphs[wordDocument.Paragraphs.Count].Range;
                        range.Text = "Информация об итогах исполнения (заполняется исполнителем)";
                        range.FormattedText.Bold = 1;

                        wordDocument.Paragraphs.Add();
                        range = wordDocument.Paragraphs[wordDocument.Paragraphs.Count].Range;
                        range.FormattedText.Bold = 0;
                        wordDocument.Tables.Add(range, 2, 2, autoFitBehavior);
                        oTable = wordDocument.Tables[++numberCard];

                        wordCellRange = oTable.Cell(1, 1).Range;
                        wordCellRange.Text = "Мероприятия,\nпроведенные в\nходе исполнения\nпоручения";
                        wordCellRange.FormattedText.Bold = 1;
                        wordCellRange.Cells.VerticalAlignment = Word.WdCellVerticalAlignment.wdCellAlignVerticalCenter;
                        wordCellRange = oTable.Cell(1, 2).Range;
                        wordCellRange.Text = Convert.ToString(d["events"]);
                        wordCellRange.ParagraphFormat.Alignment = Word.WdParagraphAlignment.wdAlignParagraphLeft;
                        wordCellRange = oTable.Cell(2, 1).Range;
                        wordCellRange.Text = "Исполнено, № док.";
                        wordCellRange.FormattedText.Bold = 1;
                        wordCellRange = oTable.Cell(2, 2).Range;
                        wordCellRange.Text = "Дата\t\t\tПодпись";
                        wordCellRange.ParagraphFormat.Alignment = Word.WdParagraphAlignment.wdAlignParagraphLeft;
                        oTable.Cell(1, 1).SetWidth(120, Word.WdRulerStyle.wdAdjustProportional);
                        oTable.Cell(2, 1).SetWidth(120, Word.WdRulerStyle.wdAdjustProportional);
                        oTable.Rows[1].Height = 150;
                        oTable.Rows[2].Height = 30;

                        //Резолюция
                        //wordDocument.Paragraphs.Add();
                        range = wordDocument.Paragraphs[wordDocument.Paragraphs.Count].Range;
                        range.Text = "Резолюция директора";
                        range.FormattedText.Bold = 1;

                        wordDocument.Paragraphs.Add();
                        range = wordDocument.Paragraphs[wordDocument.Paragraphs.Count].Range;
                        wordDocument.Tables.Add(range, 3, 6, autoFitBehavior);
                        oTable = wordDocument.Tables[++numberCard];

                        wordCellRange = oTable.Cell(1, 1).Range;
                        wordCellRange.Text = "Снять с контроля";
                        wordCellRange.FormattedText.Bold = 1;
                        wordCellRange = oTable.Cell(1, 2).Range;
                        wordCellRange.Text = "Продлить";
                        wordCellRange.FormattedText.Bold = 1;
                        wordCellRange = oTable.Cell(1, 3).Range;
                        wordCellRange.Text = "Срок";
                        wordCellRange.FormattedText.Bold = 1;
                        wordCellRange = oTable.Cell(1, 4).Range;
                        wordCellRange.Text = "Установить периодический контроль";
                        wordCellRange.FormattedText.Bold = 1;

                        wordCellRange = oTable.Cell(2, 4).Range;
                        wordCellRange.Text = "Раз в неделю";
                        wordCellRange.FormattedText.Bold = 1;
                        wordCellRange = oTable.Cell(2, 5).Range;
                        wordCellRange.Text = "Раз в месяц";
                        wordCellRange.FormattedText.Bold = 1;
                        wordCellRange = oTable.Cell(2, 6).Range;
                        wordCellRange.Text = "Раз в квартал";
                        wordCellRange.FormattedText.Bold = 1;

                        oTable.Rows[3].Height = 70;

                        oTable.Cell(1, 4).Merge(oTable.Cell(1, 6));
                        oTable.Cell(1, 1).Merge(oTable.Cell(2, 1));
                        oTable.Cell(1, 2).Merge(oTable.Cell(2, 2));
                        oTable.Cell(1, 3).Merge(oTable.Cell(2, 3));

                        numberCard++;
                        //wordDocument.Paragraphs.Add();
                        object what = Word.WdGoToItem.wdGoToLine;
                        object which = Word.WdGoToDirection.wdGoToLast;
                        Word.Range endRange = wordDocument.GoTo(ref what, ref which);
                        endRange.InsertBreak(Word.WdBreakType.wdSectionBreakNextPage);
                    }
                }
                //Сохранение
                DateTime dateNowFileName = DateTime.Now;
                string strSaveName = Convert.ToString(dateNowFileName);
                strSaveName = strSaveName.Replace(':', '-');
                //Console.WriteLine(storagePath + userSaveName + " " + strSaveName + ".docx");
                savePath = Directory.GetParent(Assembly.GetExecutingAssembly().Location) + storagePath + userSaveName + " " + strSaveName + ".docx";
                wordDocument.SaveAs(savePath);
                wordDocument.Close();
                oWord.Quit();

                Console.WriteLine("Завершено!");
            }
            catch (Exception ex)
            {
                haveError = true;
                Console.WriteLine("error: " + ex.Message);
            }
        }
        #endregion


        #region Отчёты
        static private void Report(int type, string param, out string savePath, out bool haveError)
        {
            savePath = null;
            haveError = false;

            string command = "http://" + server + "/kontrol_order/report_print?" + param;
            string userSaveName = "Отчёт";
            //Запрос
            dynamic json = null;
            try
            {
                WebRequest request = WebRequest.Create(command);
                WebResponse response = request.GetResponse();
                using (Stream stream = response.GetResponseStream())
                {
                    using (StreamReader reader = new StreamReader(stream))
                    {
                        //json = JsonConvert.DeserializeObject(reader.ReadToEnd());
                        json = JsonConvert.DeserializeObject<Dictionary<string, dynamic>>(reader.ReadToEnd());
                    }
                }
            }
            catch (Exception ex)
            {
                haveError = true;
                Console.WriteLine("error в запросе: " + ex.Message);
                return;
            }

            //Создание доков
            try
            {
                //Создаем документ
                Word.Application oWord = new Word.Application();
                oWord.Visible = false;
                oWord.Visible = true;
                Object template = Type.Missing;
                Object newTemplate = false;
                Object documentType = Word.WdNewDocumentType.wdNewBlankDocument;
                //Object visible = true;
                oWord.Documents.Add(ref template, ref newTemplate, ref documentType);
                Word.Document wordDocument = (Word.Document)oWord.Documents.get_Item(1);
                //Альбомная ориентация
                wordDocument.PageSetup.Orientation = Word.WdOrientation.wdOrientLandscape;
                wordDocument.PageSetup.TopMargin = 27f;
                wordDocument.PageSetup.LeftMargin = 27f;
                wordDocument.PageSetup.RightMargin = 27f;
                wordDocument.PageSetup.BottomMargin = 27f;
                //Добавления шапки
                var range = wordDocument.Paragraphs[wordDocument.Paragraphs.Count].Range;
                range.Font.Name = "Times New Roman";
                range.FormattedText.Bold = 1;
                if (type == 1)
                    range.Text = "Поручения, НЕ ИСПОЛНЕННЫЕ по состоянию на:\t\t" + DateTime.Now.ToShortDateString();
                else if (type == 2)
                    range.Text = "Поручения К ИСПОЛНЕНИЮ по состоянию на:\t\t" + DateTime.Now.ToShortDateString();
                else if (type == 3)
                    range.Text = "Поручения, ИСПОЛНЕННЫЕ в течение месяца, по состоянию на:\t\t" + DateTime.Now.ToShortDateString();
                //Добавление в файл таблицы
                wordDocument.Paragraphs.Add();
                Object autoFitBehavior = Word.WdAutoFitBehavior.wdAutoFitWindow;
                range = wordDocument.Paragraphs[wordDocument.Paragraphs.Count].Range;
                range.FormattedText.Bold = 0;
                wordDocument.Tables.Add(range, 1, 6, autoFitBehavior);
                Word.Table oTable = wordDocument.Tables[1];
                string[] nameHeaderTable = { "№ п/п,\nДокумент\n№, дата", "Организация", "Содержание документа", "Содержание поручения", "Выполнение поручения", "Срок исп., /\nперенос\nсрока /\nпросрочено"};
                if (type == 3)
                    nameHeaderTable[5] = "Срок исп.,\nперенесено,\nисполнено,\nхар-ка";
                for (int i = 0; i < oTable.Columns.Count; i++)
                {
                    Word.Range wordCellRange = oTable.Cell(1, i + 1).Range;
                    wordCellRange.ParagraphFormat.Alignment = Word.WdParagraphAlignment.wdAlignParagraphCenter;
                    wordCellRange.Cells.VerticalAlignment = Word.WdCellVerticalAlignment.wdCellAlignVerticalCenter;
                    wordCellRange.Text = nameHeaderTable[i];
                }
                if (json != null)
                {
                    int posRows = 2;
                    List<int> positionMarginRows = new List<int>();
                    foreach (KeyValuePair<string, dynamic> d in json)
                    {
                        oTable.Rows.Add();
                        Word.Range wordCellRange = oTable.Cell(posRows, 1).Range;
                        wordCellRange.Text = d.Key;
                        wordCellRange.FormattedText.Bold = 1;
                        wordCellRange = oTable.Cell(posRows, 2).Range;
                        wordCellRange.Text = "( " + Convert.ToString(d.Value.Count) + " )";
                        wordCellRange.FormattedText.Bold = 1;
                        posRows++;
                        foreach (dynamic in_d in d.Value)
                        {
                            oTable.Rows.Add();
                            wordCellRange = oTable.Cell(posRows, 1).Range;
                            wordCellRange.Text = Convert.ToString(in_d["number_document"]) + "\n" + Convert.ToString(in_d["type_document"]) + "\n" + Convert.ToString(in_d["number_order"]) + "\n" + Convert.ToString(in_d["date_order"]);
                            wordCellRange.FormattedText.Bold = 0;
                            wordCellRange.ParagraphFormat.Alignment = Word.WdParagraphAlignment.wdAlignParagraphLeft;
                            wordCellRange = oTable.Cell(posRows, 2).Range;
                            wordCellRange.Text = Convert.ToString(in_d["period_kontrol"]) + "\n" + Convert.ToString(in_d["counterpartie"]);
                            wordCellRange.FormattedText.Bold = 0;
                            wordCellRange = oTable.Cell(posRows, 3).Range;
                            wordCellRange.Text = Convert.ToString(in_d["short_maintenance"]);
                            wordCellRange.ParagraphFormat.Alignment = Word.WdParagraphAlignment.wdAlignParagraphLeft;
                            wordCellRange = oTable.Cell(posRows, 4).Range;
                            wordCellRange.Text = Convert.ToString(in_d["maintenance_order"]);
                            wordCellRange.ParagraphFormat.Alignment = Word.WdParagraphAlignment.wdAlignParagraphLeft;
                            wordCellRange = oTable.Cell(posRows, 5).Range;
                            wordCellRange.Text = Convert.ToString(in_d["events"]);
                            wordCellRange.ParagraphFormat.Alignment = Word.WdParagraphAlignment.wdAlignParagraphLeft;
                            wordCellRange = oTable.Cell(posRows, 6).Range;
                            if (type != 3)
                                wordCellRange.Text = Convert.ToString(in_d["date_maturity_formating"]) + "\n" + Convert.ToString(in_d["last_postponement_formating"]) + "\n" + Convert.ToString(in_d["proskor_formating"]);
                            else
                                wordCellRange.Text = Convert.ToString(in_d["date_maturity_formating"]) + "\n" + Convert.ToString(in_d["last_postponement_formating"]) + "\n" + Convert.ToString(in_d["date_complete_executor_formating"]) + "\n" + Convert.ToString(in_d["character_formating"]);
                            posRows++;
                            oTable.Rows.Add();
                            wordCellRange = oTable.Cell(++posRows, 1).Range;
                            wordCellRange.Text = "Уведомления от:";
                            wordCellRange.FormattedText.Underline = Word.WdUnderline.wdUnderlineSingle;
                            oTable.Rows.Add();
                            positionMarginRows.Add(posRows);
                            wordCellRange = oTable.Cell(++posRows, 1).Range;
                            wordCellRange.FormattedText.Underline = Word.WdUnderline.wdUnderlineNone;
                            string notifycations = "";
                            foreach (dynamic notify in in_d["notifycations"])
                                notifycations += notify["created_at_formating"] + "\t";
                            wordCellRange.Text = notifycations;
                            wordCellRange.ParagraphFormat.Alignment = Word.WdParagraphAlignment.wdAlignParagraphLeft;
                        }
                    }
                    foreach (int positionMerged in positionMarginRows)
                    {
                        oTable.Cell(positionMerged, 1).Merge(oTable.Cell(positionMerged, 6));
                        oTable.Cell(positionMerged, 1).Range.ParagraphFormat.Alignment = Word.WdParagraphAlignment.wdAlignParagraphLeft;
                    }
                }
                //Сохранение
                DateTime dateNowFileName = DateTime.Now;
                string strSaveName = Convert.ToString(dateNowFileName);
                strSaveName = strSaveName.Replace(':', '-');
                //Console.WriteLine(storagePath + userSaveName + " " + strSaveName + ".docx");
                savePath = Directory.GetParent(Assembly.GetExecutingAssembly().Location) + storagePath + userSaveName + " " + strSaveName + ".docx";
                wordDocument.SaveAs(savePath);
                wordDocument.Close();
                oWord.Quit();

                Console.WriteLine("Завершено!");
            }
            catch (Exception ex)
            {
                haveError = true;
                Console.WriteLine("error: " + ex.Message);
            }
        }
        #endregion
    }
}
