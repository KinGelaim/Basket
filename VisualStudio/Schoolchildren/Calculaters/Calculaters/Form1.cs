using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Windows.Forms;
using System.Threading;

namespace Calculaters
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
                string str1 = textBox1.Text;
                double db1 = Convert.ToDouble(str1);

                string str2 = textBox2.Text;
                double db2 = Convert.ToDouble(str2);

                double res = db1 + db2;
                string str3 = Convert.ToString(res);

                textBox3.Text = str3;

                textBox6.Text = Convert.ToString(Convert.ToDouble(textBox4.Text) - Convert.ToDouble(textBox5.Text));
                textBox9.Text = Convert.ToString(Convert.ToDouble(textBox7.Text) * Convert.ToDouble(textBox8.Text));
                if (textBox10.Text.Length > 0 && textBox11.Text.Length > 0)
                {
                    textBox12.Text = Convert.ToString(Convert.ToDouble(textBox10.Text) / Convert.ToDouble(textBox11.Text));
                }
            }
            catch
            {
                MessageBox.Show("Пожалуйста, заполните все поля!");
            }
        }
    }
}
