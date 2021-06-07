using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Windows.Forms;

namespace ORC_Helper
{
    public partial class FormEnterThePassword : Form
    {
        public bool isExit = false;
        public string password = "";
        public FormEnterThePassword()
        {
            InitializeComponent();
            isExit = false;
        }

        private void button1_Click(object sender, EventArgs e)
        {
            password = textBox1.Text;
            isExit = true;
            this.Close();
        }

        private void textBox1_KeyUp(object sender, KeyEventArgs e)
        {
            if (e.KeyCode == Keys.Enter)
            {
                button1.PerformClick();
            }
        }

        private void textBox1_KeyPress(object sender, KeyPressEventArgs e)
        {

        }
    }
}
