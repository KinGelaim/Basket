using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Windows.Forms;

namespace OnlineGame
{
    public partial class FormSettings : Form
    {
        public bool isExit = false;
        public string ip = "";
        public string port = "";

        public FormSettings()
        {
            InitializeComponent();
        }

        private void button1_Click(object sender, EventArgs e)
        {
            if (textBox1.Text.Length > 0 && textBox2.Text.Length > 0)
            {
                ip = textBox1.Text;
                port = textBox2.Text;
                isExit = true;
                this.Close();
            }
            else
                MessageBox.Show("Введите IP адресс и порт сервера!", "Ошибка", MessageBoxButtons.OK, MessageBoxIcon.Error);
        }
    }
}
