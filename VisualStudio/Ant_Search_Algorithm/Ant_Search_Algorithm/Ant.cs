using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading;

namespace Ant_Search_Algorithm
{
    public class Ant
    {
        public int Q { get; set; }
        public double P { get; set; }
        public int alpha { get; set; }
        public int beta { get; set; }
        public int posX { get; set; }
        public int posY { get; set; }
        public int width { get; set; }
        public int height { get; set; }
        public static int speed { get; set; }
        public City currentCity { get; set; }
        public City beginCity { get; set; }
        public City nextCity { get; set; }
        public City endCity { get; set; }
        public List<City> badCityList { get; set; }
        public List<Road> oldRoadList { get; set; }

        private bool is_endSearch = false;

        public static bool is_endAlgoritm = true;

        public static bool is_pauseAlgoritm = false;
        
        private static Random rand = new Random();

        //Для движения по прямой 
        private double k { get; set; }
        private double b { get; set; }

        public Ant(City bCity, City eCity, int Q, double P, int alpha, int beta)
        {
            beginCity = bCity;
            currentCity = bCity;
            nextCity = null;
            this.endCity = eCity;
            posX = bCity.x + bCity.width / 2;
            posY = bCity.y + bCity.height / 2;
            this.Q = Q;
            this.P = P;
            this.alpha = alpha;
            this.beta = beta;
            this.width = 10;
            this.height = 10;
            badCityList = new List<City>();
            oldRoadList = new List<Road>();
        }

        struct Point
        {
            public int posX;
            public int posY;

            public Point(int posX, int posY)
            {
                this.posX = posX;
                this.posY = posY;
            }
        }

        //Движение муравьишки
        public void MoveAnt()
        {
            while (!Ant.is_endAlgoritm)
            {
                if (!Ant.is_pauseAlgoritm)
                {
                    if (this.is_endSearch)
                    {
                        MoveBackAnt();
                        continue;
                    }
                    MoveForwardAnt();
                }
                else
                    Thread.Sleep(3000);
            }
        }

        private void MoveForwardAnt()
        {
            //Рандомная пауза перед движением
            Thread.Sleep(rand.Next(Ant.speed));
            //Следующего города нет, ищем новый
            if (nextCity == null)
                SearchNextCity();
            if ((nextCity.x + nextCity.width / 2) == posX && (nextCity.y + nextCity.height / 2) > posY - 15 || (nextCity.x + nextCity.width / 2) == posX && (nextCity.y + nextCity.height / 2) > posY + 15)
            {
                currentCity = nextCity;
                if (currentCity != endCity)
                    SearchNextCity();
            }
            //Текущий город равен нашему конечному пути
            if (currentCity == endCity)
            {
                //Меняем ферамоны у дорог
                double deltaL = 0;
                foreach (Road road in this.oldRoadList)
                {
                    deltaL += road.length;
                }
                double deltaTau = this.Q / deltaL;
                foreach (Road road in this.oldRoadList)
                {
                    road.pheramon = (1 - this.P) * road.pheramon + deltaTau;
                }
                this.is_endSearch = true;
            }
            else
            {
                //Позиционной движение к следующему городу
                Point p = EditPosition(k, b, posX, posY, nextCity.x + nextCity.width / 2, nextCity.y + nextCity.height / 2);
                posX = p.posX;
                posY = p.posY;
            }
        }

        public void SearchNextCity()
        {
            //Рандомная пауза в городе
            Thread.Sleep(rand.Next(300, 7000));
            //Добавляем прошлый город в лист плохих городов
            badCityList.Add(currentCity);
            List<double> shansList = new List<double>();
            double summa = 0;
            foreach (Road road in currentCity.roadInNextCity)
                if (!badCityList.Contains(road.eCity))
                    summa += Math.Pow((this.Q / road.length), this.beta) * Math.Pow(road.pheramon, this.alpha);
            for (int i = 0; i < currentCity.roadInNextCity.Count; i++)
                if (!badCityList.Contains(currentCity.roadInNextCity[i].eCity))
                    if (shansList.Count == 0)
                        shansList.Add(Math.Pow((this.Q / currentCity.roadInNextCity[i].length), this.beta) * Math.Pow(currentCity.roadInNextCity[i].pheramon, this.alpha) / summa * 100);
                    else
                        shansList.Add(shansList[shansList.Count - 1] + (Math.Pow((this.Q / currentCity.roadInNextCity[i].length), this.beta) * Math.Pow(currentCity.roadInNextCity[i].pheramon, this.alpha) / summa * 100));

            double currentShans = rand.Next(0, 100);
            Console.WriteLine(currentShans);
            if (currentShans == 0)
                rand = new Random();
            for (int i = 0; i < shansList.Count; i++)
                if (!badCityList.Contains(currentCity.roadInNextCity[i].eCity))
                    if (currentShans <= shansList[i])
                    {
                        nextCity = currentCity.roadInNextCity[i].eCity;
                        oldRoadList.Add(currentCity.roadInNextCity[i]);
                        EditPath(posX, posY, nextCity.x + nextCity.width / 2, nextCity.y + nextCity.height / 2);
                        break;
                    }
        }

        private void MoveBackAnt()
        {
            Thread.Sleep(rand.Next(Ant.speed));
            if ((nextCity.x + nextCity.width / 2) == posX && (nextCity.y + nextCity.height / 2) > posY - 15 || (nextCity.x + nextCity.width / 2) == posX && (nextCity.y + nextCity.height / 2) > posY + 15)
            {
                currentCity = nextCity;
                if (currentCity != beginCity)
                    SearchPrevCity();
            }
            if (currentCity == beginCity)
            {
                this.badCityList.Clear();
                this.oldRoadList.Clear();
                this.nextCity = null;
                this.currentCity = beginCity;
                this.is_endSearch = false;
            }
            else
            {
                //Позиционной движение к предыдущему городу
                Point p = EditPosition(k, b, posX, posY, nextCity.x + nextCity.width / 2, nextCity.y + nextCity.height / 2);
                posX = p.posX;
                posY = p.posY;
            }
        }

        public void SearchPrevCity()
        {
            Thread.Sleep(rand.Next(100, 3000));
            nextCity = oldRoadList[oldRoadList.Count - 1].bCity;
            oldRoadList.RemoveAt(oldRoadList.Count - 1);
            EditPath(posX, posY, nextCity.x + nextCity.width / 2, nextCity.y + nextCity.height / 2);
        }

        //Функция для нахождения параметров прямой (y = kx + b)
        private void EditPath(int currentX, int currentY, int nextX, int nextY)
        {
            //Console.WriteLine("Перерасчет");
            this.k = (Convert.ToDouble(nextY) - Convert.ToDouble(currentY)) / (Convert.ToDouble(nextX) - Convert.ToDouble(currentX));
            this.b = Convert.ToDouble(currentY) - k * Convert.ToDouble(currentX);
        }

        //Функция для изменения координат точки
        private Point EditPosition(double k, double b, int currentX, int currentY, int nextX, int nextY)
        {
            if (nextX > currentX)
            {
                currentX++;
                try
                {
                    currentY = Convert.ToInt32(k * currentX + b);
                }
                catch (System.OverflowException)
                {
                    Console.WriteLine("Слишком близко! (+)");
                    EditPath(currentX, currentY, nextX, nextY);
                }
            }
            else
            {
                currentX--;
                try
                {
                    currentY = Convert.ToInt32(k * currentX + b);
                }
                catch (System.OverflowException)
                {
                    Console.WriteLine("Слишком близко! (-)");
                    EditPath(currentX, currentY, nextX, nextY);
                }
            }
            return new Point(currentX, currentY);
        }
    }
}
