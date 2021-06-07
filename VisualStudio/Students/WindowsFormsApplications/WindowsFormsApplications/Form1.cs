using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Windows.Forms;

namespace WindowsFormsApplications
{
    public partial class Form1 : Form
    {
        public Form1()
        {
            InitializeComponent();
        }

        private void button1_Click(object sender, EventArgs e)
        {
            try
            {
                calculate(textBox1.Text, textBox2.Text, label1.Text, textBox3);
                calculate(textBox4.Text, textBox5.Text, label3.Text, textBox6);
                calculate(textBox7.Text, textBox8.Text, label4.Text, textBox9);
                calculate(textBox10.Text, textBox11.Text, label5.Text, textBox12);
            }
            catch(Exception ex)
            {
                MessageBox.Show("Ошибка!" + ex.Message, "Ошибка", MessageBoxButtons.OK, MessageBoxIcon.Error);
            }
        }

        private void calculate(string a, string b, string ch, TextBox resultTextBox)
        {
            if (a.Length > 0 && b.Length > 0)
            {
                double x = Convert.ToDouble(a);
                double y = Convert.ToDouble(b);
                switch (ch)
                {
                    case "+":
                        resultTextBox.Text = Convert.ToString(x + y);
                        break;
                    case "-":
                        resultTextBox.Text = Convert.ToString(x - y);
                        break;
                    case "*":
                        resultTextBox.Text = Convert.ToString(x * y);
                        break;
                    case "/":
                        resultTextBox.Text = Convert.ToString(x / y);
                        break;
                    default:
                        break;
                }
            }
        }
    }
}
