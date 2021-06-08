using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Windows.Forms;

namespace bilet2
{
    public partial class Form1 : Form
    {
        public Form1()
        {
            InitializeComponent();
        }

        private void button1_Click(object sender, EventArgs e)
        {
            int result = 0;
            switch (comboBox1.SelectedItem.ToString())
            {
                case "Фиалка":
                    result = 10;
                    break;
                case "Роза":
                    result = 20;
                    break;
                case "Ромашка":
                    result = 15;
                    break;
                case "Подснежник":
                    result = 40;
                    break;
                default:
                    MessageBox.Show("Ошибка в выборе цветка!");
                    return;
            }
            result *= Convert.ToInt32(numericUpDown1.Value);
            textBox1.Text = result.ToString();
        }
    }
}
