using System;
using System.Windows.Forms;

namespace Calculater2
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
                switch (textBox2.Text)
                {
                    case "+":
                        textBox4.Text = Convert.ToString(Convert.ToDouble(textBox1.Text) + Convert.ToDouble(textBox3.Text));
                        break;
                    case "-":
                        textBox4.Text = Convert.ToString(Convert.ToDouble(textBox1.Text) - Convert.ToDouble(textBox3.Text));
                        break;
                    case "*":
                        textBox4.Text = Convert.ToString(Convert.ToDouble(textBox1.Text) * Convert.ToDouble(textBox3.Text));
                        break;
                    case "/":
                        textBox4.Text = Convert.ToString(Convert.ToDouble(textBox1.Text) / Convert.ToDouble(textBox3.Text));
                        break;
                    default:
                        MessageBox.Show("Введён недопустимый знак между значениями!");
                        break;
                }
            }
            catch (FormatException fe)
            {
                MessageBox.Show("Ошибка при введении значений!\n" + fe.Message);
            }
            catch
            {
                MessageBox.Show("ОЙ! Какая-то ошибка!");
            }
        }
    }
}
