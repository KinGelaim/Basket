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
    public partial class FormChoseUchAndCount : Form
    {
        public bool isExit = false;
        public string uchNumber = "";
        public string countEkz = "1";

        public FormChoseUchAndCount()
        {
            InitializeComponent();
            isExit = false;
        }

        private void button1_Click(object sender, EventArgs e)
        {
            uchNumber = textBox1.Text;
            countEkz = numericUpDown1.Value.ToString();
            isExit = true;
            this.Close();
        }
    }
}
