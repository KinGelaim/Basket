using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Windows.Forms;

namespace Project_2
{
    public partial class Form1 : Form
    {
        public Form1()
        {
            InitializeComponent();
        }

        private void button1_Click(object sender, EventArgs e)
        {
            double cena = 8;
            if (radioButton1.Checked)
                cena = 8;
            if (radioButton2.Checked)
                cena = 18;
            if (radioButton3.Checked)
                cena = 28;
            double count = Math.Ceiling(Convert.ToDouble(textBox1.Text));
            double result = cena * count;
            textBox2.Text = "Цена за " + count + " фото\nСоставляет: " + result;
        }
    }
}
