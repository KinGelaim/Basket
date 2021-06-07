using System;
using System.Collections.Generic;
using System.Linq;
using System.Media;
using System.Text;
using System.Windows;
using System.Windows.Controls;
using System.Windows.Data;
using System.Windows.Documents;
using System.Windows.Input;
using System.Windows.Media.Imaging;
using System.Windows.Navigation;
using System.Windows.Shapes;

namespace InfiniteMilitaryService_v1._0
{
    /// <summary>
    /// Логика взаимодействия для MainWindow.xaml
    /// </summary>
    public partial class MainWindow : Window
    {
        public MainWindow()
        {
            InitializeComponent();
        }

        private void Window_Loaded(object sender, RoutedEventArgs e)
        {
            //Настройки окна
            this.WindowState = System.Windows.WindowState.Maximized;
            this.WindowStyle = System.Windows.WindowStyle.None;
            this.Focus();

            //Подписываемся на событие для нажатия по клавише экрана
            this.KeyUp += new System.Windows.Input.KeyEventHandler(ReadKeyboard.Window_KeyUp);

            //Подписываемся на событие закрытия окна
            this.Closing += new System.ComponentModel.CancelEventHandler(Window_Closing);

            //Настройки начального отображения
            MainMenu.Visibility = System.Windows.Visibility.Visible;
            TreeGame.Visibility = System.Windows.Visibility.Hidden;
            MainContainer.Visibility = System.Windows.Visibility.Hidden;

            //Создаём объект игры и вызываем его конструктор, передав в качестве параметра ссылку на главное окно
            Game game = new Game(this);
            game.ShowMainMenu();
        }

        //Закрытие главного экрана
        public void CloseWindow()
        {
            this.Close();
        }

        //Перед закрытием окна
        private void Window_Closing(object sender, System.ComponentModel.CancelEventArgs e)
        {
            if (Episode.screenThread != null)
                if (Episode.screenThread.IsAlive)
                    Episode.screenThread.Abort();
        }

        //TODO: в настройках - скорость воспроизведение текста, громкость, размер шрифта, сброс сохранения
    }
}
