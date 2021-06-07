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
    public partial class Form15 : Form
    {
        public bool exitAgree = false;
        public bool monthOrYear;
        public bool planOrQuery;
        public Form15(bool monthOrYear, bool planOrQuery)
        {
            InitializeComponent();
            button1.Visible = true;
            button3.Visible = false;
            label3.Visible = false;
            label4.Visible = false;
            textBox1.Visible = false;
            textBox2.Visible = false;
            comboBox2.SelectedIndex = 0;
            comboBox2.Visible = false;
            numericUpDown1.Value = DateTime.Today.Year;
            exitAgree = false;
            this.monthOrYear = monthOrYear;
            this.planOrQuery = planOrQuery;
            if (monthOrYear)
            {
                label1.Visible = true;
                numericUpDown1.Visible = true;
                if (!planOrQuery)
                {
                    button1.Visible = false;
                    button3.Visible = true;
                }
            }
            else
            {
                if (planOrQuery)
                {
                    label1.Visible = true;
                    numericUpDown1.Visible = true;
                    label2.Visible = true;
                    comboBox1.Visible = true;
                }
                else
                {
                    label1.Visible = true;
                    numericUpDown1.Visible = true;
                    label2.Visible = true;
                    comboBox1.Visible = true;
                }
            }
        }

        private void button3_Click(object sender, EventArgs e)
        {
            if (!monthOrYear && comboBox1.Text.Length > 0)
            {
                exitAgree = true;
                this.Close();
            }
            if (monthOrYear)
            {
                exitAgree = true;
                this.Close();
            }
        }

        private void button1_Click(object sender, EventArgs e)
        {
            if (monthOrYear)
            {
                label1.Visible = false;
                numericUpDown1.Visible = false;
                label2.Visible = false;
                comboBox1.Visible = false;
                button3.Visible = true;
                button1.Visible = false;
                label3.Visible = true;
                label4.Visible = true;
                textBox1.Visible = true;
                textBox2.Visible = true;
                if (planOrQuery)
                    comboBox2.Visible = true;
            }
            else if (comboBox1.Text.Length > 0)
            {
                label1.Visible = false;
                numericUpDown1.Visible = false;
                label2.Visible = false;
                comboBox1.Visible = false;
                button3.Visible = true;
                button1.Visible = false;
                label3.Visible = true;
                label4.Visible = true;
                textBox1.Visible = true;
                textBox2.Visible = true;
                if (planOrQuery)
                    comboBox2.Visible = true;
            }
        }
    }
}
