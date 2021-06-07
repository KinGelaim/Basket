using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Data.Odbc;
using System.Data.OleDb;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Windows.Forms;
using Word = Microsoft.Office.Interop.Word;
using Excel = Microsoft.Office.Interop.Excel;
using System.IO;
using System.Text.RegularExpressions;
using System.Diagnostics;
using System.Threading;


namespace ARMNewViewReports
{
    public partial class Form1 : Form
    {
        private OleDbConnection _connection = null;
        private OdbcConnection _connection2 = null;

        //Результ
        private string templateFileNameWordPlanSbResult = @"\ШАБЛОНЫ\Результ ШАБЛОН ПЛАН НА СБОРКУ.docx";
        private string templateFileNameWordAllPlanIspResult = @"\ШАБЛОНЫ\Результ ШАБЛОН СВОДНЫЙ ПЛАН ИСПЫТАНИЙ.docx";
        private string templateFileNameWordPlanIspResult = @"\ШАБЛОНЫ\Результ ШАБЛОН ПЛАН ИСПЫТАНИЙ.docx";
        private string templateFileNameWordPotrVKEIspResult = @"\ШАБЛОНЫ\Результ ШАБЛОН РАСЧЕТ ПОТРЕБНОСТИ ДЛЯ ОБЕСПЕЧЕНИЯ ИСПЫТАНИЙ.docx";
        private string templateFileNameWordVKEIspResult = @"\ШАБЛОНЫ\Результ ШАБЛОН СПРАВКА-ОБОСНОВАНИЕ ДЛЯ ОБЕСПЕЧЕНИЯ ИСПЫТАНИЙ.docx";
        private string templateFileNameWordQueryResult = @"\ШАБЛОНЫ\Результ ШАБЛОН ЗАЯВКИ НА ПОСТАВКУ.docx";
        private string templateFileNameWordApplicationResult = @"\ШАБЛОНЫ\Результ ШАБЛОН ПИСЬМА НА ПОСТАВКУ.docx";
        private string templateFileNameWordPotrMCResult = @"\ШАБЛОНЫ\Результ ШАБЛОН СПРАВКА-ОБОСНОВАНИЕ ПОТРЕБНОСТИ В МАТЧАСТИ.docx";
        private string templateFileNameWordMCResult = @"\ШАБЛОНЫ\Результ ШАБЛОН МАТЧАСТЬ РезультИЛИЗАЦИОННОГО ПЛАНА.docx";
        private string templateFileNameWordCallMCResult = @"\ШАБЛОНЫ\Результ ШАБЛОН ЗАЯВКИ НА ПОСТАВКУ МАТЧАСТИ.docx";
        private string templateFileNameWordPotrVBPResult = @"\ШАБЛОНЫ\Результ ШАБЛОН СПРАВКА-ОБОСНОВАНИЕ ПОТРЕБНОСТИ В БРОНЕПЛИТАХ.docx";
        private string templateFileNameWordSvPotrVBPResult = @"\ШАБЛОНЫ\Результ ШАБЛОН СВОДНАЯ ВЕДОМОСТЬ ПОТРЕБНОСТИ В БРОНЕПЛИТАХ.docx";
        private string templateFileNameWordKrImuResult = @"\ШАБЛОНЫ\Результ ШАБЛОН ЗАЯВКИ НА ПОСТАВКУ КРЕШЕРНОГО ИМУЩЕСТВА.docx";
        private string templateFileNameWordRashGodAG = @"\ШАБЛОНЫ\Результ ШАБЛОН АНАЛИЗ ГОТОВНОСТИ.docx";

        //Месячный
        private string templateFileNameWordControlPlan = @"\ШАБЛОНЫ\МЕСЯЧНЫЙ ШАБЛОН ПЛАН КОНТРОЛЬНЫХ ИСПЫТАНИЙ.docx";
        private string templateFileNameWordControlPlanNS = @"\ШАБЛОНЫ\МЕСЯЧНЫЙ ШАБЛОН ПЛАН КОНТРОЛЬНЫХ ИСПЫТАНИЙ (НС).docx";
        private string templateFileNameWordQueryPotr = @"\ШАБЛОНЫ\МЕСЯЧНЫЙ ШАБЛОН ПОТРЕБНОСТЬ.docx";
        private string templateFileNameWordQueryPotrNS = @"\ШАБЛОНЫ\МЕСЯЧНЫЙ ШАБЛОН ПОТРЕБНОСТЬ (НС).docx";
        private string templateFileNameWord = @"\ШАБЛОНЫ\МЕСЯЧНЫЙ ШАБЛОН СПРАВКА-ОБОСНОВАНИЯ.docx";
        private string templateFileNameWordNS = @"\ШАБЛОНЫ\МЕСЯЧНЫЙ ШАБЛОН СПРАВКА-ОБОСНОВАНИЯ (НС).docx";
        private string templateFileNameWordAG = @"\ШАБЛОНЫ\МЕСЯЧНЫЙ ШАБЛОН АНАЛИЗ ГОТОВНОСТИ.docx";
        private string templateFileNameWordQueryOnMonth = @"\ШАБЛОНЫ\МЕСЯЧНЫЙ ШАБЛОН ПЕЧАСТЬ ЗАПРОСОВ НА МЕСЯЦ.docx";
        private string templateFileNameWordQueryOnMonthOneUchet = @"\ШАБЛОНЫ\МЕСЯЧНЫЙ ШАБЛОН ПЕЧАСТЬ ЗАПРОСОВ НА МЕСЯЦ (ОДИН).docx";

        private string templateFileNameExcel = @"\ШАБЛОНЫ\МЕСЯЧНЫЙ ШАБЛОН EXCEL.xlsm";
        private string templateFileNameExcelNS = @"\ШАБЛОНЫ\МЕСЯЧНЫЙ ШАБЛОН EXCEL (НС).xlsm";

        private DataBaseSNS dataBaseSNS;

        //Годовой
        private string templateFileNameWordPlanIspKontrol = @"\ШАБЛОНЫ\ГОДОВОЙ ШАБЛОН ПЛАН КОНТРОЛЬНЫХ ИСПЫТАНИЙ.docx";
        private string templateFileNameWordKontrolAG = @"\ШАБЛОНЫ\ГОДОВОЙ ШАБЛОН АНАЛИЗ ГОТОВНОСТИ.docx";
        private string templateFileNameWordKontrolNomenclature = @"\ШАБЛОНЫ\ГОДОВОЙ ШАБЛОН НОМЕНКЛАТУРЫ.docx";

        //Материалы
        private string templateFileNameWordMaterKE = @"\ШАБЛОНЫ\МАТЕРИАЛЫ ШАБЛОН ПОТРЕБНОСТИ В МТР.docx";
        private string templateFileNameWordMaterSO = @"\ШАБЛОНЫ\МАТЕРИАЛЫ ШАБЛОН СПРАВКА-ОБОСНОВАНИЕ ПОТРЕБНОСТИ МТР.docx";

        //Новый М план
        private string templateFileNameExcelFormaPlanIsp = @"\ШАБЛОНЫ\ФОРМА_12_ЗАДАНИЕ.xls";
        private string templateFileNameExcelFormaKE = @"\ШАБЛОНЫ\ФОРМА_27_СКИ.xls";
        private string templateFileNameWordNormTimeIsp = @"\ШАБЛОНЫ\Результ ШАБЛОН ТРУДОЗАТРАТ.docx";


        //Лист кнопок для блокировок на время печати
        List<Button> buttonList = new List<Button>();
        //Для отмены выхода во время печати
        bool isAgreeExit = true;

        //Цвета для смены во время блокировки (выделяется та кнопка, на которую нажали)
        private Color saveColorBTN;
        private Color choseColorBTN = Color.Aqua;

        public Form1()
        {
            InitializeComponent();

            saveColorBTN = button1.BackColor;

            button17.Visible = false;
            button19.Visible = false;
            buttonList.AddRange(new Button[] { button1, button2, button3, button4, button5, button6, button7, button8, button9, button10, button11, button12, button13, button14, button15, button16, button18, button20, button21, button22, button23, button24, button25, button26, button27, button28, button29, button30, button31, button32, button33 });

            if (Properties.Settings.Default.PathForReports.Length == 0)
            {
                Properties.Settings.Default.PathForReports = Directory.GetCurrentDirectory() + "/reports";
                Properties.Settings.Default.Save();
            }

            dataBaseSNS = new DataBaseSNS(Properties.Settings.Default.SNSPath);
            if (!dataBaseSNS.Load())
                MessageBox.Show("Ошибка при загрузки БД");
            if (!dataBaseSNS.Sort())
                MessageBox.Show("Ошибка при сортировке");
        }

        //Верхнее меню
        private void openReportsToolStripMenuItem_Click(object sender, EventArgs e)
        {
            if (!Directory.Exists(Properties.Settings.Default.PathForReports))
                Directory.CreateDirectory(Properties.Settings.Default.PathForReports);
            System.Diagnostics.Process.Start(Properties.Settings.Default.PathForExplorer, Properties.Settings.Default.PathForReports);
        }

        private void openOtraslToolStripMenuItem_Click(object sender, EventArgs e)
        {
            if (Properties.Settings.Default.OtraslPath.Length > 0)
            {
                if (Directory.Exists(Properties.Settings.Default.OtraslPath))
                    System.Diagnostics.Process.Start(Properties.Settings.Default.PathForExplorer, Properties.Settings.Default.OtraslPath);
                else
                    MessageBox.Show("Проверьте выбранную директорию!", "Ошибка!", MessageBoxButtons.OK, MessageBoxIcon.Error);
            }
            else
                MessageBox.Show("Для начала выберите директорию OTRASL в настройках", "Ошибка!", MessageBoxButtons.OK, MessageBoxIcon.Error);
        }

        private void historyToolStripMenuItem_Click(object sender, EventArgs e)
        {
            FormHistoryDocument fhd = new FormHistoryDocument();
            fhd.ShowDialog();
        }

        private void settingsToolStripMenuItem_Click(object sender, EventArgs e)
        {
            FormSettings settingsForm = new FormSettings();
            settingsForm.ShowDialog();
        }

        private void aboutTheProgrammToolStripMenuItem_Click(object sender, EventArgs e)
        {
            FormAboutBox aboutBox = new FormAboutBox();
            aboutBox.ShowDialog();
        }

        private void exitToolStripMenuItem_Click(object sender, EventArgs e)
        {
            Application.Exit();
        }

        //---------МАТЕРИАЛЫ---------
        //Печать плана испытаний
        #region ПЛАН ИСПЫТАНИЙ

        private void button28_Click(object sender, EventArgs e)
        {
            button28.BackColor = choseColorBTN;
            BlockAllButton();
            if (Properties.Settings.Default.OtraslPath.Length > 0)
            {
                if (Properties.Settings.Default.MaterFolderPath.Length > 0)
                {
                    FormChoseUchYearCount fcuyc = new FormChoseUchYearCount();
                    fcuyc.ShowDialog();
                    if (fcuyc.isExit)
                    {
                        try
                        {
                            label4.BeginInvoke((MethodInvoker)(() => this.label4.Text = "Информация: подготовка к печати"));
                            Thread thread = new Thread(() =>
                            {
                                List<RashGodPlanIsp> planIspList = new List<RashGodPlanIsp>();
                                //Получение плана испытаний
                                planIspList = GetMaterPlanIsp(planIspList);
                                //Заполнение плана испытанйи
                                planIspList = FillMaterPlanIsp(planIspList);
                                //Печать плана испытаний
                                PrintMaterPlanIsp(planIspList, fcuyc.year, fcuyc.uchNumber, fcuyc.count);

                                BlockAllButton(false);
                            });
                            thread.SetApartmentState(ApartmentState.STA);
                            thread.Start();
                        }
                        catch (Exception ex)
                        {
                            BlockAllButton(false);
                            MessageBox.Show("Очередная ошибка\n" + ex.Message + "\n" + ex.Source + "\n" + ex.StackTrace + "\n" + ex.TargetSite, "Ошибка", MessageBoxButtons.OK, MessageBoxIcon.Error);
                        }
                    }
                    else
                        BlockAllButton(false);
                }
                else
                {
                    BlockAllButton(false);
                    MessageBox.Show("Выберите директорию MATER");
                }
            }
            else
            {
                BlockAllButton(false);
                MessageBox.Show("Выберите директорию OTRASL");
            }
        }

        private List<RashGodPlanIsp> GetMaterPlanIsp(List<RashGodPlanIsp> planIspList)
        {
            planIspList.Clear();
            label4.BeginInvoke((MethodInvoker)(() => this.label4.Text = "Информация: получение плана"));
            string strConnection = @"Provider=Microsoft.JET.Oledb.4.0;Data Source=" + Properties.Settings.Default.MaterFolderPath + "/PLAN;Extended Properties=dBASE IV;User ID=;Password=;";
            using (_connection = new OleDbConnection(strConnection))
            {
                _connection.Open();
                using (OleDbCommand cmd = _connection.CreateCommand())
                {
                    cmd.CommandText = "SELECT * FROM PLISP.DBF";
                    using (OleDbDataReader reader = cmd.ExecuteReader())
                    {
                        while (reader.Read())
                        {
                            RashGodPlanIsp isp = new RashGodPlanIsp();
                            isp.kodel = Convert.ToString(reader["KODEL"]);
                            isp.kodotr = Convert.ToString(reader["KODOTR"]);
                            isp.shvid = Convert.ToString(reader["SHVID"]);
                            isp.pol = Convert.ToString(reader["POL"]);
                            isp.kodeiz = Convert.ToString(reader["KODEIZ"]);
                            isp.qolg = Convert.ToString(reader["QOLG"]);
                            isp.qol1 = Convert.ToString(reader["QOL1"]);
                            isp.qol2 = Convert.ToString(reader["QOL2"]);
                            isp.qol3 = Convert.ToString(reader["QOL3"]);
                            isp.qol4 = Convert.ToString(reader["QOL4"]);
                            isp.shsis = Convert.ToString(reader["SHSIS"]);
                            isp.shsis2 = Convert.ToString(reader["SHSIS2"]);
                            isp.shsis3 = Convert.ToString(reader["SHSIS3"]);
                            planIspList.Add(isp);
                        }
                    }
                }
            }
            return planIspList;
        }

        private List<RashGodPlanIsp> FillMaterPlanIsp(List<RashGodPlanIsp> planIspList)
        {
            label4.BeginInvoke((MethodInvoker)(() => this.label4.Text = "Информация: получение информации о элементах"));
            //Получение информации о элементе
            string strConnection = @"Provider=Microsoft.JET.Oledb.4.0;Data Source=" + Properties.Settings.Default.OtraslPath + "/SPNAIM;Extended Properties=dBASE IV;User ID=;Password=;";
            using (_connection = new OleDbConnection(strConnection))
            {
                _connection.Open();
                using (OleDbCommand cmd = _connection.CreateCommand())
                {
                    foreach (RashGodPlanIsp isp in planIspList)
                    {
                        if (isp.kodel != null && isp.kodel != "")
                        {
                            cmd.CommandText = "SELECT * FROM SPNAIM.DBF WHERE KODEL='" + isp.kodel + "'";
                            using (OleDbDataReader reader = cmd.ExecuteReader())
                            {
                                while (reader.Read())
                                {
                                    isp.snhert = Convert.ToString(reader["SNHERT"] ?? "");

                                    isp.snindiz = Convert.ToString(reader["SNINDIZ"] ?? "");

                                    isp.snnaim = Convert.ToString(reader["SNNAIM1"] ?? "") + Convert.ToString(reader["SNNAIM2"] ?? "") +
                                        Convert.ToString(reader["SNNAIM3"] ?? "") + Convert.ToString(reader["SNNAIM4"] ?? "") +
                                        Convert.ToString(reader["SNNAIM5"] ?? "") + Convert.ToString(reader["SNNAIM6"] ?? "") +
                                        Convert.ToString(reader["SNNAIM7"] ?? "") + Convert.ToString(reader["SNNAIM8"] ?? "") +
                                        Convert.ToString(reader["SNNAIM9"] ?? "") + Convert.ToString(reader["SNNAIM10"] ?? "");

                                }
                            }
                        }
                        else
                            MessageBox.Show("В плане элемент без кода!", "Косяк", MessageBoxButtons.OK, MessageBoxIcon.Error);
                    }
                }
            }
            //Получение информации о заводе
            label4.BeginInvoke((MethodInvoker)(() => this.label4.Text = "Информация: получение информации о заводе"));
            strConnection = @"Provider=Microsoft.JET.Oledb.4.0;Data Source=" + Properties.Settings.Default.OtraslPath + "/SPRXZ;Extended Properties=dBASE IV;User ID=;Password=;";
            using (_connection = new OleDbConnection(strConnection))
            {
                _connection.Open();
                using (OleDbCommand cmd = _connection.CreateCommand())
                {
                    foreach (RashGodPlanIsp isp in planIspList)
                    {
                        cmd.CommandText = "SELECT KODOTR, NZAV1, NZAV2, NZAV3, INN FROM SPRXZ.DBF WHERE KODOTR='" + isp.kodotr + "'";
                        using (OleDbDataReader reader = cmd.ExecuteReader())
                        {
                            while (reader.Read())
                            {
                                isp.nzav = Convert.ToString(reader["NZAV1"]) + Convert.ToString(reader["NZAV2"]) + Convert.ToString(reader["NZAV3"]);
                                isp.inn = Convert.ToString(reader["INN"]);
                            }
                        }
                    }
                }
            }
            //Получение информации о виде
            label4.BeginInvoke((MethodInvoker)(() => this.label4.Text = "Информация: получение информации о виде"));
            strConnection = @"Provider=Microsoft.JET.Oledb.4.0;Data Source=" + Properties.Settings.Default.OtraslPath + "/SPRAV;Extended Properties=dBASE IV;User ID=;Password=;";
            using (_connection = new OleDbConnection(strConnection))
            {
                _connection.Open();
                using (OleDbCommand cmd = _connection.CreateCommand())
                {
                    foreach (RashGodPlanIsp isp in planIspList)
                    {
                        cmd.CommandText = "SELECT * FROM SPVID.DBF WHERE SHVID='" + isp.shvid + "'";
                        using (OleDbDataReader reader = cmd.ExecuteReader())
                        {
                            while (reader.Read())
                            {
                                isp.nvid = Convert.ToString(reader["NVID1"]) + " " + Convert.ToString(reader["NVID2"]);
                            }
                        }
                    }
                }
            }
            //Получение информации о полигоне
            label4.BeginInvoke((MethodInvoker)(() => this.label4.Text = "Информация: получение информации о полигоне"));
            try
            {
                strConnection = @"Provider=Microsoft.JET.Oledb.4.0;Data Source=" + Properties.Settings.Default.OtraslPath + "/SPRAV;Extended Properties=dBASE IV;User ID=;Password=;";
                using (_connection = new OleDbConnection(strConnection))
                {
                    _connection.Open();
                    using (OleDbCommand cmd = _connection.CreateCommand())
                    {
                        foreach (RashGodPlanIsp isp in planIspList)
                        {
                            cmd.CommandText = "SELECT * FROM NPOL1.DBF WHERE POL='" + isp.pol + "'";
                            using (OleDbDataReader reader = cmd.ExecuteReader())
                            {
                                while (reader.Read())
                                {
                                    isp.npol = Convert.ToString(reader["NPOL"]);
                                }
                            }
                        }
                    }
                }
            }
            catch (Exception ex)
            {
                MessageBox.Show("Что-то с полигонами!");
            }
            //Получение информации о МЧ
            label4.BeginInvoke((MethodInvoker)(() => this.label4.Text = "Информация: получение информации о МЧ"));
            try
            {
                strConnection = @"Provider=Microsoft.JET.Oledb.4.0;Data Source=" + Properties.Settings.Default.OtraslPath + "/SPRAV;Extended Properties=dBASE IV;User ID=;Password=;";
                using (_connection = new OleDbConnection(strConnection))
                {
                    _connection.Open();
                    using (OleDbCommand cmd = _connection.CreateCommand())
                    {
                        foreach (RashGodPlanIsp isp in planIspList)
                        {
                            if (isp.shsis != null)
                                if (isp.shsis != "")
                                {
                                    cmd.CommandText = "SELECT * FROM SPSIS.DBF WHERE SHSIS='" + isp.shsis + "'";
                                    using (OleDbDataReader reader = cmd.ExecuteReader())
                                    {
                                        while (reader.Read())
                                        {
                                            isp.nsis = Convert.ToString(reader["NSIS"]);
                                        }
                                    }
                                }
                            if (isp.shsis2 != null)
                                if (isp.shsis2 != "")
                                {
                                    cmd.CommandText = "SELECT * FROM SPSIS.DBF WHERE SHSIS='" + isp.shsis2 + "'";
                                    using (OleDbDataReader reader = cmd.ExecuteReader())
                                    {
                                        while (reader.Read())
                                        {
                                            isp.nsis2 = Convert.ToString(reader["NSIS"]);
                                        }
                                    }
                                }
                            if (isp.shsis3 != null)
                                if (isp.shsis3 != "")
                                {
                                    cmd.CommandText = "SELECT * FROM SPSIS.DBF WHERE SHSIS='" + isp.shsis3 + "'";
                                    using (OleDbDataReader reader = cmd.ExecuteReader())
                                    {
                                        while (reader.Read())
                                        {
                                            isp.nsis3 = Convert.ToString(reader["NSIS"]);
                                        }
                                    }
                                }
                        }
                    }
                }
            }
            catch (Exception ex)
            {
                MessageBox.Show("Что-то с МЧ!!!");
            }

            //Получение информации о ЦЕНАХ
            label4.BeginInvoke((MethodInvoker)(() => this.label4.Text = "Информация: получение информации о ценах"));
            try
            {
                strConnection = @"Provider=Microsoft.JET.Oledb.4.0;Data Source=" + Properties.Settings.Default.OtraslPath + "/RAHGOD/CEN;Extended Properties=dBASE IV;User ID=;Password=;";
                using (_connection = new OleDbConnection(strConnection))
                {
                    _connection.Open();
                    using (OleDbCommand cmd = _connection.CreateCommand())
                    {
                        foreach (RashGodPlanIsp isp in planIspList)
                        {
                            cmd.CommandText = "SELECT * FROM CENVI.DBF WHERE POL='" + isp.pol + "' AND KODEL='" + isp.kodel + "' AND SHVID='" + isp.shvid + "'";
                            using (OleDbDataReader reader = cmd.ExecuteReader())
                            {
                                while (reader.Read())
                                {
                                    isp.cena = Convert.ToString(reader["CENA"]);
                                }
                            }
                        }
                    }
                }
            }
            catch (Exception ex)
            {
                MessageBox.Show("Что-то с ЦЕНАМИ!!!");
            }
            return planIspList;
        }

        private void PrintMaterPlanIsp(List<RashGodPlanIsp> planIspList, string yearPrintResult, string uchNumber, string countEkz)
        {
            label4.BeginInvoke((MethodInvoker)(() => this.label4.Text = "Информация: подготовка к печати"));
            //Сортируем план испытаний
            var sortedList = planIspList.OrderBy(a => a.kodotr).ThenBy(b => b.kodel).ThenBy(c => c.shvid);
            try
            {
                //Создаем документ
                Word.Application oWord = new Word.Application();
                oWord.Visible = checkBox5.Checked;

                string path = Directory.GetCurrentDirectory() + templateFileNameWordAllPlanIspResult;
                Word.Document wordDocument = oWord.Documents.Open(path);

                //Поиск и замена текста
                functionReplaceInText(wordDocument, oWord, "{uchNumber}", uchNumber);
                functionReplaceInText(wordDocument, oWord, "{year}", yearPrintResult);

                //Добавление в файл таблицы
                label4.BeginInvoke((MethodInvoker)(() => this.label4.Text = "Информация: создание шапки таблицы"));
                wordDocument.Paragraphs.Add();
                Object autoFitBehavior = Word.WdAutoFitBehavior.wdAutoFitWindow;
                var range = wordDocument.Paragraphs[wordDocument.Paragraphs.Count - 1].Range;
                wordDocument.Tables.Add(range, 1, 7, autoFitBehavior);
                Word.Table oTable = wordDocument.Tables[1];
                string[] nameHeaderTable = { "№\nп/п", "Шифр,\nнаименование\nэлемента", "Шифр,\nнаименование\nзавода", "Шифр,\nнаименование\nвида испытания", "Шифр,\nнаименование\nсистемы", "Шифр,\nнаименование\nполигона", "Количество изделий(выстрелов)", "Год", "1 кв", "2 кв", "3 кв", "4 кв" };
                for (int i = 0; i < oTable.Columns.Count; i++)
                {
                    Word.Range wordCellRange = oTable.Cell(1, i + 1).Range;
                    wordCellRange.ParagraphFormat.Alignment = Word.WdParagraphAlignment.wdAlignParagraphCenter;
                    wordCellRange.Text = nameHeaderTable[i];
                    wordCellRange.Font.Name = "Times New Roman";
                }
                oTable.Rows.First.HeadingFormat = -1;
                oTable.Cell(1, 1).Width = 35;
                oTable.Cell(1, 2).Width = 120;
                oTable.Cell(1, 3).Width = 100;
                oTable.Cell(1, 4).Width = 100;
                oTable.Cell(1, 5).Width = 100;
                oTable.Cell(1, 6).Width = 50;
                oTable.Cell(1, 7).Width = 270;
                oTable.Cell(1, 7).Split(2, 1);
                oTable.Cell(2, 7).Split(1, 5);
                for (int i = 7; i < 12; i++)
                {
                    Word.Range wordCellRange = oTable.Cell(2, i).Range;
                    wordCellRange.ParagraphFormat.Alignment = Word.WdParagraphAlignment.wdAlignParagraphCenter;
                    wordCellRange.Text = nameHeaderTable[i];
                    wordCellRange.Rows.HeadingFormat = -1;
                }
                //Вывод
                int posRows = 3;
                foreach (RashGodPlanIsp isp in sortedList)
                {
                    label4.BeginInvoke((MethodInvoker)(() => this.label4.Text = "Информация: печать элемента " + (posRows - 2) + " из " + sortedList.Count()));
                    oTable.Rows.Add();
                    //Порядковый номер
                    Word.Range wordCellRange = oTable.Cell(posRows, 1).Range;
                    wordCellRange.Rows.HeadingFormat = 0;
                    wordCellRange.Rows.AllowBreakAcrossPages = 0;
                    wordCellRange.Text = "0\n0";
                    //Шифр, наименование элемента
                    wordCellRange = oTable.Cell(posRows, 2).Range;
                    wordCellRange.Rows.HeadingFormat = 0;
                    wordCellRange.Text = Convert.ToString(isp.kodel) + "\n" + isp.snhert + "\n" + isp.snindiz + "\n" + isp.snnaim;
                    wordCellRange.ParagraphFormat.Alignment = Word.WdParagraphAlignment.wdAlignParagraphLeft;
                    //Шифр, наименование завода
                    wordCellRange = oTable.Cell(posRows, 3).Range;
                    wordCellRange.Rows.HeadingFormat = 0;
                    wordCellRange.Text = Convert.ToString(isp.kodotr) + "\n" + isp.nzav;
                    //Шифр, наименование вида испытания
                    wordCellRange = oTable.Cell(posRows, 4).Range;
                    wordCellRange.Rows.HeadingFormat = 0;
                    wordCellRange.Text = Convert.ToString(isp.shvid) + "\n" + isp.nvid;
                    //Шифр, наименование системы
                    wordCellRange = oTable.Cell(posRows, 5).Range;
                    wordCellRange.Rows.HeadingFormat = 0;
                    if (isp.shsis != null && isp.shsis != "")
                        wordCellRange.Text = Convert.ToString(isp.shsis) + "\n" + isp.nsis;
                    else if (isp.shsis2 != null && isp.shsis2 != "")
                        wordCellRange.Text = Convert.ToString(isp.shsis2) + "\n" + isp.nsis2;
                    else if (isp.shsis3 != null && isp.shsis3 != "")
                        wordCellRange.Text = Convert.ToString(isp.shsis3) + "\n" + isp.nsis3;
                    //Полигон
                    wordCellRange = oTable.Cell(posRows, 6).Range;
                    wordCellRange.Rows.HeadingFormat = 0;
                    wordCellRange.Text = Convert.ToString(isp.pol) + "\n" + Convert.ToString(isp.npol);
                    //Количество испытаний
                    double cena = 0;
                    if (isp.cena != null && isp.cena != "")
                        cena = Convert.ToDouble(isp.cena);
                    double countIsp = 0;
                    if (isp.qolg != null && isp.qolg != "")
                        countIsp = Convert.ToDouble(isp.qolg);
                    wordCellRange = oTable.Cell(posRows, 7).Range;
                    wordCellRange.Rows.HeadingFormat = 0;
                    if (cena != 0 && countIsp != 0)
                        wordCellRange.Text = Convert.ToString(countIsp) + "\n" + Convert.ToString(countIsp * cena);
                    else
                        wordCellRange.Text = Convert.ToString(isp.qolg);

                    countIsp = 0;
                    if (isp.qol1 != null && isp.qol1 != "")
                        countIsp = Convert.ToDouble(isp.qol1);
                    wordCellRange = oTable.Cell(posRows, 8).Range;
                    wordCellRange.Rows.HeadingFormat = 0;
                    if (cena != 0 && countIsp != 0)
                        wordCellRange.Text = Convert.ToString(countIsp) + "\n" + Convert.ToString(countIsp * cena);
                    else
                        wordCellRange.Text = Convert.ToString(isp.qol1);

                    countIsp = 0;
                    if (isp.qol2 != null && isp.qol2 != "")
                        countIsp = Convert.ToDouble(isp.qol2);
                    wordCellRange = oTable.Cell(posRows, 9).Range;
                    wordCellRange.Rows.HeadingFormat = 0;
                    if (cena != 0 && countIsp != 0)
                        wordCellRange.Text = Convert.ToString(countIsp) + "\n" + Convert.ToString(countIsp * cena);
                    else
                        wordCellRange.Text = Convert.ToString(isp.qol2);

                    countIsp = 0;
                    if (isp.qol3 != null && isp.qol3 != "")
                        countIsp = Convert.ToDouble(isp.qol3);
                    wordCellRange = oTable.Cell(posRows, 10).Range;
                    wordCellRange.Rows.HeadingFormat = 0;
                    if (cena != 0 && countIsp != 0)
                        wordCellRange.Text = Convert.ToString(countIsp) + "\n" + Convert.ToString(countIsp * cena);
                    else
                        wordCellRange.Text = Convert.ToString(isp.qol3);

                    countIsp = 0;
                    if (isp.qol4 != null && isp.qol4 != "")
                        countIsp = Convert.ToDouble(isp.qol4);
                    wordCellRange = oTable.Cell(posRows, 11).Range;
                    wordCellRange.Rows.HeadingFormat = 0;
                    if (cena != 0 && countIsp != 0)
                        wordCellRange.Text = Convert.ToString(countIsp) + "\n" + Convert.ToString(countIsp * cena);
                    else
                        wordCellRange.Text = Convert.ToString(isp.qol4);
                    posRows++;
                }
                //Создание экземпляров
                label4.BeginInvoke((MethodInvoker)(() => this.label4.Text = "Информация: создание экземпляров"));
                wordDocument.Range().Copy();
                var rangeAllDocumentEkz = wordDocument.Content;
                rangeAllDocumentEkz.Find.ClearFormatting();
                //for (int j = 0; j < planIspList.Count; j++)
                {
                    functionReplaceInText(wordDocument, oWord, "{numberEkz}", "1");
                }
                for (int i = 0; i < Convert.ToInt32(countEkz) - 1; i++)
                {
                    //В конец документа
                    object what = Word.WdGoToItem.wdGoToLine;
                    object which = Word.WdGoToDirection.wdGoToLast;
                    Word.Range endRange = wordDocument.GoTo(ref what, ref which);
                    //Создаем разрыв РАЗДЕЛА (не страниц)
                    endRange.InsertBreak(Word.WdBreakType.wdSectionBreakNextPage);
                    //Вставляем
                    endRange.Paste();
                    rangeAllDocumentEkz = wordDocument.Content;
                    rangeAllDocumentEkz.Find.ClearFormatting();
                    rangeAllDocumentEkz.Find.Execute(FindText: "{numberEkz}", ReplaceWith: i + 2);
                }
                //Очистка буфера
                Clipboard.Clear();
                //Сохранение
                label4.BeginInvoke((MethodInvoker)(() => this.label4.Text = "Информация: сохранение"));
                DateTime dateNowFileName = DateTime.Now;
                string strSaveName = Convert.ToString(dateNowFileName);
                strSaveName = strSaveName.Replace(':', '-');
                if (!Directory.Exists(Properties.Settings.Default.PathForReports))
                    Directory.CreateDirectory(Directory.GetCurrentDirectory() + "/reports");
                wordDocument.SaveAs(Properties.Settings.Default.PathForReports + "/План испытаний материалов" + " " + strSaveName + ".docx");
                wordDocument.Close();
                oWord.Quit();
                label3.BeginInvoke((MethodInvoker)(() => this.label3.Text = "Информация: сводный план сохранен!"));
                MessageBox.Show("Сводный план создан!");
            }
            catch (Exception ex)
            {
                MessageBox.Show("error: " + ex.Message);
            }
        }

        #endregion

        // Потребность в МТР
        #region ПОТРЕБНОСТЬ В МТР

        private void button29_Click(object sender, EventArgs e)
        {
            button29.BackColor = choseColorBTN;
            BlockAllButton();
            if (Properties.Settings.Default.OtraslPath.Length > 0)
            {
                if (Properties.Settings.Default.MaterFolderPath.Length > 0)
                {
                    FormChoseUchAndCount fcuac = new FormChoseUchAndCount();
                    fcuac.ShowDialog();
                    if (fcuac.isExit)
                    {
                        try
                        {
                            label4.BeginInvoke((MethodInvoker)(() => this.label4.Text = "Информация: подготовка к печати"));
                            Thread thread = new Thread(() =>
                            {
                                List<MaterKE> materKEList = new List<MaterKE>();
                                //Получение плана испытаний
                                materKEList = GetMaterKE(materKEList);
                                //Заполнение плана испытанйи
                                materKEList = FillMaterKE(materKEList);
                                //Печать потребности в МТР
                                PrintMaterKE(materKEList, fcuac.uchNumber, fcuac.countEkz);

                                BlockAllButton(false);
                            });
                            thread.SetApartmentState(ApartmentState.STA);
                            thread.Start();
                        }
                        catch (Exception ex)
                        {
                            BlockAllButton(false);
                            MessageBox.Show("Очередная ошибка\n" + ex.Message + "\n" + ex.Source + "\n" + ex.StackTrace + "\n" + ex.TargetSite, "Ошибка", MessageBoxButtons.OK, MessageBoxIcon.Error);
                        }
                    }
                    else
                        BlockAllButton(false);
                }
                else
                {
                    BlockAllButton(false);
                    MessageBox.Show("Выберите дирректорию MATER");
                }
            }
            else
            {
                BlockAllButton(false);
                MessageBox.Show("Выберите директорию OTRASL");
            }
        }

        private List<MaterKE> GetMaterKE(List<MaterKE> materKEList)
        {
            materKEList.Clear();
            label4.BeginInvoke((MethodInvoker)(() => this.label4.Text = "Информация: получение потребности"));
            string strConnection = @"Provider=Microsoft.JET.Oledb.4.0;Data Source=" + Properties.Settings.Default.MaterFolderPath + "/POTR;Extended Properties=dBASE IV;User ID=;Password=;";
            using (_connection = new OleDbConnection(strConnection))
            {
                _connection.Open();
                using (OleDbCommand cmd = _connection.CreateCommand())
                {
                    cmd.CommandText = "SELECT * FROM POTM.DBF";
                    using (OleDbDataReader reader = cmd.ExecuteReader())
                    {
                        while (reader.Read())
                        {
                            MaterKE ke = new MaterKE();
                            ke.kodm = Convert.ToString(reader["KODM"]);
                            ke.kodeiz = Convert.ToString(reader["KODEIZ"]);
                            ke.qpmg = Convert.ToString(reader["QPMG"]);
                            ke.qpm1 = Convert.ToString(reader["QPM1"]);
                            ke.qpm2 = Convert.ToString(reader["QPM2"]);
                            ke.qpm3 = Convert.ToString(reader["QPM3"]);
                            ke.qpm4 = Convert.ToString(reader["QPM4"]);
                            ke.kodotr = Convert.ToString(reader["KODOTR"]);
                            materKEList.Add(ke);
                        }
                    }
                }
            }
            return materKEList;
        }

        private List<MaterKE> FillMaterKE(List<MaterKE> materKEList)
        {
            label4.BeginInvoke((MethodInvoker)(() => this.label4.Text = "Информация: заполнение потребности"));
            //Получение информации о элементе
            label4.BeginInvoke((MethodInvoker)(() => this.label4.Text = "Информация: получение информации о МТР"));
            string strConnection = @"Provider=Microsoft.JET.Oledb.4.0;Data Source=" + Properties.Settings.Default.OtraslPath + "/SPRAV;Extended Properties=dBASE IV;User ID=;Password=;";
            try
            {
                using (_connection = new OleDbConnection(strConnection))
                {
                    _connection.Open();
                    using (OleDbCommand cmd = _connection.CreateCommand())
                    {
                        foreach (MaterKE ke in materKEList)
                        {
                            if (ke.kodm != null && ke.kodm != "")
                            {
                                cmd.CommandText = "SELECT * FROM SPMAT.DBF WHERE KODM='" + ke.kodm + "'";
                                using (OleDbDataReader reader = cmd.ExecuteReader())
                                {
                                    while (reader.Read())
                                    {
                                        ke.artic = Convert.ToString(reader["ARTIC"] ?? "");

                                        ke.razdel = Convert.ToString(reader["RAZDEL"] ?? "");

                                        ke.kodeiz = Convert.ToString(reader["KODEIZ"] ?? "");

                                        ke.namem = Convert.ToString(reader["NAIM1"] ?? "") + Convert.ToString(reader["NAIM2"] ?? "");
                                    }
                                }
                            }
                            else
                                MessageBox.Show("В потребности элемент без кода!", "Косяк", MessageBoxButtons.OK, MessageBoxIcon.Error);
                        }
                    }
                }
            }
            catch(Exception ex)
            {
                MessageBox.Show(ex.Message);
            }
            //Получение информации о ед. измерения
            label4.BeginInvoke((MethodInvoker)(() => this.label4.Text = "Информация: получение информации о ед. измерениях"));
            strConnection = @"Provider=Microsoft.JET.Oledb.4.0;Data Source=" + Properties.Settings.Default.OtraslPath + "/SPRAV;Extended Properties=dBASE IV;User ID=;Password=;";
            using (_connection = new OleDbConnection(strConnection))
            {
                _connection.Open();
                using (OleDbCommand cmd = _connection.CreateCommand())
                {
                    foreach (MaterKE ke in materKEList)
                    {
                        cmd.CommandText = "SELECT * FROM SPEIZ2 WHERE KODEIZ='" + ke.kodeiz + "'";
                        using (OleDbDataReader reader = cmd.ExecuteReader())
                        {
                            while (reader.Read())
                            {
                                ke.naimeiz = Convert.ToString(reader["NAIMEIZ"]);
                                ke.naik = Convert.ToString(reader["NAIK"]);
                            }
                        }
                    }
                }
            }
            //Получение информации о заводе
            label4.BeginInvoke((MethodInvoker)(() => this.label4.Text = "Информация: получение информации о заводе"));
            strConnection = @"Provider=Microsoft.JET.Oledb.4.0;Data Source=" + Properties.Settings.Default.OtraslPath + "/SPRXZ;Extended Properties=dBASE IV;User ID=;Password=;";
            using (_connection = new OleDbConnection(strConnection))
            {
                _connection.Open();
                using (OleDbCommand cmd = _connection.CreateCommand())
                {
                    foreach (MaterKE ke in materKEList)
                    {
                        cmd.CommandText = "SELECT KODOTR, NZAV1, NZAV2, NZAV3, INN FROM SPRXZ.DBF WHERE KODOTR='" + ke.kodotr + "'";
                        using (OleDbDataReader reader = cmd.ExecuteReader())
                        {
                            while (reader.Read())
                            {
                                ke.nzav = Convert.ToString(reader["NZAV1"]) + Convert.ToString(reader["NZAV2"]) + Convert.ToString(reader["NZAV3"]);
                                ke.inn = Convert.ToString(reader["INN"]);
                            }
                        }
                    }
                }
            }
            return materKEList;
        }

        private void PrintMaterKE(List<MaterKE> keList, string uchNumber, string countEkz)
        {
            label4.BeginInvoke((MethodInvoker)(() => this.label4.Text = "Информация: печатаем потребность"));
            //Сортируем план испытаний
            var sortedList = keList.OrderBy(a => a.kodm).ThenBy(b => b.kodotr);
            try
            {
                //Создаем документ
                Word.Application oWord = new Word.Application();
                oWord.Visible = checkBox5.Checked;
                //Открытие документа
                Word.Document wordDocument;
                string path = Directory.GetCurrentDirectory() + templateFileNameWordMaterKE;
                wordDocument = oWord.Documents.Open(path);
                //Поиск и замена текста
                functionReplaceInText(wordDocument, oWord, "{uchNumber}", uchNumber);
                functionReplaceInText(wordDocument, oWord, "{year}", DateTime.Now.Year.ToString());
                //Добавление в файл таблицы
                Object autoFitBehavior = Word.WdAutoFitBehavior.wdAutoFitWindow;
                //Добавление в файл таблицы
                label4.BeginInvoke((MethodInvoker)(() => this.label4.Text = "Информация: создание таблицы"));
                var range = wordDocument.Paragraphs[wordDocument.Paragraphs.Count - 1].Range;
                wordDocument.Tables.Add(range, 2, 10, autoFitBehavior);
                Word.Table oTable = wordDocument.Tables[1];
                string[] nameHeaderTable = { "№\nп/п", "Код и наименование\nкомплектующего\nэлемента", "Ед. измерения", "Поставщик", "Потребность в КЭ", "Примечание", "год", "1 кв", "2 кв", "3 кв", "4 кв" };
                for (int i = 0; i < 5; i++)
                {
                    Word.Range wordCellRange = oTable.Cell(1, i + 1).Range;
                    wordCellRange.ParagraphFormat.Alignment = Word.WdParagraphAlignment.wdAlignParagraphCenter;
                    wordCellRange.Text = nameHeaderTable[i];
                    wordCellRange.Font.Name = "Times New Roman";
                }
                Word.Range wordCellRangeUn = oTable.Cell(1, 10).Range;
                wordCellRangeUn.Text = nameHeaderTable[5];
                oTable.Rows.First.HeadingFormat = -1;

                oTable.Cell(1, 1).Width = 35;
                oTable.Cell(2, 1).Width = 35;
                oTable.Cell(1, 2).Width = 120;
                oTable.Cell(2, 2).Width = 120;
                oTable.Cell(1, 3).Width = 60;
                oTable.Cell(2, 3).Width = 60;
                oTable.Cell(1, 4).Width = 80;
                oTable.Cell(2, 4).Width = 80;
                oTable.Cell(1, 5).Width = 80;
                oTable.Cell(2, 5).Width = 80;
                oTable.Cell(1, 6).Width = 80;
                oTable.Cell(2, 6).Width = 80;
                oTable.Cell(1, 7).Width = 60;
                oTable.Cell(2, 7).Width = 60;
                oTable.Cell(1, 8).Width = 60;
                oTable.Cell(2, 8).Width = 60;
                oTable.Cell(1, 9).Width = 60;
                oTable.Cell(2, 9).Width = 60;
                oTable.Cell(1, 10).Width = 80;
                oTable.Cell(2, 10).Width = 80;

                oTable.Cell(1, 10).Merge(oTable.Cell(2, 10));
                oTable.Cell(1, 5).Merge(oTable.Cell(1, 9));
                oTable.Cell(1, 1).Merge(oTable.Cell(2, 1));
                oTable.Cell(1, 2).Merge(oTable.Cell(2, 2));
                oTable.Cell(1, 3).Merge(oTable.Cell(2, 3));
                oTable.Cell(1, 4).Merge(oTable.Cell(2, 4));

                for (int i = 5; i < 10; i++)
                {
                    Word.Range wordCellRange = oTable.Cell(2, i).Range;
                    wordCellRange.ParagraphFormat.Alignment = Word.WdParagraphAlignment.wdAlignParagraphCenter;
                    wordCellRange.Text = nameHeaderTable[i + 1];
                    wordCellRange.Rows.HeadingFormat = -1;
                }
                //oTable.Rows.First.HeadingFormat = -1;
                //Вывод
                int posRows = 3;
                foreach (MaterKE ke in sortedList)
                {
                    label4.BeginInvoke((MethodInvoker)(() => this.label4.Text = "Информация: печать элемента " + (posRows - 2) + " из " + sortedList.Count()));
                    oTable.Rows.Add();
                    Word.Range wordCellRange = oTable.Cell(posRows, 1).Range;
                    wordCellRange.Rows.AllowBreakAcrossPages = 0;
                    wordCellRange.Text = Convert.ToString(posRows - 2);
                    wordCellRange = oTable.Cell(posRows, 2).Range;
                    wordCellRange.Text = Convert.ToString(ke.kodm) + "\n" + ke.artic + "\n" + ke.namem;
                    wordCellRange = oTable.Cell(posRows, 3).Range;
                    wordCellRange.Text = Convert.ToString(ke.kodeiz) + "\n" + Convert.ToString(ke.naik) + "\n" + ke.naimeiz;
                    wordCellRange = oTable.Cell(posRows, 4).Range;
                    wordCellRange.Text = Convert.ToString(ke.kodotr) + "\n" + ke.nzav;
                    wordCellRange = oTable.Cell(posRows, 5).Range;
                    wordCellRange.Text = Convert.ToString(ke.qpmg);
                    wordCellRange = oTable.Cell(posRows, 6).Range;
                    wordCellRange.Text = Convert.ToString(ke.qpm1);
                    wordCellRange = oTable.Cell(posRows, 7).Range;
                    wordCellRange.Text = Convert.ToString(ke.qpm2);
                    wordCellRange = oTable.Cell(posRows, 8).Range;
                    wordCellRange.Text = Convert.ToString(ke.qpm3);
                    wordCellRange = oTable.Cell(posRows, 9).Range;
                    wordCellRange.Text = Convert.ToString(ke.qpm4);
                    posRows++;
                }
                //Создание экземпляров
                label4.BeginInvoke((MethodInvoker)(() => this.label4.Text = "Информация: создание экземпляров"));
                wordDocument.Range().Copy();
                var rangeAllDocumentEkz = wordDocument.Content;
                rangeAllDocumentEkz.Find.ClearFormatting();
                //for (int j = 0; j < planIspList.Count; j++)
                {
                    functionReplaceInText(wordDocument, oWord, "{numberEkz}", "1");
                }
                for (int i = 0; i < Convert.ToInt32(countEkz) - 1; i++)
                {
                    //В конец документа
                    object what = Word.WdGoToItem.wdGoToLine;
                    object which = Word.WdGoToDirection.wdGoToLast;
                    Word.Range endRange = wordDocument.GoTo(ref what, ref which);
                    //Создаем разрыв РАЗДЕЛА (не страниц)
                    endRange.InsertBreak(Word.WdBreakType.wdSectionBreakNextPage);
                    //Вставляем
                    endRange.Paste();
                    rangeAllDocumentEkz = wordDocument.Content;
                    rangeAllDocumentEkz.Find.ClearFormatting();
                    rangeAllDocumentEkz.Find.Execute(FindText: "{numberEkz}", ReplaceWith: i + 2);
                }
                //Очистка буфера
                Clipboard.Clear();
                //Сохранение
                label4.BeginInvoke((MethodInvoker)(() => this.label4.Text = "Информация: сохранение"));
                DateTime dateNowFileName = DateTime.Now;
                string strSaveName = Convert.ToString(dateNowFileName);
                strSaveName = strSaveName.Replace(':', '-');
                if (!Directory.Exists(Properties.Settings.Default.PathForReports))
                    Directory.CreateDirectory(Directory.GetCurrentDirectory() + "/reports");
                wordDocument.SaveAs(Properties.Settings.Default.PathForReports + "/Потребность в МТР" + " " + strSaveName + ".docx");
                wordDocument.Close();
                oWord.Quit();
                label4.BeginInvoke((MethodInvoker)(() => this.label4.Text = "Информация: потребность в МТР сохранена"));
                MessageBox.Show("Потребность в МТР создана!");
            }
            catch (Exception ex)
            {
                MessageBox.Show("error: " + ex.Message);
            }
        }

        #endregion

        // Справка-обоснование потребности
        #region СПРАВКА-ОБОСНОВАНИЕ ПОТРЕБНОСТИ

        private void button30_Click(object sender, EventArgs e)
        {
            button30.BackColor = choseColorBTN;
            BlockAllButton();
            if (Properties.Settings.Default.OtraslPath.Length > 0)
            {
                if (Properties.Settings.Default.MaterFolderPath.Length > 0)
                {
                    FormChoseUchAndCount fcuac = new FormChoseUchAndCount();
                    fcuac.ShowDialog();
                    if (fcuac.isExit)
                    {
                        try
                        {
                            label4.BeginInvoke((MethodInvoker)(() => this.label4.Text = "Информация: подготовка к печати"));
                            Thread thread = new Thread(() =>
                            {
                                List<MaterSO> soList = new List<MaterSO>();
                                //Получение справки-обоснования
                                soList = GetMaterSO(soList);
                                //Заполнение справки-обоснование
                                soList = FillMaterSO(soList);
                                //Печать сводного плана испытаний
                                PrintMaterSO(soList, fcuac.uchNumber, fcuac.countEkz);

                                BlockAllButton(false);
                            });
                            thread.SetApartmentState(ApartmentState.STA);
                            thread.Start();
                        }
                        catch (Exception ex)
                        {
                            BlockAllButton(false);
                            MessageBox.Show("Очередная ошибка\n" + ex.Message + "\n" + ex.Source + "\n" + ex.StackTrace + "\n" + ex.TargetSite, "Ошибка", MessageBoxButtons.OK, MessageBoxIcon.Error);
                        }
                    }
                    else
                        BlockAllButton(false);
                }
                else
                {
                    BlockAllButton(false);
                    MessageBox.Show("Выберите поддиректорию RAHGOD");
                }
            }
            else
            {
                BlockAllButton(false);
                MessageBox.Show("Выберите директорию OTRASL");
            }
        }

        private List<MaterSO> GetMaterSO(List<MaterSO> soList)
        {
            soList.Clear();
            label4.BeginInvoke((MethodInvoker)(() => this.label4.Text = "Информация: получение справки-обоснование"));
            string strConnection = @"Provider=Microsoft.JET.Oledb.4.0;Data Source=" + Properties.Settings.Default.MaterFolderPath + "/POTR;Extended Properties=dBASE IV;User ID=;Password=;";
            using (_connection = new OleDbConnection(strConnection))
            {
                _connection.Open();
                using (OleDbCommand cmd = _connection.CreateCommand())
                {
                    cmd.CommandText = "SELECT * FROM OBOIE.DBF";
                    using (OleDbDataReader reader = cmd.ExecuteReader())
                    {
                        while (reader.Read())
                        {
                            MaterSO so = new MaterSO();
                            so.kodm = Convert.ToString(reader["KODM"]);
                            so.kodel = Convert.ToString(reader["KODEL"]);
                            so.kodotr = Convert.ToString(reader["KODOTR"]);
                            so.vispg = Convert.ToString(reader["VISPG"]);
                            so.visp1 = Convert.ToString(reader["VISP1"]);
                            so.visp2 = Convert.ToString(reader["VISP2"]);
                            so.visp3 = Convert.ToString(reader["VISP3"]);
                            so.visp4 = Convert.ToString(reader["VISP4"]);
                            soList.Add(so);
                        }
                    }
                }
            }
            return soList;
        }

        private List<MaterSO> FillMaterSO(List<MaterSO> soList)
        {
            label4.BeginInvoke((MethodInvoker)(() => this.label4.Text = "Информация: заполняем справку-обоснование"));
            //Получение информации о элементе
            label4.BeginInvoke((MethodInvoker)(() => this.label4.Text = "Информация: получение информации о МТР"));
            string strConnection = @"Provider=Microsoft.JET.Oledb.4.0;Data Source=" + Properties.Settings.Default.OtraslPath + "/SPRAV;Extended Properties=dBASE IV;User ID=;Password=;";
            using (_connection = new OleDbConnection(strConnection))
            {
                _connection.Open();
                using (OleDbCommand cmd = _connection.CreateCommand())
                {
                    foreach (MaterSO so in soList)
                    {
                        if (so.kodm != null && so.kodm != "")
                        {
                            cmd.CommandText = "SELECT * FROM SPMAT.DBF WHERE KODM='" + so.kodm + "'";
                            using (OleDbDataReader reader = cmd.ExecuteReader())
                            {
                                while (reader.Read())
                                {
                                    so.artic = Convert.ToString(reader["ARTIC"] ?? "");

                                    so.razdel = Convert.ToString(reader["RAZDEL"] ?? "");

                                    //so.kodeiz = Convert.ToString(reader["KODEIZ"] ?? "");

                                    so.namem = Convert.ToString(reader["NAIM1"] ?? "") + Convert.ToString(reader["NAIM2"] ?? "");
                                }
                            }
                        }
                        else
                            MessageBox.Show("В потребности элемент без кода!", "Косяк", MessageBoxButtons.OK, MessageBoxIcon.Error);
                    }
                }
            }
            //Получение информации о кол-ве комплектующих
            label4.BeginInvoke((MethodInvoker)(() => this.label4.Text = "Информация: получение потребности"));
            strConnection = @"Provider=Microsoft.JET.Oledb.4.0;Data Source=" + Properties.Settings.Default.MaterFolderPath + "/POTR;Extended Properties=dBASE IV;User ID=;Password=;";
            using (_connection = new OleDbConnection(strConnection))
            {
                _connection.Open();
                using (OleDbCommand cmd = _connection.CreateCommand())
                {
                    foreach (MaterSO so in soList)
                    {
                        if (so.kodm != null && so.kodm != "")
                        {
                            cmd.CommandText = "SELECT * FROM POTM.DBF";
                            using (OleDbDataReader reader = cmd.ExecuteReader())
                            {
                                while (reader.Read())
                                {
                                    so.qpmg = Convert.ToString(reader["QPMG"]);
                                    so.qpm1 = Convert.ToString(reader["QPM1"]);
                                    so.qpm2 = Convert.ToString(reader["QPM2"]);
                                    so.qpm3 = Convert.ToString(reader["QPM3"]);
                                    so.qpm4 = Convert.ToString(reader["QPM4"]);
                                }
                            }
                        }
                        else
                            MessageBox.Show("В потребности элемент без кода!", "Косяк", MessageBoxButtons.OK, MessageBoxIcon.Error);
                    }
                }
            }
            //Получение информации о элементах
            label4.BeginInvoke((MethodInvoker)(() => this.label4.Text = "Информация: получение информации о элементах"));
            strConnection = @"Provider=Microsoft.JET.Oledb.4.0;Data Source=" + Properties.Settings.Default.OtraslPath + "/SPNAIM;Extended Properties=dBASE IV;User ID=;Password=;";
            using (_connection = new OleDbConnection(strConnection))
            {
                _connection.Open();
                using (OleDbCommand cmd = _connection.CreateCommand())
                {
                    foreach (MaterSO so in soList)
                    {
                        if (so.kodel != null && so.kodel != "")
                        {
                            cmd.CommandText = "SELECT * FROM SPNAIM.DBF WHERE KODEL='" + so.kodel + "'";
                            using (OleDbDataReader reader = cmd.ExecuteReader())
                            {
                                while (reader.Read())
                                {
                                    so.snhertel = Convert.ToString(reader["SNHERT"] ?? "");

                                    so.snindizel = Convert.ToString(reader["SNINDIZ"] ?? "");

                                    so.snnaimel = Convert.ToString(reader["SNNAIM1"] ?? "") + Convert.ToString(reader["SNNAIM2"] ?? "") +
                                        Convert.ToString(reader["SNNAIM3"] ?? "") + Convert.ToString(reader["SNNAIM4"] ?? "") +
                                        Convert.ToString(reader["SNNAIM5"] ?? "") + Convert.ToString(reader["SNNAIM6"] ?? "") +
                                        Convert.ToString(reader["SNNAIM7"] ?? "") + Convert.ToString(reader["SNNAIM8"] ?? "") +
                                        Convert.ToString(reader["SNNAIM9"] ?? "") + Convert.ToString(reader["SNNAIM10"] ?? "");

                                }
                            }
                        }
                        else
                            MessageBox.Show("В потребности элемент без кода!", "Косяк", MessageBoxButtons.OK, MessageBoxIcon.Error);
                    }
                }
            }
            //Получение информации о заводе
            label4.BeginInvoke((MethodInvoker)(() => this.label4.Text = "Информация: получение информации о заводе потребителя"));
            strConnection = @"Provider=Microsoft.JET.Oledb.4.0;Data Source=" + Properties.Settings.Default.OtraslPath + "/SPRXZ;Extended Properties=dBASE IV;User ID=;Password=;";
            using (_connection = new OleDbConnection(strConnection))
            {
                _connection.Open();
                using (OleDbCommand cmd = _connection.CreateCommand())
                {
                    foreach (MaterSO so in soList)
                    {
                        cmd.CommandText = "SELECT KODOTR, NZAV1, NZAV2, NZAV3, INN FROM SPRXZ.DBF WHERE KODOTR='" + so.kodotr + "'";
                        using (OleDbDataReader reader = cmd.ExecuteReader())
                        {
                            while (reader.Read())
                            {
                                so.nzav = Convert.ToString(reader["NZAV1"]) + Convert.ToString(reader["NZAV2"]) + Convert.ToString(reader["NZAV3"]);
                                so.inn = Convert.ToString(reader["INN"]);
                            }
                        }
                    }
                }
            }
            return soList;
        }

        private void PrintMaterSO(List<MaterSO> soList, string uchNumber, string countEkz)
        {
            label4.BeginInvoke((MethodInvoker)(() => this.label4.Text = "Информация: печать справки-обоснование"));
            //Сортируем план испытаний
            var sortedList = soList.OrderBy(a => a.kodel).ThenBy(b => b.kodotr);
            try
            {
                //Создаем документ
                Word.Application oWord = new Word.Application();
                oWord.Visible = checkBox5.Checked;
                //Открытие документа
                Word.Document wordDocument;
                string path = Directory.GetCurrentDirectory() + templateFileNameWordMaterSO;
                wordDocument = oWord.Documents.Open(path);
                //Поиск и замена текста
                functionReplaceInText(wordDocument, oWord, "{uchNumber}", uchNumber);
                functionReplaceInText(wordDocument, oWord, "{year}", DateTime.Now.Year.ToString());
                //Добавление в файл таблицы
                Object autoFitBehavior = Word.WdAutoFitBehavior.wdAutoFitWindow;
                //Добавление в файл таблицы
                label4.BeginInvoke((MethodInvoker)(() => this.label4.Text = "Информация: создание таблицы"));
                var range = wordDocument.Paragraphs[wordDocument.Paragraphs.Count - 1].Range;
                wordDocument.Tables.Add(range, 1, 5, autoFitBehavior);
                Word.Table oTable = wordDocument.Tables[1];
                string[] nameHeaderTable = { "№\nп/п", "Код и наименование\nматериала", "Код и наименование\nиспытуемого\nэлемента", "Код и наименование\nпотребителя", "Заявлено (в шт) /Объем испытаний (в выстрелах)", "год", "1 кв", "2 кв", "3 кв", "4 кв" };
                for (int i = 0; i < oTable.Columns.Count; i++)
                {
                    Word.Range wordCellRange = oTable.Cell(1, i + 1).Range;
                    wordCellRange.ParagraphFormat.Alignment = Word.WdParagraphAlignment.wdAlignParagraphCenter;
                    wordCellRange.Text = nameHeaderTable[i];
                    wordCellRange.Font.Name = "Times New Roman";
                }
                oTable.Rows.First.HeadingFormat = -1;
                oTable.Cell(1, 1).Width = 35;
                oTable.Cell(1, 2).Width = 120;
                oTable.Cell(1, 3).Width = 120;
                oTable.Cell(1, 4).Width = 80;
                oTable.Cell(1, 5).Width = 370;
                oTable.Cell(1, 5).Split(2, 1);
                oTable.Cell(2, 5).Split(1, 5);
                oTable.Cell(2, 5).Width = 130;
                oTable.Cell(2, 6).Width = 60;
                oTable.Cell(2, 7).Width = 60;
                oTable.Cell(2, 8).Width = 60;
                oTable.Cell(2, 9).Width = 60;
                for (int i = 5; i < 9; i++)
                {
                    Word.Range wordCellRange = oTable.Cell(2, i).Range;
                    wordCellRange.ParagraphFormat.Alignment = Word.WdParagraphAlignment.wdAlignParagraphCenter;
                    wordCellRange.Text = nameHeaderTable[i];
                    wordCellRange.Rows.HeadingFormat = -1;
                }
                //Вывод
                string lastCodeElement = "";
                int posRows = 3;
                int numberPosition = 1;
                foreach (MaterSO so in sortedList)
                {
                    label4.BeginInvoke((MethodInvoker)(() => this.label4.Text = "Информация: печать элемента " + (posRows - 2) + " из " + sortedList.Count()));
                    oTable.Rows.Add();
                    // Нумерация
                    Word.Range wordCellRange = oTable.Cell(posRows, 1).Range;
                    wordCellRange.Rows.AllowBreakAcrossPages = 0;
                    if (lastCodeElement != so.kodm)
                    {
                        wordCellRange.Text = Convert.ToString(numberPosition);
                        numberPosition++;
                    }

                    // Материал
                    wordCellRange = oTable.Cell(posRows, 2).Range;
                    wordCellRange.Text = Convert.ToString(so.kodm) + "\n" + so.artic + "\n" + so.namem;

                    // Испытуемый элемент
                    wordCellRange = oTable.Cell(posRows, 3).Range;
                    if (lastCodeElement != so.kodel)
                        wordCellRange.Text = Convert.ToString(so.kodel) + "\n" + so.snhertel + "\n" + so.snindizel + "\n" + so.snnaimel;
                    else
                        wordCellRange.Text = "(продолжение)";

                    // Потребитель
                    wordCellRange = oTable.Cell(posRows, 4).Range;
                    wordCellRange.Text = "\n\n\n" + so.kodotr + "\n" + so.nzav;

                    // Заявлено в год
                    wordCellRange = oTable.Cell(posRows, 5).Range;
                    if (lastCodeElement != so.kodel)
                        wordCellRange.Text = "Заявлено:   " + Convert.ToString(so.qpmg) + "\n\n" + "Объём испытаний:   " + Convert.ToString(so.vispg);
                    else
                        wordCellRange.Text = "\n\n\n" + "Объём испытаний:   " + Convert.ToString(so.vispg);
                    // 1кв
                    wordCellRange = oTable.Cell(posRows, 6).Range;
                    if (lastCodeElement != so.kodel)
                        wordCellRange.Text = Convert.ToString(so.qpm1) + "\n\n" + Convert.ToString(so.visp1);
                    else
                        wordCellRange.Text = "\n\n\n" + Convert.ToString(so.visp1);
                    wordCellRange = oTable.Cell(posRows, 7).Range;
                    if (lastCodeElement != so.kodel)
                        wordCellRange.Text = Convert.ToString(so.qpm2) + "\n\n" + Convert.ToString(so.visp2);
                    else
                        wordCellRange.Text = "\n\n\n" + Convert.ToString(so.visp2);
                    wordCellRange = oTable.Cell(posRows, 8).Range;
                    if (lastCodeElement != so.kodel)
                        wordCellRange.Text = Convert.ToString(so.qpm3) + "\n\n" + Convert.ToString(so.visp3);
                    else
                        wordCellRange.Text = "\n\n\n" + Convert.ToString(so.visp3);
                    wordCellRange = oTable.Cell(posRows, 9).Range;
                    if (lastCodeElement != so.kodel)
                    {
                        wordCellRange.Text = Convert.ToString(so.qpm4) + "\n\n" + Convert.ToString(so.visp4);
                        lastCodeElement = so.kodel;
                    }
                    else
                        wordCellRange.Text = "\n\n\n" + Convert.ToString(so.visp4);
                    posRows++;
                }
                //Создание экземпляров
                label4.BeginInvoke((MethodInvoker)(() => this.label4.Text = "Информация: создание экземпляров"));
                wordDocument.Range().Copy();
                var rangeAllDocumentEkz = wordDocument.Content;
                rangeAllDocumentEkz.Find.ClearFormatting();
                //for (int j = 0; j < planIspList.Count; j++)
                {
                    functionReplaceInText(wordDocument, oWord, "{numberEkz}", "1");
                }
                for (int i = 0; i < Convert.ToInt32(countEkz) - 1; i++)
                {
                    //В конец документа
                    object what = Word.WdGoToItem.wdGoToLine;
                    object which = Word.WdGoToDirection.wdGoToLast;
                    Word.Range endRange = wordDocument.GoTo(ref what, ref which);
                    //Создаем разрыв РАЗДЕЛА (не страниц)
                    endRange.InsertBreak(Word.WdBreakType.wdSectionBreakNextPage);
                    //Вставляем
                    endRange.Paste();
                    rangeAllDocumentEkz = wordDocument.Content;
                    rangeAllDocumentEkz.Find.ClearFormatting();
                    rangeAllDocumentEkz.Find.Execute(FindText: "{numberEkz}", ReplaceWith: i + 2);
                }
                //Очистка буфера
                Clipboard.Clear();
                //Сохранение
                label4.BeginInvoke((MethodInvoker)(() => this.label4.Text = "Информация: сохранение"));
                DateTime dateNowFileName = DateTime.Now;
                string strSaveName = Convert.ToString(dateNowFileName);
                strSaveName = strSaveName.Replace(':', '-');
                if (!Directory.Exists(Properties.Settings.Default.PathForReports))
                    Directory.CreateDirectory(Directory.GetCurrentDirectory() + "/reports");
                wordDocument.SaveAs(Properties.Settings.Default.PathForReports + "/Справка-обоснование МТР" + " " + strSaveName + ".docx");
                wordDocument.Close();
                oWord.Quit();
                label4.BeginInvoke((MethodInvoker)(() => this.label4.Text = "Информация: справка-обоснование МТР сохранена!"));
                MessageBox.Show("Справка-обоснование МТР создана!");
            }
            catch (Exception ex)
            {
                MessageBox.Show("error: " + ex.Message);
            }
        }

        #endregion


        //---------М ПЛАН (НОВЫЙ)---------
        // Печать плана испытаний
        #region ПЛАН ИСПЫТАНИЙ

        private void button32_Click(object sender, EventArgs e)
        {
            button32.BackColor = choseColorBTN;
            BlockAllButton();
            if (Properties.Settings.Default.OtraslPath.Length > 0)
            {
                if (Properties.Settings.Default.RashGodFolderPath.Length > 0)
                {
                    try
                    {
                        label5.BeginInvoke((MethodInvoker)(() => this.label5.Text = "Информация: подготовка к печати"));
                        Thread thread = new Thread(() =>
                        {
                            List<RashGodPlanIsp> planIspList = new List<RashGodPlanIsp>();
                            //Получение плана испытаний
                            planIspList = GetRashGodPlanIsp(planIspList, label5);
                            //Заполнение плана испытанйи
                            planIspList = FillRashGodPlanIsp(planIspList, label5);
                            //Печать плана испытаний
                            PrintFormaIsp(planIspList);

                            BlockAllButton(false);
                        });
                        thread.SetApartmentState(ApartmentState.STA);
                        thread.Start();
                    }
                    catch (Exception ex)
                    {
                        BlockAllButton(false);
                        MessageBox.Show("Очередная ошибка\n" + ex.Message + "\n" + ex.Source + "\n" + ex.StackTrace + "\n" + ex.TargetSite, "Ошибка", MessageBoxButtons.OK, MessageBoxIcon.Error);
                    }
                }
                else
                {
                    BlockAllButton(false);
                    MessageBox.Show("Выберите поддиректорию RAHGOD");
                }
            }
            else
            {
                BlockAllButton(false);
                MessageBox.Show("Выберите директорию OTRASL");
            }
        }

        private void PrintFormaIsp(List<RashGodPlanIsp> planIspList)
        {
            label5.BeginInvoke((MethodInvoker)(() => this.label5.Text = "Информация: подготовка к печати"));
            //Сортируем анализ готовности
            var sortedList = planIspList.OrderBy(a => a.kodotr).ThenBy(b => b.kodel).ThenBy(c => c.shvid);

            //Создаем документ
            Excel.Application oExcel = new Excel.Application();
            oExcel.Visible = checkBox6.Checked;
            try
            {
                //Открытие шаблона
                string path = Directory.GetCurrentDirectory() + templateFileNameExcelFormaPlanIsp;
                Excel.Workbook excelDocument = oExcel.Workbooks.Open(path);

                Excel.Workbooks excelWorkBooks = oExcel.Workbooks;
                Excel.Workbook excelWorkBook = excelWorkBooks[1];
                excelWorkBook.Saved = false;
                Excel.Sheets excelSheets = oExcel.Worksheets;
                Excel.Worksheet excelWorkSheets = excelSheets.get_Item(1);

                // Заголовок
                // Наименование организации
                Excel.Range excelCells;
                excelCells = excelWorkSheets.get_Range("B8");
                excelCells.Value2 = "ФИЛИАЛ \"НИЖНЕТАГИЛЬСКИЙ ИНСТИТУТ ИСПЫТАНИЯ МЕТАЛЛОВ\" ФЕДЕРАЛЬНОГО КАЗЕННОГО ПРЕДПРИЯТИЯ \"НАЦИОНАЛЬНОЕ ИСПЫТАТЕЛЬНОЕ ОБЪЕДИНЕНИЕ \"ГОСУДАРСТВЕННЫЕ БОЕПРИПАСНЫЕ ИСПЫТАТЕЛЬНЫЕ ПОЛИГОНЫ РОССИИ\"";
                // ИНН организации
                excelCells = excelWorkSheets.get_Range("D8");
                excelCells.Value2 = "5023002050";
                // ОКВЭД организации
                excelCells = excelWorkSheets.get_Range("E8");
                excelCells.Value2 = "72.19";
                //Вывод
                int numberPosition = 1;
                int posRows = 14;
                foreach (RashGodPlanIsp rgpi in sortedList)
                {
                    label5.BeginInvoke((MethodInvoker)(() => this.label5.Text = "Информация: печать элемента " + numberPosition + " из " + planIspList.Count));

                    // Порядковый номер
                    excelCells = excelWorkSheets.get_Range("A" + posRows);
                    excelCells.Value2 = Convert.ToString(numberPosition);
                    // Код ОКПД2
                    excelCells = excelWorkSheets.get_Range("B" + posRows);

                    // Наименование КИ
                    excelCells = excelWorkSheets.get_Range("C" + posRows);
                    excelCells.Value2 = rgpi.snnaim;
                    // Наименование заказчика, ИНН
                    excelCells = excelWorkSheets.get_Range("D" + posRows);
                    excelCells.Value2 = (rgpi.kodotr + "\n" + rgpi.nzav + "\n" + rgpi.inn).Replace('*','"');
                    // Субъект Федерации
                    excelCells = excelWorkSheets.get_Range("E" + posRows);
                    excelCells.Value2 = "Филиал \"НТИИМ\" ФКП \"НИО \"ГБИП России\"\n622015, Российская Федерация, Свердловская область, г. Нижний Тагил, ул. Гагарина, д. 29";
                    // Реквезиты приказа

                    // Место поставки готовой продукции

                    // Наименование чертежа КД, ТД, ГОСТ
                    excelCells = excelWorkSheets.get_Range("H" + posRows);
                    excelCells.Value2 = rgpi.kodel + "\n" + rgpi.snhert + "\n" + rgpi.snindiz;
                    // Ед. изм
                    excelCells = excelWorkSheets.get_Range("I" + posRows);
                    excelCells.Value2 = "шт.";
                    // Всего
                    excelCells = excelWorkSheets.get_Range("J" + posRows);
                    excelCells.Value2 = rgpi.qolg;
                    // 1кв
                    excelCells = excelWorkSheets.get_Range("K" + posRows);
                    excelCells.Value2 = rgpi.qol1;
                    // 2кв
                    excelCells = excelWorkSheets.get_Range("L" + posRows);
                    excelCells.Value2 = rgpi.qol2;
                    // 3кв
                    excelCells = excelWorkSheets.get_Range("M" + posRows);
                    excelCells.Value2 = rgpi.qol3;
                    // 4кв
                    excelCells = excelWorkSheets.get_Range("N" + posRows);
                    excelCells.Value2 = rgpi.qol4;

                    numberPosition++;
                    posRows++;
                }

                //Выравнивание таблицы по верхнему краю вертикально и по центру горизонтально
                excelCells = excelWorkSheets.Range["A14", "P" + (posRows - 1)];
                excelCells.VerticalAlignment = Excel.XlVAlign.xlVAlignTop;
                excelCells.HorizontalAlignment = Excel.XlHAlign.xlHAlignCenter;
                excelCells.Font.Name = "Times New Roman";
                //Границы
                excelCells.Borders.LineStyle = Excel.XlLineStyle.xlContinuous;

                //Очистка буфера
                Clipboard.Clear();
                //Сохранение
                label5.BeginInvoke((MethodInvoker)(() => this.label5.Text = "Информация: сохранение"));
                DateTime dateNowFileName = DateTime.Now;
                string strSaveName = Convert.ToString(dateNowFileName);
                strSaveName = strSaveName.Replace(':', '-');
                if (!Directory.Exists(Properties.Settings.Default.PathForReports))
                    Directory.CreateDirectory(Directory.GetCurrentDirectory() + "/reports");
                string savePath = Properties.Settings.Default.PathForReports + "/Форма М плана" + " " + strSaveName + ".xls";
                excelDocument.SaveAs(savePath);
                excelDocument.Close();
                oExcel.Quit();
                label5.BeginInvoke((MethodInvoker)(() => this.label5.Text = "Информация: план создан готов"));
                MessageBox.Show("План создан!");
            }
            catch (Exception ex)
            {
                oExcel.Visible = true;
                label5.BeginInvoke((MethodInvoker)(() => this.label5.Text = "Информация: ошибка!"));
                MessageBox.Show("error: " + ex.Message);
            }
        }

        #endregion

        // Печать потребности
        #region ПОТРЕБНОСТЬ (Как анализ готовности)

        private void button31_Click(object sender, EventArgs e)
        {
            button31.BackColor = choseColorBTN;
            BlockAllButton();
            if (Properties.Settings.Default.OtraslPath.Length > 0)
            {
                if (Properties.Settings.Default.RashGodFolderPath.Length > 0)
                {
                    try
                    {
                        label5.BeginInvoke((MethodInvoker)(() => this.label5.Text = "Информация: подготовка к печати"));
                        Thread thread = new Thread(() =>
                        {
                            try
                            {
                                List<RashGodPlanIsp> planIspList = new List<RashGodPlanIsp>();
                                //Получение плана испытаний
                                planIspList = GetRashGodPlanIsp(planIspList, label5);
                                //Заполнение плана испытанйи
                                planIspList = FillRashGodPlanIsp(planIspList, label5);
                                //Заполнение анализа готовности
                                planIspList = FillRashGodAG(planIspList);

                                //Печать анализа готовности
                                PrintFormaKE(planIspList);
                            }
                            catch (Exception ex)
                            {
                                MessageBox.Show("Очередная ошибка ин поток\n" + ex.Message + "\n" + ex.Source + "\n" + ex.StackTrace + "\n" + ex.TargetSite, "Ошибка", MessageBoxButtons.OK, MessageBoxIcon.Error);
                            }
                            finally
                            {
                                BlockAllButton(false);
                            }
                        });
                        thread.SetApartmentState(ApartmentState.STA);
                        thread.Start();
                    }
                    catch (Exception ex)
                    {
                        BlockAllButton(false);
                        MessageBox.Show("Очередная ошибка\n" + ex.Message + "\n" + ex.Source + "\n" + ex.StackTrace + "\n" + ex.TargetSite, "Ошибка", MessageBoxButtons.OK, MessageBoxIcon.Error);
                    }
                }
                else
                {
                    BlockAllButton(false);
                    MessageBox.Show("Выберите поддиректорию RAHGOD");
                }
            }
            else
            {
                BlockAllButton(false);
                MessageBox.Show("Выберите директорию OTRASL");
            }
        }

        private void PrintFormaKE(List<RashGodPlanIsp> planIspList)
        {
            label5.BeginInvoke((MethodInvoker)(() => this.label5.Text = "Информация: подготовка к печати"));
            //Сортируем анализ готовности
            var sortedList = planIspList.OrderBy(a => a.kodotr).ThenBy(b => b.kodel).ThenBy(c => c.shvid);

            //Создаем документ
            Excel.Application oExcel = new Excel.Application();
            oExcel.Visible = checkBox6.Checked;
            try
            {
                //Открытие шаблона
                string path = Directory.GetCurrentDirectory() + templateFileNameExcelFormaKE;
                Excel.Workbook excelDocument = oExcel.Workbooks.Open(path);

                int currentSheet = 1;
                Excel.Workbooks excelWorkBooks = oExcel.Workbooks;
                Excel.Workbook excelWorkBook = excelWorkBooks[1];
                excelWorkBook.Saved = false;
                Excel.Sheets excelSheets = oExcel.Worksheets;
                Excel.Worksheet excelWorkSheets = excelSheets.get_Item(currentSheet);

                // Позициирование
                string posNameOrg = "A7";
                string posElement = "B12";
                string okpdElement = "D12";
                string edIzmElement = "E12";
                string allElement = "G12";
                string firstElement = "H12";
                string secondElement = "I12";
                string thirdElement = "J12";
                string thourElement = "K12";

                Excel.Range excelCells;

                //Вывод
                int numberPosition = 1;
                int posRows = 18;
                int beginPosRows = 18;
                foreach (RashGodPlanIsp rgpi in sortedList)
                {
                    label5.BeginInvoke((MethodInvoker)(() => this.label5.Text = "Информация: печать элемента " + numberPosition + " из " + planIspList.Count));

                    if (currentSheet >= 2)
                    {
                        posNameOrg = "A2";
                        posElement = "B9";
                        okpdElement = "D9";
                        edIzmElement = "E9";
                        allElement = "G9";
                        firstElement = "H9";
                        secondElement = "I9";
                        thirdElement = "J9";
                        thourElement = "K9";
                        posRows = 15;
                        beginPosRows = 15;
                        numberPosition = 1;
                        // Активируем следующий лист и копируем содержимое
                        excelWorkSheets = excelSheets.get_Item(currentSheet);
                        excelWorkSheets.Copy(After: excelWorkSheets);
                        excelWorkSheets.Name = Convert.ToString(currentSheet);
                    }

                    // Наименование организации
                    excelCells = excelWorkSheets.get_Range(posNameOrg);
                    excelCells.Value2 = "ФИЛИАЛ \"НИЖНЕТАГИЛЬСКИЙ ИНСТИТУТ ИСПЫТАНИЯ МЕТАЛЛОВ\" ФЕДЕРАЛЬНОГО КАЗЕННОГО ПРЕДПРИЯТИЯ \"НАЦИОНАЛЬНОЕ ИСПЫТАТЕЛЬНОЕ ОБЪЕДИНЕНИЕ \"ГОСУДАРСТВЕННЫЕ БОЕПРИПАСНЫЕ ИСПЫТАТЕЛЬНЫЕ ПОЛИГОНЫ РОССИИ\"";
                    // Наименование КИ
                    excelCells = excelWorkSheets.get_Range(posElement);
                    excelCells.Value2 = rgpi.snhert + "\n" + rgpi.snindiz + "\n" + rgpi.snnaim;
                    // Код ОКПД2
                    excelCells = excelWorkSheets.get_Range(okpdElement);
                    excelCells.Value2 = rgpi.kodel;
                    // Ед. изм
                    excelCells = excelWorkSheets.get_Range(edIzmElement);
                    excelCells.Value2 = "шт.";
                    // Всего
                    excelCells = excelWorkSheets.get_Range(allElement);
                    excelCells.Value2 = rgpi.qolg;
                    // 1кв
                    excelCells = excelWorkSheets.get_Range(firstElement);
                    excelCells.Value2 = rgpi.qol1;
                    // 2кв
                    excelCells = excelWorkSheets.get_Range(secondElement);
                    excelCells.Value2 = rgpi.qol2;
                    // 3кв
                    excelCells = excelWorkSheets.get_Range(thirdElement);
                    excelCells.Value2 = rgpi.qol3;
                    // 4кв
                    excelCells = excelWorkSheets.get_Range(thourElement);
                    excelCells.Value2 = rgpi.qol4;

                    //Комплектующие элементы
                    foreach (RashGodPlanIsp ag in rgpi.agList)
                    {
                        // Порядковый номер
                        excelCells = excelWorkSheets.get_Range("A" + posRows);
                        excelCells.Value2 = Convert.ToString(numberPosition);

                        // Код ОКПД2
                        excelCells = excelWorkSheets.get_Range("B" + posRows);


                        // Наименование СКИ
                        excelCells = excelWorkSheets.get_Range("C" + posRows);
                        excelCells.Value2 = ag.snnaim;

                        // Наименование ГОСТ
                        excelCells = excelWorkSheets.get_Range("D" + posRows);
                        excelCells.Value2 = ag.kodel + "\n" + ag.snhert + "\n" + ag.snindiz;

                        // Ед. изм.
                        excelCells = excelWorkSheets.get_Range("E" + posRows);
                        excelCells.Value2 = ag.naimeiz;

                        // Необходимая на единицу комплектация
                        excelCells = excelWorkSheets.get_Range("F" + posRows);
                        excelCells.Value2 = ag.countOnOne;

                        //Объём потребности
                        excelCells = excelWorkSheets.get_Range("G" + posRows);
                        excelCells.Value2 = ag.qolg;
                        excelCells = excelWorkSheets.get_Range("H" + posRows);
                        excelCells.Value2 = ag.qol1;
                        excelCells = excelWorkSheets.get_Range("I" + posRows);
                        excelCells.Value2 = ag.qol2;
                        excelCells = excelWorkSheets.get_Range("J" + posRows);
                        excelCells.Value2 = ag.qol3;
                        excelCells = excelWorkSheets.get_Range("K" + posRows);
                        excelCells.Value2 = ag.qol4;

                        //Организация-поставщик
                        excelCells = excelWorkSheets.get_Range("L" + posRows);
                        excelCells.Value2 = ag.nzav;
                        excelCells = excelWorkSheets.get_Range("M" + posRows);
                        excelCells.Value2 = ag.inn;

                        numberPosition++;
                        posRows++;
                    }

                    //Выравнивание таблицы по верхнему краю вертикально и по центру горизонтально
                    excelCells = excelWorkSheets.Range["A" + beginPosRows, "M" + (posRows - 1)];
                    excelCells.VerticalAlignment = Excel.XlVAlign.xlVAlignTop;
                    excelCells.HorizontalAlignment = Excel.XlHAlign.xlHAlignCenter;
                    excelCells.Font.Name = "Times New Roman";
                    //Границы
                    excelCells.Borders.LineStyle = Excel.XlLineStyle.xlContinuous;

                    currentSheet++;
                }

                //Очистка буфера
                Clipboard.Clear();
                //Сохранение
                label5.BeginInvoke((MethodInvoker)(() => this.label5.Text = "Информация: сохранение"));
                DateTime dateNowFileName = DateTime.Now;
                string strSaveName = Convert.ToString(dateNowFileName);
                strSaveName = strSaveName.Replace(':', '-');
                if (!Directory.Exists(Properties.Settings.Default.PathForReports))
                    Directory.CreateDirectory(Directory.GetCurrentDirectory() + "/reports");
                string savePath = Properties.Settings.Default.PathForReports + "/Форма М поставка СКИ" + " " + strSaveName + ".xls";
                excelDocument.SaveAs(savePath);
                excelDocument.Close();
                oExcel.Quit();
                label5.BeginInvoke((MethodInvoker)(() => this.label5.Text = "Информация: форма М поставка СКИ готов"));
                MessageBox.Show("Форма М поставка СКИ создан!");
            }
            catch (Exception ex)
            {
                oExcel.Visible = true;
                label5.BeginInvoke((MethodInvoker)(() => this.label5.Text = "Информация: ошибка!"));
                MessageBox.Show("error: " + ex.Message);
            }
        }

        #endregion

        // Печать пустышки под трудозатраты из плана испытаний
        #region ПУСТЫЕ ТУРДОЗАТРАТЫ

        private void button33_Click(object sender, EventArgs e)
        {
            button33.BackColor = choseColorBTN;
            BlockAllButton();
            if (Properties.Settings.Default.OtraslPath.Length > 0)
            {
                if (Properties.Settings.Default.RashGodFolderPath.Length > 0)
                {
                    FormChoseUchYearCount fcuyc = new FormChoseUchYearCount();
                    fcuyc.ShowDialog();
                    if (fcuyc.isExit)
                    {
                        try
                        {
                            label5.BeginInvoke((MethodInvoker)(() => this.label5.Text = "Информация: подготовка к печати"));
                            Thread thread = new Thread(() =>
                            {
                                List<RashGodPlanIsp> planIspList = new List<RashGodPlanIsp>();
                                //Получение плана испытаний
                                planIspList = GetRashGodPlanIsp(planIspList, label5);
                                //Заполнение плана испытанйи
                                planIspList = FillRashGodPlanIsp(planIspList, label5);
                                //Печать пустых трудозатрат
                                PrintNormTimeIsp(planIspList, fcuyc.year, fcuyc.uchNumber, fcuyc.count);

                                BlockAllButton(false);
                            });
                            thread.SetApartmentState(ApartmentState.STA);
                            thread.Start();
                        }
                        catch (Exception ex)
                        {
                            BlockAllButton(false);
                            MessageBox.Show("Очередная ошибка\n" + ex.Message + "\n" + ex.Source + "\n" + ex.StackTrace + "\n" + ex.TargetSite, "Ошибка", MessageBoxButtons.OK, MessageBoxIcon.Error);
                        }
                    }
                    else
                        BlockAllButton(false);
                }
                else
                {
                    BlockAllButton(false);
                    MessageBox.Show("Выберите поддиректорию RAHGOD");
                }
            }
            else
            {
                BlockAllButton(false);
                MessageBox.Show("Выберите директорию OTRASL");
            }
        }

        private void PrintNormTimeIsp(List<RashGodPlanIsp> planIspList, string yearPrintResult, string uchNumber, string countEkz)
        {
            label5.BeginInvoke((MethodInvoker)(() => this.label5.Text = "Информация: подготовка к печати"));
            //Сортируем план испытаний
            var sortedList = planIspList.OrderBy(a => a.kodotr).ThenBy(b => b.kodel).ThenBy(c => c.shvid);
            try
            {
                //Создаем документ
                Word.Application oWord = new Word.Application();
                oWord.Visible = checkBox6.Checked;

                string path = Directory.GetCurrentDirectory() + templateFileNameWordNormTimeIsp;
                Word.Document wordDocument = oWord.Documents.Open(path);

                //Поиск и замена текста
                functionReplaceInText(wordDocument, oWord, "{uchNumber}", uchNumber);
                functionReplaceInText(wordDocument, oWord, "{year}", yearPrintResult);
                functionReplaceInText(wordDocument, oWord, "{year}", yearPrintResult);
                functionReplaceInText(wordDocument, oWord, "{nameWorkNormTime}", "на проведение контрольных испытаний");

                //Добавление в файл таблицы
                label5.BeginInvoke((MethodInvoker)(() => this.label5.Text = "Информация: создание шапки таблицы"));
                wordDocument.Paragraphs.Add();
                Object autoFitBehavior = Word.WdAutoFitBehavior.wdAutoFitWindow;
                var range = wordDocument.Paragraphs[wordDocument.Paragraphs.Count - 1].Range;
                wordDocument.Tables.Add(range, 1, 6, autoFitBehavior);
                Word.Table oTable = wordDocument.Tables[1];
                string[] nameHeaderTable = { "№\nп/п", "Шифр,\nнаименование\nэлемента", "Шифр,\nнаименование\nвида испытания", "ФКП НТИИМ", "Vисп.", "Трудозатраты", "подразделение", "нормочасы\nна 1 изделие" };
                for (int i = 0; i < oTable.Columns.Count; i++)
                {
                    Word.Range wordCellRange = oTable.Cell(1, i + 1).Range;
                    wordCellRange.ParagraphFormat.Alignment = Word.WdParagraphAlignment.wdAlignParagraphCenter;
                    wordCellRange.Text = nameHeaderTable[i];
                    wordCellRange.Rows.HeadingFormat = -1;
                    //Изменение размера шрифта
                    wordCellRange.Font.Size = 10;
                    //Изменение шрифта
                    wordCellRange.Font.Name = "Times New Roman";
                }
                oTable.Rows.First.HeadingFormat = -1;
                oTable.Cell(1, 1).Width = 35;
                oTable.Cell(1, 2).Width = 150;
                oTable.Cell(1, 3).Width = 100;
                oTable.Cell(1, 4).Width = 250;
                oTable.Cell(1, 5).Width = 70;
                oTable.Cell(1, 6).Width = 100;
                oTable.Cell(1, 4).Split(2, 1);
                oTable.Cell(2, 4).Split(1, 2);
                for (int i = 4; i < 6; i++)
                {
                    Word.Range wordCellRange = oTable.Cell(2, i).Range;
                    wordCellRange.ParagraphFormat.Alignment = Word.WdParagraphAlignment.wdAlignParagraphCenter;
                    wordCellRange.Text = nameHeaderTable[i + 2];
                    wordCellRange.Rows.HeadingFormat = -1;
                }
                // Вывод
                int countPrint = 1;
                int posRows = 3;
                foreach (RashGodPlanIsp isp in sortedList)
                {
                    label5.BeginInvoke((MethodInvoker)(() => this.label5.Text = "Информация: печать элемента " + (countPrint) + " из " + sortedList.Count()));
                    oTable.Rows.Add();
                    // Порядковый номер
                    Word.Range wordCellRange = oTable.Cell(posRows, 1).Range;
                    wordCellRange.Rows.HeadingFormat = 0;
                    wordCellRange.Rows.AllowBreakAcrossPages = 0;
                    wordCellRange.Text = Convert.ToString(countPrint);
                    // Шифр, наименование элемента
                    wordCellRange = oTable.Cell(posRows, 2).Range;
                    wordCellRange.Rows.HeadingFormat = 0;
                    wordCellRange.Text = "     " + Convert.ToString(isp.kodel) + "\n" + isp.snhert + "\n" + isp.snindiz + "\n" + isp.snnaim;
                    wordCellRange.ParagraphFormat.Alignment = Word.WdParagraphAlignment.wdAlignParagraphLeft;
                    // Вид испытания
                    wordCellRange = oTable.Cell(posRows, 3).Range;
                    wordCellRange.Rows.HeadingFormat = 0;
                    wordCellRange.Text = Convert.ToString(isp.shvid) + "\n" + isp.nvid;
                    wordCellRange.ParagraphFormat.Alignment = Word.WdParagraphAlignment.wdAlignParagraphLeft;

                    // Наименование групп
                    oTable.Cell(posRows, 4).Split(17, 1);
                    wordCellRange = oTable.Cell(posRows, 4).Range;
                    wordCellRange.Text = "1 цех";
                    wordCellRange = oTable.Cell(posRows + 1, 4).Range;
                    wordCellRange.Text = "2 цех";
                    wordCellRange = oTable.Cell(posRows + 2, 4).Range;
                    wordCellRange.Text = "3 цех";
                    wordCellRange = oTable.Cell(posRows + 3, 4).Range;
                    wordCellRange.Text = "Темперирование";
                    wordCellRange = oTable.Cell(posRows + 4, 4).Range;
                    wordCellRange.Text = "ОТК";
                    wordCellRange = oTable.Cell(posRows + 5, 4).Range;
                    wordCellRange.Text = "2 отдел";
                    wordCellRange = oTable.Cell(posRows + 6, 4).Range;
                    wordCellRange.Text = "15 отдел";
                    wordCellRange = oTable.Cell(posRows + 7, 4).Range;
                    wordCellRange.Text = "93 отдел";
                    wordCellRange = oTable.Cell(posRows + 8, 4).Range;
                    wordCellRange.Text = "   гр.Луч";
                    wordCellRange = oTable.Cell(posRows + 9, 4).Range;
                    wordCellRange.Text = "   гр.Крешер";
                    wordCellRange = oTable.Cell(posRows + 10, 4).Range;
                    wordCellRange.Text = "   гр.Метео";
                    wordCellRange = oTable.Cell(posRows + 11, 4).Range;
                    wordCellRange.Text = "   гр.Кама";
                    wordCellRange = oTable.Cell(posRows + 12, 4).Range;
                    wordCellRange.Text = "   гр.Соленоиды";
                    wordCellRange = oTable.Cell(posRows + 13, 4).Range;
                    wordCellRange.Text = "   гр.ГДИ";
                    wordCellRange = oTable.Cell(posRows + 14, 4).Range;
                    wordCellRange.Text = "   гр.СВК";
                    wordCellRange = oTable.Cell(posRows + 15, 4).Range;
                    wordCellRange.Text = "   гр.Ветер";
                    wordCellRange = oTable.Cell(posRows + 16, 4).Range;
                    wordCellRange.Text = "   гр.Зуммер";

                    // Время
                    oTable.Cell(posRows, 5).Split(17, 1);

                    // Количество испытаний
                    wordCellRange = oTable.Cell(posRows, 6).Range;
                    wordCellRange.Rows.HeadingFormat = 0;
                    wordCellRange.Text = Convert.ToString(isp.qolg);

                    // Сплитуем итоговое время
                    oTable.Cell(posRows, 7).Split(17, 1);

                    posRows += 17;
                    countPrint++;
                }
                //Создание экземпляров
                label5.BeginInvoke((MethodInvoker)(() => this.label5.Text = "Информация: создание экземпляров"));
                wordDocument.Range().Copy();
                var rangeAllDocumentEkz = wordDocument.Content;
                rangeAllDocumentEkz.Find.ClearFormatting();
                //for (int j = 0; j < planIspList.Count; j++)
                {
                    functionReplaceInText(wordDocument, oWord, "{numberEkz}", "1");
                }
                for (int i = 0; i < Convert.ToInt32(countEkz) - 1; i++)
                {
                    //В конец документа
                    object what = Word.WdGoToItem.wdGoToLine;
                    object which = Word.WdGoToDirection.wdGoToLast;
                    Word.Range endRange = wordDocument.GoTo(ref what, ref which);
                    //Создаем разрыв РАЗДЕЛА (не страниц)
                    endRange.InsertBreak(Word.WdBreakType.wdSectionBreakNextPage);
                    //Вставляем
                    endRange.Paste();
                    rangeAllDocumentEkz = wordDocument.Content;
                    rangeAllDocumentEkz.Find.ClearFormatting();
                    rangeAllDocumentEkz.Find.Execute(FindText: "{numberEkz}", ReplaceWith: i + 2);
                }
                //Очистка буфера
                Clipboard.Clear();
                //Сохранение
                label5.BeginInvoke((MethodInvoker)(() => this.label5.Text = "Информация: сохранение"));
                DateTime dateNowFileName = DateTime.Now;
                string strSaveName = Convert.ToString(dateNowFileName);
                strSaveName = strSaveName.Replace(':', '-');
                if (!Directory.Exists(Properties.Settings.Default.PathForReports))
                    Directory.CreateDirectory(Directory.GetCurrentDirectory() + "/reports");
                wordDocument.SaveAs(Properties.Settings.Default.PathForReports + "/Пустые трудозатраты испытаний" + " " + strSaveName + ".docx");
                wordDocument.Close();
                oWord.Quit();
                label5.BeginInvoke((MethodInvoker)(() => this.label5.Text = "Информация: пустые трудозатраты испытаний сохранены!"));
                MessageBox.Show("Пустые трудозатраты испытаний созданы!");
            }
            catch (Exception ex)
            {
                MessageBox.Show("error: " + ex.Message);
            }
        }

        #endregion


        //---------Результ ГОД---------
        //Печать плана испытаний
        #region Результ ПЛАН ИСПЫТАНИЙ
        private void button1_Click(object sender, EventArgs e)
        { 
            button1.BackColor = choseColorBTN;
            BlockAllButton();
            if (Properties.Settings.Default.OtraslPath.Length > 0)
            {
                if (Properties.Settings.Default.RashGodFolderPath.Length > 0)
                {
                    FormChoseUchYearCount fcuyc = new FormChoseUchYearCount();
                    fcuyc.ShowDialog();
                    if (fcuyc.isExit)
                    {
                        try
                        {
                            label3.BeginInvoke((MethodInvoker)(() => this.label3.Text = "Информация: подготовка к печати"));
                            Thread thread = new Thread(() =>
                            {
                                List<RashGodPlanIsp> planIspList = new List<RashGodPlanIsp>();
                                //Получение плана испытаний
                                planIspList = GetRashGodPlanIsp(planIspList);
                                //Заполнение плана испытанйи
                                planIspList = FillRashGodPlanIsp(planIspList);
                                //Печать сводного плана испытаний
                                PrintRashGodPlanIsp(planIspList, fcuyc.year, fcuyc.uchNumber, fcuyc.count);

                                if (DialogResult.Yes == MessageBox.Show("Желаете вывести на печать полигонные планы испытаний?", "Внимание", MessageBoxButtons.YesNo, MessageBoxIcon.Information))
                                {
                                    //Получение списка полигонов из плана испытаний
                                    Dictionary<string, string> polList = GetPolRashGodPlanIsp(planIspList);
                                    //Печать полигонного плана испытаний
                                    foreach (KeyValuePair<string,string> poligon in polList)
                                    {
                                        PrintRashGodPlanIsp(planIspList, fcuyc.year, fcuyc.uchNumber, fcuyc.count, poligon.Key, poligon.Value);
                                    }
                                }
                                BlockAllButton(false);
                            });
                            thread.SetApartmentState(ApartmentState.STA);
                            thread.Start();
                        }
                        catch (Exception ex)
                        {
                            BlockAllButton(false);
                            MessageBox.Show("Очередная ошибка\n" + ex.Message + "\n" + ex.Source + "\n" + ex.StackTrace + "\n" + ex.TargetSite, "Ошибка", MessageBoxButtons.OK, MessageBoxIcon.Error);
                        }
                    }
                    else
                        BlockAllButton(false);
                }
                else
                {
                    BlockAllButton(false);
                    MessageBox.Show("Выберите поддиректорию RAHGOD");
                }
            }
            else
            {
                BlockAllButton(false);
                MessageBox.Show("Выберите директорию OTRASL");
            }
        }

        private List<RashGodPlanIsp> GetRashGodPlanIsp(List<RashGodPlanIsp> planIspList, Label labenCurrent = null)
        {
            if (labenCurrent == null)
                labenCurrent = label3;

            planIspList.Clear();
            labenCurrent.BeginInvoke((MethodInvoker)(() => labenCurrent.Text = "Информация: получение плана"));
            string strConnection = @"Provider=Microsoft.JET.Oledb.4.0;Data Source=" + Properties.Settings.Default.OtraslPath + "/RAHGOD/" + Properties.Settings.Default.RashGodFolderPath + "/PLAN;Extended Properties=dBASE IV;User ID=;Password=;";
            using (_connection = new OleDbConnection(strConnection))
            {
                _connection.Open();
                using (OleDbCommand cmd = _connection.CreateCommand())
                {
                    cmd.CommandText = "SELECT * FROM PLISP.DBF";
                    using (OleDbDataReader reader = cmd.ExecuteReader())
                    {
                        while (reader.Read())
                        {
                            RashGodPlanIsp isp = new RashGodPlanIsp();
                            isp.kodel = Convert.ToString(reader["KODEL"]);
                            isp.kodotr = Convert.ToString(reader["KODOTR"]);
                            isp.shvid = Convert.ToString(reader["SHVID"]);
                            isp.pol = Convert.ToString(reader["POL"]);
                            isp.kodeiz = Convert.ToString(reader["KODEIZ"]);
                            isp.qolg = Convert.ToString(reader["QOLG"]);
                            isp.qol1 = Convert.ToString(reader["QOL1"]);
                            isp.qol2 = Convert.ToString(reader["QOL2"]);
                            isp.qol3 = Convert.ToString(reader["QOL3"]);
                            isp.qol4 = Convert.ToString(reader["QOL4"]);
                            isp.shsis = Convert.ToString(reader["SHSIS"]);
                            isp.shsis2 = Convert.ToString(reader["SHSIS2"]);
                            isp.shsis3 = Convert.ToString(reader["SHSIS3"]);
                            planIspList.Add(isp);
                        }
                    }
                }
            }
            return planIspList;
        }

        private List<RashGodPlanIsp> FillRashGodPlanIsp(List<RashGodPlanIsp> planIspList, Label labelCurrent = null)
        {
            if (labelCurrent == null)
                labelCurrent = label3;

            labelCurrent.BeginInvoke((MethodInvoker)(() => labelCurrent.Text = "Информация: получение информации о элементах"));
            //Получение информации о элементе
            string strConnection = @"Provider=Microsoft.JET.Oledb.4.0;Data Source=" + Properties.Settings.Default.OtraslPath + "/SPNAIM;Extended Properties=dBASE IV;User ID=;Password=;";
            using (_connection = new OleDbConnection(strConnection))
            {
                _connection.Open();
                using (OleDbCommand cmd = _connection.CreateCommand())
                {
                    foreach (RashGodPlanIsp isp in planIspList)
                    {
                        if (isp.kodel != null && isp.kodel != "")
                        {
                            cmd.CommandText = "SELECT * FROM SPNAIM.DBF WHERE KODEL='" + isp.kodel + "'";
                            using (OleDbDataReader reader = cmd.ExecuteReader())
                            {
                                while (reader.Read())
                                {
                                    isp.snhert = Convert.ToString(reader["SNHERT"] ?? "");

                                    isp.snindiz = Convert.ToString(reader["SNINDIZ"] ?? "");

                                    isp.snnaim = Convert.ToString(reader["SNNAIM1"] ?? "") + Convert.ToString(reader["SNNAIM2"] ?? "") +
                                        Convert.ToString(reader["SNNAIM3"] ?? "") + Convert.ToString(reader["SNNAIM4"] ?? "") +
                                        Convert.ToString(reader["SNNAIM5"] ?? "") + Convert.ToString(reader["SNNAIM6"] ?? "") +
                                        Convert.ToString(reader["SNNAIM7"] ?? "") + Convert.ToString(reader["SNNAIM8"] ?? "") +
                                        Convert.ToString(reader["SNNAIM9"] ?? "") + Convert.ToString(reader["SNNAIM10"] ?? "");

                                }
                            }
                        }
                        else
                            MessageBox.Show("В плане элемент без кода!", "Косяк", MessageBoxButtons.OK, MessageBoxIcon.Error);
                    }
                }
            }
            //Получение информации о заводе
            labelCurrent.BeginInvoke((MethodInvoker)(() => labelCurrent.Text = "Информация: получение информации о заводе"));
            strConnection = @"Provider=Microsoft.JET.Oledb.4.0;Data Source=" + Properties.Settings.Default.OtraslPath + "/SPRXZ;Extended Properties=dBASE IV;User ID=;Password=;";
            using (_connection = new OleDbConnection(strConnection))
            {
                _connection.Open();
                using (OleDbCommand cmd = _connection.CreateCommand())
                {
                    foreach (RashGodPlanIsp isp in planIspList)
                    {
                        cmd.CommandText = "SELECT KODOTR, NZAV1, NZAV2, NZAV3, INN FROM SPRXZ.DBF WHERE KODOTR='" + isp.kodotr + "'";
                        using (OleDbDataReader reader = cmd.ExecuteReader())
                        {
                            while (reader.Read())
                            {
                                isp.nzav = Convert.ToString(reader["NZAV1"]) + Convert.ToString(reader["NZAV2"]) + Convert.ToString(reader["NZAV3"]);
                                isp.inn = Convert.ToString(reader["INN"]);
                            }
                        }
                    }
                }
            }
            //Получение информации о виде
            labelCurrent.BeginInvoke((MethodInvoker)(() => labelCurrent.Text = "Информация: получение информации о виде"));
            strConnection = @"Provider=Microsoft.JET.Oledb.4.0;Data Source=" + Properties.Settings.Default.OtraslPath + "/SPRAV;Extended Properties=dBASE IV;User ID=;Password=;";
            using (_connection = new OleDbConnection(strConnection))
            {
                _connection.Open();
                using (OleDbCommand cmd = _connection.CreateCommand())
                {
                    foreach (RashGodPlanIsp isp in planIspList)
                    {
                        cmd.CommandText = "SELECT * FROM SPVID.DBF WHERE SHVID='" + isp.shvid + "'";
                        using (OleDbDataReader reader = cmd.ExecuteReader())
                        {
                            while (reader.Read())
                            {
                                isp.nvid = Convert.ToString(reader["NVID1"]) + " " + Convert.ToString(reader["NVID2"]);
                            }
                        }
                    }
                }
            }
            //Получение информации о полигоне
            labelCurrent.BeginInvoke((MethodInvoker)(() => labelCurrent.Text = "Информация: получение информации о полигоне"));
            try
            {
                strConnection = @"Provider=Microsoft.JET.Oledb.4.0;Data Source=" + Properties.Settings.Default.OtraslPath + "/SPRAV;Extended Properties=dBASE IV;User ID=;Password=;";
                using (_connection = new OleDbConnection(strConnection))
                {
                    _connection.Open();
                    using (OleDbCommand cmd = _connection.CreateCommand())
                    {
                        foreach (RashGodPlanIsp isp in planIspList)
                        {
                            cmd.CommandText = "SELECT * FROM NPOL1.DBF WHERE POL='" + isp.pol + "'";
                            using (OleDbDataReader reader = cmd.ExecuteReader())
                            {
                                while (reader.Read())
                                {
                                    isp.npol = Convert.ToString(reader["NPOL"]);
                                }
                            }
                        }
                    }
                }
            }
            catch (Exception ex)
            {
                MessageBox.Show("Что-то с полигонами!");
            }
            //Получение информации о МЧ
            labelCurrent.BeginInvoke((MethodInvoker)(() => labelCurrent.Text = "Информация: получение информации о МЧ"));
            try
            {
                strConnection = @"Provider=Microsoft.JET.Oledb.4.0;Data Source=" + Properties.Settings.Default.OtraslPath + "/SPRAV;Extended Properties=dBASE IV;User ID=;Password=;";
                using (_connection = new OleDbConnection(strConnection))
                {
                    _connection.Open();
                    using (OleDbCommand cmd = _connection.CreateCommand())
                    {
                        foreach (RashGodPlanIsp isp in planIspList)
                        {
                            if (isp.shsis != null)
                                if (isp.shsis != "")
                                {
                                    cmd.CommandText = "SELECT * FROM SPSIS.DBF WHERE SHSIS='" + isp.shsis + "'";
                                    using (OleDbDataReader reader = cmd.ExecuteReader())
                                    {
                                        while (reader.Read())
                                        {
                                            isp.nsis = Convert.ToString(reader["NSIS"]);
                                        }
                                    }
                                }
                            if (isp.shsis2 != null)
                                if (isp.shsis2 != "")
                                {
                                    cmd.CommandText = "SELECT * FROM SPSIS.DBF WHERE SHSIS='" + isp.shsis2 + "'";
                                    using (OleDbDataReader reader = cmd.ExecuteReader())
                                    {
                                        while (reader.Read())
                                        {
                                            isp.nsis2 = Convert.ToString(reader["NSIS"]);
                                        }
                                    }
                                }
                            if (isp.shsis3 != null)
                                if (isp.shsis3 != "")
                                {
                                    cmd.CommandText = "SELECT * FROM SPSIS.DBF WHERE SHSIS='" + isp.shsis3 + "'";
                                    using (OleDbDataReader reader = cmd.ExecuteReader())
                                    {
                                        while (reader.Read())
                                        {
                                            isp.nsis3 = Convert.ToString(reader["NSIS"]);
                                        }
                                    }
                                }
                        }
                    }
                }
            }
            catch (Exception ex)
            {
                MessageBox.Show("Что-то с МЧ!!!");
            }

            //Получение информации о ЦЕНАХ
            labelCurrent.BeginInvoke((MethodInvoker)(() => labelCurrent.Text = "Информация: получение информации о ценах"));
            try
            {
                strConnection = @"Provider=Microsoft.JET.Oledb.4.0;Data Source=" + Properties.Settings.Default.OtraslPath + "/RAHGOD/CEN;Extended Properties=dBASE IV;User ID=;Password=;";
                using (_connection = new OleDbConnection(strConnection))
                {
                    _connection.Open();
                    using (OleDbCommand cmd = _connection.CreateCommand())
                    {
                        foreach (RashGodPlanIsp isp in planIspList)
                        {
                            cmd.CommandText = "SELECT * FROM CENVI.DBF WHERE POL='" + isp.pol + "' AND KODEL='" + isp.kodel + "' AND SHVID='" + isp.shvid + "'";
                            using (OleDbDataReader reader = cmd.ExecuteReader())
                            {
                                while (reader.Read())
                                {
                                    isp.cena = Convert.ToString(reader["CENA"]);
                                }
                            }
                        }
                    }
                }
            }
            catch (Exception ex)
            {
                MessageBox.Show("Что-то с ЦЕНАМИ!!!");
            }
            return planIspList;
        }

        private Dictionary<string, string> GetPolRashGodPlanIsp(List<RashGodPlanIsp> planIspList)
        {
            Dictionary<string, string> prList = new Dictionary<string, string>();
            foreach (RashGodPlanIsp isp in planIspList)
            {
                if (!prList.ContainsKey(isp.pol))
                    prList.Add(isp.pol, isp.npol);
            }
            return prList;
        }

        //Сводный план испытаний Результ года
        private void PrintRashGodPlanIsp(List<RashGodPlanIsp> planIspList, string yearPrintResult, string uchNumber, string countEkz)
        {
            label3.BeginInvoke((MethodInvoker)(() => this.label3.Text = "Информация: подготовка к печати"));
            //Сортируем план испытаний
            var sortedList = planIspList.OrderBy(a => a.kodotr).ThenBy(b => b.kodel).ThenBy(c => c.shvid);
            try
            {
                //Создаем документ
                Word.Application oWord = new Word.Application();
                oWord.Visible = checkBox4.Checked;
                /*Object template = Type.Missing;
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
                range.Text = "План испытаний";
                range.ParagraphFormat.Alignment = Word.WdParagraphAlignment.wdAlignParagraphCenter;
                wordDocument.Paragraphs.Add();
                range = wordDocument.Paragraphs[2].Range;
                range.Text = "по состоянию на " + DateTime.Now.ToShortDateString() + " г.";
                range.ParagraphFormat.Alignment = Word.WdParagraphAlignment.wdAlignParagraphRight;*/

                string path = Directory.GetCurrentDirectory() + templateFileNameWordAllPlanIspResult;
                Word.Document wordDocument = oWord.Documents.Open(path);

                //Поиск и замена текста
                functionReplaceInText(wordDocument, oWord, "{uchNumber}", uchNumber);
                functionReplaceInText(wordDocument, oWord, "{year}", yearPrintResult);

                //Добавление в файл таблицы
                label3.BeginInvoke((MethodInvoker)(() => this.label3.Text = "Информация: создание шапки таблицы"));
                wordDocument.Paragraphs.Add();
                Object autoFitBehavior = Word.WdAutoFitBehavior.wdAutoFitWindow;
                var range = wordDocument.Paragraphs[wordDocument.Paragraphs.Count - 1].Range;
                wordDocument.Tables.Add(range, 1, 7, autoFitBehavior);
                Word.Table oTable = wordDocument.Tables[1];
                string[] nameHeaderTable = { "№\nп/п", "Шифр,\nнаименование\nэлемента", "Шифр,\nнаименование\nзавода", "Шифр,\nнаименование\nвида испытания", "Шифр,\nнаименование\nсистемы", "Шифр,\nнаименование\nполигона", "Количество изделий(выстрелов)", "Год", "1 кв", "2 кв", "3 кв", "4 кв" };
                for (int i = 0; i < oTable.Columns.Count; i++)
                {
                    Word.Range wordCellRange = oTable.Cell(1, i + 1).Range;
                    wordCellRange.ParagraphFormat.Alignment = Word.WdParagraphAlignment.wdAlignParagraphCenter;
                    wordCellRange.Text = nameHeaderTable[i];
                    wordCellRange.Font.Name = "Times New Roman";
                }
                oTable.Rows.First.HeadingFormat = -1;
                oTable.Cell(1, 1).Width = 35;
                oTable.Cell(1, 2).Width = 120;
                oTable.Cell(1, 3).Width = 100;
                oTable.Cell(1, 4).Width = 100;
                oTable.Cell(1, 5).Width = 100;
                oTable.Cell(1, 6).Width = 50;
                oTable.Cell(1, 7).Width = 270;
                oTable.Cell(1, 7).Split(2, 1);
                oTable.Cell(2, 7).Split(1, 5);
                for (int i = 7; i < 12; i++)
                {
                    Word.Range wordCellRange = oTable.Cell(2, i).Range;
                    wordCellRange.ParagraphFormat.Alignment = Word.WdParagraphAlignment.wdAlignParagraphCenter;
                    wordCellRange.Text = nameHeaderTable[i];
                    wordCellRange.Rows.HeadingFormat = -1;
                }
                //Вывод
                int posRows = 3;
                foreach (RashGodPlanIsp isp in sortedList)
                {
                    label3.BeginInvoke((MethodInvoker)(() => this.label3.Text = "Информация: печать элемента " + (posRows-2) + " из " + sortedList.Count()));
                    oTable.Rows.Add();
                    //Порядковый номер
                    Word.Range wordCellRange = oTable.Cell(posRows, 1).Range;
                    wordCellRange.Rows.HeadingFormat = 0;
                    wordCellRange.Rows.AllowBreakAcrossPages = 0;
                    wordCellRange.Text = "0\n0";
                    //Шифр, наименование элемента
                    wordCellRange = oTable.Cell(posRows, 2).Range;
                    wordCellRange.Rows.HeadingFormat = 0;
                    wordCellRange.Text = Convert.ToString(isp.kodel) + "\n" + isp.snhert + "\n" + isp.snindiz + "\n" + isp.snnaim;
                    wordCellRange.ParagraphFormat.Alignment = Word.WdParagraphAlignment.wdAlignParagraphLeft;
                    //Шифр, наименование завода
                    wordCellRange = oTable.Cell(posRows, 3).Range;
                    wordCellRange.Rows.HeadingFormat = 0;
                    wordCellRange.Text = Convert.ToString(isp.kodotr) + "\n" + isp.nzav;
                    //Шифр, наименование вида испытания
                    wordCellRange = oTable.Cell(posRows, 4).Range;
                    wordCellRange.Rows.HeadingFormat = 0;
                    wordCellRange.Text = Convert.ToString(isp.shvid) + "\n" + isp.nvid;
                    //Шифр, наименование системы
                    wordCellRange = oTable.Cell(posRows, 5).Range;
                    wordCellRange.Rows.HeadingFormat = 0;
                    if (isp.shsis != null && isp.shsis != "")
                        wordCellRange.Text = Convert.ToString(isp.shsis) + "\n" + isp.nsis;
                    else if (isp.shsis2 != null && isp.shsis2 != "")
                        wordCellRange.Text = Convert.ToString(isp.shsis2) + "\n" + isp.nsis2;
                    else if (isp.shsis3 != null && isp.shsis3 != "")
                        wordCellRange.Text = Convert.ToString(isp.shsis3) + "\n" + isp.nsis3;
                    //Полигон
                    wordCellRange = oTable.Cell(posRows, 6).Range;
                    wordCellRange.Rows.HeadingFormat = 0;
                    wordCellRange.Text = Convert.ToString(isp.pol) + "\n" + Convert.ToString(isp.npol);
                    //Количество испытаний
                    double cena = 0;
                    if (isp.cena != null && isp.cena != "")
                        cena = Convert.ToDouble(isp.cena);
                    double countIsp = 0;
                    if (isp.qolg != null && isp.qolg != "")
                        countIsp = Convert.ToDouble(isp.qolg);
                    wordCellRange = oTable.Cell(posRows, 7).Range;
                    wordCellRange.Rows.HeadingFormat = 0;
                    if(cena != 0 && countIsp != 0)
                        wordCellRange.Text = Convert.ToString(countIsp) + "\n" + Convert.ToString(countIsp * cena);
                    else
                        wordCellRange.Text = Convert.ToString(isp.qolg);

                    countIsp = 0;
                    if (isp.qol1 != null && isp.qol1 != "")
                        countIsp = Convert.ToDouble(isp.qol1);
                    wordCellRange = oTable.Cell(posRows, 8).Range;
                    wordCellRange.Rows.HeadingFormat = 0;
                    if (cena != 0 && countIsp != 0)
                        wordCellRange.Text = Convert.ToString(countIsp) + "\n" + Convert.ToString(countIsp * cena);
                    else
                        wordCellRange.Text = Convert.ToString(isp.qol1);

                    countIsp = 0;
                    if (isp.qol2 != null && isp.qol2 != "")
                        countIsp = Convert.ToDouble(isp.qol2);
                    wordCellRange = oTable.Cell(posRows, 9).Range;
                    wordCellRange.Rows.HeadingFormat = 0;
                    if (cena != 0 && countIsp != 0)
                        wordCellRange.Text = Convert.ToString(countIsp) + "\n" + Convert.ToString(countIsp * cena);
                    else
                        wordCellRange.Text = Convert.ToString(isp.qol2);

                    countIsp = 0;
                    if (isp.qol3 != null && isp.qol3 != "")
                        countIsp = Convert.ToDouble(isp.qol3);
                    wordCellRange = oTable.Cell(posRows, 10).Range;
                    wordCellRange.Rows.HeadingFormat = 0;
                    if (cena != 0 && countIsp != 0)
                        wordCellRange.Text = Convert.ToString(countIsp) + "\n" + Convert.ToString(countIsp * cena);
                    else
                        wordCellRange.Text = Convert.ToString(isp.qol3);

                    countIsp = 0;
                    if (isp.qol4 != null && isp.qol4 != "")
                        countIsp = Convert.ToDouble(isp.qol4);
                    wordCellRange = oTable.Cell(posRows, 11).Range;
                    wordCellRange.Rows.HeadingFormat = 0;
                    if (cena != 0 && countIsp != 0)
                        wordCellRange.Text = Convert.ToString(countIsp) + "\n" + Convert.ToString(countIsp * cena);
                    else
                        wordCellRange.Text = Convert.ToString(isp.qol4);
                    posRows++;
                }
                //Создание экземпляров
                label3.BeginInvoke((MethodInvoker)(() => this.label3.Text = "Информация: создание экземпляров"));
                wordDocument.Range().Copy();
                var rangeAllDocumentEkz = wordDocument.Content;
                rangeAllDocumentEkz.Find.ClearFormatting();
                //for (int j = 0; j < planIspList.Count; j++)
                {
                    functionReplaceInText(wordDocument, oWord, "{numberEkz}", "1");
                }
                for (int i = 0; i < Convert.ToInt32(countEkz) - 1; i++)
                {
                    //В конец документа
                    object what = Word.WdGoToItem.wdGoToLine;
                    object which = Word.WdGoToDirection.wdGoToLast;
                    Word.Range endRange = wordDocument.GoTo(ref what, ref which);
                    //Создаем разрыв РАЗДЕЛА (не страниц)
                    endRange.InsertBreak(Word.WdBreakType.wdSectionBreakNextPage);
                    //Вставляем
                    endRange.Paste();
                    rangeAllDocumentEkz = wordDocument.Content;
                    rangeAllDocumentEkz.Find.ClearFormatting();
                    rangeAllDocumentEkz.Find.Execute(FindText: "{numberEkz}", ReplaceWith: i + 2);
                }
                //Очистка буфера
                Clipboard.Clear();
                //Сохранение
                label3.BeginInvoke((MethodInvoker)(() => this.label3.Text = "Информация: сохранение"));
                DateTime dateNowFileName = DateTime.Now;
                string strSaveName = Convert.ToString(dateNowFileName);
                strSaveName = strSaveName.Replace(':', '-');
                if (!Directory.Exists(Properties.Settings.Default.PathForReports))
                    Directory.CreateDirectory(Directory.GetCurrentDirectory() + "/reports");
                wordDocument.SaveAs(Properties.Settings.Default.PathForReports + "/План испытаний" + " " + strSaveName + ".docx");
                wordDocument.Close();
                oWord.Quit();
                label3.BeginInvoke((MethodInvoker)(() => this.label3.Text = "Информация: сводный план сохранен!"));
                MessageBox.Show("Сводный план создан!");
            }
            catch (Exception ex)
            {
                MessageBox.Show("error: " + ex.Message);
            }
        }

        //Полигонный план испытаний расчётного года
        private void PrintRashGodPlanIsp(List<RashGodPlanIsp> planIspList, string yearPrintResult, string uchNumber, string countEkz, string poligonNumber, string poligonName)
        {
            label3.BeginInvoke((MethodInvoker)(() => this.label3.Text = "Информация: подготовка к печати плана на полигон"));
            //Сортируем план испытаний
            var sortedList = planIspList.OrderBy(a => a.kodotr).ThenBy(b => b.kodel).ThenBy(c => c.shvid);
            try
            {
                //Создаем документ
                Word.Application oWord = new Word.Application();
                oWord.Visible = checkBox4.Checked;

                string path = Directory.GetCurrentDirectory() + templateFileNameWordPlanIspResult;
                Word.Document wordDocument = oWord.Documents.Open(path);

                //Поиск и замена текста
                functionReplaceInText(wordDocument, oWord, "{uchNumber}", uchNumber);
                functionReplaceInText(wordDocument, oWord, "{year}", yearPrintResult);
                functionReplaceInText(wordDocument, oWord, "{polNumber}", poligonNumber);
                functionReplaceInText(wordDocument, oWord, "{polName}", poligonName);

                //Добавление в файл таблицы
                wordDocument.Paragraphs.Add();
                Object autoFitBehavior = Word.WdAutoFitBehavior.wdAutoFitWindow;
                var range = wordDocument.Paragraphs[wordDocument.Paragraphs.Count - 1].Range;
                wordDocument.Tables.Add(range, 1, 7, autoFitBehavior);
                Word.Table oTable = wordDocument.Tables[1];
                string[] nameHeaderTable = { "№\nп/п", "Шифр,\nнаименование\nэлемента", "Шифр,\nнаименование\nзавода", "Шифр,\nнаименование\nвида испытания", "Шифр,\nнаименование\nсистемы", "Цена,\nт.р.", "Количество изделий(выстрелов)/стоимость, т.р.", "Год", "1 кв", "2 кв", "3 кв", "4 кв" };
                for (int i = 0; i < oTable.Columns.Count; i++)
                {
                    Word.Range wordCellRange = oTable.Cell(1, i + 1).Range;
                    wordCellRange.ParagraphFormat.Alignment = Word.WdParagraphAlignment.wdAlignParagraphCenter;
                    wordCellRange.Text = nameHeaderTable[i];
                    //wordCellRange.Rows.HeadingFormat = -1;
                    wordCellRange.Font.Name = "Times New Roman";
                }
                oTable.Rows.First.HeadingFormat = -1;
                oTable.Cell(1, 1).Width = 35;
                oTable.Cell(1, 2).Width = 150;
                oTable.Cell(1, 3).Width = 100;
                oTable.Cell(1, 4).Width = 100;
                oTable.Cell(1, 5).Width = 100;
                oTable.Cell(1, 6).Width = 50;
                oTable.Cell(1, 7).Width = 270;
                oTable.Cell(1, 7).Split(2, 1);
                oTable.Cell(2, 7).Split(1, 5);
                for (int i = 7; i < 12; i++)
                {
                    Word.Range wordCellRange = oTable.Cell(2, i).Range;
                    wordCellRange.ParagraphFormat.Alignment = Word.WdParagraphAlignment.wdAlignParagraphCenter;
                    wordCellRange.Text = nameHeaderTable[i];
                    wordCellRange.Rows.HeadingFormat = -1;
                }
                //Вывод
                int posRows = 3;
                foreach (RashGodPlanIsp isp in sortedList)
                {
                    label3.BeginInvoke((MethodInvoker)(() => this.label3.Text = "Информация: печать элемента " + (posRows - 2) + " из " + sortedList.Count()));
                    if (isp.pol == poligonNumber)
                    {
                        oTable.Rows.Add();
                        //Порядковый номер
                        Word.Range wordCellRange = oTable.Cell(posRows, 1).Range;
                        wordCellRange.Rows.HeadingFormat = 0;
                        wordCellRange.Rows.AllowBreakAcrossPages = 0;
                        wordCellRange.Text = "0\n0";
                        //Шифр, наименование элемента
                        wordCellRange = oTable.Cell(posRows, 2).Range;
                        wordCellRange.Rows.HeadingFormat = 0;
                        wordCellRange.Text = Convert.ToString(isp.kodel) + "\n" + isp.snhert + "\n" + isp.snindiz + "\n" + isp.snnaim;
                        wordCellRange.ParagraphFormat.Alignment = Word.WdParagraphAlignment.wdAlignParagraphLeft;
                        //Шифр, наименование завода
                        wordCellRange = oTable.Cell(posRows, 3).Range;
                        wordCellRange.Rows.HeadingFormat = 0;
                        wordCellRange.Text = Convert.ToString(isp.kodotr) + "\n" + isp.nzav;
                        //Шифр, наименование вида испытания
                        wordCellRange = oTable.Cell(posRows, 4).Range;
                        wordCellRange.Rows.HeadingFormat = 0;
                        wordCellRange.Text = Convert.ToString(isp.shvid) + "\n" + isp.nvid;
                        //Шифр, наименование системы
                        wordCellRange = oTable.Cell(posRows, 5).Range;
                        wordCellRange.Rows.HeadingFormat = 0;
                        if (isp.shsis != null && isp.shsis != "")
                            wordCellRange.Text = Convert.ToString(isp.shsis) + "\n" + isp.nsis;
                        else if (isp.shsis2 != null && isp.shsis2 != "")
                            wordCellRange.Text = Convert.ToString(isp.shsis2) + "\n" + isp.nsis2;
                        else if (isp.shsis3 != null && isp.shsis3 != "")
                            wordCellRange.Text = Convert.ToString(isp.shsis3) + "\n" + isp.nsis3;
                        //Цена, т.р.
                        wordCellRange = oTable.Cell(posRows, 6).Range;
                        wordCellRange.Rows.HeadingFormat = 0;
                        wordCellRange.Text = Convert.ToString(isp.cena);
                        //Количество испытаний
                        double cena = 0;
                        if (isp.cena != null && isp.cena != "")
                            cena = Convert.ToDouble(isp.cena);
                        double countIsp = 0;
                        if (isp.qolg != null && isp.qolg != "")
                            countIsp = Convert.ToDouble(isp.qolg);
                        wordCellRange = oTable.Cell(posRows, 7).Range;
                        wordCellRange.Rows.HeadingFormat = 0;
                        if (cena != 0 && countIsp != 0)
                            wordCellRange.Text = Convert.ToString(countIsp) + "\n" + Convert.ToString(countIsp * cena);
                        else
                            wordCellRange.Text = Convert.ToString(isp.qolg);

                        countIsp = 0;
                        if (isp.qol1 != null && isp.qol1 != "")
                            countIsp = Convert.ToDouble(isp.qol1);
                        wordCellRange = oTable.Cell(posRows, 8).Range;
                        wordCellRange.Rows.HeadingFormat = 0;
                        if (cena != 0 && countIsp != 0)
                            wordCellRange.Text = Convert.ToString(countIsp) + "\n" + Convert.ToString(countIsp * cena);
                        else
                            wordCellRange.Text = Convert.ToString(isp.qol1);

                        countIsp = 0;
                        if (isp.qol2 != null && isp.qol2 != "")
                            countIsp = Convert.ToDouble(isp.qol2);
                        wordCellRange = oTable.Cell(posRows, 9).Range;
                        wordCellRange.Rows.HeadingFormat = 0;
                        if (cena != 0 && countIsp != 0)
                            wordCellRange.Text = Convert.ToString(countIsp) + "\n" + Convert.ToString(countIsp * cena);
                        else
                            wordCellRange.Text = Convert.ToString(isp.qol2);

                        countIsp = 0;
                        if (isp.qol3 != null && isp.qol3 != "")
                            countIsp = Convert.ToDouble(isp.qol3);
                        wordCellRange = oTable.Cell(posRows, 10).Range;
                        wordCellRange.Rows.HeadingFormat = 0;
                        if (cena != 0 && countIsp != 0)
                            wordCellRange.Text = Convert.ToString(countIsp) + "\n" + Convert.ToString(countIsp * cena);
                        else
                            wordCellRange.Text = Convert.ToString(isp.qol3);

                        countIsp = 0;
                        if (isp.qol4 != null && isp.qol4 != "")
                            countIsp = Convert.ToDouble(isp.qol4);
                        wordCellRange = oTable.Cell(posRows, 11).Range;
                        wordCellRange.Rows.HeadingFormat = 0;
                        if (cena != 0 && countIsp != 0)
                            wordCellRange.Text = Convert.ToString(countIsp) + "\n" + Convert.ToString(countIsp * cena);
                        else
                            wordCellRange.Text = Convert.ToString(isp.qol4);
                        posRows++;
                    }
                }
                //Создание экземпляров
                label3.BeginInvoke((MethodInvoker)(() => this.label3.Text = "Информация: создание экземпляров"));
                wordDocument.Range().Copy();
                var rangeAllDocumentEkz = wordDocument.Content;
                rangeAllDocumentEkz.Find.ClearFormatting();
                //for (int j = 0; j < planIspList.Count; j++)
                {
                    functionReplaceInText(wordDocument, oWord, "{numberEkz}", "1");
                }
                for (int i = 0; i < Convert.ToInt32(countEkz) - 1; i++)
                {
                    //В конец документа
                    object what = Word.WdGoToItem.wdGoToLine;
                    object which = Word.WdGoToDirection.wdGoToLast;
                    Word.Range endRange = wordDocument.GoTo(ref what, ref which);
                    //Создаем разрыв РАЗДЕЛА (не страниц)
                    endRange.InsertBreak(Word.WdBreakType.wdSectionBreakNextPage);
                    //Вставляем
                    endRange.Paste();
                    rangeAllDocumentEkz = wordDocument.Content;
                    rangeAllDocumentEkz.Find.ClearFormatting();
                    rangeAllDocumentEkz.Find.Execute(FindText: "{numberEkz}", ReplaceWith: i + 2);
                }
                //Очистка буфера
                Clipboard.Clear();
                //Сохранение
                label3.BeginInvoke((MethodInvoker)(() => this.label3.Text = "Информация: сохранение"));
                DateTime dateNowFileName = DateTime.Now;
                string strSaveName = Convert.ToString(dateNowFileName);
                strSaveName = strSaveName.Replace(':', '-');
                if (!Directory.Exists(Properties.Settings.Default.PathForReports))
                    Directory.CreateDirectory(Directory.GetCurrentDirectory() + "/reports");
                wordDocument.SaveAs(Properties.Settings.Default.PathForReports + "/Полигонный план испытаний " + poligonNumber + " " + strSaveName + ".docx");
                wordDocument.Close();
                oWord.Quit();
                label3.BeginInvoke((MethodInvoker)(() => this.label3.Text = "Информация: полигонный план сохранен!"));
                MessageBox.Show("Полигонный план создан!");
            }
            catch (Exception ex)
            {
                MessageBox.Show("error: " + ex.Message);
            }
        }
        #endregion

        //Печать потребности комплектующих элементов
        #region Результ ПОТРЕБНОСТЬ В КЭ
        private void button2_Click(object sender, EventArgs e)
        {
            button2.BackColor = choseColorBTN;
            BlockAllButton();
            if (Properties.Settings.Default.OtraslPath.Length > 0)
            {
                if (Properties.Settings.Default.RashGodFolderPath.Length > 0)
                {
                    FormChoseUchYearCount fcuyc = new FormChoseUchYearCount();
                    fcuyc.ShowDialog();
                    if (fcuyc.isExit)
                    {
                        try
                        {
                            label3.BeginInvoke((MethodInvoker)(() => this.label3.Text = "Информация: подготовка к печати"));
                            Thread thread = new Thread(() =>
                            {
                                List<RashGodKE> keList = new List<RashGodKE>();
                                //Получение плана испытаний
                                keList = GetRashGodKE(keList);
                                //Заполнение потребности в комплектующих
                                keList = FillRashGodKE(keList);
                                //Печать сводного плана испытаний
                                PrintRashGodKE(keList, fcuyc.year, fcuyc.uchNumber, fcuyc.count);

                                //if (DialogResult.Yes == MessageBox.Show("Желаете вывести на печать полигонную потребность в кэ?", "Внимание", MessageBoxButtons.YesNo, MessageBoxIcon.Information))
                                {
                                    //MessageBox.Show("Ещё не готова!");
                                    //Получение списка полигонов из плана испытаний
                                    //List<string> polList = GetPolRashGodKE(keList);
                                    //Печать полигонного плана испытаний
                                    //foreach (string poligon in polList)
                                    {
                                        //PrintRashGodKE(keList, poligon);
                                    }
                                }
                                BlockAllButton(false);
                            });
                            thread.SetApartmentState(ApartmentState.STA);
                            thread.Start();
                        }
                        catch (Exception ex)
                        {
                            BlockAllButton(false);
                            MessageBox.Show("Очередная ошибка\n" + ex.Message + "\n" + ex.Source + "\n" + ex.StackTrace + "\n" + ex.TargetSite, "Ошибка", MessageBoxButtons.OK, MessageBoxIcon.Error);
                        }
                    }
                    else
                        BlockAllButton(false);
                }
                else
                {
                    BlockAllButton(false);
                    MessageBox.Show("Выберите поддиректорию RAHGOD");
                }
            }
            else
            {
                BlockAllButton(false);
                MessageBox.Show("Выберите директорию OTRASL");
            }
        }

        private List<RashGodKE> GetRashGodKE(List<RashGodKE> keList)
        {
            keList.Clear();
            label3.BeginInvoke((MethodInvoker)(() => this.label3.Text = "Информация: получение потребности"));
            string strConnection = @"Provider=Microsoft.JET.Oledb.4.0;Data Source=" + Properties.Settings.Default.OtraslPath + "/RAHGOD/" + Properties.Settings.Default.RashGodFolderPath + "/POTR;Extended Properties=dBASE IV;User ID=;Password=;";
            using (_connection = new OleDbConnection(strConnection))
            {
                _connection.Open();
                using (OleDbCommand cmd = _connection.CreateCommand())
                {
                    cmd.CommandText = "SELECT * FROM POTR.DBF";
                    using (OleDbDataReader reader = cmd.ExecuteReader())
                    {
                        while (reader.Read())
                        {
                            RashGodKE ke = new RashGodKE();
                            ke.kodel = Convert.ToString(reader["KODEL"]);
                            ke.kodeiz = Convert.ToString(reader["KODEIZ"]);
                            ke.pol = Convert.ToString(reader["POL"]);
                            ke.polz = Convert.ToString(reader["POLZ"]);
                            ke.qptg = Convert.ToString(reader["QPTG"]);
                            ke.qpt1 = Convert.ToString(reader["QPT1"]);
                            ke.qpt2 = Convert.ToString(reader["QPT2"]);
                            ke.qpt3 = Convert.ToString(reader["QPT3"]);
                            ke.qpt4 = Convert.ToString(reader["QPT4"]);
                            ke.kodotr = Convert.ToString(reader["KODOTR"]);
                            keList.Add(ke);
                        }
                    }
                }
            }
            return keList;
        }

        private List<RashGodKE> FillRashGodKE(List<RashGodKE> keList)
        {
            label3.BeginInvoke((MethodInvoker)(() => this.label3.Text = "Информация: заполнение потребности"));
            //Получение информации о элементе
            label3.BeginInvoke((MethodInvoker)(() => this.label3.Text = "Информация: получение информации о элементах"));
            string strConnection = @"Provider=Microsoft.JET.Oledb.4.0;Data Source=" + Properties.Settings.Default.OtraslPath + "/SPNAIM;Extended Properties=dBASE IV;User ID=;Password=;";
            using (_connection = new OleDbConnection(strConnection))
            {
                _connection.Open();
                using (OleDbCommand cmd = _connection.CreateCommand())
                {
                    foreach (RashGodKE ke in keList)
                    {
                        if (ke.kodel != null && ke.kodel != "")
                        {
                            cmd.CommandText = "SELECT * FROM SPNAIM.DBF WHERE KODEL='" + ke.kodel + "'";
                            using (OleDbDataReader reader = cmd.ExecuteReader())
                            {
                                while (reader.Read())
                                {
                                    ke.snhert = Convert.ToString(reader["SNHERT"] ?? "");

                                    ke.snindiz = Convert.ToString(reader["SNINDIZ"] ?? "");

                                    ke.snnaim = Convert.ToString(reader["SNNAIM1"] ?? "") + Convert.ToString(reader["SNNAIM2"] ?? "") +
                                        Convert.ToString(reader["SNNAIM3"] ?? "") + Convert.ToString(reader["SNNAIM4"] ?? "") +
                                        Convert.ToString(reader["SNNAIM5"] ?? "") + Convert.ToString(reader["SNNAIM6"] ?? "") +
                                        Convert.ToString(reader["SNNAIM7"] ?? "") + Convert.ToString(reader["SNNAIM8"] ?? "") +
                                        Convert.ToString(reader["SNNAIM9"] ?? "") + Convert.ToString(reader["SNNAIM10"] ?? "");

                                }
                            }
                        }
                        else
                            MessageBox.Show("В потребности элемент без кода!", "Косяк", MessageBoxButtons.OK, MessageBoxIcon.Error);
                    }
                }
            }
            //Получение информации о ед. измерения
            label3.BeginInvoke((MethodInvoker)(() => this.label3.Text = "Информация: получение информации о ед. измерениях"));
            strConnection = @"Provider=Microsoft.JET.Oledb.4.0;Data Source=" + Properties.Settings.Default.OtraslPath + "/SPRAV;Extended Properties=dBASE IV;User ID=;Password=;";
            using (_connection = new OleDbConnection(strConnection))
            {
                _connection.Open();
                using (OleDbCommand cmd = _connection.CreateCommand())
                {
                    foreach (RashGodKE ke in keList)
                    {
                        cmd.CommandText = "SELECT * FROM SPEIZ2 WHERE KODEIZ='" + ke.kodeiz + "'";
                        using (OleDbDataReader reader = cmd.ExecuteReader())
                        {
                            while (reader.Read())
                            {
                                ke.naimeiz = Convert.ToString(reader["NAIMEIZ"]);
                                ke.naik = Convert.ToString(reader["NAIK"]);
                            }
                        }
                    }
                }
            }
            //Получение информации о полигоне
            label3.BeginInvoke((MethodInvoker)(() => this.label3.Text = "Информация: получение информации о полигоне"));
            try
            {
                strConnection = @"Provider=Microsoft.JET.Oledb.4.0;Data Source=" + Properties.Settings.Default.OtraslPath + "/SPRAV;Extended Properties=dBASE IV;User ID=;Password=;";
                using (_connection = new OleDbConnection(strConnection))
                {
                    _connection.Open();
                    using (OleDbCommand cmd = _connection.CreateCommand())
                    {
                        foreach (RashGodKE ke in keList)
                        {
                            cmd.CommandText = "SELECT * FROM NPOL1.DBF WHERE POL='" + ke.pol + "'";
                            using (OleDbDataReader reader = cmd.ExecuteReader())
                            {
                                while (reader.Read())
                                {
                                    ke.npol = Convert.ToString(reader["NPOL"]);
                                }
                            }
                        }
                    }
                }
            }
            catch (Exception ex)
            {
                MessageBox.Show("Что-то с полигонами!");
            }
            //Получение информации о заводе
            label3.BeginInvoke((MethodInvoker)(() => this.label3.Text = "Информация: получение информации о заводе"));
            strConnection = @"Provider=Microsoft.JET.Oledb.4.0;Data Source=" + Properties.Settings.Default.OtraslPath + "/SPRXZ;Extended Properties=dBASE IV;User ID=;Password=;";
            using (_connection = new OleDbConnection(strConnection))
            {
                _connection.Open();
                using (OleDbCommand cmd = _connection.CreateCommand())
                {
                    foreach (RashGodKE ke in keList)
                    {
                        cmd.CommandText = "SELECT KODOTR, NZAV1, NZAV2, NZAV3, INN FROM SPRXZ.DBF WHERE KODOTR='" + ke.kodotr + "'";
                        using (OleDbDataReader reader = cmd.ExecuteReader())
                        {
                            while (reader.Read())
                            {
                                ke.nzav = Convert.ToString(reader["NZAV1"]) + Convert.ToString(reader["NZAV2"]) + Convert.ToString(reader["NZAV3"]);
                                ke.inn = Convert.ToString(reader["INN"]);
                            }
                        }
                    }
                }
            }
            return keList;
        }

        private void PrintRashGodKE(List<RashGodKE> keList, string year, string uchNumber, string countEkz)
        {
            label3.BeginInvoke((MethodInvoker)(() => this.label3.Text = "Информация: печатаем сводную потребность"));
            //Сортируем план испытаний
            var sortedList = keList.OrderBy(a => a.kodel).ThenBy(b => b.kodotr);
            try
            {
                //Создаем документ
                Word.Application oWord = new Word.Application();
                oWord.Visible = checkBox4.Checked;
                //Открытие документа
                Word.Document wordDocument;
                string path = Directory.GetCurrentDirectory() + templateFileNameWordPotrVKEIspResult;
                wordDocument = oWord.Documents.Open(path);
                //Поиск и замена текста
                functionReplaceInText(wordDocument, oWord, "{uchNumber}", uchNumber);
                functionReplaceInText(wordDocument, oWord, "{year}", year);
                if (keList.Count > 0)
                {
                    functionReplaceInText(wordDocument, oWord, "{polName}", keList[0].npol);
                    functionReplaceInText(wordDocument, oWord, "{polNumber}", keList[0].pol);
                }
                //Добавление в файл таблицы
                Object autoFitBehavior = Word.WdAutoFitBehavior.wdAutoFitWindow;
                //Добавление в файл таблицы
                label3.BeginInvoke((MethodInvoker)(() => this.label3.Text = "Информация: создание таблицы"));
                var range = wordDocument.Paragraphs[wordDocument.Paragraphs.Count - 1].Range;
                wordDocument.Tables.Add(range, 2, 10, autoFitBehavior);
                Word.Table oTable = wordDocument.Tables[1];
                string[] nameHeaderTable = { "№\nп/п", "Код и наименование\nкомплектующего\nэлемента", "Ед. измерения", "Поставщик", "Потребность в КЭ", "Примечание", "год", "1 кв", "2 кв", "3 кв", "4 кв" };
                for (int i = 0; i < 5; i++)
                {
                    Word.Range wordCellRange = oTable.Cell(1, i + 1).Range;
                    wordCellRange.ParagraphFormat.Alignment = Word.WdParagraphAlignment.wdAlignParagraphCenter;
                    wordCellRange.Text = nameHeaderTable[i];
                    wordCellRange.Font.Name = "Times New Roman";
                }
                Word.Range wordCellRangeUn = oTable.Cell(1, 10).Range;
                wordCellRangeUn.Text = nameHeaderTable[5];
                oTable.Rows.First.HeadingFormat = -1;

                oTable.Cell(1, 1).Width = 35;
                oTable.Cell(2, 1).Width = 35;
                oTable.Cell(1, 2).Width = 120;
                oTable.Cell(2, 2).Width = 120;
                oTable.Cell(1, 3).Width = 60;
                oTable.Cell(2, 3).Width = 60;
                oTable.Cell(1, 4).Width = 80;
                oTable.Cell(2, 4).Width = 80;
                oTable.Cell(1, 5).Width = 80;
                oTable.Cell(2, 5).Width = 80;
                oTable.Cell(1, 6).Width = 80;
                oTable.Cell(2, 6).Width = 80;
                oTable.Cell(1, 7).Width = 60;
                oTable.Cell(2, 7).Width = 60;
                oTable.Cell(1, 8).Width = 60;
                oTable.Cell(2, 8).Width = 60;
                oTable.Cell(1, 9).Width = 60;
                oTable.Cell(2, 9).Width = 60;
                oTable.Cell(1, 10).Width = 80;
                oTable.Cell(2, 10).Width = 80;

                oTable.Cell(1, 10).Merge(oTable.Cell(2, 10));
                oTable.Cell(1, 5).Merge(oTable.Cell(1, 9));
                oTable.Cell(1, 1).Merge(oTable.Cell(2, 1));
                oTable.Cell(1, 2).Merge(oTable.Cell(2, 2));
                oTable.Cell(1, 3).Merge(oTable.Cell(2, 3));
                oTable.Cell(1, 4).Merge(oTable.Cell(2, 4));

                for (int i = 5; i < 10; i++)
                {
                    Word.Range wordCellRange = oTable.Cell(2, i).Range;
                    wordCellRange.ParagraphFormat.Alignment = Word.WdParagraphAlignment.wdAlignParagraphCenter;
                    wordCellRange.Text = nameHeaderTable[i + 1];
                    wordCellRange.Rows.HeadingFormat = -1;
                }
                //oTable.Rows.First.HeadingFormat = -1;
                //Вывод
                int posRows = 3;
                foreach (RashGodKE ke in sortedList)
                {
                    label3.BeginInvoke((MethodInvoker)(() => this.label3.Text = "Информация: печать элемента " + (posRows-2) + " из " + sortedList.Count()));
                    oTable.Rows.Add();
                    Word.Range wordCellRange = oTable.Cell(posRows, 1).Range;
                    wordCellRange.Rows.AllowBreakAcrossPages = 0;
                    wordCellRange.Text = Convert.ToString(posRows - 2);
                    wordCellRange = oTable.Cell(posRows, 2).Range;
                    wordCellRange.Text = Convert.ToString(ke.kodel) + "\n" + ke.snhert + "\n" + ke.snindiz + "\n" + ke.snnaim;
                    wordCellRange = oTable.Cell(posRows, 3).Range;
                    wordCellRange.Text = Convert.ToString(ke.kodeiz) + " " + Convert.ToString(ke.naik);
                    wordCellRange = oTable.Cell(posRows, 4).Range;
                    wordCellRange.Text = Convert.ToString(ke.kodotr) + "\n" + ke.nzav;
                    wordCellRange = oTable.Cell(posRows, 5).Range;
                    wordCellRange.Text = Convert.ToString(ke.qptg);
                    wordCellRange = oTable.Cell(posRows, 6).Range;
                    wordCellRange.Text = Convert.ToString(ke.qpt1);
                    wordCellRange = oTable.Cell(posRows, 7).Range;
                    wordCellRange.Text = Convert.ToString(ke.qpt2);
                    wordCellRange = oTable.Cell(posRows, 8).Range;
                    wordCellRange.Text = Convert.ToString(ke.qpt3);
                    wordCellRange = oTable.Cell(posRows, 9).Range;
                    wordCellRange.Text = Convert.ToString(ke.qpt4);
                    posRows++;
                }
                //Создание экземпляров
                label3.BeginInvoke((MethodInvoker)(() => this.label3.Text = "Информация: создание экземпляров"));
                wordDocument.Range().Copy();
                var rangeAllDocumentEkz = wordDocument.Content;
                rangeAllDocumentEkz.Find.ClearFormatting();
                //for (int j = 0; j < planIspList.Count; j++)
                {
                    functionReplaceInText(wordDocument, oWord, "{numberEkz}", "1");
                }
                for (int i = 0; i < Convert.ToInt32(countEkz) - 1; i++)
                {
                    //В конец документа
                    object what = Word.WdGoToItem.wdGoToLine;
                    object which = Word.WdGoToDirection.wdGoToLast;
                    Word.Range endRange = wordDocument.GoTo(ref what, ref which);
                    //Создаем разрыв РАЗДЕЛА (не страниц)
                    endRange.InsertBreak(Word.WdBreakType.wdSectionBreakNextPage);
                    //Вставляем
                    endRange.Paste();
                    rangeAllDocumentEkz = wordDocument.Content;
                    rangeAllDocumentEkz.Find.ClearFormatting();
                    rangeAllDocumentEkz.Find.Execute(FindText: "{numberEkz}", ReplaceWith: i + 2);
                }
                //Очистка буфера
                Clipboard.Clear();
                //Сохранение
                label3.BeginInvoke((MethodInvoker)(() => this.label3.Text = "Информация: сохранение"));
                DateTime dateNowFileName = DateTime.Now;
                string strSaveName = Convert.ToString(dateNowFileName);
                strSaveName = strSaveName.Replace(':', '-');
                if (!Directory.Exists(Properties.Settings.Default.PathForReports))
                    Directory.CreateDirectory(Directory.GetCurrentDirectory() + "/reports");
                wordDocument.SaveAs(Properties.Settings.Default.PathForReports + "/Потребность в КЭ" + " " + strSaveName + ".docx");
                wordDocument.Close();
                oWord.Quit();
                label3.BeginInvoke((MethodInvoker)(() => this.label3.Text = "Информация: потребность в КЭ сохранена"));
                MessageBox.Show("Потребность в КЭ создана!");
            }
            catch (Exception ex)
            {
                MessageBox.Show("error: " + ex.Message);
            }
        }
        #endregion

        //Печать справка-обоснование
        #region Результ СПРАВКА-ОБОСНОВАНИЕ

        private void button3_Click(object sender, EventArgs e)
        {
            button3.BackColor = choseColorBTN;
            BlockAllButton();
            if (Properties.Settings.Default.OtraslPath.Length > 0)
            {
                if (Properties.Settings.Default.RashGodFolderPath.Length > 0)
                {
                    FormChoseUchYearCount fcuyc = new FormChoseUchYearCount();
                    fcuyc.ShowDialog();
                    if (fcuyc.isExit)
                    {
                        try
                        {
                            label3.BeginInvoke((MethodInvoker)(() => this.label3.Text = "Информация: подготовка к печати"));
                            Thread thread = new Thread(() =>
                            {
                                List<RashGodSO> soList = new List<RashGodSO>();
                                //Получение справки-обоснования
                                soList = GetRashGodSO(soList);
                                //Заполнение справки-обоснование
                                soList = FillRashGodSO(soList);
                                //Печать сводного плана испытаний
                                PrintRashGodSO(soList, fcuyc.year, fcuyc.uchNumber, fcuyc.count);

                                //if (DialogResult.Yes == MessageBox.Show("Желаете вывести на печать полигонную справку-обоснование?", "Внимание", MessageBoxButtons.YesNo, MessageBoxIcon.Information))
                                {
                                    //Получение списка полигонов из плана испытаний
                                    //List<string> polList = GetPolRashGodKE(keList);
                                    //Печать полигонного плана испытаний
                                    //foreach (string poligon in polList)
                                    {
                                        //PrintRashGodKE(keList, poligon);
                                    }
                                }
                                BlockAllButton(false);
                            });
                            thread.SetApartmentState(ApartmentState.STA);
                            thread.Start();
                        }
                        catch (Exception ex)
                        {
                            BlockAllButton(false);
                            MessageBox.Show("Очередная ошибка\n" + ex.Message + "\n" + ex.Source + "\n" + ex.StackTrace + "\n" + ex.TargetSite, "Ошибка", MessageBoxButtons.OK, MessageBoxIcon.Error);
                        }
                    }
                    else
                        BlockAllButton(false);
                }
                else
                {
                    BlockAllButton(false);
                    MessageBox.Show("Выберите поддиректорию RAHGOD");
                }
            }
            else
            {
                BlockAllButton(false);
                MessageBox.Show("Выберите директорию OTRASL");
            }
        }

        private List<RashGodSO> GetRashGodSO(List<RashGodSO> soList)
        {
            soList.Clear();
            label3.BeginInvoke((MethodInvoker)(() => this.label3.Text = "Информация: получение справки-обоснование"));
            string strConnection = @"Provider=Microsoft.JET.Oledb.4.0;Data Source=" + Properties.Settings.Default.OtraslPath + "/RAHGOD/" + Properties.Settings.Default.RashGodFolderPath + "/POTR/PTRAH;Extended Properties=dBASE IV;User ID=;Password=;";
            using (_connection = new OleDbConnection(strConnection))
            {
                _connection.Open();
                using (OleDbCommand cmd = _connection.CreateCommand())
                {
                    cmd.CommandText = "SELECT * FROM SPOB2.DBF";
                    using (OleDbDataReader reader = cmd.ExecuteReader())
                    {
                        while (reader.Read())
                        {
                            RashGodSO so = new RashGodSO();
                            so.kodel = Convert.ToString(reader["KODEL"]);
                            so.kodil = Convert.ToString(reader["KODIL"]);
                            so.kodotr = Convert.ToString(reader["KODOTR"]);
                            so.pol = Convert.ToString(reader["POL"]);
                            so.polz = Convert.ToString(reader["POLZ"]);
                            so.vispg = Convert.ToString(reader["VISPG"]);
                            so.visp1 = Convert.ToString(reader["VISP1"]);
                            so.visp2 = Convert.ToString(reader["VISP2"]);
                            so.visp3 = Convert.ToString(reader["VISP3"]);
                            so.visp4 = Convert.ToString(reader["VISP4"]);
                            so.qptg1 = Convert.ToString(reader["QPTG1"]);
                            so.qptg = Convert.ToString(reader["QPTG"]);
                            so.qpt1 = Convert.ToString(reader["QPT1"]);
                            so.qpt2 = Convert.ToString(reader["QPT2"]);
                            so.qpt3 = Convert.ToString(reader["QPT3"]);
                            so.qpt4 = Convert.ToString(reader["QPT4"]);
                            so.kodpost = Convert.ToString(reader["POST"]);
                            so.ost = Convert.ToString(reader["OST"]);
                            so.kodeiz = Convert.ToString(reader["KODEIZ"]);
                            soList.Add(so);
                        }
                    }
                }
            }
            return soList;
        }

        private List<RashGodSO> FillRashGodSO(List<RashGodSO> soList)
        {
            label3.BeginInvoke((MethodInvoker)(() => this.label3.Text = "Информация: заполняем справку-обоснование"));
            //Получение информации о элементах
            label3.BeginInvoke((MethodInvoker)(() => this.label3.Text = "Информация: получение информации о элементах"));
            string strConnection = @"Provider=Microsoft.JET.Oledb.4.0;Data Source=" + Properties.Settings.Default.OtraslPath + "/SPNAIM;Extended Properties=dBASE IV;User ID=;Password=;";
            using (_connection = new OleDbConnection(strConnection))
            {
                _connection.Open();
                using (OleDbCommand cmd = _connection.CreateCommand())
                {
                    foreach (RashGodSO so in soList)
                    {
                        if (so.kodel != null && so.kodel != "")
                        {
                            cmd.CommandText = "SELECT * FROM SPNAIM.DBF WHERE KODEL='" + so.kodel + "'";
                            using (OleDbDataReader reader = cmd.ExecuteReader())
                            {
                                while (reader.Read())
                                {
                                    so.snhertel = Convert.ToString(reader["SNHERT"] ?? "");

                                    so.snindizel = Convert.ToString(reader["SNINDIZ"] ?? "");

                                    so.snnaimel = Convert.ToString(reader["SNNAIM1"] ?? "") + Convert.ToString(reader["SNNAIM2"] ?? "") +
                                        Convert.ToString(reader["SNNAIM3"] ?? "") + Convert.ToString(reader["SNNAIM4"] ?? "") +
                                        Convert.ToString(reader["SNNAIM5"] ?? "") + Convert.ToString(reader["SNNAIM6"] ?? "") +
                                        Convert.ToString(reader["SNNAIM7"] ?? "") + Convert.ToString(reader["SNNAIM8"] ?? "") +
                                        Convert.ToString(reader["SNNAIM9"] ?? "") + Convert.ToString(reader["SNNAIM10"] ?? "");

                                }
                            }
                        }
                        else
                            MessageBox.Show("В потребности элемент без кода!", "Косяк", MessageBoxButtons.OK, MessageBoxIcon.Error);
                    }
                }
            }
            label3.BeginInvoke((MethodInvoker)(() => this.label3.Text = "Информация: получение информации о элементах комплектации"));
            strConnection = @"Provider=Microsoft.JET.Oledb.4.0;Data Source=" + Properties.Settings.Default.OtraslPath + "/SPNAIM;Extended Properties=dBASE IV;User ID=;Password=;";
            using (_connection = new OleDbConnection(strConnection))
            {
                _connection.Open();
                using (OleDbCommand cmd = _connection.CreateCommand())
                {
                    foreach (RashGodSO so in soList)
                    {
                        if (so.kodil != null && so.kodil != "")
                        {
                            cmd.CommandText = "SELECT * FROM SPNAIM.DBF WHERE KODEL='" + so.kodil + "'";
                            using (OleDbDataReader reader = cmd.ExecuteReader())
                            {
                                while (reader.Read())
                                {
                                    so.snhertil = Convert.ToString(reader["SNHERT"] ?? "");

                                    so.snindizil = Convert.ToString(reader["SNINDIZ"] ?? "");

                                    so.snnaimil = Convert.ToString(reader["SNNAIM1"] ?? "") + Convert.ToString(reader["SNNAIM2"] ?? "") +
                                        Convert.ToString(reader["SNNAIM3"] ?? "") + Convert.ToString(reader["SNNAIM4"] ?? "") +
                                        Convert.ToString(reader["SNNAIM5"] ?? "") + Convert.ToString(reader["SNNAIM6"] ?? "") +
                                        Convert.ToString(reader["SNNAIM7"] ?? "") + Convert.ToString(reader["SNNAIM8"] ?? "") +
                                        Convert.ToString(reader["SNNAIM9"] ?? "") + Convert.ToString(reader["SNNAIM10"] ?? "");

                                }
                            }
                        }
                    }
                }
            }
            //Получение информации о ед. измерения
            label3.BeginInvoke((MethodInvoker)(() => this.label3.Text = "Информация: получение информации о ед. измерениях"));
            strConnection = @"Provider=Microsoft.JET.Oledb.4.0;Data Source=" + Properties.Settings.Default.OtraslPath + "/SPRAV;Extended Properties=dBASE IV;User ID=;Password=;";
            using (_connection = new OleDbConnection(strConnection))
            {
                _connection.Open();
                using (OleDbCommand cmd = _connection.CreateCommand())
                {
                    foreach (RashGodSO so in soList)
                    {
                        cmd.CommandText = "SELECT * FROM SPEIZ2 WHERE KODEIZ='" + so.kodeiz + "'";
                        using (OleDbDataReader reader = cmd.ExecuteReader())
                        {
                            while (reader.Read())
                            {
                                so.naimeiz = Convert.ToString(reader["NAIMEIZ"]);
                                so.naik = Convert.ToString(reader["NAIK"]);
                            }
                        }
                    }
                }
            }
            //Получение информации о заводе
            label3.BeginInvoke((MethodInvoker)(() => this.label3.Text = "Информация: получение информации о заводе потребителя"));
            strConnection = @"Provider=Microsoft.JET.Oledb.4.0;Data Source=" + Properties.Settings.Default.OtraslPath + "/SPRXZ;Extended Properties=dBASE IV;User ID=;Password=;";
            using (_connection = new OleDbConnection(strConnection))
            {
                _connection.Open();
                using (OleDbCommand cmd = _connection.CreateCommand())
                {
                    foreach (RashGodSO so in soList)
                    {
                        cmd.CommandText = "SELECT KODOTR, NZAV1, NZAV2, NZAV3, INN FROM SPRXZ.DBF WHERE KODOTR='" + so.kodotr + "'";
                        using (OleDbDataReader reader = cmd.ExecuteReader())
                        {
                            while (reader.Read())
                            {
                                so.nzavotr = Convert.ToString(reader["NZAV1"]) + Convert.ToString(reader["NZAV2"]) + Convert.ToString(reader["NZAV3"]);
                                //so.inn = Convert.ToString(reader["INN"]);
                            }
                        }
                    }
                }
            }
            //Получение информации о заводе поставщиков
            label3.BeginInvoke((MethodInvoker)(() => this.label3.Text = "Информация: получение информации о заводе поставщика"));
            strConnection = @"Provider=Microsoft.JET.Oledb.4.0;Data Source=" + Properties.Settings.Default.OtraslPath + "/SPRXZ;Extended Properties=dBASE IV;User ID=;Password=;";
            using (_connection = new OleDbConnection(strConnection))
            {
                _connection.Open();
                using (OleDbCommand cmd = _connection.CreateCommand())
                {
                    foreach (RashGodSO so in soList)
                    {
                        cmd.CommandText = "SELECT KODOTR, NZAV1, NZAV2, NZAV3, INN FROM SPRXZ.DBF WHERE KODOTR='" + so.kodpost + "'";
                        using (OleDbDataReader reader = cmd.ExecuteReader())
                        {
                            while (reader.Read())
                            {
                                so.nzavpost = Convert.ToString(reader["NZAV1"]) + Convert.ToString(reader["NZAV2"]) + Convert.ToString(reader["NZAV3"]);
                                so.innpost = Convert.ToString(reader["INN"]);
                            }
                        }
                    }
                }
            }
            return soList;
        }

        private void PrintRashGodSO(List<RashGodSO> soList, string year, string uchNumber, string countEkz)
        {
            label3.BeginInvoke((MethodInvoker)(() => this.label3.Text = "Информация: печать справки-обоснование"));
            //Сортируем план испытаний
            var sortedList = soList.OrderBy(a => a.kodel).ThenBy(b => b.kodotr);
            try
            {
                //Создаем документ
                Word.Application oWord = new Word.Application();
                oWord.Visible = checkBox4.Checked;
                //Открытие документа
                Word.Document wordDocument;
                string path = Directory.GetCurrentDirectory() + templateFileNameWordVKEIspResult;
                wordDocument = oWord.Documents.Open(path);
                //Поиск и замена текста
                functionReplaceInText(wordDocument, oWord, "{uchNumber}", uchNumber);
                functionReplaceInText(wordDocument, oWord, "{year}", year);
                //Добавление в файл таблицы
                Object autoFitBehavior = Word.WdAutoFitBehavior.wdAutoFitWindow;
                //Добавление в файл таблицы
                label3.BeginInvoke((MethodInvoker)(() => this.label3.Text = "Информация: создание таблицы"));
                var range = wordDocument.Paragraphs[wordDocument.Paragraphs.Count - 1].Range;
                wordDocument.Tables.Add(range, 1, 6, autoFitBehavior);
                Word.Table oTable = wordDocument.Tables[1];
                string[] nameHeaderTable = { "№\nп/п", "Код и наименование\nкомплектующего\nэлемента", "Код и наименование\nиспытуемого\nэлемента", "Заводы-\nизготовители\nКЭ и ИЭ", "Заявлено (шт) /Объем испытаний (выстр) /Результ потр.в КЭ (шт)", "Остаток\nна начало\nпериода", "год", "1 кв", "2 кв", "3 кв", "4 кв" };
                for (int i = 0; i < oTable.Columns.Count; i++)
                {
                    Word.Range wordCellRange = oTable.Cell(1, i + 1).Range;
                    wordCellRange.ParagraphFormat.Alignment = Word.WdParagraphAlignment.wdAlignParagraphCenter;
                    wordCellRange.Text = nameHeaderTable[i];
                    wordCellRange.Font.Name = "Times New Roman";
                }
                oTable.Rows.First.HeadingFormat = -1;
                oTable.Cell(1, 1).Width = 35;
                oTable.Cell(1, 2).Width = 120;
                oTable.Cell(1, 3).Width = 120;
                oTable.Cell(1, 4).Width = 80;
                oTable.Cell(1, 5).Width = 370;
                oTable.Cell(1, 6).Width = 60;
                oTable.Cell(1, 5).Split(2, 1);
                oTable.Cell(2, 5).Split(1, 5);
                oTable.Cell(2, 5).Width = 130;
                oTable.Cell(2, 6).Width = 60;
                oTable.Cell(2, 7).Width = 60;
                oTable.Cell(2, 8).Width = 60;
                oTable.Cell(2, 9).Width = 60;
                oTable.Cell(1, 6).Merge(oTable.Cell(2, 10));
                for (int i = 5; i < 10; i++)
                {
                    Word.Range wordCellRange = oTable.Cell(2, i).Range;
                    wordCellRange.ParagraphFormat.Alignment = Word.WdParagraphAlignment.wdAlignParagraphCenter;
                    wordCellRange.Text = nameHeaderTable[i + 1];
                    wordCellRange.Rows.HeadingFormat = -1;
                }
                //Вывод
                string lastCodeElement = "";
                int posRows = 3;
                int numberPosition = 1;
                foreach (RashGodSO so in sortedList)
                {
                    label3.BeginInvoke((MethodInvoker)(() => this.label3.Text = "Информация: печать элемента " + (posRows-2) + " из " + sortedList.Count()));
                    oTable.Rows.Add();
                    Word.Range wordCellRange = oTable.Cell(posRows, 1).Range;
                    wordCellRange.Rows.AllowBreakAcrossPages = 0;
                    if (lastCodeElement != so.kodel)
                    {
                        wordCellRange.Text = Convert.ToString(numberPosition);
                        numberPosition++;
                    }
                    wordCellRange = oTable.Cell(posRows, 2).Range;
                    if (lastCodeElement != so.kodel)
                        wordCellRange.Text = Convert.ToString(so.kodel) + "\n" + so.snhertel + "\n" + so.snindizel + "\n" + so.snnaimel;
                    else
                        wordCellRange.Text = "(продолжение)";
                    wordCellRange = oTable.Cell(posRows, 3).Range;
                    wordCellRange.Text = Convert.ToString(so.kodil) + "\n" + so.snhertil + "\n" + so.snindizil + "\n" + so.snnaimil;
                    wordCellRange = oTable.Cell(posRows, 4).Range;
                    wordCellRange.Text = Convert.ToString(so.kodpost) + "\n" + so.nzavpost + "\n\n" + so.kodotr + "\n" + so.nzavotr;
                    wordCellRange = oTable.Cell(posRows, 5).Range;
                    if (lastCodeElement != so.kodel)
                        wordCellRange.Text = "Заявлено:   " + Convert.ToString(so.qptg) + "\n\n" + "Объём испытаний:   " + Convert.ToString(so.vispg) + "\n" + "Потребность в КЭ:   " + Convert.ToString(so.qptg1);
                    else
                        wordCellRange.Text = "\n\n\n" + "Объём испытаний:   " + Convert.ToString(so.vispg) + "\n" + "Потребность в КЭ:   " + Convert.ToString(so.qptg1);
                    wordCellRange = oTable.Cell(posRows, 6).Range;
                    if (lastCodeElement != so.kodel)
                        wordCellRange.Text = Convert.ToString(so.qpt1) + "\n\n" + Convert.ToString(so.visp1);
                    else
                        wordCellRange.Text = "\n\n\n" + Convert.ToString(so.visp1);
                    wordCellRange = oTable.Cell(posRows, 7).Range;
                    if (lastCodeElement != so.kodel)
                        wordCellRange.Text = Convert.ToString(so.qpt2) + "\n\n" + Convert.ToString(so.visp2);
                    else
                        wordCellRange.Text = "\n\n\n" + Convert.ToString(so.visp2);
                    wordCellRange = oTable.Cell(posRows, 8).Range;
                    if (lastCodeElement != so.kodel)
                        wordCellRange.Text = Convert.ToString(so.qpt3) + "\n\n" + Convert.ToString(so.visp3);
                    else
                        wordCellRange.Text = "\n\n\n" + Convert.ToString(so.visp3);
                    wordCellRange = oTable.Cell(posRows, 9).Range;
                    if (lastCodeElement != so.kodel)
                    {
                        wordCellRange.Text = Convert.ToString(so.qpt4) + "\n\n" + Convert.ToString(so.visp4);
                        lastCodeElement = so.kodel;
                    }
                    else
                        wordCellRange.Text = "\n\n\n" + Convert.ToString(so.visp4);
                    posRows++;
                }
                //Создание экземпляров
                label3.BeginInvoke((MethodInvoker)(() => this.label3.Text = "Информация: создание экземпляров"));
                wordDocument.Range().Copy();
                var rangeAllDocumentEkz = wordDocument.Content;
                rangeAllDocumentEkz.Find.ClearFormatting();
                //for (int j = 0; j < planIspList.Count; j++)
                {
                    functionReplaceInText(wordDocument, oWord, "{numberEkz}", "1");
                }
                for (int i = 0; i < Convert.ToInt32(countEkz) - 1; i++)
                {
                    //В конец документа
                    object what = Word.WdGoToItem.wdGoToLine;
                    object which = Word.WdGoToDirection.wdGoToLast;
                    Word.Range endRange = wordDocument.GoTo(ref what, ref which);
                    //Создаем разрыв РАЗДЕЛА (не страниц)
                    endRange.InsertBreak(Word.WdBreakType.wdSectionBreakNextPage);
                    //Вставляем
                    endRange.Paste();
                    rangeAllDocumentEkz = wordDocument.Content;
                    rangeAllDocumentEkz.Find.ClearFormatting();
                    rangeAllDocumentEkz.Find.Execute(FindText: "{numberEkz}", ReplaceWith: i + 2);
                }
                //Очистка буфера
                Clipboard.Clear();
                //Сохранение
                label3.BeginInvoke((MethodInvoker)(() => this.label3.Text = "Информация: сохранение"));
                DateTime dateNowFileName = DateTime.Now;
                string strSaveName = Convert.ToString(dateNowFileName);
                strSaveName = strSaveName.Replace(':', '-');
                if (!Directory.Exists(Properties.Settings.Default.PathForReports))
                    Directory.CreateDirectory(Directory.GetCurrentDirectory() + "/reports");
                wordDocument.SaveAs(Properties.Settings.Default.PathForReports + "/Справка-обоснование" + " " + strSaveName + ".docx");
                wordDocument.Close();
                oWord.Quit();
                label3.BeginInvoke((MethodInvoker)(() => this.label3.Text = "Информация: справка-обоснование сохранена!"));
                MessageBox.Show("Справка-обоснование создана!");
            }
            catch (Exception ex)
            {
                MessageBox.Show("error: " + ex.Message);
            }
        }

        #endregion

        //Печать мат. части
        #region Результ МАТ ЧАСТЬ
        private void button4_Click(object sender, EventArgs e)
        {
            button4.BackColor = choseColorBTN;
            BlockAllButton();
            if (Properties.Settings.Default.OtraslPath.Length > 0)
            {
                if (Properties.Settings.Default.RashGodFolderPath.Length > 0)
                {
                    FormChoseRashMCH fcrmch = new FormChoseRashMCH();
                    fcrmch.ShowDialog();
                    if (fcrmch.isExit)
                    {
                        try
                        {
                            label3.BeginInvoke((MethodInvoker)(() => this.label3.Text = "Информация: подготовка к печати"));
                            Thread thread = new Thread(() =>
                            {
                                List<RashGodMCH> mchList = new List<RashGodMCH>();
                                //Получение мат части
                                mchList = GetRashGodMCH(mchList);
                                //Заполнение мат части
                                //mchList = FillRashGodMCH(mchList);
                                //Печать сводного плана испытаний
                                PrintRashGodMCH(fcrmch.typeMCH, mchList, fcrmch.year, fcrmch.uchNumber, fcrmch.count);
                                BlockAllButton(false);
                            });
                            thread.SetApartmentState(ApartmentState.STA);
                            thread.Start();
                        }
                        catch (Exception ex)
                        {
                            BlockAllButton(false);
                            MessageBox.Show("Очередная ошибка\n" + ex.Message + "\n" + ex.Source + "\n" + ex.StackTrace + "\n" + ex.TargetSite, "Ошибка", MessageBoxButtons.OK, MessageBoxIcon.Error);
                        }
                    }
                    else
                        BlockAllButton(false);
                }
                else
                {
                    BlockAllButton(false);
                    MessageBox.Show("Выберите поддиректорию RAHGOD");
                }
            }
            else
            {
                BlockAllButton(false);
                MessageBox.Show("Выберите директорию OTRASL");
            }
        }

        private List<RashGodMCH> GetRashGodMCH(List<RashGodMCH> mchList)
        {
            mchList.Clear();
            label3.BeginInvoke((MethodInvoker)(() => this.label3.Text = "Информация: получение мат. части"));
            string strConnection = @"Provider=Microsoft.JET.Oledb.4.0;Data Source=" + Properties.Settings.Default.OtraslPath + "/RAHGOD/" + Properties.Settings.Default.RashGodFolderPath + "/MATCH;Extended Properties=dBASE IV;User ID=;Password=;";
            using (_connection = new OleDbConnection(strConnection))
            {
                _connection.Open();
                using (OleDbCommand cmd = _connection.CreateCommand())
                {
                    cmd.CommandText = "SELECT * FROM MH1.DBF";
                    using (OleDbDataReader reader = cmd.ExecuteReader())
                    {
                        while (reader.Read())
                        {
                            RashGodMCH mch = new RashGodMCH();
                            mch.kodel = Convert.ToString(reader["KODEL"]);
                            mch.shvid = Convert.ToString(reader["SHVID"]);
                            mch.kodotr = Convert.ToString(reader["KODOTR"]);
                            mch.pol = Convert.ToString(reader["POL"]);
                            mch.vispg = Convert.ToString(reader["VISPG"]);
                            mch.visp1 = Convert.ToString(reader["VISP1"]);
                            mch.visp2 = Convert.ToString(reader["VISP2"]);
                            mch.visp3 = Convert.ToString(reader["VISP3"]);
                            mch.visp4 = Convert.ToString(reader["VISP4"]);
                            mch.shsis = Convert.ToString(reader["SHSIS"]);
                            mch.shstv = Convert.ToString(reader["SHSTV"]);
                            mch.shstd = Convert.ToString(reader["SHSTD"]);
                            mch.potsisg = Convert.ToString(reader["POTSISG"]);
                            mch.potsis1 = Convert.ToString(reader["POTSIS1"]);
                            mch.potsis2 = Convert.ToString(reader["POTSIS2"]);
                            mch.potsis3 = Convert.ToString(reader["POTSIS3"]);
                            mch.potsis4 = Convert.ToString(reader["POTSIS4"]);
                            mch.potstvg = Convert.ToString(reader["POTSTVG"]);
                            mch.potstv1 = Convert.ToString(reader["POTSTV1"]);
                            mch.potstv2 = Convert.ToString(reader["POTSTV2"]);
                            mch.potstv3 = Convert.ToString(reader["POTSTV3"]);
                            mch.potstv4 = Convert.ToString(reader["POTSTV4"]);
                            mch.potstdg = Convert.ToString(reader["POTSTDG"]);
                            mch.potstd1 = Convert.ToString(reader["POTSTD1"]);
                            mch.potstd2 = Convert.ToString(reader["POTSTD2"]);
                            mch.potstd3 = Convert.ToString(reader["POTSTD3"]);
                            mch.potstd4 = Convert.ToString(reader["POTSTD4"]);
                            mch.rp = Convert.ToString(reader["RP"]);
                            mch.kvp = Convert.ToString(reader["KVP"]);
                            mch.klg = Convert.ToString(reader["KLG"]);
                            mch.kvs = Convert.ToString(reader["KVS"]);
                            mch.givsis = Convert.ToString(reader["GIVSIS"]);
                            mch.givstv = Convert.ToString(reader["GIVSTV"]);
                            mch.givstd = Convert.ToString(reader["GIVSTD"]);
                            mch.plprt = Convert.ToString(reader["PLPRT"]);
                            mch.plprp = Convert.ToString(reader["PLPRP"]);
                            mchList.Add(mch);
                        }
                    }
                }
            }
            return mchList;
        }

        private void PrintRashGodMCH(int typeMCH, List<RashGodMCH> mchList, string year, string uchNumber, string countEkz)
        {
            //WORD
            label3.BeginInvoke((MethodInvoker)(() => this.label3.Text = "Информация: подготовка документа к печати"));
            Word.Application oWord = new Word.Application();
            oWord.Visible = checkBox4.Checked;
            try
            {
                //Открытие документа
                Word.Document wordDocument;
                string path = Directory.GetCurrentDirectory() + templateFileNameWordPotrMCResult;
                if (typeMCH == 1)
                    path = Directory.GetCurrentDirectory() + templateFileNameWordPotrMCResult;
                else if (typeMCH == 2)
                    path = Directory.GetCurrentDirectory() + templateFileNameWordMCResult;
                else
                    path = Directory.GetCurrentDirectory() + templateFileNameWordCallMCResult;
                wordDocument = oWord.Documents.Open(path);
                //Поиск и замена текста
                functionReplaceInText(wordDocument, oWord, "{uchNumber}", uchNumber);
                functionReplaceInText(wordDocument, oWord, "{year}", year);
                //Добавление в файл таблицы
                label3.BeginInvoke((MethodInvoker)(() => this.label3.Text = "Информация: создание титульного листа"));
                Word.Table oTable = functionCreateNewTableInMC(wordDocument, typeMCH);
                int positionRows = 3;
                int printMCCount = 1;
                //---------Справка-обоснование мат части---------
                if (typeMCH == 1)
                {
                    var sortedList = mchList.OrderBy(a => a.kodel);
                    foreach (RashGodMCH mc in sortedList)
                    {
                        label3.BeginInvoke((MethodInvoker)(() => this.label3.Text = "Информация: печать справки-обоснование " + printMCCount + " из " + sortedList.Count()));
                        printMCCount++;
                        oTable.Rows.Add();
                        for (int j = 0; j < 9; j++)
                        {
                            Word.Range wordCellRange = oTable.Cell(positionRows, j + 1).Range;
                            wordCellRange.Rows.HeadingFormat = 0;
                            if (j == 0)
                                wordCellRange.Text = Convert.ToString(printMCCount - 1);
                            if (j == 1)
                                wordCellRange.Text = mc.shsis + "\n" + mc.shstv + "\n" + mc.shstd;
                            if (j == 2)
                            {
                                int countPosMC = 0;
                                double potrMCFond = 0;

                                wordCellRange = oTable.Cell(positionRows, 8).Range;
                                wordCellRange.Font.Bold = 1;
                                wordCellRange.Text = "Итого";
                                wordCellRange = oTable.Cell(positionRows, 9).Range;
                                wordCellRange.Text = Convert.ToString(Math.Ceiling(potrMCFond));
                                wordCellRange.Font.Bold = 0;
                            }
                        }
                        positionRows++;
                    }
                }
                else
                {
                    if (typeMCH == 2)
                    {
                        var sortedList = mchList.OrderBy(a => a.kodel);
                        foreach (RashGodMCH mc in sortedList)
                        {
                            label3.BeginInvoke((MethodInvoker)(() => this.label3.Text = "Информация: печать матчасти Результилизационного плана " + printMCCount + " из " + sortedList.Count()));
                            printMCCount++;
                            oTable.Rows.Add();
                            int realySummMC = 0;
                            int summYearMC = 0;
                            int realOneMC = 0;
                            for (int j = 1; j < 10; j++)
                            {
                                Word.Range wordCellRange = oTable.Cell(positionRows, j).Range;
                                wordCellRange.Rows.HeadingFormat = 0;
                                if (j == 1)
                                    wordCellRange.Text = Convert.ToString(printMCCount - 1);
                                if (j == 2)
                                    wordCellRange.Text = mc.shsis + "\n" + mc.shstv + "\n" + mc.shstd;
                                //if (j == 3)
                                //wordCellRange.Text = mc.nameFactoryPost;
                                if (j == 4)
                                {
                                    wordCellRange.Text = mc.potsis1 + "\n" + mc.potstv1 + "\n" + mc.potstd1;
                                }
                                if (j == 5)
                                {
                                    wordCellRange.Text = mc.potsis2 + "\n" + mc.potstv2 + "\n" + mc.potstd2;
                                }
                                if (j == 6)
                                {
                                    wordCellRange.Text = mc.potsis3 + "\n" + mc.potstv3 + "\n" + mc.potstd3;
                                }
                                if (j == 7)
                                {
                                    wordCellRange.Text = mc.potsis4 + "\n" + mc.potstv4 + "\n" + mc.potstd4;
                                }
                                if (j == 8)
                                {
                                    wordCellRange = oTable.Cell(positionRows, 5).Range;
                                    wordCellRange.Text = mc.potsisg + "\n" + mc.potstvg + "\n" + mc.potstdg;
                                }
                                if (j == 9)
                                    wordCellRange.Text = "";
                            }
                            positionRows++;
                        }
                    }
                    else
                    {
                        foreach (RashGodMCH mc in mchList)
                        {
                            var range = wordDocument.Content;
                            range.Find.ClearFormatting();
                            //range.Find.Execute(FindText: "{factoryPost}", ReplaceWith: mcf.nameFactoryPost);
                            positionRows += 2;
                            label3.BeginInvoke((MethodInvoker)(() => this.label3.Text = "Информация: печать заявок " + printMCCount + " из " + mchList.Count));
                            printMCCount++;
                            oTable.Rows.Add();
                            int realySummMC = 0;
                            int summYearMC = 0;
                            int realOneMC = 0;
                            int printMCFactorysCount = 1;
                            //Добавление новой страницы
                            if (printMCCount - 1 != mchList.Count)
                            {
                                //В конец документа
                                object what = Word.WdGoToItem.wdGoToLine;
                                object which = Word.WdGoToDirection.wdGoToLast;
                                Word.Range endRange = wordDocument.GoTo(ref what, ref which);
                                //Создаем разрыв страниц
                                endRange.InsertBreak();
                                //Добавляем параграф
                                range = wordDocument.GoTo(ref what, ref which);
                                range.Text = "\t\t\t\t\t\tпредприятие-поставщик:  {factoryPost}";
                                wordDocument.Paragraphs.Add();
                                wordDocument.Paragraphs.Add();
                                wordDocument.Paragraphs.Add();
                                //Создаем новую таблицу
                                oTable = functionCreateNewTableInMC(wordDocument, typeMCH);
                            }
                        }
                    }
                }
                label3.BeginInvoke((MethodInvoker)(() => this.label3.Text = "Информация: создание экземпляров"));
                //Создание экземпляров
                wordDocument.Range().Copy();
                var rangeAllDocumentEkz = wordDocument.Content;
                rangeAllDocumentEkz.Find.ClearFormatting();
                //for (int j = 0; j < plIspResultList.Count; j++)
                {
                    functionReplaceInText(wordDocument, oWord, "{numberEkz}", "1");
                }
                for (int i = 0; i < Convert.ToInt32(countEkz) - 1; i++)
                {
                    //В конец документа
                    object what = Word.WdGoToItem.wdGoToLine;
                    object which = Word.WdGoToDirection.wdGoToLast;
                    Word.Range endRange = wordDocument.GoTo(ref what, ref which);
                    //Создаем разрыв РАЗДЕЛА (не страниц)
                    endRange.InsertBreak(Word.WdBreakType.wdSectionBreakNextPage);
                    //Вставляем
                    endRange.Paste();
                    rangeAllDocumentEkz = wordDocument.Content;
                    rangeAllDocumentEkz.Find.ClearFormatting();
                    rangeAllDocumentEkz.Find.Execute(FindText: "{numberEkz}", ReplaceWith: i + 2);
                }
                //Очистка буфера
                Clipboard.Clear();
                //Сохранение
                label3.BeginInvoke((MethodInvoker)(() => this.label3.Text = "Информация: сохранение"));
                DateTime dateNowFileName = DateTime.Now;
                string strSaveName = Convert.ToString(dateNowFileName);
                strSaveName = strSaveName.Replace(':', '-');
                if (!Directory.Exists(Properties.Settings.Default.PathForReports))
                    Directory.CreateDirectory(Directory.GetCurrentDirectory() + "/reports");
                if (typeMCH == 1)
                    wordDocument.SaveAs(Properties.Settings.Default.PathForReports + "/СПРАВКА-ОБОСНОВАНИЕ РАСЧЕТА ПОТРЕБНОСТИ В МАТЧАСТИ" + " " + strSaveName + ".docx");
                else if (typeMCH == 2)
                    wordDocument.SaveAs(Properties.Settings.Default.PathForReports + "/МАТЧАСТЬ РезультИЛИЗАЦИОННОГО ПЛАНА" + " " + strSaveName + ".docx");
                else
                    wordDocument.SaveAs(Properties.Settings.Default.PathForReports + "/ЗАЯВКИ НА МАТЧАСТЬ" + " " + strSaveName + ".docx");
                wordDocument.Close();

            }
            catch
            {
                MessageBox.Show("Ошибка!");
            }
            finally
            {
                oWord.Quit();
                if (typeMCH == 1)
                    label3.BeginInvoke((MethodInvoker)(() => this.label3.Text = "Информация: справка-обоснование матчасти напечатана!"));
                else if (typeMCH == 2)
                    label3.BeginInvoke((MethodInvoker)(() => this.label3.Text = "Информация: матчасть Результилизационного плана напечатана!"));
                else
                    label3.BeginInvoke((MethodInvoker)(() => this.label3.Text = "Информация: заявки на специальные комплектующие изделия напечатаны!"));
            }
        }

        //Функция добавления таблицы в документ
        private Word.Table functionCreateNewTableInMC(Word.Document wordDocument, int typePrint)
        {
            Object autoFitBehavior = Word.WdAutoFitBehavior.wdAutoFitWindow;
            //Добавление в файл таблицы
            var range = wordDocument.Paragraphs[wordDocument.Paragraphs.Count - 1].Range;
            if (typePrint == 1)
                wordDocument.Tables.Add(range, 1, 8, autoFitBehavior);
            else if (typePrint == 2)
                wordDocument.Tables.Add(range, 1, 5, autoFitBehavior);
            else
                wordDocument.Tables.Add(range, 1, 9, autoFitBehavior);
            Word.Table oTable = wordDocument.Tables[wordDocument.Tables.Count];
            string[] nameHeaderTable1 = { "№\nп/п", "Код\nи наименование\nматчасти", "Код и наименование\nиспытуемого\nэлемента", "Изготовитель\nиспытуемого\nэлемента", "Код и\nнаименование\nвида\nиспытания", "План испытаний(первичных)", "Живучесть\nматчасти", "Испрашиваемый\nфонд", "планируемого\nгода", "планируемого\n+10%" };
            string[] nameHeaderTable2 = { "№ п/п", "Матчасть", "Поставщик", "Результ потребность", "Примечание", "год", "1 кв", "2 кв", "3 кв", "4 кв" };
            string[] nameHeaderTable3 = { "№\nп/п", "Наименование\nизделия,тип\nчертеж", "Ед.\nизм.", "Шифр\nпред-\nприят\nпост.", "Шифр\nкомпле\nтующ.\nиздел.", "Ши-\nфр\nед.\nизм", "Цена\nки в руб.", "Фонд\n2010\nр.г.", "Потребн-ть на ГП Результ/вр", "все-\nго", "в т.ч. по кварталам" };
            for (int i = 0; i < oTable.Columns.Count; i++)
            {
                Word.Range wordCellRange = oTable.Cell(1, i + 1).Range;
                wordCellRange.ParagraphFormat.Alignment = Word.WdParagraphAlignment.wdAlignParagraphCenter;
                wordCellRange.Font.Name = "Times New Roman";
                if (typePrint == 1)
                    wordCellRange.Text = nameHeaderTable1[i];
                else if (typePrint == 2)
                    wordCellRange.Text = nameHeaderTable2[i];
                else
                    wordCellRange.Text = nameHeaderTable3[i];
            }
            oTable.Rows.First.HeadingFormat = -1;
            //Формат таблицы
            if (typePrint == 1)
            {
                oTable.Cell(1, 1).Width = 35;
                oTable.Cell(1, 3).Width = 125;
                oTable.Cell(1, 6).Split(2, 1);
                oTable.Cell(2, 6).Split(1, 2);
            }
            else if (typePrint == 2)
            {
                oTable.Cell(1, 1).Width = 40;
                oTable.Cell(1, 2).Width = 200;
                oTable.Cell(1, 3).Width = 200;
                oTable.Cell(1, 5).Width = 115;
                oTable.Cell(1, 4).Split(2, 1);
                oTable.Cell(2, 4).Split(1, 5);
            }
            else
            {
                oTable.Cell(1, 1).Width = 35;
                oTable.Cell(1, 2).Width = 120;
                oTable.Cell(1, 3).Width = 35;
                oTable.Cell(1, 4).Width = 50;
                oTable.Cell(1, 5).Width = 50;
                oTable.Cell(1, 6).Width = 35;
                oTable.Cell(1, 7).Width = 60;
                oTable.Cell(1, 8).Width = 50;
                oTable.Cell(1, 9).Width = 175;
                oTable.Cell(1, 9).Split(2, 1);
                oTable.Cell(2, 9).Split(1, 2);
                oTable.Cell(2, 9).Width = 35;
                oTable.Cell(2, 10).Width = 140;
                oTable.Cell(2, 10).Split(2, 1);
                oTable.Cell(3, 10).Split(1, 4);
                oTable.Rows.Add();
            }
            //Дозаполнения заголовка
            if (typePrint == 1)
            {
                for (int i = 6; i < 8; i++)
                {
                    Word.Range wordCellRange = oTable.Cell(2, i).Range;
                    wordCellRange.ParagraphFormat.Alignment = Word.WdParagraphAlignment.wdAlignParagraphCenter;
                    wordCellRange.Text = nameHeaderTable1[i + 2];
                    wordCellRange.Rows.HeadingFormat = -1;
                }
            }
            else if (typePrint == 2)
            {
                for (int i = 4; i < 9; i++)
                {
                    Word.Range wordCellRange = oTable.Cell(2, i).Range;
                    wordCellRange.ParagraphFormat.Alignment = Word.WdParagraphAlignment.wdAlignParagraphCenter;
                    wordCellRange.Text = nameHeaderTable2[i + 1];
                    wordCellRange.Rows.HeadingFormat = -1;
                }
            }
            else
            {
                for (int i = 9; i < 11; i++)
                {
                    Word.Range wordCellRange = oTable.Cell(2, i).Range;
                    wordCellRange.ParagraphFormat.Alignment = Word.WdParagraphAlignment.wdAlignParagraphCenter;
                    wordCellRange.Text = nameHeaderTable3[i];
                    wordCellRange.Rows.HeadingFormat = -1;
                }
                for (int i = 10; i < 14; i++)
                {
                    Word.Range wordCellRange = oTable.Cell(3, i).Range;
                    wordCellRange.Text = Convert.ToString(i - 9);
                    wordCellRange.Rows.HeadingFormat = -1;
                }
                Word.Range wordCellRange2 = oTable.Cell(4, 1).Range;
                wordCellRange2.Rows.HeadingFormat = -1;
                wordCellRange2.Text = "а";
                wordCellRange2 = oTable.Cell(4, 2).Range;
                wordCellRange2.Text = "б";
                wordCellRange2 = oTable.Cell(4, 3).Range;
                wordCellRange2.Text = "в";
                wordCellRange2 = oTable.Cell(4, 4).Range;
                wordCellRange2.Text = "г";
                for (int i = 5; i < 14; i++)
                {
                    Word.Range wordCellRange = oTable.Cell(4, i).Range;
                    wordCellRange.Text = Convert.ToString(i - 4);
                }
            }
            return oTable;
        }

        #endregion

        //Печать бронеплиты
        #region Результ БРОНЕПЛИТЫ (преграды)
        private void button5_Click(object sender, EventArgs e)
        {
            button5.BackColor = choseColorBTN;
            BlockAllButton();
            if (Properties.Settings.Default.OtraslPath.Length > 0)
            {
                if (Properties.Settings.Default.RashGodFolderPath.Length > 0)
                {
                    FormChoseRashPreg fcrpreg = new FormChoseRashPreg();
                    fcrpreg.ShowDialog();
                    if (fcrpreg.isExit)
                    {
                        try
                        {
                            label3.BeginInvoke((MethodInvoker)(() => this.label3.Text = "Информация: подготовка к печати"));
                            Thread thread = new Thread(() =>
                            {
                                List<RashGodPreg> pregList = new List<RashGodPreg>();
                                //Получение мат части
                                pregList = GetRashGodPreg(pregList);
                                //Заполнение мат части
                                pregList = FillRashGodPreg(pregList);
                                //Печать бронеплит
                                PrintRashGodPreg(fcrpreg.typePreg, pregList, fcrpreg.year, fcrpreg.uchNumber, fcrpreg.count);
                                BlockAllButton(false);
                            });
                            thread.SetApartmentState(ApartmentState.STA);
                            thread.Start();
                        }
                        catch (Exception ex)
                        {
                            BlockAllButton(false);
                            MessageBox.Show("Очередная ошибка\n" + ex.Message + "\n" + ex.Source + "\n" + ex.StackTrace + "\n" + ex.TargetSite, "Ошибка", MessageBoxButtons.OK, MessageBoxIcon.Error);
                        }
                    }
                    else
                        BlockAllButton(false);
                }
                else
                {
                    BlockAllButton(false);
                    MessageBox.Show("Выберите поддиректорию RAHGOD");
                }
            }
            else
            {
                BlockAllButton(false);
                MessageBox.Show("Выберите директорию OTRASL");
            }
        }

        private List<RashGodPreg> GetRashGodPreg(List<RashGodPreg> pregList)
        {
            pregList.Clear();
            label3.BeginInvoke((MethodInvoker)(() => this.label3.Text = "Информация: получение преграды"));
            string strConnection = @"Provider=Microsoft.JET.Oledb.4.0;Data Source=" + Properties.Settings.Default.OtraslPath + "/RAHGOD/" + Properties.Settings.Default.RashGodFolderPath + "/PREG;Extended Properties=dBASE IV;User ID=;Password=;";
            using (_connection = new OleDbConnection(strConnection))
            {
                _connection.Open();
                using (OleDbCommand cmd = _connection.CreateCommand())
                {
                    cmd.CommandText = "SELECT * FROM PREG.DBF";
                    using (OleDbDataReader reader = cmd.ExecuteReader())
                    {
                        while (reader.Read())
                        {
                            RashGodPreg preg = new RashGodPreg();
                            preg.preg = Convert.ToString(reader["PREG"]);
                            preg.razm = Convert.ToString(reader["RAZM"]);
                            preg.ves = Convert.ToString(reader["VES"]);
                            preg.gipg = Convert.ToString(reader["GIPG"]);
                            preg.qol = Convert.ToString(reader["QOL"]);
                            preg.qol1 = Convert.ToString(reader["QOL1"]);
                            preg.kodel = Convert.ToString(reader["KODEL"]);
                            preg.kodotr = Convert.ToString(reader["KODOTR"]);
                            preg.shvid = Convert.ToString(reader["SHVID"]);
                            preg.shsis = Convert.ToString(reader["SHSIS"]);
                            preg.pol = Convert.ToString(reader["POL"]);
                            pregList.Add(preg);
                        }
                    }
                }
            }
            return pregList;
        }

        private List<RashGodPreg> FillRashGodPreg(List<RashGodPreg> pregList)
        {
            label3.BeginInvoke((MethodInvoker)(() => this.label3.Text = "Информация: получение информации о элементах"));
            //Получение информации о элементе
            string strConnection = @"Provider=Microsoft.JET.Oledb.4.0;Data Source=" + Properties.Settings.Default.OtraslPath + "/SPNAIM;Extended Properties=dBASE IV;User ID=;Password=;";
            using (_connection = new OleDbConnection(strConnection))
            {
                _connection.Open();
                using (OleDbCommand cmd = _connection.CreateCommand())
                {
                    foreach (RashGodPreg preg in pregList)
                    {
                        if (preg.kodel != null && preg.kodel != "")
                        {
                            cmd.CommandText = "SELECT * FROM SPNAIM.DBF WHERE KODEL='" + preg.kodel + "'";
                            using (OleDbDataReader reader = cmd.ExecuteReader())
                            {
                                while (reader.Read())
                                {
                                    preg.snhert = Convert.ToString(reader["SNHERT"] ?? "");

                                    preg.snindiz = Convert.ToString(reader["SNINDIZ"] ?? "");

                                    preg.snnaim = Convert.ToString(reader["SNNAIM1"] ?? "") + Convert.ToString(reader["SNNAIM2"] ?? "") +
                                        Convert.ToString(reader["SNNAIM3"] ?? "") + Convert.ToString(reader["SNNAIM4"] ?? "") +
                                        Convert.ToString(reader["SNNAIM5"] ?? "") + Convert.ToString(reader["SNNAIM6"] ?? "") +
                                        Convert.ToString(reader["SNNAIM7"] ?? "") + Convert.ToString(reader["SNNAIM8"] ?? "") +
                                        Convert.ToString(reader["SNNAIM9"] ?? "") + Convert.ToString(reader["SNNAIM10"] ?? "");

                                }
                            }
                        }
                        else
                            MessageBox.Show("В плане элемент без кода!", "Косяк", MessageBoxButtons.OK, MessageBoxIcon.Error);
                    }
                }
            }
            //Получение информации о заводе
            label3.BeginInvoke((MethodInvoker)(() => this.label3.Text = "Информация: получение информации о заводе"));
            strConnection = @"Provider=Microsoft.JET.Oledb.4.0;Data Source=" + Properties.Settings.Default.OtraslPath + "/SPRXZ;Extended Properties=dBASE IV;User ID=;Password=;";
            using (_connection = new OleDbConnection(strConnection))
            {
                _connection.Open();
                using (OleDbCommand cmd = _connection.CreateCommand())
                {
                    foreach (RashGodPreg preg in pregList)
                    {
                        cmd.CommandText = "SELECT KODOTR, NZAV1, NZAV2, NZAV3, INN FROM SPRXZ.DBF WHERE KODOTR='" + preg.kodotr + "'";
                        using (OleDbDataReader reader = cmd.ExecuteReader())
                        {
                            while (reader.Read())
                            {
                                preg.nzav = Convert.ToString(reader["NZAV1"]) + Convert.ToString(reader["NZAV2"]) + Convert.ToString(reader["NZAV3"]);
                                preg.inn = Convert.ToString(reader["INN"]);
                            }
                        }
                    }
                }
            }
            //Получение информации о виде
            label3.BeginInvoke((MethodInvoker)(() => this.label3.Text = "Информация: получение информации о виде"));
            strConnection = @"Provider=Microsoft.JET.Oledb.4.0;Data Source=" + Properties.Settings.Default.OtraslPath + "/SPRAV;Extended Properties=dBASE IV;User ID=;Password=;";
            using (_connection = new OleDbConnection(strConnection))
            {
                _connection.Open();
                using (OleDbCommand cmd = _connection.CreateCommand())
                {
                    foreach (RashGodPreg preg in pregList)
                    {
                        cmd.CommandText = "SELECT * FROM SPVID.DBF WHERE SHVID='" + preg.shvid + "'";
                        using (OleDbDataReader reader = cmd.ExecuteReader())
                        {
                            while (reader.Read())
                            {
                                preg.nvid = Convert.ToString(reader["NVID1"]) + " " + Convert.ToString(reader["NVID2"]);
                            }
                        }
                    }
                }
            }
            //Получение информации о полигоне
            label3.BeginInvoke((MethodInvoker)(() => this.label3.Text = "Информация: получение информации о полигоне"));
            try
            {
                strConnection = @"Provider=Microsoft.JET.Oledb.4.0;Data Source=" + Properties.Settings.Default.OtraslPath + "/SPRAV;Extended Properties=dBASE IV;User ID=;Password=;";
                using (_connection = new OleDbConnection(strConnection))
                {
                    _connection.Open();
                    using (OleDbCommand cmd = _connection.CreateCommand())
                    {
                        foreach (RashGodPreg preg in pregList)
                        {
                            cmd.CommandText = "SELECT * FROM NPOL1.DBF WHERE POL='" + preg.pol + "'";
                            using (OleDbDataReader reader = cmd.ExecuteReader())
                            {
                                while (reader.Read())
                                {
                                    preg.npol = Convert.ToString(reader["NPOL"]);
                                }
                            }
                        }
                    }
                }
            }
            catch (Exception ex)
            {
                MessageBox.Show("Что-то с полигонами!");
            }
            //Получение информации о МЧ
            label3.BeginInvoke((MethodInvoker)(() => this.label3.Text = "Информация: получение информации о МЧ"));
            try
            {
                strConnection = @"Provider=Microsoft.JET.Oledb.4.0;Data Source=" + Properties.Settings.Default.OtraslPath + "/SPRAV;Extended Properties=dBASE IV;User ID=;Password=;";
                using (_connection = new OleDbConnection(strConnection))
                {
                    _connection.Open();
                    using (OleDbCommand cmd = _connection.CreateCommand())
                    {
                        foreach (RashGodPreg preg in pregList)
                        {
                            if (preg.shsis != null)
                                if (preg.shsis != "")
                                {
                                    cmd.CommandText = "SELECT * FROM SPSIS.DBF WHERE SHSIS='" + preg.shsis + "'";
                                    using (OleDbDataReader reader = cmd.ExecuteReader())
                                    {
                                        while (reader.Read())
                                        {
                                            preg.nsis = Convert.ToString(reader["NSIS"]);
                                        }
                                    }
                                }
                        }
                    }
                }
            }
            catch (Exception ex)
            {
                MessageBox.Show("Что-то с МЧ!!!");
            }
            //Результаты Результ
            label3.BeginInvoke((MethodInvoker)(() => this.label3.Text = "Информация: получение результатов преград"));
            strConnection = @"Provider=Microsoft.JET.Oledb.4.0;Data Source=" + Properties.Settings.Default.OtraslPath + "/RAHGOD/" + Properties.Settings.Default.RashGodFolderPath + "/PREG;Extended Properties=dBASE IV;User ID=;Password=;";
            using (_connection = new OleDbConnection(strConnection))
            {
                _connection.Open();
                using (OleDbCommand cmd = _connection.CreateCommand())
                {
                    foreach (RashGodPreg preg in pregList)
                    {
                        cmd.CommandText = "SELECT * FROM PREGR.DBF WHERE PREG='" + preg.preg + "'";
                        using (OleDbDataReader reader = cmd.ExecuteReader())
                        {
                            while (reader.Read())
                            {
                                preg.obem2 = Convert.ToString(reader["OBEM2"]);
                                preg.ves2 = Convert.ToString(reader["VES"]);
                                preg.qol2 = Convert.ToString(reader["QOL2"]);
                            }
                        }
                    }
                }
            }
            return pregList;
        }

        private void PrintRashGodPreg(int typePreg, List<RashGodPreg> pregList, string year, string uchNumber, string countEkz)
        {
            //WORD
            label3.BeginInvoke((MethodInvoker)(() => this.label3.Text = "Информация: подготовка документа к печати"));
            Word.Application oWord = new Word.Application();
            oWord.Visible = checkBox4.Checked;
            try
            {
                //Открытие документа
                Word.Document wordDocument;
                string path = Directory.GetCurrentDirectory() + templateFileNameWordPotrVBPResult;
                if (typePreg == 1)
                    path = Directory.GetCurrentDirectory() + templateFileNameWordPotrVBPResult;
                else if (typePreg == 2)
                    path = Directory.GetCurrentDirectory() + templateFileNameWordPotrVBPResult;
                else if (typePreg == 3)
                    path = Directory.GetCurrentDirectory() + templateFileNameWordSvPotrVBPResult;
                else
                    path = Directory.GetCurrentDirectory() + templateFileNameWordSvPotrVBPResult;
                wordDocument = oWord.Documents.Open(path);
                //Поиск и замена текста
                functionReplaceInText(wordDocument, oWord, "{uchNumber}", uchNumber);
                functionReplaceInText(wordDocument, oWord, "{year}", year);
                //Добавление в файл таблицы
                label3.BeginInvoke((MethodInvoker)(() => this.label3.Text = "Информация: создание титульного листа"));
                Object autoFitBehavior = Word.WdAutoFitBehavior.wdAutoFitWindow;
                //Добавление в файл таблицы
                var range = wordDocument.Paragraphs[wordDocument.Paragraphs.Count - 1].Range;
                if (typePreg == 2)
                    wordDocument.Tables.Add(range, 1, 10, autoFitBehavior);
                else if(typePreg == 3)
                    wordDocument.Tables.Add(range, 1, 9, autoFitBehavior);
                else if(typePreg == 0)
                    wordDocument.Tables.Add(range, 1, 6, autoFitBehavior);
                else
                    wordDocument.Tables.Add(range, 1, 5, autoFitBehavior);
                Word.Table oTable = wordDocument.Tables[1];
                string[] nameHeaderTable1 = { "Шифр,\nразмер броне-\nплиты", "Вес\nб/плиты\nв кг", "Полигон", "Завод-\nизготовитель\nИЭ", "Шифр,\nнаименование\nиспытуемого эл-та", "Шифр,\nнаименование\nсистемы", "Шифр,\nнаименование\nВИ", "Кол-во\nвыстре-\nлов", "Живу-\nчесть\nБП", "Результ\nпотребность\nв б/плитах" };
                string[] nameHeaderTable2 = { "Шифр,\nразмер броне-\nплиты", "Вес\nб/плиты\nв кг", "Завод-\nизготовитель\nИЭ", "Шифр,\nнаименование\nиспытуемого эл-та", "Шифр,\nнаименование\nсистемы", "Шифр,\nнаименование\nВИ", "Кол-во\nвыстре-\nлов", "Живу-\nчесть\nБП", "Результ\nпотребность\nв б/плитах" };
                string[] nameHeaderSVTable1 = { "Шифр,\nразмер бронеплиты", "Полигон-\nпотребитель", "Потребное\nкол-во,шт", "Вес,\nт", "Предприятие-\nизготовитель", "Примечание" };
                string[] nameHeaderSVTable2 = { "Шифр,\nразмер бронеплиты", "Потребное\nкол-во,шт", "Вес,\nт", "Предприятие-\nизготовитель", "Примечание" };
                for (int i = 0; i < oTable.Columns.Count; i++)
                {
                    Word.Range wordCellRange = oTable.Cell(1, i + 1).Range;
                    wordCellRange.ParagraphFormat.Alignment = Word.WdParagraphAlignment.wdAlignParagraphCenter;
                    wordCellRange.Font.Name = "Times New Roman";
                    if (typePreg == 2)
                        wordCellRange.Text = nameHeaderTable1[i];
                    else if(typePreg == 3)
                        wordCellRange.Text = nameHeaderTable2[i];
                    else if (typePreg == 0)
                        wordCellRange.Text = nameHeaderSVTable1[i];
                    else
                        wordCellRange.Text = nameHeaderSVTable2[i];
                }
                oTable.Rows.First.HeadingFormat = -1;
                if (typePreg == 0 || typePreg == 1)
                {
                    oTable.Cell(1, 1).Width = 85;
                    oTable.Cell(1, 2).Width = 55;
                    oTable.Cell(1, 3).Width = 80;
                    oTable.Cell(1, 4).Width = 115;
                    oTable.Cell(1, 5).Width = 115;
                }
                else
                {

                }
                int positionRows = 2;
                int printVBPCount = 1;
                double summPotrVBP = 0;
                double summVesBP = 0;
                double itogPotrVBP = 0;
                double itogVesBP = 0;
                var sortedList = pregList.OrderBy(a => a.preg);
                string oldPreg = "";
                int numberPrintPreg = 1;
                foreach (RashGodPreg vbp in sortedList)
                {
                    if(typePreg == 0)
                    {
                        label3.BeginInvoke((MethodInvoker)(() => this.label3.Text = "Информация: печать сводной " + printVBPCount + " из " + pregList.Count));
                        printVBPCount++;
                        if (oldPreg != vbp.preg)
                        {
                            oTable.Rows.Add();
                            Word.Range wordCellRange = oTable.Cell(positionRows, 1).Range;
                            wordCellRange.Rows.HeadingFormat = 0;
                            wordCellRange.Text = Convert.ToString(numberPrintPreg) + ".   " + vbp.preg + "\n" + vbp.razm;
                            wordCellRange = oTable.Cell(positionRows, 2).Range;
                            wordCellRange.Text = vbp.pol + "\n" + vbp.npol;
                            wordCellRange = oTable.Cell(positionRows, 3).Range;
                            wordCellRange.Text = vbp.qol2;
                            wordCellRange = oTable.Cell(positionRows, 4).Range;
                            wordCellRange.Text = vbp.ves2;

                            summPotrVBP += Convert.ToDouble(vbp.qol2);
                            summVesBP += Convert.ToDouble(vbp.ves2);

                            positionRows++;
                            if (printVBPCount - 1 == pregList.Count)
                            {
                                oTable.Cell(positionRows - 1, 5).Split(2, 2);
                                wordCellRange = oTable.Cell(positionRows - 1, 5).Range;
                                wordCellRange.Text = "Потребн.,шт";
                                wordCellRange = oTable.Cell(positionRows - 1, 6).Range;
                                wordCellRange.Text = "Вес,т";
                                wordCellRange = oTable.Cell(positionRows, 5).Range;
                                wordCellRange.Text = Convert.ToString(summPotrVBP);
                                itogPotrVBP += summPotrVBP;
                                wordCellRange = oTable.Cell(positionRows, 6).Range;
                                wordCellRange.Text = Convert.ToString(summVesBP);
                                itogVesBP += summVesBP;
                            }
                            oldPreg = vbp.preg;
                            numberPrintPreg++;
                        }
                    }
                    else if (typePreg == 1)
                    {
                        label3.BeginInvoke((MethodInvoker)(() => this.label3.Text = "Информация: печать сводной " + printVBPCount + " из " + pregList.Count));
                        printVBPCount++;
                        if (oldPreg != vbp.preg)
                        {
                            oTable.Rows.Add();
                            Word.Range wordCellRange = oTable.Cell(positionRows, 1).Range;
                            wordCellRange.Rows.HeadingFormat = 0;
                            wordCellRange.Text = Convert.ToString(numberPrintPreg) + ".   " + vbp.preg + "\n" + vbp.razm;
                            wordCellRange = oTable.Cell(positionRows, 2).Range;
                            wordCellRange.Text = vbp.qol2;
                            wordCellRange = oTable.Cell(positionRows, 3).Range;
                            wordCellRange.Text = vbp.ves2;

                            summPotrVBP += Convert.ToDouble(vbp.qol2);
                            summVesBP += Convert.ToDouble(vbp.ves2);

                            positionRows++;
                            if (printVBPCount - 1 == pregList.Count)
                            {
                                oTable.Cell(positionRows - 1, 4).Split(2, 2);
                                wordCellRange = oTable.Cell(positionRows - 1, 4).Range;
                                wordCellRange.Text = "Потребн.,шт";
                                wordCellRange = oTable.Cell(positionRows - 1, 5).Range;
                                wordCellRange.Text = "Вес,т";
                                wordCellRange = oTable.Cell(positionRows, 4).Range;
                                wordCellRange.Text = Convert.ToString(summPotrVBP);
                                itogPotrVBP += summPotrVBP;
                                wordCellRange = oTable.Cell(positionRows, 5).Range;
                                wordCellRange.Text = Convert.ToString(summVesBP);
                                itogVesBP += summVesBP;
                            }
                            oldPreg = vbp.preg;
                            numberPrintPreg++;
                        }
                    }
                    else if (typePreg == 2)
                    {
                        label3.BeginInvoke((MethodInvoker)(() => this.label3.Text = "Информация: печать справки-обоснование " + printVBPCount + " из " + pregList.Count));
                        printVBPCount++;
                        oTable.Rows.Add();

                        Word.Range wordCellRange = oTable.Cell(positionRows, 1).Range;
                        wordCellRange.Rows.HeadingFormat = 0;
                        if (oldPreg != vbp.preg)
                        {
                            wordCellRange.Text = Convert.ToString(numberPrintPreg) + ".     " + vbp.preg + "\n" + vbp.razm;
                            oldPreg = vbp.preg;
                            numberPrintPreg++;
                        }
                        wordCellRange = oTable.Cell(positionRows, 2).Range;
                        wordCellRange.Text = vbp.ves;
                        wordCellRange = oTable.Cell(positionRows, 3).Range;
                        wordCellRange.Text = vbp.pol + "\n" + vbp.npol;
                        wordCellRange = oTable.Cell(positionRows, 4).Range;
                        wordCellRange.Text = vbp.kodotr + "\n" + vbp.nzav;
                        wordCellRange = oTable.Cell(positionRows, 5).Range;
                        wordCellRange.Text = vbp.kodel + "\n" + vbp.snhert + "\n" + vbp.snindiz + "\n" + vbp.snnaim;
                        wordCellRange = oTable.Cell(positionRows, 6).Range;
                        wordCellRange.Text = vbp.shsis + "\n" + vbp.nsis;
                        wordCellRange = oTable.Cell(positionRows, 7).Range;
                        wordCellRange.Text = vbp.shvid + "\n" + vbp.nvid;
                        wordCellRange = oTable.Cell(positionRows, 8).Range;
                        wordCellRange.Text = vbp.qol;
                        wordCellRange = oTable.Cell(positionRows, 9).Range;
                        wordCellRange.Text = vbp.gipg;
                        wordCellRange = oTable.Cell(positionRows, 10).Range;
                        wordCellRange.Text = vbp.qol1;

                        positionRows++;
                    }
                    else
                    {
                        label3.BeginInvoke((MethodInvoker)(() => this.label3.Text = "Информация: печать справки-обоснование " + printVBPCount + " из " + pregList.Count));
                        printVBPCount++;
                        oTable.Rows.Add();

                        Word.Range wordCellRange = oTable.Cell(positionRows, 1).Range;
                        wordCellRange.Rows.HeadingFormat = 0;
                        if (oldPreg != vbp.preg)
                        {
                            wordCellRange.Text = Convert.ToString(numberPrintPreg) + ".     " + vbp.preg + "\n" + vbp.razm;
                            oldPreg = vbp.preg;
                            numberPrintPreg++;
                        }
                        wordCellRange = oTable.Cell(positionRows, 2).Range;
                        wordCellRange.Text = vbp.ves;
                        wordCellRange = oTable.Cell(positionRows, 3).Range;
                        wordCellRange.Text = vbp.kodotr + "\n" + vbp.nzav;
                        wordCellRange = oTable.Cell(positionRows, 4).Range;
                        wordCellRange.Text = vbp.kodel + "\n" + vbp.snhert + "\n" + vbp.snindiz + "\n" + vbp.snnaim;
                        wordCellRange = oTable.Cell(positionRows, 5).Range;
                        wordCellRange.Text = vbp.shsis + "\n" + vbp.nsis;
                        wordCellRange = oTable.Cell(positionRows, 6).Range;
                        wordCellRange.Text = vbp.shvid + "\n" + vbp.nvid;
                        wordCellRange = oTable.Cell(positionRows, 7).Range;
                        wordCellRange.Text = vbp.qol;
                        wordCellRange = oTable.Cell(positionRows, 8).Range;
                        wordCellRange.Text = vbp.gipg;
                        wordCellRange = oTable.Cell(positionRows, 9).Range;
                        wordCellRange.Text = vbp.qol1;

                        positionRows++;
                    }
                }
                if (typePreg == 0 || typePreg == 1)
                {
                    wordDocument.Paragraphs.Add();
                    range = wordDocument.Paragraphs[wordDocument.Paragraphs.Count - 1].Range;
                    range.Text = "\t\t\t\t\t\t\t\t\tИтог\t     " + itogPotrVBP + "\t\t" + itogVesBP + "\n\tИ.о. начальника отдела №22\t\t\tТ.М.Мясникова";
                    wordDocument.Paragraphs.Add();
                }
                else
                {
                    oTable.Rows.Add();
                    Word.Range wordCellRange = oTable.Cell(positionRows, 2).Range;
                    wordCellRange.Rows.HeadingFormat = 0;
                    wordCellRange.Text = "ИТОГО";
                    wordCellRange = oTable.Cell(positionRows, 3).Range;
                    wordCellRange.Text = Convert.ToString(itogPotrVBP);
                    wordCellRange = oTable.Cell(positionRows, 4).Range;
                    wordCellRange.Text = Convert.ToString(itogVesBP);
                    wordDocument.Paragraphs.Add();
                    range = wordDocument.Paragraphs[wordDocument.Paragraphs.Count - 1].Range;
                    range.Text = "\n\tИ.о. начальника отдела №22\t\t\tТ.М.Мясникова";
                }
                label3.BeginInvoke((MethodInvoker)(() => this.label3.Text = "Информация: создание экземпляров"));
                //Создание экземпляров
                wordDocument.Range().Copy();
                var rangeAllDocumentEkz = wordDocument.Content;
                rangeAllDocumentEkz.Find.ClearFormatting();
                //for (int j = 0; j < plIspResultList.Count; j++)
                {
                    functionReplaceInText(wordDocument, oWord, "{numberEkz}", "1");
                }
                for (int i = 0; i < Convert.ToInt32(countEkz) - 1; i++)
                {
                    //В конец документа
                    object what = Word.WdGoToItem.wdGoToLine;
                    object which = Word.WdGoToDirection.wdGoToLast;
                    Word.Range endRange = wordDocument.GoTo(ref what, ref which);
                    //Создаем разрыв РАЗДЕЛА (не страниц)
                    endRange.InsertBreak(Word.WdBreakType.wdSectionBreakNextPage);
                    //Вставляем
                    endRange.Paste();
                    rangeAllDocumentEkz = wordDocument.Content;
                    rangeAllDocumentEkz.Find.ClearFormatting();
                    rangeAllDocumentEkz.Find.Execute(FindText: "{numberEkz}", ReplaceWith: i + 2);
                }
                //Очистка буфера
                Clipboard.Clear();
                //Сохранение
                label3.BeginInvoke((MethodInvoker)(() => this.label3.Text = "Информация: сохранение документа"));
                DateTime dateNowFileName = DateTime.Now;
                string strSaveName = Convert.ToString(dateNowFileName);
                strSaveName = strSaveName.Replace(':', '-');
                if (!Directory.Exists(Properties.Settings.Default.PathForReports))
                    Directory.CreateDirectory(Directory.GetCurrentDirectory() + "/reports");
                if (typePreg == 2 || typePreg == 3)
                    wordDocument.SaveAs(Properties.Settings.Default.PathForReports + "/СПРАВКА-ОБОСНОВАНИЕ ПОТРЕБНОСТИ В БРОНЕПЛИТАХ" + " " + strSaveName + ".docx");
                else
                    wordDocument.SaveAs(Properties.Settings.Default.PathForReports + "/СВОДНАЯ ВЕДОМОСТЬ ПОТРЕБНОСТИ В БРОНЕПЛИТАХ" + " " + strSaveName + ".docx");
                wordDocument.Close();
                MessageBox.Show("Преграды созданы!");
            }
            catch
            {
                MessageBox.Show("Ошибка! В плитах");
            }
            finally
            {
                oWord.Quit();
                label3.BeginInvoke((MethodInvoker)(() => this.label3.Text = "Информация: документ сохранён!"));
            }
        }
        #endregion

        //Печать крешерного имущества
        #region Результ КРЕШЕРНОЕ ИМУЩЕСТВО
        private void button24_Click(object sender, EventArgs e)
        {
            button24.BackColor = choseColorBTN;
            BlockAllButton();
            if (Properties.Settings.Default.OtraslPath.Length > 0)
            {
                if (Properties.Settings.Default.RashGodFolderPath.Length > 0)
                {
                    FormChoseUchYearCount fcuyc = new FormChoseUchYearCount();
                    fcuyc.ShowDialog();
                    if (fcuyc.isExit)
                    {
                        try
                        {
                            label3.BeginInvoke((MethodInvoker)(() => this.label3.Text = "Информация: подготовка к печати"));
                            Thread thread = new Thread(() =>
                            {
                                List<RashGodKresh> kreshList = new List<RashGodKresh>();
                                //Получение крешерного имущества
                                kreshList = GetRashGodKresh(kreshList);
                                //Заполнение крешерного имущества
                                kreshList = FillRashGodKresh(kreshList);
                                //Печать крешерное имущество
                                PrintRashGodKresh(kreshList, fcuyc.year, fcuyc.uchNumber, fcuyc.count);
                                BlockAllButton(false);
                            });
                            thread.SetApartmentState(ApartmentState.STA);
                            thread.Start();
                        }
                        catch (Exception ex)
                        {
                            BlockAllButton(false);
                            MessageBox.Show("Очередная ошибка\n" + ex.Message + "\n" + ex.Source + "\n" + ex.StackTrace + "\n" + ex.TargetSite, "Ошибка", MessageBoxButtons.OK, MessageBoxIcon.Error);
                        }
                    }
                    else
                        BlockAllButton(false);
                }
                else
                {
                    BlockAllButton(false);
                    MessageBox.Show("Выберите поддиректорию RAHGOD");
                }
            }
            else
            {
                BlockAllButton(false);
                MessageBox.Show("Выберите директорию OTRASL");
            }
        }

        private List<RashGodKresh> GetRashGodKresh(List<RashGodKresh> kreshList)
        {
            kreshList.Clear();
            label3.BeginInvoke((MethodInvoker)(() => this.label3.Text = "Информация: получение крешерного имущества"));
            string strConnection = @"Provider=Microsoft.JET.Oledb.4.0;Data Source=" + Properties.Settings.Default.OtraslPath + "/RAHGOD/" + Properties.Settings.Default.RashGodFolderPath + "/KRESH;Extended Properties=dBASE IV;User ID=;Password=;";
            using (_connection = new OleDbConnection(strConnection))
            {
                _connection.Open();
                using (OleDbCommand cmd = _connection.CreateCommand())
                {
                    cmd.CommandText = "SELECT * FROM KRESH.DBF";
                    using (OleDbDataReader reader = cmd.ExecuteReader())
                    {
                        while (reader.Read())
                        {
                            RashGodKresh kresh = new RashGodKresh();
                            kresh.kodel = Convert.ToString(reader["KODEL"]);
                            kresh.kodotr = Convert.ToString(reader["KODOTR"]);
                            kresh.vxod_z = Convert.ToString(reader["VXOD_Z"]);
                            kresh.kodeiz = Convert.ToString(reader["KODEIZ"]);
                            kresh.qgod = Convert.ToString(reader["QGOD"]);
                            kresh.q1k = Convert.ToString(reader["Q1K"]);
                            kresh.q2k = Convert.ToString(reader["Q2K"]);
                            kresh.q3k = Convert.ToString(reader["Q3K"]);
                            kresh.q4k = Convert.ToString(reader["Q4K"]);
                            kreshList.Add(kresh);
                        }
                    }
                }
            }
            return kreshList;
        }

        private List<RashGodKresh> FillRashGodKresh(List<RashGodKresh> kreshList)
        {
            label3.BeginInvoke((MethodInvoker)(() => this.label3.Text = "Информация: получение информации о элементах"));
            //Получение информации о элементе
            string strConnection = @"Provider=Microsoft.JET.Oledb.4.0;Data Source=" + Properties.Settings.Default.OtraslPath + "/SPNAIM;Extended Properties=dBASE IV;User ID=;Password=;";
            using (_connection = new OleDbConnection(strConnection))
            {
                _connection.Open();
                using (OleDbCommand cmd = _connection.CreateCommand())
                {
                    foreach (RashGodKresh kresh in kreshList)
                    {
                        if (kresh.kodel != null && kresh.kodel != "")
                        {
                            cmd.CommandText = "SELECT * FROM SPNAIM.DBF WHERE KODEL='" + kresh.kodel + "'";
                            using (OleDbDataReader reader = cmd.ExecuteReader())
                            {
                                while (reader.Read())
                                {
                                    kresh.snhert = Convert.ToString(reader["SNHERT"] ?? "");

                                    kresh.snindiz = Convert.ToString(reader["SNINDIZ"] ?? "");

                                    kresh.snnaim = Convert.ToString(reader["SNNAIM1"] ?? "") + Convert.ToString(reader["SNNAIM2"] ?? "") +
                                        Convert.ToString(reader["SNNAIM3"] ?? "") + Convert.ToString(reader["SNNAIM4"] ?? "") +
                                        Convert.ToString(reader["SNNAIM5"] ?? "") + Convert.ToString(reader["SNNAIM6"] ?? "") +
                                        Convert.ToString(reader["SNNAIM7"] ?? "") + Convert.ToString(reader["SNNAIM8"] ?? "") +
                                        Convert.ToString(reader["SNNAIM9"] ?? "") + Convert.ToString(reader["SNNAIM10"] ?? "");

                                }
                            }
                        }
                        else
                            MessageBox.Show("В плане элемент без кода!", "Косяк", MessageBoxButtons.OK, MessageBoxIcon.Error);
                    }
                }
            }
            //Получение информации о заводе
            label3.BeginInvoke((MethodInvoker)(() => this.label3.Text = "Информация: получение информации о заводе"));
            strConnection = @"Provider=Microsoft.JET.Oledb.4.0;Data Source=" + Properties.Settings.Default.OtraslPath + "/SPRXZ;Extended Properties=dBASE IV;User ID=;Password=;";
            using (_connection = new OleDbConnection(strConnection))
            {
                _connection.Open();
                using (OleDbCommand cmd = _connection.CreateCommand())
                {
                    foreach (RashGodKresh kresh in kreshList)
                    {
                        cmd.CommandText = "SELECT KODOTR, NZAV1, NZAV2, NZAV3, INN FROM SPRXZ.DBF WHERE KODOTR='" + kresh.kodotr + "'";
                        using (OleDbDataReader reader = cmd.ExecuteReader())
                        {
                            while (reader.Read())
                            {
                                kresh.nzav = Convert.ToString(reader["NZAV1"]) + Convert.ToString(reader["NZAV2"]) + Convert.ToString(reader["NZAV3"]);
                                kresh.inn = Convert.ToString(reader["INN"]);
                            }
                        }
                    }
                }
            }
            //Получение информации о ед. измерения
            label3.BeginInvoke((MethodInvoker)(() => this.label3.Text = "Информация: получение информации о ед. измерений"));
            strConnection = @"Provider=Microsoft.JET.Oledb.4.0;Data Source=" + Properties.Settings.Default.OtraslPath + "/SPRAV;Extended Properties=dBASE IV;User ID=;Password=;";
            using (_connection = new OleDbConnection(strConnection))
            {
                _connection.Open();
                using (OleDbCommand cmd = _connection.CreateCommand())
                {
                    foreach (RashGodKresh kresh in kreshList)
                    {
                        cmd.CommandText = "SELECT * FROM SPEIZ2 WHERE KODEIZ='" + kresh.kodeiz + "'";
                        using (OleDbDataReader reader = cmd.ExecuteReader())
                        {
                            while (reader.Read())
                            {
                                kresh.naimeiz = Convert.ToString(reader["NAIMEIZ"]);
                                kresh.naik = Convert.ToString(reader["NAIK"]);
                            }
                        }
                    }
                }
            }
            return kreshList;
        }

        private void PrintRashGodKresh(List<RashGodKresh> kreshList, string year, string uchNumber, string countEkz)
        {
            //WORD
            Word.Application oWord = new Word.Application();
            oWord.Visible = checkBox4.Checked;
            try
            {
                label3.BeginInvoke((MethodInvoker)(() => this.label3.Text = "Информация: подготовка документа к печати"));
                //Открытие документа
                Word.Document wordDocument;
                string path = Directory.GetCurrentDirectory() + templateFileNameWordKrImuResult;
                wordDocument = oWord.Documents.Open(path);
                //Поиск и замена текста
                functionReplaceInText(wordDocument, oWord, "{uchNumber}", uchNumber);
                functionReplaceInText(wordDocument, oWord, "{year}", year);
                //Добавление в файл таблицы
                label3.BeginInvoke((MethodInvoker)(() => this.label3.Text = "Информация: создание титульного листа"));
                Object autoFitBehavior = Word.WdAutoFitBehavior.wdAutoFitWindow;
                //Добавление в файл таблицы
                var range = wordDocument.Paragraphs[wordDocument.Paragraphs.Count - 1].Range;
                wordDocument.Tables.Add(range, 1, 8, autoFitBehavior);
                Word.Table oTable = wordDocument.Tables[1];
                string[] nameHeaderTable = { "№\nп/п", "Изделие", "Заказчик", "Ед.\nизм.", "Количество изделий", "Стоимость, т.р.", "Цена,\nт.р.", "Примечание", "год", "1 кв", "2 кв", "3 кв", "4 кв" };
                for (int i = 0; i < oTable.Columns.Count; i++)
                {
                    Word.Range wordCellRange = oTable.Cell(1, i + 1).Range;
                    wordCellRange.ParagraphFormat.Alignment = Word.WdParagraphAlignment.wdAlignParagraphCenter;
                    wordCellRange.Text = nameHeaderTable[i];
                    wordCellRange.Font.Name = "Times New Roman";
                }
                oTable.Rows.First.HeadingFormat = -1;
                oTable.Cell(1, 1).Width = 35;
                oTable.Cell(1, 5).Split(2, 1);
                oTable.Cell(2, 5).Split(1, 5);
                oTable.Cell(1, 6).Split(2, 1);
                oTable.Cell(2, 10).Split(1, 5);
                for (int i = 5; i < 10; i++)
                {
                    Word.Range wordCellRange = oTable.Cell(2, i).Range;
                    wordCellRange.ParagraphFormat.Alignment = Word.WdParagraphAlignment.wdAlignParagraphCenter;
                    wordCellRange.Text = nameHeaderTable[i + 3];
                    wordCellRange.Rows.HeadingFormat = -1;
                }
                for (int i = 10; i < 15; i++)
                {
                    Word.Range wordCellRange = oTable.Cell(2, i).Range;
                    wordCellRange.ParagraphFormat.Alignment = Word.WdParagraphAlignment.wdAlignParagraphCenter;
                    wordCellRange.Text = nameHeaderTable[i - 2];
                    wordCellRange.Rows.HeadingFormat = -1;
                }
                int positionRows = 3;
                int printVKICount = 1;
                var sortedList = kreshList.OrderBy(a => a.kodel).ThenBy(b => b.kodotr);
                foreach (RashGodKresh kresh in sortedList)
                {
                    label3.BeginInvoke((MethodInvoker)(() => this.label3.Text = "Информация: печать крешерного " + printVKICount + " из " + kreshList.Count));
                    printVKICount++;
                    oTable.Rows.Add();

                    Word.Range wordCellRange = oTable.Cell(positionRows, 1).Range;
                    wordCellRange.Rows.AllowBreakAcrossPages = 0;
                    wordCellRange.Rows.HeadingFormat = 0;
                    wordCellRange.Text = Convert.ToString(printVKICount - 1);
                    wordCellRange = oTable.Cell(positionRows, 2).Range;
                    wordCellRange.Rows.HeadingFormat = 0;
                    wordCellRange.Text = kresh.kodel + "\n" + kresh.snhert + "\n" + kresh.snindiz + "\n" + kresh.snnaim;
                    wordCellRange = oTable.Cell(positionRows, 3).Range;
                    wordCellRange.Rows.HeadingFormat = 0;
                    wordCellRange.Text = kresh.kodotr + "\n" + kresh.nzav;
                    wordCellRange = oTable.Cell(positionRows, 4).Range;
                    wordCellRange.Rows.HeadingFormat = 0;
                    wordCellRange.Text = kresh.kodeiz + "\n" + kresh.naimeiz;
                    wordCellRange = oTable.Cell(positionRows, 5).Range;
                    wordCellRange.Rows.HeadingFormat = 0;
                    wordCellRange.Text = kresh.qgod;
                    wordCellRange = oTable.Cell(positionRows, 6).Range;
                    wordCellRange.Rows.HeadingFormat = 0;
                    wordCellRange.Text = kresh.q1k;
                    wordCellRange = oTable.Cell(positionRows, 7).Range;
                    wordCellRange.Rows.HeadingFormat = 0;
                    wordCellRange.Text = kresh.q2k;
                    wordCellRange = oTable.Cell(positionRows, 8).Range;
                    wordCellRange.Rows.HeadingFormat = 0;
                    wordCellRange.Text = kresh.q3k;
                    wordCellRange = oTable.Cell(positionRows, 9).Range;
                    wordCellRange.Rows.HeadingFormat = 0;
                    wordCellRange.Text = kresh.q4k;

                    positionRows++;
                }
                label3.BeginInvoke((MethodInvoker)(() => this.label3.Text = "Информация: создание экземпляров"));
                //Создание экземпляров
                wordDocument.Range().Copy();
                var rangeAllDocumentEkz = wordDocument.Content;
                rangeAllDocumentEkz.Find.ClearFormatting();
                //for (int j = 0; j < plIspResultList.Count; j++)
                {
                    functionReplaceInText(wordDocument, oWord, "{numberEkz}", "1");
                }
                for (int i = 0; i < Convert.ToInt32(countEkz) - 1; i++)
                {
                    //В конец документа
                    object what = Word.WdGoToItem.wdGoToLine;
                    object which = Word.WdGoToDirection.wdGoToLast;
                    Word.Range endRange = wordDocument.GoTo(ref what, ref which);
                    //Создаем разрыв РАЗДЕЛА (не страниц)
                    endRange.InsertBreak(Word.WdBreakType.wdSectionBreakNextPage);
                    //Вставляем
                    endRange.Paste();
                    rangeAllDocumentEkz = wordDocument.Content;
                    rangeAllDocumentEkz.Find.ClearFormatting();
                    rangeAllDocumentEkz.Find.Execute(FindText: "{numberEkz}", ReplaceWith: i + 2);
                }
                //Очистка буфера
                Clipboard.Clear();
                label3.BeginInvoke((MethodInvoker)(() => this.label3.Text = "Информация: сохранение документа"));
                //Сохранение
                DateTime dateNowFileName = DateTime.Now;
                string strSaveName = Convert.ToString(dateNowFileName);
                strSaveName = strSaveName.Replace(':', '-');
                if (!Directory.Exists(Properties.Settings.Default.PathForReports))
                    Directory.CreateDirectory(Directory.GetCurrentDirectory() + "/reports");
                wordDocument.SaveAs(Properties.Settings.Default.PathForReports + "/ЗАЯВКИ НА ПОСТАВКУ КРЕШЕРНОГО ИМУЩЕСТВА" + " " + strSaveName + ".docx");
                wordDocument.Close();
                MessageBox.Show("Крешрное имущество создано!");
            }
            catch
            {
                MessageBox.Show("Ошибка в крешерном!");
            }
            finally
            {
                oWord.Quit();
            }
        }
        #endregion

        //Печать заявок на комплектующие элементы (справка-обоснование по заводам)
        #region Результ ЗАЯВКИ НА КОМПЛЕКТУЮЩИЕ
        private void button23_Click(object sender, EventArgs e)
        {
            button23.BackColor = choseColorBTN;
            BlockAllButton();
            if (Properties.Settings.Default.OtraslPath.Length > 0)
            {
                if (Properties.Settings.Default.RashGodFolderPath.Length > 0)
                {
                    FormChoseUchYearCount fcuyc = new FormChoseUchYearCount();
                    fcuyc.ShowDialog();
                    if (fcuyc.isExit)
                    {
                        try
                        {
                            label3.BeginInvoke((MethodInvoker)(() => this.label3.Text = "Информация: подготовка к печати"));
                            Thread thread = new Thread(() =>
                            {
                                List<RashGodSO> soList = new List<RashGodSO>();
                                //Получение справки-обоснования
                                soList = GetRashGodSO(soList);
                                //Заполнение справки-обоснование
                                soList = FillRashGodSO(soList);
                                //Печать заявок на комплектующие
                                PrintRashApplication(soList, fcuyc.year, fcuyc.uchNumber, fcuyc.count);
                                //Разблокировка клавиш
                                BlockAllButton(false);
                            });
                            thread.SetApartmentState(ApartmentState.STA);
                            thread.Start();
                        }
                        catch (Exception ex)
                        {
                            BlockAllButton(false);
                            MessageBox.Show("Очередная ошибка\n" + ex.Message + "\n" + ex.Source + "\n" + ex.StackTrace + "\n" + ex.TargetSite, "Ошибка", MessageBoxButtons.OK, MessageBoxIcon.Error);
                        }
                    }
                    else
                        BlockAllButton(false);
                }
                else
                {
                    BlockAllButton(false);
                    MessageBox.Show("Выберите поддиректорию RAHGOD");
                }
            }
            else
            {
                BlockAllButton(false);
                MessageBox.Show("Выберите директорию OTRASL");
            }
        }

        private void PrintRashApplication(List<RashGodSO> soList, string yearPrintResult, string uchNumber, string countEkz)
        {
            label3.BeginInvoke((MethodInvoker)(() => this.label3.Text = "Информация: печать заявок"));
            //Сортируем план испытаний
            var sortedList = soList.OrderBy(a => a.kodpost).ThenBy(b => b.kodel);
            //Создали объект для ворда
            Word.Application oWord = new Word.Application();
            //Видимость включили
            oWord.Visible = checkBox4.Checked;
            try
            {
                string codeElement = "";
                string namePositionFactoryInWord = null;
                int positionInSortedList = 0;
                int positionRows = 4;
                int printVKECount = 1;
                Word.Document wordDocument = null;
                Word.Table oTable = null;
                foreach (RashGodSO so in sortedList)
                {
                    if (namePositionFactoryInWord == null || namePositionFactoryInWord != so.kodpost)
                    {
                        codeElement = "";
                        positionRows = 4;
                        string path = Directory.GetCurrentDirectory() + templateFileNameWordQueryResult;
                        wordDocument = oWord.Documents.Open(path);
                        //Поиск и замена текста
                        functionReplaceInText(wordDocument, oWord, "{uchNumber}", uchNumber);
                        if (uchNumber.Length > 0)
                            uchNumber = Convert.ToString(Convert.ToInt32(uchNumber) + 1);
                        functionReplaceInText(wordDocument, oWord, "{year}", yearPrintResult);
                        functionReplaceInText(wordDocument, oWord, "{nameQuery}", "специальных комплектующих изделий");
                        //Добавление в файл таблицы
                        label3.BeginInvoke((MethodInvoker)(() => this.label3.Text = "Печать: создание титульного листа"));
                        Object autoFitBehavior = Word.WdAutoFitBehavior.wdAutoFitWindow;
                        //Добавление в файл таблицы
                        var range = wordDocument.Paragraphs[wordDocument.Paragraphs.Count - 1].Range;
                        wordDocument.Tables.Add(range, 1, 9, autoFitBehavior);
                        oTable = wordDocument.Tables[1];
                        string[] nameHeaderTable = { "Наименование комплектующего изделия, код ОКП", "Организация поставщик, ИНН", "Наименование комплектуемого (ремонтируемого) изделия, код ОКП", "Организация потребитель, ИНН", "Единица измерения", "Стоимость единицы продукции в ценах на 01.01.2015г. (тыс.руб.)", "Объем поставок", "Стоимость продукции (тыс.руб.)", "Результилизационное задание применительно к условиям 2010 года (объем поставок)", "год", "1 кв", "2 кв", "3 кв", "4 кв" };
                        for (int i = 0; i < oTable.Columns.Count; i++)
                        {
                            Word.Range wordCellRange = oTable.Cell(1, i + 1).Range;
                            wordCellRange.ParagraphFormat.Alignment = Word.WdParagraphAlignment.wdAlignParagraphCenter;
                            wordCellRange.Text = nameHeaderTable[i];
                            wordCellRange.Font.Name = "Times New Roman";
                            wordCellRange.Font.Bold = 1;
                            wordCellRange.Font.Size = 11;
                        }
                        oTable.Rows.First.HeadingFormat = -1;
                        /*oTable.Cell(1, 1).Width = 35;
                        oTable.Cell(1, 2).Width = 120;
                        oTable.Cell(1, 3).Width = 60;
                        oTable.Cell(1, 4).Width = 80;
                        oTable.Cell(1, 5).Width = 50;
                        oTable.Cell(1, 6).Width = 320;
                        oTable.Cell(1, 7).Width = 120;*/
                        oTable.Cell(1, 7).Split(2, 1);
                        oTable.Cell(2, 7).Split(1, 5);
                        /*oTable.Cell(2, 6).Width = 80;
                        oTable.Cell(2, 7).Width = 60;
                        oTable.Cell(2, 8).Width = 60;
                        oTable.Cell(2, 9).Width = 60;
                        oTable.Cell(2, 10).Width = 60;
                        oTable.Cell(1, 7).Merge(oTable.Cell(2, 11));*/
                        for (int i = 7; i < 12; i++)
                        {
                            Word.Range wordCellRange = oTable.Cell(2, i).Range;
                            wordCellRange.ParagraphFormat.Alignment = Word.WdParagraphAlignment.wdAlignParagraphCenter;
                            wordCellRange.Text = nameHeaderTable[i + 2];
                            wordCellRange.Rows.HeadingFormat = -1;
                            wordCellRange.Font.Bold = 1;
                        }
                        oTable.Rows.Add();
                        for (int i = 1; i < 14; i++)
                        {
                            Word.Range wordCellRange = oTable.Cell(3, i).Range;
                            wordCellRange.ParagraphFormat.Alignment = Word.WdParagraphAlignment.wdAlignParagraphCenter;
                            wordCellRange.Text = Convert.ToString(i);
                            wordCellRange.Rows.HeadingFormat = -1;
                            wordCellRange.Font.Bold = 1;
                        }
                        namePositionFactoryInWord = so.kodpost;
                    }
                    label3.BeginInvoke((MethodInvoker)(() => this.label3.Text = "Печать: печать потребности " + printVKECount + " из " + soList.Count));
                    printVKECount++;
                    oTable.Rows.Add();
                    int positionNewRows = positionRows;
                    for (int j = 0; j < 13; j++)
                    {
                        Word.Range wordCellRange = oTable.Cell(positionRows, j + 1).Range;
                        wordCellRange.Rows.AllowBreakAcrossPages = 0;
                        wordCellRange.Rows.HeadingFormat = 0;
                        wordCellRange.Font.Bold = 0;
                        if (j == 0)
                        {
                            if (codeElement != so.kodel)
                                wordCellRange.Text = "     " + so.kodel + "\n" + so.snhertel + "\n" + so.snindizel + "\n" + so.snnaimel;
                            else
                                wordCellRange.Text = "(продолжение)";
                            wordCellRange.ParagraphFormat.Alignment = Word.WdParagraphAlignment.wdAlignParagraphLeft;
                        }
                        if (j == 1)
                        {
                            wordCellRange.Text = so.kodpost + "\n" + so.nzavpost + ",\nИНН\n" + so.innpost;
                        }
                        if (j == 2)
                        {
                            wordCellRange = oTable.Cell(positionNewRows, j + 1).Range;
                            wordCellRange.Font.Bold = 0;
                            wordCellRange.Text = "     " + so.kodil + "\n" + so.snhertil + "\n" + so.snindizil + "\n" + so.snnaimil;
                            wordCellRange.ParagraphFormat.Alignment = Word.WdParagraphAlignment.wdAlignParagraphLeft;
                            wordCellRange = oTable.Cell(positionNewRows, j + 2).Range;
                            wordCellRange.Font.Bold = 0;
                            wordCellRange.Rows.HeadingFormat = 0;
                            wordCellRange.Text = "ФКП \"НТИИМ\" г. Нижний Тагил\nИНН\n6668000472 (для испытаний " + so.nzavotr + ")";
                        }
                        if (j == 4)
                            wordCellRange.Text = so.naimeiz;
                        if (j == 5)
                            wordCellRange.Text = "";
                        if (j == 6)
                            if (codeElement != so.kodel)
                                wordCellRange.Text = Convert.ToString(so.qptg);
                        if (j == 7)
                            if (codeElement != so.kodel)
                                wordCellRange.Text = Convert.ToString(so.qpt1);
                        if (j == 8)
                            if (codeElement != so.kodel)
                                wordCellRange.Text = Convert.ToString(so.qpt2);
                        if (j == 9)
                            if (codeElement != so.kodel)
                                wordCellRange.Text = Convert.ToString(so.qpt3);
                        if (j == 10)
                            if (codeElement != so.kodel)
                                wordCellRange.Text = Convert.ToString(so.qpt4);
                        if (j == 11)
                            wordCellRange.Text = "";
                        if (j == 12)
                            wordCellRange.Text = "";
                    }
                    if (codeElement != so.kodel)
                        codeElement = so.kodel;
                    positionRows = positionNewRows;
                    positionRows++;
                    bool proverkaEndList = false;
                    if (positionInSortedList < sortedList.Count() - 1)
                    {
                        if (sortedList.ToArray()[positionInSortedList + 1].kodpost != namePositionFactoryInWord)
                        {
                            proverkaEndList = true;
                        }
                    }
                    else
                    {
                        proverkaEndList = true;
                    }
                    if (proverkaEndList)
                    {
                        wordDocument.Paragraphs.Add();
                        Word.Range endParagraph = wordDocument.GoTo(Word.WdGoToItem.wdGoToLine, Word.WdGoToDirection.wdGoToLast);
                        endParagraph.Text = "Генеральный директор ФКП \"НТИИМ\"\t\t\t\t\t\tН.П.Смирнов\nСОГЛАСОВАНО:\n";
                        label3.BeginInvoke((MethodInvoker)(() => this.label3.Text = "Печать: создание экземпляров"));
                        //Создание экземпляров
                        label3.BeginInvoke((MethodInvoker)(() => this.label3.Text = "Информация: создание экземпляров"));
                        wordDocument.Range().Copy();
                        var rangeAllDocumentEkz = wordDocument.Content;
                        rangeAllDocumentEkz.Find.ClearFormatting();
                        //for (int j = 0; j < soList.Count; j++)
                        {
                            functionReplaceInText(wordDocument, oWord, "{numberEkz}", "1");
                        }
                        for (int i = 0; i < Convert.ToInt32(countEkz) - 1; i++)
                        {
                            //В конец документа
                            object what = Word.WdGoToItem.wdGoToLine;
                            object which = Word.WdGoToDirection.wdGoToLast;
                            Word.Range endRange = wordDocument.GoTo(ref what, ref which);
                            //Создаем разрыв РАЗДЕЛА (не страниц)
                            endRange.InsertBreak(Word.WdBreakType.wdSectionBreakNextPage);
                            //Вставляем
                            endRange.Paste();
                            rangeAllDocumentEkz = wordDocument.Content;
                            rangeAllDocumentEkz.Find.ClearFormatting();
                            rangeAllDocumentEkz.Find.Execute(FindText: "{numberEkz}", ReplaceWith: i + 2);
                        }
                        label3.BeginInvoke((MethodInvoker)(() => this.label3.Text = "Печать: сохранение документа"));

                        //Очистка буфера
                        Clipboard.Clear();
                        //Сохранение
                        label3.BeginInvoke((MethodInvoker)(() => this.label3.Text = "Информация: сохранение"));
                        DateTime dateNowFileName = DateTime.Now;
                        string strSaveName = Convert.ToString(dateNowFileName);
                        strSaveName = strSaveName.Replace(':', '-');
                        if (!Directory.Exists(Properties.Settings.Default.PathForReports))
                            Directory.CreateDirectory(Directory.GetCurrentDirectory() + "/reports");
                        wordDocument.SaveAs(Properties.Settings.Default.PathForReports + "/"+Regex.Replace(so.kodpost, @"[^\w\.@-]", "") +" Заявка" + " " + strSaveName + ".docx");
                        wordDocument.Close();

                        if (DialogResult.Yes == MessageBox.Show("Вы желаете распечатать письмо?", "Подтверждение", MessageBoxButtons.YesNo, MessageBoxIcon.Question))
                            printMessageQuery(Regex.Replace(so.nzavpost, @"[^\w\.@-]", ""));
                    }
                    positionInSortedList++;
                }
            }
            catch
            {
                MessageBox.Show("Ошибка!");
            }
            finally
            {
                oWord.Quit();
            }
        }

        private void printMessageQuery(string nameFactoryQuery)
        {
            //Количество экземпляров
            string countEkz = "1";
            //Учетный номер
            string uchNumber = "123";
            //WORD
            label3.BeginInvoke((MethodInvoker)(() => this.label3.Text = "Информация: печать письма"));
            Word.Application oWord = new Word.Application();
            oWord.Visible = false;
            try
            {
                //Открытие документа
                Word.Document wordDocument;
                wordDocument = oWord.Documents.Open(templateFileNameWordApplicationResult);
                //Поиск и замена текста
                functionReplaceInText(wordDocument, oWord, "{uchNumber}", uchNumber);
                functionReplaceInText(wordDocument, oWord, "{year}", "2020");
                //Замена основного текста
                label3.BeginInvoke((MethodInvoker)(() => this.label3.Text = "Информация: создание письма"));

                //Создание экземпляров
                label3.BeginInvoke((MethodInvoker)(() => this.label3.Text = "Печать: создание экземпляров"));
                wordDocument.Range().Copy();
                var rangeAllDocumentEkz = wordDocument.Content;
                rangeAllDocumentEkz.Find.ClearFormatting();
                //for (int j = 0; j < plIspResultList.Count; j++)
                {
                    functionReplaceInText(wordDocument, oWord, "{numberEkz}", "1");
                }
                for (int i = 0; i < Convert.ToInt32(countEkz) - 1; i++)
                {
                    //В конец документа
                    object what = Word.WdGoToItem.wdGoToLine;
                    object which = Word.WdGoToDirection.wdGoToLast;
                    Word.Range endRange = wordDocument.GoTo(ref what, ref which);
                    //Создаем разрыв РАЗДЕЛА (не страниц)
                    endRange.InsertBreak(Word.WdBreakType.wdSectionBreakNextPage);
                    //Вставляем
                    endRange.Paste();
                    rangeAllDocumentEkz = wordDocument.Content;
                    rangeAllDocumentEkz.Find.ClearFormatting();
                    rangeAllDocumentEkz.Find.Execute(FindText: "{numberEkz}", ReplaceWith: i + 2);
                }
                //Сохранение
                label3.BeginInvoke((MethodInvoker)(() => this.label3.Text = "Информация: сохранение"));
                DateTime dateNowFileName = DateTime.Now;
                string strSaveName = Convert.ToString(dateNowFileName);
                strSaveName = strSaveName.Replace(':', '-');
                if (!Directory.Exists(Properties.Settings.Default.PathForReports))
                    Directory.CreateDirectory(Directory.GetCurrentDirectory() + "/reports");
                wordDocument.SaveAs(Properties.Settings.Default.PathForReports + "/" + nameFactoryQuery + " Письмо" + " " + strSaveName + ".docx");
                wordDocument.Close();
            }
            catch
            {
                MessageBox.Show("Ошибка при печати письма!");
            }
            finally
            {
                oWord.Quit();
            }
        }
        #endregion

        //Печать анализ готовности
        #region Результ АНАЛИЗ ГОТОВНОСТИ
        private void button27_Click(object sender, EventArgs e)
        {
            button27.BackColor = choseColorBTN;
            BlockAllButton();
            if (Properties.Settings.Default.OtraslPath.Length > 0)
            {
                if (Properties.Settings.Default.RashGodFolderPath.Length > 0)
                {
                    try
                    {
                        FormChoseUchAndCount fcuac = new FormChoseUchAndCount();
                        fcuac.ShowDialog();
                        if (fcuac.isExit)
                        {
                            label3.BeginInvoke((MethodInvoker)(() => this.label3.Text = "Информация: подготовка к печати"));
                            Thread thread = new Thread(() =>
                            {
                                try
                                {
                                    List<RashGodPlanIsp> planIspList = new List<RashGodPlanIsp>();
                                    //Получение плана испытаний
                                    planIspList = GetRashGodPlanIsp(planIspList);
                                    //Заполнение плана испытанйи
                                    planIspList = FillRashGodPlanIsp(planIspList);
                                    //Заполнение анализа готовности
                                    planIspList = FillRashGodAG(planIspList);
                                    //Печать анализа готовности
                                    PrintRashGodAG(planIspList, fcuac.uchNumber, fcuac.countEkz);
                                }
                                catch (Exception ex)
                                {
                                    MessageBox.Show("Очередная ошибка ин поток\n" + ex.Message + "\n" + ex.Source + "\n" + ex.StackTrace + "\n" + ex.TargetSite, "Ошибка", MessageBoxButtons.OK, MessageBoxIcon.Error);
                                }
                                finally
                                {
                                    BlockAllButton(false);
                                }
                            });
                            thread.SetApartmentState(ApartmentState.STA);
                            thread.Start();
                        }
                        else
                            BlockAllButton(false);
                    }
                    catch (Exception ex)
                    {
                        BlockAllButton(false);
                        MessageBox.Show("Очередная ошибка\n" + ex.Message + "\n" + ex.Source + "\n" + ex.StackTrace + "\n" + ex.TargetSite, "Ошибка", MessageBoxButtons.OK, MessageBoxIcon.Error);
                    }
                }
                else
                {
                    BlockAllButton(false);
                    MessageBox.Show("Выберите поддиректорию RASHGOD");
                }
            }
            else
            {
                BlockAllButton(false);
                MessageBox.Show("Выберите директорию OTRASL");
            }
        }

        private List<RashGodPlanIsp> FillRashGodAG(List<RashGodPlanIsp> planIspList, bool isFullRashet = true)
        {
            //Получение информации о комплектации
            label3.BeginInvoke((MethodInvoker)(() => this.label3.Text = "Информация: получение информации о комплектации"));
            string strConnection = @"Provider=Microsoft.JET.Oledb.4.0;Data Source=" + Properties.Settings.Default.OtraslPath + "/RAHGOD/VK;Extended Properties=dBASE IV;User ID=;Password=;";
            using (_connection = new OleDbConnection(strConnection))
            {
                _connection.Open();
                using (OleDbCommand cmd = _connection.CreateCommand())
                {
                    foreach (RashGodPlanIsp rgpi in planIspList)
                    {
                        cmd.CommandText = "SELECT * FROM VK.DBF WHERE KODEL='" + rgpi.kodel + "' AND SHVID='" + rgpi.shvid + "'";
                        using (OleDbDataReader reader = cmd.ExecuteReader())
                        {
                            while (reader.Read())
                            {
                                RashGodPlanIsp newMPI = new RashGodPlanIsp();
                                newMPI.kodel = Convert.ToString(reader["SHEL"]);
                                if (isFullRashet)
                                {
                                    newMPI.qolg = Convert.ToString(Convert.ToDouble(reader["KELIZ"]) * Convert.ToDouble(rgpi.qolg));
                                    double count = 0;
                                    if (double.TryParse(rgpi.qol1, out count))
                                    {
                                        newMPI.qol1 = Convert.ToString(Convert.ToDouble(reader["KELIZ"]) * count);
                                    }
                                    count = 0;
                                    if (double.TryParse(rgpi.qol2, out count))
                                    {
                                        newMPI.qol2 = Convert.ToString(Convert.ToDouble(reader["KELIZ"]) * count);
                                    }
                                    count = 0;
                                    if (double.TryParse(rgpi.qol3, out count))
                                    {
                                        newMPI.qol3 = Convert.ToString(Convert.ToDouble(reader["KELIZ"]) * count);
                                    }
                                    count = 0;
                                    if (double.TryParse(rgpi.qol4, out count))
                                    {
                                        newMPI.qol4 = Convert.ToString(Convert.ToDouble(reader["KELIZ"]) * count);
                                    }
                                    
                                }
                                else
                                    newMPI.qolg = Convert.ToString(reader["KELIZ"]);
                                newMPI.kodeiz = Convert.ToString(reader["KODEIZ"]);
                                newMPI.countOnOne = Convert.ToString(reader["KELIZ"]);
                                rgpi.agList.Add(newMPI);
                            }
                        }
                    }
                }
            }
            //Получение информации о элементе комплектации
            label3.BeginInvoke((MethodInvoker)(() => this.label3.Text = "Информация: получение информации о элементах"));
            strConnection = @"Provider=Microsoft.JET.Oledb.4.0;Data Source=" + Properties.Settings.Default.OtraslPath + "/SPNAIM;Extended Properties=dBASE IV;User ID=;Password=;";
            using (_connection = new OleDbConnection(strConnection))
            {
                _connection.Open();
                using (OleDbCommand cmd = _connection.CreateCommand())
                {
                    foreach (RashGodPlanIsp rgkpi in planIspList)
                    {
                        foreach (RashGodPlanIsp ag in rgkpi.agList)
                        {
                            if (ag.kodel != null && ag.kodel != "")
                            {
                                cmd.CommandText = "SELECT * FROM SPNAIM.DBF WHERE KODEL='" + ag.kodel + "'";
                                using (OleDbDataReader reader = cmd.ExecuteReader())
                                {
                                    while (reader.Read())
                                    {
                                        ag.snhert = Convert.ToString(reader["SNHERT"] ?? "");

                                        ag.snindiz = Convert.ToString(reader["SNINDIZ"] ?? "");

                                        ag.snnaim = Convert.ToString(reader["SNNAIM1"] ?? "") + "\n" + Convert.ToString(reader["SNNAIM2"] ?? "") + "\n" +
                                            Convert.ToString(reader["SNNAIM3"] ?? "") + "\n" + Convert.ToString(reader["SNNAIM4"] ?? "") + "\n" +
                                            Convert.ToString(reader["SNNAIM5"] ?? "") + "\n" + Convert.ToString(reader["SNNAIM6"] ?? "") + "\n" +
                                            Convert.ToString(reader["SNNAIM7"] ?? "") + "\n" + Convert.ToString(reader["SNNAIM8"] ?? "") + "\n" +
                                            Convert.ToString(reader["SNNAIM9"] ?? "") + "\n" + Convert.ToString(reader["SNNAIM10"] ?? "");
                                        ag.snnaim = ag.snnaim.TrimEnd('\n');
                                        ag.snnaim = ag.snnaim.Replace('*', '"');
                                    }
                                }
                            }
                        }
                    }
                }
            }
            try
            {
                //Получение кода поставщика
                label3.BeginInvoke((MethodInvoker)(() => this.label3.Text = "Информация: получение информации о поставщиках"));
                strConnection = @"Provider=Microsoft.JET.Oledb.4.0;Data Source=" + Properties.Settings.Default.OtraslPath + "/RAHGOD/SPRXZ;Extended Properties=dBASE IV;User ID=;Password=;";
                using (_connection = new OleDbConnection(strConnection))
                {
                    _connection.Open();
                    using (OleDbCommand cmd = _connection.CreateCommand())
                    {
                        foreach (RashGodPlanIsp rgpi in planIspList)
                        {
                            foreach (RashGodPlanIsp ag in rgpi.agList)
                            {
                                if (ag.kodel != null && ag.kodel != "")
                                {
                                    cmd.CommandText = "SELECT * FROM OBRASK.DBF WHERE KODEL='" + ag.kodel + "'";
                                    using (OleDbDataReader reader = cmd.ExecuteReader())
                                    {
                                        while (reader.Read())
                                        {
                                            ag.kodotr = Convert.ToString(reader["KODOTR"] ?? "");
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
            catch
            {
                MessageBox.Show("Ошибка при получении кода поставщика комплектации!");
            }
            //Получение информации о заводе
            label3.BeginInvoke((MethodInvoker)(() => this.label3.Text = "Информация: получение информации о заводах"));
            strConnection = @"Provider=Microsoft.JET.Oledb.4.0;Data Source=" + Properties.Settings.Default.OtraslPath + "/SPRXZ;Extended Properties=dBASE IV;User ID=;Password=;";
            using (_connection = new OleDbConnection(strConnection))
            {
                _connection.Open();
                using (OleDbCommand cmd = _connection.CreateCommand())
                {
                    foreach (RashGodPlanIsp rgpi in planIspList)
                    {
                        foreach (RashGodPlanIsp ag in rgpi.agList)
                        {
                            cmd.CommandText = "SELECT KODOTR, NZAV1, NZAV2, NZAV3, INN FROM SPRXZ.DBF WHERE KODOTR='" + ag.kodotr + "'";
                            using (OleDbDataReader reader = cmd.ExecuteReader())
                            {
                                while (reader.Read())
                                {
                                    ag.nzav = Convert.ToString(reader["NZAV1"]) + "\n" + Convert.ToString(reader["NZAV2"]) + "\n" + Convert.ToString(reader["NZAV3"]);
                                    ag.nzav = ag.nzav.TrimEnd('\n');
                                    ag.nzav = ag.nzav.Replace('*', '"');
                                    ag.inn = Convert.ToString(reader["INN"]);
                                }
                            }
                        }
                    }
                }
            }
            //Получение информации о ед. измерения
            label3.BeginInvoke((MethodInvoker)(() => this.label3.Text = "Информация: получение информации о ед. измерений"));
            strConnection = @"Provider=Microsoft.JET.Oledb.4.0;Data Source=" + Properties.Settings.Default.OtraslPath + "/SPRAV;Extended Properties=dBASE IV;User ID=;Password=;";
            using (_connection = new OleDbConnection(strConnection))
            {
                _connection.Open();
                using (OleDbCommand cmd = _connection.CreateCommand())
                {
                    foreach (RashGodPlanIsp rgpi in planIspList)
                    {
                        foreach (RashGodPlanIsp ag in rgpi.agList)
                        {
                            cmd.CommandText = "SELECT * FROM SPEIZ2 WHERE KODEIZ='" + ag.kodeiz + "'";
                            using (OleDbDataReader reader = cmd.ExecuteReader())
                            {
                                while (reader.Read())
                                {
                                    ag.naimeiz = Convert.ToString(reader["NAIMEIZ"]);
                                    ag.naik = Convert.ToString(reader["NAIK"]);
                                }
                            }
                        }
                    }
                }
            }
            return planIspList;
        }

        private void PrintRashGodAG(List<RashGodPlanIsp> planIspList, string uchNumber, string countEkz)
        {
            label3.BeginInvoke((MethodInvoker)(() => this.label3.Text = "Информация: подготовка к печати"));
            //Сортируем анализ готовности
            var sortedList = planIspList.OrderBy(a => a.kodotr).ThenBy(b => b.kodel).ThenBy(c => c.shvid);
            //Создаем документ
            Word.Application oWord = new Word.Application();
            oWord.Visible = checkBox3.Checked;
            try
            {
                //Открытие документа
                Word.Document wordDocument;
                string path = Directory.GetCurrentDirectory() + templateFileNameWordRashGodAG;
                //MessageBox.Show("Путь: " + path);
                wordDocument = oWord.Documents.Open(path);
                //Поиск и замена текста
                functionReplaceInText(wordDocument, oWord, "{uchNumber}", uchNumber);
                functionReplaceInText(wordDocument, oWord, "{year}", "2020");
                //Добавление в файл таблицы
                Object autoFitBehavior = Word.WdAutoFitBehavior.wdAutoFitWindow;
                //Добавление в файл таблицы
                var range = wordDocument.Paragraphs[wordDocument.Paragraphs.Count - 1].Range;
                wordDocument.Tables.Add(range, 1, 11, autoFitBehavior);
                Word.Table oTable = wordDocument.Tables[1];
                //Запрет на перенос строк
                oTable.Rows.AllowBreakAcrossPages = 0;
                string[] nameHeaderTable = {
                                               "№ п/п", 
                                               "Код и наименование\nиспытуемого элемента (ИЭ)",
                                               "Код и\nнаименование\nвида испытания", 
                                               "Код и наименованеи\nзавода-\nизготовителя ИЭ", 
                                               "Объём\nпоставок",
                                               "Готовность",
                                               "Код и наименование\nкомплектующего элемента",
                                               "Код/\nЕд.\nизм.",
                                               "Объём\nпотребности КЭ",
                                               "Код,наименование и\nместонахождение\nорганизации-\nпоставщика\nИНН",
                                               "Документ\nподтверждающий\nобеспеченность КЭ"
                                           };
                for (int i = 0; i < oTable.Columns.Count; i++)
                {
                    Word.Range wordCellRange = oTable.Cell(1, i + 1).Range;
                    wordCellRange.ParagraphFormat.Alignment = Word.WdParagraphAlignment.wdAlignParagraphCenter;
                    wordCellRange.Text = nameHeaderTable[i];
                    wordCellRange.Font.Name = "Times New Roman";
                    wordCellRange.Font.Size = 10;
                }
                //Повтор заголовков
                oTable.Rows[1].HeadingFormat = -1;
                //Попытка вертикально центрирования заголовка
                oTable.Rows[1].Cells.VerticalAlignment = Word.WdCellVerticalAlignment.wdCellAlignVerticalCenter;
                //Вывод
                int numberPosition = 1;
                int posRows = 2;
                foreach (RashGodPlanIsp rgpi in sortedList)
                {
                    label3.BeginInvoke((MethodInvoker)(() => this.label3.Text = "Информация: печать элемента " + numberPosition + " из " + planIspList.Count));
                    oTable.Rows.Add();
                    //Порядковый номер
                    Word.Range wordCellRange = oTable.Cell(posRows, 1).Range;
                    wordCellRange.Text = Convert.ToString(numberPosition);
                    wordCellRange.Cells.VerticalAlignment = Word.WdCellVerticalAlignment.wdCellAlignVerticalCenter;
                    //Испытуемый элемент
                    wordCellRange = oTable.Cell(posRows, 2).Range;
                    if (!string.IsNullOrWhiteSpace(rgpi.kodel))
                        wordCellRange.Text = "Код " + Convert.ToString(rgpi.kodel) + "\n\n" + rgpi.snhert + "\n" + rgpi.snindiz + "\n" + rgpi.snnaim;
                    else
                        wordCellRange.Text = Convert.ToString(rgpi.kodel) + "\n\n" + rgpi.snhert + "\n" + rgpi.snindiz + "\n" + rgpi.snnaim;
                    wordCellRange.ParagraphFormat.Alignment = Word.WdParagraphAlignment.wdAlignParagraphLeft;
                    wordCellRange.Cells.VerticalAlignment = Word.WdCellVerticalAlignment.wdCellAlignVerticalTop;
                    //Вид испытания
                    wordCellRange = oTable.Cell(posRows, 3).Range;
                    if (!string.IsNullOrWhiteSpace(rgpi.shvid))
                        wordCellRange.Text = "Код " + Convert.ToString(rgpi.shvid) + "\n\n\n\n" + rgpi.nvid;
                    else
                        wordCellRange.Text = Convert.ToString(rgpi.shvid) + "\n\n\n\n" + rgpi.nvid;
                    wordCellRange.Cells.VerticalAlignment = Word.WdCellVerticalAlignment.wdCellAlignVerticalTop;
                    //Завод-изготовитель ИЭ
                    wordCellRange = oTable.Cell(posRows, 4).Range;
                    if (!string.IsNullOrWhiteSpace(rgpi.kodotr))
                        wordCellRange.Text = "Код " + Convert.ToString(rgpi.kodotr) + "\n\n\n\n" + rgpi.nzav;
                    else
                        wordCellRange.Text = Convert.ToString(rgpi.kodotr) + "\n\n\n\n" + rgpi.nzav;
                    wordCellRange.Cells.VerticalAlignment = Word.WdCellVerticalAlignment.wdCellAlignVerticalTop;
                    //Объём поставок
                    wordCellRange = oTable.Cell(posRows, 5).Range;
                    wordCellRange.Text = "\n\n\n\n" + Convert.ToString(rgpi.qolg);
                    wordCellRange.Cells.VerticalAlignment = Word.WdCellVerticalAlignment.wdCellAlignVerticalTop;
                    //Готовность

                    //Комплектующие элементы
                    bool isNewRow = false;
                    foreach (RashGodPlanIsp ag in rgpi.agList)
                    {
                        if (isNewRow)
                        {
                            oTable.Rows.Add();
                            posRows++;
                        }
                        //Комплектующий элемент
                        wordCellRange = oTable.Cell(posRows, 7).Range;
                        if (!string.IsNullOrWhiteSpace(ag.kodel))
                            wordCellRange.Text = "Код " + Convert.ToString(ag.kodel) + "\n\n" + ag.snhert + "\n" + ag.snindiz + "\n" + ag.snnaim;
                        else
                            wordCellRange.Text = Convert.ToString(ag.kodel) + "\n\n" + ag.snhert + "\n" + ag.snindiz + "\n" + ag.snnaim;
                        wordCellRange.ParagraphFormat.Alignment = Word.WdParagraphAlignment.wdAlignParagraphLeft;
                        wordCellRange.Cells.VerticalAlignment = Word.WdCellVerticalAlignment.wdCellAlignVerticalTop;
                        //Ед. измерения.
                        wordCellRange = oTable.Cell(posRows, 8).Range;
                        if (!string.IsNullOrWhiteSpace(ag.kodeiz))
                            wordCellRange.Text = "Код " + Convert.ToString(ag.kodeiz) + "\n\n\n\n" + Convert.ToString(ag.naimeiz);
                        else
                            wordCellRange.Text = Convert.ToString(ag.kodeiz) + "\n\n\n\n" + Convert.ToString(ag.naimeiz);
                        wordCellRange.Cells.VerticalAlignment = Word.WdCellVerticalAlignment.wdCellAlignVerticalTop;
                        //Объём потребности
                        wordCellRange = oTable.Cell(posRows, 9).Range;
                        wordCellRange.Text = "\n\n\n\n" + Convert.ToString(ag.qolg);
                        wordCellRange.Cells.VerticalAlignment = Word.WdCellVerticalAlignment.wdCellAlignVerticalTop;
                        //Организация-поставщик
                        wordCellRange = oTable.Cell(posRows, 10).Range;
                        if (!string.IsNullOrWhiteSpace(ag.kodotr))
                            wordCellRange.Text = "Код " + Convert.ToString(ag.kodotr) + "\n\n\n\n" + ag.nzav + "\n" + ag.inn;
                        else
                            wordCellRange.Text = Convert.ToString(ag.kodotr) + "\n\n\n\n" + ag.nzav + "\n" + ag.inn;
                        wordCellRange.Cells.VerticalAlignment = Word.WdCellVerticalAlignment.wdCellAlignVerticalTop;
                        //Документ подтверждающий готовность

                        isNewRow = true;
                    }
                    numberPosition++;
                    posRows++;
                }
                //Создание экземпляров
                label3.BeginInvoke((MethodInvoker)(() => this.label3.Text = "Информация: создание экземпляров"));
                wordDocument.Range().Copy();
                var rangeAllDocumentEkz = wordDocument.Content;
                rangeAllDocumentEkz.Find.ClearFormatting();
                //for (int j = 0; j < planIspList.Count; j++)
                {
                    functionReplaceInText(wordDocument, oWord, "{numberEkz}", "1");
                }
                for (int i = 0; i < Convert.ToInt32(countEkz) - 1; i++)
                {
                    //В конец документа
                    object what = Word.WdGoToItem.wdGoToLine;
                    object which = Word.WdGoToDirection.wdGoToLast;
                    Word.Range endRange = wordDocument.GoTo(ref what, ref which);
                    //Создаем разрыв РАЗДЕЛА (не страниц)
                    endRange.InsertBreak(Word.WdBreakType.wdSectionBreakNextPage);
                    //Вставляем
                    endRange.Paste();
                    rangeAllDocumentEkz = wordDocument.Content;
                    rangeAllDocumentEkz.Find.ClearFormatting();
                    rangeAllDocumentEkz.Find.Execute(FindText: "{numberEkz}", ReplaceWith: i + 2);
                }
                //Очистка буфера
                Clipboard.Clear();
                //Сохранение
                label3.BeginInvoke((MethodInvoker)(() => this.label3.Text = "Информация: сохранение"));
                DateTime dateNowFileName = DateTime.Now;
                string strSaveName = Convert.ToString(dateNowFileName);
                strSaveName = strSaveName.Replace(':', '-');
                if (!Directory.Exists(Properties.Settings.Default.PathForReports))
                    Directory.CreateDirectory(Directory.GetCurrentDirectory() + "/reports");
                wordDocument.SaveAs(Properties.Settings.Default.PathForReports + "/Результ анализ готовности" + " " + strSaveName + ".docx");
                wordDocument.Close();
                oWord.Quit();
                label3.BeginInvoke((MethodInvoker)(() => this.label3.Text = "Информация: анализ готовности создан"));
                MessageBox.Show("Результ анализ готовности создан!");
            }
            catch (Exception ex)
            {
                oWord.Visible = true;
                label2.BeginInvoke((MethodInvoker)(() => this.label2.Text = "Информация: ошибка!"));
                MessageBox.Show("error: " + ex.Message);
            }
        }
        #endregion


        //---------МЕСЯЧНЫЙ РАСЧЁТ---------
        //Печать плана испытаний
        #region МЕСЯЧНЫЙ ПЛАН ИСПЫТАНИЙ
        private void button6_Click(object sender, EventArgs e)
        {
            button6.BackColor = choseColorBTN;
            BlockAllButton();
            if (Properties.Settings.Default.OtraslMesplPath.Length > 0)
            {
                if (Properties.Settings.Default.OtraslPath.Length > 0)
                {
                    if (Properties.Settings.Default.RashGodFolderPath.Length > 0)
                    {
                        try
                        {
                            FormChoseMespl fcm = new FormChoseMespl();
                            fcm.ShowDialog();
                            if (fcm.isExit)
                            {
                                label1.BeginInvoke((MethodInvoker)(() => this.label1.Text = "Информация: подготовка к печати"));
                                Thread thread = new Thread(() =>
                                {
                                    try
                                    {
                                        List<MesplPlanIsp> planIspList = new List<MesplPlanIsp>();
                                        //Получение плана испытаний
                                        planIspList = GetMesplPlanIsp(planIspList, fcm.mesac, fcm.godr);
                                        //Заполнение плана испытанйи
                                        planIspList = FillMesplPlanIsp(planIspList);
                                        //Печать сводного плана испытаний
                                        string savePath = null;
                                        string savePath2 = null;
                                        string countList = null;
                                        PrintMesplPlanIsp(planIspList, fcm.uchNumber, fcm.countEkz, fcm.nameMesac, fcm.godr, out countList, out savePath);
                                        //Создание уголка
                                        FormChoseUgolok fcu = new FormChoseUgolok(fcm.uchNumber, fcm.countEkz, countList);
                                        fcu.ShowDialog();
                                        if (fcu.isExit)
                                            CreateUgolok(fcu.uchNumber, fcu.countEkz, fcu.countList, fcu.HMD, fcu.author, fcu.date, out savePath2, label1);
                                        //Открытие документов
                                        if (checkBox3.Checked)
                                        {
                                            OpenTheDocument(savePath, label1);
                                            OpenTheDocument(savePath2, label1);
                                        }
                                    }
                                    catch (Exception ex)
                                    {
                                        MessageBox.Show("Очередная ошибка ин поток\n" + ex.Message + "\n" + ex.Source + "\n" + ex.StackTrace + "\n" + ex.TargetSite, "Ошибка", MessageBoxButtons.OK, MessageBoxIcon.Error);
                                    }
                                    finally
                                    {
                                        BlockAllButton(false);
                                    }
                                });
                                thread.SetApartmentState(ApartmentState.STA);
                                thread.Start();
                            }
                            else
                                BlockAllButton(false);
                        }
                        catch (Exception ex)
                        {
                            BlockAllButton(false);
                            MessageBox.Show("Очередная ошибка\n" + ex.Message + "\n" + ex.Source + "\n" + ex.StackTrace + "\n" + ex.TargetSite, "Ошибка", MessageBoxButtons.OK, MessageBoxIcon.Error);
                        }
                    }
                    else
                    {
                        BlockAllButton(false);
                        MessageBox.Show("Выберите поддиректорию RAHGOD");
                    }
                }
                else
                {
                    BlockAllButton(false);
                    MessageBox.Show("Выберите директорию OTRASL");
                }
            }
            else
            {
                BlockAllButton(false);
                MessageBox.Show("Выберите директорию OTRASL1");
            }
        }

        private List<MesplPlanIsp> GetMesplPlanIsp(List<MesplPlanIsp> planIspList, string mesac, string godr)
        {
            planIspList.Clear();
            //MessageBox.Show("Получаем план");
            label1.BeginInvoke((MethodInvoker)(() => this.label1.Text = "Информация: получение плана"));
            string strConnection = @"Provider=Microsoft.JET.Oledb.4.0;Data Source=" + Properties.Settings.Default.OtraslMesplPath + "/MESPL/PLAN;Extended Properties=dBASE IV;User ID=;Password=;";
            using (_connection = new OleDbConnection(strConnection))
            {
                _connection.Open();
                using (OleDbCommand cmd = _connection.CreateCommand())
                {
                    cmd.CommandText = "SELECT * FROM PLANM.DBF WHERE MESAC='" + mesac + "' AND GODR='" + godr + "'";
                    using (OleDbDataReader reader = cmd.ExecuteReader())
                    {
                        while (reader.Read())
                        {
                            MesplPlanIsp isp = new MesplPlanIsp();
                            isp.kodel = Convert.ToString(reader["KODEL"]);
                            isp.kodotr = Convert.ToString(reader["KODOTR"]);
                            isp.shvid = Convert.ToString(reader["SHVID"]);
                            isp.npart = Convert.ToString(reader["NPART"]);
                            isp.vist = Convert.ToString(reader["VIST"]);
                            isp.dpost = Convert.ToString(reader["DPOST"]);
                            isp.mesac = Convert.ToString(reader["MESAC"]);
                            isp.godr = Convert.ToString(reader["GODR"]);
                            isp.prim = Convert.ToString(reader["PRIM"]);
                            planIspList.Add(isp);
                        }
                    }
                }
            }
            //MessageBox.Show("План получен");
            return planIspList;
        }

        private List<MesplPlanIsp> FillMesplPlanIsp(List<MesplPlanIsp> planIspList)
        {
            //MessageBox.Show("Заполняем");
            //Получение информации о элементе
            label1.BeginInvoke((MethodInvoker)(() => this.label1.Text = "Информация: получение информации о элементах"));
            string strConnection = @"Provider=Microsoft.JET.Oledb.4.0;Data Source=" + Properties.Settings.Default.OtraslPath + "/SPNAIM;Extended Properties=dBASE IV;User ID=;Password=;";
            using (_connection = new OleDbConnection(strConnection))
            {
                _connection.Open();
                using (OleDbCommand cmd = _connection.CreateCommand())
                {
                    foreach (MesplPlanIsp isp in planIspList)
                    {
                        if (isp.kodel != null && isp.kodel != "")
                        {
                            cmd.CommandText = "SELECT * FROM SPNAIM.DBF WHERE KODEL='" + isp.kodel + "'";
                            using (OleDbDataReader reader = cmd.ExecuteReader())
                            {
                                while (reader.Read())
                                {
                                    isp.snhert = Convert.ToString(reader["SNHERT"] ?? "");

                                    isp.snindiz = Convert.ToString(reader["SNINDIZ"] ?? "");

                                    isp.snnaim = Convert.ToString(reader["SNNAIM1"] ?? "") + "\n" + Convert.ToString(reader["SNNAIM2"] ?? "") + "\n" +
                                        Convert.ToString(reader["SNNAIM3"] ?? "") + "\n" + Convert.ToString(reader["SNNAIM4"] ?? "") + "\n" +
                                        Convert.ToString(reader["SNNAIM5"] ?? "") + "\n" + Convert.ToString(reader["SNNAIM6"] ?? "") + "\n" +
                                        Convert.ToString(reader["SNNAIM7"] ?? "") + "\n" + Convert.ToString(reader["SNNAIM8"] ?? "") + "\n" +
                                        Convert.ToString(reader["SNNAIM9"] ?? "") + "\n" + Convert.ToString(reader["SNNAIM10"] ?? "");
                                    isp.snnaim = isp.snnaim.TrimEnd('\n');
                                    isp.snnaim = isp.snnaim.Replace('*', '"');
                                }
                            }
                        }
                        else
                            MessageBox.Show("В плане элемент без кода!", "Косяк", MessageBoxButtons.OK, MessageBoxIcon.Error);
                    }
                }
            }
            //Получение информации о заводе
            label1.BeginInvoke((MethodInvoker)(() => this.label1.Text = "Информация: получение информации о заводах"));
            strConnection = @"Provider=Microsoft.JET.Oledb.4.0;Data Source=" + Properties.Settings.Default.OtraslPath + "/SPRXZ;Extended Properties=dBASE IV;User ID=;Password=;";
            using (_connection = new OleDbConnection(strConnection))
            {
                _connection.Open();
                using (OleDbCommand cmd = _connection.CreateCommand())
                {
                    foreach (MesplPlanIsp isp in planIspList)
                    {
                        cmd.CommandText = "SELECT KODOTR, NZAV1, NZAV2, NZAV3, INN FROM SPRXZ.DBF WHERE KODOTR='" + isp.kodotr + "'";
                        using (OleDbDataReader reader = cmd.ExecuteReader())
                        {
                            while (reader.Read())
                            {
                                isp.nzav = Convert.ToString(reader["NZAV1"]) + "\n" + Convert.ToString(reader["NZAV2"]) + "\n" + Convert.ToString(reader["NZAV3"]);
                                isp.nzav = isp.nzav.TrimEnd('\n');
                                isp.nzav = isp.nzav.Replace('*', '"');
                                isp.inn = Convert.ToString(reader["INN"]);
                            }
                        }
                    }
                }
            }
            //Получение информации о виде
            label1.BeginInvoke((MethodInvoker)(() => this.label1.Text = "Информация: получение информации о видах испытаний"));
            strConnection = @"Provider=Microsoft.JET.Oledb.4.0;Data Source=" + Properties.Settings.Default.OtraslPath + "/SPRAV;Extended Properties=dBASE IV;User ID=;Password=;";
            using (_connection = new OleDbConnection(strConnection))
            {
                _connection.Open();
                using (OleDbCommand cmd = _connection.CreateCommand())
                {
                    foreach (MesplPlanIsp isp in planIspList)
                    {
                        cmd.CommandText = "SELECT * FROM SPVID.DBF WHERE SHVID='" + isp.shvid + "'";
                        using (OleDbDataReader reader = cmd.ExecuteReader())
                        {
                            while (reader.Read())
                            {
                                isp.nvid = Convert.ToString(reader["NVID1"]) + "\n" + Convert.ToString(reader["NVID2"]);
                                isp.nvid = isp.nvid.TrimEnd('\n');
                            }
                        }
                    }
                }
            }
            //Получаем информацию о мишенной обстановки
            try
            {
                label1.BeginInvoke((MethodInvoker)(() => this.label1.Text = "Информация: получение информации о мишенях"));
                strConnection = @"Provider=Microsoft.JET.Oledb.4.0;Data Source=" + Properties.Settings.Default.OtraslMesplPath + "/MESPL/UFI;Extended Properties=dBASE IV;User ID=;Password=;";
                using (_connection = new OleDbConnection(strConnection))
                {
                    _connection.Open();
                    using (OleDbCommand cmd = _connection.CreateCommand())
                    {
                        foreach (MesplPlanIsp isp in planIspList)
                        {
                            cmd.CommandText = "SELECT KODEL,SHVID,PREG1,PREG2 FROM UFI.DBF WHERE KODEL='" + isp.kodel + "' AND SHVID='" + isp.shvid + "'";
                            using (OleDbDataReader reader = cmd.ExecuteReader())
                            {
                                while (reader.Read())
                                {
                                    isp.preg1 = Convert.ToString(reader["PREG1"]);
                                    isp.preg2 = Convert.ToString(reader["PREG2"]);
                                }
                            }
                        }
                    }
                }
            }
            catch(Exception ex)
            {
                MessageBox.Show("Ошибка при получении мишенной обстановки!" + ex.Message);
            }
            string errorString = "";
            try
            {
                strConnection = @"Provider=Microsoft.JET.Oledb.4.0;Data Source=" + Properties.Settings.Default.OtraslPath + "/SPRAV;Extended Properties=dBASE IV;User ID=;Password=;";
                using (_connection = new OleDbConnection(strConnection))
                {
                    _connection.Open();
                    using (OleDbCommand cmd = _connection.CreateCommand())
                    {
                        foreach (MesplPlanIsp isp in planIspList)
                        {
                            errorString += "Код: " + isp.kodel + "!    ";
                            if (isp.preg1 != "" && isp.preg1 != "000")
                            {
                                errorString += "преграда1: " + isp.preg1 + "   ";
                                if (isp.preg1[0] == '1')
                                    isp.npreg1 = "Бронеплита";
                                if (isp.preg1[0] == '2')
                                    isp.npreg1 = "Стальной лист";
                                if (isp.preg1[0] == '3')
                                    isp.npreg1 = "Дюралевый лист";
                                if (isp.preg1[0] == '4')
                                    isp.npreg1 = "Щит из досок";
                                if (isp.preg1[0] == '5')
                                    isp.npreg1 = "Щит из фанеры";
                                if (isp.preg1[0] == '6')
                                    isp.npreg1 = "Щит из картона";
                                if (isp.preg1[0] == '7')
                                    isp.npreg1 = "Ул-тель из БП";
                                isp.npreg1 += "\n";
                                cmd.CommandText = "SELECT * FROM SPREG.DBF WHERE PREG='" + isp.preg1 + "'";
                                using (OleDbDataReader reader = cmd.ExecuteReader())
                                {
                                    while (reader.Read())
                                    {
                                        isp.npreg1 += Convert.ToString(reader["NAIM"]);
                                    }
                                }
                            }
                            if (isp.preg2 != "" && isp.preg2 != "000")
                            {
                                errorString += "преграда2: " + isp.preg1 + "!   ";
                                if (isp.preg2[0] == '1')
                                    isp.npreg2 = "Бронеплита";
                                if (isp.preg2[0] == '2')
                                    isp.npreg2 = "Стальной лист";
                                if (isp.preg2[0] == '3')
                                    isp.npreg2 = "Дюралевый лист";
                                if (isp.preg2[0] == '4')
                                    isp.npreg2 = "Щит из досок";
                                if (isp.preg2[0] == '5')
                                    isp.npreg2 = "Щит из фанеры";
                                if (isp.preg2[0] == '6')
                                    isp.npreg2 = "Щит из картона";
                                if (isp.preg2[0] == '7')
                                    isp.npreg2 = "Ул-тель из БП";
                                isp.npreg2 += "\n";
                                cmd.CommandText = "SELECT * FROM SPREG.DBF WHERE PREG='" + isp.preg2 + "'";
                                using (OleDbDataReader reader = cmd.ExecuteReader())
                                {
                                    while (reader.Read())
                                    {
                                        isp.npreg2 += Convert.ToString(reader["NAIM"]);
                                    }
                                }
                            }
                        }
                    }
                }
            }
            catch(Exception ex)
            {
                MessageBox.Show("Ошибка при получении преград!\n(Скорее всего отсутствует УФИ)\n" + ex.Message + "\n" + errorString);
            }

            //Получаем коды мат части из форматок
            try
            {
                label1.BeginInvoke((MethodInvoker)(() => this.label1.Text = "Информация: получение информации о мишенях"));
                strConnection = @"Provider=Microsoft.JET.Oledb.4.0;Data Source=" + Properties.Settings.Default.OtraslMesplPath + "/MESPL/UFI;Extended Properties=dBASE IV;User ID=;Password=;";
                using (_connection = new OleDbConnection(strConnection))
                {
                    _connection.Open();
                    using (OleDbCommand cmd = _connection.CreateCommand())
                    {
                        foreach (MesplPlanIsp isp in planIspList)
                        {
                            cmd.CommandText = "SELECT KODEL,SHVID,SHSIS,SHSTV,SHSTD FROM UFI.DBF WHERE KODEL='" + isp.kodel + "' AND SHVID='" + isp.shvid + "'";
                            using (OleDbDataReader reader = cmd.ExecuteReader())
                            {
                                while (reader.Read())
                                {
                                    isp.shsis = Convert.ToString(reader["SHSIS"]);
                                    isp.shstv = Convert.ToString(reader["SHSTV"]);
                                    isp.shstd = Convert.ToString(reader["SHSTD"]);
                                }
                            }
                        }
                    }
                }
            }
            catch (Exception ex)
            {
                MessageBox.Show("Ошибка при получении мишенной обстановки!" + ex.Message);
            }

            //Получение информации о МЧ
            label2.BeginInvoke((MethodInvoker)(() => this.label2.Text = "Информация: получение информации о МЧ"));
            try
            {
                strConnection = @"Provider=Microsoft.JET.Oledb.4.0;Data Source=" + Properties.Settings.Default.OtraslPath + "/SPRAV;Extended Properties=dBASE IV;User ID=;Password=;";
                using (_connection = new OleDbConnection(strConnection))
                {
                    _connection.Open();
                    using (OleDbCommand cmd = _connection.CreateCommand())
                    {
                        foreach (MesplPlanIsp isp in planIspList)
                        {
                            if (isp.shsis != null)
                                if (isp.shsis != "")
                                {
                                    cmd.CommandText = "SELECT * FROM SPSIS.DBF WHERE SHSIS='" + isp.shsis + "'";
                                    using (OleDbDataReader reader = cmd.ExecuteReader())
                                    {
                                        while (reader.Read())
                                        {
                                            isp.nsis = Convert.ToString(reader["NSIS"]);
                                        }
                                    }
                                }
                            if (isp.shstv != null)
                                if (isp.shstv != "")
                                {
                                    cmd.CommandText = "SELECT * FROM SPSIS.DBF WHERE SHSIS='" + isp.shstv + "'";
                                    using (OleDbDataReader reader = cmd.ExecuteReader())
                                    {
                                        while (reader.Read())
                                        {
                                            isp.nstv = Convert.ToString(reader["NSIS"]);
                                        }
                                    }
                                }
                            if (isp.shstd != null)
                                if (isp.shstd != "")
                                {
                                    cmd.CommandText = "SELECT * FROM SPSIS.DBF WHERE SHSIS='" + isp.shstd + "'";
                                    using (OleDbDataReader reader = cmd.ExecuteReader())
                                    {
                                        while (reader.Read())
                                        {
                                            isp.nstd = Convert.ToString(reader["NSIS"]);
                                        }
                                    }
                                }
                        }
                    }
                }
            }
            catch (Exception ex)
            {
                MessageBox.Show("Что-то с МЧ!!!");
            }
            //MessageBox.Show("Вся инфа получена");
            return planIspList;
        }

        private void PrintMesplPlanIsp(List<MesplPlanIsp> planIspList, string uchNumber, string countEkz, string nameMesac, string yearPrintResult, out string countList, out string savePath)
        {
            savePath = null;
            countList = "";

            //MessageBox.Show("Печатаем план");
            label1.BeginInvoke((MethodInvoker)(() => this.label1.Text = "Информация: печать плана"));
            //Сортируем план испытаний
            var sortedList = planIspList.OrderBy(a => a.kodotr).ThenBy(b => b.kodel).ThenBy(c => c.shvid);

            //Создаем документ
            Word.Application oWord = new Word.Application();
            oWord.Visible = checkBox1.Checked;
            try
            {
                string path = Directory.GetCurrentDirectory() + templateFileNameWordControlPlan;
                //MessageBox.Show("Путь: " + path);
                Word.Document wordDocument = oWord.Documents.Open(path);

                //Поиск и замена текста
                functionReplaceInText(wordDocument, oWord, "{uchNumber}", uchNumber);
                functionReplaceInText(wordDocument, oWord, "{month}", nameMesac);
                functionReplaceInText(wordDocument, oWord, "{year}", yearPrintResult);

                //Добавление в файл таблицы
                wordDocument.Paragraphs.Add();
                Object autoFitBehavior = Word.WdAutoFitBehavior.wdAutoFitWindow;
                var range = wordDocument.Paragraphs[wordDocument.Paragraphs.Count - 1].Range;
                wordDocument.Tables.Add(range, 1, 10, autoFitBehavior);
                Word.Table oTable = wordDocument.Tables[1];
                //Запрет на перенос строк
                oTable.Rows.AllowBreakAcrossPages = 0;
                string[] nameHeaderTable = { "№\nп/п", "Код и наименование\nиспытуемого\nэлемента", " Код и\nнаименование\nзавода-\nизготовителя", "Код и\nнаименование\nвида испытания", "Код и\nнаименование\nмат части", "Номер\nпартии", "Количество\nвыстрелов", "Планируемая\nдата\nпоставки", "Мишенная\nобстановка", "№, Дата\nГОСЗАКАЗА/\nПримечание" };
                for (int i = 0; i < oTable.Columns.Count; i++)
                {
                    Word.Range wordCellRange = oTable.Cell(1, i + 1).Range;
                    wordCellRange.ParagraphFormat.Alignment = Word.WdParagraphAlignment.wdAlignParagraphCenter;
                    wordCellRange.Text = nameHeaderTable[i];
                    wordCellRange.Font.Name = "Times New Roman";
                }
                //Повтор заголовка
                oTable.Rows.First.HeadingFormat = -1;
                //Вертикальное центрирование заголовка
                oTable.Rows[1].Cells.VerticalAlignment = Word.WdCellVerticalAlignment.wdCellAlignVerticalCenter;
                //Вывод
                int posRows = 2;
                List<string> oglavlenie = new List<string>();
                List<string> oglavlenieStr = new List<string>();
                string lastCodeZavod = "";
                foreach (MesplPlanIsp isp in sortedList)
                {
                    label1.BeginInvoke((MethodInvoker)(() => this.label1.Text = "Информация: печать плана " + (posRows-1) + " из " + planIspList.Count));
                    oTable.Rows.Add();
                    //Порядковый номер
                    Word.Range wordCellRange = oTable.Cell(posRows, 1).Range;
                    wordCellRange.Rows.HeadingFormat = 0;
                    wordCellRange.Text = Convert.ToString(posRows-1);
                    wordCellRange.Cells.VerticalAlignment = Word.WdCellVerticalAlignment.wdCellAlignVerticalCenter;
                    //Шифр, наименование элемента
                    wordCellRange = oTable.Cell(posRows, 2).Range;
                    wordCellRange.Rows.HeadingFormat = 0;
                    if(!string.IsNullOrWhiteSpace(isp.kodel))
                        wordCellRange.Text = "Код " + Convert.ToString(isp.kodel) + "\n\n" + isp.snhert + "\n" + isp.snindiz + "\n" + isp.snnaim;
                    else
                        wordCellRange.Text = Convert.ToString(isp.kodel) + "\n\n" + isp.snhert + "\n" + isp.snindiz + "\n" + isp.snnaim;
                    wordCellRange.ParagraphFormat.Alignment = Word.WdParagraphAlignment.wdAlignParagraphLeft;
                    wordCellRange.Cells.VerticalAlignment = Word.WdCellVerticalAlignment.wdCellAlignVerticalTop;
                    //Шифр, наименование завода
                    wordCellRange = oTable.Cell(posRows, 3).Range;
                    wordCellRange.Rows.HeadingFormat = 0;
                    if (!string.IsNullOrWhiteSpace(isp.kodotr))
                        wordCellRange.Text = "Код " + Convert.ToString(isp.kodotr) + "\n\n\n\n" + isp.nzav;
                    else
                        wordCellRange.Text = Convert.ToString(isp.kodotr) + "\n\n\n\n" + isp.nzav;
                    wordCellRange.Cells.VerticalAlignment = Word.WdCellVerticalAlignment.wdCellAlignVerticalTop;
                    //Шифр, наименование вида испытания
                    wordCellRange = oTable.Cell(posRows, 4).Range;
                    wordCellRange.Rows.HeadingFormat = 0;
                    if (!string.IsNullOrWhiteSpace(isp.shvid))
                        wordCellRange.Text = "Код " + Convert.ToString(isp.shvid) + "\n\n\n\n" + isp.nvid;
                    else
                        wordCellRange.Text = Convert.ToString(isp.shvid) + "\n\n\n\n" + isp.nvid;
                    wordCellRange.Cells.VerticalAlignment = Word.WdCellVerticalAlignment.wdCellAlignVerticalTop;

                    //Код, наименование мат части
                    wordCellRange = oTable.Cell(posRows, 5).Range;
                    wordCellRange.Rows.HeadingFormat = 0;
                    string fullSistema = "";
                    if (!string.IsNullOrWhiteSpace(isp.shsis) && isp.shsis != "000" && isp.shsis != "0000")
                        fullSistema = "Код " + Convert.ToString(isp.shsis) + "\n" + isp.nsis;
                    else if (!string.IsNullOrWhiteSpace(isp.shstv) && isp.shstv != "000" && isp.shstv != "0000")
                        fullSistema = "Код " + Convert.ToString(isp.shstv) + "\n" + isp.nstv;
                    else if (!string.IsNullOrWhiteSpace(isp.shstd) && isp.shstd != "000" && isp.shstd != "0000")
                        fullSistema = "Код " + Convert.ToString(isp.shstd) + "\n" + isp.nstd;
                    wordCellRange.Text = fullSistema;
                    wordCellRange.Cells.VerticalAlignment = Word.WdCellVerticalAlignment.wdCellAlignVerticalCenter;

                    //Номер партии
                    wordCellRange = oTable.Cell(posRows, 6).Range;
                    wordCellRange.Rows.HeadingFormat = 0;
                    wordCellRange.Text = Convert.ToString(isp.npart);
                    wordCellRange.Cells.VerticalAlignment = Word.WdCellVerticalAlignment.wdCellAlignVerticalCenter;
                    //Количество выстрелов
                    wordCellRange = oTable.Cell(posRows, 7).Range;
                    wordCellRange.Rows.HeadingFormat = 0;
                    wordCellRange.Text = Convert.ToString(isp.vist);
                    wordCellRange.Cells.VerticalAlignment = Word.WdCellVerticalAlignment.wdCellAlignVerticalCenter;
                    //Планируемая дата поставки
                    wordCellRange = oTable.Cell(posRows, 8).Range;
                    wordCellRange.Rows.HeadingFormat = 0;
                    wordCellRange.Text = Convert.ToString(isp.dpost);
                    wordCellRange.Cells.VerticalAlignment = Word.WdCellVerticalAlignment.wdCellAlignVerticalCenter;
                    //Мишенная обстановка
                    string nameMishen = "";
                    if (isp.npreg1 != "")
                    {
                        nameMishen += isp.npreg1 + "\n";
                    }
                    if (isp.npreg2 != "")
                    {
                        nameMishen += isp.npreg2;
                    }
                    wordCellRange = oTable.Cell(posRows, 9).Range;
                    wordCellRange.Rows.HeadingFormat = 0;
                    wordCellRange.Text = "\n" + nameMishen;
                    wordCellRange.Cells.VerticalAlignment = Word.WdCellVerticalAlignment.wdCellAlignVerticalCenter;
                    //Примечание
                    wordCellRange = oTable.Cell(posRows, 10).Range;
                    wordCellRange.Rows.HeadingFormat = 0;
                    wordCellRange.Text = Convert.ToString(isp.prim);
                    wordCellRange.Cells.VerticalAlignment = Word.WdCellVerticalAlignment.wdCellAlignVerticalCenter;

                    //Для оглавления
                    if (isp.kodotr != lastCodeZavod)
                    {
                        lastCodeZavod = isp.kodotr;
                        //oglavlenie.Add(isp.nzav.Replace("\n", "").Trim());
                        //oglavlenieStr.Add(wordDocument.ComputeStatistics(Word.WdStatistic.wdStatisticPages, false).ToString());

                        wordDocument.TablesOfAuthorities.MarkCitation(wordCellRange, isp.kodotr + "   " + isp.nzav.Replace("\n", " ").Trim(), isp.kodotr + "   " + isp.nzav.Replace("\n", " ").Trim(), Type.Missing, "1");
                    }
                    posRows++;
                }
                label1.BeginInvoke((MethodInvoker)(() => this.label1.Text = "Информация: создание оглавления"));
                //Оглавление
                wordDocument.Paragraphs.Add();
                range = wordDocument.Paragraphs[wordDocument.Paragraphs.Count].Range;
                range.InsertBreak();
                range = wordDocument.Paragraphs[wordDocument.Paragraphs.Count].Range;
                wordDocument.Paragraphs.Add();
                range = wordDocument.Paragraphs[wordDocument.Paragraphs.Count - 1].Range;
                wordDocument.Paragraphs.Add();
                range = wordDocument.Paragraphs[wordDocument.Paragraphs.Count - 1].Range;
                range.Bold = 1;
                range.Text = "Предприятие";
                wordDocument.Paragraphs.Add();
                wordDocument.Paragraphs.Add();
                range = wordDocument.Paragraphs[wordDocument.Paragraphs.Count - 1].Range;
                range.Bold = 0;
                wordDocument.TablesOfAuthorities.Add(range, "1", Passim: false, KeepEntryFormatting: true, IncludeCategoryHeader: false);
                wordDocument.TablesOfAuthorities[1].KeepEntryFormatting = true;
                wordDocument.TablesOfAuthorities[1].Passim = false;
                wordDocument.TablesOfAuthorities[1].TabLeader = Word.WdTabLeader.wdTabLeaderDots;

                /*oTableOglavlenie.Rows.AllowBreakAcrossPages = 0;
                string[] nameHeaderTable2 = { "№ п/п", "Предприятие", "Стр.", "", "№ п/п", "Предприятие", "Стр." };
                for (int i = 0; i < oTableOglavlenie.Columns.Count; i++)
                {
                    Word.Range wordCellRange = oTableOglavlenie.Cell(1, i + 1).Range;
                    wordCellRange.ParagraphFormat.Alignment = Word.WdParagraphAlignment.wdAlignParagraphCenter;
                    wordCellRange.Text = nameHeaderTable2[i];
                    wordCellRange.Font.Name = "Times New Roman";
                }
                oTableOglavlenie.Columns[1].Width = 20;
                oTableOglavlenie.Columns[2].Width = 300;
                oTableOglavlenie.Columns[3].Width = 20;
                oTableOglavlenie.Columns[4].Width = 70;
                oTableOglavlenie.Columns[5].Width = 20;
                oTableOglavlenie.Columns[6].Width = 300;
                oTableOglavlenie.Columns[7].Width = 20;
                //Повтор заголовка
                oTableOglavlenie.Rows.First.HeadingFormat = -1;
                //Вертикальное центрирование заголовка
                oTableOglavlenie.Rows[1].Cells.VerticalAlignment = Word.WdCellVerticalAlignment.wdCellAlignVerticalCenter;
                //Вывод оглавления
                int posOglavlenieRows = 3;
                for (int i = 0; i < oglavlenie.Count; i++)
                {
                    oTableOglavlenie.Rows.Add();
                    oTableOglavlenie.Rows.Add();
                    Word.Range wordCellRange = oTableOglavlenie.Cell(posOglavlenieRows, 1).Range;
                    wordCellRange.Text = Convert.ToString(i + 1);
                    wordCellRange = oTableOglavlenie.Cell(posOglavlenieRows, 2).Range;
                    wordCellRange.Text = oglavlenie[i];
                    wordCellRange = oTableOglavlenie.Cell(posOglavlenieRows, 3).Range;
                    wordCellRange.Text = oglavlenieStr[i];
                    posOglavlenieRows++;
                    posOglavlenieRows++;
                }
                oTableOglavlenie.Columns.Borders[Word.WdBorderType.wdBorderTop].LineStyle = Word.WdLineStyle.wdLineStyleNone;
                oTableOglavlenie.Columns.Borders[Word.WdBorderType.wdBorderLeft].LineStyle = Word.WdLineStyle.wdLineStyleNone;
                oTableOglavlenie.Columns.Borders[Word.WdBorderType.wdBorderBottom].LineStyle = Word.WdLineStyle.wdLineStyleNone;
                oTableOglavlenie.Columns.Borders[Word.WdBorderType.wdBorderRight].LineStyle = Word.WdLineStyle.wdLineStyleNone;
                oTableOglavlenie.Columns.Borders[Word.WdBorderType.wdBorderHorizontal].LineStyle = Word.WdLineStyle.wdLineStyleNone;
                oTableOglavlenie.Columns.Borders[Word.WdBorderType.wdBorderVertical].LineStyle = Word.WdLineStyle.wdLineStyleNone;*/

                //Количество листов для уголка
                countList = wordDocument.ComputeStatistics(Word.WdStatistic.wdStatisticPages, false).ToString();

                //Создание экземпляров
                label1.BeginInvoke((MethodInvoker)(() => this.label1.Text = "Информация: создание экземляров"));
                wordDocument.Range().Copy();
                var rangeAllDocumentEkz = wordDocument.Content;
                rangeAllDocumentEkz.Find.ClearFormatting();
                //for (int j = 0; j < planIspList.Count; j++)
                {
                    functionReplaceInText(wordDocument, oWord, "{numberEkz}", "1");
                }
                for (int i = 0; i < Convert.ToInt32(countEkz) - 1; i++)
                {
                    //В конец документа
                    object what = Word.WdGoToItem.wdGoToLine;
                    object which = Word.WdGoToDirection.wdGoToLast;
                    Word.Range endRange = wordDocument.GoTo(ref what, ref which);
                    //Создаем разрыв РАЗДЕЛА (не страниц)
                    endRange.InsertBreak(Word.WdBreakType.wdSectionBreakNextPage);
                    //Вставляем
                    endRange.Paste();
                    rangeAllDocumentEkz = wordDocument.Content;
                    rangeAllDocumentEkz.Find.ClearFormatting();
                    rangeAllDocumentEkz.Find.Execute(FindText: "{numberEkz}", ReplaceWith: i + 2);
                }
                //Очистка буфера
                Clipboard.Clear();
                //Сохранение
                label1.BeginInvoke((MethodInvoker)(() => this.label1.Text = "Информация: сохранение"));
                DateTime dateNowFileName = DateTime.Now;
                string strSaveName = Convert.ToString(dateNowFileName);
                strSaveName = strSaveName.Replace(':', '-');
                if (!Directory.Exists(Properties.Settings.Default.PathForReports))
                    Directory.CreateDirectory(Directory.GetCurrentDirectory() + "/reports");
                savePath = Properties.Settings.Default.PathForReports + "/Месячный план испытаний" + " " + strSaveName + ".docx";
                wordDocument.SaveAs(savePath);
                wordDocument.Close();
                oWord.Quit();
                label1.BeginInvoke((MethodInvoker)(() => this.label1.Text = "Информация: месячный план создан!"));
                MessageBox.Show("Месячный план создан!");
            }
            catch (Exception ex)
            {
                oWord.Visible = true;
                label1.BeginInvoke((MethodInvoker)(() => this.label1.Text = "Информация: ошибка при печати"));
                MessageBox.Show("error: " + ex.Message);
            }
        }
        #endregion

        //Печать плана испытаний НС
        #region МЕСЯЧНЫЙ ПЛАН ИСПЫТАНИЙ (НС)
        private void button10_Click(object sender, EventArgs e)
        {
            button10.BackColor = choseColorBTN;
            BlockAllButton();
            if (Properties.Settings.Default.OtraslMesplPath.Length > 0)
            {
                if (Properties.Settings.Default.OtraslPath.Length > 0)
                {
                    if (Properties.Settings.Default.RashGodFolderPath.Length > 0)
                    {
                        try
                        {
                            FormChoseMespl fcm = new FormChoseMespl();
                            fcm.ShowDialog();
                            if (fcm.isExit)
                            {
                                label1.BeginInvoke((MethodInvoker)(() => this.label1.Text = "Информация: подготовка к печати"));
                                Thread thread = new Thread(() =>
                                {
                                    try
                                    {
                                        List<MesplPlanIsp> planIspList = new List<MesplPlanIsp>();
                                        //Получение плана испытаний
                                        planIspList = GetMesplPlanIsp(planIspList, fcm.mesac, fcm.godr);
                                        //Заполнение плана испытанйи
                                        planIspList = FillMesplPlanIsp(planIspList);
                                        //Печать сводного плана испытаний
                                        string savePath = null;
                                        PrintMesplPlanIspNS(planIspList, fcm.uchNumber, fcm.countEkz, fcm.nameMesac, fcm.godr, out savePath);
                                        //Открытие документа
                                        if (checkBox3.Checked)
                                            OpenTheDocument(savePath, label1);
                                    }
                                    catch (Exception ex)
                                    {
                                        MessageBox.Show("Очередная ошибка ин поток\n" + ex.Message + "\n" + ex.Source + "\n" + ex.StackTrace + "\n" + ex.TargetSite, "Ошибка", MessageBoxButtons.OK, MessageBoxIcon.Error);
                                    }
                                    finally
                                    {
                                        BlockAllButton(false);
                                    }
                                });
                                thread.SetApartmentState(ApartmentState.STA);
                                thread.Start();
                            }
                            else
                                BlockAllButton(false);
                        }
                        catch (Exception ex)
                        {
                            BlockAllButton(false);
                            MessageBox.Show("Очередная ошибка\n" + ex.Message + "\n" + ex.Source + "\n" + ex.StackTrace + "\n" + ex.TargetSite, "Ошибка", MessageBoxButtons.OK, MessageBoxIcon.Error);
                        }
                    }
                    else
                    {
                        BlockAllButton(false);
                        MessageBox.Show("Выберите поддиректорию RAHGOD");
                    }
                }
                else
                {
                    BlockAllButton(false);
                    MessageBox.Show("Выберите директорию OTRASL");
                }
            }
            else
            {
                BlockAllButton(false);
                MessageBox.Show("Выберите директорию OTRASL1");
            }
        }

        private void PrintMesplPlanIspNS(List<MesplPlanIsp> planIspList, string uchNumber, string countEkz, string nameMesac, string yearPrintResult, out string savePath)
        {
            savePath = null;

            label1.BeginInvoke((MethodInvoker)(() => this.label1.Text = "Информация: подготовка к печати"));
            var sortedList = planIspList.OrderBy(a => a.kodotr).ThenBy(b => b.kodel).ThenBy(c => c.shvid);

            //Создаем документ
            Word.Application oWord = new Word.Application();
            oWord.Visible = checkBox1.Checked;
            try
            {
                string path = Directory.GetCurrentDirectory() + templateFileNameWordControlPlanNS;
                //MessageBox.Show("Путь: " + path);
                Word.Document wordDocument = oWord.Documents.Open(path);

                //Поиск и замена текста
                functionReplaceInText(wordDocument, oWord, "{uchNumber}", uchNumber);
                functionReplaceInText(wordDocument, oWord, "{month}", nameMesac);
                functionReplaceInText(wordDocument, oWord, "{year}", yearPrintResult);

                //Добавление в файл таблицы
                wordDocument.Paragraphs.Add();
                Object autoFitBehavior = Word.WdAutoFitBehavior.wdAutoFitWindow;
                var range = wordDocument.Paragraphs[wordDocument.Paragraphs.Count - 1].Range;
                wordDocument.Tables.Add(range, 1, 10, autoFitBehavior);
                Word.Table oTable = wordDocument.Tables[1];
                //Запрет на перенос строк
                oTable.Rows.AllowBreakAcrossPages = 0;
                string[] nameHeaderTable = { "№\nп/п", "Наименование\nиспытуемого\nэлемента", "Завод-\nизготовитель", "Наименование\nвида испытания", "Наименование\nмат части", "Номер\nпартии", "Количество\nвыстрелов", "Планируемая\nдата\nпоставки", "Мишенная\nобстановка", "Примечание" };
                for (int i = 0; i < oTable.Columns.Count; i++)
                {
                    Word.Range wordCellRange = oTable.Cell(1, i + 1).Range;
                    wordCellRange.ParagraphFormat.Alignment = Word.WdParagraphAlignment.wdAlignParagraphCenter;
                    wordCellRange.Text = nameHeaderTable[i];
                    wordCellRange.Font.Name = "Times New Roman";
                }
                //Повтор заголовка
                oTable.Rows.First.HeadingFormat = -1;
                //Вертикальное центрирование заголовка
                oTable.Rows[1].Cells.VerticalAlignment = Word.WdCellVerticalAlignment.wdCellAlignVerticalCenter;
                //Вывод
                int posRows = 2;
                List<string> oglavlenie = new List<string>();       //Список для создания строчек оглавления
                List<string> oglavlenieStr = new List<string>();    //Список для страниц оглавления
                string lastCodeZavod = "";                          //Код последнего завода, зафиксированного в оглавлении
                foreach (MesplPlanIsp isp in sortedList)
                {
                    label1.BeginInvoke((MethodInvoker)(() => this.label1.Text = "Информация: печать плана " + (posRows - 1) + " из " + planIspList.Count));
                    oTable.Rows.Add();
                    //Центрируем строчку вертикально
                    oTable.Rows[posRows].Cells.VerticalAlignment = Word.WdCellVerticalAlignment.wdCellAlignVerticalCenter;
                    //Порядковый номер
                    Word.Range wordCellRange = oTable.Cell(posRows, 1).Range;
                    wordCellRange.Rows.HeadingFormat = 0;
                    wordCellRange.Text = Convert.ToString(posRows - 1);
                    //Наименование элемента
                    wordCellRange = oTable.Cell(posRows, 2).Range;
                    wordCellRange.Rows.HeadingFormat = 0;
                    string hertOrIndiz = isp.snindiz;
                    if (isp.snindiz == "")
                        hertOrIndiz += isp.snhert;
                    if (dataBaseSNS[isp.kodel] != null)
                        hertOrIndiz = dataBaseSNS[isp.kodel].name.Replace("\r", "");
                    wordCellRange.Text = hertOrIndiz;
                    wordCellRange.ParagraphFormat.Alignment = Word.WdParagraphAlignment.wdAlignParagraphLeft;
                    //Наименование завода
                    wordCellRange = oTable.Cell(posRows, 3).Range;
                    wordCellRange.Rows.HeadingFormat = 0;
                    wordCellRange.Text = isp.nzav;
                    //Наименование вида испытания
                    wordCellRange = oTable.Cell(posRows, 4).Range;
                    wordCellRange.Rows.HeadingFormat = 0;
                    wordCellRange.Text = isp.nvid;
                    //Наименование мат части
                    string fullNameSistem = "";
                    if (!string.IsNullOrWhiteSpace(isp.shsis) && isp.shsis != "000" && isp.shsis != "0000")
                        fullNameSistem = isp.nsis;
                    else if (!string.IsNullOrWhiteSpace(isp.shstv) && isp.shstv != "000" && isp.shstv != "0000")
                        fullNameSistem = isp.nstv;
                    else if (!string.IsNullOrWhiteSpace(isp.shstd) && isp.shstd != "000" && isp.shstd != "0000")
                        fullNameSistem = isp.nstd;
                    wordCellRange = oTable.Cell(posRows, 5).Range;
                    wordCellRange.Rows.HeadingFormat = 0;
                    wordCellRange.Text = fullNameSistem;
                    wordCellRange.Cells.VerticalAlignment = Word.WdCellVerticalAlignment.wdCellAlignVerticalCenter;
                    //Номер партии
                    wordCellRange = oTable.Cell(posRows, 6).Range;
                    wordCellRange.Rows.HeadingFormat = 0;
                    wordCellRange.Text = Convert.ToString(isp.npart);
                    //Количество выстрелов
                    wordCellRange = oTable.Cell(posRows, 7).Range;
                    wordCellRange.Rows.HeadingFormat = 0;
                    wordCellRange.Text = Convert.ToString(isp.vist);
                    //Планируемая дата поставки
                    wordCellRange = oTable.Cell(posRows, 8).Range;
                    wordCellRange.Rows.HeadingFormat = 0;
                    wordCellRange.Text = Convert.ToString(isp.dpost);
                    //Мишенная обстановка
                    string nameMishen = "";
                    if (isp.npreg1 != "")
                    {
                        nameMishen += isp.npreg1 + "\n";
                    }
                    if (isp.npreg2 != "")
                    {
                        nameMishen += isp.npreg2 + "\n";
                    }
                    wordCellRange = oTable.Cell(posRows, 9).Range;
                    wordCellRange.Rows.HeadingFormat = 0;
                    wordCellRange.Text = "\n" + nameMishen;
                    //Примечание

                    //Для оглавления
                    if (isp.nzav != lastCodeZavod)
                    {
                        lastCodeZavod = isp.nzav;
                        //oglavlenie.Add(isp.nzav.Replace("\n", "").Trim());
                        //oglavlenieStr.Add(wordDocument.ComputeStatistics(Word.WdStatistic.wdStatisticPages, false).ToString());

                        wordDocument.TablesOfAuthorities.MarkCitation(wordCellRange, isp.nzav.Replace("\n", " ").Trim(), isp.nzav.Replace("\n", " ").Trim(), Type.Missing, "1");
                    }
                    posRows++;
                }
                //Добавляем несколько пустых строк в конце (чтобы была пустая страничка)
                //Фиксируем текущую страничку
                //Добавляем пустые, как только вышли на след страничку
                //Добавляем ещё три строчки
                int currentPage = wordDocument.ComputeStatistics(Word.WdStatistic.wdStatisticPages, false);
                for (int i = 0; i < 4; i++)
                {
                    oTable.Rows.Add();
                    //Центрируем строчку вертикально
                    oTable.Rows[posRows].Cells.VerticalAlignment = Word.WdCellVerticalAlignment.wdCellAlignVerticalCenter;
                    //Делаем высоту
                    oTable.Rows[posRows].Height = 100;
                    Word.Range wordCellRange = oTable.Cell(posRows, 1).Range;
                    if (currentPage == wordDocument.ComputeStatistics(Word.WdStatistic.wdStatisticPages, false))
                        i--;
                    posRows++;
                }
                //Оглавление
                label1.BeginInvoke((MethodInvoker)(() => this.label1.Text = "Информация: создание оглавления"));
                wordDocument.Paragraphs.Add();
                range = wordDocument.Paragraphs[wordDocument.Paragraphs.Count].Range;
                range.InsertBreak();
                range = wordDocument.Paragraphs[wordDocument.Paragraphs.Count].Range;
                wordDocument.Paragraphs.Add();
                range = wordDocument.Paragraphs[wordDocument.Paragraphs.Count - 1].Range;
                range.Bold = 1;
                range.Text = "Предприятие";
                wordDocument.Paragraphs.Add();
                wordDocument.Paragraphs.Add();
                range = wordDocument.Paragraphs[wordDocument.Paragraphs.Count - 1].Range;
                range.Bold = 0;
                wordDocument.TablesOfAuthorities.Add(range, "1", Passim: false, KeepEntryFormatting: true, IncludeCategoryHeader: false);
                wordDocument.TablesOfAuthorities[1].KeepEntryFormatting = true;
                wordDocument.TablesOfAuthorities[1].Passim = false;
                wordDocument.TablesOfAuthorities[1].TabLeader = Word.WdTabLeader.wdTabLeaderDots;

                /*wordDocument.Tables.Add(range, 1, 7, autoFitBehavior);
                Word.Table oTableOglavlenie = wordDocument.Tables[2];
                //Запрет на перенос строк
                oTableOglavlenie.Rows.AllowBreakAcrossPages = 0;
                string[] nameHeaderTable2 = { "№ п/п", "Предприятие", "Стр.", "", "№ п/п", "Предприятие", "Стр." };
                for (int i = 0; i < oTableOglavlenie.Columns.Count; i++)
                {
                    Word.Range wordCellRange = oTableOglavlenie.Cell(1, i + 1).Range;
                    wordCellRange.ParagraphFormat.Alignment = Word.WdParagraphAlignment.wdAlignParagraphCenter;
                    wordCellRange.Text = nameHeaderTable2[i];
                    wordCellRange.Font.Name = "Times New Roman";
                }
                oTableOglavlenie.Columns[1].Width = 20;
                oTableOglavlenie.Columns[2].Width = 300;
                oTableOglavlenie.Columns[3].Width = 20;
                oTableOglavlenie.Columns[4].Width = 70;
                oTableOglavlenie.Columns[5].Width = 20;
                oTableOglavlenie.Columns[6].Width = 300;
                oTableOglavlenie.Columns[7].Width = 20;
                //Повтор заголовка
                oTableOglavlenie.Rows.First.HeadingFormat = -1;
                //Вертикальное центрирование заголовка
                oTableOglavlenie.Rows[1].Cells.VerticalAlignment = Word.WdCellVerticalAlignment.wdCellAlignVerticalCenter;
                //Вывод оглавления
                int posOglavlenieRows = 3;
                for (int i = 0; i < oglavlenie.Count; i++)
                {
                    oTableOglavlenie.Rows.Add();
                    oTableOglavlenie.Rows.Add();
                    Word.Range wordCellRange = oTableOglavlenie.Cell(posOglavlenieRows, 1).Range;
                    wordCellRange.Text = Convert.ToString(i + 1);
                    wordCellRange = oTableOglavlenie.Cell(posOglavlenieRows, 2).Range;
                    wordCellRange.Text = oglavlenie[i];
                    wordCellRange = oTableOglavlenie.Cell(posOglavlenieRows, 3).Range;
                    wordCellRange.Text = oglavlenieStr[i];
                    posOglavlenieRows++;
                    posOglavlenieRows++;
                }
                oTableOglavlenie.Columns.Borders[Word.WdBorderType.wdBorderTop].LineStyle = Word.WdLineStyle.wdLineStyleNone;
                oTableOglavlenie.Columns.Borders[Word.WdBorderType.wdBorderLeft].LineStyle = Word.WdLineStyle.wdLineStyleNone;
                oTableOglavlenie.Columns.Borders[Word.WdBorderType.wdBorderBottom].LineStyle = Word.WdLineStyle.wdLineStyleNone;
                oTableOglavlenie.Columns.Borders[Word.WdBorderType.wdBorderRight].LineStyle = Word.WdLineStyle.wdLineStyleNone;
                oTableOglavlenie.Columns.Borders[Word.WdBorderType.wdBorderHorizontal].LineStyle = Word.WdLineStyle.wdLineStyleNone;
                oTableOglavlenie.Columns.Borders[Word.WdBorderType.wdBorderVertical].LineStyle = Word.WdLineStyle.wdLineStyleNone;*/

                //Создание экземпляров
                label1.BeginInvoke((MethodInvoker)(() => this.label1.Text = "Информация: создание экземпляров"));
                wordDocument.Range().Copy();
                var rangeAllDocumentEkz = wordDocument.Content;
                rangeAllDocumentEkz.Find.ClearFormatting();
                //for (int j = 0; j < planIspList.Count; j++)
                {
                    functionReplaceInText(wordDocument, oWord, "{numberEkz}", "1");
                }
                for (int i = 0; i < Convert.ToInt32(countEkz) - 1; i++)
                {
                    //В конец документа
                    object what = Word.WdGoToItem.wdGoToLine;
                    object which = Word.WdGoToDirection.wdGoToLast;
                    Word.Range endRange = wordDocument.GoTo(ref what, ref which);
                    //Создаем разрыв РАЗДЕЛА (не страниц)
                    endRange.InsertBreak(Word.WdBreakType.wdSectionBreakNextPage);
                    //Вставляем
                    endRange.Paste();
                    rangeAllDocumentEkz = wordDocument.Content;
                    rangeAllDocumentEkz.Find.ClearFormatting();
                    rangeAllDocumentEkz.Find.Execute(FindText: "{numberEkz}", ReplaceWith: i + 2);
                }
                //Очистка буфера
                Clipboard.Clear();
                //Сохранение
                label1.BeginInvoke((MethodInvoker)(() => this.label1.Text = "Информация: сохранение"));
                DateTime dateNowFileName = DateTime.Now;
                string strSaveName = Convert.ToString(dateNowFileName);
                strSaveName = strSaveName.Replace(':', '-');
                if (!Directory.Exists(Properties.Settings.Default.PathForReports))
                    Directory.CreateDirectory(Directory.GetCurrentDirectory() + "/reports");
                savePath = Properties.Settings.Default.PathForReports + "/Месячный план испытаний (НС)" + " " + strSaveName + ".docx";
                wordDocument.SaveAs(savePath);
                wordDocument.Close();
                oWord.Quit();
                label1.BeginInvoke((MethodInvoker)(() => this.label1.Text = "Информация: месячный план (НС) создан!"));
                MessageBox.Show("Месячный план (НС) создан!");
            }
            catch (Exception ex)
            {
                oWord.Visible = true;
                label1.BeginInvoke((MethodInvoker)(() => this.label1.Text = "Информация: ошибка!"));
                MessageBox.Show("error: " + ex.Message);
            }
        }
        #endregion

        //Печать месячных запросов
        #region МЕСЯЧНЫЕ ЗАПРОСЫ
        //На один учётный номер
        private void button11_Click(object sender, EventArgs e)
        {
            button11.BackColor = choseColorBTN;
            BlockAllButton();
            if (Properties.Settings.Default.OtraslMesplPath.Length > 0)
            {
                if (Properties.Settings.Default.OtraslPath.Length > 0)
                {
                    if (Properties.Settings.Default.RashGodFolderPath.Length > 0)
                    {
                        try
                        {
                            FormChoseMespl fcm = new FormChoseMespl();
                            fcm.ShowDialog();
                            if (fcm.isExit)
                            {
                                label1.BeginInvoke((MethodInvoker)(() => this.label1.Text = "Информация: подготовка к печати"));
                                Thread thread = new Thread(() =>
                                {
                                    try
                                    {
                                        List<MesplPlanIspG> planIspGList = new List<MesplPlanIspG>();
                                        //Получение плана испытаний
                                        planIspGList = GetMesplPlanIspZ(planIspGList, fcm.mesac, fcm.godr);
                                        //Заполнение плана испытанйи
                                        planIspGList = FillMesplPlanIspZ(planIspGList);
                                        //Печать запросов
                                        string savePath = null;
                                        string savePath2 = null;
                                        string countList = null;
                                        PrintMesplPlanIspZ(planIspGList, fcm.uchNumber, fcm.countEkz, fcm.nameMesac, fcm.godr, out countList, out savePath);
                                        //Создание уголка
                                        FormChoseUgolok fcu = new FormChoseUgolok(fcm.uchNumber, fcm.countEkz, countList);
                                        fcu.ShowDialog();
                                        if (fcu.isExit)
                                            CreateUgolok(fcu.uchNumber, fcu.countEkz, fcu.countList, fcu.HMD, fcu.author, fcu.date, out savePath2, label1);
                                        //Открытие документа
                                        if (checkBox3.Checked)
                                        {
                                            OpenTheDocument(savePath, label1);
                                        }
                                    }
                                    catch (Exception ex)
                                    {
                                        MessageBox.Show("Очередная ошибка ин поток\n" + ex.Message + "\n" + ex.Source + "\n" + ex.StackTrace + "\n" + ex.TargetSite, "Ошибка", MessageBoxButtons.OK, MessageBoxIcon.Error);
                                    }
                                    finally
                                    {
                                        BlockAllButton(false);
                                    }
                                });
                                thread.SetApartmentState(ApartmentState.STA);
                                thread.Start();
                            }
                            else
                                BlockAllButton(false);
                        }
                        catch (Exception ex)
                        {
                            BlockAllButton(false);
                            MessageBox.Show("Очередная ошибка\n" + ex.Message + "\n" + ex.Source + "\n" + ex.StackTrace + "\n" + ex.TargetSite, "Ошибка", MessageBoxButtons.OK, MessageBoxIcon.Error);
                        }
                    }
                    else
                    {
                        BlockAllButton(false);
                        MessageBox.Show("Выберите поддиректорию RAHGOD");
                    }
                }
                else
                {
                    BlockAllButton(false);
                    MessageBox.Show("Выберите директорию OTRASL");
                }
            }
            else
            {
                BlockAllButton(false);
                MessageBox.Show("Выберите директорию OTRASL1");
            }
        }

        private List<MesplPlanIspG> GetMesplPlanIspZ(List<MesplPlanIspG> planIspGList, string mesac, string godr)
        {
            label1.BeginInvoke((MethodInvoker)(() => this.label1.Text = "Информация: получение запросов"));
            planIspGList.Clear();
            string qol = "QOL";
            switch (mesac)
            {
                case "01":
                case "02":
                case "03":
                    qol += "1";
                    break;
                case "04":
                case "05":
                case "06":
                    qol += "2";
                    break;
                case "07":
                case "08":
                case "09":
                    qol += "3";
                    break;
                case "10":
                case "11":
                case "12":
                    qol += "4";
                    break;
            }
            string strConnection = @"Provider=Microsoft.JET.Oledb.4.0;Data Source=" + Properties.Settings.Default.OtraslMesplPath + "/MESPL/PLAN;Extended Properties=dBASE IV;User ID=;Password=;";
            using (_connection = new OleDbConnection(strConnection))
            {
                _connection.Open();
                using (OleDbCommand cmd = _connection.CreateCommand())
                {
                    cmd.CommandText = "SELECT * FROM PLANG.DBF WHERE " + qol + ">0 AND GODR='" + godr + "'";
                    using (OleDbDataReader reader = cmd.ExecuteReader())
                    {
                        while (reader.Read())
                        {
                            MesplPlanIspG isp = new MesplPlanIspG();
                            isp.kodel = Convert.ToString(reader["KODEL"]);
                            isp.kodotr = Convert.ToString(reader["KODOTR"]);
                            isp.shvid = Convert.ToString(reader["SHVID"]);
                            isp.shsis = Convert.ToString(reader["SHSIS"]);

                            isp.qolg = Convert.ToString(reader["QOLG"]);
                            isp.qol1 = Convert.ToString(reader["QOL1"]);
                            isp.qol2 = Convert.ToString(reader["QOL2"]);
                            isp.qol3 = Convert.ToString(reader["QOL3"]);
                            isp.qol4 = Convert.ToString(reader["QOL4"]);

                            isp.nporsv = Convert.ToString(reader["NPORSV"]);
                            isp.nporpl = Convert.ToString(reader["NPORPL"]);

                            isp.godr = Convert.ToString(reader["GODR"]);
                            isp.prim = Convert.ToString(reader["PRIM"]);
                            planIspGList.Add(isp);
                        }
                    }
                }
            }
            return planIspGList;
        }

        private List<MesplPlanIspG> FillMesplPlanIspZ(List<MesplPlanIspG> planIspGList)
        {
            //Получение информации о элементе
            label1.BeginInvoke((MethodInvoker)(() => this.label1.Text = "Информация: получение информации о элементах"));
            string strConnection = @"Provider=Microsoft.JET.Oledb.4.0;Data Source=" + Properties.Settings.Default.OtraslPath + "/SPNAIM;Extended Properties=dBASE IV;User ID=;Password=;";
            using (_connection = new OleDbConnection(strConnection))
            {
                _connection.Open();
                using (OleDbCommand cmd = _connection.CreateCommand())
                {
                    foreach (MesplPlanIspG isp in planIspGList)
                    {
                        if (isp.kodel != null && isp.kodel != "")
                        {
                            cmd.CommandText = "SELECT * FROM SPNAIM.DBF WHERE KODEL='" + isp.kodel + "'";
                            using (OleDbDataReader reader = cmd.ExecuteReader())
                            {
                                while (reader.Read())
                                {
                                    isp.snhert = Convert.ToString(reader["SNHERT"] ?? "");

                                    isp.snindiz = Convert.ToString(reader["SNINDIZ"] ?? "");

                                    isp.snnaim = Convert.ToString(reader["SNNAIM1"] ?? "") + "\n" + Convert.ToString(reader["SNNAIM2"] ?? "") + "\n" +
                                        Convert.ToString(reader["SNNAIM3"] ?? "") + "\n" + Convert.ToString(reader["SNNAIM4"] ?? "") + "\n" +
                                        Convert.ToString(reader["SNNAIM5"] ?? "") + "\n" + Convert.ToString(reader["SNNAIM6"] ?? "") + "\n" +
                                        Convert.ToString(reader["SNNAIM7"] ?? "") + "\n" + Convert.ToString(reader["SNNAIM8"] ?? "") + "\n" +
                                        Convert.ToString(reader["SNNAIM9"] ?? "") + "\n" + Convert.ToString(reader["SNNAIM10"] ?? "");
                                    isp.snnaim = isp.snnaim.TrimEnd('\n');
                                    isp.snnaim = isp.snnaim.Replace('*', '"');
                                }
                            }
                        }
                        else
                            MessageBox.Show("В плане элемент без кода!", "Косяк", MessageBoxButtons.OK, MessageBoxIcon.Error);
                    }
                }
            }
            //Получение информации о заводе
            label1.BeginInvoke((MethodInvoker)(() => this.label1.Text = "Информация: получение информации о заводах"));
            strConnection = @"Provider=Microsoft.JET.Oledb.4.0;Data Source=" + Properties.Settings.Default.OtraslPath + "/SPRXZ;Extended Properties=dBASE IV;User ID=;Password=;";
            using (_connection = new OleDbConnection(strConnection))
            {
                _connection.Open();
                using (OleDbCommand cmd = _connection.CreateCommand())
                {
                    foreach (MesplPlanIspG isp in planIspGList)
                    {
                        cmd.CommandText = "SELECT KODOTR, NZAV1, NZAV2, NZAV3, INN FROM SPRXZ.DBF WHERE KODOTR='" + isp.kodotr + "'";
                        using (OleDbDataReader reader = cmd.ExecuteReader())
                        {
                            while (reader.Read())
                            {
                                isp.nzav = Convert.ToString(reader["NZAV1"]) + "\n" + Convert.ToString(reader["NZAV2"]) + "\n" + Convert.ToString(reader["NZAV3"]);
                                isp.nzav = isp.nzav.TrimEnd('\n');
                                isp.nzav = isp.nzav.Replace('*', '"');
                                isp.inn = Convert.ToString(reader["INN"]);
                            }
                        }
                    }
                }
            }
            //Получение информации о виде
            label1.BeginInvoke((MethodInvoker)(() => this.label1.Text = "Информация: получение информации о видах испытаний"));
            strConnection = @"Provider=Microsoft.JET.Oledb.4.0;Data Source=" + Properties.Settings.Default.OtraslPath + "/SPRAV;Extended Properties=dBASE IV;User ID=;Password=;";
            using (_connection = new OleDbConnection(strConnection))
            {
                _connection.Open();
                using (OleDbCommand cmd = _connection.CreateCommand())
                {
                    foreach (MesplPlanIspG isp in planIspGList)
                    {
                        cmd.CommandText = "SELECT * FROM SPVID.DBF WHERE SHVID='" + isp.shvid + "'";
                        using (OleDbDataReader reader = cmd.ExecuteReader())
                        {
                            while (reader.Read())
                            {
                                isp.nvid = Convert.ToString(reader["NVID1"]) + "\n" + Convert.ToString(reader["NVID2"]);
                                isp.nvid = isp.nvid.TrimEnd('\n');
                            }
                        }
                    }
                }
            }
            //Получение информации о системе
            label1.BeginInvoke((MethodInvoker)(() => this.label1.Text = "Информация: получение информации о системах"));
            try
            {
                strConnection = @"Provider=Microsoft.JET.Oledb.4.0;Data Source=" + Properties.Settings.Default.OtraslPath + "/SPRAV;Extended Properties=dBASE IV;User ID=;Password=;";
                using (_connection = new OleDbConnection(strConnection))
                {
                    _connection.Open();
                    using (OleDbCommand cmd = _connection.CreateCommand())
                    {
                        foreach (MesplPlanIspG isp in planIspGList)
                        {
                            if (isp.shsis != null)
                                if (isp.shsis != "")
                                {
                                    cmd.CommandText = "SELECT * FROM SPSIS.DBF WHERE SHSIS='" + isp.shsis + "'";
                                    using (OleDbDataReader reader = cmd.ExecuteReader())
                                    {
                                        while (reader.Read())
                                        {
                                            isp.nsis = Convert.ToString(reader["NSIS"]);
                                        }
                                    }
                                }
                        }
                    }
                }
            }
            catch (Exception ex)
            {
                MessageBox.Show("Что-то с МЧ!!!");
            }
            //Получение информации о адресе завода
            label1.BeginInvoke((MethodInvoker)(() => this.label1.Text = "Информация: получение информации о адресах заводов"));
            strConnection = @"Provider=Microsoft.JET.Oledb.4.0;Data Source=" + Properties.Settings.Default.OtraslMesplPath + "/SPRXZ;Extended Properties=dBASE IV;User ID=;Password=;";
            using (_connection = new OleDbConnection(strConnection))
            {
                _connection.Open();
                using (OleDbCommand cmd = _connection.CreateCommand())
                {
                    foreach (MesplPlanIspG isp in planIspGList)
                    {
                        cmd.CommandText = "SELECT * FROM SPRED.DBF WHERE KODOTR='" + isp.kodotr + "'";
                        using (OleDbDataReader reader = cmd.ExecuteReader())
                        {
                            while (reader.Read())
                            {
                                isp.adres = Convert.ToString(reader["ADRES1"]) + "\n" + Convert.ToString(reader["ADRES2"]) + "\n" + Convert.ToString(reader["ADRES3"]) + "\n" + Convert.ToString(reader["ADRES4"]) + "\n" + Convert.ToString(reader["ADRES5"]) + "\n" + Convert.ToString(reader["ADRES6"]);
                                isp.adres = isp.adres.TrimEnd('\n');
                                isp.adres = isp.adres.Replace('*', '"');
                            }
                        }
                    }
                }
            }
            return planIspGList;
        }

        private void PrintMesplPlanIspZ(List<MesplPlanIspG> planIspGList, string uchNumber, string countEkz, string nameMesac, string yearPrintResult, out string countList, out string savePath, bool oneUchet = true)
        {
            savePath = null;
            countList = null;

            label1.BeginInvoke((MethodInvoker)(() => this.label1.Text = "Информация: подготовка к печати"));
            //MessageBox.Show("Печатаем план");
            //Сортируем план испытаний
            var sortedList = planIspGList.OrderBy(a => a.kodotr).ThenBy(b => b.kodel).ThenBy(c => c.shvid);

            //Создаем документ
            Word.Application oWord = new Word.Application();
            oWord.Visible = checkBox1.Checked;
            try
            {
                string path = Directory.GetCurrentDirectory() + templateFileNameWordQueryOnMonth;
                if (oneUchet)
                    path = Directory.GetCurrentDirectory() + templateFileNameWordQueryOnMonthOneUchet;
                //MessageBox.Show("Путь: " + path);
                Word.Document wordDocument = oWord.Documents.Open(path);

                //Поиск и замена текста
                int newUchNumber = 0;
                if (uchNumber != "")
                    newUchNumber = Convert.ToInt32(uchNumber);
                if(oneUchet)
                    functionReplaceInText(wordDocument, oWord, "{uchNumber}", uchNumber);
                functionReplaceInText(wordDocument, oWord, "{year}", yearPrintResult);

                if (nameMesac != "МАРТ" && nameMesac != "АВГУСТ")
                    nameMesac = nameMesac.Substring(0, nameMesac.Length - 1);
                nameMesac += "Е";
                functionReplaceInText(wordDocument, oWord, "{month}", nameMesac);

                int year2 = Convert.ToInt32(yearPrintResult);

                string month2 = nameMesac;
                switch (nameMesac)
                {
                    case "ЯНВАРЕ":
                        month2 = "ДЕКАБРЯ";
                        year2--;
                        break;
                    case "ФЕВРАЛЕ":
                        month2 = "ЯНВАРЯ";
                        break;
                    case "МАРТЕ":
                        month2 = "ФЕВРАЛЯ";
                        break;
                    case "АПРЕЛЕ":
                        month2 = "МАРТА";
                        break;
                    case "МАЕ":
                        month2 = "АПРЕЛЯ";
                        break;
                    case "ИЮНЕ":
                        month2 = "МАЯ";
                        break;
                    case "ИЮЛЕ":
                        month2 = "ИЮНЯ";
                        break;
                    case "АВГУСТЕ":
                        month2 = "ИЮЛЯ";
                        break;
                    case "СЕНТЯБРЕ":
                        month2 = "АВГУСТА";
                        break;
                    case "ОКТЯБРЕ":
                        month2 = "СЕНТЯБРЯ";
                        break;
                    case "НОЯБРЕ":
                        month2 = "ОКТЯБРЯ";
                        break;
                    case "ДЕКАБРЕ":
                        month2 = "НОЯБРЯ";
                        break;
                }
                functionReplaceInText(wordDocument, oWord, "{month2}", month2);
                functionReplaceInText(wordDocument, oWord, "{year2}", Convert.ToString(year2));

                int tablesCount = 1;
                string strNewCode = "";

                Word.Table oTable;
                Word.Range wordCellRange;
                int posRows = 0;
                Word.Range range;

                wordDocument.Range().Copy();

                functionReplaceInText(wordDocument, oWord, "{uchNumber}", Convert.ToString(newUchNumber));

                int numericElement = 1;

                foreach (MesplPlanIspG isp in sortedList)
                {
                    label1.BeginInvoke((MethodInvoker)(() => this.label1.Text = "Информация: печать элемента " + numericElement++ + " из " + planIspGList.Count));
                    if (strNewCode != isp.kodotr)
                    {
                        if (strNewCode != "")
                        {
                            //Создание нижней подписи
                            range = wordDocument.Paragraphs[wordDocument.Paragraphs.Count].Range;
                            wordDocument.Paragraphs.Add();
                            range = wordDocument.Paragraphs[wordDocument.Paragraphs.Count].Range;
                            range.Text = "Начальник сборочно-испытательного производства - зам. главного инженера\t\tА.И. Кочуров";
                            range = wordDocument.Paragraphs[wordDocument.Paragraphs.Count].Range;
                            wordDocument.Paragraphs.Add();
                            range = wordDocument.Paragraphs[wordDocument.Paragraphs.Count].Range;
                            if (oneUchet)
                                range.InsertBreak(Word.WdBreakType.wdPageBreak);
                            else
                                range.InsertBreak(Word.WdBreakType.wdSectionBreakNextPage);
                            range.Paste();
                            tablesCount++;
                            if (!oneUchet)
                            {
                                newUchNumber++;
                            }
                            functionReplaceInText(wordDocument, oWord, "{uchNumber}", Convert.ToString(newUchNumber));
                        }
                        //functionReplaceInText(wordDocument, oWord, "{otprText}", isp.adres);
                        oTable = wordDocument.Tables[tablesCount];
                        wordCellRange = oTable.Cell(1, 2).Range;
                        wordCellRange.Text = "ВЕДОМОСТЬ ПОСТАВОК НА ИСПЫТАНИЯ\n\n" + isp.adres;
                        //Код завода
                        //wordDocument.Paragraphs.Add();
                        range = wordDocument.Paragraphs[wordDocument.Paragraphs.Count].Range;
                        range.Text = isp.kodotr;
                        strNewCode = isp.kodotr;
                        //Добавление в файл таблицы
                        wordDocument.Paragraphs.Add();
                        //wordDocument.Paragraphs.Add();
                        Object autoFitBehavior = Word.WdAutoFitBehavior.wdAutoFitWindow;
                        range = wordDocument.Paragraphs[wordDocument.Paragraphs.Count].Range;
                        wordDocument.Tables.Add(range, 1, 9, autoFitBehavior);
                        tablesCount++;
                        oTable = wordDocument.Tables[tablesCount];
                        //Запрет на перенос строк
                        oTable.Rows.AllowBreakAcrossPages = 0;
                        string[] nameHeaderTable = { "№\nп/п",
                                               "Код и наименование\nиспытуемого\nэлемента",
                                               "Код и наиме-\nнование вида\nиспытания",
                                               "Код и наименование\nматчасти",
                                               "Номер\nпартии",
                                               "Число\nвыстрелов\nот партии",
                                               "Дата\nпоставки\nна полигон",
                                               "Номер\nзаказа\nна заводе",
                                               "№, Дата ГОСЗАКАЗА\nПРИМЕЧАНИЕ"
                                           };
                        for (int i = 0; i < oTable.Columns.Count; i++)
                        {
                            wordCellRange = oTable.Cell(1, i + 1).Range;
                            wordCellRange.ParagraphFormat.Alignment = Word.WdParagraphAlignment.wdAlignParagraphCenter;
                            wordCellRange.Text = nameHeaderTable[i];
                            wordCellRange.Font.Name = "Times New Roman";
                        }
                        posRows = 2;
                        //Повтор заголовка
                        oTable.Rows.First.HeadingFormat = -1;
                        //Вертикальное центрирование заголовка
                        oTable.Rows[1].Cells.VerticalAlignment = Word.WdCellVerticalAlignment.wdCellAlignVerticalCenter;
                        //Изменяем размеры колонок
                        oTable.Cell(1, 1).Width = 50;
                        oTable.Cell(1, 2).Width = 120;
                        oTable.Cell(1, 3).Width = 78;
                        oTable.Cell(1, 4).Width = 85;
                        oTable.Cell(1, 5).Width = 77;
                        oTable.Cell(1, 6).Width = 74;
                        oTable.Cell(1, 7).Width = 86;
                        oTable.Cell(1, 8).Width = 86;
                        oTable.Cell(1, 9).Width = 90;
                    }
                    oTable = wordDocument.Tables[tablesCount];
                    oTable.Rows.Add();
                    //Порядковый номер
                    wordCellRange = oTable.Cell(posRows, 1).Range;
                    wordCellRange.Rows.HeadingFormat = 0;
                    wordCellRange.Text = Convert.ToString(posRows - 1);
                    wordCellRange.Cells.VerticalAlignment = Word.WdCellVerticalAlignment.wdCellAlignVerticalCenter;
                    //Шифр, наименование элемента
                    wordCellRange = oTable.Cell(posRows, 2).Range;
                    wordCellRange.Rows.HeadingFormat = 0;
                    if (!string.IsNullOrWhiteSpace(isp.kodel))
                        wordCellRange.Text = "Код " + Convert.ToString(isp.kodel) + "\n\n" + isp.snhert + "\n" + isp.snindiz + "\n" + isp.snnaim;
                    else
                        wordCellRange.Text = Convert.ToString(isp.kodel) + "\n\n" + isp.snhert + "\n" + isp.snindiz + "\n" + isp.snnaim;
                    wordCellRange.ParagraphFormat.Alignment = Word.WdParagraphAlignment.wdAlignParagraphLeft;
                    wordCellRange.Cells.VerticalAlignment = Word.WdCellVerticalAlignment.wdCellAlignVerticalTop;
                    //Шифр, наименование вида испытания
                    wordCellRange = oTable.Cell(posRows, 3).Range;
                    wordCellRange.Rows.HeadingFormat = 0;
                    if (!string.IsNullOrWhiteSpace(isp.shvid))
                        wordCellRange.Text = "Код " + Convert.ToString(isp.shvid) + "\n\n\n\n" + isp.nvid;
                    else
                        wordCellRange.Text = Convert.ToString(isp.shvid) + "\n\n\n\n" + isp.nvid;
                    wordCellRange.Cells.VerticalAlignment = Word.WdCellVerticalAlignment.wdCellAlignVerticalTop;
                    //Мат часть
                    wordCellRange = oTable.Cell(posRows, 4).Range;
                    wordCellRange.Rows.HeadingFormat = 0;
                    if (!string.IsNullOrWhiteSpace(isp.shsis) && isp.shsis != "000")
                        wordCellRange.Text = "Код " + Convert.ToString(isp.shsis) + "\n" + isp.nsis;
                    else
                        wordCellRange.Text = Convert.ToString(isp.shvid) + "\n\n\n\n" + isp.nvid;
                    wordCellRange.Cells.VerticalAlignment = Word.WdCellVerticalAlignment.wdCellAlignVerticalCenter;
                    //Номер партии

                    //Количество выстрелов

                    //Планируемая дата поставки

                    //Номер заказа на заводе

                    //Примечание

                    posRows++;
                }
                //Создание нижней подписи
                range = wordDocument.Paragraphs[wordDocument.Paragraphs.Count].Range;
                wordDocument.Paragraphs.Add();
                range = wordDocument.Paragraphs[wordDocument.Paragraphs.Count].Range;
                range.Text = "Начальник сборочно-испытательного производства - зам. главного инженера\t\t\tА.И. Кочуров";

                //Количество листов для уголка
                countList = wordDocument.ComputeStatistics(Word.WdStatistic.wdStatisticPages, false).ToString();

                //Создание экземпляров
                label1.BeginInvoke((MethodInvoker)(() => this.label1.Text = "Информация: создание экземпляров"));
                wordDocument.Range().Copy();
                var rangeAllDocumentEkz = wordDocument.Content;
                rangeAllDocumentEkz.Find.ClearFormatting();
                //for (int j = 0; j < planIspList.Count; j++)
                {
                    functionReplaceInText(wordDocument, oWord, "{numberEkz}", "1");
                }
                for (int i = 0; i < Convert.ToInt32(countEkz) - 1; i++)
                {
                    //В конец документа
                    object what = Word.WdGoToItem.wdGoToLine;
                    object which = Word.WdGoToDirection.wdGoToLast;
                    Word.Range endRange = wordDocument.GoTo(ref what, ref which);
                    //Создаем разрыв РАЗДЕЛА (не страниц)
                    endRange.InsertBreak(Word.WdBreakType.wdSectionBreakNextPage);
                    //Вставляем
                    endRange.Paste();
                    rangeAllDocumentEkz = wordDocument.Content;
                    rangeAllDocumentEkz.Find.ClearFormatting();
                    rangeAllDocumentEkz.Find.Execute(FindText: "{numberEkz}", ReplaceWith: i + 2);
                }
                //Очистка буфера
                Clipboard.Clear();
                //Сохранение
                label1.BeginInvoke((MethodInvoker)(() => this.label1.Text = "Информация: сохранение"));
                DateTime dateNowFileName = DateTime.Now;
                string strSaveName = Convert.ToString(dateNowFileName);
                strSaveName = strSaveName.Replace(':', '-');
                if (!Directory.Exists(Properties.Settings.Default.PathForReports))
                    Directory.CreateDirectory(Directory.GetCurrentDirectory() + "/reports");
                savePath = Properties.Settings.Default.PathForReports + "/Месячные заявки на испытания" + " " + strSaveName + ".docx";
                wordDocument.SaveAs(savePath);
                wordDocument.Close();
                oWord.Quit();
                label1.BeginInvoke((MethodInvoker)(() => this.label1.Text = "Информация: заявки созданы"));
                MessageBox.Show("Заявки созданы!");
            }
            catch (Exception ex)
            {
                oWord.Visible = true;
                label1.BeginInvoke((MethodInvoker)(() => this.label1.Text = "Информация: ошибка!"));
                MessageBox.Show("error: " + ex.Message);
            }
        }

        //На несколько учётных номеров
        private void button12_Click(object sender, EventArgs e)
        {
            BlockAllButton();
            if (Properties.Settings.Default.OtraslMesplPath.Length > 0)
            {
                if (Properties.Settings.Default.OtraslPath.Length > 0)
                {
                    if (Properties.Settings.Default.RashGodFolderPath.Length > 0)
                    {
                        try
                        {
                            FormChoseMespl fcm = new FormChoseMespl();
                            fcm.ShowDialog();
                            if (fcm.isExit)
                            {
                                label1.BeginInvoke((MethodInvoker)(() => this.label1.Text = "Информация: подготовка к печати"));
                                Thread thread = new Thread(() =>
                                {
                                    try
                                    {
                                        List<MesplPlanIspG> planIspGList = new List<MesplPlanIspG>();
                                        //Получение плана испытаний
                                        planIspGList = GetMesplPlanIspZ(planIspGList, fcm.mesac, fcm.godr);
                                        //Заполнение плана испытанйи
                                        planIspGList = FillMesplPlanIspZ(planIspGList);
                                        //Печать запросов
                                        string savePath = null;
                                        string countList = null;
                                        //TODO: уголки сделать на несколько номеров!!!
                                        PrintMesplPlanIspZ(planIspGList, fcm.uchNumber, fcm.countEkz, fcm.nameMesac, fcm.godr, out countList, out savePath, false);
                                        //Открытие документа
                                        if (checkBox3.Checked)
                                            OpenTheDocument(savePath, label1);
                                    }
                                    catch (Exception ex)
                                    {
                                        MessageBox.Show("Очередная ошибка ин поток\n" + ex.Message + "\n" + ex.Source + "\n" + ex.StackTrace + "\n" + ex.TargetSite, "Ошибка", MessageBoxButtons.OK, MessageBoxIcon.Error);
                                    }
                                    finally
                                    {
                                        BlockAllButton(false);
                                    }
                                });
                                thread.SetApartmentState(ApartmentState.STA);
                                thread.Start();
                            }
                        }
                        catch (Exception ex)
                        {
                            BlockAllButton(false);
                            MessageBox.Show("Очередная ошибка\n" + ex.Message + "\n" + ex.Source + "\n" + ex.StackTrace + "\n" + ex.TargetSite, "Ошибка", MessageBoxButtons.OK, MessageBoxIcon.Error);
                        }
                    }
                    else
                        MessageBox.Show("Выберите поддиректорию RAHGOD");
                }
                else
                    MessageBox.Show("Выберите директорию OTRASL");
            }
            else
                MessageBox.Show("Выберите директорию OTRASL1");
        }
        #endregion

        //Печать месячной потребности в КЭ
        #region МЕСЯЧНАЯ ПОТРЕБНОСТЬ В КЭ
        private void button7_Click(object sender, EventArgs e)
        {
            button7.BackColor = choseColorBTN;
            BlockAllButton();
            if (Properties.Settings.Default.OtraslMesplPath.Length > 0)
            {
                if (Properties.Settings.Default.OtraslPath.Length > 0)
                {
                    if (Properties.Settings.Default.RashGodFolderPath.Length > 0)
                    {
                        try
                        {
                            FormChoseMespl fcm = new FormChoseMespl();
                            fcm.ShowDialog();
                            if (fcm.isExit)
                            {
                                label1.BeginInvoke((MethodInvoker)(() => this.label1.Text = "Информация: подготовка к печати"));
                                Thread thread = new Thread(() =>
                                {
                                    try
                                    {
                                        List<MesplKE> keList = new List<MesplKE>();
                                        //Получение потребности в КЭ
                                        keList = GetMesplKE(keList, fcm.mesac, fcm.godr);
                                        //Заполнение потребности в комплектующих
                                        keList = FillMesplKE(keList);
                                        //Печать потребности в КЭ
                                        string savePath = null;
                                        string savePath2 = null;
                                        string countList = null;
                                        PrintMesplKE(keList, fcm.nameMesac, fcm.godr, fcm.uchNumber, fcm.countEkz, out countList, out savePath);
                                        //Создание уголка
                                        FormChoseUgolok fcu = new FormChoseUgolok(fcm.uchNumber, fcm.countEkz, countList);
                                        fcu.ShowDialog();
                                        if (fcu.isExit)
                                            CreateUgolok(fcu.uchNumber, fcu.countEkz, fcu.countList, fcu.HMD, fcu.author, fcu.date, out savePath2, label1);
                                        //Открытие документа
                                        if (checkBox3.Checked)
                                        {
                                            OpenTheDocument(savePath, label1);
                                            OpenTheDocument(savePath2, label1);
                                        }
                                    }
                                    catch (Exception ex)
                                    {
                                        MessageBox.Show("Очередная ошибка ин поток\n" + ex.Message + "\n" + ex.Source + "\n" + ex.StackTrace + "\n" + ex.TargetSite, "Ошибка", MessageBoxButtons.OK, MessageBoxIcon.Error);
                                    }
                                    finally
                                    {
                                        BlockAllButton(false);
                                    }
                                });
                                thread.SetApartmentState(ApartmentState.STA);
                                thread.Start();
                            }
                            else
                                BlockAllButton(false);
                        }
                        catch (Exception ex)
                        {
                            BlockAllButton(false);
                            MessageBox.Show("Очередная ошибка\n" + ex.Message + "\n" + ex.Source + "\n" + ex.StackTrace + "\n" + ex.TargetSite, "Ошибка", MessageBoxButtons.OK, MessageBoxIcon.Error);
                        }
                    }
                    else
                    {
                        BlockAllButton(false);
                        MessageBox.Show("Выберите поддиректорию RAHGOD");
                    }
                }
                else
                {
                    BlockAllButton(false);
                    MessageBox.Show("Выберите директорию OTRASL");
                }
            }
            else
            {
                BlockAllButton(false);
                MessageBox.Show("Выберите директорию OTRASL1");
            }
        }

        private List<MesplKE> GetMesplKE(List<MesplKE> keList, string mesac, string godr)
        {
            label1.BeginInvoke((MethodInvoker)(() => this.label1.Text = "Информация: получаем потребность"));
            keList.Clear();
            //MessageBox.Show("Получаем потребность");
            string strConnection = @"Provider=Microsoft.JET.Oledb.4.0;Data Source=" + Properties.Settings.Default.OtraslMesplPath + "/MESPL/PLAN;Extended Properties=dBASE IV;User ID=;Password=;";
            using (_connection = new OleDbConnection(strConnection))
            {
                _connection.Open();
                using (OleDbCommand cmd = _connection.CreateCommand())
                {
                    cmd.CommandText = "SELECT * FROM POTRM.DBF WHERE MESAC='" + mesac + "' AND GODR='" + godr + "'";
                    using (OleDbDataReader reader = cmd.ExecuteReader())
                    {
                        while (reader.Read())
                        {
                            MesplKE ke = new MesplKE();
                            ke.kodel = Convert.ToString(reader["KODEL"]);
                            ke.kodeiz = Convert.ToString(reader["KODEIZ"]);
                            ke.qpotr = Convert.ToString(reader["QPOTR"]);
                            ke.mesac = Convert.ToString(reader["MESAC"]);
                            ke.godr = Convert.ToString(reader["GODR"]);
                            ke.priz = Convert.ToString(reader["PRIZ"]);
                            keList.Add(ke);
                        }
                    }
                }
            }
            //MessageBox.Show("Потребность получена");
            return keList;
        }

        private List<MesplKE> FillMesplKE(List<MesplKE> keList)
        {
            //MessageBox.Show("Заполняем потребность");
            //Получение информации о элементе
            label1.BeginInvoke((MethodInvoker)(() => this.label1.Text = "Информация: получение информации о элементах"));
            string strConnection = @"Provider=Microsoft.JET.Oledb.4.0;Data Source=" + Properties.Settings.Default.OtraslPath + "/SPNAIM;Extended Properties=dBASE IV;User ID=;Password=;";
            using (_connection = new OleDbConnection(strConnection))
            {
                _connection.Open();
                using (OleDbCommand cmd = _connection.CreateCommand())
                {
                    foreach (MesplKE ke in keList)
                    {
                        if (ke.kodel != null && ke.kodel != "")
                        {
                            cmd.CommandText = "SELECT * FROM SPNAIM.DBF WHERE KODEL='" + ke.kodel + "'";
                            using (OleDbDataReader reader = cmd.ExecuteReader())
                            {
                                while (reader.Read())
                                {
                                    ke.snhert = Convert.ToString(reader["SNHERT"] ?? "");

                                    ke.snindiz = Convert.ToString(reader["SNINDIZ"] ?? "");

                                    ke.snnaim = Convert.ToString(reader["SNNAIM1"] ?? "") + "\n" + Convert.ToString(reader["SNNAIM2"] ?? "") + "\n" +
                                        Convert.ToString(reader["SNNAIM3"] ?? "") + "\n" + Convert.ToString(reader["SNNAIM4"] ?? "") + "\n" +
                                        Convert.ToString(reader["SNNAIM5"] ?? "") + "\n" + Convert.ToString(reader["SNNAIM6"] ?? "") + "\n" +
                                        Convert.ToString(reader["SNNAIM7"] ?? "") + "\n" + Convert.ToString(reader["SNNAIM8"] ?? "") + "\n" +
                                        Convert.ToString(reader["SNNAIM9"] ?? "") + "\n" + Convert.ToString(reader["SNNAIM10"] ?? "");
                                    ke.snnaim = ke.snnaim.TrimEnd('\n');
                                    ke.snnaim = ke.snnaim.Replace('*', '"');
                                }
                            }
                        }
                        else
                            MessageBox.Show("В потребности элемент без кода!", "Косяк", MessageBoxButtons.OK, MessageBoxIcon.Error);
                    }
                }
            }
            //Получение информации о ед. измерения
            label1.BeginInvoke((MethodInvoker)(() => this.label1.Text = "Информация: получение информации о ед. измерений"));
            strConnection = @"Provider=Microsoft.JET.Oledb.4.0;Data Source=" + Properties.Settings.Default.OtraslPath + "/SPRAV;Extended Properties=dBASE IV;User ID=;Password=;";
            using (_connection = new OleDbConnection(strConnection))
            {
                _connection.Open();
                using (OleDbCommand cmd = _connection.CreateCommand())
                {
                    foreach (MesplKE ke in keList)
                    {
                        cmd.CommandText = "SELECT * FROM SPEIZ2 WHERE KODEIZ='" + ke.kodeiz + "'";
                        using (OleDbDataReader reader = cmd.ExecuteReader())
                        {
                            while (reader.Read())
                            {
                                ke.naimeiz = Convert.ToString(reader["NAIMEIZ"]);
                                ke.naik = Convert.ToString(reader["NAIK"]);
                            }
                        }
                    }
                }
            }
            //MessageBox.Show("Потребность заполнена");
            return keList;
        }

        private void PrintMesplKE(List<MesplKE> keList, string nameMesac, string godr, string uchNumber, string countEkz, out string countList, out string savePath)
        {
            savePath = null;
            countList = "";

            label1.BeginInvoke((MethodInvoker)(() => this.label1.Text = "Информация: печатаем месячную потребность"));
            //MessageBox.Show("Печатаем месячную потребность");
            //Сортируем потребность
            var sortedList = keList.OrderBy(a => a.kodel);
            //Создаем документ
            Word.Application oWord = new Word.Application();
            oWord.Visible = checkBox1.Checked;
            try
            {
                //Открытие документа
                Word.Document wordDocument;
                string path = Directory.GetCurrentDirectory() + templateFileNameWordQueryPotr;
                //MessageBox.Show("Путь: " + path);
                wordDocument = oWord.Documents.Open(path);
                //Поиск и замена текста
                functionReplaceInText(wordDocument, oWord, "{uchNumber}", uchNumber);
                functionReplaceInText(wordDocument, oWord, "{year}", godr);
                functionReplaceInText(wordDocument, oWord, "{month}", nameMesac);
                //Добавление в файл таблицы
                Object autoFitBehavior = Word.WdAutoFitBehavior.wdAutoFitWindow;
                //Добавление в файл таблицы
                var range = wordDocument.Paragraphs[wordDocument.Paragraphs.Count - 1].Range;
                wordDocument.Tables.Add(range, 1, 11, autoFitBehavior);
                Word.Table oTable = wordDocument.Tables[1];
                //Запрет на перенос строк
                oTable.Rows.AllowBreakAcrossPages = 0;
                string[] nameHeaderTable = { "Код/\nКомплектующий\nэлемент", "Код/\nЕдиница\nизмерения", "Потребность\nна\nмесяц", "", "Код/\nКомплектующий\nэлемент", "Код/\nЕдиница\nизмерения", "Потребность\nна\nмесяц", "", "Код/\nКомплектующий\nэлемент", "Код/\nЕдиница\nизмерения", "Потребность\nна\nмесяц" };
                for (int i = 0; i < oTable.Columns.Count; i++)
                {
                    Word.Range wordCellRange = oTable.Cell(1, i + 1).Range;
                    wordCellRange.ParagraphFormat.Alignment = Word.WdParagraphAlignment.wdAlignParagraphCenter;
                    wordCellRange.Text = nameHeaderTable[i];
                    wordCellRange.Font.Name = "Times New Roman";
                }
                //Скрываем колонку
                oTable.Columns[4].Borders[Word.WdBorderType.wdBorderTop].LineStyle = Word.WdLineStyle.wdLineStyleNone;
                oTable.Columns[4].Borders[Word.WdBorderType.wdBorderLeft].LineStyle = Word.WdLineStyle.wdLineStyleNone;
                oTable.Columns[4].Borders[Word.WdBorderType.wdBorderBottom].LineStyle = Word.WdLineStyle.wdLineStyleNone;
                oTable.Columns[4].Borders[Word.WdBorderType.wdBorderRight].LineStyle = Word.WdLineStyle.wdLineStyleNone;
                oTable.Columns[4].Borders[Word.WdBorderType.wdBorderLeft].LineStyle = Word.WdLineStyle.wdLineStyleSingle;
                oTable.Columns[4].Borders[Word.WdBorderType.wdBorderRight].LineStyle = Word.WdLineStyle.wdLineStyleSingle;
                oTable.Columns[8].Borders[Word.WdBorderType.wdBorderTop].LineStyle = Word.WdLineStyle.wdLineStyleNone;
                oTable.Columns[8].Borders[Word.WdBorderType.wdBorderLeft].LineStyle = Word.WdLineStyle.wdLineStyleNone;
                oTable.Columns[8].Borders[Word.WdBorderType.wdBorderBottom].LineStyle = Word.WdLineStyle.wdLineStyleNone;
                oTable.Columns[8].Borders[Word.WdBorderType.wdBorderRight].LineStyle = Word.WdLineStyle.wdLineStyleNone;
                oTable.Columns[8].Borders[Word.WdBorderType.wdBorderLeft].LineStyle = Word.WdLineStyle.wdLineStyleSingle;
                oTable.Columns[8].Borders[Word.WdBorderType.wdBorderRight].LineStyle = Word.WdLineStyle.wdLineStyleSingle;
                //Вывод
                int numeric = 1;
                int posRows = 2;
                int posCols = 0;
                int numericElement = 1;
                oTable.Rows.Add();
                foreach (MesplKE ke in sortedList)
                {
                    label1.BeginInvoke((MethodInvoker)(() => this.label1.Text = "Информация: печать элемента " + numericElement + " из " + keList.Count));
                    Word.Range wordCellRange = oTable.Cell(posRows, posCols+1).Range;
                    if (!string.IsNullOrWhiteSpace(ke.kodel))
                        wordCellRange.Text = /*"  " + numeric + ".\n" +*/ "Код " + Convert.ToString(ke.kodel) + "\n\n" + ke.snhert + "\n" + ke.snindiz + "\n" + ke.snnaim;
                    else
                        wordCellRange.Text = /*"  " + numeric + ".\n" +*/ Convert.ToString(ke.kodel) + "\n\n" + ke.snhert + "\n" + ke.snindiz + "\n" + ke.snnaim;
                    wordCellRange.ParagraphFormat.Alignment = Word.WdParagraphAlignment.wdAlignParagraphLeft;
                    numeric++;
                    wordCellRange = oTable.Cell(posRows, posCols+2).Range;
                    if (!string.IsNullOrWhiteSpace(ke.kodeiz))
                        wordCellRange.Text = "Код " + Convert.ToString(ke.kodeiz) + "\n\n" + ke.naimeiz;
                    else
                        wordCellRange.Text = Convert.ToString(ke.kodeiz) + "\n\n" + ke.naimeiz;
                    wordCellRange = oTable.Cell(posRows, posCols+3).Range;
                    wordCellRange.Text = "\n\n\n\n" + Convert.ToString(ke.qpotr);
                    posCols += 4;
                    if (posCols > 8)
                    {
                        oTable.Rows.Add();
                        posRows++;
                        posCols = 0;
                    }
                    numericElement++;
                }
                //Повтор заголовка
                oTable.Rows[1].HeadingFormat = -1;
                //Вертикальное центрирование заголовка
                oTable.Rows[1].Cells.VerticalAlignment = Word.WdCellVerticalAlignment.wdCellAlignVerticalCenter;

                //Количество листов для уголка
                countList = wordDocument.ComputeStatistics(Word.WdStatistic.wdStatisticPages, false).ToString();

                //Создание экземпляров
                label1.BeginInvoke((MethodInvoker)(() => this.label1.Text = "Информация: создание экземпляров"));
                wordDocument.Range().Copy();
                var rangeAllDocumentEkz = wordDocument.Content;
                rangeAllDocumentEkz.Find.ClearFormatting();
                //for (int j = 0; j < keList.Count; j++)
                {
                    functionReplaceInText(wordDocument, oWord, "{numberEkz}", "1");
                }
                for (int i = 0; i < Convert.ToInt32(countEkz) - 1; i++)
                {
                    //В конец документа
                    object what = Word.WdGoToItem.wdGoToLine;
                    object which = Word.WdGoToDirection.wdGoToLast;
                    Word.Range endRange = wordDocument.GoTo(ref what, ref which);
                    //Создаем разрыв РАЗДЕЛА (не страниц)
                    endRange.InsertBreak(Word.WdBreakType.wdSectionBreakNextPage);
                    //Вставляем
                    endRange.Paste();
                    rangeAllDocumentEkz = wordDocument.Content;
                    rangeAllDocumentEkz.Find.ClearFormatting();
                    rangeAllDocumentEkz.Find.Execute(FindText: "{numberEkz}", ReplaceWith: i + 2);
                }
                //Очистка буфера
                Clipboard.Clear();
                //Сохранение
                label1.BeginInvoke((MethodInvoker)(() => this.label1.Text = "Информация: сохранение"));
                DateTime dateNowFileName = DateTime.Now;
                string strSaveName = Convert.ToString(dateNowFileName);
                strSaveName = strSaveName.Replace(':', '-');
                if (!Directory.Exists(Properties.Settings.Default.PathForReports))
                    Directory.CreateDirectory(Directory.GetCurrentDirectory() + "/reports");
                savePath = Properties.Settings.Default.PathForReports + "/Месячная потребность в КЭ" + " " + strSaveName + ".docx";
                wordDocument.SaveAs(savePath);
                wordDocument.Close();
                oWord.Quit();
                label1.BeginInvoke((MethodInvoker)(() => this.label1.Text = "Информация: потребность в КЭ создана"));
                MessageBox.Show("Месячная потребность в КЭ создана!");
            }
            catch (Exception ex)
            {
                oWord.Visible = true;
                label1.BeginInvoke((MethodInvoker)(() => this.label1.Text = "Информация: ошибка!"));
                MessageBox.Show("error: " + ex.Message);
            }
        }
        #endregion

        //Печать месячной потребности в КЭ (НС)
        #region МЕСЯЧНАЯ ПОТРЕБНОСТЬ В КЭ (НС)
        private void button13_Click(object sender, EventArgs e)
        {
            button13.BackColor = choseColorBTN;
            BlockAllButton();
            if (Properties.Settings.Default.OtraslMesplPath.Length > 0)
            {
                if (Properties.Settings.Default.OtraslPath.Length > 0)
                {
                    if (Properties.Settings.Default.RashGodFolderPath.Length > 0)
                    {
                        try
                        {
                            FormChoseMespl fcm = new FormChoseMespl();
                            fcm.ShowDialog();
                            if (fcm.isExit)
                            {
                                label1.BeginInvoke((MethodInvoker)(() => this.label1.Text = "Информация: подготовка к печати"));
                                Thread thread = new Thread(() =>
                                {
                                    try
                                    {
                                        List<MesplKE> keList = new List<MesplKE>();
                                        //Получение потребности в КЭ
                                        keList = GetMesplKE(keList, fcm.mesac, fcm.godr);
                                        //Заполнение потребности в комплектующих
                                        keList = FillMesplKE(keList);
                                        //Печать потребности в КЭ
                                        string savePath = null;
                                        PrintMesplKENS(keList, fcm.nameMesac, fcm.godr, fcm.uchNumber, fcm.countEkz, out savePath);
                                        //Открытие документа
                                        if (checkBox3.Checked)
                                            OpenTheDocument(savePath, label1);
                                    }
                                    catch (Exception ex)
                                    {
                                        MessageBox.Show("Очередная ошибка ин поток\n" + ex.Message + "\n" + ex.Source + "\n" + ex.StackTrace + "\n" + ex.TargetSite, "Ошибка", MessageBoxButtons.OK, MessageBoxIcon.Error);
                                    }
                                    finally
                                    {
                                        BlockAllButton(false);
                                    }
                                });
                                thread.SetApartmentState(ApartmentState.STA);
                                thread.Start();
                            }
                            else
                                BlockAllButton(false);
                        }
                        catch (Exception ex)
                        {
                            BlockAllButton(false);
                            MessageBox.Show("Очередная ошибка\n" + ex.Message + "\n" + ex.Source + "\n" + ex.StackTrace + "\n" + ex.TargetSite, "Ошибка", MessageBoxButtons.OK, MessageBoxIcon.Error);
                        }
                    }
                    else
                    {
                        BlockAllButton(false);
                        MessageBox.Show("Выберите поддиректорию RAHGOD");
                    }
                }
                else
                {
                    BlockAllButton(false);
                    MessageBox.Show("Выберите директорию OTRASL");
                }
            }
            else
            {
                BlockAllButton(false);
                MessageBox.Show("Выберите директорию OTRASL1");
            }
        }

        private void PrintMesplKENS(List<MesplKE> keList, string nameMesac, string godr, string uchNumber, string countEkz, out string savePath)
        {
            savePath = null;
            
            label1.BeginInvoke((MethodInvoker)(() => this.label1.Text = "Информация: подготовка к печати"));
            //MessageBox.Show("Печатаем месячную потребность");
            //Сортируем потребность
            var sortedList = keList.OrderBy(a => a.kodel);
            //Создаем документ
            Word.Application oWord = new Word.Application();
            oWord.Visible = checkBox1.Checked;
            try
            {
                //Открытие документа
                Word.Document wordDocument;
                string path = Directory.GetCurrentDirectory() + templateFileNameWordQueryPotrNS;
                //MessageBox.Show("Путь: " + path);
                wordDocument = oWord.Documents.Open(path);
                //Поиск и замена текста
                functionReplaceInText(wordDocument, oWord, "{uchNumber}", uchNumber);
                functionReplaceInText(wordDocument, oWord, "{year}", godr);
                functionReplaceInText(wordDocument, oWord, "{month}", nameMesac);
                //Добавление в файл таблицы
                Object autoFitBehavior = Word.WdAutoFitBehavior.wdAutoFitWindow;
                //Добавление в файл таблицы
                var range = wordDocument.Paragraphs[wordDocument.Paragraphs.Count - 1].Range;
                wordDocument.Tables.Add(range, 1, 11, autoFitBehavior);
                Word.Table oTable = wordDocument.Tables[1];
                //Запрет на перенос строк
                oTable.Rows.AllowBreakAcrossPages = 0;
                string[] nameHeaderTable = { "Комплектующий\nэлемент", "Единица\nизмерения", "Потребность\nна\nмесяц", "", "Комплектующий\nэлемент", "Единица\nизмерения", "Потребность\nна\nмесяц", "", "Комплектующий\nэлемент", "Единица\nизмерения", "Потребность\nна\nмесяц" };
                for (int i = 0; i < oTable.Columns.Count; i++)
                {
                    Word.Range wordCellRange = oTable.Cell(1, i + 1).Range;
                    wordCellRange.ParagraphFormat.Alignment = Word.WdParagraphAlignment.wdAlignParagraphCenter;
                    wordCellRange.Text = nameHeaderTable[i];
                    wordCellRange.Font.Name = "Times New Roman";
                }
                //Скрываем колонку
                oTable.Columns[4].Borders[Word.WdBorderType.wdBorderTop].LineStyle = Word.WdLineStyle.wdLineStyleNone;
                oTable.Columns[4].Borders[Word.WdBorderType.wdBorderLeft].LineStyle = Word.WdLineStyle.wdLineStyleNone;
                oTable.Columns[4].Borders[Word.WdBorderType.wdBorderBottom].LineStyle = Word.WdLineStyle.wdLineStyleNone;
                oTable.Columns[4].Borders[Word.WdBorderType.wdBorderRight].LineStyle = Word.WdLineStyle.wdLineStyleNone;
                oTable.Columns[4].Borders[Word.WdBorderType.wdBorderLeft].LineStyle = Word.WdLineStyle.wdLineStyleSingle;
                oTable.Columns[4].Borders[Word.WdBorderType.wdBorderRight].LineStyle = Word.WdLineStyle.wdLineStyleSingle;
                oTable.Columns[8].Borders[Word.WdBorderType.wdBorderTop].LineStyle = Word.WdLineStyle.wdLineStyleNone;
                oTable.Columns[8].Borders[Word.WdBorderType.wdBorderLeft].LineStyle = Word.WdLineStyle.wdLineStyleNone;
                oTable.Columns[8].Borders[Word.WdBorderType.wdBorderBottom].LineStyle = Word.WdLineStyle.wdLineStyleNone;
                oTable.Columns[8].Borders[Word.WdBorderType.wdBorderRight].LineStyle = Word.WdLineStyle.wdLineStyleNone;
                oTable.Columns[8].Borders[Word.WdBorderType.wdBorderLeft].LineStyle = Word.WdLineStyle.wdLineStyleSingle;
                oTable.Columns[8].Borders[Word.WdBorderType.wdBorderRight].LineStyle = Word.WdLineStyle.wdLineStyleSingle;
                //Вывод
                int numeric = 1;
                int posRows = 2;
                int posCols = 0;
                int numericElement = 1;
                oTable.Rows.Add();
                oTable.Rows[posRows].Cells.VerticalAlignment = Word.WdCellVerticalAlignment.wdCellAlignVerticalCenter;
                foreach (MesplKE ke in sortedList)
                {
                    label1.BeginInvoke((MethodInvoker)(() => this.label1.Text = "Информация: печать элемента " + numericElement++ + " из " + keList.Count));
                    Word.Range wordCellRange = oTable.Cell(posRows, posCols + 1).Range;
                    string hertOrIndiz = ke.snindiz;
                    if (ke.snindiz == "")
                        hertOrIndiz += ke.snhert;
                    if (dataBaseSNS[ke.kodel] != null)
                        hertOrIndiz = dataBaseSNS[ke.kodel].name.Replace("\r", "");
                    wordCellRange.Text = hertOrIndiz;
                    wordCellRange.ParagraphFormat.Alignment = Word.WdParagraphAlignment.wdAlignParagraphLeft;
                    numeric++;
                    wordCellRange = oTable.Cell(posRows, posCols + 2).Range;
                    wordCellRange.Text = ke.naimeiz;
                    wordCellRange = oTable.Cell(posRows, posCols + 3).Range;
                    wordCellRange.Text = Convert.ToString(ke.qpotr);
                    posCols += 4;
                    if (posCols > 8)
                    {
                        oTable.Rows.Add();
                        posRows++;
                        posCols = 0;
                        oTable.Rows[posRows].Cells.VerticalAlignment = Word.WdCellVerticalAlignment.wdCellAlignVerticalCenter;
                    }
                }
                //Повтор заголовка
                oTable.Rows[1].HeadingFormat = -1;
                //Вертикальное центрирование заголовка
                oTable.Rows[1].Cells.VerticalAlignment = Word.WdCellVerticalAlignment.wdCellAlignVerticalCenter;
                //Создание экземпляров
                label1.BeginInvoke((MethodInvoker)(() => this.label1.Text = "Информация: создание экземпляров"));
                wordDocument.Range().Copy();
                var rangeAllDocumentEkz = wordDocument.Content;
                rangeAllDocumentEkz.Find.ClearFormatting();
                //for (int j = 0; j < keList.Count; j++)
                {
                    functionReplaceInText(wordDocument, oWord, "{numberEkz}", "1");
                }
                for (int i = 0; i < Convert.ToInt32(countEkz) - 1; i++)
                {
                    //В конец документа
                    object what = Word.WdGoToItem.wdGoToLine;
                    object which = Word.WdGoToDirection.wdGoToLast;
                    Word.Range endRange = wordDocument.GoTo(ref what, ref which);
                    //Создаем разрыв РАЗДЕЛА (не страниц)
                    endRange.InsertBreak(Word.WdBreakType.wdSectionBreakNextPage);
                    //Вставляем
                    endRange.Paste();
                    rangeAllDocumentEkz = wordDocument.Content;
                    rangeAllDocumentEkz.Find.ClearFormatting();
                    rangeAllDocumentEkz.Find.Execute(FindText: "{numberEkz}", ReplaceWith: i + 2);
                }
                //Очистка буфера
                Clipboard.Clear();
                //Сохранение
                label1.BeginInvoke((MethodInvoker)(() => this.label1.Text = "Информация: сохранение"));
                DateTime dateNowFileName = DateTime.Now;
                string strSaveName = Convert.ToString(dateNowFileName);
                strSaveName = strSaveName.Replace(':', '-');
                if (!Directory.Exists(Properties.Settings.Default.PathForReports))
                    Directory.CreateDirectory(Directory.GetCurrentDirectory() + "/reports");
                savePath = Properties.Settings.Default.PathForReports + "/Месячная потребность в КЭ (НС)" + " " + strSaveName + ".docx";
                wordDocument.SaveAs(savePath);
                wordDocument.Close();
                oWord.Quit();
                label1.BeginInvoke((MethodInvoker)(() => this.label1.Text = "Информация: потребность в КЭ (НС) создана"));
                MessageBox.Show("Месячная потребность в КЭ (НС) создана!");
            }
            catch (Exception ex)
            {
                oWord.Visible = true;
                label1.BeginInvoke((MethodInvoker)(() => this.label1.Text = "Информация: ошибка!"));
                MessageBox.Show("error: " + ex.Message);
            }
        }
        #endregion

        //Печать месячной справки-обоснования в КЭ
        #region МЕСЯЧНАЯ СПРАВКА-ОБОСНОВАНИЕ
        private void button8_Click(object sender, EventArgs e)
        {
            button8.BackColor = choseColorBTN;
            BlockAllButton();
            if (Properties.Settings.Default.OtraslMesplPath.Length > 0)
            {
                if (Properties.Settings.Default.OtraslPath.Length > 0)
                {
                    if (Properties.Settings.Default.RashGodFolderPath.Length > 0)
                    {
                        try
                        {
                            FormChoseMespl fcm = new FormChoseMespl();
                            fcm.ShowDialog();
                            if (fcm.isExit)
                            {
                                label1.BeginInvoke((MethodInvoker)(() => this.label1.Text = "Информация: подготовка к печати"));
                                Thread thread = new Thread(() =>
                                {
                                    try
                                    {
                                        List<MesplKE> keList = new List<MesplKE>();
                                        //Получение потребности в КЭ
                                        keList = GetMesplKE(keList, fcm.mesac, fcm.godr);
                                        //Заполнение потребности в комплектующих
                                        keList = FillMesplKE(keList);
                                        //Заполняем справку-обоснование
                                        keList = FillMesplSO(keList);
                                        //Печать справки-обоснования
                                        string savePath = null;
                                        string savePath2 = null;
                                        string countList = null;
                                        PrintMesplSO(keList, fcm.nameMesac, fcm.godr, fcm.uchNumber, fcm.countEkz, out countList, out savePath);
                                        //Создание уголка
                                        FormChoseUgolok fcu = new FormChoseUgolok(fcm.uchNumber, fcm.countEkz, countList);
                                        fcu.ShowDialog();
                                        if (fcu.isExit)
                                            CreateUgolok(fcu.uchNumber, fcu.countEkz, fcu.countList, fcu.HMD, fcu.author, fcu.date, out savePath2, label1);
                                        //Открытие документа
                                        if (checkBox3.Checked)
                                        {
                                            OpenTheDocument(savePath, label1);
                                            OpenTheDocument(savePath2, label1);
                                        }
                                    }
                                    catch (Exception ex)
                                    {
                                        MessageBox.Show("Очередная ошибка ин поток\n" + ex.Message + "\n" + ex.Source + "\n" + ex.StackTrace + "\n" + ex.TargetSite, "Ошибка", MessageBoxButtons.OK, MessageBoxIcon.Error);
                                    }
                                    finally
                                    {
                                        BlockAllButton(false);
                                    }
                                });
                                thread.SetApartmentState(ApartmentState.STA);
                                thread.Start();
                            }
                            else
                                BlockAllButton(false);
                        }
                        catch (Exception ex)
                        {
                            BlockAllButton(false);
                            MessageBox.Show("Очередная ошибка\n" + ex.Message + "\n" + ex.Source + "\n" + ex.StackTrace + "\n" + ex.TargetSite, "Ошибка", MessageBoxButtons.OK, MessageBoxIcon.Error);
                        }
                    }
                    else
                    {
                        BlockAllButton(false);
                        MessageBox.Show("Выберите поддиректорию RAHGOD");
                    }
                }
                else
                {
                    BlockAllButton(false);
                    MessageBox.Show("Выберите директорию OTRASL");
                }
            }
            else
            {
                BlockAllButton(false);
                MessageBox.Show("Выберите директорию OTRASL1");
            }
        }

        private List<MesplKE> FillMesplSO(List<MesplKE> keList)
        {
            //Получение информации о справке-обосновании
            label1.BeginInvoke((MethodInvoker)(() => this.label1.Text = "Информация: получение справки-обоснование"));
            string strConnection = @"Provider=Microsoft.JET.Oledb.4.0;Data Source=" + Properties.Settings.Default.OtraslMesplPath + "/MESPL/PLAN;Extended Properties=dBASE IV;User ID=;Password=;";
            using (_connection = new OleDbConnection(strConnection))
            {
                _connection.Open();
                using (OleDbCommand cmd = _connection.CreateCommand())
                {
                    foreach (MesplKE ke in keList)
                    {
                        if (ke.kodel != null && ke.kodel != "")
                        {
                            cmd.CommandText = "SELECT * FROM SPOBM.DBF WHERE KODEL='" + ke.kodel + "' AND MESAC='" + ke.mesac + "' AND GODR='" + ke.godr + "'";
                            using (OleDbDataReader reader = cmd.ExecuteReader())
                            {
                                while (reader.Read())
                                {
                                    MesplPlanIsp mpi = new MesplPlanIsp();
                                    mpi.kodel = Convert.ToString(reader["KODIL"]);
                                    mpi.kodotr = Convert.ToString(reader["KODOTR"]);
                                    mpi.vist = Convert.ToString(reader["VISP"]);
                                    ke.soList.Add(mpi);
                                }
                            }
                        }
                        else
                            MessageBox.Show("В потребности элемент без кода!", "Косяк", MessageBoxButtons.OK, MessageBoxIcon.Error);
                    }
                }
            }
            //Получение информации о элементе
            label1.BeginInvoke((MethodInvoker)(() => this.label1.Text = "Информация: получение информации о элементах"));
            strConnection = @"Provider=Microsoft.JET.Oledb.4.0;Data Source=" + Properties.Settings.Default.OtraslPath + "/SPNAIM;Extended Properties=dBASE IV;User ID=;Password=;";
            using (_connection = new OleDbConnection(strConnection))
            {
                _connection.Open();
                using (OleDbCommand cmd = _connection.CreateCommand())
                {
                    foreach (MesplKE ke in keList)
                    {
                        foreach (MesplPlanIsp mpi in ke.soList)
                        {
                            if (mpi.kodel != null && mpi.kodel != "")
                            {
                                cmd.CommandText = "SELECT * FROM SPNAIM.DBF WHERE KODEL='" + mpi.kodel + "'";
                                using (OleDbDataReader reader = cmd.ExecuteReader())
                                {
                                    while (reader.Read())
                                    {
                                        mpi.snhert = Convert.ToString(reader["SNHERT"] ?? "");

                                        mpi.snindiz = Convert.ToString(reader["SNINDIZ"] ?? "");

                                        mpi.snnaim = Convert.ToString(reader["SNNAIM1"] ?? "") + "\n" + Convert.ToString(reader["SNNAIM2"] ?? "") + "\n" +
                                            Convert.ToString(reader["SNNAIM3"] ?? "") + "\n" + Convert.ToString(reader["SNNAIM4"] ?? "") + "\n" +
                                            Convert.ToString(reader["SNNAIM5"] ?? "") + "\n" + Convert.ToString(reader["SNNAIM6"] ?? "") + "\n" +
                                            Convert.ToString(reader["SNNAIM7"] ?? "") + "\n" + Convert.ToString(reader["SNNAIM8"] ?? "") + "\n" +
                                            Convert.ToString(reader["SNNAIM9"] ?? "") + "\n" + Convert.ToString(reader["SNNAIM10"] ?? "");
                                        mpi.snnaim = mpi.snnaim.TrimEnd('\n');
                                        mpi.snnaim = mpi.snnaim.Replace('*', '"');
                                    }
                                }
                            }
                        }
                    }
                }
            }
            //Получение информации о заводе
            label1.BeginInvoke((MethodInvoker)(() => this.label1.Text = "Информация: получение информации о заводах"));
            strConnection = @"Provider=Microsoft.JET.Oledb.4.0;Data Source=" + Properties.Settings.Default.OtraslPath + "/SPRXZ;Extended Properties=dBASE IV;User ID=;Password=;";
            using (_connection = new OleDbConnection(strConnection))
            {
                _connection.Open();
                using (OleDbCommand cmd = _connection.CreateCommand())
                {
                    foreach (MesplKE ke in keList)
                    {
                        foreach (MesplPlanIsp mpi in ke.soList)
                        {
                            cmd.CommandText = "SELECT KODOTR, NZAV1, NZAV2, NZAV3, INN FROM SPRXZ.DBF WHERE KODOTR='" + mpi.kodotr + "'";
                            using (OleDbDataReader reader = cmd.ExecuteReader())
                            {
                                while (reader.Read())
                                {
                                    mpi.nzav = Convert.ToString(reader["NZAV1"]) + "\n" + Convert.ToString(reader["NZAV2"]) + "\n" + Convert.ToString(reader["NZAV3"]);
                                    mpi.nzav = mpi.nzav.TrimEnd('\n');
                                    mpi.nzav = mpi.nzav.Replace('*', '"');
                                    mpi.inn = Convert.ToString(reader["INN"]);
                                }
                            }
                        }
                    }
                }
            }
            return keList;
        }

        /// <summary>
        /// Печать справки-обоснования
        /// </summary>
        /// <param name="keList">Лист потребности + справка-обоснование</param>
        /// <param name="nameMesac">Наименование месяца</param>
        /// <param name="godr">Год</param>
        /// <param name="uchNumber">Учётный номер</param>
        /// <param name="countEkz">Кол-во экземпляров</param>
        /// <param name="countList">Количество листов для уголка</param>
        /// <param name="savePath">Путь для открытия документа</param>
        private void PrintMesplSO(List<MesplKE> keList, string nameMesac, string godr, string uchNumber, string countEkz, out string countList, out string savePath)
        {
            savePath = null;
            countList = "";

            label1.BeginInvoke((MethodInvoker)(() => this.label1.Text = "Информация: подготовка к печати"));
            //Сортируем потребность
            var sortedList = keList.OrderBy(a => a.kodel);
            //Создаем документ
            Word.Application oWord = new Word.Application();
            oWord.Visible = checkBox1.Checked;
            try
            {
                //Открытие документа
                Word.Document wordDocument;
                string path = Directory.GetCurrentDirectory() + templateFileNameWord;
                //MessageBox.Show("Путь: " + path);
                wordDocument = oWord.Documents.Open(path);
                //Поиск и замена текста
                functionReplaceInText(wordDocument, oWord, "{uchNumber}", uchNumber);
                functionReplaceInText(wordDocument, oWord, "{year}", godr);
                functionReplaceInText(wordDocument, oWord, "{month}", nameMesac);
                //Добавление в файл таблицы
                Object autoFitBehavior = Word.WdAutoFitBehavior.wdAutoFitWindow;
                //Добавление в файл таблицы
                var range = wordDocument.Paragraphs[wordDocument.Paragraphs.Count - 1].Range;
                wordDocument.Tables.Add(range, 1, 7, autoFitBehavior);
                Word.Table oTable = wordDocument.Tables[1];
                //Запрет на перенос строк
                oTable.Rows.AllowBreakAcrossPages = 0;
                string[] nameHeaderTable = { "Код/Комплектующий\nэлемент", "Код/Единица\nизмерения", "Потребность\nна месяц", "Наличие\nна складе", "Код/Испытуемый\nэлемент", "Код/Завод-\nизготовитель", "Объём\nиспытаний" };
                for (int i = 0; i < oTable.Columns.Count; i++)
                {
                    Word.Range wordCellRange = oTable.Cell(1, i + 1).Range;
                    wordCellRange.ParagraphFormat.Alignment = Word.WdParagraphAlignment.wdAlignParagraphCenter;
                    wordCellRange.Text = nameHeaderTable[i];
                    wordCellRange.Font.Name = "Times New Roman";
                }
                //Вывод
                int posRows = 2;
                int numericElement = 1;
                foreach (MesplKE ke in sortedList)
                {
                    label1.BeginInvoke((MethodInvoker)(() => this.label1.Text = "Информация: печать элемента " + numericElement + " из " + keList.Count));
                    oTable.Rows.Add();
                    //Комплектующий
                    Word.Range wordCellRange = oTable.Cell(posRows, 1).Range;
                    if (!string.IsNullOrWhiteSpace(ke.kodel))
                        wordCellRange.Text = "Код " + Convert.ToString(ke.kodel) + "\n\n" + ke.snhert + "\n" + ke.snindiz + "\n" + ke.snnaim;
                    else
                        wordCellRange.Text = Convert.ToString(ke.kodel) + "\n\n" + ke.snhert + "\n" + ke.snindiz + "\n" + ke.snnaim;
                    wordCellRange.ParagraphFormat.Alignment = Word.WdParagraphAlignment.wdAlignParagraphLeft;
                    wordCellRange.Cells.VerticalAlignment = Word.WdCellVerticalAlignment.wdCellAlignVerticalTop;
                    //Ед. измерения
                    wordCellRange = oTable.Cell(posRows, 2).Range;
                    if (!string.IsNullOrWhiteSpace(ke.kodeiz))
                        wordCellRange.Text = "Код " + Convert.ToString(ke.kodeiz) + "\n\n\n\n" + ke.naimeiz;
                    else
                        wordCellRange.Text = Convert.ToString(ke.kodeiz) + "\n\n\n\n" + ke.naimeiz;
                    wordCellRange.Cells.VerticalAlignment = Word.WdCellVerticalAlignment.wdCellAlignVerticalTop;
                    //Потребность на месяц
                    wordCellRange = oTable.Cell(posRows, 3).Range;
                    wordCellRange.Text = "\n\n\n\n" + Convert.ToString(ke.qpotr);
                    wordCellRange.Cells.VerticalAlignment = Word.WdCellVerticalAlignment.wdCellAlignVerticalTop;
                    //Наличие на складе

                    //Испытуемые элементы
                    bool isNewRow = false;
                    foreach (MesplPlanIsp mpi in ke.soList)
                    {
                        if (isNewRow)
                        {
                            oTable.Rows.Add();
                            posRows++;
                        }
                        //Испытуемый элемент
                        wordCellRange = oTable.Cell(posRows, 5).Range;
                        if (!string.IsNullOrWhiteSpace(mpi.kodel))
                            wordCellRange.Text = "Код " + Convert.ToString(mpi.kodel) + "\n\n" + mpi.snhert + "\n" + mpi.snindiz + "\n" + mpi.snnaim;
                        else
                            wordCellRange.Text = Convert.ToString(mpi.kodel) + "\n\n" + mpi.snhert + "\n" + mpi.snindiz + "\n" + mpi.snnaim;
                        wordCellRange.ParagraphFormat.Alignment = Word.WdParagraphAlignment.wdAlignParagraphLeft;
                        wordCellRange.Cells.VerticalAlignment = Word.WdCellVerticalAlignment.wdCellAlignVerticalTop;
                        //Завод-изготовитель
                        wordCellRange = oTable.Cell(posRows, 6).Range;
                        if (!string.IsNullOrWhiteSpace(mpi.kodotr))
                            wordCellRange.Text = "Код " + Convert.ToString(mpi.kodotr) + "\n\n\n\n" + mpi.nzav;
                        else
                            wordCellRange.Text = Convert.ToString(mpi.kodotr) + "\n\n\n\n" + mpi.nzav;
                        wordCellRange.Cells.VerticalAlignment = Word.WdCellVerticalAlignment.wdCellAlignVerticalTop;
                        //Объём испытаний
                        wordCellRange = oTable.Cell(posRows, 7).Range;
                        wordCellRange.Text = "\n\n\n\n" + Convert.ToString(mpi.vist);
                        wordCellRange.Cells.VerticalAlignment = Word.WdCellVerticalAlignment.wdCellAlignVerticalTop;
                        isNewRow = true;
                    }
                    posRows++;
                    numericElement++;
                }
                //Повтор заголовков
                oTable.Rows[1].HeadingFormat = -1;
                //Вертикальное центрирование заголовка
                oTable.Rows[1].Cells.VerticalAlignment = Word.WdCellVerticalAlignment.wdCellAlignVerticalCenter;

                //Количество листов для уголка
                countList = wordDocument.ComputeStatistics(Word.WdStatistic.wdStatisticPages, false).ToString();

                //Создание экземпляров
                label1.BeginInvoke((MethodInvoker)(() => this.label1.Text = "Информация: создание экземпляров"));
                wordDocument.Range().Copy();
                var rangeAllDocumentEkz = wordDocument.Content;
                rangeAllDocumentEkz.Find.ClearFormatting();
                //for (int j = 0; j < keList.Count; j++)
                {
                    functionReplaceInText(wordDocument, oWord, "{numberEkz}", "1");
                }
                for (int i = 0; i < Convert.ToInt32(countEkz) - 1; i++)
                {
                    //В конец документа
                    object what = Word.WdGoToItem.wdGoToLine;
                    object which = Word.WdGoToDirection.wdGoToLast;
                    Word.Range endRange = wordDocument.GoTo(ref what, ref which);
                    //Создаем разрыв РАЗДЕЛА (не страниц)
                    endRange.InsertBreak(Word.WdBreakType.wdSectionBreakNextPage);
                    //Вставляем
                    endRange.Paste();
                    rangeAllDocumentEkz = wordDocument.Content;
                    rangeAllDocumentEkz.Find.ClearFormatting();
                    rangeAllDocumentEkz.Find.Execute(FindText: "{numberEkz}", ReplaceWith: i + 2);
                }
                //Очистка буфера
                Clipboard.Clear();
                //Сохранение
                label1.BeginInvoke((MethodInvoker)(() => this.label1.Text = "Информация: сохранение"));
                DateTime dateNowFileName = DateTime.Now;
                string strSaveName = Convert.ToString(dateNowFileName);
                strSaveName = strSaveName.Replace(':', '-');
                if (!Directory.Exists(Properties.Settings.Default.PathForReports))
                    Directory.CreateDirectory(Directory.GetCurrentDirectory() + "/reports");
                savePath = Properties.Settings.Default.PathForReports + "/Месячная справка-обоснование потребности в КЭ" + " " + strSaveName + ".docx";
                wordDocument.SaveAs(savePath);
                wordDocument.Close();
                oWord.Quit();
                label1.BeginInvoke((MethodInvoker)(() => this.label1.Text = "Информация: справка-обоснование в КЭ создана"));
                MessageBox.Show("Месячная справка-обоснование в КЭ создана!");
            }
            catch (Exception ex)
            {
                oWord.Visible = true;
                label1.BeginInvoke((MethodInvoker)(() => this.label1.Text = "Информация: ошибка!"));
                MessageBox.Show("error: " + ex.Message);
            }
        }
        #endregion

        //Печать месячной справки-обоснование в КЭ (НС)
        #region МЕСЯЧНАЯ СПРАВКА-ОБОСНОВАНИЕ В КЭ (НС)
        private void button14_Click(object sender, EventArgs e)
        {
            button14.BackColor = choseColorBTN;
            BlockAllButton();
            if (Properties.Settings.Default.OtraslMesplPath.Length > 0)
            {
                if (Properties.Settings.Default.OtraslPath.Length > 0)
                {
                    if (Properties.Settings.Default.RashGodFolderPath.Length > 0)
                    {
                        try
                        {
                            FormChoseMespl fcm = new FormChoseMespl();
                            fcm.ShowDialog();
                            if (fcm.isExit)
                            {
                                label1.BeginInvoke((MethodInvoker)(() => this.label1.Text = "Информация: подготовка к печати"));
                                Thread thread = new Thread(() =>
                                {
                                    try
                                    {
                                        List<MesplKE> keList = new List<MesplKE>();
                                        //Получение потребности в КЭ
                                        keList = GetMesplKE(keList, fcm.mesac, fcm.godr);
                                        //Заполнение потребности в комплектующих
                                        keList = FillMesplKE(keList);
                                        //Заполняем справку-обоснование
                                        keList = FillMesplSO(keList);
                                        //Печать справки-обоснования
                                        string savePath = null;
                                        PrintMesplSONS(keList, fcm.nameMesac, fcm.godr, fcm.uchNumber, fcm.countEkz, out savePath);
                                        //Открытие документа
                                        if (checkBox3.Checked)
                                            OpenTheDocument(savePath, label1);
                                    }
                                    catch (Exception ex)
                                    {
                                        MessageBox.Show("Очередная ошибка ин поток\n" + ex.Message + "\n" + ex.Source + "\n" + ex.StackTrace + "\n" + ex.TargetSite, "Ошибка", MessageBoxButtons.OK, MessageBoxIcon.Error);
                                    }
                                    finally
                                    {
                                        BlockAllButton(false);
                                    }
                                });
                                thread.SetApartmentState(ApartmentState.STA);
                                thread.Start();
                            }
                            else
                                BlockAllButton(false);
                        }
                        catch (Exception ex)
                        {
                            BlockAllButton(false);
                            MessageBox.Show("Очередная ошибка\n" + ex.Message + "\n" + ex.Source + "\n" + ex.StackTrace + "\n" + ex.TargetSite, "Ошибка", MessageBoxButtons.OK, MessageBoxIcon.Error);
                        }
                    }
                    else
                    {
                        BlockAllButton(false);
                        MessageBox.Show("Выберите поддиректорию RAHGOD");
                    }
                }
                else
                {
                    BlockAllButton(false);
                    MessageBox.Show("Выберите директорию OTRASL");
                }
            }
            else
            {
                BlockAllButton(false);
                MessageBox.Show("Выберите директорию OTRASL1");
            }
        }

        private void PrintMesplSONS(List<MesplKE> keList, string nameMesac, string godr, string uchNumber, string countEkz, out string savePath)
        {
            savePath = null;

            //Сортируем потребность
            var sortedList = keList.OrderBy(a => a.kodel);
            //Создаем документ
            Word.Application oWord = new Word.Application();
            oWord.Visible = checkBox1.Checked;
            try
            {
                //Открытие документа
                Word.Document wordDocument;
                string path = Directory.GetCurrentDirectory() + templateFileNameWordNS;
                //MessageBox.Show("Путь: " + path);
                wordDocument = oWord.Documents.Open(path);
                //Поиск и замена текста
                functionReplaceInText(wordDocument, oWord, "{uchNumber}", uchNumber);
                functionReplaceInText(wordDocument, oWord, "{year}", godr);
                functionReplaceInText(wordDocument, oWord, "{month}", nameMesac);
                //Добавление в файл таблицы
                Object autoFitBehavior = Word.WdAutoFitBehavior.wdAutoFitWindow;
                //Добавление в файл таблицы
                var range = wordDocument.Paragraphs[wordDocument.Paragraphs.Count - 1].Range;
                wordDocument.Tables.Add(range, 1, 7, autoFitBehavior);
                Word.Table oTable = wordDocument.Tables[1];
                //Запрет на перенос строк
                oTable.Rows.AllowBreakAcrossPages = 0;
                string[] nameHeaderTable = { "Комплектующий\nэлемент", "Единица\nизмерения", "Потребность\nна месяц", "Наличие\nна складе", "Испытуемый\nэлемент", "Завод-\nизготовитель", "Объём\nиспытаний" };
                for (int i = 0; i < oTable.Columns.Count; i++)
                {
                    Word.Range wordCellRange = oTable.Cell(1, i + 1).Range;
                    wordCellRange.ParagraphFormat.Alignment = Word.WdParagraphAlignment.wdAlignParagraphCenter;
                    wordCellRange.Text = nameHeaderTable[i];
                    wordCellRange.Font.Name = "Times New Roman";
                }
                //Вывод
                int posRows = 2;
                int numericElement = 1;
                foreach (MesplKE ke in sortedList)
                {
                    label1.BeginInvoke((MethodInvoker)(() => this.label1.Text = "Информация: печать элемента " + numericElement++ + " из " + keList.Count));
                    oTable.Rows.Add();
                    //Центрируем
                    oTable.Rows[posRows].Cells.VerticalAlignment = Word.WdCellVerticalAlignment.wdCellAlignVerticalCenter;
                    //Комплектующий
                    Word.Range wordCellRange = oTable.Cell(posRows, 1).Range;
                    string hertOrIndiz = ke.snindiz;
                    if (ke.snindiz == "")
                        hertOrIndiz += ke.snhert;
                    if (dataBaseSNS[ke.kodel] != null)
                        hertOrIndiz = dataBaseSNS[ke.kodel].name.Replace("\r", "");
                    wordCellRange.Text = hertOrIndiz;
                    wordCellRange.ParagraphFormat.Alignment = Word.WdParagraphAlignment.wdAlignParagraphLeft;
                    //Ед. измерения
                    wordCellRange = oTable.Cell(posRows, 2).Range;
                    wordCellRange.Text = ke.naimeiz;
                    //Потребность на месяц
                    wordCellRange = oTable.Cell(posRows, 3).Range;
                    wordCellRange.Text = Convert.ToString(ke.qpotr);
                    //Наличие на складе

                    //Испытуемые элементы
                    bool isNewRow = false;
                    foreach (MesplPlanIsp mpi in ke.soList)
                    {
                        if (isNewRow)
                        {
                            oTable.Rows.Add();
                            posRows++;
                            oTable.Rows[posRows].Cells.VerticalAlignment = Word.WdCellVerticalAlignment.wdCellAlignVerticalCenter;
                        }
                        //Испытуемый элемент
                        wordCellRange = oTable.Cell(posRows, 5).Range;
                        string hertOrIndizMPI = mpi.snindiz;
                        if (mpi.snindiz == "")
                            hertOrIndizMPI += mpi.snhert;
                        if (dataBaseSNS[mpi.kodel] != null)
                            hertOrIndizMPI = dataBaseSNS[mpi.kodel].name.Replace("\r", "");
                        wordCellRange.Text = hertOrIndizMPI;
                        wordCellRange.ParagraphFormat.Alignment = Word.WdParagraphAlignment.wdAlignParagraphLeft;
                        //Завод-изготовитель
                        wordCellRange = oTable.Cell(posRows, 6).Range;
                        wordCellRange.Text = mpi.nzav;
                        //Объём испытаний
                        wordCellRange = oTable.Cell(posRows, 7).Range;
                        wordCellRange.Text = Convert.ToString(mpi.vist);
                        isNewRow = true;
                    }
                    posRows++;
                }
                //Повтор заголовков
                oTable.Rows[1].HeadingFormat = -1;
                //Вертикальное центрирование заголовка
                oTable.Rows[1].Cells.VerticalAlignment = Word.WdCellVerticalAlignment.wdCellAlignVerticalCenter;
                //Создание экземпляров
                label1.BeginInvoke((MethodInvoker)(() => this.label1.Text = "Информация: создание экземпляров"));
                wordDocument.Range().Copy();
                var rangeAllDocumentEkz = wordDocument.Content;
                rangeAllDocumentEkz.Find.ClearFormatting();
                //for (int j = 0; j < keList.Count; j++)
                {
                    functionReplaceInText(wordDocument, oWord, "{numberEkz}", "1");
                }
                for (int i = 0; i < Convert.ToInt32(countEkz) - 1; i++)
                {
                    //В конец документа
                    object what = Word.WdGoToItem.wdGoToLine;
                    object which = Word.WdGoToDirection.wdGoToLast;
                    Word.Range endRange = wordDocument.GoTo(ref what, ref which);
                    //Создаем разрыв РАЗДЕЛА (не страниц)
                    endRange.InsertBreak(Word.WdBreakType.wdSectionBreakNextPage);
                    //Вставляем
                    endRange.Paste();
                    rangeAllDocumentEkz = wordDocument.Content;
                    rangeAllDocumentEkz.Find.ClearFormatting();
                    rangeAllDocumentEkz.Find.Execute(FindText: "{numberEkz}", ReplaceWith: i + 2);
                }
                //Очистка буфера
                Clipboard.Clear();
                //Сохранение
                label1.BeginInvoke((MethodInvoker)(() => this.label1.Text = "Информация: сохранение"));
                DateTime dateNowFileName = DateTime.Now;
                string strSaveName = Convert.ToString(dateNowFileName);
                strSaveName = strSaveName.Replace(':', '-');
                if (!Directory.Exists(Properties.Settings.Default.PathForReports))
                    Directory.CreateDirectory(Directory.GetCurrentDirectory() + "/reports");
                savePath = Properties.Settings.Default.PathForReports + "/Месячная справка-обоснование потребности в КЭ (НС)" + " " + strSaveName + ".docx";
                wordDocument.SaveAs(savePath);
                wordDocument.Close();
                oWord.Quit();
                label1.BeginInvoke((MethodInvoker)(() => this.label1.Text = "Информация: справка-обоснование в КЭ (НС) созадана"));
                MessageBox.Show("Месячная справка-обоснование в КЭ (НС) создана!");
            }
            catch (Exception ex)
            {
                oWord.Visible = true;
                label1.BeginInvoke((MethodInvoker)(() => this.label1.Text = "Информация: ошибка!"));
                MessageBox.Show("error: " + ex.Message);
            }
        }
        #endregion

        //Печать месячного анализа готовности
        #region МЕСЯЧНЫЙ АНАЛИЗ ГОТОВНОСТИ
        private void button9_Click(object sender, EventArgs e)
        {
            button9.BackColor = choseColorBTN;
            BlockAllButton();
            if (Properties.Settings.Default.OtraslMesplPath.Length > 0)
            {
                if (Properties.Settings.Default.OtraslPath.Length > 0)
                {
                    if (Properties.Settings.Default.RashGodFolderPath.Length > 0)
                    {
                        try
                        {
                            FormChoseMespl fcm = new FormChoseMespl();
                            fcm.ShowDialog();
                            if (fcm.isExit)
                            {
                                label1.BeginInvoke((MethodInvoker)(() => this.label1.Text = "Информация: подготовка к печати"));
                                Thread thread = new Thread(() =>
                                {
                                    try
                                    {
                                        List<MesplPlanIsp> planIspList = new List<MesplPlanIsp>();
                                        //Получение плана испытаний
                                        planIspList = GetMesplPlanIsp(planIspList, fcm.mesac, fcm.godr);
                                        //Заполнение плана испытанйи
                                        planIspList = FillMesplPlanIsp(planIspList);
                                        //Заполнение анализа готовности
                                        planIspList = FillMesplAG(planIspList);
                                        //Печать анализа готовности
                                        string savePath = null;
                                        string savePath2 = null;
                                        string countList = null;
                                        PrintMesplAG(planIspList, fcm.nameMesac, fcm.godr, fcm.uchNumber, fcm.countEkz, out countList, out savePath);
                                        //Создание уголка
                                        FormChoseUgolok fcu = new FormChoseUgolok(fcm.uchNumber, fcm.countEkz, countList);
                                        fcu.ShowDialog();
                                        if (fcu.isExit)
                                            CreateUgolok(fcu.uchNumber, fcu.countEkz, fcu.countList, fcu.HMD, fcu.author, fcu.date, out savePath2, label1);
                                        //Открытие документа
                                        if (checkBox3.Checked)
                                        {
                                            OpenTheDocument(savePath, label1);
                                            OpenTheDocument(savePath2, label1);
                                        }
                                    }
                                    catch (Exception ex)
                                    {
                                        MessageBox.Show("Очередная ошибка ин поток\n" + ex.Message + "\n" + ex.Source + "\n" + ex.StackTrace + "\n" + ex.TargetSite, "Ошибка", MessageBoxButtons.OK, MessageBoxIcon.Error);
                                    }
                                    finally
                                    {
                                        BlockAllButton(false);
                                    }
                                });
                                thread.SetApartmentState(ApartmentState.STA);
                                thread.Start();
                            }
                            else
                                BlockAllButton(false);
                        }
                        catch (Exception ex)
                        {
                            BlockAllButton(false);
                            MessageBox.Show("Очередная ошибка\n" + ex.Message + "\n" + ex.Source + "\n" + ex.StackTrace + "\n" + ex.TargetSite, "Ошибка", MessageBoxButtons.OK, MessageBoxIcon.Error);
                        }
                    }
                    else
                    {
                        BlockAllButton(false);
                        MessageBox.Show("Выберите поддиректорию RAHGOD");
                    }
                }
                else
                {
                    BlockAllButton(false);
                    MessageBox.Show("Выберите директорию OTRASL");
                }
            }
            else
            {
                BlockAllButton(false);
                MessageBox.Show("Выберите директорию OTRASL1");
            }
        }

        private List<MesplPlanIsp> FillMesplAG(List<MesplPlanIsp> planIspList)
        {
            //Получение информации о комплектации
            label1.BeginInvoke((MethodInvoker)(() => this.label1.Text = "Информация: получение информации о комплектации"));
            string strConnection = @"Provider=Microsoft.JET.Oledb.4.0;Data Source=" + Properties.Settings.Default.OtraslMesplPath + "/MESPL/VK;Extended Properties=dBASE IV;User ID=;Password=;";
            using (_connection = new OleDbConnection(strConnection))
            {
                _connection.Open();
                using (OleDbCommand cmd = _connection.CreateCommand())
                {
                    foreach (MesplPlanIsp mpi in planIspList)
                    {
                        cmd.CommandText = "SELECT * FROM VK.DBF WHERE KODEL='" + mpi.kodel + "' AND SHVID='" + mpi.shvid + "'";
                        using (OleDbDataReader reader = cmd.ExecuteReader())
                        {
                            while (reader.Read())
                            {
                                MesplPlanIsp newMPI = new MesplPlanIsp();
                                newMPI.kodel = Convert.ToString(reader["SHEL"]);
                                newMPI.vist = Convert.ToString(Convert.ToDouble(reader["KELIZ"]) * Convert.ToDouble(mpi.vist));
                                newMPI.kodeiz = Convert.ToString(reader["KODEIZ"]);
                                mpi.agList.Add(newMPI);
                            }
                        }
                    }
                }
            }
            //Получение информации о элементе комплектации
            label1.BeginInvoke((MethodInvoker)(() => this.label1.Text = "Информация: получение информации о элементах"));
            strConnection = @"Provider=Microsoft.JET.Oledb.4.0;Data Source=" + Properties.Settings.Default.OtraslPath + "/SPNAIM;Extended Properties=dBASE IV;User ID=;Password=;";
            using (_connection = new OleDbConnection(strConnection))
            {
                _connection.Open();
                using (OleDbCommand cmd = _connection.CreateCommand())
                {
                    foreach (MesplPlanIsp mpi in planIspList)
                    {
                        foreach (MesplPlanIsp ag in mpi.agList)
                        {
                            if (ag.kodel != null && ag.kodel != "")
                            {
                                cmd.CommandText = "SELECT * FROM SPNAIM.DBF WHERE KODEL='" + ag.kodel + "'";
                                using (OleDbDataReader reader = cmd.ExecuteReader())
                                {
                                    while (reader.Read())
                                    {
                                        ag.snhert = Convert.ToString(reader["SNHERT"] ?? "");

                                        ag.snindiz = Convert.ToString(reader["SNINDIZ"] ?? "");

                                        ag.snnaim = Convert.ToString(reader["SNNAIM1"] ?? "") + "\n" + Convert.ToString(reader["SNNAIM2"] ?? "") + "\n" +
                                            Convert.ToString(reader["SNNAIM3"] ?? "") + "\n" + Convert.ToString(reader["SNNAIM4"] ?? "") + "\n" +
                                            Convert.ToString(reader["SNNAIM5"] ?? "") + "\n" + Convert.ToString(reader["SNNAIM6"] ?? "") + "\n" +
                                            Convert.ToString(reader["SNNAIM7"] ?? "") + "\n" + Convert.ToString(reader["SNNAIM8"] ?? "") + "\n" +
                                            Convert.ToString(reader["SNNAIM9"] ?? "") + "\n" + Convert.ToString(reader["SNNAIM10"] ?? "");
                                        ag.snnaim = ag.snnaim.TrimEnd('\n');
                                        ag.snnaim = ag.snnaim.Replace('*', '"');
                                    }
                                }
                            }
                        }
                    }
                }
            }
            try
            {
                //Получение кода поставщика
                label1.BeginInvoke((MethodInvoker)(() => this.label1.Text = "Информация: получение информации о поставщиках"));
                strConnection = @"Provider=Microsoft.JET.Oledb.4.0;Data Source=" + Properties.Settings.Default.OtraslPath + "/RAHGOD/SPRXZ;Extended Properties=dBASE IV;User ID=;Password=;";
                using (_connection = new OleDbConnection(strConnection))
                {
                    _connection.Open();
                    using (OleDbCommand cmd = _connection.CreateCommand())
                    {
                        foreach (MesplPlanIsp mpi in planIspList)
                        {
                            foreach (MesplPlanIsp ag in mpi.agList)
                            {
                                if (ag.kodel != null && ag.kodel != "")
                                {
                                    cmd.CommandText = "SELECT * FROM OBRASK.DBF WHERE KODEL='" + ag.kodel + "'";
                                    using (OleDbDataReader reader = cmd.ExecuteReader())
                                    {
                                        while (reader.Read())
                                        {
                                            ag.kodotr = Convert.ToString(reader["KODOTR"] ?? "");
                                            break;
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
            catch
            {
                MessageBox.Show("Ошибка при получении кода поставщика комплектации!");
            }
            //Получение информации о заводе
            strConnection = @"Provider=Microsoft.JET.Oledb.4.0;Data Source=" + Properties.Settings.Default.OtraslPath + "/SPRXZ;Extended Properties=dBASE IV;User ID=;Password=;";
            using (_connection = new OleDbConnection(strConnection))
            {
                _connection.Open();
                using (OleDbCommand cmd = _connection.CreateCommand())
                {
                    foreach (MesplPlanIsp mpi in planIspList)
                    {
                        foreach (MesplPlanIsp ag in mpi.agList)
                        {
                            cmd.CommandText = "SELECT KODOTR, NZAV1, NZAV2, NZAV3, INN FROM SPRXZ.DBF WHERE KODOTR='" + ag.kodotr + "'";
                            using (OleDbDataReader reader = cmd.ExecuteReader())
                            {
                                while (reader.Read())
                                {
                                    ag.nzav = Convert.ToString(reader["NZAV1"]) + "\n" + Convert.ToString(reader["NZAV2"]) + "\n" + Convert.ToString(reader["NZAV3"]);
                                    ag.nzav = ag.nzav.TrimEnd('\n');
                                    ag.nzav = ag.nzav.Replace('*', '"');
                                    ag.inn = Convert.ToString(reader["INN"]);
                                }
                            }
                        }
                    }
                }
            }
            //Получение информации о ед. измерения
            label1.BeginInvoke((MethodInvoker)(() => this.label1.Text = "Информация: получение информации о ед. измерений"));
            strConnection = @"Provider=Microsoft.JET.Oledb.4.0;Data Source=" + Properties.Settings.Default.OtraslPath + "/SPRAV;Extended Properties=dBASE IV;User ID=;Password=;";
            using (_connection = new OleDbConnection(strConnection))
            {
                _connection.Open();
                using (OleDbCommand cmd = _connection.CreateCommand())
                {
                    foreach (MesplPlanIsp mpi in planIspList)
                    {
                        foreach (MesplPlanIsp ag in mpi.agList)
                        {
                            cmd.CommandText = "SELECT * FROM SPEIZ2 WHERE KODEIZ='" + ag.kodeiz + "'";
                            using (OleDbDataReader reader = cmd.ExecuteReader())
                            {
                                while (reader.Read())
                                {
                                    ag.naimeiz = Convert.ToString(reader["NAIMEIZ"]);
                                    ag.naik = Convert.ToString(reader["NAIK"]);
                                }
                            }
                        }
                    }
                }
            }
            return planIspList;
        }

        private void PrintMesplAG(List<MesplPlanIsp> planIspList, string nameMesac, string godr, string uchNumber, string countEkz, out string countList, out string savePath)
        {
            savePath = null;
            countList = "";

            label1.BeginInvoke((MethodInvoker)(() => this.label1.Text = "Информация: подготовка к печати"));
            //Сортируем анализ готовности
            var sortedList = planIspList.OrderBy(a => a.kodotr).ThenBy(b => b.kodel).ThenBy(c => c.shvid);
            //Создаем документ
            Word.Application oWord = new Word.Application();
            oWord.Visible = checkBox1.Checked;
            try
            {
                //Открытие документа
                Word.Document wordDocument;
                string path = Directory.GetCurrentDirectory() + templateFileNameWordAG;
                //MessageBox.Show("Путь: " + path);
                wordDocument = oWord.Documents.Open(path);
                //Поиск и замена текста
                functionReplaceInText(wordDocument, oWord, "{uchNumber}", uchNumber);
                functionReplaceInText(wordDocument, oWord, "{year}", godr);
                functionReplaceInText(wordDocument, oWord, "{month}", nameMesac);
                //Добавление в файл таблицы
                Object autoFitBehavior = Word.WdAutoFitBehavior.wdAutoFitWindow;
                //Добавление в файл таблицы
                var range = wordDocument.Paragraphs[wordDocument.Paragraphs.Count - 1].Range;
                wordDocument.Tables.Add(range, 1, 11, autoFitBehavior);
                Word.Table oTable = wordDocument.Tables[1];
                //Запрет на перенос строк
                oTable.Rows.AllowBreakAcrossPages = 0;
                string[] nameHeaderTable = {
                                               "№ п/п", 
                                               "Код и наименование\nиспытуемого элемента (ИЭ)",
                                               "Код и\nнаименование\nвида испытания", 
                                               "Код и наименование\nзавода-\nизготовителя ИЭ", 
                                               "Объём\nпоставок",
                                               "Готовность",
                                               "Код и наименование\nкомплектующего элемента",
                                               "Код/\nЕд.\nизм.",
                                               "Объём\nпотребности КЭ",
                                               "Код, наименование и\nместонахождение\nорганизации-\nпоставщика\nИНН",
                                               "Документ\nподтверждающий\nобеспеченность КЭ"
                                           };
                for (int i = 0; i < oTable.Columns.Count; i++)
                {
                    Word.Range wordCellRange = oTable.Cell(1, i + 1).Range;
                    wordCellRange.ParagraphFormat.Alignment = Word.WdParagraphAlignment.wdAlignParagraphCenter;
                    wordCellRange.Text = nameHeaderTable[i];
                    wordCellRange.Font.Name = "Times New Roman";
                    wordCellRange.Font.Size = 10;
                }
                //Вывод
                int numberPosition = 1;
                int posRows = 2;
                foreach (MesplPlanIsp mpi in sortedList)
                {
                    label1.BeginInvoke((MethodInvoker)(() => this.label1.Text = "Информация: печать элемента " + numberPosition + " из " + planIspList.Count));
                    oTable.Rows.Add();
                    //Порядковый номер
                    Word.Range wordCellRange = oTable.Cell(posRows, 1).Range;
                    wordCellRange.Text = Convert.ToString(numberPosition);
                    //Испытуемый элемент
                    wordCellRange = oTable.Cell(posRows, 2).Range;
                    if (!string.IsNullOrWhiteSpace(mpi.kodel))
                        wordCellRange.Text = "Код " + Convert.ToString(mpi.kodel) + "\n\n" + mpi.snhert + "\n" + mpi.snindiz + "\n" + mpi.snnaim;
                    else
                        wordCellRange.Text = Convert.ToString(mpi.kodel) + "\n\n" + mpi.snhert + "\n" + mpi.snindiz + "\n" + mpi.snnaim;
                    wordCellRange.ParagraphFormat.Alignment = Word.WdParagraphAlignment.wdAlignParagraphLeft;
                    //Вид испытания
                    wordCellRange = oTable.Cell(posRows, 3).Range;
                    if (!string.IsNullOrWhiteSpace(mpi.shvid))
                        wordCellRange.Text = "Код " + Convert.ToString(mpi.shvid) + "\n\n\n\n" + mpi.nvid;
                    else
                        wordCellRange.Text = Convert.ToString(mpi.shvid) + "\n\n\n\n" + mpi.nvid;
                    //Завод-изготовитель ИЭ
                    wordCellRange = oTable.Cell(posRows, 4).Range;
                    if (!string.IsNullOrWhiteSpace(mpi.kodotr))
                        wordCellRange.Text = "Код " + Convert.ToString(mpi.kodotr) + "\n\n\n\n" + mpi.nzav;
                    else
                        wordCellRange.Text = Convert.ToString(mpi.kodotr) + "\n\n\n\n" + mpi.nzav;
                    //Объём поставок
                    wordCellRange = oTable.Cell(posRows, 5).Range;
                    wordCellRange.Text = "\n\n\n\n" + Convert.ToString(mpi.vist);
                    //Готовность

                    //Комплектующие элементы
                    bool isNewRow = false;
                    foreach (MesplPlanIsp ag in mpi.agList)
                    {
                        if (isNewRow)
                        {
                            oTable.Rows.Add();
                            posRows++;
                        }
                        //Комплектующий элемент
                        wordCellRange = oTable.Cell(posRows, 7).Range;
                        if (!string.IsNullOrWhiteSpace(ag.kodel))
                            wordCellRange.Text = "Код " + Convert.ToString(ag.kodel) + "\n\n" + ag.snhert + "\n" + ag.snindiz + "\n" + ag.snnaim;
                        else
                            wordCellRange.Text = Convert.ToString(ag.kodel) + "\n\n" + ag.snhert + "\n" + ag.snindiz + "\n" + ag.snnaim;
                        wordCellRange.ParagraphFormat.Alignment = Word.WdParagraphAlignment.wdAlignParagraphLeft;
                        //Ед. измерения.
                        wordCellRange = oTable.Cell(posRows, 8).Range;
                        if (!string.IsNullOrWhiteSpace(ag.kodeiz))
                            wordCellRange.Text = "Код " + Convert.ToString(ag.kodeiz) + "\n\n\n\n" + Convert.ToString(ag.naimeiz);
                        else
                            wordCellRange.Text = Convert.ToString(ag.kodeiz) + "\n\n\n\n" + Convert.ToString(ag.naimeiz);
                        //Объём потребности
                        wordCellRange = oTable.Cell(posRows, 9).Range;
                        wordCellRange.Text = "\n\n\n\n" + Convert.ToString(ag.vist);
                        //Организация-поставщик
                        wordCellRange = oTable.Cell(posRows, 10).Range;
                        if (!string.IsNullOrWhiteSpace(ag.kodotr))
                            wordCellRange.Text = "Код " + Convert.ToString(ag.kodotr) + "\n\n\n\n" + ag.nzav + "\n" + ag.inn;
                        else
                            wordCellRange.Text = Convert.ToString(ag.kodotr) + "\n\n\n\n" + ag.nzav + "\n" + ag.inn;
                        //Документ подтверждающий готовность

                        isNewRow = true;
                    }
                    numberPosition++;
                    posRows++;
                }
                //Повтор заголовков
                oTable.Rows[1].HeadingFormat = -1;
                //Попытка вертикально центрирования заголовка
                oTable.Rows[1].Cells.VerticalAlignment = Word.WdCellVerticalAlignment.wdCellAlignVerticalCenter;

                //Количество листов для уголка
                countList = wordDocument.ComputeStatistics(Word.WdStatistic.wdStatisticPages, false).ToString();

                //Создание экземпляров
                label1.BeginInvoke((MethodInvoker)(() => this.label1.Text = "Информация: создание экземпляров"));
                wordDocument.Range().Copy();
                var rangeAllDocumentEkz = wordDocument.Content;
                rangeAllDocumentEkz.Find.ClearFormatting();
                //for (int j = 0; j < planIspList.Count; j++)
                {
                    functionReplaceInText(wordDocument, oWord, "{numberEkz}", "1");
                }
                for (int i = 0; i < Convert.ToInt32(countEkz) - 1; i++)
                {
                    //В конец документа
                    object what = Word.WdGoToItem.wdGoToLine;
                    object which = Word.WdGoToDirection.wdGoToLast;
                    Word.Range endRange = wordDocument.GoTo(ref what, ref which);
                    //Создаем разрыв РАЗДЕЛА (не страниц)
                    endRange.InsertBreak(Word.WdBreakType.wdSectionBreakNextPage);
                    //Вставляем
                    endRange.Paste();
                    rangeAllDocumentEkz = wordDocument.Content;
                    rangeAllDocumentEkz.Find.ClearFormatting();
                    rangeAllDocumentEkz.Find.Execute(FindText: "{numberEkz}", ReplaceWith: i + 2);
                }
                //Очистка буфера
                Clipboard.Clear();
                //Сохранение
                label1.BeginInvoke((MethodInvoker)(() => this.label1.Text = "Информация: сохранение"));
                DateTime dateNowFileName = DateTime.Now;
                string strSaveName = Convert.ToString(dateNowFileName);
                strSaveName = strSaveName.Replace(':', '-');
                if (!Directory.Exists(Properties.Settings.Default.PathForReports))
                    Directory.CreateDirectory(Directory.GetCurrentDirectory() + "/reports");
                savePath = Properties.Settings.Default.PathForReports + "/Месячный анализ готовности" + " " + strSaveName + ".docx";
                wordDocument.SaveAs(savePath);
                wordDocument.Close();
                oWord.Quit();
                label1.BeginInvoke((MethodInvoker)(() => this.label1.Text = "Информация: анализ готовности готов"));
                MessageBox.Show("Месячный анализ готовности создан!");
            }
            catch (Exception ex)
            {
                oWord.Visible = true;
                label1.BeginInvoke((MethodInvoker)(() => this.label1.Text = "Информация: ошибка!"));
                MessageBox.Show("error: " + ex.Message);
            }
        }
        #endregion

        //Печать месячный EXCEL
        #region МЕСЯЧНЫЙ EXCEL (СВОРАЧИВАЕТСЯ)
        private void button20_Click(object sender, EventArgs e)
        {
            /*List<MesplPlanIsp> planIspList2 = new List<MesplPlanIsp>();
            PrintMesplExcel(planIspList2, "", "", "", "1");
            return;*/
            button20.BackColor = choseColorBTN;
            BlockAllButton();
            if (Properties.Settings.Default.OtraslMesplPath.Length > 0)
            {
                if (Properties.Settings.Default.OtraslPath.Length > 0)
                {
                    if (Properties.Settings.Default.RashGodFolderPath.Length > 0)
                    {
                        try
                        {
                            FormChoseMespl fcm = new FormChoseMespl();
                            fcm.ShowDialog();
                            if (fcm.isExit)
                            {
                                label1.BeginInvoke((MethodInvoker)(() => this.label1.Text = "Информация: подготовка к печати"));
                                Thread thread = new Thread(() =>
                                {
                                    try
                                    {
                                        List<MesplPlanIsp> planIspList = new List<MesplPlanIsp>();
                                        //Получение плана испытаний
                                        planIspList = GetMesplPlanIsp(planIspList, fcm.mesac, fcm.godr);
                                        //Заполнение плана испытанйи
                                        planIspList = FillMesplPlanIsp(planIspList);
                                        //Заполнение анализа готовности
                                        planIspList = FillMesplAG(planIspList);
                                        //Печать Excel (С)
                                        string savePath = null;
                                        PrintMesplExcel(planIspList, fcm.nameMesac, fcm.godr, fcm.uchNumber, fcm.countEkz, out savePath);
                                        //Открытие документа
                                        if (checkBox3.Checked)
                                            OpenTheDocument(savePath, label1);
                                    }
                                    catch (Exception ex)
                                    {
                                        MessageBox.Show("Очередная ошибка ин поток\n" + ex.Message + "\n" + ex.Source + "\n" + ex.StackTrace + "\n" + ex.TargetSite, "Ошибка", MessageBoxButtons.OK, MessageBoxIcon.Error);
                                    }
                                    finally
                                    {
                                        BlockAllButton(false);
                                    }
                                });
                                thread.SetApartmentState(ApartmentState.STA);
                                thread.Start();
                            }
                            else
                                BlockAllButton(false);
                        }
                        catch (Exception ex)
                        {
                            BlockAllButton(false);
                            MessageBox.Show("Очередная ошибка\n" + ex.Message + "\n" + ex.Source + "\n" + ex.StackTrace + "\n" + ex.TargetSite, "Ошибка", MessageBoxButtons.OK, MessageBoxIcon.Error);
                        }
                    }
                    else
                    {
                        BlockAllButton(false);
                        MessageBox.Show("Выберите поддиректорию RAHGOD");
                    }
                }
                else
                {
                    BlockAllButton(false);
                    MessageBox.Show("Выберите директорию OTRASL");
                }
            }
            else
            {
                BlockAllButton(false);
                MessageBox.Show("Выберите директорию OTRASL1");
            }
        }

        private void PrintMesplExcel(List<MesplPlanIsp> planIspList, string nameMesac, string godr, string uchNumber, string countEkz, out string savePath)
        {
            savePath = null;

            label1.BeginInvoke((MethodInvoker)(() => this.label1.Text = "Информация: подготовка к печати"));
            //Сортируем анализ готовности
            var sortedList = planIspList.OrderBy(a => a.kodotr).ThenBy(b => b.kodel).ThenBy(c => c.shvid);

            //Создаем документ
            Excel.Application oExcel = new Excel.Application();
            oExcel.Visible = checkBox1.Checked;
            try
            {
                //Открытие шаблона
                string path = Directory.GetCurrentDirectory() + templateFileNameExcel;
                Excel.Workbook excelDocument = oExcel.Workbooks.Open(path);

                Excel.Workbooks excelWorkBooks = oExcel.Workbooks;
                Excel.Workbook excelWorkBook = excelWorkBooks[1];
                excelWorkBook.Saved = false;
                Excel.Sheets excelSheets = oExcel.Worksheets;
                Excel.Worksheet excelWorkSheets = excelSheets.get_Item(1);

                //Замена переменных
                excelWorkSheets.Cells.Replace("{numberEkz}", "1", Excel.XlLookAt.xlPart, Excel.XlSearchOrder.xlByColumns, MatchCase: false, SearchFormat: false, ReplaceFormat: false);
                excelWorkSheets.Cells.Replace("{uchNumber}", uchNumber, Excel.XlLookAt.xlPart, Excel.XlSearchOrder.xlByColumns, MatchCase: false, SearchFormat: false, ReplaceFormat: false);
                excelWorkSheets.Cells[13, 1].Replace("{month}", nameMesac, Excel.XlLookAt.xlPart, Excel.XlSearchOrder.xlByColumns, MatchCase: false, SearchFormat: false, ReplaceFormat: false);
                excelWorkSheets.Cells[13, 1].Replace("{year}", godr, Excel.XlLookAt.xlPart, Excel.XlSearchOrder.xlByColumns, MatchCase: false, SearchFormat: false, ReplaceFormat: false);

                //Заголовок
                string[] nameHeaderTable = {
                                               "№ п/п", 
                                               "Код и наименование\nиспытуемого элемента (ИЭ)",
                                               "Код и\nнаименование\nвида испытания", 
                                               "Код и наименование\nзавода-\nизготовителя ИЭ", 
                                               "Объём\nпоставок",
                                               "Сведения по\nкомплектующим\n(двойное нажатие)",
                                               "Код и наименование\nкомплектующего элемента",
                                               "Код/\nЕд.\nизм.",
                                               "Объём\nпотребности КЭ",
                                               "Код, наименование и\nместонахождение\nорганизации-\nпоставщика\nИНН"
                                           };

                //Заполнение
                Excel.Range excelCells;
                excelCells = excelWorkSheets.get_Range("A32");
                excelCells.Value2 = nameHeaderTable[0];
                excelCells.Font.Bold = 1;
                excelCells = excelWorkSheets.get_Range("B32");
                excelCells.Value2 = nameHeaderTable[1];
                excelCells.Font.Bold = 1;
                excelCells = excelWorkSheets.get_Range("C32");
                excelCells.Value2 = nameHeaderTable[2];
                excelCells.Font.Bold = 1;
                excelCells = excelWorkSheets.get_Range("D32");
                excelCells.Value2 = nameHeaderTable[3];
                excelCells.Font.Bold = 1;
                excelCells = excelWorkSheets.get_Range("E32");
                excelCells.Value2 = nameHeaderTable[4];
                excelCells.Font.Bold = 1;
                excelCells = excelWorkSheets.get_Range("F32");
                excelCells.Value2 = nameHeaderTable[5];
                excelCells.Font.Bold = 1;
                excelCells = excelWorkSheets.get_Range("G32");
                excelCells.Value2 = nameHeaderTable[6];
                excelCells.Font.Bold = 1;
                excelCells = excelWorkSheets.get_Range("H32");
                excelCells.Value2 = nameHeaderTable[7];
                excelCells.Font.Bold = 1;
                excelCells = excelWorkSheets.get_Range("I32");
                excelCells.Value2 = nameHeaderTable[8];
                excelCells.Font.Bold = 1;
                excelCells = excelWorkSheets.get_Range("J32");
                excelCells.Value2 = nameHeaderTable[9];
                excelCells.Font.Bold = 1;

                //Автоматическое равнение по ширине
                /*excelWorkSheets.Columns[1].EntireColumn.AutoFit();
                excelWorkSheets.Columns[2].EntireColumn.AutoFit();
                excelWorkSheets.Columns[3].EntireColumn.AutoFit();
                excelWorkSheets.Columns[4].EntireColumn.AutoFit();
                excelWorkSheets.Columns[5].EntireColumn.AutoFit();
                excelWorkSheets.Columns[6].EntireColumn.AutoFit();
                excelWorkSheets.Columns[7].EntireColumn.AutoFit();
                excelWorkSheets.Columns[8].EntireColumn.AutoFit();
                excelWorkSheets.Columns[9].EntireColumn.AutoFit();
                excelWorkSheets.Columns[10].EntireColumn.AutoFit();*/
                excelWorkSheets.Columns[1].ColumnWidth = 10;
                excelWorkSheets.Columns[2].ColumnWidth = 17;
                excelWorkSheets.Columns[3].ColumnWidth = 17;
                excelWorkSheets.Columns[4].ColumnWidth = 17;
                excelWorkSheets.Columns[5].ColumnWidth = 14;
                excelWorkSheets.Columns[6].ColumnWidth = 18;
                excelWorkSheets.Columns[7].ColumnWidth = 19;
                excelWorkSheets.Columns[8].ColumnWidth = 10;
                excelWorkSheets.Columns[9].ColumnWidth = 13;
                excelWorkSheets.Columns[10].ColumnWidth = 22;

                //Сквозная строка
                //excelCells = excelWorkSheets.Range["A32", "J32"];


                //Вывод
                int numberPosition = 1;
                int posRows = 33;
                foreach (MesplPlanIsp mpi in sortedList)
                {
                    label1.BeginInvoke((MethodInvoker)(() => this.label1.Text = "Информация: печать элемента " + numberPosition + " из " + planIspList.Count));
                    //Порядковый номер
                    excelCells = excelWorkSheets.get_Range("A" + posRows);
                    excelCells.Value2 = Convert.ToString(numberPosition);
                    //Испытуемый элемент
                    excelCells = excelWorkSheets.get_Range("B" + posRows);
                    if (!string.IsNullOrWhiteSpace(mpi.kodel))
                        excelCells.Value2 = "Код " + Convert.ToString(mpi.kodel) + "\n\n" + mpi.snhert + "\n" + mpi.snindiz + "\n" + mpi.snnaim;
                    else
                        excelCells.Value2 = Convert.ToString(mpi.kodel) + "\n\n" + mpi.snhert + "\n" + mpi.snindiz + "\n" + mpi.snnaim;
                    //Вид испытания
                    excelCells = excelWorkSheets.get_Range("C" + posRows);
                    if (!string.IsNullOrWhiteSpace(mpi.shvid))
                        excelCells.Value2 = "Код " + Convert.ToString(mpi.shvid) + "\n\n\n\n" + mpi.nvid;
                    else
                        excelCells.Value2 = Convert.ToString(mpi.shvid) + "\n\n\n\n" + mpi.nvid;
                    //Завод-изготовитель ИЭ
                    excelCells = excelWorkSheets.get_Range("D" + posRows);
                    if (!string.IsNullOrWhiteSpace(mpi.kodotr))
                        excelCells.Value2 = "Код " + Convert.ToString(mpi.kodotr) + "\n\n\n\n" + mpi.nzav;
                    else
                        excelCells.Value2 = Convert.ToString(mpi.kodotr) + "\n\n\n\n" + mpi.nzav;
                    //Объём поставок
                    excelCells = excelWorkSheets.get_Range("E" + posRows);
                    excelCells.Value2 = "\n\n\n\n" + Convert.ToString(mpi.vist);
                    //Плюсик или минусик
                    if (mpi.agList.Count > 0)
                    {
                        excelCells = excelWorkSheets.get_Range("F" + posRows);
                        excelCells.Value2 = "+";
                        excelCells.Font.Size = 48;
                        excelCells.Style.HorizontalAlignment = Excel.XlHAlign.xlHAlignCenter;
                        excelCells.Interior.ColorIndex = 34;
                    }
                    else
                    {
                        excelCells = excelWorkSheets.get_Range("F" + posRows);
                        excelCells.Value2 = "-";
                        excelCells.Font.Size = 48;
                        excelCells.Style.HorizontalAlignment = Excel.XlHAlign.xlHAlignCenter;
                        excelCells.Font.ColorIndex = 2;
                    }
                    string beginRow = "A" + (posRows + 1);
                    //Комплектующие элементы
                    foreach (MesplPlanIsp ag in mpi.agList)
                    {
                        posRows++;
                        //Комплектующий элемент
                        excelCells = excelWorkSheets.get_Range("G" + posRows);
                        if (!string.IsNullOrWhiteSpace(ag.kodel))
                            excelCells.Value2 = "Код " + Convert.ToString(ag.kodel) + "\n\n" + ag.snhert + "\n" + ag.snindiz + "\n" + ag.snnaim;
                        else
                            excelCells.Value2 = Convert.ToString(ag.kodel) + "\n\n" + ag.snhert + "\n" + ag.snindiz + "\n" + ag.snnaim;
                        //Ед. измерения.
                        excelCells = excelWorkSheets.get_Range("H" + posRows);
                        if (!string.IsNullOrWhiteSpace(ag.kodeiz))
                            excelCells.Value2 = "Код " + Convert.ToString(ag.kodeiz) + "\n\n\n\n" + Convert.ToString(ag.naimeiz);
                        else
                            excelCells.Value2 = Convert.ToString(ag.kodeiz) + "\n\n\n\n" + Convert.ToString(ag.naimeiz);
                        //Объём потребности
                        excelCells = excelWorkSheets.get_Range("I" + posRows);
                        excelCells.Value2 = "\n\n\n\n" + Convert.ToString(ag.vist);
                        //Организация-поставщик
                        excelCells = excelWorkSheets.get_Range("J" + posRows);
                        if (!string.IsNullOrWhiteSpace(ag.kodotr))
                            excelCells.Value2 = "Код " + Convert.ToString(ag.kodotr) + "\n\n\n\n" + ag.nzav + "\n" + ag.inn;
                        else
                            excelCells.Value2 = Convert.ToString(ag.kodotr) + "\n\n\n\n" + ag.nzav + "\n" + ag.inn;
                    }
                    //Изначально всё сворачиваем
                    if (mpi.agList.Count > 0)
                    {
                        excelCells = excelWorkSheets.Range[beginRow, "A" + posRows];
                        excelCells.EntireRow.Hidden = true;
                    }

                    numberPosition++;
                    posRows++;
                }
                //Выравнивание таблицы по верхнему краю вертикально и по центру горизонтально
                excelCells = excelWorkSheets.Range["A32", "J" + (posRows-1)];
                excelCells.VerticalAlignment = Excel.XlVAlign.xlVAlignTop;
                excelCells.HorizontalAlignment = Excel.XlHAlign.xlHAlignCenter;
                excelCells.Font.Name = "Times New Roman";
                //Границы
                excelCells.Borders.LineStyle = Excel.XlLineStyle.xlContinuous;
                //excelCells.Borders[Excel.XlBordersIndex.xlEdgeBottom].LineStyle = Excel.XlLineStyle.xlContinuous;

                //Создание экземпляров
                label1.BeginInvoke((MethodInvoker)(() => this.label1.Text = "Информация: создание экземпляров"));


                //Очистка буфера
                Clipboard.Clear();
                //Сохранение
                label1.BeginInvoke((MethodInvoker)(() => this.label1.Text = "Информация: сохранение"));
                DateTime dateNowFileName = DateTime.Now;
                string strSaveName = Convert.ToString(dateNowFileName);
                strSaveName = strSaveName.Replace(':', '-');
                if (!Directory.Exists(Properties.Settings.Default.PathForReports))
                    Directory.CreateDirectory(Directory.GetCurrentDirectory() + "/reports");
                savePath = Properties.Settings.Default.PathForReports + "/Месячный EXCEL" + " " + strSaveName + ".xlsm";
                excelDocument.SaveAs(savePath);
                excelDocument.Close();
                oExcel.Quit();
                label1.BeginInvoke((MethodInvoker)(() => this.label1.Text = "Информация: EXCEL готов"));
                MessageBox.Show("Месячный EXCEL создан!");
            }
            catch (Exception ex)
            {
                oExcel.Visible = true;
                label1.BeginInvoke((MethodInvoker)(() => this.label1.Text = "Информация: ошибка!"));
                MessageBox.Show("error: " + ex.Message);
            }
        }
        #endregion

        //Печать  месячный EXCEL (НС)
        #region МЕСЯЧНЫЙ EXCEL (НС)
        private void button21_Click(object sender, EventArgs e)
        {
            button21.BackColor = choseColorBTN;
            BlockAllButton();
            if (Properties.Settings.Default.OtraslMesplPath.Length > 0)
            {
                if (Properties.Settings.Default.OtraslPath.Length > 0)
                {
                    if (Properties.Settings.Default.RashGodFolderPath.Length > 0)
                    {
                        try
                        {
                            FormChoseMespl fcm = new FormChoseMespl();
                            fcm.ShowDialog();
                            if (fcm.isExit)
                            {
                                label1.BeginInvoke((MethodInvoker)(() => this.label1.Text = "Информация: подготовка к печати"));
                                Thread thread = new Thread(() =>
                                {
                                    try
                                    {
                                        List<MesplPlanIsp> planIspList = new List<MesplPlanIsp>();
                                        //Получение плана испытаний
                                        planIspList = GetMesplPlanIsp(planIspList, fcm.mesac, fcm.godr);
                                        //Заполнение плана испытанйи
                                        planIspList = FillMesplPlanIsp(planIspList);
                                        //Заполнение анализа готовности
                                        planIspList = FillMesplAG(planIspList);
                                        //Печать Excel (НС)
                                        string savePath = null;
                                        PrintMesplExcelNS(planIspList, fcm.nameMesac, fcm.godr, fcm.uchNumber, fcm.countEkz, out savePath);
                                        //Открытие документа
                                        if (checkBox3.Checked)
                                            OpenTheDocument(savePath, label1);
                                    }
                                    catch (Exception ex)
                                    {
                                        MessageBox.Show("Очередная ошибка ин поток\n" + ex.Message + "\n" + ex.Source + "\n" + ex.StackTrace + "\n" + ex.TargetSite, "Ошибка", MessageBoxButtons.OK, MessageBoxIcon.Error);
                                    }
                                    finally
                                    {
                                        BlockAllButton(false);
                                    }
                                });
                                thread.SetApartmentState(ApartmentState.STA);
                                thread.Start();
                            }
                            else
                                BlockAllButton(false);
                        }
                        catch (Exception ex)
                        {
                            BlockAllButton(false);
                            MessageBox.Show("Очередная ошибка\n" + ex.Message + "\n" + ex.Source + "\n" + ex.StackTrace + "\n" + ex.TargetSite, "Ошибка", MessageBoxButtons.OK, MessageBoxIcon.Error);
                        }
                    }
                    else
                    {
                        BlockAllButton(false);
                        MessageBox.Show("Выберите поддиректорию RAHGOD");
                    }
                }
                else
                {
                    BlockAllButton(false);
                    MessageBox.Show("Выберите директорию OTRASL");
                }
            }
            else
            {
                BlockAllButton(false);
                MessageBox.Show("Выберите директорию OTRASL1");
            }
        }

        private void PrintMesplExcelNS(List<MesplPlanIsp> planIspList, string nameMesac, string godr, string uchNumber, string countEkz, out string savePath)
        {
            savePath = null;

            label1.BeginInvoke((MethodInvoker)(() => this.label1.Text = "Информация: подготовка к печати"));
            //Сортируем анализ готовности
            var sortedList = planIspList.OrderBy(a => a.kodotr).ThenBy(b => b.kodel).ThenBy(c => c.shvid);

            //Создаем документ
            Excel.Application oExcel = new Excel.Application();
            oExcel.Visible = checkBox1.Checked;
            try
            {
                //Открытие шаблона
                string path = Directory.GetCurrentDirectory() + templateFileNameExcelNS;
                Excel.Workbook excelDocument = oExcel.Workbooks.Open(path);

                Excel.Workbooks excelWorkBooks = oExcel.Workbooks;
                Excel.Workbook excelWorkBook = excelWorkBooks[1];
                excelWorkBook.Saved = false;
                Excel.Sheets excelSheets = oExcel.Worksheets;
                Excel.Worksheet excelWorkSheets = excelSheets.get_Item(1);

                //Замена переменных
                excelWorkSheets.Cells.Replace("{numberEkz}", "1", Excel.XlLookAt.xlPart, Excel.XlSearchOrder.xlByColumns, MatchCase: false, SearchFormat: false, ReplaceFormat: false);
                excelWorkSheets.Cells[13, 1].Replace("{month}", nameMesac, Excel.XlLookAt.xlPart, Excel.XlSearchOrder.xlByColumns, MatchCase: false, SearchFormat: false, ReplaceFormat: false);
                excelWorkSheets.Cells[13, 1].Replace("{year}", godr, Excel.XlLookAt.xlPart, Excel.XlSearchOrder.xlByColumns, MatchCase: false, SearchFormat: false, ReplaceFormat: false);

                //Заголовок
                string[] nameHeaderTable = {
                                               "№ п/п", 
                                               "Наименование\nиспытуемого элемента (ИЭ)",
                                               "Наименование\nвида испытания", 
                                               "Наименование\nзавода-\nизготовителя ИЭ", 
                                               "Объём\nпоставок",
                                               "Сведения по\nкомплектующим\n(двойное нажатие)",
                                               "Наименование\nкомплектующего элемента",
                                               "Ед.\nизм.",
                                               "Объём\nпотребности КЭ",
                                               "Наименование и\nместонахождение\nорганизации-\nпоставщика\nИНН"
                                           };

                //Заполнение
                Excel.Range excelCells;
                excelCells = excelWorkSheets.get_Range("A32");
                excelCells.Value2 = nameHeaderTable[0];
                excelCells.Font.Bold = 1;
                excelCells = excelWorkSheets.get_Range("B32");
                excelCells.Value2 = nameHeaderTable[1];
                excelCells.Font.Bold = 1;
                excelCells = excelWorkSheets.get_Range("C32");
                excelCells.Value2 = nameHeaderTable[2];
                excelCells.Font.Bold = 1;
                excelCells = excelWorkSheets.get_Range("D32");
                excelCells.Value2 = nameHeaderTable[3];
                excelCells.Font.Bold = 1;
                excelCells = excelWorkSheets.get_Range("E32");
                excelCells.Value2 = nameHeaderTable[4];
                excelCells.Font.Bold = 1;
                excelCells = excelWorkSheets.get_Range("F32");
                excelCells.Value2 = nameHeaderTable[5];
                excelCells.Font.Bold = 1;
                excelCells = excelWorkSheets.get_Range("G32");
                excelCells.Value2 = nameHeaderTable[6];
                excelCells.Font.Bold = 1;
                excelCells = excelWorkSheets.get_Range("H32");
                excelCells.Value2 = nameHeaderTable[7];
                excelCells.Font.Bold = 1;
                excelCells = excelWorkSheets.get_Range("I32");
                excelCells.Value2 = nameHeaderTable[8];
                excelCells.Font.Bold = 1;
                excelCells = excelWorkSheets.get_Range("J32");
                excelCells.Value2 = nameHeaderTable[9];
                excelCells.Font.Bold = 1;
                excelCells = excelWorkSheets.get_Range("A32:J32");
                excelCells.HorizontalAlignment = Excel.XlHAlign.xlHAlignCenter;

                //Автоматическое выравнивание колонок
                excelWorkSheets.Columns[1].ColumnWidth = 10;
                excelWorkSheets.Columns[2].ColumnWidth = 17;
                excelWorkSheets.Columns[3].ColumnWidth = 17;
                excelWorkSheets.Columns[4].ColumnWidth = 17;
                excelWorkSheets.Columns[5].ColumnWidth = 14;
                excelWorkSheets.Columns[6].ColumnWidth = 18;
                excelWorkSheets.Columns[7].ColumnWidth = 19;
                excelWorkSheets.Columns[8].ColumnWidth = 10;
                excelWorkSheets.Columns[9].ColumnWidth = 13;
                excelWorkSheets.Columns[10].ColumnWidth = 22;

                //Вывод
                int numberPosition = 1;
                int posRows = 33;
                foreach (MesplPlanIsp mpi in sortedList)
                {
                    label1.BeginInvoke((MethodInvoker)(() => this.label1.Text = "Информация: печать элемента " + numberPosition + " из " + planIspList.Count));
                    //Порядковый номер
                    excelCells = excelWorkSheets.get_Range("A" + posRows);
                    excelCells.Value2 = Convert.ToString(numberPosition);
                    //Испытуемый элемент
                    excelCells = excelWorkSheets.get_Range("B" + posRows);
                    string hertOrIndizMPI = mpi.snindiz;
                    if (mpi.snindiz == "")
                        hertOrIndizMPI += mpi.snhert;
                    if (mpi.snindiz == "" && mpi.snhert == "")
                        hertOrIndizMPI += mpi.snnaim;
                    if (dataBaseSNS[mpi.kodel] != null)
                        hertOrIndizMPI = dataBaseSNS[mpi.kodel].name.Replace("\r", "");
                    excelCells.Value2 = hertOrIndizMPI;
                    //Вид испытания
                    excelCells = excelWorkSheets.get_Range("C" + posRows);
                    excelCells.Value2 = mpi.nvid;
                    //Завод-изготовитель ИЭ
                    excelCells = excelWorkSheets.get_Range("D" + posRows);
                    excelCells.Value2 = mpi.nzav;
                    //Объём поставок
                    excelCells = excelWorkSheets.get_Range("E" + posRows);
                    excelCells.Value2 = Convert.ToString(mpi.vist);
                    //Плюсик или минусик
                    if (mpi.agList.Count > 0)
                    {
                        excelCells = excelWorkSheets.get_Range("F" + posRows);
                        excelCells.Value2 = "+";
                        excelCells.Font.Size = 48;
                        excelCells.Style.HorizontalAlignment = Excel.XlHAlign.xlHAlignCenter;
                        excelCells.Interior.ColorIndex = 34;
                    }
                    else
                    {
                        excelCells = excelWorkSheets.get_Range("F" + posRows);
                        excelCells.Value2 = "-";
                        excelCells.Font.Size = 48;
                        excelCells.Style.HorizontalAlignment = Excel.XlHAlign.xlHAlignCenter;
                        excelCells.Font.ColorIndex = 2;
                    }
                    string beginRow = "A" + (posRows + 1);
                    //Комплектующие элементы
                    foreach (MesplPlanIsp ag in mpi.agList)
                    {
                        posRows++;
                        //Комплектующий элемент
                        excelCells = excelWorkSheets.get_Range("G" + posRows);
                        hertOrIndizMPI = ag.snindiz;
                        if (ag.snindiz == "")
                            hertOrIndizMPI += ag.snhert;
                        if (ag.snindiz == "" && ag.snhert == "")
                            hertOrIndizMPI += ag.snnaim;
                        if (dataBaseSNS[ag.kodel] != null)
                            hertOrIndizMPI = dataBaseSNS[ag.kodel].name.Replace("\r","");
                        excelCells.Value2 = hertOrIndizMPI;
                        //Ед. измерения.
                        excelCells = excelWorkSheets.get_Range("H" + posRows);
                        excelCells.Value2 = Convert.ToString(ag.naimeiz);
                        //Объём потребности
                        excelCells = excelWorkSheets.get_Range("I" + posRows);
                        excelCells.Value2 = Convert.ToString(ag.vist);
                        //Организация-поставщик
                        excelCells = excelWorkSheets.get_Range("J" + posRows);
                        excelCells.Value2 = ag.nzav + "\n" + ag.inn;
                    }
                    //Изначально всё сворачиваем
                    if (mpi.agList.Count > 0)
                    {
                        excelCells = excelWorkSheets.Range[beginRow, "A" + posRows];
                        excelCells.EntireRow.Hidden = true;
                    }

                    numberPosition++;
                    posRows++;
                }
                //Выравнивание таблицы по верхнему краю и по центру горизонтально
                excelCells = excelWorkSheets.Range["A32", "J" + (posRows - 1)];
                excelCells.VerticalAlignment = Excel.XlVAlign.xlVAlignTop;
                excelCells.HorizontalAlignment = Excel.XlHAlign.xlHAlignCenter;
                excelCells.Font.Name = "Times New Roman";
                //Границы
                excelCells.Borders.LineStyle = Excel.XlLineStyle.xlContinuous;
                //excelCells.Borders[Excel.XlBordersIndex.xlEdgeBottom].LineStyle = Excel.XlLineStyle.xlContinuous;

                //Создание экземпляров
                label1.BeginInvoke((MethodInvoker)(() => this.label1.Text = "Информация: создание экземпляров"));


                //Очистка буфера
                Clipboard.Clear();
                //Сохранение
                label1.BeginInvoke((MethodInvoker)(() => this.label1.Text = "Информация: сохранение"));
                DateTime dateNowFileName = DateTime.Now;
                string strSaveName = Convert.ToString(dateNowFileName);
                strSaveName = strSaveName.Replace(':', '-');
                if (!Directory.Exists(Properties.Settings.Default.PathForReports))
                    Directory.CreateDirectory(Directory.GetCurrentDirectory() + "/reports");
                savePath = Properties.Settings.Default.PathForReports + "/Месячный EXCEL (НС)" + " " + strSaveName + ".xlsm";
                excelDocument.SaveAs(savePath);
                excelDocument.Close();
                oExcel.Quit();
                label1.BeginInvoke((MethodInvoker)(() => this.label1.Text = "Информация: EXCEL (НС) готов"));
                MessageBox.Show("Месячный EXCEL (НС) создан!");
            }
            catch (Exception ex)
            {
                oExcel.Visible = true;
                label1.BeginInvoke((MethodInvoker)(() => this.label1.Text = "Информация: ошибка!"));
                MessageBox.Show("error: " + ex.Message);
            }
        }
        #endregion

        //Настройка конвертации из С в НС
        private void button22_Click(object sender, EventArgs e)
        {
            FormSNS fSNS = new FormSNS(dataBaseSNS);
            fSNS.ShowDialog();
            if (!dataBaseSNS.Save())
                MessageBox.Show("Ошибка при сохранении!");
        }


        //---------ГОДОВОЙ РАСЧЁТ---------
        //Печать годового плана испытаний
        #region ГОДОВОЙ ПЛАН ИСПЫТАНИЙ
        private void button15_Click(object sender, EventArgs e)
        {
            BlockAllButton();
            if (Properties.Settings.Default.OtraslPath.Length > 0)
            {
                if (Properties.Settings.Default.KontrolFolderPath.Length > 0)
                {
                    try
                    {
                        FormChoseUchAndCount fcuac = new FormChoseUchAndCount();
                        fcuac.ShowDialog();
                        if (fcuac.isExit)
                        {
                            label2.BeginInvoke((MethodInvoker)(() => this.label2.Text = "Информация: подготовка к печати"));
                            Thread thread = new Thread(() =>
                            {
                                try
                                {
                                    List<KontrolPlanIsp> planIspList = new List<KontrolPlanIsp>();
                                    //Получение плана испытаний
                                    planIspList = GetKontrolPlanIsp(planIspList);
                                    //Заполнение плана испытанйи
                                    planIspList = FillKontrolPlanIsp(planIspList);
                                    //Печать сводного плана испытаний
                                    PrintKontrolPlanIsp(planIspList, fcuac.uchNumber, fcuac.countEkz);
                                }
                                catch (Exception ex)
                                {
                                    MessageBox.Show("Очередная ошибка ин поток\n" + ex.Message + "\n" + ex.Source + "\n" + ex.StackTrace + "\n" + ex.TargetSite, "Ошибка", MessageBoxButtons.OK, MessageBoxIcon.Error);
                                }
                                finally
                                {
                                    BlockAllButton(false);
                                }
                            });
                            thread.SetApartmentState(ApartmentState.STA);
                            thread.Start();
                        }
                    }
                    catch (Exception ex)
                    {
                        BlockAllButton(false);
                        MessageBox.Show("Очередная ошибка\n" + ex.Message + "\n" + ex.Source + "\n" + ex.StackTrace + "\n" + ex.TargetSite, "Ошибка", MessageBoxButtons.OK, MessageBoxIcon.Error);
                    }
                }
                else
                    MessageBox.Show("Выберите поддиректорию KONTROL");
            }
            else
                MessageBox.Show("Выберите директорию OTRASL");
        }

        private List<KontrolPlanIsp> GetKontrolPlanIsp(List<KontrolPlanIsp> planIspList)
        {
            planIspList.Clear();
            //MessageBox.Show("Получаем план");
            label2.BeginInvoke((MethodInvoker)(() => this.label2.Text = "Информация: получение плана"));
            string strConnection = @"Provider=Microsoft.JET.Oledb.4.0;Data Source=" + Properties.Settings.Default.OtraslPath + "/KONTROL/" + Properties.Settings.Default.KontrolFolderPath + "/PLAN;Extended Properties=dBASE IV;User ID=;Password=;";
            using (_connection = new OleDbConnection(strConnection))
            {
                _connection.Open();
                using (OleDbCommand cmd = _connection.CreateCommand())
                {
                    cmd.CommandText = "SELECT * FROM PLISP.DBF";
                    using (OleDbDataReader reader = cmd.ExecuteReader())
                    {
                        while (reader.Read())
                        {
                            KontrolPlanIsp isp = new KontrolPlanIsp();
                            isp.kodel = Convert.ToString(reader["KODEL"]);
                            isp.kodotr = Convert.ToString(reader["KODOTR"]);
                            isp.shvid = Convert.ToString(reader["SHVID"]);
                            isp.pol = Convert.ToString(reader["POL"]);
                            isp.kodeiz = Convert.ToString(reader["KODEIZ"]);
                            isp.qolg = Convert.ToString(reader["QOLG"]);
                            isp.qol1 = Convert.ToString(reader["QOL1"]);
                            isp.qol2 = Convert.ToString(reader["QOL2"]);
                            isp.qol3 = Convert.ToString(reader["QOL3"]);
                            isp.qol4 = Convert.ToString(reader["QOL4"]);
                            isp.shsis = Convert.ToString(reader["SHSIS"]);
                            isp.shsis2 = Convert.ToString(reader["SHSIS2"]);
                            isp.shsis3 = Convert.ToString(reader["SHSIS3"]);
                            isp.cena = Convert.ToString(reader["CENA"]);
                            isp.stoim = Convert.ToString(reader["STOIM"]);
                            isp.nporsv = Convert.ToString(reader["NPORSV"]);
                            isp.nporpl = Convert.ToString(reader["NPORPL"]);
                            isp.prim = Convert.ToString(reader["PRIM1"]) + Convert.ToString(reader["PRIM2"]);
                            planIspList.Add(isp);
                        }
                    }
                }
            }
            //MessageBox.Show("План получен");
            return planIspList;
        }

        private List<KontrolPlanIsp> FillKontrolPlanIsp(List<KontrolPlanIsp> planIspList)
        {
            //MessageBox.Show("Заполняем");
            //Получение информации о элементе
            label2.BeginInvoke((MethodInvoker)(() => this.label2.Text = "Информация: получение информации о элементах"));
            string strConnection = @"Provider=Microsoft.JET.Oledb.4.0;Data Source=" + Properties.Settings.Default.OtraslPath + "/SPNAIM;Extended Properties=dBASE IV;User ID=;Password=;";
            using (_connection = new OleDbConnection(strConnection))
            {
                _connection.Open();
                using (OleDbCommand cmd = _connection.CreateCommand())
                {
                    foreach (KontrolPlanIsp kpi in planIspList)
                    {
                        if (kpi.kodel != null && kpi.kodel != "")
                        {
                            cmd.CommandText = "SELECT * FROM SPNAIM.DBF WHERE KODEL='" + kpi.kodel + "'";
                            using (OleDbDataReader reader = cmd.ExecuteReader())
                            {
                                while (reader.Read())
                                {
                                    kpi.snhert = Convert.ToString(reader["SNHERT"] ?? "");

                                    kpi.snindiz = Convert.ToString(reader["SNINDIZ"] ?? "");

                                    kpi.snnaim = Convert.ToString(reader["SNNAIM1"] ?? "") + "\n" + Convert.ToString(reader["SNNAIM2"] ?? "") + "\n" +
                                        Convert.ToString(reader["SNNAIM3"] ?? "") + "\n" + Convert.ToString(reader["SNNAIM4"] ?? "") + "\n" +
                                        Convert.ToString(reader["SNNAIM5"] ?? "") + "\n" + Convert.ToString(reader["SNNAIM6"] ?? "") + "\n" +
                                        Convert.ToString(reader["SNNAIM7"] ?? "") + "\n" + Convert.ToString(reader["SNNAIM8"] ?? "") + "\n" +
                                        Convert.ToString(reader["SNNAIM9"] ?? "") + "\n" + Convert.ToString(reader["SNNAIM10"] ?? "");
                                    kpi.snnaim = kpi.snnaim.TrimEnd('\n');
                                    kpi.snnaim = kpi.snnaim.Replace('*', '"');
                                }
                            }
                        }
                        else
                            MessageBox.Show("В плане элемент без кода!", "Косяк", MessageBoxButtons.OK, MessageBoxIcon.Error);
                    }
                }
            }
            //Получение информации о заводе
            label2.BeginInvoke((MethodInvoker)(() => this.label2.Text = "Информация: получение информации о заводах"));
            strConnection = @"Provider=Microsoft.JET.Oledb.4.0;Data Source=" + Properties.Settings.Default.OtraslPath + "/SPRXZ;Extended Properties=dBASE IV;User ID=;Password=;";
            using (_connection = new OleDbConnection(strConnection))
            {
                _connection.Open();
                using (OleDbCommand cmd = _connection.CreateCommand())
                {
                    foreach (KontrolPlanIsp kpi in planIspList)
                    {
                        cmd.CommandText = "SELECT KODOTR, NZAV1, NZAV2, NZAV3, INN FROM SPRXZ.DBF WHERE KODOTR='" + kpi.kodotr + "'";
                        using (OleDbDataReader reader = cmd.ExecuteReader())
                        {
                            while (reader.Read())
                            {
                                kpi.nzav = Convert.ToString(reader["NZAV1"]) + "\n" + Convert.ToString(reader["NZAV2"]) + "\n" + Convert.ToString(reader["NZAV3"]);
                                kpi.nzav = kpi.nzav.TrimEnd('\n');
                                kpi.nzav = kpi.nzav.Replace('*', '"');
                                kpi.inn = Convert.ToString(reader["INN"]);
                            }
                        }
                    }
                }
            }
            //Получение информации о виде
            label2.BeginInvoke((MethodInvoker)(() => this.label2.Text = "Информация: получение информации о видах испытаний"));
            strConnection = @"Provider=Microsoft.JET.Oledb.4.0;Data Source=" + Properties.Settings.Default.OtraslPath + "/SPRAV;Extended Properties=dBASE IV;User ID=;Password=;";
            using (_connection = new OleDbConnection(strConnection))
            {
                _connection.Open();
                using (OleDbCommand cmd = _connection.CreateCommand())
                {
                    foreach (KontrolPlanIsp kpi in planIspList)
                    {
                        cmd.CommandText = "SELECT * FROM SPVID.DBF WHERE SHVID='" + kpi.shvid + "'";
                        using (OleDbDataReader reader = cmd.ExecuteReader())
                        {
                            while (reader.Read())
                            {
                                kpi.nvid = Convert.ToString(reader["NVID1"]) + "\n" + Convert.ToString(reader["NVID2"]);
                                kpi.nvid = kpi.nvid.TrimEnd('\n');
                            }
                        }
                    }
                }
            }
            //Получение информации о МЧ
            label2.BeginInvoke((MethodInvoker)(() => this.label2.Text = "Информация: получение информации о МЧ"));
            try
            {
                strConnection = @"Provider=Microsoft.JET.Oledb.4.0;Data Source=" + Properties.Settings.Default.OtraslPath + "/SPRAV;Extended Properties=dBASE IV;User ID=;Password=;";
                using (_connection = new OleDbConnection(strConnection))
                {
                    _connection.Open();
                    using (OleDbCommand cmd = _connection.CreateCommand())
                    {
                        foreach (KontrolPlanIsp kpi in planIspList)
                        {
                            if (kpi.shsis != null)
                                if (kpi.shsis != "")
                                {
                                    cmd.CommandText = "SELECT * FROM SPSIS.DBF WHERE SHSIS='" + kpi.shsis + "'";
                                    using (OleDbDataReader reader = cmd.ExecuteReader())
                                    {
                                        while (reader.Read())
                                        {
                                            kpi.nsis = Convert.ToString(reader["NSIS"]);
                                        }
                                    }
                                }
                            if (kpi.shsis2 != null)
                                if (kpi.shsis2 != "")
                                {
                                    cmd.CommandText = "SELECT * FROM SPSIS.DBF WHERE SHSIS='" + kpi.shsis2 + "'";
                                    using (OleDbDataReader reader = cmd.ExecuteReader())
                                    {
                                        while (reader.Read())
                                        {
                                            kpi.nsis2 = Convert.ToString(reader["NSIS"]);
                                        }
                                    }
                                }
                            if (kpi.shsis3 != null)
                                if (kpi.shsis3 != "")
                                {
                                    cmd.CommandText = "SELECT * FROM SPSIS.DBF WHERE SHSIS='" + kpi.shsis3 + "'";
                                    using (OleDbDataReader reader = cmd.ExecuteReader())
                                    {
                                        while (reader.Read())
                                        {
                                            kpi.nsis3 = Convert.ToString(reader["NSIS"]);
                                        }
                                    }
                                }
                        }
                    }
                }
            }
            catch (Exception ex)
            {
                MessageBox.Show("Что-то с МЧ!!!");
            }

            //Получение информации о ЦЕНАХ
            label2.BeginInvoke((MethodInvoker)(() => this.label2.Text = "Информация: получение информации о ценах"));
            try
            {
                strConnection = @"Provider=Microsoft.JET.Oledb.4.0;Data Source=" + Properties.Settings.Default.OtraslPath + "/RAHGOD/CEN;Extended Properties=dBASE IV;User ID=;Password=;";
                using (_connection = new OleDbConnection(strConnection))
                {
                    _connection.Open();
                    using (OleDbCommand cmd = _connection.CreateCommand())
                    {
                        foreach (KontrolPlanIsp kpi in planIspList)
                        {
                            cmd.CommandText = "SELECT * FROM CENVI.DBF WHERE POL='" + kpi.pol + "' AND KODEL='" + kpi.kodel + "' AND SHVID='" + kpi.shvid + "'";
                            using (OleDbDataReader reader = cmd.ExecuteReader())
                            {
                                while (reader.Read())
                                {
                                    kpi.cena = Convert.ToString(reader["CENA"]);
                                }
                            }
                        }
                    }
                }
            }
            catch (Exception ex)
            {
                MessageBox.Show("Что-то с ЦЕНАМИ!!!");
            }

            //MessageBox.Show("Вся инфа получена");
            return planIspList;
        }

        private void PrintKontrolPlanIsp(List<KontrolPlanIsp> planIspList, string uchNumber, string countEkz)
        {
            //Год
            string yearPrintResult = "2020";

            //MessageBox.Show("Печатаем контрольный план");
            label2.BeginInvoke((MethodInvoker)(() => this.label2.Text = "Информация: подготовка к печати"));
            //Сортируем план испытаний
            var sortedList = planIspList.OrderBy(a => a.kodotr).ThenBy(b => b.kodel).ThenBy(c => c.shvid);

            //Создаем документ
            Word.Application oWord = new Word.Application();
            oWord.Visible = checkBox2.Checked;
            try
            {
                string path = Directory.GetCurrentDirectory() + templateFileNameWordPlanIspKontrol;
                Word.Document wordDocument = oWord.Documents.Open(path);

                //Поиск и замена текста
                functionReplaceInText(wordDocument, oWord, "{uchNumber}", uchNumber);
                functionReplaceInText(wordDocument, oWord, "{year}", yearPrintResult);

                //Добавление в файл таблицы
                wordDocument.Paragraphs.Add();
                Object autoFitBehavior = Word.WdAutoFitBehavior.wdAutoFitWindow;
                var range = wordDocument.Paragraphs[wordDocument.Paragraphs.Count - 1].Range;
                wordDocument.Tables.Add(range, 1, 7, autoFitBehavior);
                Word.Table oTable = wordDocument.Tables[1];
                string[] nameHeaderTable = { "№\nп/п", "Код,\nнаименование\nэлемента", "Код,\nнаименование\nзавода", "Код,\nнаименование\nвида испытания", "Код,\nнаименование\nсистемы", "Количество выстрелов, шт", "Примечание", "Год", "1 кв", "2 кв", "3 кв", "4 кв" };
                for (int i = 0; i < oTable.Columns.Count; i++)
                {
                    Word.Range wordCellRange = oTable.Cell(1, i + 1).Range;
                    wordCellRange.ParagraphFormat.Alignment = Word.WdParagraphAlignment.wdAlignParagraphCenter;
                    wordCellRange.Text = nameHeaderTable[i];
                    wordCellRange.Font.Name = "Times New Roman";
                }
                oTable.Rows.First.HeadingFormat = -1;
                //Запрет переноса строк
                oTable.Rows.AllowBreakAcrossPages = 0;
                //Вертикальное центрирование заголовка
                oTable.Rows[1].Cells.VerticalAlignment = Word.WdCellVerticalAlignment.wdCellAlignVerticalCenter;
                /*oTable.Cell(1, 1).Width = 35;
                oTable.Cell(1, 2).Width = 150;
                oTable.Cell(1, 3).Width = 100;
                oTable.Cell(1, 4).Width = 100;
                oTable.Cell(1, 5).Width = 100;
                oTable.Cell(1, 6).Width = 50;
                oTable.Cell(1, 7).Width = 270;*/
                oTable.Cell(1, 6).Split(2, 1);
                oTable.Cell(2, 6).Split(1, 5);
                for (int i = 7; i < 12; i++)
                {
                    Word.Range wordCellRange = oTable.Cell(2, i-1).Range;
                    wordCellRange.ParagraphFormat.Alignment = Word.WdParagraphAlignment.wdAlignParagraphCenter;
                    wordCellRange.Text = nameHeaderTable[i];
                    wordCellRange.Rows.HeadingFormat = -1;
                }
                //Вывод
                int posRows = 3;
                foreach (KontrolPlanIsp isp in sortedList)
                {
                    label2.BeginInvoke((MethodInvoker)(() => this.label2.Text = "Информация: печать элемента " + (posRows-2) + " из " + planIspList.Count));
                    oTable.Rows.Add();
                    //Порядковый номер
                    Word.Range wordCellRange = oTable.Cell(posRows, 1).Range;
                    wordCellRange.Rows.HeadingFormat = 0;
                    wordCellRange.Text = Convert.ToString(posRows - 2);     //"0\n0"
                    wordCellRange.Cells.VerticalAlignment = Word.WdCellVerticalAlignment.wdCellAlignVerticalCenter;
                    //Шифр, наименование элемента
                    wordCellRange = oTable.Cell(posRows, 2).Range;
                    wordCellRange.Rows.HeadingFormat = 0;
                    if (!string.IsNullOrWhiteSpace(isp.kodel))
                        wordCellRange.Text = "Код " + Convert.ToString(isp.kodel) + "\n\n" + isp.snhert + "\n" + isp.snindiz + "\n" + isp.snnaim;
                    else
                        wordCellRange.Text = Convert.ToString(isp.kodel) + "\n\n" + isp.snhert + "\n" + isp.snindiz + "\n" + isp.snnaim;
                    wordCellRange.ParagraphFormat.Alignment = Word.WdParagraphAlignment.wdAlignParagraphLeft;
                    wordCellRange.Cells.VerticalAlignment = Word.WdCellVerticalAlignment.wdCellAlignVerticalTop;
                    //Шифр, наименование завода
                    wordCellRange = oTable.Cell(posRows, 3).Range;
                    wordCellRange.Rows.HeadingFormat = 0;
                    if (!string.IsNullOrWhiteSpace(isp.kodotr))
                        wordCellRange.Text = "Код " + Convert.ToString(isp.kodotr) + "\n\n\n\n" + isp.nzav;
                    else
                        wordCellRange.Text = Convert.ToString(isp.kodotr) + "\n\n\n\n" + isp.nzav;
                    wordCellRange.Cells.VerticalAlignment = Word.WdCellVerticalAlignment.wdCellAlignVerticalTop;
                    //Шифр, наименование вида испытания
                    wordCellRange = oTable.Cell(posRows, 4).Range;
                    wordCellRange.Rows.HeadingFormat = 0;
                    if (!string.IsNullOrWhiteSpace(isp.shvid))
                        wordCellRange.Text = "Код " + Convert.ToString(isp.shvid) + "\n\n\n\n" + isp.nvid;
                    else
                        wordCellRange.Text = Convert.ToString(isp.shvid) + "\n\n\n\n" + isp.nvid;
                    wordCellRange.Cells.VerticalAlignment = Word.WdCellVerticalAlignment.wdCellAlignVerticalTop;
                    //Шифр, наименование системы
                    wordCellRange = oTable.Cell(posRows, 5).Range;
                    wordCellRange.Rows.HeadingFormat = 0;
                    string fullSistema = "";
                    if (isp.shsis != null && isp.shsis != "" && isp.shsis != "000")
                        fullSistema += "Код " + Convert.ToString(isp.shsis) + "\n" + isp.nsis + "\n";
                    if (isp.shsis2 != null && isp.shsis2 != "" && isp.shsis2 != "000")
                        fullSistema += "Код " + Convert.ToString(isp.shsis2) + "\n" + isp.nsis2 + "\n";
                    if (isp.shsis3 != null && isp.shsis3 != "" && isp.shsis3 != "000")
                        fullSistema += "Код " + Convert.ToString(isp.shsis3) + "\n" + isp.nsis3;
                    wordCellRange.Text = fullSistema;
                    wordCellRange.Cells.VerticalAlignment = Word.WdCellVerticalAlignment.wdCellAlignVerticalCenter;
                    //Количество испытаний
                    double cena = 0;
                    if (isp.cena != null && isp.cena != "")
                        cena = Convert.ToDouble(isp.cena);
                    double countIsp = 0;
                    if (isp.qolg != null && isp.qolg != "")
                        countIsp = Convert.ToDouble(isp.qolg);
                    wordCellRange = oTable.Cell(posRows, 6).Range;
                    wordCellRange.Rows.HeadingFormat = 0;
                    if (cena != 0 && countIsp != 0)
                        wordCellRange.Text = Convert.ToString(countIsp);// + "\n" + Convert.ToString(countIsp * cena);
                    else
                        if(isp.qolg != "0")
                            wordCellRange.Text = Convert.ToString(isp.qolg);
                    wordCellRange.Cells.VerticalAlignment = Word.WdCellVerticalAlignment.wdCellAlignVerticalCenter;

                    countIsp = 0;
                    if (isp.qol1 != null && isp.qol1 != "")
                        countIsp = Convert.ToDouble(isp.qol1);
                    wordCellRange = oTable.Cell(posRows, 7).Range;
                    wordCellRange.Rows.HeadingFormat = 0;
                    if (cena != 0 && countIsp != 0)
                        wordCellRange.Text = Convert.ToString(countIsp);// + "\n" + Convert.ToString(countIsp * cena);
                    else
                        if(isp.qol1 != "0")
                            wordCellRange.Text = Convert.ToString(isp.qol1);
                    wordCellRange.Cells.VerticalAlignment = Word.WdCellVerticalAlignment.wdCellAlignVerticalCenter;

                    countIsp = 0;
                    if (isp.qol2 != null && isp.qol2 != "")
                        countIsp = Convert.ToDouble(isp.qol2);
                    wordCellRange = oTable.Cell(posRows, 8).Range;
                    wordCellRange.Rows.HeadingFormat = 0;
                    if (cena != 0 && countIsp != 0)
                        wordCellRange.Text = Convert.ToString(countIsp);// + "\n" + Convert.ToString(countIsp * cena);
                    else
                        if(isp.qol2 != "0")
                            wordCellRange.Text = Convert.ToString(isp.qol2);
                    wordCellRange.Cells.VerticalAlignment = Word.WdCellVerticalAlignment.wdCellAlignVerticalCenter;

                    countIsp = 0;
                    if (isp.qol3 != null && isp.qol3 != "")
                        countIsp = Convert.ToDouble(isp.qol3);
                    wordCellRange = oTable.Cell(posRows, 9).Range;
                    wordCellRange.Rows.HeadingFormat = 0;
                    if (cena != 0 && countIsp != 0)
                        wordCellRange.Text = Convert.ToString(countIsp);// + "\n" + Convert.ToString(countIsp * cena);
                    else
                        if(isp.qol3 != "0")
                            wordCellRange.Text = Convert.ToString(isp.qol3);
                    wordCellRange.Cells.VerticalAlignment = Word.WdCellVerticalAlignment.wdCellAlignVerticalCenter;

                    countIsp = 0;
                    if (isp.qol4 != null && isp.qol4 != "")
                        countIsp = Convert.ToDouble(isp.qol4);
                    wordCellRange = oTable.Cell(posRows, 10).Range;
                    wordCellRange.Rows.HeadingFormat = 0;
                    if (cena != 0 && countIsp != 0)
                        wordCellRange.Text = Convert.ToString(countIsp);// + "\n" + Convert.ToString(countIsp * cena);
                    else
                        if(isp.qol4 != "0")
                            wordCellRange.Text = Convert.ToString(isp.qol4);
                    wordCellRange.Cells.VerticalAlignment = Word.WdCellVerticalAlignment.wdCellAlignVerticalCenter;

                    //Примечание
                    wordCellRange = oTable.Cell(posRows, 11).Range;
                    wordCellRange.Rows.HeadingFormat = 0;
                    wordCellRange.Text = isp.prim;

                    posRows++;
                }
                //Создание экземпляров
                label2.BeginInvoke((MethodInvoker)(() => this.label2.Text = "Информация: создание экземпляров"));
                wordDocument.Range().Copy();
                var rangeAllDocumentEkz = wordDocument.Content;
                rangeAllDocumentEkz.Find.ClearFormatting();
                //for (int j = 0; j < planIspList.Count; j++)
                {
                    functionReplaceInText(wordDocument, oWord, "{numberEkz}", "1");
                }
                for (int i = 0; i < Convert.ToInt32(countEkz) - 1; i++)
                {
                    //В конец документа
                    object what = Word.WdGoToItem.wdGoToLine;
                    object which = Word.WdGoToDirection.wdGoToLast;
                    Word.Range endRange = wordDocument.GoTo(ref what, ref which);
                    //Создаем разрыв РАЗДЕЛА (не страниц)
                    endRange.InsertBreak(Word.WdBreakType.wdSectionBreakNextPage);
                    //Вставляем
                    endRange.Paste();
                    rangeAllDocumentEkz = wordDocument.Content;
                    rangeAllDocumentEkz.Find.ClearFormatting();
                    rangeAllDocumentEkz.Find.Execute(FindText: "{numberEkz}", ReplaceWith: i + 2);
                }
                //Очистка буфера
                Clipboard.Clear();
                //Сохранение
                label2.BeginInvoke((MethodInvoker)(() => this.label2.Text = "Информация: сохранение"));
                DateTime dateNowFileName = DateTime.Now;
                string strSaveName = Convert.ToString(dateNowFileName);
                strSaveName = strSaveName.Replace(':', '-');
                if (!Directory.Exists(Properties.Settings.Default.PathForReports))
                    Directory.CreateDirectory(Directory.GetCurrentDirectory() + "/reports");
                wordDocument.SaveAs(Properties.Settings.Default.PathForReports + "/Годовой план испытаний" + " " + strSaveName + ".docx");
                wordDocument.Close();
                oWord.Quit();
                label2.BeginInvoke((MethodInvoker)(() => this.label2.Text = "Информация: годовой план создан"));
                MessageBox.Show("Годовой план испытаний создан!");
            }
            catch (Exception ex)
            {
                oWord.Visible = true;
                label2.BeginInvoke((MethodInvoker)(() => this.label2.Text = "Информация: ошибка!"));
                MessageBox.Show("error: " + ex.Message);
            }
        }
        #endregion

        //Печать годового анализа готовности
        #region ГОДОВОЙ АНАЛИЗ ГОТОВНОСТИ
        private void button16_Click(object sender, EventArgs e)
        {
            BlockAllButton();
            if (Properties.Settings.Default.OtraslPath.Length > 0)
            {
                if (Properties.Settings.Default.KontrolFolderPath.Length > 0)
                {
                    try
                    {
                        FormChoseUchAndCount fcuac = new FormChoseUchAndCount();
                        fcuac.ShowDialog();
                        if (fcuac.isExit)
                        {
                            label2.BeginInvoke((MethodInvoker)(() => this.label2.Text = "Информация: подготовка к печати"));
                            Thread thread = new Thread(() =>
                            {
                                try
                                {
                                    List<KontrolPlanIsp> planIspList = new List<KontrolPlanIsp>();
                                    //Получение плана испытаний
                                    planIspList = GetKontrolPlanIsp(planIspList);
                                    //Заполнение плана испытанйи
                                    planIspList = FillKontrolPlanIsp(planIspList);
                                    //Заполнение анализа готовности
                                    planIspList = FillKontrolAG(planIspList);
                                    //Печать анализа готовности
                                    PrintKontrolAG(planIspList, fcuac.uchNumber, fcuac.countEkz);
                                }
                                catch (Exception ex)
                                {
                                    MessageBox.Show("Очередная ошибка ин поток\n" + ex.Message + "\n" + ex.Source + "\n" + ex.StackTrace + "\n" + ex.TargetSite, "Ошибка", MessageBoxButtons.OK, MessageBoxIcon.Error);
                                }
                                finally
                                {
                                    BlockAllButton(false);
                                }
                            });
                            thread.SetApartmentState(ApartmentState.STA);
                            thread.Start();
                        }
                    }
                    catch (Exception ex)
                    {
                        BlockAllButton(false);
                        MessageBox.Show("Очередная ошибка\n" + ex.Message + "\n" + ex.Source + "\n" + ex.StackTrace + "\n" + ex.TargetSite, "Ошибка", MessageBoxButtons.OK, MessageBoxIcon.Error);
                    }
                }
                else
                    MessageBox.Show("Выберите поддиректорию KONTROL");
            }
            else
                MessageBox.Show("Выберите директорию OTRASL");
        }

        private List<KontrolPlanIsp> FillKontrolAG(List<KontrolPlanIsp> planIspList, bool isFullRashet = true)
        {
            //MessageBox.Show("Заполняем!");
            //Получение информации о комплектации
            label2.BeginInvoke((MethodInvoker)(() => this.label2.Text = "Информация: получение информации о комплектации"));
            string strConnection = @"Provider=Microsoft.JET.Oledb.4.0;Data Source=" + Properties.Settings.Default.OtraslMesplPath + "/MESPL/VK;Extended Properties=dBASE IV;User ID=;Password=;";
            using (_connection = new OleDbConnection(strConnection))
            {
                _connection.Open();
                using (OleDbCommand cmd = _connection.CreateCommand())
                {
                    foreach (KontrolPlanIsp kpi in planIspList)
                    {
                        cmd.CommandText = "SELECT * FROM VK.DBF WHERE KODEL='" + kpi.kodel + "' AND SHVID='" + kpi.shvid + "'";
                        using (OleDbDataReader reader = cmd.ExecuteReader())
                        {
                            while (reader.Read())
                            {
                                KontrolPlanIsp newMPI = new KontrolPlanIsp();
                                newMPI.kodel = Convert.ToString(reader["SHEL"]);
                                if (isFullRashet)
                                    newMPI.qolg = Convert.ToString(Convert.ToDouble(reader["KELIZ"]) * Convert.ToDouble(kpi.qolg));
                                else
                                    newMPI.qolg = Convert.ToString(reader["KELIZ"]);
                                newMPI.kodeiz = Convert.ToString(reader["KODEIZ"]);
                                kpi.agList.Add(newMPI);
                            }
                        }
                    }
                }
            }
            //Получение информации о элементе комплектации
            label2.BeginInvoke((MethodInvoker)(() => this.label2.Text = "Информация: получение информации о элементах"));
            strConnection = @"Provider=Microsoft.JET.Oledb.4.0;Data Source=" + Properties.Settings.Default.OtraslPath + "/SPNAIM;Extended Properties=dBASE IV;User ID=;Password=;";
            using (_connection = new OleDbConnection(strConnection))
            {
                _connection.Open();
                using (OleDbCommand cmd = _connection.CreateCommand())
                {
                    foreach (KontrolPlanIsp kpi in planIspList)
                    {
                        foreach (KontrolPlanIsp ag in kpi.agList)
                        {
                            if (ag.kodel != null && ag.kodel != "")
                            {
                                cmd.CommandText = "SELECT * FROM SPNAIM.DBF WHERE KODEL='" + ag.kodel + "'";
                                using (OleDbDataReader reader = cmd.ExecuteReader())
                                {
                                    while (reader.Read())
                                    {
                                        ag.snhert = Convert.ToString(reader["SNHERT"] ?? "");

                                        ag.snindiz = Convert.ToString(reader["SNINDIZ"] ?? "");

                                        ag.snnaim = Convert.ToString(reader["SNNAIM1"] ?? "") + "\n" + Convert.ToString(reader["SNNAIM2"] ?? "") + "\n" +
                                            Convert.ToString(reader["SNNAIM3"] ?? "") + "\n" + Convert.ToString(reader["SNNAIM4"] ?? "") + "\n" +
                                            Convert.ToString(reader["SNNAIM5"] ?? "") + "\n" + Convert.ToString(reader["SNNAIM6"] ?? "") + "\n" +
                                            Convert.ToString(reader["SNNAIM7"] ?? "") + "\n" + Convert.ToString(reader["SNNAIM8"] ?? "") + "\n" +
                                            Convert.ToString(reader["SNNAIM9"] ?? "") + "\n" + Convert.ToString(reader["SNNAIM10"] ?? "");
                                        ag.snnaim = ag.snnaim.TrimEnd('\n');
                                        ag.snnaim = ag.snnaim.Replace('*', '"');
                                    }
                                }
                            }
                        }
                    }
                }
            }
            try
            {
                //Получение кода поставщика
                label2.BeginInvoke((MethodInvoker)(() => this.label2.Text = "Информация: получение информации о поставщиках"));
                strConnection = @"Provider=Microsoft.JET.Oledb.4.0;Data Source=" + Properties.Settings.Default.OtraslPath + "/RAHGOD/SPRXZ;Extended Properties=dBASE IV;User ID=;Password=;";
                using (_connection = new OleDbConnection(strConnection))
                {
                    _connection.Open();
                    using (OleDbCommand cmd = _connection.CreateCommand())
                    {
                        foreach (KontrolPlanIsp kpi in planIspList)
                        {
                            foreach (KontrolPlanIsp ag in kpi.agList)
                            {
                                if (ag.kodel != null && ag.kodel != "")
                                {
                                    cmd.CommandText = "SELECT * FROM OBRASK.DBF WHERE KODEL='" + ag.kodel + "'";
                                    using (OleDbDataReader reader = cmd.ExecuteReader())
                                    {
                                        while (reader.Read())
                                        {
                                            ag.kodotr = Convert.ToString(reader["KODOTR"] ?? "");
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
            catch
            {
                MessageBox.Show("Ошибка при получении кода поставщика комплектации!");
            }
            //Получение информации о заводе
            label2.BeginInvoke((MethodInvoker)(() => this.label2.Text = "Информация: получение информации о заводах"));
            strConnection = @"Provider=Microsoft.JET.Oledb.4.0;Data Source=" + Properties.Settings.Default.OtraslPath + "/SPRXZ;Extended Properties=dBASE IV;User ID=;Password=;";
            using (_connection = new OleDbConnection(strConnection))
            {
                _connection.Open();
                using (OleDbCommand cmd = _connection.CreateCommand())
                {
                    foreach (KontrolPlanIsp kpi in planIspList)
                    {
                        foreach (KontrolPlanIsp ag in kpi.agList)
                        {
                            cmd.CommandText = "SELECT KODOTR, NZAV1, NZAV2, NZAV3, INN FROM SPRXZ.DBF WHERE KODOTR='" + ag.kodotr + "'";
                            using (OleDbDataReader reader = cmd.ExecuteReader())
                            {
                                while (reader.Read())
                                {
                                    ag.nzav = Convert.ToString(reader["NZAV1"]) + "\n" + Convert.ToString(reader["NZAV2"]) + "\n" + Convert.ToString(reader["NZAV3"]);
                                    ag.nzav = ag.nzav.TrimEnd('\n');
                                    ag.nzav = ag.nzav.Replace('*', '"');
                                    ag.inn = Convert.ToString(reader["INN"]);
                                }
                            }
                        }
                    }
                }
            }
            //Получение информации о ед. измерения
            label2.BeginInvoke((MethodInvoker)(() => this.label2.Text = "Информация: получение информации о ед. измерений"));
            strConnection = @"Provider=Microsoft.JET.Oledb.4.0;Data Source=" + Properties.Settings.Default.OtraslPath + "/SPRAV;Extended Properties=dBASE IV;User ID=;Password=;";
            using (_connection = new OleDbConnection(strConnection))
            {
                _connection.Open();
                using (OleDbCommand cmd = _connection.CreateCommand())
                {
                    foreach (KontrolPlanIsp kpi in planIspList)
                    {
                        foreach (KontrolPlanIsp ag in kpi.agList)
                        {
                            cmd.CommandText = "SELECT * FROM SPEIZ2 WHERE KODEIZ='" + ag.kodeiz + "'";
                            using (OleDbDataReader reader = cmd.ExecuteReader())
                            {
                                while (reader.Read())
                                {
                                    ag.naimeiz = Convert.ToString(reader["NAIMEIZ"]);
                                    ag.naik = Convert.ToString(reader["NAIK"]);
                                }
                            }
                        }
                    }
                }
            }
            //MessageBox.Show("Заполнено!");
            return planIspList;
        }

        private void PrintKontrolAG(List<KontrolPlanIsp> planIspList, string uchNumber, string countEkz)
        {
            label2.BeginInvoke((MethodInvoker)(() => this.label2.Text = "Информация: подготовка к печати"));
            //Сортируем анализ готовности
            var sortedList = planIspList.OrderBy(a => a.kodotr).ThenBy(b => b.kodel).ThenBy(c => c.shvid);
            //Создаем документ
            Word.Application oWord = new Word.Application();
            oWord.Visible = checkBox2.Checked;
            try
            {
                //Открытие документа
                Word.Document wordDocument;
                string path = Directory.GetCurrentDirectory() + templateFileNameWordKontrolAG;
                //MessageBox.Show("Путь: " + path);
                wordDocument = oWord.Documents.Open(path);
                //Поиск и замена текста
                functionReplaceInText(wordDocument, oWord, "{uchNumber}", uchNumber);
                functionReplaceInText(wordDocument, oWord, "{year}", "2020");
                //Добавление в файл таблицы
                Object autoFitBehavior = Word.WdAutoFitBehavior.wdAutoFitWindow;
                //Добавление в файл таблицы
                var range = wordDocument.Paragraphs[wordDocument.Paragraphs.Count - 1].Range;
                wordDocument.Tables.Add(range, 1, 11, autoFitBehavior);
                Word.Table oTable = wordDocument.Tables[1];
                //Запрет на перенос строк
                oTable.Rows.AllowBreakAcrossPages = 0;
                string[] nameHeaderTable = {
                                               "№ п/п", 
                                               "Код и наименование\nиспытуемого элемента (ИЭ)",
                                               "Код и\nнаименование\nвида испытания", 
                                               "Код и наименованеи\nзавода-\nизготовителя ИЭ", 
                                               "Объём\nпоставок",
                                               "Готовность",
                                               "Код и наименование\nкомплектующего элемента",
                                               "Код/\nЕд.\nизм.",
                                               "Объём\nпотребности КЭ",
                                               "Код,наименование и\nместонахождение\nорганизации-\nпоставщика\nИНН",
                                               "Документ\nподтверждающий\nобеспеченность КЭ"
                                           };
                for (int i = 0; i < oTable.Columns.Count; i++)
                {
                    Word.Range wordCellRange = oTable.Cell(1, i + 1).Range;
                    wordCellRange.ParagraphFormat.Alignment = Word.WdParagraphAlignment.wdAlignParagraphCenter;
                    wordCellRange.Text = nameHeaderTable[i];
                    wordCellRange.Font.Name = "Times New Roman";
                    wordCellRange.Font.Size = 10;
                }
                //Повтор заголовков
                oTable.Rows[1].HeadingFormat = -1;
                //Попытка вертикально центрирования заголовка
                oTable.Rows[1].Cells.VerticalAlignment = Word.WdCellVerticalAlignment.wdCellAlignVerticalCenter;
                //Вывод
                int numberPosition = 1;
                int posRows = 2;
                foreach (KontrolPlanIsp kpi in sortedList)
                {
                    label2.BeginInvoke((MethodInvoker)(() => this.label2.Text = "Информация: печать элемента " + numberPosition + " из " + planIspList.Count));
                    oTable.Rows.Add();
                    //Порядковый номер
                    Word.Range wordCellRange = oTable.Cell(posRows, 1).Range;
                    wordCellRange.Text = Convert.ToString(numberPosition);
                    wordCellRange.Cells.VerticalAlignment = Word.WdCellVerticalAlignment.wdCellAlignVerticalCenter;
                    //Испытуемый элемент
                    wordCellRange = oTable.Cell(posRows, 2).Range;
                    if (!string.IsNullOrWhiteSpace(kpi.kodel))
                        wordCellRange.Text = "Код " + Convert.ToString(kpi.kodel) + "\n\n" + kpi.snhert + "\n" + kpi.snindiz + "\n" + kpi.snnaim;
                    else
                        wordCellRange.Text = Convert.ToString(kpi.kodel) + "\n\n" + kpi.snhert + "\n" + kpi.snindiz + "\n" + kpi.snnaim;
                    wordCellRange.ParagraphFormat.Alignment = Word.WdParagraphAlignment.wdAlignParagraphLeft;
                    wordCellRange.Cells.VerticalAlignment = Word.WdCellVerticalAlignment.wdCellAlignVerticalTop;
                    //Вид испытания
                    wordCellRange = oTable.Cell(posRows, 3).Range;
                    if (!string.IsNullOrWhiteSpace(kpi.shvid))
                        wordCellRange.Text = "Код " + Convert.ToString(kpi.shvid) + "\n\n\n\n" + kpi.nvid;
                    else
                        wordCellRange.Text = Convert.ToString(kpi.shvid) + "\n\n\n\n" + kpi.nvid;
                    wordCellRange.Cells.VerticalAlignment = Word.WdCellVerticalAlignment.wdCellAlignVerticalTop;
                    //Завод-изготовитель ИЭ
                    wordCellRange = oTable.Cell(posRows, 4).Range;
                    if (!string.IsNullOrWhiteSpace(kpi.kodotr))
                        wordCellRange.Text = "Код " + Convert.ToString(kpi.kodotr) + "\n\n\n\n" + kpi.nzav;
                    else
                        wordCellRange.Text = Convert.ToString(kpi.kodotr) + "\n\n\n\n" + kpi.nzav;
                    wordCellRange.Cells.VerticalAlignment = Word.WdCellVerticalAlignment.wdCellAlignVerticalTop;
                    //Объём поставок
                    wordCellRange = oTable.Cell(posRows, 5).Range;
                    wordCellRange.Text = "\n\n\n\n" + Convert.ToString(kpi.qolg);
                    wordCellRange.Cells.VerticalAlignment = Word.WdCellVerticalAlignment.wdCellAlignVerticalTop;
                    //Готовность

                    //Комплектующие элементы
                    bool isNewRow = false;
                    foreach (KontrolPlanIsp ag in kpi.agList)
                    {
                        if (isNewRow)
                        {
                            oTable.Rows.Add();
                            posRows++;
                        }
                        //Комплектующий элемент
                        wordCellRange = oTable.Cell(posRows, 7).Range;
                        if (!string.IsNullOrWhiteSpace(ag.kodel))
                            wordCellRange.Text = "Код " + Convert.ToString(ag.kodel) + "\n\n" + ag.snhert + "\n" + ag.snindiz + "\n" + ag.snnaim;
                        else
                            wordCellRange.Text = Convert.ToString(ag.kodel) + "\n\n" + ag.snhert + "\n" + ag.snindiz + "\n" + ag.snnaim;
                        wordCellRange.ParagraphFormat.Alignment = Word.WdParagraphAlignment.wdAlignParagraphLeft;
                        wordCellRange.Cells.VerticalAlignment = Word.WdCellVerticalAlignment.wdCellAlignVerticalTop;
                        //Ед. измерения.
                        wordCellRange = oTable.Cell(posRows, 8).Range;
                        if (!string.IsNullOrWhiteSpace(ag.kodeiz))
                            wordCellRange.Text = "Код " + Convert.ToString(ag.kodeiz) + "\n\n\n\n" + Convert.ToString(ag.naimeiz);
                        else
                            wordCellRange.Text = Convert.ToString(ag.kodeiz) + "\n\n\n\n" + Convert.ToString(ag.naimeiz);
                        wordCellRange.Cells.VerticalAlignment = Word.WdCellVerticalAlignment.wdCellAlignVerticalTop;
                        //Объём потребности
                        wordCellRange = oTable.Cell(posRows, 9).Range;
                        wordCellRange.Text = "\n\n\n\n" + Convert.ToString(ag.qolg);
                        wordCellRange.Cells.VerticalAlignment = Word.WdCellVerticalAlignment.wdCellAlignVerticalTop;
                        //Организация-поставщик
                        wordCellRange = oTable.Cell(posRows, 10).Range;
                        if (!string.IsNullOrWhiteSpace(ag.kodotr))
                            wordCellRange.Text = "Код " + Convert.ToString(ag.kodotr) + "\n\n\n\n" + ag.nzav + "\n" + ag.inn;
                        else
                            wordCellRange.Text = Convert.ToString(ag.kodotr) + "\n\n\n\n" + ag.nzav + "\n" + ag.inn;
                        wordCellRange.Cells.VerticalAlignment = Word.WdCellVerticalAlignment.wdCellAlignVerticalTop;
                        //Документ подтверждающий готовность

                        isNewRow = true;
                    }
                    numberPosition++;
                    posRows++;
                }
                //Создание экземпляров
                label2.BeginInvoke((MethodInvoker)(() => this.label2.Text = "Информация: создание экземпляров"));
                wordDocument.Range().Copy();
                var rangeAllDocumentEkz = wordDocument.Content;
                rangeAllDocumentEkz.Find.ClearFormatting();
                //for (int j = 0; j < planIspList.Count; j++)
                {
                    functionReplaceInText(wordDocument, oWord, "{numberEkz}", "1");
                }
                for (int i = 0; i < Convert.ToInt32(countEkz) - 1; i++)
                {
                    //В конец документа
                    object what = Word.WdGoToItem.wdGoToLine;
                    object which = Word.WdGoToDirection.wdGoToLast;
                    Word.Range endRange = wordDocument.GoTo(ref what, ref which);
                    //Создаем разрыв РАЗДЕЛА (не страниц)
                    endRange.InsertBreak(Word.WdBreakType.wdSectionBreakNextPage);
                    //Вставляем
                    endRange.Paste();
                    rangeAllDocumentEkz = wordDocument.Content;
                    rangeAllDocumentEkz.Find.ClearFormatting();
                    rangeAllDocumentEkz.Find.Execute(FindText: "{numberEkz}", ReplaceWith: i + 2);
                }
                //Очистка буфера
                Clipboard.Clear();
                //Сохранение
                label2.BeginInvoke((MethodInvoker)(() => this.label2.Text = "Информация: сохранение"));
                DateTime dateNowFileName = DateTime.Now;
                string strSaveName = Convert.ToString(dateNowFileName);
                strSaveName = strSaveName.Replace(':', '-');
                if (!Directory.Exists(Properties.Settings.Default.PathForReports))
                    Directory.CreateDirectory(Directory.GetCurrentDirectory() + "/reports");
                wordDocument.SaveAs(Properties.Settings.Default.PathForReports + "/Годовой анализ готовности" + " " + strSaveName + ".docx");
                wordDocument.Close();
                oWord.Quit();
                label2.BeginInvoke((MethodInvoker)(() => this.label2.Text = "Информация: анализ готовности создан"));
                MessageBox.Show("Годовой анализ готовности создан!");
            }
            catch (Exception ex)
            {
                oWord.Visible = true;
                label2.BeginInvoke((MethodInvoker)(() => this.label2.Text = "Информация: ошибка!"));
                MessageBox.Show("error: " + ex.Message);
            }
        }

        #endregion

        //Печать номенклатуры (испытуемых элементов / по сборке) с комплектующими элементами
        //Представляет собой анализ готовности БЕЗ распределений по заводам
        //Т.е. обеспечивает уникальность ключа КОД элемента => Вид испытания
        //И используется при объёмах равных 1
        //Потребность в комплектации не нужна, а нужно количество выстрелов от партии из УФИ
        #region ПЕЧАТЬ НОМЕНКЛАТУРА С КОМПЛЕКТУЮЩИМИ ЭЛЕМЕНТАМИ
        private void button18_Click(object sender, EventArgs e)
        {
            BlockAllButton();
            if (Properties.Settings.Default.OtraslPath.Length > 0)
            {
                if (Properties.Settings.Default.KontrolFolderPath.Length > 0)
                {
                    try
                    {
                        FormChoseUchAndCount fcuac = new FormChoseUchAndCount();
                        fcuac.ShowDialog();
                        if (fcuac.isExit)
                        {
                            label2.BeginInvoke((MethodInvoker)(() => this.label2.Text = "Информация: подготовка к печати"));
                            Thread thread = new Thread(() =>
                            {
                                try
                                {
                                    List<KontrolPlanIsp> planIspList = new List<KontrolPlanIsp>();
                                    //Получение плана испытаний
                                    planIspList = GetKontrolPlanIsp(planIspList);
                                    //Фильтрация на повтор
                                    planIspList = FiltrKontrolNomenclature(planIspList);
                                    //Заполнение плана испытанйи
                                    planIspList = FillKontrolPlanIsp(planIspList);
                                    //Заполнение анализа готовности
                                    planIspList = FillKontrolAG(planIspList, false);
                                    //Заполнение из УФИ
                                    planIspList = FillNomenclatureUFI(planIspList);
                                    //Печать номенклатуры
                                    PrintKontrolNomenclature(planIspList, fcuac.uchNumber, fcuac.countEkz);
                                }
                                catch (Exception ex)
                                {
                                    MessageBox.Show("Очередная ошибка ин поток\n" + ex.Message + "\n" + ex.Source + "\n" + ex.StackTrace + "\n" + ex.TargetSite, "Ошибка", MessageBoxButtons.OK, MessageBoxIcon.Error);
                                }
                                finally
                                {
                                    BlockAllButton(false);
                                }
                            });
                            thread.SetApartmentState(ApartmentState.STA);
                            thread.Start();
                        }
                        else
                            BlockAllButton(false);
                    }
                    catch (Exception ex)
                    {
                        BlockAllButton(false);
                        MessageBox.Show("Очередная ошибка\n" + ex.Message + "\n" + ex.Source + "\n" + ex.StackTrace + "\n" + ex.TargetSite, "Ошибка", MessageBoxButtons.OK, MessageBoxIcon.Error);
                    }
                }
                else
                    MessageBox.Show("Выберите поддиректорию KONTROL");
            }
            else
                MessageBox.Show("Выберите директорию OTRASL");
        }

        private List<KontrolPlanIsp> FillNomenclatureUFI(List<KontrolPlanIsp> planIspList)
        {
            //Получение информации о количество выстрелов от партии
            label2.BeginInvoke((MethodInvoker)(() => this.label2.Text = "Информация: получение информации о кол-ве выстрелов из УФИ"));
            string strConnection = @"Provider=Microsoft.JET.Oledb.4.0;Data Source=" + Properties.Settings.Default.OtraslMesplPath + "/MESPL/UFI;Extended Properties=dBASE IV;User ID=;Password=;";
            using (_connection = new OleDbConnection(strConnection))
            {
                _connection.Open();
                using (OleDbCommand cmd = _connection.CreateCommand())
                {
                    foreach (KontrolPlanIsp kpi in planIspList)
                    {
                        cmd.CommandText = "SELECT * FROM UFI.DBF WHERE KODEL='" + kpi.kodel + "' AND SHVID='" + kpi.shvid + "'";
                        using (OleDbDataReader reader = cmd.ExecuteReader())
                        {
                            while (reader.Read())
                            {
                                kpi.vist = Convert.ToString(reader["VIST"]);
                            }
                        }
                    }
                }
            }
            return planIspList;
        }

        private List<KontrolPlanIsp> FiltrKontrolNomenclature(List<KontrolPlanIsp> planIspList)
        {
            label2.BeginInvoke((MethodInvoker)(() => this.label2.Text = "Информация: фильтрация плана"));
            //Необходимо убрать повторы комбинаций КОД элемента => ВИД испытания
            List<KontrolPlanIsp> filtrPlanIspList = new List<KontrolPlanIsp>();
            foreach (KontrolPlanIsp kpi in planIspList)
            {
                bool isProverka = true;
                foreach (KontrolPlanIsp fp in filtrPlanIspList)
                {
                    if (fp.kodel == kpi.kodel && fp.shvid == kpi.shvid)
                    {
                        isProverka = false;
                        break;
                    }
                }
                if (isProverka)
                    filtrPlanIspList.Add(kpi);
            }
            return filtrPlanIspList;
        }

        private void PrintKontrolNomenclature(List<KontrolPlanIsp> planIspList, string uchNumber, string countEkz)
        {
            label2.BeginInvoke((MethodInvoker)(() => this.label2.Text = "Информация: подготовка к печати"));
            //Сортируем анализ готовности
            var sortedList = planIspList.OrderBy(a => a.kodotr).ThenBy(b => b.kodel).ThenBy(c => c.shvid);
            //Создаем документ
            Word.Application oWord = new Word.Application();
            oWord.Visible = checkBox2.Checked;
            try
            {
                //Открытие документа
                Word.Document wordDocument;
                string path = Directory.GetCurrentDirectory() + templateFileNameWordKontrolNomenclature;
                //MessageBox.Show("Путь: " + path);
                wordDocument = oWord.Documents.Open(path);
                //Поиск и замена текста
                functionReplaceInText(wordDocument, oWord, "{uchNumber}", uchNumber);
                functionReplaceInText(wordDocument, oWord, "{year}", "2020");
                //Добавление в файл таблицы
                Object autoFitBehavior = Word.WdAutoFitBehavior.wdAutoFitWindow;
                //Добавление в файл таблицы
                var range = wordDocument.Paragraphs[wordDocument.Paragraphs.Count - 1].Range;
                wordDocument.Tables.Add(range, 1, 10, autoFitBehavior);
                Word.Table oTable = wordDocument.Tables[1];
                //Запрет на перенос строк
                oTable.Rows.AllowBreakAcrossPages = 0;
                string[] nameHeaderTable = {
                                               "№ п/п", 
                                               "Код и наименование\nиспытуемого элемента (ИЭ)",
                                               "Код и\nнаименование\nвида испытания",
                                               "Количество",
                                               "Инв.№№\nТД и КД\n(ИЭ)",
                                               "Код и наименование\nкомплектующего элемента",
                                               "Код/\nЕд.\nизм.",
                                               "Потребность КЭ",
                                               "Код, наименование и\nместонахождение\nорганизации-\nпоставщика\nИНН",
                                               "Примечание"
                                           };
                for (int i = 0; i < oTable.Columns.Count; i++)
                {
                    Word.Range wordCellRange = oTable.Cell(1, i + 1).Range;
                    wordCellRange.ParagraphFormat.Alignment = Word.WdParagraphAlignment.wdAlignParagraphCenter;
                    wordCellRange.Text = nameHeaderTable[i];
                    wordCellRange.Font.Name = "Times New Roman";
                    wordCellRange.Font.Size = 10;
                }
                //Повтор заголовков
                oTable.Rows.First.HeadingFormat = -1;
                //Попытка вертикально центрирования заголовка
                oTable.Rows[1].Cells.VerticalAlignment = Word.WdCellVerticalAlignment.wdCellAlignVerticalCenter;

                //Для оглавления
                //List<string> oglavlenie = new List<string>();
                //List<string> oglavlenieStr = new List<string>();
                //string lastIndexKPI = "";

                //Вывод
                int numberPosition = 1;
                int posRows = 2;
                foreach (KontrolPlanIsp kpi in sortedList)
                {
                    label2.BeginInvoke((MethodInvoker)(() => this.label2.Text = "Информация: печать элемента " + numberPosition + " из " + planIspList.Count));
                    oTable.Rows.Add();
                    //Выравниваем вертикально к верху
                    oTable.Rows[posRows].HeadingFormat = 0;
                    oTable.Rows[posRows].Cells.VerticalAlignment = Word.WdCellVerticalAlignment.wdCellAlignVerticalTop;
                    //Порядковый номер
                    Word.Range wordCellRange = oTable.Cell(posRows, 1).Range;
                    wordCellRange.Text = Convert.ToString(numberPosition);
                    wordCellRange.Cells.VerticalAlignment = Word.WdCellVerticalAlignment.wdCellAlignVerticalCenter;
                    //Испытуемый элемент
                    wordCellRange = oTable.Cell(posRows, 2).Range;
                    if (!string.IsNullOrWhiteSpace(kpi.kodel))
                        wordCellRange.Text = "Код " + Convert.ToString(kpi.kodel) + "\n\n" + kpi.snhert + "\n" + kpi.snindiz.Trim().Trim('\n') + "\n" + kpi.snnaim;
                    else
                        wordCellRange.Text = Convert.ToString(kpi.kodel) + "\n\n" + kpi.snhert + "\n" + kpi.snindiz.Trim().Trim('\n') + "\n" + kpi.snnaim;
                    wordCellRange.ParagraphFormat.Alignment = Word.WdParagraphAlignment.wdAlignParagraphLeft;
                    if (!string.IsNullOrWhiteSpace(kpi.snindiz.Trim().Trim('\n')))
                        wordDocument.TablesOfAuthorities.MarkCitation(wordCellRange, kpi.snindiz.Trim().Trim('\n').ToString(), kpi.snindiz.Trim().Trim('\n').ToString(), Type.Missing, "1");
                    else if(!string.IsNullOrWhiteSpace(kpi.snhert.Trim().Trim('\n')))
                        wordDocument.TablesOfAuthorities.MarkCitation(wordCellRange, kpi.snhert.Trim().Trim('\n').ToString(), kpi.snhert.Trim().Trim('\n').ToString(), Type.Missing, "1");
                    else if (!string.IsNullOrWhiteSpace(kpi.snnaim.Trim().Trim('\n')))
                        wordDocument.TablesOfAuthorities.MarkCitation(wordCellRange, kpi.snnaim.Trim().Trim('\n').ToString(), kpi.snnaim.Trim().Trim('\n').ToString(), Type.Missing, "1");
                    //Вид испытания
                    wordCellRange = oTable.Cell(posRows, 3).Range;
                    if (!string.IsNullOrWhiteSpace(kpi.shvid))
                        wordCellRange.Text = "Код " + Convert.ToString(kpi.shvid) + "\n\n\n\n" + kpi.nvid;
                    else
                        wordCellRange.Text = Convert.ToString(kpi.shvid) + "\n\n\n\n" + kpi.nvid;
                    //Количество выстрелов от партии, согласно УФИ
                    wordCellRange = oTable.Cell(posRows, 4).Range;
                    wordCellRange.Text = "\n\n\n\n" + Convert.ToString(kpi.vist);
                    //ТД и КД (ИЭ)

                    //Для оглавления
                    /*if (kpi.snindiz != lastIndexKPI)
                    {
                        lastIndexKPI = kpi.snindiz;
                        oglavlenie.Add(kpi.snindiz.Replace("\n", "").Trim());
                        oglavlenieStr.Add(wordDocument.ComputeStatistics(Word.WdStatistic.wdStatisticPages, false).ToString());
                    }*/

                    //Комплектующие элементы
                    bool isNewRow = false;
                    var sortedAGList = kpi.agList.OrderBy(b => b.kodel);
                    foreach (KontrolPlanIsp ag in sortedAGList)
                    {
                        if (isNewRow)
                        {
                            oTable.Rows.Add();
                            posRows++;
                        }
                        //Комплектующий элемент
                        wordCellRange = oTable.Cell(posRows, 6).Range;
                        if (!string.IsNullOrWhiteSpace(ag.kodel))
                            wordCellRange.Text = "Код " + Convert.ToString(ag.kodel) + "\n\n" + ag.snhert + "\n" + ag.snindiz + "\n" + ag.snnaim;
                        else
                            wordCellRange.Text = Convert.ToString(ag.kodel) + "\n\n" + ag.snhert + "\n" + ag.snindiz + "\n" + ag.snnaim;
                        wordCellRange.ParagraphFormat.Alignment = Word.WdParagraphAlignment.wdAlignParagraphLeft;
                        //Ед. измерения.
                        wordCellRange = oTable.Cell(posRows, 7).Range;
                        if (!string.IsNullOrWhiteSpace(ag.kodeiz))
                            wordCellRange.Text = "Код " + Convert.ToString(ag.kodeiz) + "\n\n\n\n" + Convert.ToString(ag.naimeiz);
                        else
                            wordCellRange.Text = Convert.ToString(ag.kodeiz) + "\n\n\n\n" + Convert.ToString(ag.naimeiz);
                        //Объём потребности
                        //wordCellRange = oTable.Cell(posRows, 8).Range;
                        //wordCellRange.Text = "\n\n\n\n" + Convert.ToString(ag.qolg);

                        //Организация-поставщик
                        wordCellRange = oTable.Cell(posRows, 9).Range;
                        if (!string.IsNullOrWhiteSpace(ag.kodotr))
                            wordCellRange.Text = "Код " + Convert.ToString(ag.kodotr) + "\n\n\n\n" + ag.nzav + "\n" + ag.inn;
                        else
                            wordCellRange.Text = Convert.ToString(ag.kodotr) + "\n\n\n\n" + ag.nzav + "\n" + ag.inn;
                        //Примечание

                        isNewRow = true;
                    }

                    numberPosition++;
                    posRows++;
                }
                //Оглавление
                label2.BeginInvoke((MethodInvoker)(() => this.label2.Text = "Информация: создание оглавления"));
                wordDocument.Paragraphs.Add();
                range = wordDocument.Paragraphs[wordDocument.Paragraphs.Count].Range;
                range.InsertBreak();
                range = wordDocument.Paragraphs[wordDocument.Paragraphs.Count].Range;
                wordDocument.Paragraphs.Add();
                range = wordDocument.Paragraphs[wordDocument.Paragraphs.Count - 1].Range;

                //Оглавление через ссылки
                /*for (int i = 2; i < planIspList.Count + 2; i++)
                {
                    label2.BeginInvoke((MethodInvoker)(() => this.label2.Text = "Информация: создание оглавления " + (i - 1).ToString() + " из " + (planIspList.Count + 1).ToString()));
                    foreach (KontrolPlanIsp kpi in sortedList)
                        wordDocument.TablesOfAuthorities.MarkCitation(oTable.Cell(i, 2).Range, kpi.snindiz.Trim().Trim('\n').ToString(), kpi.snindiz.Trim().Trim('\n').ToString(), Type.Missing, "1");
                }*/
                //wordDocument.Paragraphs.Add();
                wordDocument.Paragraphs.Add();
                range = wordDocument.Paragraphs[wordDocument.Paragraphs.Count - 1].Range;
                range.Bold = 1;
                range.Text = "Индекс ИЭ";
                wordDocument.Paragraphs.Add();
                wordDocument.Paragraphs.Add();
                range = wordDocument.Paragraphs[wordDocument.Paragraphs.Count - 1].Range;
                range.Bold = 0;
                wordDocument.TablesOfAuthorities.Add(range, "1", Passim: false, KeepEntryFormatting: true, IncludeCategoryHeader: false);
                wordDocument.TablesOfAuthorities[1].KeepEntryFormatting = true;
                wordDocument.TablesOfAuthorities[1].Passim = false;
                wordDocument.TablesOfAuthorities[1].TabLeader = Word.WdTabLeader.wdTabLeaderDots;

                /*wordDocument.Tables.Add(range, 1, 7, autoFitBehavior);
                Word.Table oTableOglavlenie = wordDocument.Tables[2];
                //Запрет на перенос строк
                oTableOglavlenie.Rows.AllowBreakAcrossPages = 0;
                string[] nameHeaderTable2 = { "№ п/п", "Индекс ИЭ", "Стр.", "", "№ п/п", "Индекс ИЭ", "Стр." };
                for (int i = 0; i < oTableOglavlenie.Columns.Count; i++)
                {
                    Word.Range wordCellRange = oTableOglavlenie.Cell(1, i + 1).Range;
                    wordCellRange.ParagraphFormat.Alignment = Word.WdParagraphAlignment.wdAlignParagraphCenter;
                    wordCellRange.Text = nameHeaderTable2[i];
                    wordCellRange.Font.Name = "Times New Roman";
                }
                oTableOglavlenie.Rows.First.HeadingFormat = -1;
                oTableOglavlenie.Columns[1].Width = 20;
                oTableOglavlenie.Columns[2].Width = 300;
                oTableOglavlenie.Columns[3].Width = 20;
                oTableOglavlenie.Columns[4].Width = 70;
                oTableOglavlenie.Columns[5].Width = 20;
                oTableOglavlenie.Columns[6].Width = 300;
                oTableOglavlenie.Columns[7].Width = 20;
                //Повтор заголовка
                oTableOglavlenie.Rows.First.HeadingFormat = -1;
                //Вертикальное центрирование заголовка
                oTableOglavlenie.Rows[1].Cells.VerticalAlignment = Word.WdCellVerticalAlignment.wdCellAlignVerticalCenter;
                //Вывод оглавления
                int posOglavlenieRows = 3;
                //int posOglavlenieCols = 0;
                for (int i = 0; i < oglavlenie.Count; i++)
                {
                    oTableOglavlenie.Rows.Add();
                    oTableOglavlenie.Rows.Add();
                    Word.Range wordCellRange = oTableOglavlenie.Cell(posOglavlenieRows, 1).Range;
                    wordCellRange.Text = Convert.ToString(i + 1);
                    wordCellRange = oTableOglavlenie.Cell(posOglavlenieRows, 2).Range;
                    wordCellRange.Text = oglavlenie[i];
                    wordCellRange = oTableOglavlenie.Cell(posOglavlenieRows, 3).Range;
                    wordCellRange.Text = oglavlenieStr[i];
                    posOglavlenieRows++;
                    posOglavlenieRows++;
                }
                oTableOglavlenie.Columns.Borders[Word.WdBorderType.wdBorderTop].LineStyle = Word.WdLineStyle.wdLineStyleNone;
                oTableOglavlenie.Columns.Borders[Word.WdBorderType.wdBorderLeft].LineStyle = Word.WdLineStyle.wdLineStyleNone;
                oTableOglavlenie.Columns.Borders[Word.WdBorderType.wdBorderBottom].LineStyle = Word.WdLineStyle.wdLineStyleNone;
                oTableOglavlenie.Columns.Borders[Word.WdBorderType.wdBorderRight].LineStyle = Word.WdLineStyle.wdLineStyleNone;
                oTableOglavlenie.Columns.Borders[Word.WdBorderType.wdBorderHorizontal].LineStyle = Word.WdLineStyle.wdLineStyleNone;
                oTableOglavlenie.Columns.Borders[Word.WdBorderType.wdBorderVertical].LineStyle = Word.WdLineStyle.wdLineStyleNone;*/

                //Создание экземпляров
                label2.BeginInvoke((MethodInvoker)(() => this.label2.Text = "Информация: создание экземпляров"));
                wordDocument.Range().Copy();
                var rangeAllDocumentEkz = wordDocument.Content;
                rangeAllDocumentEkz.Find.ClearFormatting();
                //for (int j = 0; j < planIspList.Count; j++)
                {
                    functionReplaceInText(wordDocument, oWord, "{numberEkz}", "1");
                }
                for (int i = 0; i < Convert.ToInt32(countEkz) - 1; i++)
                {
                    //В конец документа
                    object what = Word.WdGoToItem.wdGoToLine;
                    object which = Word.WdGoToDirection.wdGoToLast;
                    Word.Range endRange = wordDocument.GoTo(ref what, ref which);
                    //Создаем разрыв РАЗДЕЛА (не страниц)
                    endRange.InsertBreak(Word.WdBreakType.wdSectionBreakNextPage);
                    //Вставляем
                    endRange.Paste();
                    rangeAllDocumentEkz = wordDocument.Content;
                    rangeAllDocumentEkz.Find.ClearFormatting();
                    rangeAllDocumentEkz.Find.Execute(FindText: "{numberEkz}", ReplaceWith: i + 2);
                }
                //Очистка буфера
                Clipboard.Clear();
                //Сохранение
                label2.BeginInvoke((MethodInvoker)(() => this.label2.Text = "Информация: сохранение"));
                DateTime dateNowFileName = DateTime.Now;
                string strSaveName = Convert.ToString(dateNowFileName);
                strSaveName = strSaveName.Replace(':', '-');
                if (!Directory.Exists(Properties.Settings.Default.PathForReports))
                    Directory.CreateDirectory(Directory.GetCurrentDirectory() + "/reports");
                wordDocument.SaveAs(Properties.Settings.Default.PathForReports + "/Номенклатура" + " " + strSaveName + ".docx");
                wordDocument.Close();
                oWord.Quit();
                label2.BeginInvoke((MethodInvoker)(() => this.label2.Text = "Информация: номенклатура создана"));
                MessageBox.Show("Номенклатура создана!");
            }
            catch (Exception ex)
            {
                oWord.Visible = true;
                label2.BeginInvoke((MethodInvoker)(() => this.label2.Text = "Информация: ошибка!"));
                MessageBox.Show("error: " + ex.Message);
            }
        }
        #endregion


        //---------ДОПОЛНИТЕЛЬНЫЕ ФУНКЦИИ--------- 
        //Функция печати уголка
        private void CreateUgolok(string uchNumber, string countEkz, string countList, string HMD, string author, string date, out string savePath, Label label = null)
        {
            savePath = null;

            if (label != null)
                label.BeginInvoke((MethodInvoker)(() => label.Text = "Информация: создание уголка"));
            try
            {
                //Создаем word
                Word.Application oWord = new Word.Application();
                oWord.Visible = checkBox1.Checked;
                try
                {
                    //Создание документа
                    Word.Document wordDocument = new Word.Document();
                    wordDocument.PageSetup.Orientation = Word.WdOrientation.wdOrientLandscape;

                    //Создание текста
                    for (int i = 0; i < 24; i++)
                        wordDocument.Paragraphs.Add();
                    Word.Range range = wordDocument.Paragraphs[wordDocument.Paragraphs.Count].Range;
                    range.Text = uchNumber;
                    wordDocument.Paragraphs.Add();
                    range = wordDocument.Paragraphs[wordDocument.Paragraphs.Count].Range;
                    range.Text = countEkz;
                    wordDocument.Paragraphs.Add();
                    range = wordDocument.Paragraphs[wordDocument.Paragraphs.Count].Range;
                    range.Text = countList;
                    wordDocument.Paragraphs.Add();
                    range = wordDocument.Paragraphs[wordDocument.Paragraphs.Count].Range;
                    range.Text = HMD;
                    wordDocument.Paragraphs.Add();
                    range = wordDocument.Paragraphs[wordDocument.Paragraphs.Count].Range;
                    range.Text = author;
                    wordDocument.Paragraphs.Add();
                    range = wordDocument.Paragraphs[wordDocument.Paragraphs.Count].Range;
                    range.Text = date;

                    //Промежуточный интервал
                    wordDocument.Range().ParagraphFormat.SpaceAfter = 0;
                    wordDocument.Range().Font.Name = "Times New Roman";

                    //Сохранение
                    DateTime dateNowFileName = DateTime.Now;
                    string strSaveName = Convert.ToString(dateNowFileName);
                    strSaveName = strSaveName.Replace(':', '-');
                    if (!Directory.Exists(Properties.Settings.Default.PathForReports))
                        Directory.CreateDirectory(Directory.GetCurrentDirectory() + "/reports");
                    savePath = Properties.Settings.Default.PathForReports + "/Уголок " + " " + strSaveName + ".docx";
                    wordDocument.SaveAs(savePath);
                    wordDocument.Close();
                    oWord.Quit();
                    MessageBox.Show("Уголок создан!");
                    if (label != null)
                        label.BeginInvoke((MethodInvoker)(() => label.Text = "Информация: уголок создан!"));
                }
                catch (Exception ex)
                {
                    MessageBox.Show("error: " + ex.Message);
                    if (label != null)
                        label.BeginInvoke((MethodInvoker)(() => label.Text = "Информация: ошибка печати уголка!"));
                    oWord.Visible = true;
                }
            }
            catch (Exception ex)
            {
                MessageBox.Show("error: " + ex.Message);
                if (label != null)
                    label.BeginInvoke((MethodInvoker)(() => label.Text = "Информация: ошибка печати уголка!"));
            }
        }

        //Функция открытия документа
        private void OpenTheDocument(string path, Label label = null)
        {
            if (path != null)
            {
                if (label != null)
                    label.BeginInvoke((MethodInvoker)(() => label.Text = "Информация: открытие документа"));
                if (File.Exists(path))
                    System.Diagnostics.Process.Start(path);
            }
        }

        //Функция замены текста (+колонтитул)
        private void functionReplaceInText(Word.Document wordDocument, Word.Application oWord, string findText, string replaceText)
        {
            //var range = wordDocument.Content;
            //range.Find.ClearFormatting();
            //range.Find.Execute(FindText: findText, ReplaceWith: replaceText);
            object wrap = Word.WdFindWrap.wdFindContinue;
            //Word.Range ra = oWord.Inse
            wordDocument.TrackRevisions = false;
            //Word.Range r = 
            foreach (Word.Range range in wordDocument.StoryRanges)
            {
                Word.Range rng = range;
                while (rng != null)
                {
                    rng.Find.ClearFormatting();
                    rng.Find.Execute(findText, ReplaceWith: replaceText, Replace: Word.WdReplace.wdReplaceAll);
                    rng = rng.NextStoryRange;
                }
                //range.Find.ClearFormatting();
                //range.Find.Execute(FindText: findText, ReplaceWith: replaceText, Wrap: wrap, MatchCase: true, Replace: Word.WdReplace.wdReplaceAll, MatchWholeWord: true, Forward: true);
            }
        }

        //Функция блокировки/разблокировки кнопок
        private void BlockAllButton(bool isBlock = true)
        {
            foreach (Button button in buttonList)
            {
                button.Enabled = !isBlock;
                if (!isBlock)
                    button.BackColor = saveColorBTN;
            }
            isAgreeExit = !isBlock;
        }

        //Перед закрытием формы
        private void Form1_FormClosing(object sender, FormClosingEventArgs e)
        {
            e.Cancel = !isAgreeExit;
        }

        //Проба пера
        private void button17_Click(object sender, EventArgs e)
        {
            /*var ds = button17.BackColor;
            button17.BackColor = Color.Aqua;
            button17.Enabled = false;
            button17.BackColor = ds;
            button17.Enabled = true;
            string asdasd = "    ";
            if (!string.IsNullOrWhiteSpace(asdasd))
                MessageBox.Show("Не пустая");
            return;*/
            BlockAllButton();
            label1.BeginInvoke((MethodInvoker)(() => this.label1.Text = "Информация: подготовка документа к печати"));
            try
            {
                Thread thread = new Thread(() =>
                {
                    try
                    {
                        //Создаем документ
                        Word.Application oWord = new Word.Application();
                        oWord.Visible = checkBox1.Checked;
                        try
                        {
                            string path = Directory.GetCurrentDirectory() + templateFileNameWordQueryOnMonthOneUchet;
                            //MessageBox.Show("Путь: " + path);
                            Word.Document wordDocument = oWord.Documents.Open(path);

                            //Поиск и замена текста

                            //functionReplaceInText(wordDocument, oWord, "{otprText}", isp.adres);
                            Word.Table oTable = wordDocument.Tables[1];
                            Word.Range wordCellRange = oTable.Cell(1, 2).Range;
                            wordCellRange.Text = "ВЕДОМОСТЬ ПОСТАВОК НА ИСПЫТАНИЯ\n\n" + "Какой-то там адресс\nasd\nqwe";
                            //Код завода
                            wordDocument.Paragraphs.Add();
                            var range = wordDocument.Paragraphs[wordDocument.Paragraphs.Count - 1].Range;
                            range.Text = "Кодик";
                            //strNewCode = isp.kodotr;
                            //Добавление в файл таблицы
                            wordDocument.Paragraphs.Add();
                            wordDocument.Paragraphs.Add();
                            Object autoFitBehavior = Word.WdAutoFitBehavior.wdAutoFitWindow;
                            range = wordDocument.Paragraphs[wordDocument.Paragraphs.Count - 1].Range;
                            wordDocument.Tables.Add(range, 2, 10, autoFitBehavior);
                            oTable = wordDocument.Tables[2];
                            string[] nameHeaderTable = { "№\nп/п", "Код и наименование\nкомплектующего\nэлемента", "Ед. измерения", "Поставщик", "Потребность в КЭ", "Примечание", "год", "1 кв", "2 кв", "3 кв", "4 кв" };
                            for (int i = 0; i < 5; i++)
                            {
                                wordCellRange = oTable.Cell(1, i + 1).Range;
                                wordCellRange.ParagraphFormat.Alignment = Word.WdParagraphAlignment.wdAlignParagraphCenter;
                                wordCellRange.Text = nameHeaderTable[i];
                                wordCellRange.Rows.HeadingFormat = -1;
                                wordCellRange.Font.Name = "Times New Roman";
                            }
                            wordCellRange = oTable.Cell(1, 10).Range;
                            wordCellRange.Text = nameHeaderTable[5];

                            oTable.Cell(1, 1).Width = 35;
                            oTable.Cell(2, 1).Width = 35;
                            oTable.Cell(1, 2).Width = 120;
                            oTable.Cell(2, 2).Width = 120;
                            oTable.Cell(1, 3).Width = 60;
                            oTable.Cell(2, 3).Width = 60;
                            oTable.Cell(1, 4).Width = 80;
                            oTable.Cell(2, 4).Width = 80;
                            oTable.Cell(1, 5).Width = 80;
                            oTable.Cell(2, 5).Width = 80;
                            oTable.Cell(1, 6).Width = 80;
                            oTable.Cell(2, 6).Width = 80;
                            oTable.Cell(1, 7).Width = 60;
                            oTable.Cell(2, 7).Width = 60;
                            oTable.Cell(1, 8).Width = 60;
                            oTable.Cell(2, 8).Width = 60;
                            oTable.Cell(1, 9).Width = 60;
                            oTable.Cell(2, 9).Width = 60;
                            oTable.Cell(1, 10).Width = 80;
                            oTable.Cell(2, 10).Width = 80;

                            oTable.Cell(1, 10).Merge(oTable.Cell(2, 10));
                            oTable.Cell(1, 5).Merge(oTable.Cell(1, 9));
                            oTable.Cell(1, 1).Merge(oTable.Cell(2, 1));
                            oTable.Cell(1, 2).Merge(oTable.Cell(2, 2));
                            oTable.Cell(1, 3).Merge(oTable.Cell(2, 3));
                            oTable.Cell(1, 4).Merge(oTable.Cell(2, 4));
                            
                            for (int i = 5; i < 10; i++)
                            {
                                wordCellRange = oTable.Cell(2, i).Range;
                                wordCellRange.ParagraphFormat.Alignment = Word.WdParagraphAlignment.wdAlignParagraphCenter;
                                wordCellRange.Text = nameHeaderTable[i + 1];
                                wordCellRange.Rows.HeadingFormat = -1;
                            }
                            MessageBox.Show("dsa");
                            return;
                            /*Object autoFitBehavior = Word.WdAutoFitBehavior.wdAutoFitWindow;
                            range = wordDocument.Paragraphs[wordDocument.Paragraphs.Count - 1].Range;
                            wordDocument.Tables.Add(range, 1, 7, autoFitBehavior);
                            oTable = wordDocument.Tables[2];
                            string[] nameHeaderTable = { "№\nп/п", "Шифр,\nнаименование\nэлемента", "Шифр,\nнаименование\nзавода", "Шифр,\nнаименование\nвида испытания", "Шифр,\nнаименование\nсистемы", "Количество выстрелов, шт", "Примечание", "Год", "1 кв", "2 кв", "3 кв", "4 кв" };
                            for (int i = 0; i < oTable.Columns.Count; i++)
                            {
                                wordCellRange = oTable.Cell(1, i + 1).Range;
                                wordCellRange.ParagraphFormat.Alignment = Word.WdParagraphAlignment.wdAlignParagraphCenter;
                                wordCellRange.Text = nameHeaderTable[i];
                                wordCellRange.Font.Name = "Times New Roman";
                            }*/
                            //MessageBox.Show("ds");
                            //Повтор заголовка
                            oTable.Rows[1].HeadingFormat = -1;
                            //MessageBox.Show("as");
                            //Вертикальное центрирование заголовка
                            oTable.Rows[1].Cells.VerticalAlignment = Word.WdCellVerticalAlignment.wdCellAlignVerticalCenter;
                            //MessageBox.Show("ms");
                            oTable.Cell(1, 1).Width = 50;
                            /*oTable.Cell(1, 2).Width = 150;
                            oTable.Cell(1, 3).Width = 100;
                            oTable.Cell(1, 4).Width = 100;
                            oTable.Cell(1, 5).Width = 100;
                            oTable.Cell(1, 6).Width = 50;
                            oTable.Cell(1, 7).Width = 270;*/
                            oTable.Cell(1, 6).Split(2, 1);
                            oTable.Cell(2, 6).Split(1, 5);
                            for (int i = 7; i < 12; i++)
                            {
                                wordCellRange = oTable.Cell(2, i - 1).Range;
                                wordCellRange.ParagraphFormat.Alignment = Word.WdParagraphAlignment.wdAlignParagraphCenter;
                                wordCellRange.Text = nameHeaderTable[i];
                                wordCellRange.Rows.HeadingFormat = -1;
                            }
                            //MessageBox.Show("Ds");
                            label1.BeginInvoke((MethodInvoker)(() => this.label1.Text = "Информация: печать"));
                            wordDocument.Range(0, wordDocument.Range().End).Copy();

                            //Вывод
                            int posRows = 3;
                            List<string> oglavlenie = new List<string>();   //Список для создания заводов оглавления
                            List<string> oglavlenieStr = new List<string>();
                            string lastCodeZavod = "";                      //Код последнего завода, зафиксированного в оглавлении
                            Random rand = new Random();
                            for (int i = 0; i < 40; i++)
                            {
                                //MessageBox.Show("asd");
                                oTable.Rows.Add();
                                //Порядковый номер
                                wordCellRange = oTable.Cell(posRows, 1).Range;
                                wordCellRange.Rows.HeadingFormat = 0;
                                wordCellRange.Text = Convert.ToString(posRows - 1);
                                //Шифр, наименование элемента
                                wordCellRange = oTable.Cell(posRows, 2).Range;
                                wordCellRange.Rows.HeadingFormat = 0;
                                wordCellRange.ParagraphFormat.Alignment = Word.WdParagraphAlignment.wdAlignParagraphLeft;
                                //Шифр, наименование завода
                                wordCellRange = oTable.Cell(posRows, 3).Range;
                                wordCellRange.Rows.HeadingFormat = 0;
                                //Шифр, наименование вида испытания
                                wordCellRange = oTable.Cell(posRows, 4).Range;
                                wordCellRange.Rows.HeadingFormat = 0;
                                //Номер партии
                                wordCellRange = oTable.Cell(posRows, 5).Range;
                                wordCellRange.Rows.HeadingFormat = 0;
                                //Количество выстрелов
                                wordCellRange = oTable.Cell(posRows, 6).Range;
                                wordCellRange.Rows.HeadingFormat = 0;
                                int k = rand.Next(0, 10);
                                wordCellRange.Text = Convert.ToString(k);
                                wordDocument.TablesOfAuthorities.MarkCitation(wordCellRange, k.ToString(), k.ToString(), Type.Missing, "1");
                                //Планируемая дата поставки
                                wordCellRange = oTable.Cell(posRows, 7).Range;
                                wordCellRange.Rows.HeadingFormat = 0;
                                k = rand.Next(0, 10);
                                wordCellRange.Text = Convert.ToString(k);

                                //Мишенная обстановка
                                string nameMishen = "";
                                wordCellRange = oTable.Cell(posRows, 8).Range;
                                wordCellRange.Rows.HeadingFormat = 0;
                                wordCellRange.Text = "\n" + nameMishen;
                                //Примечание

                                //Для оглавления
                                //if (isp.nzav != lastCodeZavod)
                                {
                                    oglavlenie.Add(k.ToString().Replace("\n", "").Trim());
                                    oglavlenieStr.Add(wordDocument.ComputeStatistics(Word.WdStatistic.wdStatisticPages, false).ToString());
                                }
                                posRows++;
                            }

                            //Создание нижней подписи
                            range = wordDocument.Paragraphs[wordDocument.Paragraphs.Count].Range;
                            wordDocument.Paragraphs.Add();
                            range = wordDocument.Paragraphs[wordDocument.Paragraphs.Count].Range;
                            range.Text = "Начальник сборочно-испытательного производства - зам. главного инженера\t\tА.И. Кочуров";
                            range = wordDocument.Paragraphs[wordDocument.Paragraphs.Count].Range;
                            wordDocument.Paragraphs.Add();
                            range = wordDocument.Paragraphs[wordDocument.Paragraphs.Count].Range;
                            range = wordDocument.Paragraphs[wordDocument.Paragraphs.Count].Range;
                            //MessageBox.Show("dsa");
                            range.InsertBreak(Word.WdBreakType.wdPageBreak);
                            //range.InsertBreak();
                            //MessageBox.Show("sssssssa");
                            wordDocument.Paragraphs.Add();
                            range = wordDocument.Paragraphs[wordDocument.Paragraphs.Count].Range;
                            //MessageBox.Show("123412341dsa");
                            range.Paste();
                            //MessageBox.Show("zxcvasd45fasd4f65");

                            //Оглавление
                            /*wordDocument.Paragraphs.Add();
                            range = wordDocument.Paragraphs[wordDocument.Paragraphs.Count].Range;
                            range.InsertBreak();
                            //range.InsertBreak(Word.WdBreakType.wdSectionBreakEvenPage);
                            range = wordDocument.Paragraphs[wordDocument.Paragraphs.Count].Range;
                            wordDocument.Paragraphs.Add();
                            range = wordDocument.Paragraphs[wordDocument.Paragraphs.Count - 1].Range;
                            //Установка колонок
                            //range.PageSetup.TextColumns.SetCount(2);
                            wordDocument.Tables.Add(range, 1, 7, autoFitBehavior);
                            Word.Table oTableOglavlenie = wordDocument.Tables[5];
                            //Запрет на перенос строк
                            oTableOglavlenie.Rows.AllowBreakAcrossPages = 0;
                            string[] nameHeaderTable2 = { "№ п/п", "Предприятие", "Стр.", "", "№ п/п", "Предприятие", "Стр." };
                            for (int i = 0; i < oTableOglavlenie.Columns.Count; i++)
                            {
                                wordCellRange = oTableOglavlenie.Cell(1, i + 1).Range;
                                wordCellRange.ParagraphFormat.Alignment = Word.WdParagraphAlignment.wdAlignParagraphCenter;
                                wordCellRange.Text = nameHeaderTable2[i];
                                wordCellRange.Font.Name = "Times New Roman";
                            }
                            oTableOglavlenie.Columns[1].Width = 20;
                            oTableOglavlenie.Columns[2].Width = 300;
                            oTableOglavlenie.Columns[3].Width = 20;
                            oTableOglavlenie.Columns[4].Width = 70;
                            oTableOglavlenie.Columns[5].Width = 20;
                            oTableOglavlenie.Columns[6].Width = 300;
                            oTableOglavlenie.Columns[7].Width = 20;
                            //Повтор заголовка
                            oTableOglavlenie.Rows.First.HeadingFormat = -1;
                            //Вертикальное центрирование заголовка
                            oTableOglavlenie.Rows[1].Cells.VerticalAlignment = Word.WdCellVerticalAlignment.wdCellAlignVerticalCenter;
                            //Вывод оглавления
                            int posOglavlenieRows = 2;
                            for (int i = 0; i < oglavlenie.Count; i++)
                            {
                                oTableOglavlenie.Rows.Add();
                                oTable.Rows[posOglavlenieRows-1].HeadingFormat = 0;
                                oTableOglavlenie.Rows.Add();
                                oTable.Rows[posOglavlenieRows].HeadingFormat = 0;
                                wordCellRange = oTableOglavlenie.Cell(posOglavlenieRows, 1).Range;
                                wordCellRange.Text = Convert.ToString(i + 1);
                                wordCellRange = oTableOglavlenie.Cell(posOglavlenieRows, 2).Range;
                                wordCellRange.Text = oglavlenie[i];
                                wordCellRange = oTableOglavlenie.Cell(posOglavlenieRows, 3).Range;
                                wordCellRange.Text = oglavlenieStr[i];
                                posOglavlenieRows++;
                                posOglavlenieRows++;
                            }
                            oTableOglavlenie.Columns.Borders[Word.WdBorderType.wdBorderTop].LineStyle = Word.WdLineStyle.wdLineStyleNone;
                            oTableOglavlenie.Columns.Borders[Word.WdBorderType.wdBorderLeft].LineStyle = Word.WdLineStyle.wdLineStyleNone;
                            oTableOglavlenie.Columns.Borders[Word.WdBorderType.wdBorderBottom].LineStyle = Word.WdLineStyle.wdLineStyleNone;
                            oTableOglavlenie.Columns.Borders[Word.WdBorderType.wdBorderRight].LineStyle = Word.WdLineStyle.wdLineStyleNone;
                            oTableOglavlenie.Columns.Borders[Word.WdBorderType.wdBorderHorizontal].LineStyle = Word.WdLineStyle.wdLineStyleNone;
                            oTableOglavlenie.Columns.Borders[Word.WdBorderType.wdBorderVertical].LineStyle = Word.WdLineStyle.wdLineStyleNone;*/

                            //Оглавление через ссылки
                            //Для всего документа
                            /*for (int i = 0; i < 1000; i++)
                                wordDocument.TablesOfAuthorities.MarkAllCitations(i.ToString(), i.ToString(), Type.Missing, "1"); //Для всего документа*/

                            //Для одного столбца (перебираем столбец и делаем маркировку
                            //for (int i = 3; i < 43; i++)
                                //for (int j = 0; j < 10; j++)
                                    //wordDocument.TablesOfAuthorities.MarkCitation(oTable.Cell(i, 6).Range, j.ToString(), j.ToString(), true, "1");
                            wordDocument.Paragraphs.Add();
                            wordDocument.Paragraphs.Add();
                            range = wordDocument.Paragraphs[wordDocument.Paragraphs.Count - 1].Range;
                            range.Text = "Индекс ИЭ";
                            wordDocument.Paragraphs.Add();
                            wordDocument.Paragraphs.Add();
                            range = wordDocument.Paragraphs[wordDocument.Paragraphs.Count - 1].Range;
                            wordDocument.TablesOfAuthorities.Add(range, "1", Passim: false, KeepEntryFormatting: true, IncludeCategoryHeader:false);
                            wordDocument.TablesOfAuthorities[1].KeepEntryFormatting = true;
                            wordDocument.TablesOfAuthorities[1].Passim = false;
                            wordDocument.TablesOfAuthorities[1].TabLeader = Word.WdTabLeader.wdTabLeaderDots;

                            //Оглавление через предметный указатель
                            /*for (int i = 0; i < 1000; i++)
                                wordDocument.Indexes.MarkAllEntries(wordDocument.Range(), i.ToString(), i.ToString());
                            wordDocument.Paragraphs.Add();
                            range = wordDocument.Paragraphs[wordDocument.Paragraphs.Count - 1].Range;
                            wordDocument.Indexes.Add(range, RightAlignPageNumbers: true, NumberOfColumns: "1");*/

                            /*wordDocument.Paragraphs.Add();
                            wordDocument.Paragraphs.Add();
                            wordDocument.Paragraphs.Add();
                            autoFitBehavior = Word.WdAutoFitBehavior.wdAutoFitWindow;
                            range = wordDocument.Paragraphs[wordDocument.Paragraphs.Count - 1].Range;
                            wordDocument.Tables.Add(range, 1, 9, autoFitBehavior);
                            oTable = wordDocument.Tables[6];
                            //Запрет на перенос строк
                            oTable.Rows.AllowBreakAcrossPages = 0;
                            string[] nameHeaderTable3 = { "№\nп/п",
                                               "Код и наименование\nиспытуемого\nэлемента",
                                               "Код и наиме-\nнование вида\nиспытания",
                                               "Код и наименование\nматчасти",
                                               "Номер\nпартии",
                                               "Число\nвыстрелов\nот партии",
                                               "Дата\nпоставки\nна полигон",
                                               "Номер\nзаказа\nна заводе",
                                               "№, Дата ГОСЗАКАЗА\nПРИМЕЧАНИЕ"
                                           };
                            for (int i = 0; i < oTable.Columns.Count; i++)
                            {
                                wordCellRange = oTable.Cell(1, i + 1).Range;
                                wordCellRange.ParagraphFormat.Alignment = Word.WdParagraphAlignment.wdAlignParagraphCenter;
                                wordCellRange.Text = nameHeaderTable3[i];
                                wordCellRange.Font.Name = "Times New Roman";
                            }
                            posRows = 2;
                            //Повтор заголовка
                            oTable.Rows.First.HeadingFormat = -1;
                            //Вертикальное центрирование заголовка
                            oTable.Rows[1].Cells.VerticalAlignment = Word.WdCellVerticalAlignment.wdCellAlignVerticalCenter;
                            //Изменяем размеры колонок
                            oTable.Cell(1, 1).Width = 50;
                            oTable.Cell(1, 2).Width = 120;
                            oTable.Cell(1, 3).Width = 78;
                            oTable.Cell(1, 4).Width = 85;
                            oTable.Cell(1, 5).Width = 77;
                            oTable.Cell(1, 6).Width = 74;
                            oTable.Cell(1, 7).Width = 86;
                            oTable.Cell(1, 8).Width = 86;
                            oTable.Cell(1, 9).Width = 90;

                            //Создание экземпляров
                            wordDocument.Range().Copy();
                            var rangeAllDocumentEkz = wordDocument.Content;
                            rangeAllDocumentEkz.Find.ClearFormatting();
                            //for (int j = 0; j < planIspList.Count; j++)
                            {
                                functionReplaceInText(wordDocument, oWord, "{numberEkz}", "1");
                            }*/
                            //Очистка буфера
                            Clipboard.Clear();
                            //Сохранение
                            DateTime dateNowFileName = DateTime.Now;
                            string strSaveName = Convert.ToString(dateNowFileName);
                            strSaveName = strSaveName.Replace(':', '-');
                            if (!Directory.Exists(Properties.Settings.Default.PathForReports))
                                Directory.CreateDirectory(Directory.GetCurrentDirectory() + "/reports");

                            //MessageBox.Show(Properties.Settings.Default.PathForReports + "/Месячный план испытаний (НС)" + " " + strSaveName + ".docx");
                            wordDocument.SaveAs(Properties.Settings.Default.PathForReports + "/Месячный план испытаний (НС)" + " " + strSaveName + ".docx");
                            //wordDocument.SaveAs(Properties.Settings.Default.PathForN + "/Месячный план испытаний (НС)" + " " + strSaveName + ".docx");
                            //wordDocument.SaveAs(Properties.Settings.Default.PathForS + "/Месячный план испытаний" + " " + strSaveName + ".docx");
                            wordDocument.Close();
                            oWord.Quit();
                            MessageBox.Show("Месячный план (НС) создан!");
                            label1.BeginInvoke((MethodInvoker)(() => this.label1.Text = "Информация: документ создан!"));
                        }
                        catch (Exception ex)
                        {
                            MessageBox.Show("error: " + ex.Message);
                            label1.BeginInvoke((MethodInvoker)(() => this.label1.Text = "Информация: ошибка печати!"));
                            oWord.Visible = true;
                        }
                    }
                    catch (Exception ex)
                    {
                        MessageBox.Show("error: " + ex.Message);
                        label1.BeginInvoke((MethodInvoker)(() => this.label1.Text = "Информация: ошибка печати!"));
                    }
                });
                thread.SetApartmentState(ApartmentState.STA);
                thread.Start();
            }
            catch
            {

            }
            finally
            {
                BlockAllButton(false);
            }
        }

        private void button19_Click(object sender, EventArgs e)
        {
            BlockAllButton();
            if (Properties.Settings.Default.OtraslMesplPath.Length > 0)
            {
                if (Properties.Settings.Default.OtraslPath.Length > 0)
                {
                    if (Properties.Settings.Default.RashGodFolderPath.Length > 0)
                    {
                        try
                        {
                            FormChoseMespl fcm = new FormChoseMespl();
                            fcm.ShowDialog();
                            if (fcm.isExit)
                            {
                                label1.BeginInvoke((MethodInvoker)(() => this.label1.Text = "Информация: подготовка к печати"));
                                Thread thread = new Thread(() =>
                                {
                                    try
                                    {
                                        List<MesplPlanIsp> planIspList = new List<MesplPlanIsp>();
                                        //Получение плана испытаний
                                        planIspList = GetMesplPlanIsp(planIspList, fcm.mesac, fcm.godr);
                                        //Заполнение плана испытанйи
                                        planIspList = FillMesplPlanIsp(planIspList);
                                        //Печать сводного плана испытаний
                                        string resultSaveFile = "<html><head><meta charset='utf-8'/></head><body><table>";
                                        resultSaveFile += "<thead><tr>";
                                        string[] nameHeaderTable = { "№\nп/п", "Код и наименование\nиспытуемого\nэлемента", " Код и\nнаименование\nзавода-\nизготовителя", "Код и\nнаименование\nвида испытания", "Номер\nпартии", "Количество\nвыстрелов", "Планируемая\nдата\nпоставки", "Мишенная\nобстановка", "№, Дата\nГОСЗАКАЗА/\nПримечание" };
                                        for (int i = 0; i < nameHeaderTable.Length; i++)
                                        {
                                            resultSaveFile += "<th>" + nameHeaderTable[i] + "</th>";
                                        }
                                        resultSaveFile += "</tr></thead><tbody>";

                                        int posRows = 1;
                                        foreach (MesplPlanIsp isp in planIspList)
                                        {
                                            resultSaveFile += "<tr>";
                                            //Порядковый номер
                                            resultSaveFile += "<td>" + Convert.ToString(posRows) + "</td>";
                                            //Шифр, наименование элемента
                                            resultSaveFile += "<td>" + Convert.ToString(isp.kodel) + "\n\n" + isp.snhert + "\n" + isp.snindiz + "\n" + isp.snnaim + "</td>";
                                            //Шифр, наименование завода
                                            resultSaveFile += "<td>" + Convert.ToString(isp.kodotr) + "\n\n\n\n" + isp.nzav + "</td>";
                                            //Шифр, наименование вида испытания
                                            resultSaveFile += "<td>" + Convert.ToString(isp.shvid) + "\n\n\n\n" + isp.nvid + "</td>";
                                            //Номер партии
                                            resultSaveFile += "<td>" + Convert.ToString(isp.npart) + "</td>";
                                            //Количество выстрелов
                                            resultSaveFile += "<td>" + Convert.ToString(isp.vist) + "</td>";
                                            //Планируемая дата поставки
                                            resultSaveFile += "<td>" + Convert.ToString(isp.dpost) + "</td>";
                                            //Мишенная обстановка
                                            string nameMishen = "";
                                            if (isp.npreg1 != "")
                                            {
                                                nameMishen += isp.npreg1 + "\n";
                                            }
                                            if (isp.npreg2 != "")
                                            {
                                                nameMishen += isp.npreg2;
                                            }
                                            resultSaveFile += "<td>" + "\n" + nameMishen + "</td>";
                                            //Примечание
                                            resultSaveFile += "<td>" + Convert.ToString(isp.prim) + "</td>";
                                            resultSaveFile += "</tr>";
                                            posRows++;
                                        }

                                        resultSaveFile += "</tbody>";
                                        resultSaveFile += "</table></body></html>";
                                        using (StreamWriter sw = new StreamWriter(Directory.GetCurrentDirectory() + "/reports/Месячный план испытаний.html", false, Encoding.UTF8))
                                        {
                                            sw.Write(resultSaveFile);
                                        }
                                    }
                                    catch (Exception ex)
                                    {
                                        MessageBox.Show("Очередная ошибка ин поток\n" + ex.Message + "\n" + ex.Source + "\n" + ex.StackTrace + "\n" + ex.TargetSite, "Ошибка", MessageBoxButtons.OK, MessageBoxIcon.Error);
                                    }
                                    finally
                                    {
                                        BlockAllButton(false);
                                    }
                                });
                                thread.SetApartmentState(ApartmentState.STA);
                                thread.Start();
                            }
                        }
                        catch (Exception ex)
                        {
                            BlockAllButton(false);
                            MessageBox.Show("Очередная ошибка\n" + ex.Message + "\n" + ex.Source + "\n" + ex.StackTrace + "\n" + ex.TargetSite, "Ошибка", MessageBoxButtons.OK, MessageBoxIcon.Error);
                        }
                    }
                    else
                        MessageBox.Show("Выберите поддиректорию RAHGOD");
                }
                else
                    MessageBox.Show("Выберите директорию OTRASL");
            }
            else
                MessageBox.Show("Выберите директорию OTRASL1");
        }
    }
}