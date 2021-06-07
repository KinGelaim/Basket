using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Windows.Forms;

namespace ARM
{
    public partial class Form23 : Form
    {
        public bool exitAgree = false;
        public bool isMonth = false;

        public Form23()
        {
            InitializeComponent();
        }

        private void button1_Click(object sender, EventArgs e)
        {
            button1.Visible = false;
            button2.Visible = false;
            label1.Visible = true;
            numericUpDown1.Visible = true;
            button3.Visible = true;
            checkBox1.Visible = false;
        }

        private void button2_Click(object sender, EventArgs e)
        {
            isMonth = true;
            button1.Visible = false;
            button2.Visible = false;
            label1.Visible = true;
            numericUpDown1.Visible = true;
            label2.Visible = true;
            comboBox1.Visible = true;
            button3.Visible = true;
            checkBox1.Visible = false;
        }

        private void button3_Click(object sender, EventArgs e)
        {
            if (isMonth && comboBox1.Text.Length > 0 || !isMonth)
            {
                exitAgree = true;
                this.Close();
            }
        }
    }
}
