using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Windows.Forms;
using System.Threading;

namespace Ant_Search_Algorithm
{
    public partial class FormAboutAlgoritm : Form
    {
        private bool is_closeThis = false;
        private Ant ant = null;

        private List<Road> roadList = new List<Road>();

        public FormAboutAlgoritm(List<Road> roadList, Ant ant = null)
        {
            InitializeComponent();

            new Thread (() => StartTakeInformation()).Start();

            listBox1.Items.Clear();

            this.roadList = roadList;
            this.ant = ant;
        }

        private void StartTakeInformation()
        {
            while (!is_closeThis)
            {
                Console.WriteLine("INFO");
                //this.Refresh();

                listBox1.Items.Add("Новые тау: ");
                foreach (Road road in roadList)
                    listBox1.Items.Add("Узлы: " + road.bCity.numberOfCity + " " + road.eCity.numberOfCity + "\tДлина: " + road.length + "\tТау: " + road.pheramon);

                listBox1.SelectedIndex = listBox1.Items.Count - 1;

                //Получение информационного текста о самом коротком пути
                if (ant != null)
                    label1.Text = SearchBestPath(ant.beginCity, ant.endCity);
                else
                    label1.Text = "";

                Thread.Sleep(3000);
            };
        }

        //Поиск кратчайшего пути по результатам ТАУ
        private string SearchBestPath(City bCity, City eCity, string textBestPath = "Кратчайший путь: ")
        {
            double k = 0;
            Road bestRoad = new Road();
            foreach (Road road in roadList)
            {
                if (road.bCity == bCity)
                {
                    if (road.pheramon >= k)
                    {
                        k = road.pheramon;
                        bestRoad = road;
                    }
                }
            }
            textBestPath += bestRoad.bCity.numberOfCity + " - ";
            if (bestRoad.eCity.numberOfCity == eCity.numberOfCity)
                textBestPath += bestRoad.eCity.numberOfCity;
            else
                textBestPath = SearchBestPath(bestRoad.eCity, eCity, textBestPath);
            return textBestPath;
        }

        private void FormAboutAlgoritm_FormClosing(object sender, FormClosingEventArgs e)
        {
            is_closeThis = true;
        }
    }
}
