using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Windows.Forms;

namespace Ant_Search_Algorithm
{
    public partial class FormStart : Form
    {
        private List<City> cityList = new List<City>();
        private List<Road> roadList = new List<Road>();
        private City beginCity;
        private City endCity;
        public List<Ant> antList = new List<Ant>();
        public bool is_exit;

        public FormStart(List<City> cityList, List<Road> roadList)
        {
            InitializeComponent();

            this.cityList = cityList;
            this.roadList = roadList;

            is_exit = false;

            foreach (City city in cityList)
            {
                comboBox1.Items.Add(city.numberOfCity);
                comboBox2.Items.Add(city.numberOfCity);
            }

            comboBox1.SelectedIndex = 0;
            comboBox2.SelectedIndex = comboBox2.Items.Count - 1;
        }

        //После выбора городов
        private void button1_Click(object sender, EventArgs e)
        {
            label3.Text = "";

            if (comboBox1.SelectedIndex >= 0 && comboBox2.SelectedIndex >= 0)
            {
                if (CheckRoad(cityList[comboBox1.SelectedIndex], cityList[comboBox2.SelectedIndex]))
                {
                    panel1.Visible = false;
                    panel2.Visible = true;

                    beginCity = cityList[comboBox1.SelectedIndex];
                    endCity = cityList[comboBox2.SelectedIndex];
                }
                else
                {
                    label3.Text = "Нет дороги!";
                }
            }
        }

        private bool CheckRoad(City startCity, City endCity)
        {
            foreach (Road road in roadList)
            {
                if (road.bCity.numberOfCity == startCity.numberOfCity)
                {
                    if(road.eCity.numberOfCity == endCity.numberOfCity)
                    {
                        return true;
                    }
                    else
                    {
                        if (CheckRoad(road.eCity, endCity))
                            return true;
                    }
                }
            }
            return false;
        }

        //Назад из разведки
        private void button2_Click(object sender, EventArgs e)
        {
            panel2.Visible = false;
            panel1.Visible = true;
        }

        //Вперед из разведки
        private void button3_Click(object sender, EventArgs e)
        {
            panel2.Visible = false;
            panel3.Visible = true;
        }

        //Назад из настройки муравьев
        private void button4_Click(object sender, EventArgs e)
        {
            panel3.Visible = false;
            panel2.Visible = true;
        }

        //Старт
        private void button5_Click(object sender, EventArgs e)
        {
            //Создание муравьишек
            for (int i = 0; i < numericUpDown1.Value; i++ )
            {
                Ant newAnt = new Ant(beginCity, endCity, Convert.ToInt32(numericUpDown2.Value), Convert.ToDouble(numericUpDown3.Value), Convert.ToInt32(numericUpDown4.Value), Convert.ToInt32(numericUpDown5.Value));
                antList.Add(newAnt);
            }
            is_exit = true;
            this.Close();
        }
    }
}
