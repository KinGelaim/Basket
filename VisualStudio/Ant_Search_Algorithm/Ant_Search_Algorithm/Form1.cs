using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Windows.Forms;
using System.Threading;
using System.IO;

namespace Ant_Search_Algorithm
{
    public partial class Form1 : Form
    {
        //Переменные для отрисовки
        Graphics g;
        Bitmap bitmap;
        bool updatePictureBox = true;

        List<City> cityList = new List<City>();                 //Коллекция для городов
        List<Road> roadList = new List<Road>();                 //Коллекция для дорог

        City cityMouseDownOnCity;                               //Переменная для сохранения информации о городе по которому была нажата мышка
        bool isMouseDownOnCity = false;                         //Проверка нажата ли мышка по городу

        List<Ant> antList = new List<Ant>();                    //Коллекция для муравьев
        bool is_startAlgoritm = false;

        bool is_threadOn = false;                               //Проверка запущены ли потоки?

        List<Thread> threadList = new List<Thread>();           //Коллекция для муравьиных потоков

        bool is_editMap = false;                                //Переменная для проверки редактируется ли сейчас карта

        public Form1()
        {
            InitializeComponent();

            //Инициализация переменных для отрисовки
            bitmap = new Bitmap(pictureBox1.Width, pictureBox1.Height);
            g = Graphics.FromImage(bitmap);
        }

        //---------ВЫПАДАЮЩЕЕ МЕНЮ---------
        //Создать один город
        private void createCityToolStripMenuItem_Click(object sender, EventArgs e)
        {
            if (!is_startAlgoritm)
            {
                City city = new City(Cursor.Position.X - 500, Cursor.Position.Y - 400, 30, 30);
                city.numberOfCity = cityList.Count + 1;
                cityList.Add(city);
            }
        }

        //Создать несколько городов
        private void createCitysToolStripMenuItem_Click(object sender, EventArgs e)
        {
            if (!is_startAlgoritm)
            {
                FormCreateCitys citysCreate = new FormCreateCitys();
                citysCreate.ShowDialog();
                int miniPosX = 30;
                int miniPosY = 30;
                if (citysCreate.is_exit)
                    for (int i = 0; i < citysCreate.countCitys; i++)
                    {
                        City city = new City(miniPosX, miniPosY, 30, 30);
                        city.numberOfCity = cityList.Count + 1;
                        cityList.Add(city);
                        miniPosX += 37;
                        if (miniPosX + 30 >= pictureBox1.Width)
                        {
                            miniPosX = 30;
                            miniPosY += 37;
                        }
                    }
            }
        }

        //Открыть меню управления шаблонами
        private void patternsToolStripMenuItem_Click(object sender, EventArgs e)
        {
            if (!is_startAlgoritm)
            {
                updatePictureBox = false;
                if (File.Exists(System.IO.Directory.GetCurrentDirectory() + @"\Newtonsoft.Json.dll"))
                {
                    if (Directory.Exists(System.IO.Directory.GetCurrentDirectory() + @"\Шаблоны"))
                    {
                        FormPatterns patternsForm = new FormPatterns();
                        patternsForm.ShowDialog();
                        if (patternsForm.is_exit)
                        {
                            antList.Clear();
                            cityList.Clear();
                            roadList.Clear();
                            cityList = patternsForm.cityList;
                            roadList = patternsForm.roadList;
                        }
                    }
                    else
                        if (DialogResult.Yes == MessageBox.Show("Не найдена директория для хранения шаблонов!\nЖелаете ее создать?", "Подтверждение", MessageBoxButtons.YesNo, MessageBoxIcon.Question))
                            Directory.CreateDirectory("Шаблоны");
                }
                else
                    if (DialogResult.Yes == MessageBox.Show("Не найдена библиотека для использования шаблонов!\nЖелаете ее распаковать?", "Подтверждение", MessageBoxButtons.YesNo, MessageBoxIcon.Question))
                    {
                        byte[] array = Properties.Resources.Newtonsoft_Json;
                        FileStream fs = new FileStream("Newtonsoft.Json.dll", FileMode.Create);
                        fs.Write(array, 0, array.Length);
                        fs.Close();
                    }
                updatePictureBox = true;
                pictureBox1.Invalidate();
            }
        }

        //Отрисовка города
        private void DrawCity()
        {
            if (cityList.Count > 0)
            {
                foreach(City city in cityList)
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

        //Пайнт для отрисовки на пиктуребоксе
        private void pictureBox1_Paint(object sender, PaintEventArgs e)
        {
            g.Clear(Color.White);
            DrawRoad();
            DrawCity();
            if (is_startAlgoritm)
                DrawAnts();
            if (updatePictureBox)
                pictureBox1.Image = bitmap;
        }

        //Нажатие мышки по пиктуребоксу
        private void pictureBox1_MouseDown(object sender, MouseEventArgs e)
        {
            if (!is_editMap)
            {
                if (!is_startAlgoritm)
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

        //Ведем мышкой по боксу
        private void pictureBox1_MouseMove(object sender, MouseEventArgs e)
        {
            if (is_editMap)
            {
                if(isMouseDownOnCity)
                {
                    cityMouseDownOnCity.x = e.Location.X;
                    cityMouseDownOnCity.y = e.Location.Y;
                }
            }
        }

        //Поднятие мышки
        private void pictureBox1_MouseUp(object sender, MouseEventArgs e)
        {
            if (!is_editMap)
            {
                if (!is_startAlgoritm)
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

        //Кнопка старт (для открытия окна, в котором происходит настройка алгоритма)
        private void button1_Click(object sender, EventArgs e)
        {
            if (cityList.Count >= 2 && roadList.Count >= 1)
            {
                foreach (City city in cityList)
                    city.roadInNextCity.Clear();
                foreach (Road road in roadList)
                    foreach (City city in cityList)
                        if (road.bCity == city)
                            city.roadInNextCity.Add(road);
                FormStart startForm = new FormStart(cityList, roadList);
                startForm.ShowDialog();
                if (startForm.is_exit)
                {
                    antList = startForm.antList;
                    StartAlgoritm();
                }
            }
        }

        //Запуск алгоритма
        private void StartAlgoritm()
        {
            Console.WriteLine("---------------------С Т А Р Т---------------------");
            is_startAlgoritm = true;
            button1.Enabled = false;
            button2.Enabled = true;
            button3.Enabled = false;
            button4.Enabled = true;
            Ant.is_endAlgoritm = false;
            Ant.speed = 101 - trackBar1.Value;
            //City.CreateBadCityList(cityList, antList[0].endCity);
        }

        //Отрисовка муравьев
        private void DrawAnts()
        {
            bool is_searchCityEnd = true;
            foreach (Ant ant in antList)
            {
                g.FillRectangle(Brushes.Red, ant.posX - 5, ant.posY - 5, ant.width, ant.height);
                if (Ant.is_endAlgoritm == false)
                    is_searchCityEnd = false;
            }
            if (!is_threadOn)
            {
                foreach (Ant ant in antList)
                {
                    threadList.Add(new Thread(() => ant.MoveAnt()));
                }
                foreach (Thread thread in threadList)
                    thread.Start();
                is_threadOn = true;
            }
            if(is_searchCityEnd)
            {
                //TODO:
                //Обработка тупиков
                //Разведчики (исследовательские муравьи)
                //Замедляются муравьи (причина не ясна, возможно, нагрузка потоков на расчеты)

                is_startAlgoritm = false;
                is_threadOn = false;
                button1.Enabled = true;
                button2.Enabled = false;
                button3.Enabled = true;
                button4.Enabled = false;

                //Вырубаем оставшиеся потоки
                foreach(Thread thread in threadList)
                    if (thread.IsAlive)
                        thread.Abort();

                threadList.Clear();

                Console.WriteLine("Алгоритм закончил свою работу!");

                Console.WriteLine("Новые тау: ");
                foreach (Road road in roadList)
                    Console.WriteLine("Узлы: " + road.bCity.numberOfCity + " " + road.eCity.numberOfCity + " Длина: " + road.length + "\tТау: " + road.pheramon);

                //Получение информационного текста о самом коротком пути
                string  textBestPath = SearchBestPath(antList[0].beginCity, antList[0].endCity);


                Console.WriteLine(textBestPath);
            }
        }

        //Кнопка стоп
        private void button2_Click(object sender, EventArgs e)
        {
            Ant.is_endAlgoritm = true;
        }

        //Кнопка сброс
        private void button3_Click(object sender, EventArgs e)
        {
            is_startAlgoritm = false;
            is_threadOn = false;
            button1.Enabled = true;
            button2.Enabled = false;
            antList.Clear();
            cityList.Clear();
            roadList.Clear();
        }

        //Кнопка паузы
        private void button4_Click(object sender, EventArgs e)
        {
            Ant.is_pauseAlgoritm = !Ant.is_pauseAlgoritm;
            if (Ant.is_pauseAlgoritm)
                button4.Text = "Плэй";
            else
                button4.Text = "Пауза";
        }

        //Кнопка вывода информации
        private void button5_Click(object sender, EventArgs e)
        {
            FormAboutAlgoritm aboutBox;
            if (antList.Count > 0)
                aboutBox = new FormAboutAlgoritm(roadList, antList[0]);
            else
                aboutBox = new FormAboutAlgoritm(roadList);
            aboutBox.Show();
        }

        //Кнопка редактирования карты
        private void button6_Click(object sender, EventArgs e)
        {
            if (is_editMap)
            {
                if (Ant.is_endAlgoritm)
                {
                    button1.Enabled = true;
                    button2.Enabled = false;
                    button4.Enabled = false;
                    button3.Enabled = true;
                }
                else
                {
                    button1.Enabled = false;
                    button2.Enabled = true;
                    button4.Enabled = true;
                    button3.Enabled = false;
                }
                button5.Enabled = true;
            }
            else
            {
                button1.Enabled = false;
                button2.Enabled = false;
                button3.Enabled = false;
                button4.Enabled = false;
                button5.Enabled = false;
            }
            is_editMap = !is_editMap;
        }

        //Изменение ползунка
        private void trackBar1_Scroll(object sender, EventArgs e)
        {
            Ant.speed = 101 - trackBar1.Value;
        }

        //События при изменения формы управления
        private void Form1_Resize(object sender, EventArgs e)
        {
            if (pictureBox1.Width != 0 && pictureBox1.Height != 0)
            {
                bitmap = new Bitmap(pictureBox1.Width, pictureBox1.Height);
                g = Graphics.FromImage(bitmap);
            }
        }

        //Закрытие формы
        private void Form1_FormClosing(object sender, FormClosingEventArgs e)
        {
            foreach (Thread thread in threadList)
                if (thread.IsAlive)
                    thread.Abort();
        }

        //Поиск кратчайшего пути по результатам ТАУ
        private string SearchBestPath(City bCity, City eCity, string textBestPath = "Кратчайший путь:\n")
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
    }
}
