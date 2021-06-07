using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Windows.Forms;
using System.Net;
using System.Net.Sockets;
using System.Threading;
using System.IO;
using Newtonsoft.Json;

namespace OnlineGame
{
    public partial class Form1 : Form
    {
        string SERVER_IP = "";
        string SERVER_PORT = "";
        static NetworkStream nwStream;
        static StreamWriter stWriter;
        static Thread threadRead;

        private System.Drawing.Graphics g;

        FlyingHero mainFlayingHero = null;
        FlyingHero twoFlayingHero = null;

        public Form1()
        {
            InitializeComponent();
        }

        //Начать игру
        private void button1_Click(object sender, EventArgs e)
        {
            FormSettings fSettings = new FormSettings();
            fSettings.ShowDialog();
            if(fSettings.isExit)
            {
                SERVER_IP = fSettings.ip;
                SERVER_PORT = fSettings.port;

                try
                {
                    TcpClient client = new TcpClient(SERVER_IP, Convert.ToInt32(SERVER_PORT));
                    nwStream = client.GetStream();

                    StreamReader stReader = new StreamReader(nwStream);

                    FormLoading fLoading = new FormLoading(stReader);
                    fLoading.ShowDialog();

                    if (fLoading.status == "ok")
                    {
                        stWriter = new StreamWriter(nwStream, Encoding.UTF8)
                        {
                            AutoFlush = true
                        };
                        threadRead = new Thread(() =>
                        {
                            while (true)
                            {
                                try
                                {
                                    string[] input = stReader.ReadLine().Split('~');
                                    if (input.Length >= 2)
                                        if (input[0] == "Client")
                                            twoFlayingHero = JsonConvert.DeserializeObject<FlyingHero>(input[1]);
                                        else if (input[0] == "Server")
                                            FlyingMob.allMob = JsonConvert.DeserializeObject<List<FlyingMob>>(input[1]);
                                        else if (input[0] == "Answer")
                                            mainFlayingHero.score = JsonConvert.DeserializeObject<int>(input[1]);
                                        else if (input[0] == "AnswerB")
                                            mainFlayingHero.bulletsList = JsonConvert.DeserializeObject<List<Bullet>>(input[1]);
                                }
                                catch (System.IO.IOException ex)
                                {
                                    MessageBox.Show("Ошибка при получении данных от сервера!", "Ошибка", MessageBoxButtons.OK, MessageBoxIcon.Error);
                                    break;
                                }
                            }
                        });

                        threadRead.Start();

                        panel1.Visible = false;
                        panel2.Visible = true;
                        this.Focus();

                        timer1.Interval = 100;

                        g = panel3.CreateGraphics();

                        mainFlayingHero = new FlyingHero(3, 100, 100);

                        timer1.Start();

                        timer2.Interval = 100;
                        timer2.Start();
                    }
                    else if (fLoading.status == "abort")
                        MessageBox.Show("Ожидание игрока отменено");
                    else if (fLoading.status == "error")
                        MessageBox.Show("Ошибка при получении данных");
                    else
                        MessageBox.Show("Неизвестная инфа: " + fLoading.status);
                }
                catch (System.Net.Sockets.SocketException ex)
                {
                    MessageBox.Show("Не удалось установить соединение, повторите попытку позже!", "Ошибка", MessageBoxButtons.OK, MessageBoxIcon.Error);
                }
            }
        }

        //Выход из игры
        private void button2_Click(object sender, EventArgs e)
        {
            Application.Exit();
        }

        private void panel3_Paint(object sender, PaintEventArgs e)
        {
            show_heroes(g);
            show_mobs(g);
        }

        private void timer1_Tick(object sender, EventArgs e)
        {
            panel3.Invalidate();
        }

        private void show_heroes(Graphics g)
        {
            if(mainFlayingHero != null)
            {
                if (mainFlayingHero.life > 0)
                {
                    //g.Clear(g);
                    g.DrawRectangle(Pens.Black, mainFlayingHero.posX, mainFlayingHero.posY, 30, 10);
                    g.DrawRectangle(Pens.Black, mainFlayingHero.posX + 2, mainFlayingHero.posY - 5, 5, 5);
                    g.DrawRectangle(Pens.Black, mainFlayingHero.posX + 2, mainFlayingHero.posY + 10, 5, 5);
                    g.DrawRectangle(Pens.Black, mainFlayingHero.posX + 15, mainFlayingHero.posY - 15, 7, 15);
                    g.DrawRectangle(Pens.Black, mainFlayingHero.posX + 15, mainFlayingHero.posY + 10, 7, 15);
                    //Пули
                    foreach (Bullet bullet in mainFlayingHero.bulletsList.ToArray())
                    {
                        g.DrawRectangle(Pens.Red, bullet.posX, bullet.posY, 5, 5);
                        bullet.posX += 12;
                        if (bullet.posX > panel3.Width)
                            mainFlayingHero.bulletsList.Remove(bullet);
                    }
                    //Жизни
                    int k = 27;
                    for (int i = 0; i < mainFlayingHero.life; i++)
                    {
                        Point[] p = new Point[15];

                        p[0].X = 18 + i * k;
                        p[0].Y = 10;
                        p[1].X = 24 + i * k;
                        p[1].Y = 7;
                        p[2].X = 27 + i * k;
                        p[2].Y = 7;
                        p[3].X = 30 + i * k;
                        p[3].Y = 9;
                        p[4].X = 30 + i * k;
                        p[4].Y = 12;
                        p[5].X = 25 + i * k;
                        p[5].Y = 16;
                        p[6].X = 22 + i * k;
                        p[6].Y = 18;
                        p[7].X = 20 + i * k;
                        p[7].Y = 20;
                        p[8].X = 18 + i * k;
                        p[8].Y = 18;
                        p[9].X = 15 + i * k;
                        p[9].Y = 16;
                        p[10].X = 10 + i * k;
                        p[10].Y = 12;
                        p[11].X = 10 + i * k;
                        p[11].Y = 9;
                        p[12].X = 13 + i * k;
                        p[12].Y = 7;
                        p[13].X = 16 + i * k;
                        p[13].Y = 7;
                        p[14].X = 20 + i * k;
                        p[14].Y = 10;

                        g.FillPolygon(Brushes.Red, p);
                    }
                    //Очки
                    g.DrawString(mainFlayingHero.score.ToString(), new Font("Tahoma", 12, System.Drawing.FontStyle.Regular), Brushes.Blue, 18, 30);
                }
                else
                {
                    g.DrawString("Ханасики", new Font("Tahoma", 12, System.Drawing.FontStyle.Regular), Brushes.Red, 18, 10);
                    g.DrawString(mainFlayingHero.score.ToString(), new Font("Tahoma", 12, System.Drawing.FontStyle.Regular), Brushes.Blue, 18, 30);
                }
            }
            if (twoFlayingHero != null)
            {
                if (twoFlayingHero.life > 0)
                {
                    //g.Clear(g);
                    g.DrawRectangle(Pens.Green, twoFlayingHero.posX, twoFlayingHero.posY, 30, 10);
                    g.DrawRectangle(Pens.Green, twoFlayingHero.posX + 2, twoFlayingHero.posY - 5, 5, 5);
                    g.DrawRectangle(Pens.Green, twoFlayingHero.posX + 2, twoFlayingHero.posY + 10, 5, 5);
                    g.DrawRectangle(Pens.Green, twoFlayingHero.posX + 15, twoFlayingHero.posY - 15, 7, 15);
                    g.DrawRectangle(Pens.Green, twoFlayingHero.posX + 15, twoFlayingHero.posY + 10, 7, 15);
                    //Пули
                    foreach (Bullet bullet in twoFlayingHero.bulletsList.ToArray())
                    {
                        g.DrawRectangle(Pens.Pink, bullet.posX, bullet.posY, 5, 5);
                        bullet.posX += 12;
                        if (bullet.posX > panel3.Width)
                            twoFlayingHero.bulletsList.Remove(bullet);
                    }
                    //Жизни
                    int k = 27;
                    int smeshenie = 870;
                    for (int i = 0; i < twoFlayingHero.life; i++)
                    {
                        Point[] p = new Point[15];

                        p[0].X = 18 + smeshenie + i * k;
                        p[0].Y = 10;
                        p[1].X = 24 + smeshenie + i * k;
                        p[1].Y = 7;
                        p[2].X = 27 + smeshenie + i * k;
                        p[2].Y = 7;
                        p[3].X = 30 + smeshenie + i * k;
                        p[3].Y = 9;
                        p[4].X = 30 + smeshenie + i * k;
                        p[4].Y = 12;
                        p[5].X = 25 + smeshenie + i * k;
                        p[5].Y = 16;
                        p[6].X = 22 + smeshenie + i * k;
                        p[6].Y = 18;
                        p[7].X = 20 + smeshenie + i * k;
                        p[7].Y = 20;
                        p[8].X = 18 + smeshenie + i * k;
                        p[8].Y = 18;
                        p[9].X = 15 + smeshenie + i * k;
                        p[9].Y = 16;
                        p[10].X = 10 + smeshenie + i * k;
                        p[10].Y = 12;
                        p[11].X = 10 + smeshenie + i * k;
                        p[11].Y = 9;
                        p[12].X = 13 + smeshenie + i * k;
                        p[12].Y = 7;
                        p[13].X = 16 + smeshenie + i * k;
                        p[13].Y = 7;
                        p[14].X = 20 + smeshenie + i * k;
                        p[14].Y = 10;

                        g.FillPolygon(Brushes.Green, p);
                    }
                    //Очки
                    g.DrawString(twoFlayingHero.score.ToString(), new Font("Tahoma", 12, System.Drawing.FontStyle.Regular), Brushes.Blue, 18 + smeshenie, 30);
                }
                else
                {
                    g.DrawString("Ханасики", new Font("Tahoma", 12, System.Drawing.FontStyle.Regular), Brushes.Red, 18, 10);
                    g.DrawString(mainFlayingHero.score.ToString(), new Font("Tahoma", 12, System.Drawing.FontStyle.Regular), Brushes.Blue, 18, 30);
                }
            }
        }

        private void show_mobs(Graphics g)
        {
            if (FlyingMob.allMob.Count > 0)
            {
                foreach (FlyingMob fl in FlyingMob.allMob)
                {
                    g.FillRectangle(Brushes.Black, fl.posX-10, fl.posY-10, 20, 20);
                    g.DrawString(fl.life.ToString(), new Font("Tahoma", 10, System.Drawing.FontStyle.Regular), Brushes.Red, fl.posX - 8, fl.posY - 8);
                    foreach (Bullet bullet in fl.bulletsList.ToArray())
                    {
                        g.FillRectangle(Brushes.OrangeRed, bullet.posX, bullet.posY, 5, 5);
                        if(bullet.posX < mainFlayingHero.posX + 30 && bullet.posY < mainFlayingHero.posY + 25 && bullet.posX > mainFlayingHero.posX && bullet.posY > mainFlayingHero.posY - 15
                            || bullet.posX + 5 > mainFlayingHero.posX && bullet.posX + 5 < mainFlayingHero.posX + 30 && bullet.posY < mainFlayingHero.posY + 25 && bullet.posY > mainFlayingHero.posY - 15
                            || bullet.posX + 5 > mainFlayingHero.posX && bullet.posX + 5 < mainFlayingHero.posX + 30 && bullet.posY + 5 > mainFlayingHero.posY - 15 && bullet.posY + 5 < mainFlayingHero.posY + 25
                            || bullet.posX > mainFlayingHero.posX && bullet.posX < mainFlayingHero.posX + 30 && bullet.posY + 5 > mainFlayingHero.posY - 15 && bullet.posY + 5 < mainFlayingHero.posY + 25)
                        {
                            mainFlayingHero.life--;
                            //if(mainFlayingHero.life <= 0)
                            fl.bulletsList.Remove(bullet);
                            stWriter.WriteLine("AnswerMob~" + JsonConvert.SerializeObject(FlyingMob.allMob));
                        }
                    }
                }
            }
        }

        private void Form1_KeyDown(object sender, KeyEventArgs e)
        {
            //MessageBox.Show("form " + e.KeyValue);
            //(W)
            if (e.KeyValue == 87)
            {
                if (mainFlayingHero.posY - 3 > 10)
                    mainFlayingHero.posY-=3;
            }
            //(S)
            if (e.KeyValue == 83)
            {
                if (mainFlayingHero.posY + 3 < panel3.Height - 25)
                    mainFlayingHero.posY+=3;
            }
            //(A)
            if (e.KeyValue == 65)
            {
                if (mainFlayingHero.posX - 3 > 0)
                    mainFlayingHero.posX-=3;
            }
            //(D)
            if (e.KeyValue == 68)
            {
                if (mainFlayingHero.posX + 3 < 170)
                    mainFlayingHero.posX+=3;
            }
            //Space
            if (e.KeyValue == 32)
            {
                if (mainFlayingHero.bulletsList.Count < 8)
                {
                    Bullet newBullet = new Bullet(mainFlayingHero.posX + 30, mainFlayingHero.posY + 2);
                    mainFlayingHero.bulletsList.Add(newBullet);
                }
            }
        }

        private void timer2_Tick(object sender, EventArgs e)
        {
            //Отправка данных на сервер
            string message = JsonConvert.SerializeObject(mainFlayingHero);
            stWriter.WriteLine("Client~" + message);
        }

        private void Form1_FormClosing(object sender, FormClosingEventArgs e)
        {
            try
            {
                if (threadRead.IsAlive)
                    threadRead.Abort();
            }
            catch (Exception exception)
            {

            }
            try
            {
                stWriter.Close();
            }
            catch (Exception exception)
            {

            }
            try
            {
                nwStream.Close();
            }
            catch (Exception exception)
            {

            }
        }
    }
}
