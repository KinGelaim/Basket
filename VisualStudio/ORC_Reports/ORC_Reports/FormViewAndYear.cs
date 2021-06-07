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
    public partial class FormViewAndYear : Form
    {
        public bool isExit = false;
        public FormViewAndYear()
        {
            InitializeComponent();

            List<string> viewList = ClassBD.GetSipViewContracts();

            comboBox1.Items.Clear();
            comboBox1.Items.Add("Все виды работ");
            foreach (string str in viewList)
                comboBox1.Items.Add(str);

            isExit = false;
        }

        private void button1_Click(object sender, EventArgs e)
        {
            if (comboBox2.SelectedIndex >= 0)
            {
                isExit = true;
                this.Close();
            }
            else
                MessageBox.Show("Выберите тип отчета!");
        }
    }
}
