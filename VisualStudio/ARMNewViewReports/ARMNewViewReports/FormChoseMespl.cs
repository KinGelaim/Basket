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
    public partial class FormChoseMespl : Form
    {
        public bool isExit = false;
        public string mesac = "01";
        public string godr = "2020";
        public string nameMesac = "ЯНВАРЬ";
        public string uchNumber = "";
        public string countEkz = "1";

        public FormChoseMespl()
        {
            InitializeComponent();
            isExit = false;
        }

        private void button1_Click(object sender, EventArgs e)
        {
            switch (comboBox1.Text)
            {
                case "Январь":
                    mesac = "01";
                    break;
                case "Февраль":
                    mesac = "02";
                    break;
                case "Март":
                    mesac = "03";
                    break;
                case "Апрель":
                    mesac = "04";
                    break;
                case "Май":
                    mesac = "05";
                    break;
                case "Июнь":
                    mesac = "06";
                    break;
                case "Июль":
                    mesac = "07";
                    break;
                case "Август":
                    mesac = "08";
                    break;
                case "Сентябрь":
                    mesac = "09";
                    break;
                case "Октябрь":
                    mesac = "10";
                    break;
                case "Ноябрь":
                    mesac = "11";
                    break;
                case "Декабрь":
                    mesac = "12";
                    break;
                default:
                    MessageBox.Show("Неправильно выбран месяц!");
                    isExit = false;
                    return;
            }
            nameMesac = comboBox1.Text.ToUpper();
            godr = numericUpDown1.Value.ToString();
            uchNumber = textBox1.Text;
            countEkz = numericUpDown2.Value.ToString();
            isExit = true;
            this.Close();
        }
    }
}
