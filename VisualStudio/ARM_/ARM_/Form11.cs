using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using System.Windows.Forms;

namespace ARM
{
    public partial class Form11 : Form
    {
        public bool strFunction = true;
        public bool exitAgree = false;
        public Form11()
        {
            InitializeComponent();
            exitAgree = false;
            numericUpDown1.Value = DateTime.Today.Year;
        }

        private void button1_Click(object sender, EventArgs e)
        {
            label1.Visible = true;
            numericUpDown1.Visible = true;
            button3.Visible = true;
            button1.Visible = false;
            button2.Visible = false;
            strFunction = true;
        }

        private void button2_Click(object sender, EventArgs e)
        {
            label1.Visible = true;
            numericUpDown1.Visible = true;
            label2.Visible = true;
            comboBox1.Visible = true;
            button3.Visible = true;
            button1.Visible = false;
            button2.Visible = false;
            strFunction = false;
        }

        private void button3_Click(object sender, EventArgs e)
        {
            if (!strFunction && comboBox1.Text.Length > 0 || strFunction)
            {
                exitAgree = true;
                this.Close();
            }
        }
    }
}
