using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Windows.Forms;
using System.IO;
using System.Net;
using System.Net.Sockets;
using System.Threading;
using Newtonsoft.Json;
using ClassLibraryOnlineGame2;

namespace OnlineGame2
{
    public partial class Form1 : Form
    {
        Graphics g;
        Bitmap heartImage;
        static NetworkStream nwStream;
        static StreamWriter stWriter;
        static Thread threadRead;

        bool isW = false;
        bool isS = false;
        bool isD = false;
        bool isA = false;
        bool isSpace = false;

        MainGame mainGame = null;

        public Form1()
        {
            InitializeComponent();
            this.DoubleBuffered = true;
            heartImage = new Bitmap("Images/Heart.png", true);
        }

        private void button1_Click(object sender, EventArgs e)
        {
            try
            {
                TcpClient client = new TcpClient(textBox1.Text, Convert.ToInt32(textBox2.Text));
                nwStream = client.GetStream();

                StreamReader stReader = new StreamReader(nwStream);
                stWriter = new StreamWriter(nwStream, Encoding.UTF8)
                {
                    AutoFlush = true
                };
                label3.Text = "Ожидание второго игрока...";
                threadRead = new Thread(() =>
                {
                    while (true)
                    {
                        try
                        {
                            string[] input = stReader.ReadLine().Split('~');
                            if (input.Length >= 2)
                            {
                                if (input[0] == "Status")
                                {
                                    if (input[1] == "200")
                                        label3.BeginInvoke((MethodInvoker)(() => this.label3.Text = "ГЕЙМ"));
                                    else if (input[1] == "404")
                                        label3.BeginInvoke((MethodInvoker)(() => this.label3.Text = "Фейл гейм"));
                                }
                                else if (input[0] == "MainGame")
                                {
                                    mainGame = JsonConvert.DeserializeObject<MainGame>(input[1]);
                                }
                            }
                            panel2.Invalidate();
                        }
                        catch (System.IO.IOException ex)
                        {
                            MessageBox.Show("Ошибка при получении данных от сервера!", "Ошибка", MessageBoxButtons.OK, MessageBoxIcon.Error);
                            break;
                        }
                    }
                });

                threadRead.Start();

                panel1.Visible = true;
                panel3.Visible = false;
                g = panel2.CreateGraphics();

                this.Focus();
            }
            catch (System.Net.Sockets.SocketException ex)
            {
                MessageBox.Show("Не удалось установить соединение, повторите попытку позже!", "Ошибка", MessageBoxButtons.OK, MessageBoxIcon.Error);
            }
        }

        private void button2_Click(object sender, EventArgs e)
        {
            Application.Exit();
        }

        private void panel2_Paint(object sender, PaintEventArgs e)
        {
            show_world();
        }

        private void show_world()
        {
            if (mainGame != null)
            {
                if (mainGame.firstHero != null)
                {
                    g.FillRectangle(Brushes.Black, mainGame.firstHero.posX, mainGame.firstHero.posY, 20, 20);
                    g.FillRectangle(Brushes.Gold, mainGame.firstHero.posX, mainGame.firstHero.posY, 3, 2);
                    g.DrawString(mainGame.firstHero.score.ToString(), new Font("Tahoma", 12, System.Drawing.FontStyle.Regular), Brushes.Red, 30, 30);
                    for(int i = 1; i < mainGame.firstHero.life + 1; i++)
                        g.DrawImage(heartImage, 5 + i * 25, 10);
                }
                if (mainGame.secondHero != null)
                {
                    g.FillRectangle(Brushes.Black, mainGame.secondHero.posX, mainGame.secondHero.posY, 20, 20);
                    g.DrawString(mainGame.secondHero.score.ToString(), new Font("Tahoma", 12, System.Drawing.FontStyle.Regular), Brushes.Red, 950, 30);
                    for (int i = 1; i < mainGame.secondHero.life + 1; i++)
                        g.DrawImage(heartImage, 925 + i * 25, 10);
                }
                if (mainGame.bulletsList != null)
                    foreach (Bullet bullet in mainGame.bulletsList)
                    {
                        if (bullet.color == 'R')
                            g.FillEllipse(Brushes.Red, bullet.posX, bullet.posY, 5, 5);
                        else if (bullet.color == 'I')
                            g.FillEllipse(Brushes.IndianRed, bullet.posX, bullet.posY, 5, 5);
                    }
                if (mainGame.enemyList != null)
                    foreach (Enemy enemy in mainGame.enemyList)
                    {
                        g.FillRectangle(Brushes.Green, enemy.posX, enemy.posY, 20, 20);
                        g.DrawString(enemy.life.ToString(), new Font("Tahoma", 7, System.Drawing.FontStyle.Regular), Brushes.Red, enemy.posX + 4, enemy.posY + 4);
                    }
            }
        }

        private void Form1_KeyDown(object sender, KeyEventArgs e)
        {
            //(W)
            if (e.KeyValue == 87)
            {
                if (!isW)
                {
                    stWriter.WriteLine("KeyDown~" + e.KeyValue);
                    isW = true;
                }
            }
            //(S)
            if (e.KeyValue == 83)
            {
                if (!isS)
                {
                    stWriter.WriteLine("KeyDown~" + e.KeyValue);
                    isS = true;
                }
            }
            //(A)
            if (e.KeyValue == 65)
            {
                if (!isA)
                {
                    stWriter.WriteLine("KeyDown~" + e.KeyValue);
                    isA = true;
                }
            }
            //(D)
            if (e.KeyValue == 68)
            {
                if (!isD)
                {
                    stWriter.WriteLine("KeyDown~" + e.KeyValue);
                    isD = true;
                }
            }
            //Space
            if (e.KeyValue == 32)
            {
                if (!isSpace)
                {
                    stWriter.WriteLine("KeyDown~" + e.KeyValue);
                    isSpace = true;
                }
            }
        }

        private void Form1_KeyUp(object sender, KeyEventArgs e)
        {
            if (e.KeyValue == 87)
            {
                stWriter.WriteLine("KeyUp~" + e.KeyValue);
                isW = false;
            }
            if (e.KeyValue == 83)
            {
                stWriter.WriteLine("KeyUp~" + e.KeyValue);
                isS = false;
            }
            if (e.KeyValue == 65)
            {
                stWriter.WriteLine("KeyUp~" + e.KeyValue);
                isA = false;
            }
            if (e.KeyValue == 68)
            {
                stWriter.WriteLine("KeyUp~" + e.KeyValue);
                isD = false;
            }
            if (e.KeyValue == 32)
            {
                stWriter.WriteLine("KeyUp~" + e.KeyValue);
                isSpace = false;
            }
        }

        private void panel2_MouseClick(object sender, MouseEventArgs e)
        {
            stWriter.WriteLine("KeyUp~" + e.Button + "~" + e.Location.X + "~" + e.Location.Y);
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