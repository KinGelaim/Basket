using System;
using System.Windows.Forms;

namespace ORC_Reports
{
    public partial class FormChoseYear : Form
    {
        public bool isExit = false;

        public FormChoseYear()
        {
            InitializeComponent();
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
