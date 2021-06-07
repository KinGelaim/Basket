using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Data.Common;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Windows.Forms;
using System.Data.SQLite;
using System.IO;

namespace BD_TEST_SQLite
{
    public partial class Form1 : Form
    {
        string databaseName = @"C:\cyber.db";

        public Form1()
        {
            InitializeComponent();
        }

        private void button1_Click(object sender, EventArgs e)
        {
            MessageBox.Show("Путь к БД: " + databaseName);
            try
            {
                if(!File.Exists(databaseName))
                    SQLiteConnection.CreateFile(databaseName);
                else
                    MessageBox.Show("База данных уже существует!");
            }
            catch(Exception ex)
            {
                MessageBox.Show(ex.Message.ToString(), ex.Source.ToString(), MessageBoxButtons.OK, MessageBoxIcon.Error);
            }
        }

        private void button2_Click(object sender, EventArgs e)
        {
            try
            {
                SQLiteConnection connection =
                    new SQLiteConnection(string.Format("Data Source={0};", databaseName));
                SQLiteCommand command =
                    new SQLiteCommand("CREATE TABLE example (id INTEGER PRIMARY KEY, value TEXT);", connection);
                connection.Open();
                command.ExecuteNonQuery();
                connection.Close();
                MessageBox.Show("Таблица создана!");
            }
            catch (Exception ex)
            {
                MessageBox.Show(ex.Message.ToString(), ex.Source.ToString(), MessageBoxButtons.OK, MessageBoxIcon.Error);
            }
        }

        private void button3_Click(object sender, EventArgs e)
        {
            try
            {
                SQLiteConnection connection =
                    new SQLiteConnection(string.Format("Data Source={0};", databaseName));
                connection.Open();
                SQLiteCommand command = new SQLiteCommand("SELECT name FROM sqlite_master WHERE type='table' ORDER BY name;", connection);
                SQLiteDataReader reader = command.ExecuteReader();
                foreach (DbDataRecord record in reader)
                    MessageBox.Show("Таблица: " + record["name"]);
                connection.Close();
                MessageBox.Show("Готово");
            }
            catch (Exception ex)
            {
                MessageBox.Show(ex.Message.ToString(), ex.Source.ToString(), MessageBoxButtons.OK, MessageBoxIcon.Error);
            }
        }

        private void button4_Click(object sender, EventArgs e)
        {
            try
            {
                SQLiteConnection connection =
                    new SQLiteConnection(string.Format("Data Source={0};", databaseName));
                connection.Open();
                SQLiteCommand command = new SQLiteCommand("INSERT INTO 'example' ('id', 'value') VALUES (1, 'Вася');", connection);
                command.ExecuteNonQuery();
                connection.Close();
                MessageBox.Show("Готово");
            }
            catch (Exception ex)
            {
                MessageBox.Show(ex.Message.ToString(), ex.Source.ToString(), MessageBoxButtons.OK, MessageBoxIcon.Error);
            }
        }

        private void button5_Click(object sender, EventArgs e)
        {
            try
            {
                SQLiteConnection connection =
                    new SQLiteConnection(string.Format("Data Source={0};", databaseName));
                connection.Open();
                SQLiteCommand command = new SQLiteCommand("SELECT * FROM 'example';", connection);
                SQLiteDataReader reader = command.ExecuteReader();
                string resultStr = "";
                resultStr += "\u250C" + new string('\u2500', 5) + "\u252C" + new string('\u2500', 60) + "\u2510\n";
                resultStr += "\n\u2502" + "  id \u2502" + new string(' ', 30) + "value" + new string(' ', 25) + "\u2502\n";
                resultStr += "\u251C" + new string('\u2500', 5) + "\u253C" + new string('\u2500', 60) + "\u2524\n";
                foreach (DbDataRecord record in reader)
                {
                    string id = record["id"].ToString();
                    id = id.PadLeft(5 - id.Length, ' ');
                    string value = record["value"].ToString();
                    string result = "\u2502" + id + " \u2502";
                    value = value.PadLeft(60, ' ');
                    result += value + "\u2502";
                    resultStr += result + "\n";
                }
                connection.Close();
                resultStr += "\u2514" + new string('\u2500', 5) + "\u2534" + new string('\u2500', 60) + "\u2518";
                MessageBox.Show(resultStr);
            }
            catch (Exception ex)
            {
                MessageBox.Show(ex.Message.ToString(), ex.Source.ToString(), MessageBoxButtons.OK, MessageBoxIcon.Error);
            }
        }

        private void button6_Click(object sender, EventArgs e)
        {
            folderBrowserDialog1.ShowDialog();
            if (folderBrowserDialog1.SelectedPath.Length > 0)
            {
                databaseName = folderBrowserDialog1.SelectedPath + @"\cyber.db";
                Properties.Settings.Default.Save();
                MessageBox.Show(databaseName);
            }
            folderBrowserDialog1.SelectedPath = "";
        }
    }
}
