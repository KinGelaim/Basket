using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Windows.Forms;

namespace Ant_Search_Algorithm
{
    public partial class FormCreateLength : Form
    {
        public bool is_exit = false;

        public FormCreateLength()
        {
            InitializeComponent();
            is_exit = false;
        }

        private void button1_Click(object sender, EventArgs e)
        {
            if (textBox1.Text.Length > 0)
            {
                is_exit = true;
                this.Close();
            }
        }
    }
}
