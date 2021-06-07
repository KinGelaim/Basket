using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Windows.Forms;

namespace Project6
{
    public partial class Form1 : Form
    {
        public Form1()
        {
            InitializeComponent();

            if (Properties.Settings.Default.text.Length > 0)
                textBox1.Text = Properties.Settings.Default.text;

            this.Top = Properties.Settings.Default.top;
            this.Left = Properties.Settings.Default.left;

            this.Width = Properties.Settings.Default.width;
            this.Height = Properties.Settings.Default.height;

            button1.Focus();
        }

        private void button1_Click(object sender, EventArgs e)
        {
            Properties.Settings.Default.top = this.Top;
            Properties.Settings.Default.left = this.Left;
            Properties.Settings.Default.text = textBox1.Text;
            Properties.Settings.Default.width = this.Width;
            Properties.Settings.Default.height = this.Height;
            Properties.Settings.Default.Save();
        }
    }
}
