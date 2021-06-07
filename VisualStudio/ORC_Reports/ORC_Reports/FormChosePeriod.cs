using System;
using System.Windows.Forms;

namespace ORC_Reports
{
    public partial class FormChosePeriod : Form
    {
        public bool isExit = false;

        public FormChosePeriod()
        {
            InitializeComponent();

            dateTimePicker1.Value = new DateTime(DateTime.Now.Year, DateTime.Now.Month, 1);
        }

        private void button1_Click(object sender, EventArgs e)
        {
            if (comboBox1.SelectedIndex >= 0)
            {
                isExit = true;
                this.Close();
            }
            else
                MessageBox.Show("Выберите тип отчета!");
        }
    }
}
