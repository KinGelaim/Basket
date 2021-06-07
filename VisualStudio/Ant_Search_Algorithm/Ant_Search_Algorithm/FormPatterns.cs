using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Windows.Forms;
using System.IO;
using Newtonsoft.Json;

namespace Ant_Search_Algorithm
{
    public partial class FormPatterns : Form
    {
        private Graphics g;
        private Bitmap bitmap;
        private bool is_createPattern = false;
        private bool is_editPattern = false;

        public List<City> cityList = new List<City>();
        public List<Road> roadList = new List<Road>();
        public bool is_exit = false;

        private bool isMouseDownOnCity = false;
        private City cityMouseDownOnCity;

        private IEnumerable<string> allPatterns = null;

        private string pathToDir = System.IO.Directory.GetCurrentDirectory() + @"\Шаблоны";

        public FormPatterns()
        {
            InitializeComponent();

            //Загрузка и отображение списка шаблонов
            LoadPatterns();

            //Отрисовка начало
            bitmap = new Bitmap(pictureBox1.Width, pictureBox1.Height);
            g = Graphics.FromImage(bitmap);
        }

        //Создать новый шаблон
        private void button1_Click(object sender, EventArgs e)
        {
            cityList.Clear();
            roadList.Clear();
            RedrawImage();
            is_createPattern = true;
            button1.Enabled = false;
            button2.Enabled = false;
            button3.Enabled = false;
            button4.Enabled = true;
        }

        //Редактировать старый шаблон
        private void button2_Click(object sender, EventArgs e)
        {
            is_editPattern = true;
            button1.Enabled = false;
            button2.Enabled = false;
            button3.Enabled = false;
            button4.Enabled = true;
        }

        //Удалить шаблон
        private void button3_Click(object sender, EventArgs e)
        {
            File.Delete(pathToDir + @"\" + listBox1.Items[listBox1.SelectedIndex] + ".antpattern");
            LoadPatterns();
        }

        //Сохранить шаблон
        private void button4_Click(object sender, EventArgs e)
        {
            //Сохранение (через сериализацию или вручную?)
            SaveLoadClass saveLoad = new SaveLoadClass(cityList, roadList);
            if(is_editPattern)
                File.WriteAllText(pathToDir + @"\" + listBox1.Items[listBox1.SelectedIndex] + ".antpattern", JsonConvert.SerializeObject(saveLoad));
            else
                File.WriteAllText(pathToDir + @"\" + "Шаблон " + allPatterns.ToList<string>().Count + ".antpattern", JsonConvert.SerializeObject(saveLoad));
            //Изменение отображения
            is_createPattern = false;
            is_editPattern = false;
            button1.Enabled = true;
            button2.Enabled = true;
            button3.Enabled = true;
            button4.Enabled = false;
            //Повторная подгрузка шаблонов
            LoadPatterns();
        }

        //Загрузка всего списка шаблонов
        private void LoadPatterns()
        {
            listBox1.Items.Clear();
            allPatterns = null;
            allPatterns = Directory.EnumerateFiles(pathToDir);
            foreach (string str in allPatterns)
            {
                string[] k = str.Split('\\');
                string[] item = k[k.Length - 1].Split('.');
                listBox1.Items.Add(item[0]);
            }
        }

        private void pictureBox1_MouseDown(object sender, MouseEventArgs e)
        {
            if (is_editPattern || is_createPattern)
            {
                if (e.Button == System.Windows.Forms.MouseButtons.Left)
                {
                    if (!is_editPattern)
                    {
                        if (cityList.Count > 0)
                        {
                            foreach (City city in cityList)
                            {
                                if (e.Location.X > city.x && e.Location.X < city.x + city.width && e.Location.Y > city.y && e.Location.Y < city.y + city.height)
                                {
                                    isMouseDownOnCity = true;
                                    cityMouseDownOnCity = city;
                                }
                            }
                        }
                    }
                    else
                    {
                        foreach (City city in cityList)
                        {
                            if (e.Location.X > city.x && e.Location.X < city.x + city.width && e.Location.Y > city.y && e.Location.Y < city.y + city.height)
                            {
                                isMouseDownOnCity = true;
                                cityMouseDownOnCity = city;
                            }
                        }
                    }
                }
                else
                {
                    City city = new City(Cursor.Position.X - 670, Cursor.Position.Y - 370, 30, 30);
                    city.numberOfCity = cityList.Count + 1;
                    cityList.Add(city);
                }
            }
        }

        private void pictureBox1_MouseMove(object sender, MouseEventArgs e)
        {
            if (is_editPattern)
            {
                if (isMouseDownOnCity)
                {
                    cityMouseDownOnCity.x = e.Location.X;
                    cityMouseDownOnCity.y = e.Location.Y;
                    RedrawImage();
                }
            }
        }

        private void pictureBox1_MouseUp(object sender, MouseEventArgs e)
        {
            if (is_editPattern || is_createPattern)
            {
                if (!is_editPattern)
                {
                    if (isMouseDownOnCity)
                        if (cityList.Count > 0)
                            foreach (City city in cityList)
                            {
                                if (e.Location.X > city.x && e.Location.X < city.x + city.width && e.Location.Y > city.y && e.Location.Y < city.y + city.height)
                                {
                                    if (city != cityMouseDownOnCity)
                                    {
                                        FormCreateLength createLength = new FormCreateLength();
                                        createLength.ShowDialog();
                                        if (createLength.is_exit)
                                        {
                                            Road road = new Road(cityMouseDownOnCity, city, Convert.ToInt32(createLength.textBox1.Text));
                                            roadList.Add(road);
                                        }
                                    }
                                }
                            }
                }
                isMouseDownOnCity = false;
            }
            RedrawImage();
        }

        //Выбор шаблона
        private void listBox1_SelectedIndexChanged(object sender, EventArgs e)
        {
            cityList.Clear();
            roadList.Clear();
            if (listBox1.SelectedIndex >= 0)
            {
                SaveLoadClass saveLoad = new SaveLoadClass();
                string loadText = File.ReadAllText(allPatterns.ToList<string>()[listBox1.SelectedIndex]);
                saveLoad = JsonConvert.DeserializeObject<SaveLoadClass>(loadText);
                cityList = saveLoad.cityList;
                List<Road> prRoadList = saveLoad.roadList;
                foreach(City cityB in cityList)
                    foreach (Road roadInPr in prRoadList)
                        if (roadInPr.bCity.numberOfCity == cityB.numberOfCity)
                        {
                            foreach (City cityE in cityList)
                                if (roadInPr.eCity.numberOfCity == cityE.numberOfCity)
                                {
                                    Road road = new Road(cityB, cityE, Convert.ToInt32(roadInPr.length));
                                    roadList.Add(road);
                                }
                        }
            }
            RedrawImage();
        }

        //Активация шаблона
        private void listBox1_DoubleClick(object sender, EventArgs e)
        {
            if (listBox1.SelectedIndex >= 0)
            {
                this.Close();
                is_exit = true;
            }
        }

        //Отрисовка в ПЭЙНТ
        private void pictureBox1_Paint(object sender, PaintEventArgs e)
        {

        }

        private void RedrawImage()
        {
            g.Clear(Color.White);
            DrawCity();
            DrawRoad();
            pictureBox1.Image = bitmap;
        }

        //Отрисовка города
        private void DrawCity()
        {
            if (cityList.Count > 0)
            {
                foreach (City city in cityList)
                {
                    g.DrawRectangle(Pens.Black, city.x, city.y, city.width, city.height);
                    g.DrawString(city.numberOfCity.ToString(), new Font("Microsoft Sans Serif", 10, System.Drawing.FontStyle.Regular), Brushes.Red, city.x + city.width / 4, city.y + city.height / 4);
                }
            }
        }

        //Отрисовка дорог
        private void DrawRoad()
        {
            if (roadList.Count > 0)
            {
                foreach (Road road in roadList)
                {
                    g.DrawLine(Pens.Black, road.bCity.x + road.bCity.width / 2, road.bCity.y + road.bCity.height / 2, road.eCity.x + road.eCity.width / 2, road.eCity.y + road.eCity.height / 2);
                }
            }
        }
    }
}
