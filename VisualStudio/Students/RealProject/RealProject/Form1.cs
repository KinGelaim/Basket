using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Windows.Forms;

namespace RealProject
{
    public partial class Form1 : Form
    {
        //Форма осколка
        public enum FragmentShape
        {
            plate,      //Пластина
            rhombus,    //Ромб  
        }

        const double ro0Air = 1.206;    //Плотность воздуха при нормальных условиях

        private double metalDensity;    //Плотность материала
        private double airDensity;      //Плотность воздуха
        private double v0;              //Начальная скорость

        private double a,b,m;           //Стороны и масса осколка

        private double s;               //Сечение
        private double bal;             //Балистический коэф

        FragmentShape fs;               //Форма фрагмента

        private double distance_a { get; set; } //Растояние для открытой живой силы (1 МДж/м2)
        private double distance_b { get; set; } //Растояние для живой силы в бронежелете (7.5 МДж/м2)
        private double distance_c { get; set; } //Растояние для небронированной техники (13.5 МДж/м2)
        private double distance_d { get; set; } //Растояние для легкобронированной техники (42 МДж/м2)

        public Form1()
        {
            InitializeComponent();

            //comboBox1.Items.Add("ds");
        }

        private void button1_Click(object sender, EventArgs e)
        {
            try
            {
                //Считываем все параметры
                metalDensity = Convert.ToDouble(textBox1.Text);
                airDensity = Convert.ToDouble(textBox6.Text);
                v0 = Convert.ToDouble(textBox2.Text);
                a = Convert.ToDouble(textBox3.Text);
                b = Convert.ToDouble(textBox4.Text);
                m = Convert.ToDouble(textBox5.Text);

                //Форма осколка
                if (comboBox1.Items[comboBox1.SelectedIndex].ToString() == "Пластина")
                    fs = FragmentShape.plate;
                else if (comboBox1.Items[comboBox1.SelectedIndex].ToString() == "Ромб")
                    fs = FragmentShape.rhombus;
                else
                    throw new Exception("Нет такой фигуры!");

                //Вычисление параметров
                CountS();
                CountBal();
                CountDistances();

                //Вывод информации
                PrintInfo();
            }
            catch(Exception ex)
            {
                MessageBox.Show("Какая-то ошибка!\n" + ex.Message, "Ошибка!", MessageBoxButtons.OK, MessageBoxIcon.Error);
            }
        }

        //Вычисление площади минделя
        private void CountS()
        {
            //double Davg = (a + b) / 2.0;
            switch (fs)
            {
                case FragmentShape.plate:
                    s = (a * b + m / metalDensity * (a + b) / (a * b)) / 2.0;
                    break;
                case FragmentShape.rhombus:
                    s = (a * b + 2 * m / metalDensity / b) / 2.0;
                    break;
                default:
                    s = 0;
                    break;
            }
        }

        //Балистический коэф
        private void CountBal()
        {
            double AA;
            //AA = Aconst();
            AA = 0.0603 * Cx(v0, fs);
            bal = AA * s / m * airDensity / ro0Air;
        }

        //Возвращает коэффициент Cx для указанной скорости по методике из книги "Знаменский"
        private static double Cx(double v, FragmentShape fs)
        {
            const double v1 = 150;
            const double v2 = 550;

            double a1, a2, a3, b2, b3;

            switch (fs)
            {
                case FragmentShape.plate:
                    a1 = 0.750;
                    a2 = 1.080;
                    a3 = 1.075;
                    b2 = 0.250;
                    b3 = 36000;
                    break;
                case FragmentShape.rhombus:
                    goto case FragmentShape.plate;
                default:
                    goto case FragmentShape.plate;
            }

            if (v < v1)
            {
                return a1;
            }
            if (v > v2)
            {
                return a3 + b3 / v / v;
            }
            return 1.0 / (a2 + b2 * Math.Sin(DegreesToRadians(860) - DegreesToRadians(350) * Math.Log10(v)));
        }

        private static double DegreesToRadians(double degrees)
        {
            return (Math.PI / 180) * degrees;
        }

        //Расчет показателя ударного действия (Eуд)
        private void CountDistances()
        {
            distance_a = 0;
            distance_b = 0;
            distance_c = 0;
            distance_d = 0;

            double speed = Math.Sqrt((2 * s / 10000 * 1000000) / (m / 1000));
            double distance = Math.Log(v0 / speed) / bal;
            distance_a = distance > 0 ? distance : 0;

            speed = Math.Sqrt((2 * s / 10000 * 7500000) / (m / 1000));
            distance = Math.Log(v0 / speed) / bal;
            distance_b = distance > 0 ? distance : 0;

            speed = Math.Sqrt((2 * s / 10000 * 13500000) / (m / 1000));
            distance = Math.Log(v0 / speed) / bal;
            distance_c = distance > 0 ? distance : 0;

            speed = Math.Sqrt((2 * s / 10000 * 42000000) / (m / 1000));
            distance = Math.Log(v0 / speed) / bal;
            distance_d = distance > 0 ? distance : 0;
        }

        //Выводим всю инфу
        private void PrintInfo()
        {
            textBox7.Text = "Минделево сечение = " + s + Environment.NewLine + "Балистический коэффициент = " + bal + Environment.NewLine + "Дистанции поражения:" 
                + Environment.NewLine + "Для живой силы: " + distance_a + Environment.NewLine +
                "Живая сила в бронике: " + distance_b + Environment.NewLine+"Небронированная техника: " + 
                distance_c + Environment.NewLine+"Легкобронированная техника: " + distance_d;
        }
    }
}
