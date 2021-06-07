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

namespace DBF_Test
{
    public partial class Form1 : Form
    {
        private string filePath = "";

        private OleDbConnection _connection = null;
        private OdbcConnection _connection2 = null;

        public Form1()
        {
            InitializeComponent();
        }

        //Выбрать файл
        private void button1_Click(object sender, EventArgs e)
        {
            folderBrowserDialog1.ShowDialog();
            if (folderBrowserDialog1.SelectedPath.Length > 0)
            {
                filePath = folderBrowserDialog1.SelectedPath;
                MessageBox.Show("Сохранен новый путь!\n" + filePath);
            }
            folderBrowserDialog1.SelectedPath = "";
        }

        //Вывести поля
        private void button2_Click(object sender, EventArgs e)
        {
            /*_connection = new OleDbConnection(@"")
            DataTable dt = null;
            DataSet ds = null;
            if (_connection != null)
            {
                try
                {
                    _connection.Open();
                    dt = new DataTable();
                    ds = new DataSet();
                    OleDbCommand oCmd = _connection.CreateCommand();
                    oCmd.CommandText = "SELECT * FROM " + filePath;
                    dt.Load(oCmd.ExecuteReader());
                    _connection.Close();
                    MessageBox.Show(dt.Columns.ToString());
                }
                catch (Exception ex)
                {
                    MessageBox.Show("Ошибка: " + ex.Message);
                }
            }*/
            /*try
            {
                //MessageBox.Show(@"Provider=Microsoft.Jet.OLEDB.4.0;Driver={Microsoft dBASE Driver (*.dbf)};DriverID=277;Dbq=C:\Users\admin\Desktop\123;");
                //using (_connection2 = new OdbcConnection(@"Provider=Microsoft.Jet.OLEDB.4.0;Data Source=C:\Users\admin\Desktop\123;User ID=Admin;Password=;"))
                using (_connection2 = new OdbcConnection(@"Driver={Microsoft dBASE Driver (*.dbf)}; DriverID=277; Dbq=C:\Users\admin\Desktop\123;"))
                {
                    MessageBox.Show(_connection2.ConnectionTimeout.ToString());
                    _connection2.Open();
                    MessageBox.Show("ds");
                    using (OdbcCommand cmd = _connection2.CreateCommand())
                    {
                        cmd.CommandText = "SELECT * FROM " + filePath;
                        using (OdbcDataReader reader = cmd.ExecuteReader())
                        {
                            while (reader.Read())
                            {
                                MessageBox.Show(reader.GetName(0));
                            }
                        }
                    }
                }
            }
            catch (Exception ex)
            {
                MessageBox.Show("Ошибка: " + ex.Message);
            }*/
            try
            {
                //Проверка провайдеров
                
                //MessageBox.Show(@"Provider=Microsoft.Jet.OLEDB.4.0;Driver={Microsoft dBASE Driver (*.dbf)};DriverID=277;Dbq=C:\Users\admin\Desktop\123;");
                string strConnection = @"Provider=Microsoft.JET.Oledb.4.0;Data Source=" + filePath + ";Extended Properties=dBASE IV;User ID=;Password=;";
                using (_connection = new OleDbConnection(strConnection))
                {
                    MessageBox.Show(_connection.ConnectionTimeout.ToString());
                    _connection.Open();
                    MessageBox.Show("ds");
                    using (OleDbCommand cmd = _connection.CreateCommand())
                    {
                        cmd.CommandText = "SELECT * FROM PLISP.DBF";
                        MessageBox.Show("as");
                        using (OleDbDataReader reader = cmd.ExecuteReader())
                        {
                            MessageBox.Show("2s");
                            MessageBox.Show(reader.GetName(0));
                            while (reader.Read())
                            {
                                MessageBox.Show(reader["KODEL"].ToString());
                            }
                            MessageBox.Show("zx");
                        }
                    }
                }
            }
            catch (Exception ex)
            {
                MessageBox.Show("Ошибка: " + ex.Message);
            }
        }
    }
}
