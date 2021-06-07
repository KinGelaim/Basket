using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Windows.Forms;
using System.Data.SqlClient;
using System.IO;
using Excel = Microsoft.Office.Interop.Excel;
using System.Data.SQLite;

namespace Analysis_Readiness
{
    public partial class Form1 : Form
    {
        SQLiteConnection sqliteConnection;
        SQLiteDataReader sqliteReader = null;

        List<ClassSpravka> spravkaList = new List<ClassSpravka>();
        List<ClassPotr> potrList = new List<ClassPotr>();
        List<ClassPotr> allPotrList = new List<ClassPotr>();

        public int positionInSpravks;
        public int positionInPotr;

        string bdDirectory = "";

        public Form1()
        {
            InitializeComponent();

            panel1.Visible = true;
            panel2.Visible = false;
            panel3.Visible = false;
            panel4.Visible = false;
            panel5.Visible = false;

            if (Properties.Settings.Default.mainDirectory.Length > 0)
                bdDirectory = Properties.Settings.Default.mainDirectory + @"\BD\analysis_readliness_bd.db";
            else
                bdDirectory = System.IO.Directory.GetCurrentDirectory();
        }

        //---------------------ВЕРХНЯЯ ПАНЕЛЬ---------------------
        //О программе
        private void aboutTheProgrammToolStripMenuItem_Click(object sender, EventArgs e)
        {

        }

        //Открытие корневой дирректории
        private void openToolStripMenuItem_Click(object sender, EventArgs e)
        {
            System.Diagnostics.Process.Start("explorer", Properties.Settings.Default.mainDirectory);
        }

        //Настройки
        private void settingsToolStripMenuItem_Click(object sender, EventArgs e)
        {
            folderBrowserDialog1.ShowDialog();
            if (folderBrowserDialog1.SelectedPath.Length > 0)
            {
                Properties.Settings.Default.mainDirectory = folderBrowserDialog1.SelectedPath;
                Properties.Settings.Default.Save();
                bdDirectory = Properties.Settings.Default.mainDirectory + @"\BD\analysis_readliness_bd.db";
            }
            folderBrowserDialog1.SelectedPath = "";
        }

        //Выход
        private void exitToolStripMenuItem_Click(object sender, EventArgs e)
        {
            Application.Exit();
        }

        //---------------------СПРАВКА-ОБОСНОВАНИЕ---------------------
        private void button1_Click(object sender, EventArgs e)
        {
            panel1.Visible = false;
            panel2.Visible = true;
            load_spravks();
            show_spravks(spravkaList);
            potrList.Clear();
        }

        private void listBox1_DoubleClick(object sender, EventArgs e)
        {
            if (listBox1.SelectedIndex >= 0)
            {
                panel2.Visible = false;
                panel3.Visible = true;
                positionInSpravks = listBox1.SelectedIndex;
                show_spravk();
            }
        }

        //Добавить справку-обоснование
        private void button4_Click(object sender, EventArgs e)
        {
            panel2.Visible = false;
            panel3.Visible = true;
            show_potr(potrList);
        }

        //Добавить комплектующий элемент
        private void button5_Click(object sender, EventArgs e)
        {
            if(textBox4.Text.Length > 0 && textBox5.Text.Length > 0)
            {
                potrList.Add(new ClassPotr(textBox4.Text, textBox5.Text, Convert.ToInt32(textBox6.Text)));
                textBox4.Text = "";
                textBox5.Text = "";
                textBox6.Text = "";
                show_potr(potrList);
            }
        }

        //Отображение списка комплектующих
        private void show_potr(List<ClassPotr> inPotrList)
        {
            listBox2.Items.Clear();
            if (inPotrList.Count > 0)
                foreach (ClassPotr potr in inPotrList)
                    listBox2.Items.Add("Код: " + potr.code_potr + " вид: " + potr.vid_potr + " кол-во: " + potr.count_potr);
        }

        //Сохранение справки-обоснование
        private void button6_Click(object sender, EventArgs e)
        {
            if(textBox1.Text.Length > 0 && textBox2.Text.Length > 0 && textBox3.Text.Length > 0 && potrList.Count > 0)
            {
                string currentSpravkaID = "";
                sqliteConnection =
                    new SQLiteConnection(string.Format("Data Source={0};", bdDirectory));
                SQLiteCommand command = new SQLiteCommand("INSERT INTO Spravka (code_spravka,vid_spravka,description_spravka) VALUES (@code_spravka,@vid_spravka,@description_spravka)", sqliteConnection);
                sqliteConnection.Open();
                command.Parameters.AddWithValue("code_spravka", textBox1.Text);
                command.Parameters.AddWithValue("vid_spravka", textBox2.Text);
                command.Parameters.AddWithValue("description_spravka", textBox3.Text);
                command.ExecuteNonQuery();


                SQLiteCommand command2 = new SQLiteCommand("SELECT MAX(Spravka.id) as maxID FROM [Spravka]", sqliteConnection);
                sqliteReader = command2.ExecuteReader();
                while (sqliteReader.Read())
                {
                    currentSpravkaID = Convert.ToString(sqliteReader["maxID"]);
                }
                sqliteReader.Close();

                foreach (ClassPotr potr in potrList)
                {
                    MessageBox.Show(currentSpravkaID);
                    command = new SQLiteCommand("INSERT INTO Potr (id_spravka,code_potr,vid_potr,count_potr,description_potr, is_sogl, is_niisu, is_prikaz) VALUES (@id_spravka,@code_potr,@vid_potr,@count_potr,@description_potr, @is_sogl, @is_niisu, @is_prikaz)", sqliteConnection);
                    command.Parameters.AddWithValue("id_spravka",currentSpravkaID);
                    command.Parameters.AddWithValue("code_potr", potr.code_potr);
                    command.Parameters.AddWithValue("vid_potr", potr.vid_potr);
                    command.Parameters.AddWithValue("count_potr", potr.count_potr);
                    command.Parameters.AddWithValue("description_potr", potr.description_potr);
                    command.Parameters.AddWithValue("is_sogl", "False");
                    command.Parameters.AddWithValue("is_niisu", "False");
                    command.Parameters.AddWithValue("is_prikaz", "False");
                    command.ExecuteNonQuery();
                }

                sqliteConnection.Close();

                textBox1.Text = "";
                textBox2.Text = "";
                textBox3.Text = "";
                potrList.Clear();
                panel3.Visible = false;
                panel2.Visible = true;
                load_spravks();
                show_spravks(spravkaList);
            }
        }

        //Отображение одной справки-обоснование
        private void show_spravk()
        {
            textBox1.Text = spravkaList[positionInSpravks].code_spravka;
            textBox2.Text = spravkaList[positionInSpravks].vid_spravka;
            textBox3.Text = spravkaList[positionInSpravks].description_spravka;
            button5.Visible = false;
            button6.Visible = false;
            show_potr(spravkaList[positionInSpravks].listPotrInSpravka);
        }

        //Отображение списка справок-обоснование
        private void show_spravks(List<ClassSpravka> inSpravkaList)
        {
            listBox1.Items.Clear();
            if (inSpravkaList.Count > 0)
                foreach (ClassSpravka spravka in inSpravkaList)
                    listBox1.Items.Add("Код: " + spravka.code_spravka + " вид: " + spravka.vid_spravka);
        }

        //Загрузка справок-обоснование
        private void load_spravks()
        {
            try
            {
                spravkaList.Clear();
                SQLiteConnection sqliteConnection = new SQLiteConnection(string.Format("Data Source={0};", bdDirectory));
                sqliteConnection.Open();
                SQLiteDataReader sqliteReader = null;
                SQLiteCommand command = new SQLiteCommand("SELECT * FROM Spravka", sqliteConnection);
                sqliteReader = command.ExecuteReader();
                while (sqliteReader.Read())
                {
                    ClassSpravka spravka = new ClassSpravka(Convert.ToInt32(sqliteReader["id"]), Convert.ToString(sqliteReader["code_spravka"]), Convert.ToString(sqliteReader["vid_spravka"]), Convert.ToString(sqliteReader["description_spravka"]));
                    spravkaList.Add(spravka);
                }
                sqliteReader.Close();

                sqliteReader = null;
                foreach (ClassSpravka spravka in spravkaList)
                {
                    sqliteReader = null;
                    command = new SQLiteCommand("SELECT * FROM Potr WHERE id_spravka=@id_spravka", sqliteConnection);
                    command.Parameters.AddWithValue("id_spravka", spravka.id);
                    sqliteReader = command.ExecuteReader();
                    List<ClassPotr> prListPotr = new List<ClassPotr>();
                    while (sqliteReader.Read())
                    {
                        prListPotr.Add(new ClassPotr(Convert.ToInt32(sqliteReader["id"]), Convert.ToInt32(sqliteReader["id_spravka"]), Convert.ToString(sqliteReader["code_potr"]),
                            Convert.ToString(sqliteReader["vid_potr"]), Convert.ToInt32(sqliteReader["count_potr"]), Convert.ToString(sqliteReader["description_potr"]),
                            Convert.ToBoolean(sqliteReader["is_sogl"]), Convert.ToString(sqliteReader["text_sogl"]), Convert.ToBoolean(sqliteReader["is_niisu"]), Convert.ToString(sqliteReader["text_niisu"]),
                            Convert.ToBoolean(sqliteReader["is_prikaz"]), Convert.ToString(sqliteReader["text_prikaz"])));
                    }
                    sqliteReader.Close();
                    spravka.listPotrInSpravka = prListPotr;
                }
                sqliteConnection.Close();
            }
            catch (Exception e)
            {
                MessageBox.Show(e.Message);
            }
        }

        //Кнопка назад из списка справок
        private void button9_Click(object sender, EventArgs e)
        {
            panel2.Visible = false;
            panel1.Visible = true;
        }

        //Кнопка назад из справки-обоснование
        private void button10_Click(object sender, EventArgs e)
        {
            panel3.Visible = false;
            panel2.Visible = true;
        }

        //---------------------КОМПЛЕКТУЮЩИЕ---------------------
        private void button2_Click(object sender, EventArgs e)
        {
            panel1.Visible = false;
            panel5.Visible = true;
            load_all_potr();
            show_all_potr();
        }

        //Выбор комплектующего в справке-обоснования
        private void listBox2_DoubleClick(object sender, EventArgs e)
        {
            if (listBox2.SelectedIndex >= 0)
            {
                panel3.Visible = false;
                panel4.Visible = true;
                positionInPotr = listBox2.SelectedIndex;
                show_potr();
            }
        }

        //Выбор комплектующего
        private void listBox3_DoubleClick(object sender, EventArgs e)
        {
            if (listBox3.SelectedIndex >= 0)
            {
                positionInSpravks = allPotrList[listBox3.SelectedIndex].positionInSpravka;
                positionInPotr = allPotrList[listBox3.SelectedIndex].positionInPotr;
                panel5.Visible = false;
                panel4.Visible = true;
                show_potr();
            }
        }

        //Отображение одного комплектующего
        private void show_potr()
        {
            textBox13.Text = spravkaList[positionInSpravks].listPotrInSpravka[positionInPotr].id.ToString();
            textBox7.Text = spravkaList[positionInSpravks].listPotrInSpravka[positionInPotr].code_potr;
            textBox8.Text = spravkaList[positionInSpravks].listPotrInSpravka[positionInPotr].vid_potr;
            textBox9.Text = spravkaList[positionInSpravks].listPotrInSpravka[positionInPotr].count_potr.ToString();
            checkBox1.Checked = spravkaList[positionInSpravks].listPotrInSpravka[positionInPotr].is_sogl;
            checkBox2.Checked = spravkaList[positionInSpravks].listPotrInSpravka[positionInPotr].is_niisu;
            checkBox3.Checked = spravkaList[positionInSpravks].listPotrInSpravka[positionInPotr].is_prikaz;
            textBox10.Text = spravkaList[positionInSpravks].listPotrInSpravka[positionInPotr].text_sogl;
            textBox11.Text = spravkaList[positionInSpravks].listPotrInSpravka[positionInPotr].text_niisu;
            textBox12.Text = spravkaList[positionInSpravks].listPotrInSpravka[positionInPotr].text_prikaz;
        }

        //Сохранение комплектующего
        private void button7_Click(object sender, EventArgs e)
        {
            sqliteConnection =
                    new SQLiteConnection(string.Format("Data Source={0};", bdDirectory));
            sqliteConnection.Open();
            SQLiteCommand command = new SQLiteCommand("UPDATE Potr SET [is_sogl]=@is_sogl, [text_sogl]=@text_sogl, [is_niisu]=@is_niisu, [text_niisu]=@text_niisu, [is_prikaz]=@is_prikaz, [text_prikaz]=@text_prikaz WHERE [id]=@id", sqliteConnection);
            command.Parameters.AddWithValue("id", textBox13.Text);
            command.Parameters.AddWithValue("is_sogl", checkBox1.Checked);
            command.Parameters.AddWithValue("is_niisu", checkBox2.Checked);
            command.Parameters.AddWithValue("is_prikaz", checkBox3.Checked);
            command.Parameters.AddWithValue("text_sogl", textBox10.Text);
            command.Parameters.AddWithValue("text_niisu", textBox11.Text);
            command.Parameters.AddWithValue("text_prikaz", textBox12.Text);
            command.ExecuteNonQuery();
            panel4.Visible = false;
            panel2.Visible = true;
            load_spravks();
            show_spravks(spravkaList);
            sqliteConnection.Close();
        }

        //Загрузка всех потребностей в комплектующих
        private void load_all_potr()
        {
            load_spravks();
            allPotrList.Clear();
            int k = 0;
            foreach (ClassSpravka spravka in spravkaList)
            {
                int l = 0;
                foreach (ClassPotr potr in spravka.listPotrInSpravka)
                {
                    potr.positionInSpravka = k;
                    potr.positionInPotr = l;
                    allPotrList.Add(potr);
                    l++;
                }
                k++;
            }
        }

        //Отображение всех комплектующих
        private void show_all_potr()
        {
            listBox3.Items.Clear();
            foreach(ClassPotr potr in allPotrList)
                listBox3.Items.Add("Код: " + potr.code_potr + " вид: " + potr.vid_potr + " кол-во: " + potr.count_potr);
        }

        //Поиск необходимого комплектующего
        private void button8_Click(object sender, EventArgs e)
        {
            FormSearchPotr formSearch = new FormSearchPotr(allPotrList);
            formSearch.ShowDialog();
            if(formSearch.is_exit)
            {
                positionInSpravks = formSearch.searchPotr.positionInSpravka;
                positionInPotr = formSearch.searchPotr.positionInPotr;
                panel5.Visible = false;
                panel4.Visible = true;
                show_potr();
            }
        }

        //Кнопка назад из комплектующего
        private void button11_Click(object sender, EventArgs e)
        {
            panel4.Visible = false;
            panel3.Visible = true;
        }

        //Кнопка назад из общего списка комплектующих
        private void button12_Click(object sender, EventArgs e)
        {
            panel5.Visible = false;
            panel1.Visible = true;
        }

        //---------------------ПЕЧАТЬ---------------------
        private void button3_Click(object sender, EventArgs e)
        {
            //Проверка на наличие директории
            if (Directory.Exists(Properties.Settings.Default.mainDirectory + @"\Отчеты"))
            {
                load_spravks();
                bool printProverka = true;
                //Создание эксель документа
                Excel.Application oExcel = new Excel.Application();
                oExcel.Visible = false;
                try
                {
                    oExcel.SheetsInNewWorkbook = 1;
                    oExcel.Workbooks.Add();
                    Excel.Workbooks excelWorkBooks = oExcel.Workbooks;
                    Excel.Workbook excelWorkBook = excelWorkBooks[1];
                    excelWorkBook.Saved = false;
                    Excel.Sheets excelSheets = oExcel.Worksheets;
                    Excel.Worksheet excelWorkSheets = excelSheets.get_Item(1);

                    //Заполнения даннами
                    Excel.Range excelCells;
                    int positionInRows = 1;
                    excelCells = excelWorkSheets.get_Range("A" + positionInRows);
                    excelCells.Value2 = "Код испытания";
                    excelCells = excelWorkSheets.get_Range("B" + positionInRows);
                    excelCells.Value2 = "Вид испытания";
                    excelCells = excelWorkSheets.get_Range("C" + positionInRows);
                    excelCells.Value2 = "Описание";
                    excelCells = excelWorkSheets.get_Range("D" + positionInRows);
                    excelCells.Value2 = "Код КЭ";
                    excelCells = excelWorkSheets.get_Range("E" + positionInRows);
                    excelCells.Value2 = "Вид КЭ";
                    excelCells = excelWorkSheets.get_Range("F" + positionInRows);
                    excelCells.Value2 = "Количество";
                    excelCells = excelWorkSheets.get_Range("G" + positionInRows);
                    excelCells.Value2 = "Описание";
                    excelCells = excelWorkSheets.get_Range("H" + positionInRows);
                    excelCells.Value2 = "Текст согласования";
                    excelCells = excelWorkSheets.get_Range("I" + positionInRows);
                    excelCells.Value2 = "Текст НИИСУ";
                    excelCells = excelWorkSheets.get_Range("K" + positionInRows);
                    excelCells.Value2 = "Текст приказа";
                    positionInRows++;
                    foreach(ClassSpravka spravka in spravkaList)
                    {
                        excelCells = excelWorkSheets.get_Range("A" + positionInRows);
                        excelCells.Value2 = spravka.code_spravka;
                        excelCells = excelWorkSheets.get_Range("B" + positionInRows);
                        excelCells.Value2 = spravka.vid_spravka;
                        excelCells = excelWorkSheets.get_Range("C" + positionInRows);
                        excelCells.Value2 = spravka.description_spravka;
                        foreach(ClassPotr potr in spravka.listPotrInSpravka)
                        {
                            excelCells = excelWorkSheets.get_Range("D" + positionInRows);
                            excelCells.Value2 = potr.code_potr;
                            excelCells = excelWorkSheets.get_Range("E" + positionInRows);
                            excelCells.Value2 = potr.vid_potr;
                            excelCells = excelWorkSheets.get_Range("F" + positionInRows);
                            excelCells.Value2 = potr.count_potr;
                            excelCells = excelWorkSheets.get_Range("G" + positionInRows);
                            excelCells.Value2 = potr.description_potr;
                            excelCells = excelWorkSheets.get_Range("H" + positionInRows);
                            excelCells.Value2 = potr.text_sogl;
                            excelCells = excelWorkSheets.get_Range("I" + positionInRows);
                            excelCells.Value2 = potr.text_niisu;
                            excelCells = excelWorkSheets.get_Range("K" + positionInRows);
                            excelCells.Value2 = potr.text_prikaz;
                            positionInRows++;
                        }
                        positionInRows++;
                    }

                    //Создание отчета
                    string strSaveName = Convert.ToString(DateTime.Now);
                    strSaveName = strSaveName.Replace(':', '-');
                    excelWorkBook.SaveAs(Properties.Settings.Default.mainDirectory + @"\Отчеты\" + strSaveName + ".xlsx");
                    excelWorkBook.Close();
                }
                catch
                {
                    MessageBox.Show("Ошибка!");
                    printProverka = false;
                }
                finally
                {
                    oExcel.Quit();
                    if (printProverka)
                        MessageBox.Show("Отчет сформирован!", "Информация", MessageBoxButtons.OK, MessageBoxIcon.Information);
                }
            }
            else
            {
                //Желаете создать директорию для сохранения отчетов?
                if (DialogResult.Yes == MessageBox.Show("Не найдена директория для создания отчетов!\nЖелаете ее создать?", "Подтверждение", MessageBoxButtons.YesNo, MessageBoxIcon.Question))
                    Directory.CreateDirectory("Отчеты");
            }
        }

        //---------------------Доп. функции---------------------
        //Перед закрытием формы
        private void Form1_FormClosing(object sender, FormClosingEventArgs e)
        {
            try
            {
                //На всякий случай рубаем соединение
                sqliteConnection.Close();
            }
            catch
            {

            }
        }
    }
}