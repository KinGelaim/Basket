using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using System.Windows.Forms;

namespace ReportsOnGOZ
{
    public partial class FormSettings : Form
    {
        public FormSettings()
        {
            InitializeComponent();

            textBox1.Text = Properties.Settings.Default.name.Replace("&quot;", "\"");
            textBox2.Text = Properties.Settings.Default.INN;
            textBox3.Text = Properties.Settings.Default.KPP;
            textBox4.Text = Properties.Settings.Default.nameSign;
        }

        private void button1_Click(object sender, EventArgs e)
        {
            if (textBox1.Text.Length > 0)
            {
                if (textBox2.Text.Length > 0)
                {
                    if (textBox3.Text.Length > 0)
                    {
                        if (textBox4.Text.Length > 0)
                        {
                            Properties.Settings.Default.name = textBox1.Text.Replace("\"", "&quot;");
                            Properties.Settings.Default.INN = textBox2.Text;
                            Properties.Settings.Default.KPP = textBox3.Text;
                            Properties.Settings.Default.nameSign = textBox4.Text;
                            Properties.Settings.Default.Save();
                            this.Close();
                        }
                        else
                            MessageBox.Show("Введите определяющее слово для вызова подписи", "Внимание", MessageBoxButtons.OK, MessageBoxIcon.Error);
                    }
                    else
                        MessageBox.Show("Введите КПП организации!", "Внимание!", MessageBoxButtons.OK, MessageBoxIcon.Error);
                }
                else
                    MessageBox.Show("Введите ИНН организации!", "Внимание!", MessageBoxButtons.OK, MessageBoxIcon.Error);
            }
            else
                MessageBox.Show("Введите название организации!", "Внимание!", MessageBoxButtons.OK, MessageBoxIcon.Error);
        }
    }
}
