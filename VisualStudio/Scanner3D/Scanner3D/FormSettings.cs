using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Windows.Forms;

namespace Scanner3D
{
    public partial class FormSettings : Form
    {
        public bool isExit = false;
        public FormSettings()
        {
            InitializeComponent();

            numericUpDown1.Value = Convert.ToDecimal(Properties.Settings.Default.radius);

            checkBox1.Checked = Properties.Settings.Default.animation;

            isExit = false;
        }

        private void button1_Click(object sender, EventArgs e)
        {
            Properties.Settings.Default.animation = checkBox1.Checked;
            Properties.Settings.Default.radius = Convert.ToInt32(numericUpDown1.Value);
            Properties.Settings.Default.Save();
            isExit = true;
            this.Close();
        }
    }
}
