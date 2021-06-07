using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Windows.Forms;

namespace Project4
{
    public partial class Form1 : Form
    {
        public Form1()
        {
            InitializeComponent();

            comboBox1.Items.Add("Золото");
            comboBox1.Items.Add("Платина");
        }

        private void button1_Click(object sender, EventArgs e)
        {
            try
            {
                double result = Convert.ToDouble(textBox1.Text) * Convert.ToDouble(textBox2.Text) * Convert.ToDouble(textBox3.Text);
                switch (comboBox1.Items[comboBox1.SelectedIndex].ToString())
                {
                    case "Дерево":
                        result *= 2;
                        break;
                    case "Медь":
                        result *= 3;
                        break;
                    case "Железо":
                        result *= 4;
                        break;
                    case "Золото":
                        result *= 5;
                        break;
                    case "Платина":
                        result *= 7;
                        break;
                    default:
                        MessageBox.Show("Неизвестный материал!");
                        return;
                }
                MessageBox.Show("Стоимость пластины составляет: " + result, "Результат расчета", MessageBoxButtons.OK, MessageBoxIcon.Information);
            }
            catch
            {
                MessageBox.Show("Ошибка!");
            }
        }
    }
}
