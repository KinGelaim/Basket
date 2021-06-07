using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Windows.Forms;

namespace Project5
{
    public partial class Form1 : Form
    {
        int m, s, ms;

        int m2, s2;

        public Form1()
        {
            InitializeComponent();

            //Секундомер
            timer1.Enabled = false;
            timer1.Interval = 1; //1 миллисекунда

            m = 0; s = 0; ms = 0;
            label1.Text = "00:00:00";

            button1.Enabled = true;
            button2.Enabled = false;

            //Таймер
            timer2.Enabled = false;
            timer2.Interval = 1000; //1 секунда

            label2.Text = "00:00";
            m2 = 0; s2 = 0;

        }

        //Пуск/стоп
        private void button1_Click(object sender, EventArgs e)
        {
            if (timer1.Enabled)
            {
                timer1.Enabled = false;
                button1.Text = "Пуск";
                button2.Enabled = true;
            }
            else
            {
                timer1.Enabled = true;
                button1.Text = "Стоп";
                button2.Enabled = false;
            }
        }

        //Сброс секундомера
        private void button2_Click(object sender, EventArgs e)
        {
            m = 0; s = 0; ms = 0;
            label1.Text = "00:00:00";
        }

        //Таймер для секундомера
        private void timer1_Tick(object sender, EventArgs e)
        {
            string time = "";
            if (ms < 59)
            {
                ms++;
            }
            else
            {
                ms = 0;
                if (s < 59)
                {
                    s++;
                }
                else
                {
                    s = 0;
                    m++;
                }
            }
            if (m < 10)
                time += "0" + m;
            else
                time += m;
            time += ":";
            if (s < 10)
                time += "0" + s;
            else
                time += s;
            time += ":";
            if (ms < 10)
                time += "0" + ms;
            else
                time += ms;
            label1.Text = time;
        }

        //Старт таймера
        private void button3_Click(object sender, EventArgs e)
        {
            m2 = Convert.ToInt32(numericUpDown1.Value);
            s2 = Convert.ToInt32(numericUpDown2.Value);
            timer2.Enabled = true;
            button3.Enabled = false;
        }

        //Таймер для таймера (О_о)
        private void timer2_Tick(object sender, EventArgs e)
        {
            if (s2 > 0)
            {
                s2--;
            }
            else
            {
                if (m2 > 0)
                {
                    m2--;
                    s2 = 59;
                }
            }
            if(s2 == 0 && m2 == 0)
            {
                timer2.Enabled = false;
                button3.Enabled = true;
                MessageBox.Show("Время вышло!");
            }
            string text = "";
            if (m2 < 10)
                text += "0" + m2;
            else
                text += m2;
            text += ":";
            if (s2 < 10)
                text += "0" + s2;
            else
                text += s2;
            label2.Text = text;
        }

        private void exitToolStripMenuItem_Click(object sender, EventArgs e)
        {
            Application.Exit();
        }

        private void aboutTheProgrammToolStripMenuItem_Click(object sender, EventArgs e)
        {
            AboutBox newAboutBox = new AboutBox();
            newAboutBox.ShowDialog();
        }
    }
}
