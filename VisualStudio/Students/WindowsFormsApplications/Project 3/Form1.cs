using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Windows.Forms;

namespace Project_3
{
    public partial class Form1 : Form
    {
        double cena = 300000;
        int posX, posY;

        public Form1()
        {
            InitializeComponent();

            label3.Text = cena + "р.";
            pictureBox1.Image = new Bitmap("tachka.png");
        }

        private void button1_Click(object sender, EventArgs e)
        {
            double result = cena;
            if (checkBox1.Checked)
                result += 10000;
            if (checkBox2.Checked)
                result += 30000;
            if (checkBox3.Checked)
                result += 50000;
            result *= trackBar1.Value;
            string resultMessage = "Цена с учетом Вашей комплектации\nСоставляет: ";
            textBox1.Text = resultMessage + result;
        }

        private void trackBar1_Scroll(object sender, EventArgs e)
        {
            toolTip1.Show(trackBar1.Value.ToString(), trackBar1, posX, posY - 20, 1000);
        }

        private void trackBar1_MouseMove(object sender, MouseEventArgs e)
        {
            //toolTip1.Show(trackBar1.Value.ToString(), trackBar1);
            posX = e.X;
            posY = e.Y;
        }
    }
}
