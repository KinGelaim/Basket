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
    public partial class FormSettings : Form
    {
        public FormSettings()
        {
            InitializeComponent();
        }

        //Выбрать директорию OTRASL
        private void button1_Click(object sender, EventArgs e)
        {
            folderBrowserDialog1.ShowDialog();
            if (folderBrowserDialog1.SelectedPath.Length > 0)
            {
                Properties.Settings.Default.OtraslPath = folderBrowserDialog1.SelectedPath;
                Properties.Settings.Default.Save();
                MessageBox.Show("Сохранен новый путь!\n" + Properties.Settings.Default.OtraslPath);
            }
            folderBrowserDialog1.SelectedPath = "";
        }

        //Выбрать поддиректорию RAHGOD
        private void button3_Click(object sender, EventArgs e)
        {
            folderBrowserDialog1.ShowDialog();
            if (folderBrowserDialog1.SelectedPath.Length > 0)
            {
                string[] prArr = folderBrowserDialog1.SelectedPath.Split('\\');
                Properties.Settings.Default.RashGodFolderPath = prArr[prArr.Length - 1];
                Properties.Settings.Default.Save();
                MessageBox.Show("Сохранен новая поддиректория!\n" + Properties.Settings.Default.RashGodFolderPath);
            }
            folderBrowserDialog1.SelectedPath = "";
        }

        //Выбрать директорию OTRASL1
        private void button4_Click(object sender, EventArgs e)
        {
            folderBrowserDialog1.ShowDialog();
            if (folderBrowserDialog1.SelectedPath.Length > 0)
            {
                Properties.Settings.Default.OtraslMesplPath = folderBrowserDialog1.SelectedPath;
                Properties.Settings.Default.Save();
                MessageBox.Show("Сохранен новый путь!\n" + Properties.Settings.Default.OtraslMesplPath);
            }
            folderBrowserDialog1.SelectedPath = "";
        }

        //Выбрать поддиректорию KONTROL
        private void button5_Click(object sender, EventArgs e)
        {
            folderBrowserDialog1.ShowDialog();
            if (folderBrowserDialog1.SelectedPath.Length > 0)
            {
                string[] prArr = folderBrowserDialog1.SelectedPath.Split('\\');
                Properties.Settings.Default.KontrolFolderPath = prArr[prArr.Length - 1];
                Properties.Settings.Default.Save();
                MessageBox.Show("Сохранен новая поддиректория!\n" + Properties.Settings.Default.KontrolFolderPath);
            }
            folderBrowserDialog1.SelectedPath = "";
        }

        //Выбрать файл С=>НС
        private void button6_Click(object sender, EventArgs e)
        {
            OpenFileDialog ofd = new OpenFileDialog();
            ofd.ShowDialog();
            if (ofd.FileName.Length > 0)
            {
                Properties.Settings.Default.SNSPath = ofd.FileName;
                Properties.Settings.Default.Save();
                MessageBox.Show("Сохранен новый файл!\n" + Properties.Settings.Default.SNSPath);
            }
        }

        // Выбрать директорию MATER
        private void button11_Click(object sender, EventArgs e)
        {
            folderBrowserDialog1.ShowDialog();
            if (folderBrowserDialog1.SelectedPath.Length > 0)
            {
                Properties.Settings.Default.MaterFolderPath = folderBrowserDialog1.SelectedPath;
                Properties.Settings.Default.Save();
                MessageBox.Show("Сохранен новый путь!\n" + Properties.Settings.Default.MaterFolderPath);
            }
            folderBrowserDialog1.SelectedPath = "";
        }

        // Выбрать поддиректорию MATER
        // TODO: такого разделения ещё не существует
        private void button12_Click(object sender, EventArgs e)
        {

        }

        //Директория для отчётов
        private void button10_Click(object sender, EventArgs e)
        {
            folderBrowserDialog1.ShowDialog();
            if (folderBrowserDialog1.SelectedPath.Length > 0)
            {
                Properties.Settings.Default.PathForReports = folderBrowserDialog1.SelectedPath;
                Properties.Settings.Default.Save();
                MessageBox.Show("Сохранен новый путь для отчётов!\n" + Properties.Settings.Default.PathForReports);
            }
            folderBrowserDialog1.SelectedPath = "";
        }

        //Путь к проводнику
        private void button9_Click(object sender, EventArgs e)
        {
            OpenFileDialog ofd = new OpenFileDialog();
            ofd.ShowDialog();
            if (ofd.FileName.Length > 0)
            {
                Properties.Settings.Default.PathForExplorer = ofd.FileName;
                Properties.Settings.Default.Save();
                MessageBox.Show("Сохранен новый Explorer!\n" + Properties.Settings.Default.PathForExplorer);
            }
        }

        //Директория для Н
        private void button7_Click(object sender, EventArgs e)
        {
            folderBrowserDialog1.ShowDialog();
            if (folderBrowserDialog1.SelectedPath.Length > 0)
            {
                Properties.Settings.Default.PathForN = folderBrowserDialog1.SelectedPath;
                Properties.Settings.Default.Save();
                MessageBox.Show("Сохранен новая директория Н!\n" + Properties.Settings.Default.PathForN);
            }
            folderBrowserDialog1.SelectedPath = "";
        }

        //Директория для С
        private void button8_Click(object sender, EventArgs e)
        {
            folderBrowserDialog1.ShowDialog();
            if (folderBrowserDialog1.SelectedPath.Length > 0)
            {
                Properties.Settings.Default.PathForS = folderBrowserDialog1.SelectedPath;
                Properties.Settings.Default.Save();
                MessageBox.Show("Сохранен новая директория С!\n" + Properties.Settings.Default.PathForS);
            }
            folderBrowserDialog1.SelectedPath = "";
        }

        //Выход
        private void button2_Click(object sender, EventArgs e)
        {
            this.Close();
        }
    }
}
