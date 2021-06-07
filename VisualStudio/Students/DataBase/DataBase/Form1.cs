using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Windows.Forms;

namespace DataBase
{
    public partial class Form1 : Form
    {
        public Form1()
        {
            InitializeComponent();
        }

        private void Form1_Load(object sender, EventArgs e)
        {
            // TODO: данная строка кода позволяет загрузить данные в таблицу "database1DataSet.Users". При необходимости она может быть перемещена или удалена.
            this.usersTableAdapter.Fill(this.database1DataSet.Users);
        }

        //Обновление БД
        private void button1_Click(object sender, EventArgs e)
        {
            this.usersTableAdapter.Update(this.database1DataSet.Users);
        }

        //Загрузка из БД
        private void button2_Click(object sender, EventArgs e)
        {
            this.usersTableAdapter.Fill(this.database1DataSet.Users);
        }
    }
}