using System;
using System.Collections.Generic;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Threading;
using System.Windows.Forms;

namespace MiniAirplane
{
    class Game
    {
        private List<Enemy> enemyList = new List<Enemy>();

        private Thread thread;

        private Form mainForm;

        private Bitmap background;
        private Bitmap plane;
        private Bitmap enemyPlane;

        private Graphics g;

        private Rectangle objPlane; //Расположение самолетика

        private PictureBox pictureBox;

        private Label lblScore;

        private int enemyCount;

        private int score = 0;

        private bool isGame = true;

        public Game(Form mainForm, PictureBox pictureBox, Label label, int enemyCount = 7)
        {
            score = 0;
            isGame = true;
            this.mainForm = mainForm;
            this.pictureBox = pictureBox;
            this.lblScore = label;
            this.enemyCount = enemyCount;
            try
            {
                //Загрузка изображений
                plane = Properties.Resources.plane;
                enemyPlane = Properties.Resources.enemy;
                //Прозрачный фон у самолетика
                plane.MakeTransparent();

                background = Properties.Resources.background;

                pictureBox.BackgroundImage = new Bitmap(mainForm.Width, mainForm.Height);
                g = Graphics.FromImage(pictureBox.BackgroundImage);
                //mainForm.DoubleBuffered = true;

                objPlane.X = pictureBox.Width / 3;
                objPlane.Y = pictureBox.Height / 2;
                objPlane.Width = plane.Width;
                objPlane.Height = plane.Height;
            }
            catch (Exception e)
            {
                MessageBox.Show("Какие-то проблемы!\n" + e.Message + "\nПроблема " + e.StackTrace);
            }
        }

        public void StartGame()
        {
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
                    Thread.Sleep(200);
                }
            });
            thread.Start();
        }

        public void StopGame()
        {
            isGame = false;
            enemyList.Clear();
            if (thread.IsAlive)
            {
                thread.Abort();
            }
        }

        private void ShowMainAirplane()
        {
            g.Clear(Color.White);
            g.DrawImage(background, 0, 0);
            g.DrawImage(plane, objPlane.X, objPlane.Y);
            pictureBox.Invoke(new Action(() => { pictureBox.Refresh(); }));
        }

        private void GenerateEnemy()
        {
            if (enemyList.Count < enemyCount)
            {
                Random rand = new Random();
                Enemy enemy = new Enemy();
                enemy.posX = pictureBox.Width + rand.Next(10, 300);
                enemy.posY = rand.Next(10, pictureBox.Height - 10);
                //enemy.posX = 100;
                //enemy.posY = 100;
                enemyList.Add(enemy);
            }
        }

        private void ShowEnemy()
        {
            foreach (Enemy enemy in enemyList)
            {
                g.DrawImage(enemyPlane, enemy.posX, enemy.posY);
            }
            pictureBox.Invoke(new Action(() => { pictureBox.Refresh(); }));
        }

        private void MoveEnemy()
        {
            foreach (Enemy enemy in enemyList.ToArray())
            {
                enemy.posX -= 5;
                if (enemy.posX <= 30)
                    enemyList.Remove(enemy);
            }
        }

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

        private void AppendScore()
        {
            if (isGame)
                score += 10;
        }

        private void ShowScore()
        {
            lblScore.Text = score.ToString();
        }

        public void MoveAirpalaneX(int dX)
        {
            objPlane.X += dX;
        }

        public void MoveAirpalaneY(int dY)
        {
            objPlane.Y += dY;
        }
    }
}
