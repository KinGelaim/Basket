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
    public partial class Form19 : Form
    {
        public bool exitAgree = false;
        public bool ispOrSb = true;
        public Form19(string nameForm, bool ispOrSb)
        {
            InitializeComponent();
            exitAgree = false;
            numericUpDown1.Value = DateTime.Today.Year;
            comboBox1.SelectedIndex = 0;
            label2.Visible = false;
            textBox1.Visible = false;
            this.ispOrSb = ispOrSb;
            if (!ispOrSb)
            {
                comboBox1.Visible = true;
            }
            this.Text = nameForm;
        }

        private void button3_Click(object sender, EventArgs e)
        {
            exitAgree = true;
            this.Close();
        }
    }
}
