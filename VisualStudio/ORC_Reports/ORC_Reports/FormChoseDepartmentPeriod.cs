using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Windows.Forms;

namespace ORC_Reports
{
    public partial class FormChoseDepartmentPeriod : Form
    {
        public string departmentID = "";

        public bool isExit = false;

        private Dictionary<string, string> departmentList;

        public FormChoseDepartmentPeriod()
        {
            InitializeComponent();

            departmentList = ClassBD.GetDepartments();

            comboBox2.Items.Clear();
            comboBox2.Items.Add("Все подразделения");
            foreach (KeyValuePair<string, string> keyValue in departmentList)
                comboBox2.Items.Add(keyValue.Key);
            comboBox2.SelectedIndex = 0;

            dateTimePicker1.Value = new DateTime(DateTime.Now.Year, 1, 1);
        }

        private void button1_Click(object sender, EventArgs e)
        {
            if (comboBox1.SelectedIndex >= 0)
            {
                if (comboBox2.SelectedIndex > 0)
                    departmentID = departmentList[comboBox2.Items[comboBox2.SelectedIndex].ToString()];
                else
                    departmentID = "";
                isExit = true;
                this.Close();
            }
            else
                MessageBox.Show("Выберите тип отчёта!");
        }
    }
}
