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
using System.Windows.Navigation;
using System.Windows.Shapes;
using System.IO;

namespace ParentControlOfTheInternetOnALocalNetwork
{

    /// <summary>
    /// Логика взаимодействия для MainWindow.xaml
    /// </summary>
    public partial class MainWindow : Window
    {
        private Project project;
        private int currentGrid = 1;

        public MainWindow()
        {
            InitializeComponent();

            LoginContainer.Visibility = Visibility.Visible;
            MainContainer.Visibility = Visibility.Hidden;

            project = new Project();
        }

        #region ВЕРХНЕЕ МЕНЮ

        //Новая рабочая область
        private void MenuItemNewProject_Click(object sender, RoutedEventArgs e)
        {
            project = new Project();
            ShowMainComputer();
            ShowWorkComputers();
        }

        //Открытие рабочей области
        private void MenuItemOpenProject_Click(object sender, RoutedEventArgs e)
        {
            OpenProjectWindow opw = new OpenProjectWindow();
            opw.ShowDialog();
            if (opw.isExit)
            {
                if (File.Exists(Properties.Settings.Default.directoryPathProject + @"\" + opw.nameProject + ".parentproject"))
                {
                    project = project.Load(Properties.Settings.Default.directoryPathProject + @"\" + opw.nameProject + ".parentproject");
                    ShowMainComputer();
                    ShowWorkComputers();
                }
                else
                    MessageBox.Show("Файл не найден!", "Ошибка!", MessageBoxButton.OK, MessageBoxImage.Error);
            }
        }

        //Сохранение рабочей области
        private void MenuItemSaveProject_Click(object sender, RoutedEventArgs e)
        {
            if (Properties.Settings.Default.directoryPathProject.Length == 0 || Properties.Settings.Default.directoryPathProject == "")
            {
                if (MessageBoxResult.OK == MessageBox.Show("Директория не обнаружена!\nЖелаете её создать?", "Подтверждение", MessageBoxButton.OKCancel, MessageBoxImage.Information))
                {
                    Directory.CreateDirectory("Projects");
                    Properties.Settings.Default.directoryPathProject = Directory.GetCurrentDirectory() + @"\Projects\";
                    Properties.Settings.Default.Save();
                }
            }
            if (Directory.Exists(Properties.Settings.Default.directoryPathProject))
            {
                if (File.Exists(System.IO.Directory.GetCurrentDirectory() + @"\Newtonsoft.Json.dll"))
                {
                    if (project.Save(Properties.Settings.Default.directoryPathProject))
                        MessageBox.Show("Проект успешно сохранен!");
                    else
                        MessageBox.Show("Возникли сложности с сохранением проекта!");
                }
                else
                    if (MessageBoxResult.OK == MessageBox.Show("Не найдена библиотека dll для работы с проектами!\nЖелаете её распаковать?", "Подтверждение", MessageBoxButton.OKCancel, MessageBoxImage.Information))
                    {
                        byte[] array = Properties.Resources.Newtonsoft_Json;
                        FileStream fs = new FileStream("Newtonsoft.Json.dll", FileMode.Create);
                        fs.Write(array, 0, array.Length);
                        fs.Close();
                    }
            }
            else
                MessageBox.Show("Не найдена директория для проектов!\nПроверьте настройки!", "Ошибка!", MessageBoxButton.OK, MessageBoxImage.Error);
        }

        //Выход из программы
        private void MenuItemExitBtn_Click(object sender, RoutedEventArgs e)
        {
            this.Close();
        }

        //Настройки
        private void MenuItemSettings_Click(object sender, RoutedEventArgs e)
        {
            SettingsWindow sw = new SettingsWindow();
            sw.ShowDialog();
        }

        //О программе
        private void MenuItemAboutTheProgramm_Click(object sender, RoutedEventArgs e)
        {
            AboutTheProgramm formAbount = new AboutTheProgramm();
            formAbount.ShowDialog();
        }

        #endregion

        #region АВТОРИЗАЦИЯ

        private void btnAuth_Click(object sender, RoutedEventArgs e)
        {
            if (Properties.Settings.Default.login == textBoxLogin.Text && Properties.Settings.Default.password == textBoxPassword.Password)
            {
                LoginContainer.Visibility = Visibility.Hidden;
                MainContainer.Visibility = Visibility.Visible;
            }
            else
                MessageBox.Show("Неверно введён логин или пароль!", "Ошибка", MessageBoxButton.OK, MessageBoxImage.Error);
        }

        #endregion

        #region ОСНОВНОЙ КОМПЬЮТЕР

        //Настроить основной компьютер
        private void btnSettingMainComputer_Click(object sender, RoutedEventArgs e)
        {
            ComputerWindow computerBox = new ComputerWindow(project.mainComputer);
            computerBox.ShowDialog();
            project.mainComputer = computerBox.getComputer();
            ShowMainComputer();
        }

        private void ShowMainComputer()
        {
            if (project.mainComputer != null)
            {
                lblMainCompName.Content = project.mainComputer.name;
                lblMainCompIP.Content = "IP: " + project.mainComputer.ip;
            }
            else
            {
                lblMainCompName.Content = "";
                lblMainCompIP.Content = "";
            }
        }

        #endregion

        #region РАБОЧИЕ КОМПЬЮТЕРЫ

        //Добавить рабочий комп
        private void btnAddNewWorkComputer_Click(object sender, RoutedEventArgs e)
        {
            ComputerWindow computerBox = new ComputerWindow();
            computerBox.ShowDialog();
            Computer workComputer = computerBox.getComputer();
            if (workComputer != null)
            {
                project.computers.Add(workComputer);
            }
            ShowWorkComputers();
        }

        //Отобразить рабочие компы
        private void ShowWorkComputers()
        {
            //Очищаем грид
            FirstWorkStackPanel.Children.Clear();         //Удаляет всех детей контейнера
            SecondWorkStackPanel.Children.Clear();
            ThirdWorkStackPanel.Children.Clear();
            //WorkContainer.RowDefinitions.Clear();       //Удаляеи все строки
            //WorkContainer.ColumnDefinitions.Clear();    //Удаляет все колонки

            //Добавляем в грид компьютеры
            currentGrid = 1;
            foreach (Computer comp in project.computers)
            {
                Image newWorkImage = new Image();
                newWorkImage.Width = 100;
                newWorkImage.Height = 100;
                //newWorkImage.Cursor = Cursors.Wait;
                //newWorkImage.Source = ImageMainComputer.Source;
                //Загружаем картинку
                if(comp.state == Computer.States.offline)
                    newWorkImage.Source = new BitmapImage(new Uri("/Resources/compW.png", UriKind.Relative));
                else if (comp.state == Computer.States.block)
                    newWorkImage.Source = new BitmapImage(new Uri("/Resources/compR.png", UriKind.Relative));
                else if (comp.state == Computer.States.access)
                    newWorkImage.Source = new BitmapImage(new Uri("/Resources/compG.png", UriKind.Relative));
                else
                    newWorkImage.Source = new BitmapImage(new Uri("/Resources/comp.png", UriKind.Relative));
                //Всплывающая инфа
                newWorkImage.ToolTip = comp.name + "\nIP: " + comp.ip;

                Button newWorkBtn = new Button();
                newWorkBtn.Content = "Отключить";
                newWorkBtn.Background = new SolidColorBrush(Color.FromRgb(238, 17, 166));
                if (comp.state == Computer.States.block)
                {
                    newWorkBtn.Content = "Включить";
                    newWorkBtn.Background = new SolidColorBrush(Color.FromRgb(70, 238, 197));
                }

                //Уникальный идентификатор кнопки
                newWorkBtn.Uid = comp.ip;

                //Обработка нажатия на эти клавиши
                newWorkBtn.Click += new System.Windows.RoutedEventHandler(this.BtnComputer_Click);

                switch (currentGrid)
                {
                    case 1:
                        FirstWorkStackPanel.Children.Add(newWorkImage);
                        FirstWorkStackPanel.Children.Add(newWorkBtn);
                        break;
                    case 2:
                        SecondWorkStackPanel.Children.Add(newWorkImage);
                        SecondWorkStackPanel.Children.Add(newWorkBtn);
                        break;
                    case 3:
                        ThirdWorkStackPanel.Children.Add(newWorkImage);
                        ThirdWorkStackPanel.Children.Add(newWorkBtn);
                        currentGrid = 0;
                        break;
                }
                currentGrid++;
            }
        }

        #endregion

        #region Работа с сетью

        //Проверить все компы
        private void btnCheckAllComputers_Click(object sender, RoutedEventArgs e)
        {
            project.CheckAllComputers();
            ShowWorkComputers();
            MessageBox.Show("Проверка завершена!");
        }

        //Нажатие на кнопку отключение/включение конкретного рабочего компьютера
        private void BtnComputer_Click(object sender, RoutedEventArgs e)
        {
            if (sender is Button)
            {
                Button btnSender = (Button)sender;
                Computer.States sendState = Computer.States.access;
                if (btnSender.Content.ToString() == "Отключить")
                    sendState = Computer.States.block;
                if (project.SendOnComputer(btnSender.Uid, sendState))
                    ShowWorkComputers();
                else
                    MessageBox.Show("Произошла какая-то ошибка!", "Ошибка", MessageBoxButton.OK, MessageBoxImage.Error);
            }
        }

        //Включить все компьютеры
        private void btnOnAllComputres_Click(object sender, RoutedEventArgs e)
        {
            project.SendOnAllComputer(Computer.States.access);
            ShowWorkComputers();
        }

        //Отключить все компьютеры
        private void btnOffAllComputres_Click(object sender, RoutedEventArgs e)
        {
            project.SendOnAllComputer(Computer.States.block);
            ShowWorkComputers();
        }

        #endregion
    }
}
