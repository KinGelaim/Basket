using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Windows;
using System.Windows.Controls;
using System.Windows.Data;
using System.Windows.Documents;
using System.Windows.Input;
using System.Windows.Media;
using System.Windows.Media.Imaging;
using System.Windows.Shapes;

namespace BallisticWind
{
    /// <summary>
    /// Логика взаимодействия для SettingsWindow.xaml
    /// </summary>
    public partial class SettingsWindow : Window
    {
        public SettingsWindow()
        {
            InitializeComponent();

            cmbEncoding.SelectedIndex = 0;
            cmbCorrection.SelectedIndex = 0;
        }

        private void btnApply_Click(object sender, RoutedEventArgs e)
        {
            try
            {
                Properties.Settings.Default.encodingReport = ((ListBoxItem)cmbEncoding.SelectedItem).Content.ToString();
            }
            catch
            {
                Properties.Settings.Default.encodingReport = "DOS";
            }
            try
            {
                if (((ListBoxItem)cmbCorrection.SelectedItem).Content.ToString() == "Поправки Камы")
                    Properties.Settings.Default.correctionKama = "1";
                if (((ListBoxItem)cmbCorrection.SelectedItem).Content.ToString() == "Поправка 5 градусов")
                    Properties.Settings.Default.correctionKama = "2";
            }
            catch
            {
                Properties.Settings.Default.correctionKama = "";
            }
            Properties.Settings.Default.Save();
            this.Close();
        }
    }
}
