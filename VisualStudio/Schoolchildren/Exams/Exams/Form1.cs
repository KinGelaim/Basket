using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Windows.Forms;

namespace Exams
{
    public partial class Form1 : Form
    {
        int indexEdit = -1;

        public Form1()
        {
            InitializeComponent();
        }

        private void button1_Click(object sender, EventArgs e)
        {
            if (button1.Text == "Добавить")
            {
                string k = textBox1.Text + " " + textBox2.Text + " " + textBox3.Text;
                listBox1.Items.Add(k);
            }
            else
            {
                string k = textBox1.Text + " " + textBox2.Text + " " + textBox3.Text;
                listBox1.Items[indexEdit] = k;
                button1.Text = "Добавить";
                button2.Visible = true;
            }
            textBox1.Text = "";
            textBox2.Text = "";
            textBox3.Text = "";
        }

        private void button2_Click(object sender, EventArgs e)
        {
            if (listBox1.SelectedIndex >= 0)
            {
                indexEdit = listBox1.SelectedIndex;
                string k = listBox1.Items[listBox1.SelectedIndex].ToString();
                string[] prK = k.Split(' ');
                textBox1.Text = prK[0];
                textBox2.Text = prK[1];
                textBox3.Text = prK[2];
                button1.Text = "Изменить";
                button2.Visible = false;
            }
            else
                MessageBox.Show("Выберите ученика!");
        }
    }
}
