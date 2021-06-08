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
using System.Windows.Forms.DataVisualization.Charting;
using System.Threading;

namespace HeartGraphics
{
    /// <summary>
    /// Логика взаимодействия для MainWindow.xaml
    /// </summary>
    public partial class MainWindow : Window
    {
        List<double> dataX;
        List<double> dataY;

        Thread threadUp = null;

        public MainWindow()
        {
            InitializeComponent();
        }

        private void Window_Loaded(object sender, RoutedEventArgs e)
        {
            //Создаём элемент график
            chart.ChartAreas.Add(new ChartArea("Default"));

            chart.Series.Add(new Series("0"));
            chart.Series[0].ChartArea = "Default";
            chart.Series[0].ChartType = SeriesChartType.Line;
            chart.Series[0].Color = System.Drawing.Color.Red;

            //Формируем график
            dataX = new List<double>();
            dataY = new List<double>();

            CreateHeart(0);
        }

        public void CreateHeart(double a)
        {
            dataX.Clear();
            dataY.Clear();
            for (double i = -7; i < 7; i += 0.1)
            {
                dataX.Add(i);
                //if (i > -1.4 && i < 3.2)
                {
                    double y = CalcYHeart(i, -1*a);
                    dataY.Add(y);
                }
                //else
                   // dataY.Add(0);
            }

            //Отображаем график
            chart.Series[0].Points.DataBindXY(dataX, dataY);
        }

        public double CalcYHeart(double x, double a)
        {
            double y = Math.Pow(x, 2 / 3) + 0.9 * Math.Pow((3.3 - Math.Pow(x, 2)), 1 / 2) * Math.Sin(a * Math.PI * x);
            return y;
        }

        private void btnNumericUp_Click(object sender, RoutedEventArgs e)
        {
            txtNumericUpDown.Text = Convert.ToString(Convert.ToDouble(txtNumericUpDown.Text) + 0.1);
            CreateHeart(Convert.ToDouble(txtNumericUpDown.Text));
        }

        private void btnNumericDown_Click(object sender, RoutedEventArgs e)
        {
            txtNumericUpDown.Text = Convert.ToString(Convert.ToDouble(txtNumericUpDown.Text) - 0.1);
            CreateHeart(Convert.ToDouble(txtNumericUpDown.Text));
        }

        private void btnNumericUp_MouseDown(object sender, MouseButtonEventArgs e)
        {
            if (threadUp == null)
            {
                threadUp = new Thread(() =>
                {
                    txtNumericUpDown.Text = Convert.ToString(Convert.ToDouble(txtNumericUpDown.Text) + 0.1);
                    CreateHeart(Convert.ToDouble(txtNumericUpDown.Text));
                    Thread.Sleep(500);
                });
                threadUp.Start();
            }
        }

        private void btnNumericUp_MouseUp(object sender, MouseButtonEventArgs e)
        {
            if (threadUp != null)
            {
                if (threadUp.IsAlive)
                {
                    threadUp.Abort();
                    threadUp = null;
                }
            }
        }
    }
}
