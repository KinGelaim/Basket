using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Windows.Forms;
using System.Threading;

namespace RacingGame
{
    public partial class Form1 : Form
    {
        [System.Runtime.InteropServices.DllImport("winmm.dll")]
        private static extern Boolean PlaySound(string path, int module, int flag);

        Thread createPaint;
        Graphics g;
        Bitmap bitmap;
        Random rand = new Random();

        //Настройки игры
        int statusGame = 0;     //0 - главное меню
                                //1 - начало игры (здесь производятся начальные настройки)
                                //2 - игра на паузе
                                //3 - идет игра
                                //4 - конец игры
        int streetK = 0;
        bool isDanger = false;
        bool isDangerLeft = false;
        int timeToDanger = 7000;
        int timeToMonstr = 0;
        
        //Параметры машинки (потом можно вынести в отдельный класс)
        int heroPosX;
        int heroPosY;
        int heroScore = 0;
        int heroLife = 7;
        int heroStorageLight = 100;
        bool heroIsLight = false;
        bool heroIsLeft = false;
        bool heroIsRight = false;

        List<Cactus> cactusList = new List<Cactus>();

        List<Enemy> enemyList = new List<Enemy>();

        public Form1()
        {
            InitializeComponent();
            //g = pictureBox1.CreateGraphics();
            statusGame = 0;
            //Создаем битмап
            bitmap = new Bitmap(pictureBox1.Width, pictureBox1.Height);
            g = Graphics.FromImage(bitmap);
        }

        private void Form1_Load(object sender, EventArgs e)
        {
            //this.WindowState = FormWindowState.Minimized;
            //this.FormBorderStyle = System.Windows.Forms.FormBorderStyle.None;
            createPaint = new Thread(() =>
            {
                //while (true)
                {
                    //pictureBox1.Invalidate();
                    Thread.Sleep(100);
                }
            });
            //createPaint.Start();
        }

        private void CreateMap(int speedGame)
        {
            //Очищаем экран
            g.Clear(Color.White);
            //Рисуем дорогу
            g.DrawLine(Pens.Black, pictureBox1.Width / 2 - pictureBox1.Width / 3, pictureBox1.Height, pictureBox1.Width / 2 - pictureBox1.Width / 4, 0);
            g.DrawLine(Pens.Black, pictureBox1.Width / 2 + pictureBox1.Width / 3, pictureBox1.Height, pictureBox1.Width / 2 + pictureBox1.Width / 4, 0);
            for (int i = streetK; i < pictureBox1.Height; i += 70)
            {
                g.DrawLine(Pens.Black, pictureBox1.Width / 2, pictureBox1.Height - i + 30, pictureBox1.Width / 2, pictureBox1.Height - i);
                streetK -= 3;
                if (streetK <= -100)
                    streetK = 0;
            }
            //Рисуем кактусы
            if (cactusList.Count < 7)
                if (rand.Next(0, 100) > 80)
                    if (rand.Next(0, 100) < 50)
                    {
                        Cactus cactus = new Cactus(rand.Next(-10, pictureBox1.Width / 2 - pictureBox1.Width / 3), -30, rand.Next(10, 40), rand.Next(30, 57));
                        for (int i = 0; i < rand.Next(7, 17); i++)
                            cactus.igolkaList.Add(new Igolka(cactus, rand));
                        cactusList.Add(cactus);
                    }
                    else
                    {
                        Cactus cactus = new Cactus(rand.Next(pictureBox1.Width / 2 + pictureBox1.Width / 3, pictureBox1.Width + 10), -30, rand.Next(10, 40), rand.Next(30, 57), false);
                        for (int i = 0; i < rand.Next(7, 17); i++)
                            cactus.igolkaList.Add(new Igolka(cactus, rand));
                        cactusList.Add(cactus);
                    }

            if (cactusList.Count > 0)
                foreach (Cactus cactus in cactusList.ToArray())
                {
                    g.FillRectangle(Brushes.Green, cactus.posX, cactus.posY, cactus.width, cactus.height);
                    if (cactus.isLeft)
                        cactus.posX -= 3;
                    else
                        cactus.posX += 3;
                    cactus.posY += 10;
                    if (cactus.isLeft && cactus.posX < -20 || cactus.posX > pictureBox1.Width + 20 || cactus.posY > pictureBox1.Height)
                        cactusList.Remove(cactus);
                    else
                        foreach (Igolka igolka in cactus.igolkaList)
                            g.DrawLine(Pens.Black, cactus.posX + igolka.bPosX, cactus.posY + igolka.bPosY, cactus.posX + igolka.ePosX, cactus.posY + igolka.ePosY);
                }
            Thread.Sleep(speedGame);
        }

        private void CreateMainMenu(string str = "RACING GAME")
        {
            //Дорога
            CreateMap(0);
            //Рисуем меню
            g.DrawString(str, new Font("Jokerman", 17, System.Drawing.FontStyle.Regular), Brushes.Red, pictureBox1.Width/2 - 95, 30);
            g.FillRectangle(Brushes.LightGray, pictureBox1.Width / 2 - 100, pictureBox1.Height / 4, 200, 40);
            g.DrawRectangle(Pens.Black, pictureBox1.Width / 2 - 100, pictureBox1.Height / 4, 200, 40);
            g.DrawString("NEW GAME", new Font("Jokerman", 17, System.Drawing.FontStyle.Regular), Brushes.Red, pictureBox1.Width / 2 - 70, pictureBox1.Height / 4 + 4);
            g.FillRectangle(Brushes.LightGray, pictureBox1.Width / 2 - 100, pictureBox1.Height / 2, 200, 40);
            g.DrawRectangle(Pens.Black, pictureBox1.Width / 2 - 100, pictureBox1.Height / 2, 200, 40);
            g.DrawString("EXIT", new Font("Jokerman", 17, System.Drawing.FontStyle.Regular), Brushes.Red, pictureBox1.Width / 2 - 30, pictureBox1.Height / 2 + 4);
            CreateKin();
            //MessageBox.Show("ds");
            Thread.Sleep(70);
        }

        private void StartGame()
        {
            //Настройки начала игры
            isDanger = false;
            timeToDanger = 100;
            timeToMonstr = 0;
            heroScore = 0;
            heroLife = 7;
            heroStorageLight = 100;
            heroPosX = pictureBox1.Width / 3 - 40;
            heroPosY = pictureBox1.Height - pictureBox1.Height / 3;
            statusGame = 3;
            cactusList.Clear();
            enemyList.Clear();
        }

        private void PauseGame()
        {
            //Рисуем меню паузы
            g.DrawString("PAUSE GAME", new Font("Jokerman", 17, System.Drawing.FontStyle.Regular), Brushes.Red, pictureBox1.Width / 2 - 95, pictureBox1.Height / 3 - 40);
            g.DrawString("Чтобы продолжить нажмите ESC", new Font("Tahoma", 17, System.Drawing.FontStyle.Regular), Brushes.Red, pictureBox1.Width / 2 - 190, pictureBox1.Height / 3);
            g.FillRectangle(Brushes.LightGray, pictureBox1.Width / 2 - 100, pictureBox1.Height / 2, 200, 40);
            g.DrawRectangle(Pens.Black, pictureBox1.Width / 2 - 100, pictureBox1.Height / 2, 200, 40);
            g.DrawString("EXIT", new Font("Jokerman", 17, System.Drawing.FontStyle.Regular), Brushes.Red, pictureBox1.Width / 2 - 30, pictureBox1.Height / 2 + 4);
            CreateKin();
        }

        private void ResumeGame()
        {
            //Сама игра
            CreateMap(70);
            CreateEnemy();
            CreateHero();
            CreateDanger();
        }

        private void GameOverMenu()
        {
            //Меню выхода
            CreateMainMenu("GAME OVER");
            CreateKin();
        }

        private void CreateHero()
        {
            //Количество жизней
            //g.DrawString((heroLife-4).ToString(), new Font("Jokerman", 17, System.Drawing.FontStyle.Regular), Brushes.Red, 20, 20);
            for (int i = 1; i < heroLife - 3; i++)
            {
                Point[] points = new Point[] {
                    new Point(20 + i * 40, 20),
                    new Point(25 + i * 40, 20),
                    new Point(25 + i * 40, 15),
                    new Point(30 + i * 40, 15),
                    new Point(30 + i * 40, 20),
                    new Point(40 + i * 40, 20),
                    new Point(40 + i * 40, 15),
                    new Point(45 + i * 40, 15),
                    new Point(45 + i * 40, 20),
                    new Point(50 + i * 40, 20),
                    new Point(50 + i * 40, 40),
                    new Point(20 + i * 40, 40)
                };
                g.DrawPolygon(Pens.Black, points);
            }
            //Количество очков
            g.DrawString(heroScore.ToString(), new Font("Jokerman", 17, System.Drawing.FontStyle.Regular), Brushes.Red, 60, 50);
            //Отрисовываем саму тачку
            //Фары
            g.DrawArc(Pens.Black, heroPosX + pictureBox1.Width / 20, heroPosY - 10, 20, 20, 180, 180);
            g.DrawArc(Pens.Black, heroPosX + pictureBox1.Width / 7, heroPosY - 10, 20, 20, 180, 180);
            //Корпус
            g.DrawRectangle(Pens.Black, heroPosX, heroPosY, pictureBox1.Width / 5, pictureBox1.Height / 4);
            g.DrawRectangle(Pens.Black, heroPosX, heroPosY + 40, pictureBox1.Width / 5, pictureBox1.Height / 4);
            //Колеса
            g.DrawArc(Pens.Black, heroPosX - 5, heroPosY + 30, 10, 20, 90, 180);
            g.DrawArc(Pens.Black, heroPosX - 5, heroPosY + pictureBox1.Height / 4 - 9, 10, 20, 90, 180);
            g.DrawArc(Pens.Black, heroPosX + pictureBox1.Width / 5 - 5, heroPosY + 30, 10, 20, 270, 180);
            g.DrawArc(Pens.Black, heroPosX + pictureBox1.Width / 5 - 5, heroPosY + pictureBox1.Height / 4 - 9, 10, 20, 270, 180);
            //Освещение
            g.DrawString(heroStorageLight.ToString(), new Font("Jokerman", 17, System.Drawing.FontStyle.Regular), Brushes.Red, pictureBox1.Width - 100, 20);
            if (heroIsLight && heroStorageLight > 10)
            {
                g.FillPolygon(Brushes.Yellow, new Point[] { 
                    new Point(heroPosX + pictureBox1.Width / 20, heroPosY - 10), 
                    new Point(heroPosX + pictureBox1.Width / 20 - 70, heroPosY - pictureBox1.Height / 3), 
                    new Point(heroPosX + pictureBox1.Width / 20 + 90, heroPosY - pictureBox1.Height / 3), 
                    new Point(heroPosX + pictureBox1.Width / 20 + 20, heroPosY - 10) 
                });
                g.FillPolygon(Brushes.Yellow, new Point[] { 
                    new Point(heroPosX + pictureBox1.Width / 7, heroPosY - 10), 
                    new Point(heroPosX + pictureBox1.Width / 7 - 70, heroPosY - pictureBox1.Height / 3), 
                    new Point(heroPosX + pictureBox1.Width / 7 + 90, heroPosY - pictureBox1.Height / 3), 
                    new Point(heroPosX + pictureBox1.Width / 7 + 20, heroPosY - 10) 
                });
                if (heroStorageLight - 10 >= 0)
                    heroStorageLight -= 10;
            }
            //Движение
            if (heroIsLeft)
                if (pictureBox1.Width / 2 - pictureBox1.Width / 3 + 27 < heroPosX - 10)
                    heroPosX -= 10;
            if (heroIsRight)
                if (pictureBox1.Width / 2 + pictureBox1.Width / 3 - pictureBox1.Width / 5 - 27 > heroPosX + 10)
                    heroPosX += 10;
            //Проверка на соприкосновение с врагом и со светом
            if (enemyList.Count > 0)
                foreach (Enemy enemy in enemyList.ToArray())
                    if (heroIsLight && heroStorageLight > 10 && heroPosY - pictureBox1.Height / 3 < enemy.posY + 40 && (heroPosX + pictureBox1.Width / 20 - 70 > enemy.posX && heroPosX + pictureBox1.Width / 20 - 70 < enemy.posX + pictureBox1.Width / 7 || heroPosX + pictureBox1.Width / 7 + 90 > enemy.posX && heroPosX + pictureBox1.Width / 7 + 90 < enemy.posX + pictureBox1.Width / 7 || heroPosX + pictureBox1.Width / 20 - 70 < enemy.posX && heroPosX + pictureBox1.Width / 7 + 90 > enemy.posX + pictureBox1.Width / 7))
                    {
                        enemyList.Remove(enemy);
                        heroScore += 30;
                    }
                    else if (heroPosY < enemy.posY + 40 && (heroPosX > enemy.posX && heroPosX < enemy.posX + pictureBox1.Width / 7 || heroPosX + pictureBox1.Width / 5 > enemy.posX && heroPosX + pictureBox1.Width / 5 < enemy.posX + pictureBox1.Width / 7 || heroPosX < enemy.posX && heroPosX + pictureBox1.Width / 5 > enemy.posX + pictureBox1.Width / 7))
                    {
                        enemyList.Remove(enemy);
                        heroLife--;
                        if (heroLife <= 4)
                            statusGame = 4;
                    }
            //Повышение очков
            heroScore++;
            //Повышение заряда освещения
            if(heroStorageLight < 100)
                heroStorageLight++;
        }

        private void CreateEnemy()
        {
            if(rand.Next(100) > 97)
                if (rand.Next(100) > 50)
                {
                    Enemy enemy = new Enemy(pictureBox1.Width / 2 - pictureBox1.Width / 4 + 7);
                    enemyList.Add(enemy);
                }
                else
                {
                    Enemy enemy = new Enemy(pictureBox1.Width / 2 + 7, false);
                    enemyList.Add(enemy);
                }
            if (enemyList.Count > 0)
                foreach (Enemy enemy in enemyList.ToArray())
                {
                    g.FillRectangle(Brushes.Black, enemy.posX, enemy.posY, pictureBox1.Width / 7, 40);
                    g.FillEllipse(Brushes.White, enemy.posX + 10, enemy.posY + 10, 15, 15);
                    g.FillEllipse(Brushes.White, enemy.posX + pictureBox1.Width / 7 - 25, enemy.posY + 10, 15, 15);
                    if (enemy.isLeft)
                        enemy.posX -= 1;
                    else
                        enemy.posX += 1;
                    enemy.posY += 10;
                    if (enemy.posY > pictureBox1.Height)
                        enemyList.Remove(enemy);
                }
        }

        private void CreateDanger()
        {
            if(timeToMonstr > 0)
            {
                //Монстр
                if (!isDangerLeft)
                {
                    g.FillPie(Brushes.Green, pictureBox1.Width / 2 + pictureBox1.Width / 4, pictureBox1.Height / 2 - pictureBox1.Height / 3, pictureBox1.Width / 5, pictureBox1.Width / 5, 180, 180);
                    g.FillPie(Brushes.Green, pictureBox1.Width / 2 + 40, pictureBox1.Height / 4, pictureBox1.Width / 2, pictureBox1.Height / 4, 180, 180);
                    g.FillPie(Brushes.Green, pictureBox1.Width / 2 + 40, pictureBox1.Height / 4 + 70, pictureBox1.Width / 2, pictureBox1.Height / 4, 360, 180);
                    g.FillPolygon(Brushes.Gray, new Point[]{
                        new Point(pictureBox1.Width / 2 + 40, pictureBox1.Height / 4 + pictureBox1.Height / 8), 
                        new Point(pictureBox1.Width / 2 + 90, pictureBox1.Height / 4 + pictureBox1.Height / 8 + 50),
                        new Point(pictureBox1.Width / 2 + 140, pictureBox1.Height / 4 + pictureBox1.Height / 8),
                        new Point(pictureBox1.Width / 2 + 190, pictureBox1.Height / 4 + pictureBox1.Height / 8 + 50),
                        new Point(pictureBox1.Width / 2 + 240, pictureBox1.Height / 4+ pictureBox1.Height / 8),
                        new Point(pictureBox1.Width / 2 + 290, pictureBox1.Height / 4+ pictureBox1.Height / 8 + 50),
                        new Point(pictureBox1.Width / 2 + 340, pictureBox1.Height / 4+ pictureBox1.Height / 8)
                    });
                    g.FillPolygon(Brushes.Gray, new Point[]{
                        new Point(pictureBox1.Width / 2 + 90, pictureBox1.Height / 4 + 70 + pictureBox1.Height / 8), 
                        new Point(pictureBox1.Width / 2 + 140, pictureBox1.Height / 4 + 70 - pictureBox1.Height / 8 + 120),
                        new Point(pictureBox1.Width / 2 + 190, pictureBox1.Height / 4 + 70 + pictureBox1.Height / 8),
                        new Point(pictureBox1.Width / 2 + 240, pictureBox1.Height / 4 + 70 - pictureBox1.Height / 8 + 120),
                        new Point(pictureBox1.Width / 2 + 290, pictureBox1.Height / 4 + 70 + pictureBox1.Height / 8)
                    });
                }
                else
                {
                    g.FillPie(Brushes.Green, pictureBox1.Width / 7, pictureBox1.Height / 2 - pictureBox1.Height / 3, pictureBox1.Width / 5, pictureBox1.Width / 5, 180, 180);
                    g.FillPie(Brushes.Green, 40, pictureBox1.Height / 4, pictureBox1.Width / 2, pictureBox1.Height / 4, 180, 180);
                    g.FillPie(Brushes.Green, 40, pictureBox1.Height / 4 + 70, pictureBox1.Width / 2, pictureBox1.Height / 4, 360, 180);
                    g.FillPolygon(Brushes.Gray, new Point[]{
                        new Point(40, pictureBox1.Height / 4 + pictureBox1.Height / 8), 
                        new Point(90, pictureBox1.Height / 4 + pictureBox1.Height / 8 + 50),
                        new Point(140, pictureBox1.Height / 4 + pictureBox1.Height / 8),
                        new Point(190, pictureBox1.Height / 4 + pictureBox1.Height / 8 + 50),
                        new Point(240, pictureBox1.Height / 4 + pictureBox1.Height / 8),
                        new Point(290, pictureBox1.Height / 4 + pictureBox1.Height / 8 + 50),
                        new Point(340, pictureBox1.Height / 4 + pictureBox1.Height / 8),
                        new Point(390, pictureBox1.Height / 4 + pictureBox1.Height / 8 + 50),
                        new Point(440, pictureBox1.Height / 4 + pictureBox1.Height / 8),
                        new Point(490, pictureBox1.Height / 4 + pictureBox1.Height / 8 + 50),
                        new Point(540, pictureBox1.Height / 4 + pictureBox1.Height / 8)
                    });
                        g.FillPolygon(Brushes.Gray, new Point[]{
                        new Point(90, pictureBox1.Height / 4 + 70 + pictureBox1.Height / 8), 
                        new Point(140, pictureBox1.Height / 4 + 70 - pictureBox1.Height / 8 + 120),
                        new Point(190, pictureBox1.Height / 4 + 70 + pictureBox1.Height / 8),
                        new Point(240, pictureBox1.Height / 4 + 70 - pictureBox1.Height / 8 + 120),
                        new Point(290, pictureBox1.Height / 4 + 70 + pictureBox1.Height / 8)
                    });
                }
                timeToMonstr--;
                return;
            }
            if (!isDanger && rand.Next(0,100) > 98)
            {
                if (rand.Next(100) > 50)
                    isDangerLeft = true;
                else
                    isDangerLeft = false;
                isDanger = true;
            }
            if(isDanger && !isDangerLeft)
            {
                //Данджер
                g.FillPolygon(Brushes.Red, new Point[]{
                    new Point(pictureBox1.Width, 0), 
                    new Point(pictureBox1.Width - pictureBox1.Width / 25, pictureBox1.Height / 27),
                    new Point(pictureBox1.Width - pictureBox1.Width / 25, pictureBox1.Height - pictureBox1.Height / 27),
                    new Point(pictureBox1.Width, pictureBox1.Height)
                });
                g.DrawString("D   A   N   G   E   R", new Font("Jokerman", 27, System.Drawing.FontStyle.Regular), Brushes.Red, pictureBox1.Width / 2, pictureBox1.Height / 9);
                //g.RotateTransform(-90);
                /*StringFormat stFormat = new StringFormat();
                stFormat.FormatFlags = StringFormatFlags.DirectionVertical;
                g.DrawString("Stroka", new Font("Aria", 16), new SolidBrush(Color.Black), 150, 50, stFormat);*/
                timeToDanger--;
                if (timeToDanger <= 0)
                {
                    if (heroPosX + pictureBox1.Width / 5 > pictureBox1.Width / 2)
                        heroLife--;
                    timeToMonstr = 10;
                    isDanger = false;
                    timeToDanger = 100;
                }
            }
            else if (isDanger && isDangerLeft)
            {
                g.FillPolygon(Brushes.Red, new Point[]{
                    new Point(0, 0), 
                    new Point(pictureBox1.Width / 25, pictureBox1.Height / 27),
                    new Point(pictureBox1.Width / 25, pictureBox1.Height - pictureBox1.Height / 27),
                    new Point(0, pictureBox1.Height)
                });
                g.DrawString("D   A   N   G   E   R", new Font("Jokerman", 27, System.Drawing.FontStyle.Regular), Brushes.Red, pictureBox1.Width / 2 - pictureBox1.Width / 4, pictureBox1.Height / 9);
                timeToDanger--;
                if (timeToDanger <= 0)
                {
                    if (heroPosX < pictureBox1.Width / 2)
                        heroLife--;
                    timeToMonstr = 10;
                    isDanger = false;
                    timeToDanger = 100;
                }
            }
            if (heroLife <= 4)
                statusGame = 4;
        }

        private void CreateKin()
        {
            //Подпись
            g.DrawString("KinCorporation", new Font("Jokerman", 7, System.Drawing.FontStyle.Regular), Brushes.Red, pictureBox1.Width - 80, pictureBox1.Height - 20);
        }

        private void pictureBox1_Paint(object sender, PaintEventArgs e)
        {
            if (statusGame == 0)
                CreateMainMenu();
            else if (statusGame == 1)
                StartGame();
            else if (statusGame == 2)
                PauseGame();
            else if (statusGame == 3)
                ResumeGame();
            else
                GameOverMenu();
            //Вставляем рисунок
            pictureBox1.Image = bitmap;
        }

        private void Form1_FormClosing(object sender, FormClosingEventArgs e)
        {
            if (createPaint.IsAlive)
                createPaint.Abort();
        }

        private void Form1_ResizeEnd(object sender, EventArgs e)
        {
            bitmap = new Bitmap(pictureBox1.Width, pictureBox1.Height);
            g = Graphics.FromImage(bitmap);
            CreateMap(0);
            cactusList.Clear();
        }

        private void pictureBox1_MouseClick(object sender, MouseEventArgs e)
        {
            if (statusGame == 0 || statusGame == 2 || statusGame == 4)
            {
                if (e.Location.X > pictureBox1.Width / 2 - 100 && e.Location.X < pictureBox1.Width / 2 + 100 && e.Location.Y > pictureBox1.Height / 4 && e.Location.Y < pictureBox1.Height / 4 + 40)
                {
                    //PlaySound(Application.StartupPath + "\\VSHHH.wma", 0, 1);
                    statusGame = 1;
                    pictureBox1.Invalidate();
                }
                if (e.Location.X > pictureBox1.Width / 2 - 100 && e.Location.X < pictureBox1.Width / 2 + 100 && e.Location.Y > pictureBox1.Height / 2 && e.Location.Y < pictureBox1.Height / 2 + 40)
                    Application.Exit();
            }
        }

        private void Form1_KeyDown(object sender, KeyEventArgs e)
        {
            //g.DrawString(e.KeyValue.ToString(), new Font("Jokerman", 7, System.Drawing.FontStyle.Regular), Brushes.Red, pictureBox1.Width - 80, pictureBox1.Height - 50);
            if (e.KeyValue == 27)
                if (statusGame == 3)
                    statusGame = 2;
                else if (statusGame == 2)
                    statusGame = 3;
                else if (statusGame == 0)
                    Application.Exit();
            if (statusGame == 3)
            {
                if (e.KeyValue == 32)
                    heroIsLight = true;
                if (e.KeyValue == 65)
                    heroIsLeft = true;
                if (e.KeyValue == 68)
                    heroIsRight = true;
            }

        }

        private void Form1_KeyUp(object sender, KeyEventArgs e)
        {
            if (e.KeyValue == 32)
                heroIsLight = false;
            if (e.KeyValue == 65)
                heroIsLeft = false;
            if (e.KeyValue == 68)
                heroIsRight = false;
        }
    }
}
