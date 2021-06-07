using System;
using System.Collections.Generic;
using System.Windows.Forms;


namespace ORC_Reports
{
    public partial class FormChoseContrViewSelected : Form
    {
        public string counterpartieID = "";

        public List<string> viewsNames = new List<string>();

        public bool isExit = false;

        private Dictionary<string, string> counterpartieList;
        private List<string> views;

        public FormChoseContrViewSelected()
        {
            InitializeComponent();

            counterpartieList = ClassBD.GetSipCounterparties();

            comboBox3.Items.Clear();
            comboBox3.Items.Add("Все контрагенты");
            foreach (KeyValuePair<string, string> keyValue in counterpartieList)
                comboBox3.Items.Add(keyValue.Key);
            comboBox3.SelectedIndex = 0;

            views = ClassBD.GetSipViewContracts();
            listBox1.Items.Clear();
            foreach (string str in views)
                listBox1.Items.Add(str);
        }

        private void button1_Click(object sender, EventArgs e)
        {
            if (comboBox1.SelectedIndex >= 0)
            {
                if (listBox1.SelectedItems.Count > 0)
                {
                    foreach (var sel in listBox1.SelectedItems)
                        viewsNames.Add(sel.ToString());

                    if (comboBox3.SelectedIndex > 0)
                        counterpartieID = counterpartieList[comboBox3.Items[comboBox3.SelectedIndex].ToString()];
                    else
                        counterpartieID = "";

                    isExit = true;
                    this.Close();
                }
                else
                    MessageBox.Show("Выберите вид отчёта");
            }
            else
                MessageBox.Show("Выберите тип отчёта!");
        }
    }
}
