using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Windows.Forms;

namespace Alarm
{
    public partial class FormAlarm : Form
    {
        private DateTime alarm;

        public FormAlarm()
        {
            InitializeComponent();

            numericUpDown1.Value = DateTime.Now.Hour;
            numericUpDown2.Value = DateTime.Now.Minute;

            notifyIcon1.Visible = false;
            notifyIcon1.Text = "Будильник не установлен!";

            timer1.Interval = 1000;
            timer1.Enabled = true;

            label2.Text = DateTime.Now.ToLongTimeString();
        }

        private void timer1_Tick(object sender, EventArgs e)
        {
            label2.Text = DateTime.Now.ToLongTimeString();

            if (checkBox1.Checked)
            {
                if (DateTime.Compare(DateTime.Now, alarm) > 0)
                {
                    checkBox1.Checked = false;

                    FormText text = new FormText(DateTime.Now.ToShortTimeString(),this.textBox1.Text);
                    text.ShowDialog();

                    notifyIcon1.Visible = false;

                    this.Show();
                }
            }
        }

        private void checkBox1_CheckedChanged(object sender, EventArgs e)
        {
            if (checkBox1.Checked)
            {
                numericUpDown1.Enabled = false;
                numericUpDown2.Enabled = false;
                textBox1.Enabled = false;

                alarm = new DateTime(DateTime.Now.Year, DateTime.Now.Month, DateTime.Now.Day, Convert.ToInt32(numericUpDown1.Value), Convert.ToInt32(numericUpDown2.Value), 0, 0);

                if (DateTime.Compare(DateTime.Now, alarm) > 0)
                    alarm = alarm.AddDays(1);

                notifyIcon1.Text = "Будильник на " + alarm.ToShortTimeString() + "\nТекст: " + textBox1.Text;

                button1.Enabled = true;
            }
            else
            {
                numericUpDown1.Enabled = true;
                numericUpDown2.Enabled = true;
                textBox1.Enabled = true;
                notifyIcon1.Text = "Будильник не установлен!";

                button1.Enabled = false;
            }
        }

        private void button1_Click(object sender, EventArgs e)
        {
            this.Hide();
            notifyIcon1.Visible = true;
        }

        private void hideShowToolStripMenuItem_Click(object sender, EventArgs e)
        {
            if (this.Visible)
            {
                this.Hide();
                notifyIcon1.Visible = true;
            }
            else
            {
                this.Show();
                notifyIcon1.Visible = false;
            }
        }

        private void exitToolStripMenuItem_Click(object sender, EventArgs e)
        {
            this.Close();
        }
    }
}
