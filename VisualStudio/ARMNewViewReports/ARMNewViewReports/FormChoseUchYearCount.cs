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
    public partial class FormChoseUchYearCount : Form
    {
        public bool isExit = false;
        public string year = "";
        public string uchNumber = "";
        public string count = "";

        public FormChoseUchYearCount()
        {
            InitializeComponent();

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
            this.Close();
        }
    }
}
