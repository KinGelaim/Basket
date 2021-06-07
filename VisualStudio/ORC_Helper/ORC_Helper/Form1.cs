using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Windows.Forms;
using MySql.Data.MySqlClient;
using System.IO;
using System.Net;
using Newtonsoft.Json;

namespace ORC_Helper
{
    public partial class Form1 : Form
    {
        /*static MySqlConnection sqlConnection;
        static string connectionString = "datasource=localhost;port=3306;username=root;password=;database=orc_db;CharSet=utf8;";
        static MySqlDataReader sqlReader = null;*/

        List<User> users = new List<User>();
        static string all_users = "http://192.168.55.16/orc_new/get_all_users";
        static string commands = "http://192.168.55.16/orc_new/all_select_commands";
        static string unsigning_url = "http://192.168.55.16/orc_new/public/all_unsigning_contract";

        //List<string> users_id = new List<string>();

        public Form1()
        {
            //Выгрузка DLL из ресурсов
            if (!File.Exists("Newtonsoft.Json.dll"))
            {

                byte[] array = ORC_Helper.Properties.Resources.Newtonsoft_Json;
                FileStream fs = new FileStream("Newtonsoft.Json.dll", FileMode.Create);
                fs.Write(array, 0, array.Length);
                fs.Close();
            }

            InitializeComponent();

            //Меняем иконку
            Icon ico = Properties.Resources.FILES03B;
            notifyIcon1.Icon = ico;
        }

        private void Form1_Load(object sender, EventArgs e)
        {
            timer1.Enabled = false;
            notifyIcon1.Visible = false;

            //Загрузка пользователей
            //users_id.Clear();

            //Прямое соединение с БД для получения списка пользователей и их ID
            /*sqlConnection = new MySqlConnection(connectionString);
            MySqlCommand command = new MySqlCommand("SELECT * FROM users ORDER BY users.surname ASC", sqlConnection);
            command.CommandTimeout = 60;
            sqlReader = null;
            try
            {
                sqlConnection.Open();
                sqlReader = command.ExecuteReader();
                while (sqlReader.Read())
                {
                    users.Add(Convert.ToString(sqlReader["surname"]) + " " + Convert.ToString(sqlReader["name"]) + " " + Convert.ToString(sqlReader["patronymic"]));
                    users_id.Add(Convert.ToString(sqlReader["id"]));
                }
            }
            catch (Exception ex)
            {
                MessageBox.Show(ex.Message.ToString(), ex.Source.ToString(), MessageBoxButtons.OK, MessageBoxIcon.Error);
            }
            finally
            {
                sqlConnection.Close();
            }*/

            //Обращение к веб-сервису для получения списка пользователей и их ID
            WebRequest request = WebRequest.Create(all_users);
            WebResponse response = request.GetResponse();
            using (Stream stream = response.GetResponseStream())
            {
                using (StreamReader reader = new StreamReader(stream))
                {
                    users = JsonConvert.DeserializeObject<List<User>>(reader.ReadToEnd());
                }
            }

            foreach (User user in users)
                comboBox1.Items.Add(user.surname + " " + user.name + " " + user.patronymic);

            //Установка настроек из памяти
            if (Properties.Settings.Default.id_user.Length != 0)
                for (int i = 0; i < users.Count; i++)
                    if (users[i].id == Properties.Settings.Default.id_user)
                        comboBox1.SelectedIndex = i;

            for (int i = 0; i < checkedListBox1.Items.Count; i++)
                if (checkedListBox1.Items[i].ToString() == "Письма")
                    checkedListBox1.SetItemChecked(i, Properties.Settings.Default.is_application);
                else if (checkedListBox1.Items[i].ToString() == "Договоры")
                    checkedListBox1.SetItemChecked(i, Properties.Settings.Default.is_contract);
                else if (checkedListBox1.Items[i].ToString() == "Протоколы")
                    checkedListBox1.SetItemChecked(i, Properties.Settings.Default.is_protocol);
                else if (checkedListBox1.Items[i].ToString() == "Не подписанные договора")
                    checkedListBox1.SetItemChecked(i, Properties.Settings.Default.is_none_signing);

            if (Properties.Settings.Default.time_interval != 0)
                trackBar1.Value = Properties.Settings.Default.time_interval;
            toolTip1.SetToolTip(trackBar1, trackBar1.Value.ToString());
        }

        private void Form1_Shown(object sender, EventArgs e)
        {
            if (Properties.Settings.Default.path_orc.Length != 0 && Properties.Settings.Default.id_user.Length != 0 && Properties.Settings.Default.time_interval != 0)
            {
                this.Hide();
                notifyIcon1.Visible = true;
                timer1.Interval = Properties.Settings.Default.time_interval * 60000;
                timer1.Enabled = true;
            }
        }

        //Путь к ORC
        private void button2_Click(object sender, EventArgs e)
        {
            OpenFileDialog opf = new OpenFileDialog();
            opf.Filter = "Все файлы (*.*)|*.*";
            opf.ShowDialog();
            if (File.Exists(opf.FileName))
            {
                Properties.Settings.Default.path_orc = opf.FileName;
                Properties.Settings.Default.Save();
            }
        }

        //Смена сервера
        private void checkBox1_CheckedChanged(object sender, EventArgs e)
        {
            if (checkBox1.Checked)
            {
                all_users = "http://localhost/orc_new/get_all_users";
                commands = "http://localhost/orc_new/all_select_commands";
                unsigning_url = "http://localhost/orc_new/public/all_unsigning_contract";
            }
            else
            {
                all_users = "http://192.168.55.16/orc_new/get_all_users";
                commands = "http://192.168.55.16/orc_new/all_select_commands";
                unsigning_url = "http://192.168.55.16/orc_new/public/all_unsigning_contract";
            }
        }

        private void comboBox1_SelectedIndexChanged(object sender, EventArgs e)
        {
            Properties.Settings.Default.id_user = users[comboBox1.SelectedIndex].id;
            Properties.Settings.Default.Save();
        }

        private void checkedListBox1_SelectedIndexChanged(object sender, EventArgs e)
        {
            if (checkedListBox1.GetItemChecked(checkedListBox1.SelectedIndex))
            {
                switch (checkedListBox1.SelectedItem.ToString())
                {
                    case "Письма":
                        Properties.Settings.Default.is_application = true;
                        break;
                    case "Договоры":
                        Properties.Settings.Default.is_contract = true;
                        break;
                    case "Протоколы":
                        Properties.Settings.Default.is_protocol = true;
                        break;
                    case "Не подписанные договора":
                        Properties.Settings.Default.is_none_signing = true;
                        break;
                    default:
                        break;
                }
            }
            else
            {
                switch (checkedListBox1.SelectedItem.ToString())
                {
                    case "Письма":
                        Properties.Settings.Default.is_application = false;
                        break;
                    case "Договоры":
                        Properties.Settings.Default.is_contract = false;
                        break;
                    case "Протоколы":
                        Properties.Settings.Default.is_protocol = false;
                        break;
                    case "Не подписанные договора":
                        Properties.Settings.Default.is_none_signing = false;
                        break;
                    default:
                        break;
                }
            }
            Properties.Settings.Default.Save();
        }

        private void trackBar1_Scroll(object sender, EventArgs e)
        {
            toolTip1.SetToolTip(trackBar1, trackBar1.Value.ToString());
            Properties.Settings.Default.time_interval = trackBar1.Value;
            Properties.Settings.Default.Save();
        }

        //Подтвердить
        private void button1_Click(object sender, EventArgs e)
        {
            this.Hide();
            notifyIcon1.Visible = true;
            timer1.Interval = Properties.Settings.Default.time_interval * 60000;
            timer1.Enabled = true;
        }

        //Получение информации о новых письмах, контрактах, протоколах (только о тех, которые не просмотрены!)
        private void timer1_Tick(object sender, EventArgs e)
        {
            if (Properties.Settings.Default.id_user.Length != 0)
            {
                /*int count_new_application = 0;
                int count_new_contract = 0;
                int count_new_protocol = 0;
                sqlConnection = new MySqlConnection(connectionString);
                MySqlCommand command1 = new MySqlCommand("SELECT count(*) as count_application FROM reconciliation_users JOIN users ON users.id=reconciliation_users.id_user JOIN applications ON applications.id=reconciliation_users.id_application WHERE reconciliation_users.check_reconciliation=0 AND users.id=@id_user AND reconciliation_users.id_application IS NOT NULL AND reconciliation_users.is_protocol=0 AND applications.is_protocol=0", sqlConnection);
                MySqlCommand command2 = new MySqlCommand("SELECT count(*) as count_contract FROM reconciliation_users JOIN users ON users.id=reconciliation_users.id_user WHERE check_reconciliation=0 AND users.id=@id_user AND reconciliation_users.id_contract IS NOT NULL", sqlConnection);
                MySqlCommand command3 = new MySqlCommand("SELECT count(*) as count_protocol FROM reconciliation_users JOIN users ON users.id=reconciliation_users.id_user WHERE check_reconciliation=0 AND users.id=@id_user AND reconciliation_users.id_application IS NOT NULL AND reconciliation_users.is_protocol=1", sqlConnection);
                command1.Parameters.AddWithValue("id_user", Properties.Settings.Default.id_user);
                command2.Parameters.AddWithValue("id_user", Properties.Settings.Default.id_user);
                command3.Parameters.AddWithValue("id_user", Properties.Settings.Default.id_user);
                command1.CommandTimeout = 60;
                command2.CommandTimeout = 60;
                command3.CommandTimeout = 60;
                sqlReader = null;
                try
                {
                    sqlConnection.Open();
                    sqlReader = command1.ExecuteReader();
                    while (sqlReader.Read())
                    {
                        count_new_application = Convert.ToInt32(sqlReader["count_application"]);
                    }
                    sqlConnection.Close();
                    sqlConnection.Open();
                    sqlReader = command2.ExecuteReader();
                    while (sqlReader.Read())
                    {
                        count_new_contract = Convert.ToInt32(sqlReader["count_contract"]);
                    }
                    sqlConnection.Close();
                    sqlConnection.Open();
                    sqlReader = command3.ExecuteReader();
                    while (sqlReader.Read())
                    {
                        count_new_protocol = Convert.ToInt32(sqlReader["count_protocol"]);
                    }
                }
                catch (Exception ex)
                {
                    MessageBox.Show(ex.Message.ToString(), ex.Source.ToString(), MessageBoxButtons.OK, MessageBoxIcon.Error);
                }
                finally
                {
                    sqlConnection.Close();
                }
                if (count_new_application > 0 || count_new_contract > 0 || count_new_protocol > 0)
                {
                    string text = "";
                    if (Properties.Settings.Default.is_application && count_new_application > 0)
                        text = "У Вас новые письма на согласование! (" + count_new_application + ")\n";
                    if (Properties.Settings.Default.is_contract && count_new_contract > 0)
                        text += "У Вас новые контракты на согласование! (" + count_new_contract + ")\n";
                    if (Properties.Settings.Default.is_protocol && count_new_protocol > 0)
                        text += "У Вас новые протоколы на согласование! (" + count_new_protocol + ")\n";
                    notifyIcon1.ShowBalloonTip(7000, "Новое согласование", text, ToolTipIcon.Info);
                }*/

                //Формирование запроса для получения информации
                string text = "";
                string local_commands = commands;
                local_commands += "?id_user=" + Properties.Settings.Default.id_user;
                if (Properties.Settings.Default.is_application)
                    local_commands += "&applications=1";
                if (Properties.Settings.Default.is_contract)
                    local_commands += "&contracts=1";
                if (Properties.Settings.Default.is_protocol)
                    local_commands += "&protocols=1";

                //Обращение к веб-сервису для информации о новом согласовании
                WebRequest request = WebRequest.Create(local_commands);
                WebResponse response = request.GetResponse();
                using (Stream stream = response.GetResponseStream())
                {
                    using (StreamReader reader = new StreamReader(stream))
                    {
                        text = reader.ReadToEnd();
                    }
                }

                //Обращение к веб-сервису за договорами не подписанными больше 30 дней
                if (Properties.Settings.Default.is_none_signing)
                {
                    string otvet = "";
                    request = WebRequest.Create(unsigning_url);
                    response = request.GetResponse();
                    using (Stream stream = response.GetResponseStream())
                    {
                        using (StreamReader reader = new StreamReader(stream))
                        {
                            otvet = reader.ReadToEnd();
                        }
                    }
                    if (otvet != "")
                        text += otvet;
                }

                //Вывод в трей
                if (text.Length > 0)
                {
                    Icon ico = Properties.Resources.RECL;
                    notifyIcon1.Icon = ico;
                    notifyIcon1.ShowBalloonTip(7000, "Новое согласование", text, ToolTipIcon.Info);
                }
                else
                {
                    Icon ico = Properties.Resources.FILES03B;
                    notifyIcon1.Icon = ico;
                }
            }
        }

        //Открыть ORC
        private void openORCToolStripMenuItem_Click(object sender, EventArgs e)
        {
            if (File.Exists(Properties.Settings.Default.path_orc))
            {
                System.Diagnostics.Process.Start(Properties.Settings.Default.path_orc);
                Icon ico = Properties.Resources.FILES03B;
                notifyIcon1.Icon = ico;
            }
            else
                MessageBox.Show("Файл по заданному пути не найден!\nПроверьте настройки!", "Ошибка!", MessageBoxButtons.OK, MessageBoxIcon.Error);
        }

        private void settingsToolStripMenuItem_Click(object sender, EventArgs e)
        {
            this.Show();
            notifyIcon1.Visible = false;
            timer1.Enabled = false;
        }

        private void exitToolStripMenuItem_Click(object sender, EventArgs e)
        {
            this.Close();
        }

        private void Form1_FormClosing(object sender, FormClosingEventArgs e)
        {
            e.Cancel = true;
            FormEnterThePassword fetp = new FormEnterThePassword();
            fetp.ShowDialog();
            if (fetp.isExit)
                if (fetp.password == "457")
                    e.Cancel = false;
        }
    }
}
