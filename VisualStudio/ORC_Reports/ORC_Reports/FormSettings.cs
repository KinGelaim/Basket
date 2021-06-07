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
    public partial class FormSettings : Form
    {
        public FormSettings()
        {
            InitializeComponent();
        }

        //Изменении настроек для автообновления
        private void checkBox1_CheckedChanged(object sender, EventArgs e)
        {
            if (checkBox1.Checked)
                Properties.Settings.Default.IsAutoUpdate = true;
            else
                Properties.Settings.Default.IsAutoUpdate = false;
            Properties.Settings.Default.Save();
        }

        //Выбор директории для сохранения отчетов
        private void button1_Click(object sender, EventArgs e)
        {
            folderBrowserDialog1.ShowDialog();
            if (folderBrowserDialog1.SelectedPath.Length > 0)
            {
                Properties.Settings.Default.ReportPath = folderBrowserDialog1.SelectedPath + @"\";
                Properties.Settings.Default.Save();
                MessageBox.Show("Сохранен новый путь!\n" + folderBrowserDialog1.SelectedPath + @"\");
            }
            folderBrowserDialog1.SelectedPath = "";
        }
    }
}
