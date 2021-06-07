using System;
using System.Collections.Generic;
using System.Linq;
using System.Runtime.InteropServices;
using System.Text;
using System.Windows;
using System.Windows.Controls;
using System.Windows.Data;
using System.Windows.Documents;
using System.Windows.Forms;
using System.Windows.Input;
using System.Windows.Media;
using System.Windows.Media.Imaging;
using System.Windows.Shapes;
using System.Threading.Tasks;

namespace ParentControlOfTheInternetOnALocalNetwork
{
    /// <summary>
    /// Логика взаимодействия для SettingsWindow.xaml
    /// </summary>
    public partial class SettingsWindow : Window
    {
        public SettingsWindow()
        {
            InitializeComponent();

            MainGrid.Visibility = System.Windows.Visibility.Visible;
            LoginAndPasswordGrid.Visibility = System.Windows.Visibility.Hidden;
        }

        //Меняем директорию проектов
        private void ChangePathOfDirectory_Click(object sender, RoutedEventArgs e)
        {
            FolderBrowserDialog fbd = new FolderBrowserDialog();
            fbd.ShowDialog();
            if (fbd.SelectedPath.Length > 0)
            {
                Properties.Settings.Default.directoryPathProject = fbd.SelectedPath + @"\";
                Properties.Settings.Default.Save();
                System.Windows.MessageBox.Show("Сохранен новый путь!\n" + fbd.SelectedPath + @"\");
            }
            fbd.SelectedPath = "";
        }

        //Кнопка для отображения консоли
        private void CreateConsole_Click(object sender, RoutedEventArgs e)
        {
            Task.Factory.StartNew(Console);
        }

        private void Console()
        {
            //Запускаем консоль
            if (AllocConsole())
            {
                System.Console.WriteLine("Для выхода наберите exit");
                while (true)
                {
                    string output = System.Console.ReadLine();
                    if (output == "exit")
                        break;
                }
                FreeConsole();
            }
        }

        //Используем WinApi
        [DllImport("kernel32.dll", SetLastError = true)]
        [return: MarshalAs(UnmanagedType.Bool)]
        private static extern bool AllocConsole();

        [DllImport("kernel32.dll", SetLastError = true)]
        [return: MarshalAs(UnmanagedType.Bool)]
        private static extern bool FreeConsole();
    }
}
