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
    public partial class Form14 : Form
    {
        public bool exitAgree = false;

        public Form14()
        {
            InitializeComponent();
            textBox3.Text = Convert.ToString(DateTime.Now.Date).Split(' ')[0];
            textBox1.Focus();
            exitAgree = false;
        }

        private void button1_Click(object sender, EventArgs e)
        {
            if (textBox1.Text.Length > 0 && textBox3.Text.Length > 0)
            {
                exitAgree = true;
                this.Close();
            }
        }
    }
}
