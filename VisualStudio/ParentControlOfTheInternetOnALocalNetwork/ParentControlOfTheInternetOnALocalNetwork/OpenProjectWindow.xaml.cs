using System;
using System.Collections.Generic;
using System.Windows;
using System.IO;

namespace ParentControlOfTheInternetOnALocalNetwork
{
    /// <summary>
    /// Логика взаимодействия для OpenProjectWindow.xaml
    /// </summary>
    public partial class OpenProjectWindow : Window
    {
        public bool isExit = false;
        public string nameProject = "";

        public OpenProjectWindow()
        {
            InitializeComponent();

            isExit = false;
            nameProject = "";

            LoadNamesAllProjects();
        }

        //Получаем имена всех проектов
        private void LoadNamesAllProjects()
        {
            if (Directory.Exists(Properties.Settings.Default.directoryPathProject))
            {
                cmbProjects.Items.Clear();
                IEnumerable<string> allNamesProjects = Directory.EnumerateFiles(Properties.Settings.Default.directoryPathProject);
                foreach (string str in allNamesProjects)
                {
                    string[] k = str.Split('\\');
                    string[] item = k[k.Length - 1].Split('.');
                    cmbProjects.Items.Add(item[0]);
                }
            }
            else
                MessageBox.Show("Не удаётся найти директорию пректов!\nПроверьте настройки!", "Ошибка!", MessageBoxButton.OK, MessageBoxImage.Error);
        }

        //Кнопка закрытие текущего окна и сохранение имени проекта
        private void btnLoadProject_Click(object sender, RoutedEventArgs e)
        {
            isExit = true;
            nameProject = cmbProjects.SelectedItem.ToString();
            this.Close();
        }
    }
}
