using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Windows.Forms;

namespace Calculater3
{
    public partial class Form1 : Form
    {
        double ch1 = 0;
        double ch2 = 0;
        char symbol = '!';

        public Form1()
        {
            InitializeComponent();

            try
            {
                button1.Image = new Bitmap(new Bitmap("del.png"), button1.Width - 5, button1.Height);
            }
            catch
            {
                MessageBox.Show("Проблемы с изображением, но калькулятор будет работать!");
            }
        }

        //Бэкспейс
        private void button1_Click(object sender, EventArgs e)
        {
            if (textBox1.Text.Length > 0)
            {
                string str = textBox1.Text;
                string prStr = str.Substring(0, str.Length - 1);
                textBox1.Text = prStr;
            }
        }

        //Цифры
        private void button2_Click(object sender, EventArgs e)
        {
            textBox1.Text += "0";
        }

        private void button3_Click(object sender, EventArgs e)
        {
            textBox1.Text += "1";
        }

        private void button4_Click(object sender, EventArgs e)
        {
            textBox1.Text += "2";
        }

        private void button5_Click(object sender, EventArgs e)
        {
            textBox1.Text += "3";
        }

        private void button6_Click(object sender, EventArgs e)
        {
            textBox1.Text += "4";
        }

        private void button7_Click(object sender, EventArgs e)
        {
            textBox1.Text += "5";
        }

        private void button8_Click(object sender, EventArgs e)
        {
            textBox1.Text += "6";
        }

        private void button9_Click(object sender, EventArgs e)
        {
            textBox1.Text += "7";
        }

        private void button10_Click(object sender, EventArgs e)
        {
            textBox1.Text += "8";
        }

        private void button11_Click(object sender, EventArgs e)
        {
            textBox1.Text += "9";
        }

        //Действия
        private void button13_Click(object sender, EventArgs e)
        {
            if (textBox1.Text.Length > 0)
            {
                ch1 = Convert.ToDouble(textBox1.Text);
                symbol = '+';
                textBox1.Text = "";
            }
        }

        private void button14_Click(object sender, EventArgs e)
        {
            if (textBox1.Text.Length > 0)
            {
                ch1 = Convert.ToDouble(textBox1.Text);
                symbol = '-';
                textBox1.Text = "";
            }
        }

        private void button15_Click(object sender, EventArgs e)
        {
            if (textBox1.Text.Length > 0)
            {
                ch1 = Convert.ToDouble(textBox1.Text);
                symbol = '*';
                textBox1.Text = "";
            }
        }

        private void button16_Click(object sender, EventArgs e)
        {
            if (textBox1.Text.Length > 0)
            {
                ch1 = Convert.ToDouble(textBox1.Text);
                symbol = '/';
                textBox1.Text = "";
            }
        }

        //Считаем
        private void button12_Click(object sender, EventArgs e)
        {
            if (symbol != '!' && textBox1.Text.Length > 0)
            {
                ch2 = Convert.ToDouble(textBox1.Text);
                double res = 0;
                switch (symbol)
                {
                    case '+':
                        res = ch1 + ch2;
                        break;
                    case '-':
                        res = ch1 - ch2;
                        break;
                    case '*':
                        res = ch1 * ch2;
                        break;
                    case '/':
                        res = ch1 / ch2;
                        break;
                }
                textBox1.Text = Convert.ToString(res);
                symbol = '!';
            }
        }
    }
}