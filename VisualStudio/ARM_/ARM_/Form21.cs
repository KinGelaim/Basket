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
    public partial class Form21 : Form
    {
        public bool exitAgree = false;
        public int typePrint;

        public Form21(bool typePrint, bool secondTypePrint)
        {
            InitializeComponent();
            exitAgree = false;
            if (typePrint && secondTypePrint)
            {
                button1.Visible = true;
                button2.Visible = true;
            }
            else if (!typePrint && secondTypePrint)
            {
                button3.Visible = true;
                button4.Visible = true;
                button5.Visible = true;
                button5.Focus();
            }
            else if (typePrint && !secondTypePrint)
            {
                button7.Visible = true;
                button8.Visible = true;
            }
            else
            {
                label3.Visible = true;
                label4.Visible = true;
                textBox1.Visible = true;
                textBox2.Visible = true;
                button6.Visible = true;
            }
        }

        private void button1_Click(object sender, EventArgs e)
        {
            functionExit(1);
        }

        private void button2_Click(object sender, EventArgs e)
        {
            functionExit(2);
        }

        private void button3_Click(object sender, EventArgs e)
        {
            functionExit(3);
        }

        private void button4_Click(object sender, EventArgs e)
        {
            functionExit(2);
        }

        private void button5_Click(object sender, EventArgs e)
        {
            functionExit(1);
        }

        private void functionExit(int typik)
        {
            this.typePrint = typik;
            label3.Visible = true;
            label4.Visible = true;
            textBox1.Visible = true;
            textBox2.Visible = true;
            button6.Visible = true;
            button1.Visible = false;
            button2.Visible = false;
            button3.Visible = false;
            button4.Visible = false;
            button5.Visible = false;
            button7.Visible = false;
            button8.Visible = false;
        }

        private void button6_Click(object sender, EventArgs e)
        {
            if (textBox2.Text.Length > 0)
            {
                exitAgree = true;
                this.Close();
            }
        }

        private void button7_Click(object sender, EventArgs e)
        {
            functionExit(1);
        }

        private void button8_Click(object sender, EventArgs e)
        {
            functionExit(2);
        }
    }
}
