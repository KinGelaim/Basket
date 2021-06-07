using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Windows.Forms;
using System.Net;
using System.IO;
using System.Diagnostics;

namespace AutoUpdater
{
    public partial class Form1 : Form
    {
        //Для проверки версии файла методом 2
        System.Xml.XmlReader xmlReader;

        public Form1()
        {
            InitializeComponent();

            label2.Text = Application.ProductVersion;
        }

        //Проверить наличие обновления
        private void button1_Click(object sender, EventArgs e)
        {
            //Проверять обновление можно:
            //  1) Через запрос-ответ к серверу, где лежит исполняющий файл, который проверит версию или в БД или она сама будет записана в файле
            //  2) На сервере лежит файл содержащий версию, например, XML
            //  3) Дабы не было необходимости скачивать файл, можно смотреть на сервере название файла, которое будет содержать версию

            //Метод 1
            WebRequest request = WebRequest.Create("http://localhost/orc_new/public/check_version_orc_reports");
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
                    MessageBox.Show("Необходимо обновление! \nВ случае отказа от обновление, ответственности за некорректное выполнение программы никто не несет!\nНовая версия программы: " + newVersion);
                else
                    MessageBox.Show("Обновление не требуется!");
            }
            else
                MessageBox.Show("Не удалось получить версию программы!");

            //Метод 2
            if (File.Exists("version.xml"))
            {
                xmlReader = new System.Xml.XmlTextReader("version.xml");
                //Здесь нужно будет осторожнее и правильно указать искомое имя, а то может выйти бесконечный цикл
                do xmlReader.Read();
                while (xmlReader.Name != "program1");
                xmlReader.Read();
                newVersion = xmlReader.Value;
                MessageBox.Show("Версия из XML: " + newVersion);
                //Дальше также, как в методе 1
                //..............................................
            }
            else
            {
                byte[] array = Properties.Resources.version;
                FileStream fs = new FileStream("Version.xml", FileMode.Create);
                fs.Write(array, 0, array.Length);
                fs.Close();
            }

            //Метод 3
        }

        //Обновление программы
        private void button2_Click(object sender, EventArgs e)
        {
            //В текущей проге
            //  1) Скачиваем программу с сервера (с новым названием (добавим temp_))
            //  2) Процесс скачивания отображаем
            //  3) TODO: Проверяем на повреждение (скорее всего MD5)
            //  4) Проверяем наличие лаунчера
            //  5) Выгружаем его из себя, если нет
            //  6) Посылаем запрос на закрытие старой программы (в которой, сейчас находимся)
            //  7) Запускаем отдельный лаунчер, который 
            //      7.1) Закроет старую программу (в случае, если она еще открыта)
            //      7.2) Удалит старую программу
            //      7.3) Переименует скаченную
            //      7.4) Будем передавать ключи на выполнение последующих команд (если они необходимы), тогда лаунчер:
            //          7.4.1) Закинет ярлык на рабочий стол
            //          7.4.2) Запустит новый процесс с новой программой
            //          7.4.3) Закроет лаунчер

            //Скачиваем + отображение процесса
            WebClient client = new WebClient();
            client.DownloadProgressChanged += new DownloadProgressChangedEventHandler(download_ProgressChanged);
            client.DownloadFileCompleted += new AsyncCompletedEventHandler(download_Completed);
            client.DownloadFileAsync(new Uri("http://localhost/orc_new/public/download_orc_reports"), "temp_ORC_Reports.exe");
        }

        //Метод для отображения процента закачки
        private void download_ProgressChanged(object sender, DownloadProgressChangedEventArgs e)
        {
            try
            {
                progressBar1.Value = e.ProgressPercentage;
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
                    Process.Start("Updater.exe", "temp_AutoUpdater.exe AutoUpdater.exe -y -s -e");
                    Process.GetCurrentProcess().Kill();
                }
                else
                {
                    //Выгружаем из себя Updater, если его нет
                }
            }
            catch (Exception) { }
        }


        //Выход
        private void button3_Click(object sender, EventArgs e)
        {
            //Выход из программы
            Application.Exit();
        }
    }
}
