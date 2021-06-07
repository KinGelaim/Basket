using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Data.SqlClient;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Windows.Forms;
using System.IO;

namespace Sapper
{
    public partial class EndGameForm : Form
    {
        private bool isWinner;
        private string time;
        private int countMine, height, width;

        public EndGameForm(bool isWinner, string time, int countMine, int countAllMine, int height, int width)
        {
            InitializeComponent();

            this.isWinner = isWinner;
            this.time = time;
            this.countMine = countMine;
            this.height = height;
            this.width = width;

            if (isWinner)
                this.Text = "Ура! Вы победили!";
            else
            {
                this.Text = "Увы! Вы проиграли! :(";
                label4.Visible = false;
                textBox1.Visible = false;
            }
            label1.Text = "Затраченное время: " + time;
            label2.Text = "Размер поля: " + width + " на " + height;
            label3.Text = "Найдено мин " + countMine + " из " + countAllMine;
        }

        private void button1_Click(object sender, EventArgs e)
        {
            if (isWinner)
            {
                if (Properties.Settings.Default.saveInBD)
                {
                    if (!string.IsNullOrEmpty(textBox1.Text) && !string.IsNullOrWhiteSpace(textBox1.Text))
                    {
                        //Добавление в БД
                        string strSqlConnection = @"Data Source=(LocalDB)\v11.0;AttachDbFilename=C:\Users\admin\Documents\Visual Studio 2013\Projects\Miner\Miner\DatabaseSapper.mdf;Integrated Security=True;";
                        SqlConnection sqlConnection = new SqlConnection(strSqlConnection);
                        sqlConnection.Open();
                        SqlCommand command = new SqlCommand("INSERT [result] (name,width,height,count,time,date) VALUES (@name,@width,@height,@count,@time,@date)", sqlConnection);
                        command.Parameters.AddWithValue("name", textBox1.Text);
                        command.Parameters.AddWithValue("width", width);
                        command.Parameters.AddWithValue("height", height);
                        command.Parameters.AddWithValue("count", countMine);
                        command.Parameters.AddWithValue("time", time);
                        command.Parameters.AddWithValue("date", DateTime.Now.ToShortDateString());
                        command.ExecuteNonQuery();
                        sqlConnection.Close();
                    }
                    else
                    {
                        MessageBox.Show("Поле Имени обязательно для заполнения!");
                    }
                }
                if (Properties.Settings.Default.saveInTextFile)
                {
                    if (!string.IsNullOrEmpty(textBox1.Text) && !string.IsNullOrWhiteSpace(textBox1.Text))
                    {
                        if (File.Exists("result.txt"))
                        {
                            File.AppendAllText("result.txt", "\n" + textBox1.Text + "\t" + time + "\t" + countMine + "\t\t\t" + width + "\t\t\t" + height + "\t\t\t" + DateTime.Now.ToShortDateString());
                        }
                        else
                        {
                            FileStream createFile = File.Create("result.txt");
                            StreamWriter writer = new StreamWriter(createFile);
                            writer.Write("Имя\t\tВремя\tКол-во мин\tШирина поля\tВысота поля\tДата\n" + textBox1.Text + "\t" + time + "\t" + countMine + "\t\t\t" + width + "\t\t\t" + height + "\t\t\t" + DateTime.Now.ToShortDateString());
                            writer.Close();
                            createFile.Close();
                        }
                    }
                    else
                    {
                        MessageBox.Show("Поле Имени обязательно для заполнения!");
                    }
                }
            }
            this.Close();
        }
    }
}
