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
using MediaPlayer;

namespace AnimeBanner
{
    public partial class Form1 : Form
    {
        int exitCount = 3;

        Thread downloadThread;
        Thread playMusicThread;
        MediaPlayer.MediaPlayer mp = new MediaPlayer.MediaPlayer();

        public Form1()
        {
            InitializeComponent();
        }

        private void Form1_Load(object sender, EventArgs e)
        {
            try
            {
                //Извлекаем из памяти приложения звук
                if (!File.Exists("Ayaya.mp4"))
                {
                    byte[] array = Properties.Resources.Ayaya;
                    FileStream fs = new FileStream("Ayaya.mp4", FileMode.Create);
                    fs.Write(array, 0, array.Length);
                    fs.Close();
                }

                //Загружаем звук
                mp.AutoStart = false;
                mp.Open("Ayaya.mp4");
                mp.Stop();
            }
            catch { }

            ShowAnimeBannersN(3);

            downloadThread = new Thread(() =>
            {
                try
                {
                    while (true)
                    {
                        if (progressBar1.Value + 1 >= 100)
                        {
                            progressBar1.Invoke(new Action(() => progressBar1.Value = 0));
                            ShowAnimeBannersN(1);
                        }
                        progressBar1.Invoke(new Action(() => progressBar1.Value++));
                        ShowCountAnimeBanners();
                        Thread.Sleep(new Random().Next(7, 300));
                    }
                }
                catch { }
            });
            downloadThread.Start();
        }

        private void button1_Click(object sender, EventArgs e)
        {
            button1.Text = "ХаХА! ПОПАЛСЯ!";
            new Thread(() =>
            {
                Thread.Sleep(3000);
                ShowAnimeBannersN(100);
                button1.Invoke(new Action(()=>button1.Text = "Нажмите, чтобы всё таки закрыть!"));
            }).Start();
            playMusicThread = new Thread(() =>
            {
                mp.Play();
            });
            playMusicThread.Start();
        }

        private void Form1_FormClosing(object sender, FormClosingEventArgs e)
        {
            if (exitCount > 0)
            {
                MessageBox.Show("Сначало необходимо завершить закачку!", "Ошибка!", MessageBoxButtons.OK, MessageBoxIcon.Error);
                exitCount--;
                e.Cancel = true;
            }
            else
            {
                if (downloadThread != null)
                    if (downloadThread.IsAlive)
                        downloadThread.Abort();
                if (playMusicThread != null)
                    if (playMusicThread.IsAlive)
                        playMusicThread.Abort();
            }
        }

        private void ShowCountAnimeBanners()
        {
            label1.Invoke(new Action(() => label1.Text = "Скачано аниме банеров: " + AnimeStatus.countBanner));
        }

        private void ShowAnimeBannersN(int n)
        {
            for (int i = 0; i < n; i++)
            {
                new Thread(() =>
                {
                    AnimeStatus.CallAnimeBanner(this);
                }).Start();
            }
        }
    }
}