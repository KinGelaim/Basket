using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Windows.Forms;

namespace ARMNewViewReports
{
    public partial class FormChoseUgolok : Form
    {
        public string uchNumber = "";
        public string countEkz = "";
        public string countList = "";
        public string HMD = "";
        public string author = "";
        public string date = "";

        public bool isExit = false;

        public FormChoseUgolok(string uchNumber = "", string countEkz = "", string countList = "")
        {
            InitializeComponent();

            textBox1.Text = "м.б. 22 - " + uchNumber + "с";
            textBox2.Text = "Отпечатано " + countEkz + " экз.";
            textBox3.Text = "На " + countList + " листах каждый";
            textBox4.Text = "ЖМД: уч. №144сс";
            textBox5.Text = "Исп. Ашихмина Л.Г.";
            textBox6.Text = string.Format("{0:00}/{1:00}/{2}", DateTime.Now.Day, DateTime.Now.Month, DateTime.Now.Year);
        }

        private void button1_Click(object sender, EventArgs e)
        {
            uchNumber = textBox1.Text;
            countEkz = textBox2.Text;
            countList = textBox3.Text;
            HMD = textBox4.Text;
            author = textBox5.Text;
            date = textBox6.Text;
            isExit = true;
            this.Close();
        }
    }
}
