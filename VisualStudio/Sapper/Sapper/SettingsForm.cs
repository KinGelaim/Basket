using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Windows.Forms;

namespace Sapper
{
    public partial class SettingsForm : Form
    {
        public bool isExit;

        public SettingsForm()
        {
            InitializeComponent();
            isExit = false;
            switch (Properties.Settings.Default.level)
            {
                case "izi":
                    trackBar1.Value = 0;
                    break;
                case "lover":
                    trackBar1.Value = 1;
                    break;
                case "zadr":
                    trackBar1.Value = 2;
                    break;
                default:
                    trackBar1.Value = 0;
                    break;
            }
            if (Properties.Settings.Default.saveInBD)
                checkBox1.Checked = true;
            if (Properties.Settings.Default.saveInTextFile)
                checkBox2.Checked = true;
        }

        private void button1_Click(object sender, EventArgs e)
        {
            switch (trackBar1.Value)
            {
                case 0:
                    Properties.Settings.Default.level = "izi";
                    break;
                case 1:
                    Properties.Settings.Default.level = "lover";
                    break;
                case 2:
                    Properties.Settings.Default.level = "zadr";
                    break;
                default:
                    Properties.Settings.Default.level = "izi";
                    break;
            }
            if (checkBox1.Checked)
                Properties.Settings.Default.saveInBD = true;
            else
                Properties.Settings.Default.saveInBD = false;
            if (checkBox2.Checked)
                Properties.Settings.Default.saveInTextFile = true;
            else
                Properties.Settings.Default.saveInTextFile = false;
            isExit = true;
            this.Close();
        }
    }
}
