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
    public partial class Form13 : Form
    {
        public bool exitAgree = false;
        public Form13()
        {
            InitializeComponent();
            exitAgree = false;
            numericUpDown1.Value = DateTime.Today.Year;
        }

        private void button3_Click(object sender, EventArgs e)
        {
            if (comboBox1.Text.Length > 0)
            {
                exitAgree = true;
                this.Close();
            }
        }
    }
}
