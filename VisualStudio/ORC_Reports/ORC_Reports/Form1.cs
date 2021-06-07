using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Windows.Forms;
using MySql.Data.MySqlClient;
using Word = Microsoft.Office.Interop.Word;
using Excel = Microsoft.Office.Interop.Excel;
using System.IO;
using System.Net;
using Newtonsoft.Json;
using System.Diagnostics;
using Microsoft.Win32;
using System.Reflection;

namespace ORC_Reports
{
    public partial class Form1 : Form
    {
        //Сервер
        string serverPath = ClassBD.ServerName;    //Для быстрого свича localhost и 192.168.55.16

        //Путь к хранилищу
        string storagePath = @"Отчеты\";

        //Для SQL
        /*static MySqlConnection sqlConnection;
        static string connectionString = "datasource=192.168.55.16;port=3306;username=ntiim;password=458620;database=orc_db;CharSet=utf8;Integrated Security=True";
        static MySqlDataReader sqlReader = null;*/

        public Form1()
        {
            InitializeComponent();

            if(Properties.Settings.Default.ReportPath == "")
            {
                Properties.Settings.Default.ReportPath = storagePath;
                Properties.Settings.Default.Save();
            }

            storagePath = Properties.Settings.Default.ReportPath;

            CheckStorageDir();
            CheckFileJsonExist();

            if (Properties.Settings.Default.IsAutoUpdate)
                CheckUpdateVersion();

            //ClassBD.LoadFullBD();

            CheckWindowsReestr();
        }

        //Открытия директории для хранения отчетов
        private void openToolStripMenuItem_Click(object sender, EventArgs e)
        {
            if (Directory.Exists(storagePath))
                System.Diagnostics.Process.Start("explorer", storagePath);
            else
                MessageBox.Show("Проверьте выбранную директорию в настройках!");
        }

        //Выход из приложения
        private void exitToolStripMenuItem_Click(object sender, EventArgs e)
        {
            Application.Exit();
        }

        //Открытия окна настроек
        private void settingsToolStripMenuItem1_Click(object sender, EventArgs e)
        {
            FormSettings settingsForm = new FormSettings();
            settingsForm.ShowDialog();
            storagePath = Properties.Settings.Default.ReportPath;
        }

        //Обновление программы
        private void updateToolStripMenuItem_Click(object sender, EventArgs e)
        {
            if(CheckUpdateVersion(false))
            {
                //Обновляем программу
                if (File.Exists("Updater.exe"))
                {
                    //Скачиваем + отображение процесса
                    WebClient client = new WebClient();
                    //client.DownloadProgressChanged += new DownloadProgressChangedEventHandler(download_ProgressChanged);
                    client.DownloadFileCompleted += new AsyncCompletedEventHandler(download_Completed);
                    client.DownloadFileAsync(new Uri("http://" + serverPath + "/orc_new/public/download_orc_reports"), "temp_ORC_Reports.exe");
                }
                else
                {
                    if (DialogResult.Yes == MessageBox.Show("Не найден лаунчер для обновления!\nЖелаете его создать?", "Подтверждение", MessageBoxButtons.YesNo, MessageBoxIcon.Question))
                    {
                        byte[] array = Properties.Resources.Updater;
                        FileStream fs = new FileStream("Updater.exe", FileMode.Create);
                        fs.Write(array, 0, array.Length);
                        fs.Close();
                    }
                }
            }
        }

        //Метод для отображения процента закачки
        private void download_ProgressChanged(object sender, DownloadProgressChangedEventArgs e)
        {
            try
            {
                //progressBar1.Value = e.ProgressPercentage;
            }
            catch (Exception) { }
        }

        //Метод, который будет выполняться по завершению
        private void download_Completed(object sender, AsyncCompletedEventArgs e)
        {
            try
            {
                if (File.Exists("Updater.exe"))
                {
                    //Открываем апдейтер с ключиками
                    //  -y - создать ярлык на рабочем столе
                    //  -s - старт новой программы
                    //  -e - завершения работы лаунчера
                    Process.Start("Updater.exe", "temp_ORC_Reports.exe ORC_Reports.exe -y -s -e");
                    Process.GetCurrentProcess().Kill();
                }
                else
                {
                    //Выгружаем из себя Updater, если его нет
                }
            }
            catch (Exception) { }
        }

        //Открытие окна описания программы
        private void aboutToolStripMenuItem_Click(object sender, EventArgs e)
        {
            FormAboutBox aboutBox = new FormAboutBox();
            aboutBox.ShowDialog();
        }

        //Проверка наличия директории для отчетов
        private bool CheckStorageDir()
        {
            if (Directory.Exists(storagePath))
                return true;
            else
                if (DialogResult.Yes == MessageBox.Show("Не найдена директория для отчетов!\nЖелаете ее создать?", "Подтверждение", MessageBoxButtons.YesNo, MessageBoxIcon.Question))
                {
                    Directory.CreateDirectory(storagePath);
                }
            return false;
        }

        //Проверка файла для создания json объектов
        private bool CheckFileJsonExist()
        {
            if (File.Exists(Path.GetDirectoryName(Assembly.GetExecutingAssembly().Location) + @"\Newtonsoft.Json.dll"))
                return true;
            else
                if (DialogResult.Yes == MessageBox.Show("Не найдена дополнительная библиотека для создания отчетов!\nЖелаете ее распаковать?", "Подтверждение", MessageBoxButtons.YesNo, MessageBoxIcon.Question))
                {
                    byte[] array = Properties.Resources.Newtonsoft_Json;
                    FileStream fs = new FileStream("Newtonsoft.Json.dll", FileMode.Create);
                    fs.Write(array, 0, array.Length);
                    fs.Close();
                }
            return false;
        }

        //Проверка наличия обновления
        private bool CheckUpdateVersion(bool isAutoUpdate=true)
        {
            WebRequest request = WebRequest.Create("http://" + serverPath + "/orc_new/public/check_version_orc_reports");
            WebResponse response = request.GetResponse();
            string newVersion = null;
            using (Stream stream = response.GetResponseStream())
            {
                using (StreamReader reader = new StreamReader(stream))
                {
                    newVersion = reader.ReadToEnd();
                }
            }
            if (newVersion != null)
            {
                if (new Version(newVersion) > new Version(Application.ProductVersion))
                {
                    MessageBox.Show("Необходимо обновление!\nДля обновления выберете соответствующий пункт в настройках!\nВ случае отказа от обновление, ответственности за некорректное выполнение программы никто не несет!\nНовая версия программы: " + newVersion);
                    return true;
                }
                else
                    if (!isAutoUpdate)
                        MessageBox.Show("Обновление не требуется!");
            }
            else
                MessageBox.Show("Не удалось получить версию программы!");
            return false;
        }

        //Проверка наличия протокола в реестре
        private void CheckWindowsReestr()
        {
            RegistryKey key;
            key = Registry.ClassesRoot.OpenSubKey("ORCReports");
            if (key != null)
            {

            }
            else
            {
                if (DialogResult.Yes == MessageBox.Show("Не найден протокол в реестре!\nЖелаете его создать?", "Подтверждение", MessageBoxButtons.YesNo, MessageBoxIcon.Question))
                {
                    key = Registry.ClassesRoot.CreateSubKey("ORCReports");
                    key.SetValue("", "URL: ORCReports Protocol");
                    key.SetValue("URL Protocol", "");

                    key = key.CreateSubKey("shell");
                    key = key.CreateSubKey("open");
                    key = key.CreateSubKey("command");
                    key.SetValue("", Assembly.GetExecutingAssembly().Location);
                }
            }
        }

        /*
         * 
         *          --------------!!!!!!!!!--------------ОТЧЕТЫ--------------!!!!!!!!!--------------
         * 
         * 
         * */

        //---------ОУД---------

        #region OUD

        //Справка о согласованиях совершения крупных сделок
        private void button1_Click(object sender, EventArgs e)
        {
            if (CheckFileJsonExist())
                if (CheckStorageDir())
                    if (comboBox1.SelectedIndex >= 0)
                    {
                        //Вызываем дополнительное окно для формирования имени, дат и вид отчета
                        string typeDocumet = "Excel";
                        if(comboBox1.SelectedIndex == 0)
                            typeDocumet = "Word";
                        MessageBox.Show(Reports.CreateReportOUD1("Отчетик", typeDocumet, checkBox1.Checked));
                    }
                    else
                        MessageBox.Show("Выберите тип отчета!");
        }

        // Справка: проекты Договоров/Контрактов на закуп за период
        private void button9_Click(object sender, EventArgs e)
        {
            if (CheckFileJsonExist())
                if (CheckStorageDir())
                {
                    FormChoseDepartmentPeriod fcdp = new FormChoseDepartmentPeriod();
                    fcdp.ShowDialog();
                    if (fcdp.isExit)
                    {
                        string savePath = null;
                        MessageBox.Show(Reports.CreateReportOUD2(fcdp.comboBox1.Text, fcdp.textBox1.Text, out savePath, fcdp.checkBox1.Checked, fcdp.departmentID, fcdp.dateTimePicker1.Value.ToShortDateString(), fcdp.dateTimePicker2.Value.ToShortDateString()));
                        if (fcdp.checkBox2.Checked)
                            if (savePath != null)
                                if (File.Exists(savePath))
                                    System.Diagnostics.Process.Start(savePath);
                    }
                }
        }

        //Справка: проекты Договоров/Контрактов на сбыт за период
        private void button10_Click(object sender, EventArgs e)
        {
            if (CheckFileJsonExist())
                if (CheckStorageDir())
                {
                    FormChoseDepartmentPeriod fcdp = new FormChoseDepartmentPeriod();
                    fcdp.ShowDialog();
                    if (fcdp.isExit)
                    {
                        string savePath = null;
                        MessageBox.Show(Reports.CreateReportOUD3(fcdp.comboBox1.Text, fcdp.textBox1.Text, out savePath, fcdp.checkBox1.Checked, fcdp.departmentID, fcdp.dateTimePicker1.Value.ToShortDateString(), fcdp.dateTimePicker2.Value.ToShortDateString()));
                        if (fcdp.checkBox2.Checked)
                            if (savePath != null)
                                if (File.Exists(savePath))
                                    System.Diagnostics.Process.Start(savePath);
                    }
                }
        }

        //Справка по подразделению на закуп: заключенные Договора/Контракты за период
        private void button12_Click(object sender, EventArgs e)
        {

        }

        #endregion

        //---------Второй отдел---------

        #region Второй отдел

        //Сводный отчет по договорам
        private void button2_Click(object sender, EventArgs e)
        {
            FormViewAndYear newFormViewAndYear = new FormViewAndYear();
            newFormViewAndYear.ShowDialog();
            if (newFormViewAndYear.isExit)
            {
                string savePath = null;
                MessageBox.Show(Reports.SecondDepartmentPrintReport1(newFormViewAndYear.comboBox2.Text, newFormViewAndYear.textBox2.Text, out savePath, newFormViewAndYear.checkBox1.Checked, newFormViewAndYear.comboBox1.Text, newFormViewAndYear.textBox1.Text));
                if (newFormViewAndYear.checkBox2.Checked)
                    if (savePath != null)
                        if (File.Exists(savePath))
                            System.Diagnostics.Process.Start(savePath);
            }
        }

        //Поступление за период
        private void button3_Click(object sender, EventArgs e)
        {
            FormChosePeriod fcp = new FormChosePeriod();
            fcp.ShowDialog();
            if(fcp.isExit)
            {
                string savePath = null;
                MessageBox.Show(Reports.SecondDepartmentPrintReport2(fcp.comboBox1.Text, fcp.textBox1.Text, out savePath, fcp.checkBox1.Checked, fcp.dateTimePicker1.Value.ToShortDateString(), fcp.dateTimePicker2.Value.ToShortDateString()));
                if (fcp.checkBox2.Checked)
                    if (savePath != null)
                        if (File.Exists(savePath))
                            System.Diagnostics.Process.Start(savePath);
            }
        }

        //Выполнение за период
        private void button4_Click(object sender, EventArgs e)
        {
            FormChoseTourViewContrPeriod fctvcp = new FormChoseTourViewContrPeriod();
            fctvcp.ShowDialog();
            if (fctvcp.isExit)
            {
                string savePath = null;
                MessageBox.Show(Reports.SecondDepartmentPrintReport3(fctvcp.comboBox1.Text, fctvcp.textBox1.Text, out savePath, fctvcp.checkBox1.Checked, fctvcp.comboBox2.SelectedItem.ToString(), fctvcp.counterpartieID, fctvcp.dateTimePicker1.Value.ToShortDateString(), fctvcp.dateTimePicker2.Value.ToShortDateString()));
                if (fctvcp.checkBox2.Checked)
                    if (savePath != null)
                        if (File.Exists(savePath))
                            System.Diagnostics.Process.Start(savePath);
            }
        }

        //Оплата за период
        private void button5_Click(object sender, EventArgs e)
        {
            FormChosePeriod fcp = new FormChosePeriod();
            fcp.ShowDialog();
            if (fcp.isExit)
            {
                string savePath = null;
                MessageBox.Show(Reports.SecondDepartmentPrintReport4(fcp.comboBox1.Text, fcp.textBox1.Text, out savePath, fcp.checkBox1.Checked, fcp.dateTimePicker1.Value.ToShortDateString(), fcp.dateTimePicker2.Value.ToShortDateString()));
                if (fcp.checkBox2.Checked)
                    if (savePath != null)
                        if (File.Exists(savePath))
                            System.Diagnostics.Process.Start(savePath);
            }
        }

        //Предприятия за год (кратко)
        private void button8_Click(object sender, EventArgs e)
        {
            FormChoseYear fcy = new FormChoseYear();
            fcy.ShowDialog();
            if(fcy.isExit)
            {
                string savePath = null;
                MessageBox.Show(Reports.SecondDepartmentPrintReport5(fcy.comboBox1.Text, fcy.textBox1.Text, out savePath, fcy.checkBox1.Checked, fcy.textBox2.Text));
                if (fcy.checkBox2.Checked)
                    if (savePath != null)
                        if (File.Exists(savePath))
                            System.Diagnostics.Process.Start(savePath);
            }
        }

        //Предприятия за год
        private void button6_Click(object sender, EventArgs e)
        {
            FormChoseYear fcy = new FormChoseYear();
            fcy.ShowDialog();
            if (fcy.isExit)
            {
                string savePath = null;
                MessageBox.Show(Reports.SecondDepartmentPrintReport6(fcy.comboBox1.Text, fcy.textBox1.Text, out savePath, fcy.checkBox1.Checked, fcy.textBox2.Text));
                if (fcy.checkBox2.Checked)
                    if (savePath != null)
                        if (File.Exists(savePath))
                            System.Diagnostics.Process.Start(savePath);
            }
        }

        //Сводный отчет по оплате
        private void button7_Click(object sender, EventArgs e)
        {

        }

        #endregion

        //---------ПЭО---------

        #region ПЭО

        private void button50_Click(object sender, EventArgs e)
        {

        }

        private void button51_Click(object sender, EventArgs e)
        {
            if (CheckFileJsonExist())
                if (CheckStorageDir())
                {
                    FormChoseContrViewSelected fccvs = new FormChoseContrViewSelected();
                    fccvs.ShowDialog();
                    if (fccvs.isExit)
                    {
                        string savePath = null;
                        MessageBox.Show(Reports.CreateReportPEO2(fccvs.comboBox1.Text, fccvs.textBox1.Text, out savePath, fccvs.checkBox1.Checked, fccvs.counterpartieID, fccvs.viewsNames));
                        if (fccvs.checkBox2.Checked)
                            if (savePath != null)
                                if (File.Exists(savePath))
                                    System.Diagnostics.Process.Start(savePath);
                    }
                }
        }

        #endregion
    }
}
