using System.Net;
using System.Windows;

namespace ParentControlOfTheInternetOnALocalNetwork
{
    /// <summary>
    /// Логика взаимодействия для ComputerWindow.xaml
    /// </summary>
    public partial class ComputerWindow : Window
    {
        private bool isExit = false;
        private Computer computer;

        public ComputerWindow()
        {
            InitializeComponent();
            computer = new Computer();
        }

        public ComputerWindow(Computer computer)
        {
            InitializeComponent();

            this.computer = computer;
        }

        public Computer getComputer()
        {
            if(isExit)
                return computer;
            return null;
        }

        //Подтвержаем завершение
        private void btnCompleteComputer_Click(object sender, RoutedEventArgs e)
        {
            IPAddress tmp;
            bool valid = IPAddress.TryParse(txtIPComputer.Text, out tmp);
            if (valid)
            {
                computer.name = txtNameComputer.Text;
                computer.ip = txtIPComputer.Text;
                isExit = true;
                this.Close();
            }
            else
                MessageBox.Show("IP ввдён не верно!", "Ошибка", MessageBoxButton.OK, MessageBoxImage.Error);
        }
    }
}
