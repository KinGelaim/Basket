using System;
using System.Collections.Generic;
using System.Drawing;
using System.Threading;
using System.Windows.Forms;

namespace MiniAirplane
{
    class Game
    {
        //Коллекция для хранения врагов
        private List<Enemy> enemyList = new List<Enemy>();

        //Поток для всей игровой логики
        private Thread thread;

        //Для загрузки изображений
        private Bitmap background;
        private Bitmap plane;
        private Bitmap enemyPlane;

        private Rectangle objPlane; //Расположение самолетика

        //Переменная для отрисовки
        private Graphics g;        

        //Элементы родительской формы
        private Form mainForm;

        private PictureBox pictureBox;

        private Label lblScore;

        //Переменные для мини игры
        private int score = 0;          //Кол-во очков

        private int enemyCount;         //Кол-во врагов

        private int pauseThreadGame;    //Время паузы

        private bool isGame = false;    //Идёт игра или уже закончилась

        /// <summary>
        /// Конструктор класса для создания игры
        /// </summary>
        /// <param name="mainForm">Главная форма</param>
        /// <param name="pictureBox">Область на которой будет происходить вся отрисовка</param>
        /// <param name="label">Отображение текста очков</param>
        /// <param name="enemyCount">Необязательный аргумент с кол-вом врагов</param>
        /// <param name="pauseThreadGame">Необязательный аргумент с милисекундами для скорости игры (чем больше число, тем медленее игра)</param>
        public Game(Form mainForm, PictureBox pictureBox, Label label, int enemyCount = 7, int pauseThreadGame = 200)
        {
            //Обнуляем параметры и заполняем поля
            score = 0;
            isGame = false;
            this.mainForm = mainForm;
            this.pictureBox = pictureBox;
            this.lblScore = label;
            this.enemyCount = enemyCount;
            this.pauseThreadGame = 50;
            if (pauseThreadGame > 50)
                this.pauseThreadGame = pauseThreadGame;
            try
            {
                //Загрузка изображений
                plane = Properties.Resources.plane;
                enemyPlane = Properties.Resources.enemy;
                //Прозрачный фон у изображение
                plane.MakeTransparent();
                enemyPlane.MakeTransparent();

                //Загрузка фона
                background = Properties.Resources.background;

                //Создание области для рисования (будем рисовать прям на фоном изображении)
                pictureBox.BackgroundImage = new Bitmap(pictureBox.Width, pictureBox.Height);
                g = Graphics.FromImage(pictureBox.BackgroundImage);
                //mainForm.DoubleBuffered = true;

                BeginSettingsPlane();
            }
            catch (Exception e)
            {
                MessageBox.Show("Какие-то проблемы!\n" + e.Message + "\nПроблема " + e.StackTrace);
            }
        }

        //Методы для самолётика (начальные параметры, изменение координат по оси X и оси Y)
        private void BeginSettingsPlane()
        {
            //Присвоение параметров для самолётика
            objPlane.X = pictureBox.Width / 3;
            objPlane.Y = pictureBox.Height / 2;
            objPlane.Width = plane.Width;
            objPlane.Height = plane.Height;
        }

        public void MoveAirpalaneX(int dX)
        {
            objPlane.X += dX;
        }

        public void MoveAirpalaneY(int dY)
        {
            objPlane.Y += dY;
        }

        //Метод для старта игры
        public void StartGame()
        {
            BeginSettingsPlane();
            score = 0;
            isGame = true;
            thread = new Thread(() =>
            {
                while (true)
                {
                    ShowMainAirplane();
                    GenerateEnemy();
                    ShowEnemy();
                    MoveEnemy();
                    CheckEnemy();
                    AppendScore();
                    ShowScore();
                    Thread.Sleep(100);
                }
            });
            thread.Start();
        }

        //Метод для остановки игры
        public void StopGame()
        {
            isGame = false;
            enemyList.Clear();
            if (thread.IsAlive)
            {
                thread.Abort();
            }
        }

        //Отрисовка ГГ
        private void ShowMainAirplane()
        {
            g.Clear(Color.White);
            g.DrawImage(background, 0, 0);
            g.DrawImage(plane, objPlane.X, objPlane.Y);
            pictureBox.Invoke(new Action(() => { pictureBox.Refresh(); }));
        }

        //Создание врагов
        private void GenerateEnemy()
        {
            if (enemyList.Count < enemyCount)
            {
                Random rand = new Random();
                Enemy enemy = new Enemy();
                enemy.posX = pictureBox.Width + rand.Next(10, 300);
                enemy.posY = rand.Next(10, pictureBox.Height - 10);
                enemyList.Add(enemy);
            }
        }

        //Отображение врагов
        private void ShowEnemy()
        {
            foreach (Enemy enemy in enemyList)
            {
                g.DrawImage(enemyPlane, enemy.posX, enemy.posY);
            }
            pictureBox.Invoke(new Action(() => { pictureBox.Refresh(); }));
        }

        //Перемещение врагов
        private void MoveEnemy()
        {
            foreach (Enemy enemy in enemyList.ToArray())
            {
                enemy.posX -= 5;
                if (enemy.posX <= 30)
                    enemyList.Remove(enemy);
            }
        }

        //Проверка на попадание врагов в ГГ
        private void CheckEnemy()
        {
            foreach (Enemy enemy in enemyList)
            {
                if (objPlane.X + objPlane.Width > enemy.posX && objPlane.Y < enemy.posY + enemyPlane.Height && objPlane.Y + objPlane.Height > enemy.posY + enemyPlane.Height ||
                    objPlane.X + objPlane.Width > enemy.posX && objPlane.Y + objPlane.Height > enemy.posY && objPlane.Y < enemy.posY)
                {
                    MessageBox.Show("Увы, но Вы проиграли!\nВаш счёт составил: " + score);
                    this.StopGame();
                }
            }
        }

        //Увеличение кол-ва очков
        private void AppendScore(int dScore = 1)
        {
            if (isGame)
                score += dScore;
        }

        //Отображение кол-ва очков
        private void ShowScore()
        {
            lblScore.Text = score.ToString();
        }
    }
}