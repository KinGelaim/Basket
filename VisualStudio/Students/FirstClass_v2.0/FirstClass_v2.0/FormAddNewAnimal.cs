using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Windows.Forms;

namespace FirstClass_v2._0
{
    public partial class FormAddNewAnimal : Form
    {
        private Form1 form;

        public FormAddNewAnimal(Form1 form, string[] typeList)
        {
            InitializeComponent();

            this.form = form;
            comboBox1.Items.AddRange(typeList);
        }

        private void button1_Click(object sender, EventArgs e)
        {
            if (comboBox1.Text.Length > 0)
            {
                if (textBox1.Text.Length > 0)
                {
                    if (numericUpDown1.Value > 0 && numericUpDown1.Value < 70)
                    {
                        Animal animal = new Animal(textBox1.Text, comboBox1.Text, Convert.ToDouble(numericUpDown1.Value));
                        form.AddNewAnimal(animal);
                        this.Close();
                    }
                    else
                        MessageBox.Show("Проверьте возраст животного!", "Внимание!", MessageBoxButtons.OK, MessageBoxIcon.Error);
                }
                else
                    MessageBox.Show("Введите кличку животного!", "Внимание!", MessageBoxButtons.OK, MessageBoxIcon.Error);
            }
            else
                MessageBox.Show("Выберите тип животного!", "Внимание!", MessageBoxButtons.OK, MessageBoxIcon.Error);
        }
    }
}
