using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Windows.Forms;

namespace ARMNewViewReports
{
    public partial class FormChoseRashPreg : Form
    {
        public bool isExit = false;
        public int typePreg = 0;
        public string year = "";
        public string uchNumber = "";
        public string count = "";

        public FormChoseRashPreg()
        {
            InitializeComponent();

            isExit = false;
            typePreg = 0;

            year = DateTime.Now.Year.ToString();
            uchNumber = "";
            count = "1";

            numericUpDown2.Value = Convert.ToInt32(year);
        }

        private void button1_Click(object sender, EventArgs e)
        {
            year = Convert.ToString(numericUpDown2.Value);
            uchNumber = textBox1.Text;
            count = Convert.ToString(numericUpDown1.Value);
            isExit = true;
            typePreg = 0;
            this.Close();
        }

        private void button2_Click(object sender, EventArgs e)
        {
            year = Convert.ToString(numericUpDown2.Value);
            uchNumber = textBox1.Text;
            count = Convert.ToString(numericUpDown1.Value);
            isExit = true;
            typePreg = 1;
            this.Close();
        }

        private void button3_Click(object sender, EventArgs e)
        {
            year = Convert.ToString(numericUpDown2.Value);
            uchNumber = textBox1.Text;
            count = Convert.ToString(numericUpDown1.Value);
            isExit = true;
            typePreg = 2;
            this.Close();
        }

        private void button4_Click(object sender, EventArgs e)
        {
            year = Convert.ToString(numericUpDown2.Value);
            uchNumber = textBox1.Text;
            count = Convert.ToString(numericUpDown1.Value);
            isExit = true;
            typePreg = 3;
            this.Close();
        }
    }
}
