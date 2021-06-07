using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Windows.Forms;
using Microsoft.Win32;

namespace MyMiniUnistaller
{
    public partial class Form1 : Form
    {
        private string programmGUID = "2081f237-9479-4081-9831-3b574975dcf2";
        private string programmName = "MiniProgramm";

        public Form1()
        {
            InitializeComponent();
        }

        private void button1_Click(object sender, EventArgs e)
        {
            string registryLocation = @"Software\Microsoft\Windows\CurrentVersion\Uninstall";
            RegistryKey regKey = (Registry.LocalMachine).OpenSubKey(registryLocation, true);
            RegistryKey progKey = regKey.OpenSubKey(programmName);
            // Если у нас такая ветка реестра есть
            if (progKey != null)
            {
                // Удаляем данные о программе
                regKey.DeleteSubKey(programmName);
                MessageBox.Show("Программа была успешно удалена из панели управления!");
                // Здесь также можно реализовать удаление из папки.
            }
        }
    }
}
