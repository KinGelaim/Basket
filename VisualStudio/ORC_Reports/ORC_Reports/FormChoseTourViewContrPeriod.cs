using System;
using System.Collections.Generic;
using System.Windows.Forms;

namespace ORC_Reports
{
    public partial class FormChoseTourViewContrPeriod : Form
    {
        public string counterpartieID = "";

        public bool isExit = false;

        private Dictionary<string, string> counterpartieList;

        public FormChoseTourViewContrPeriod()
        {
            InitializeComponent();

            counterpartieList = ClassBD.GetSipCounterparties();

            comboBox3.Items.Clear();
            comboBox3.Items.Add("Все контрагенты");
            foreach (KeyValuePair<string, string> keyValue in counterpartieList)
                comboBox3.Items.Add(keyValue.Key);
            comboBox3.SelectedIndex = 0;

            dateTimePicker1.Value = new DateTime(DateTime.Now.Year, DateTime.Now.Month, 1);
        }

        private void button1_Click(object sender, EventArgs e)
        {
            if (comboBox1.SelectedIndex >= 0)
            {
                if (comboBox2.SelectedIndex >= 0)
                {
                    if (comboBox3.SelectedIndex > 0)
                        counterpartieID = counterpartieList[comboBox3.Items[comboBox3.SelectedIndex].ToString()];
                    else
                        counterpartieID = "";
                    isExit = true;
                    this.Close();
                }
                else
                    MessageBox.Show("Выберите вид отчёта!");
            }
            else
                MessageBox.Show("Выберите тип отчёта!");
        }
    }
}
