using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Data.Common;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Windows.Forms;
using Excel = Microsoft.Office.Interop.Excel;
using System.Data.SQLite;
using System.IO;

namespace ConvertExcelToBD
{
    public partial class Form1 : Form
    {
        string excelReader = "";
        string bdWriter = "";
        string tableWriter = "";
        string databaseName = @"C:\cyber.db";

        public Form1()
        {
            InitializeComponent();
        }

        //Открыть Excel
        private void button1_Click(object sender, EventArgs e)
        {
            if (numericUpDown1.Value > 0)
            {
                OpenFileDialog opf = new OpenFileDialog();
                opf.Filter = "Все файлы (*.*)|*.*|Excel (*.XLS)|*.XLS|Excelx (*.XLSX)|*.XLSX";
                opf.ShowDialog();
                if (File.Exists(opf.FileName))
                {
                    excelReader = opf.FileName;
                    bool printProverka = true;
                    Excel.Application excel = new Excel.Application();
                    Excel.Sheets excelSheets;
                    Excel.Worksheet excelWorkSheets;
                    excel.Visible = true;
                    try
                    {
                        //Открытие документа
                        excel.Workbooks.Open(excelReader);
                        excelSheets = excel.Worksheets;
                        excelWorkSheets = (Excel.Worksheet)excelSheets.get_Item(1);
                        Excel.Range excelRange = excelWorkSheets.UsedRange;
                        dataGridView1.Columns.Clear();
                        int dataGridR = 0;
                        int dataGridC = 0;
                        for (int c = 1; c <= numericUpDown1.Value; c++)
                            dataGridView1.Columns.Add(new DataGridViewColumn() { CellTemplate = new DataGridViewTextBoxCell() });
                        int rowsPositionBegin = 1;
                        if (numericUpDown2.Value > 1)
                        {
                            dataGridView1.Rows.Add(new DataGridViewRow());
                            for (int c = 1; c <= numericUpDown1.Value; c++)
                            {
                                string str = Convert.ToString(excelRange.Cells[1, c].Value2);
                                dataGridView1.Rows[dataGridR].Cells[dataGridC].Value = str;
                                dataGridC++;
                            }
                            dataGridR++;
                            rowsPositionBegin = Convert.ToInt32(numericUpDown2.Value);
                        }
                        dataGridC = 0;
                        int rowsPositionEnd = excelRange.Rows.Count;
                        if (numericUpDown3.Value > 0)
                            rowsPositionEnd = Convert.ToInt32(numericUpDown3.Value);
                        for (int r = rowsPositionBegin; r <= rowsPositionEnd; r++)
                        {
                            dataGridView1.Rows.Add(new DataGridViewRow());
                            for (int c = 1; c <= numericUpDown1.Value; c++)
                            {
                                string str = Convert.ToString(excelRange.Cells[r, c].Value2);
                                dataGridView1.Rows[dataGridR].Cells[dataGridC].Value = str;
                                dataGridC++;
                            }
                            dataGridR++;
                            dataGridC = 0;
                        }
                        excel.Workbooks[1].Close();
                    }
                    catch
                    {
                        MessageBox.Show("Ошибка!");
                        printProverka = false;
                    }
                    finally
                    {
                        excel.Quit();
                        if (printProverka)
                            MessageBox.Show("Файл загружен!", "Информация", MessageBoxButtons.OK, MessageBoxIcon.Information);
                    }
                }
            }
            else
                MessageBox.Show("Введите количество колонок!");
        }

        //Выбрать базу данных
        private void button2_Click(object sender, EventArgs e)
        {
            comboBox1.Items.Clear();
            openFileDialog1.ShowDialog();
            if (openFileDialog1.CheckPathExists)
            {
                databaseName = openFileDialog1.FileName;
                try
                {
                    SQLiteConnection connection =
                        new SQLiteConnection(string.Format("Data Source={0};", databaseName));
                    connection.Open();
                    SQLiteCommand command = new SQLiteCommand("SELECT name FROM sqlite_master WHERE type='table' ORDER BY name;", connection);
                    SQLiteDataReader reader = command.ExecuteReader();
                    foreach (DbDataRecord record in reader)
                        comboBox1.Items.Add(Convert.ToString(record["name"]));
                    connection.Close();
                    MessageBox.Show("Готово");
                }
                catch (Exception ex)
                {
                    MessageBox.Show(ex.Message.ToString(), ex.Source.ToString(), MessageBoxButtons.OK, MessageBoxIcon.Error);
                }
            }
        }

        //Перенести из Excel в Базу данных
        private void button3_Click(object sender, EventArgs e)
        {
            if (tableWriter.Length > 0)
            {
                if (checkBox2.Checked == false)
                {
                    string query = "";
                    string nameColumns = "";
                    for (int c = 1; c <= dataGridView1.Columns.Count; c++)
                    {
                        if (c == 1)
                            nameColumns += "'" + dataGridView1.Rows[0].Cells[c - 1].Value + "'";
                        else
                            nameColumns += ",'" + dataGridView1.Rows[0].Cells[c - 1].Value + "'";
                    }
                    string values = "";
                    for (int r = 2; r <= dataGridView1.Rows.Count - 1; r++)
                    {
                        values = "";
                        for (int c = 1; c <= dataGridView1.Columns.Count; c++)
                        {
                            if (c == 1)
                                values += "'" + dataGridView1.Rows[r - 1].Cells[c - 1].Value + "'";
                            else
                                values += ",'" + dataGridView1.Rows[r - 1].Cells[c - 1].Value + "'";
                        }
                        query += "INSERT INTO '" + comboBox1.Text + "' (" + nameColumns + ") VALUES (" + values + ");";
                    }
                    if (checkBox1.Checked)
                        MessageBox.Show(query);
                    try
                    {
                        SQLiteConnection connection =
                            new SQLiteConnection(string.Format("Data Source={0};", databaseName));
                        connection.Open();
                        SQLiteCommand command = new SQLiteCommand(query, connection);
                        command.ExecuteNonQuery();
                        connection.Close();
                        MessageBox.Show("Готово");
                    }
                    catch (Exception ex)
                    {
                        MessageBox.Show(ex.Message.ToString(), ex.Source.ToString(), MessageBoxButtons.OK, MessageBoxIcon.Error);
                    }
                }
                else
                {
                    string query = "";
                    string values = "";
                    for (int r = 2; r <= dataGridView1.Rows.Count - 1; r++)
                    {
                        for (int c = 2; c <= dataGridView1.Columns.Count; c++)
                        {
                            if (c != dataGridView1.Columns.Count)
                                values += "'" + dataGridView1.Rows[0].Cells[c - 1].Value + "'='" + dataGridView1.Rows[r - 1].Cells[c - 1].Value + "',";
                            else
                                values += "'" + dataGridView1.Rows[0].Cells[c - 1].Value + "'='" + dataGridView1.Rows[r - 1].Cells[c - 1].Value + "'";
                        }
                        query += "UPDATE '" + comboBox1.Text + "' SET " + values + " WHERE " + dataGridView1.Rows[0].Cells[0].Value + "=" + dataGridView1.Rows[r-1].Cells[0].Value + ";";
                        values = "";
                    }
                    if (checkBox1.Checked)
                        MessageBox.Show(query);
                    try
                    {
                        SQLiteConnection connection =
                            new SQLiteConnection(string.Format("Data Source={0};", databaseName));
                        connection.Open();
                        SQLiteCommand command = new SQLiteCommand(query, connection);
                        command.ExecuteNonQuery();
                        connection.Close();
                        MessageBox.Show("Готово");
                    }
                    catch (Exception ex)
                    {
                        MessageBox.Show(ex.Message.ToString(), ex.Source.ToString(), MessageBoxButtons.OK, MessageBoxIcon.Error);
                    }
                }
            }
            else
            {
                MessageBox.Show("Выберите название таблицы!");
            }
        }

        private void comboBox1_SelectedIndexChanged(object sender, EventArgs e)
        {
            tableWriter = comboBox1.Text;
        }

        private void checkBox2_CheckedChanged(object sender, EventArgs e)
        {
            if (checkBox2.Checked)
                button3.Text = "Обновить Базу данных из Excel";
            else
                button3.Text = "Перенести Excel  в Базу данных";
        }
    }
}
