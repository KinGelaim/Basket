using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Windows.Forms;
using System.Windows.Forms.DataVisualization.Charting;  //Для отрисовки диаграмм

namespace Project9
{
    public partial class Form1 : Form
    {
        private SeriesChartType[] chartTypes = new SeriesChartType[] 
        { 
            SeriesChartType.Area,
            SeriesChartType.Bar,
            SeriesChartType.BoxPlot,
            SeriesChartType.Bubble,
            SeriesChartType.Candlestick,
            SeriesChartType.Column,
            SeriesChartType.Doughnut,
            SeriesChartType.FastLine,
            SeriesChartType.FastPoint,
            SeriesChartType.Funnel,
            SeriesChartType.Line,
            SeriesChartType.Pie,
            SeriesChartType.Radar,
            SeriesChartType.Range,
            SeriesChartType.Spline
        };
        private int sizeArr = 10;   //Размер рандомного графика
        private bool isTwoChart = false;

        public Form1()
        {
            InitializeComponent();

            chart1.ChartAreas.Add(new ChartArea("Default"));    //Добавляем область построения диаграмм
        }

        //Построить график
        private void button1_Click(object sender, EventArgs e)
        {
            chart1.Series.Clear();
            chart1.Series.Add(new Series("0"));
            chart1.Series["0"].ChartArea = "Default";
            chart1.Series["0"].ChartType = SeriesChartType.Line;
            chart1.Series["0"].BorderWidth = 5;                     //Толщина линии
            Random rand = new Random();
            Color color = Color.FromArgb(rand.Next(0, 255), rand.Next(0, 255), rand.Next(0, 255));
            chart1.Series["0"].Color = color;
            chart1.ChartAreas[0].AxisX.Interval = 1;
            chart1.ChartAreas[0].AxisX.Title = "Это ИКСИЩЕ";
            chart1.ChartAreas[0].AxisY.Title = "А это ИГРИЩЕ";
            int[] argX = new int[dataGridView1.RowCount-1];
            int[] argY = new int[dataGridView1.RowCount-1];
            for (int i = 0; i < dataGridView1.RowCount-1; i++)
            {
                try
                {
                    argX[i] = Convert.ToInt32(dataGridView1[0, i].Value);
                    argY[i] = Convert.ToInt32(dataGridView1[1, i].Value);
                }
                catch (Exception ex)
                {
                    MessageBox.Show("Ошибка при преобразованиях!\n" + ex.Message);
                }
            }
            chart1.Series["0"].Points.DataBindXY(argX, argY);
        }

        private void button2_Click(object sender, EventArgs e)
        {
            Random rand = new Random();

            chart1.Series.Clear();                                  //Очищаем серии чарта (чистим графики)
            chart1.Series.Add(new Series("0"));                     //Создаем новыую серию (график)
            chart1.Series["0"].ChartArea = "Default";               //Прикрепляем серию к области построения диаграммы
            chart1.Series["0"].ChartType = chartTypes[rand.Next(0,chartTypes.Length)];              //Тип графика
            
            //Цвет графика
            Color color = Color.FromArgb(rand.Next(0, 255), rand.Next(0, 255), rand.Next(0, 255));
            chart1.Series["0"].Color = color;                       

            chart1.ChartAreas[0].AxisX.Interval = 1;                //Отображаемый интервал
            chart1.ChartAreas[0].AxisX.Title = "Это X";             //Подпись оси X
            chart1.ChartAreas[0].AxisY.Title = "А это Y";           //Подпись оси Y
            int[] argX = new int[sizeArr];
            int[] argY = new int[sizeArr];

            for (int i = 0; i < sizeArr; i++)
            {
                argX[i] = i;
                argY[i] = rand.Next(0, 37);
            }
            chart1.Series["0"].Points.DataBindXY(argX, argY);

            if (isTwoChart)
            {
                chart1.Series.Add(new Series("1"));
                chart1.Series["1"].ChartArea = "Default";
                chart1.Series["0"].ChartType = SeriesChartType.Line;
                chart1.Series["1"].ChartType = SeriesChartType.Line;
                chart1.Series["0"].BorderWidth = 3;
                chart1.Series["1"].BorderWidth = 3;
                color = Color.FromArgb(rand.Next(0, 255), rand.Next(0, 255), rand.Next(0, 255));
                chart1.Series["1"].Color = color;

                int[] argY2 = new int[sizeArr];

                for (int i = 0; i < sizeArr; i++)
                {
                    argY2[i] = rand.Next(0, 37);
                }
                chart1.Series["1"].Points.DataBindXY(argX, argY2);

                chart1.Series["0"].Name = "График 1";                   //Наименование графика
                chart1.Series["1"].Name = "График 2";
            }
        }

        //Смена количества графиков
        private void chart1_Click(object sender, EventArgs e)
        {
            isTwoChart = !isTwoChart;
            if (isTwoChart)
            {
                chart1.Legends.Add("0");
                chart1.Legends.Add("1");
            }
            else
            {
                chart1.Legends.Clear();
            }
        }

        //Перед закрытием формы
        private void Form1_FormClosing(object sender, FormClosingEventArgs e)
        {
            e.Cancel = true;
            if (MessageBox.Show("Вы уверены, что желаете выйти?", "Подтверждение", MessageBoxButtons.OKCancel, MessageBoxIcon.Question) == DialogResult.OK)
                e.Cancel = false;
        }
    }
}
