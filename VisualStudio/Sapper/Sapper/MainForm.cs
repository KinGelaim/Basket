using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Windows.Forms;

namespace Sapper
{
    public partial class MainForm : Form
    {
        private int
            MR = 10,    //Количество строк
            MC = 10,    //Количество столбцов
            NM = 10;    //Количество мин

        private const int
            W = 40,     //Ширина одной ячейки
            H = 40;     //Высота одной ячейки

        private int[,] Pole;

        private int nMin;
        private int nFlag;

        //Переменная для определения статуса состояния игры (началась, закончалась и тд)
        private int status;

        private System.Drawing.Graphics g;

        private Timer timer;

        private string strTimer;
        private int m, s;

        public MainForm()
        {
            InitializeComponent();

            createWindow();

            timer = timer1;
            timer.Interval = 1000;
            m = 0;
            s = 0;

            newGame();
        }

        private void createWindow()
        {
            Pole = new int[MR + 2, MC + 2];

            for (int i = 0; i <= MR + 1; i++)
            {
                Pole[i, 0] = -3;
                Pole[i, MC + 1] = -3;
            }

            for (int i = 0; i <= MC + 1; i++)
            {
                Pole[0, i] = -3;
                Pole[MR + 1, i] = -3;
            }

            this.ClientSize = new Size(W * MC + W, H * MR + menuStrip1.Height + H + 50);

            this.MaximumSize = this.ClientSize;
            this.MinimumSize = this.MaximumSize;

            g = panel1.CreateGraphics();
        }

        private void newGame()
        {
            int row, col;
            int n = 0;
            int k;

            for (row = 1; row <= MR; row++)
                for (col = 1; col <= MC; col++)
                    Pole[row, col] = 0;

            Random rand = new Random();

            do
            {
                row = rand.Next(MR) + 1;
                col = rand.Next(MC) + 1;
                if (Pole[row, col] != 9)
                {
                    Pole[row, col] = 9;
                    n++;
                }
            } while (n != NM);

            for (row = 1; row <= MR; row++)
                for (col = 1; col <= MC; col++)
                    if (Pole[row, col] != 9)
                    {
                        k = 0;

                        if (Pole[row + 1, col] == 9) k++;
                        if (Pole[row + 1, col - 1] == 9) k++;
                        if (Pole[row, col - 1] == 9) k++;
                        if (Pole[row - 1, col - 1] == 9) k++;
                        if (Pole[row - 1, col] == 9) k++;
                        if (Pole[row - 1, col + 1] == 9) k++;
                        if (Pole[row, col + 1] == 9) k++;
                        if (Pole[row + 1, col + 1] == 9) k++;

                        Pole[row, col] = k;
                    }

            status = 0;
            nMin = 0;
            nFlag = 0;

            showCountMin();

            button1.Image = Properties.Resources.startImage;
            label1.Text = "00:00";

            m = 0; s = 0;
            timer.Start();
        }

        private void timer1_Tick(object sender, EventArgs e)
        {
            strTimer = "";
            if (s < 59)
            {
                s++;
            }
            else
            {
                m++;
                s = 0;
            }
            if (m < 10)
                strTimer = "0" + m.ToString();
            else
                strTimer = Convert.ToString(m);
            strTimer += ":";
            if (s < 10)
                strTimer += "0" + s.ToString();
            else
                strTimer += s.ToString();
            label1.Text = strTimer;
        }

        private void showCountMin()
        {
            int lostMin = NM - nFlag;
            if (lostMin < 0)
                label2.Text = "Error";
            else if (lostMin < 10)
                label2.Text = "  " + Convert.ToString(lostMin);
            else if (lostMin < 100)
                label2.Text = " " + Convert.ToString(lostMin);
            else
                label2.Text = Convert.ToString(lostMin);
        }

        private void showPole(Graphics g, int status)
        {
            for (int i = 1; i < MR + 1; i++)
                for (int j = 1; j < MC + 1; j++)
                    this.kletka(g, i, j, status);
        }

        private void kletka(Graphics g, int r, int c, int status)
        {
            int x, y;

            x = (c - 1) * W + 1;
            y = (r - 1) * H + 1;

            if (Pole[r, c] < 100)
                g.FillRectangle(SystemBrushes.ControlLight, x - 1, y - 1, W, H);

            if (Pole[r, c] >= 100)
            {
                if (Pole[r, c] != 109)
                    g.FillRectangle(Brushes.White, x - 1, y - 1, W, H);
                else
                    g.FillRectangle(Brushes.Red, x - 1, y - 1, W, H);

                if (Pole[r, c] >= 101 && Pole[r, c] <= 108)
                    g.DrawString((Pole[r, c] - 100).ToString(), new Font("Tahoma", 10, System.Drawing.FontStyle.Regular), Brushes.Blue, x + 14, y + 10);
            }

            if (Pole[r, c] >= 200)
                this.flag(g, x, y);

            g.DrawRectangle(Pens.Black, x - 1, y - 1, W, H);

            if (status == 2 && Pole[r, c] % 10 == 9)
                this.mina(g, x, y);

            showCountMin();
        }

        private void open(int r, int c)
        {
            int x = (c - 1) * W + 1,
                y = (r - 1) * H + 1;

            if (Pole[r, c] == 0)
            {
                Pole[r, c] = 100;
                this.kletka(g, r, c, status);

                this.open(r + 1, c);
                this.open(r + 1, c - 1);
                this.open(r, c - 1);
                this.open(r - 1, c - 1);
                this.open(r - 1, c);
                this.open(r - 1, c + 1);
                this.open(r, c + 1);
                this.open(r + 1, c + 1);
            }
            else
            {
                if (Pole[r, c] < 100 && Pole[r, c] != -3)
                {
                    Pole[r, c] += 100;
                    this.kletka(g, r, c, status);
                }
            }
        }

        private void mina(Graphics g, int x, int y)
        {
            g.FillRectangle(Brushes.Green, x + 16, y + 26, 8, 4);
            g.FillRectangle(Brushes.Green, x + 8, y + 30, 24, 4);
            g.DrawPie(Pens.Black, x + 6, y + 28, 28, 16, 0, -180);
            g.FillPie(Brushes.Green, x + 6, y + 28, 28, 16, 0, -180);
            g.DrawLine(Pens.Black, x + 20, y + 22, x + 20, y + 26);
            g.DrawLine(Pens.Black, x + 8, y + 30, x + 6, y + 28);
            g.DrawLine(Pens.Black, x + 32, y + 30, x + 34, y + 28);
        }

        private void flag(Graphics g, int x, int y)
        {
            Point[] p = new Point[3];

            p[0].X = x + 4;
            p[1].X = x + 30;
            p[2].X = x + 4;

            p[0].Y = y + 4;
            p[1].Y = y + 12;
            p[2].Y = y + 25;

            g.FillPolygon(Brushes.Red, p);

            g.DrawLine(Pens.Black, x + 4, y + 4, x + 4, y + 35);

            Point[] m = new Point[5];
            m[0].X = x + 10;
            m[1].X = x + 10;
            m[2].X = x + 12;
            m[3].X = x + 14;
            m[4].X = x + 14;
            m[0].Y = y + 16;
            m[1].Y = y + 12;
            m[2].Y = y + 14;
            m[3].Y = y + 12;
            m[4].Y = y + 16;
            g.DrawLines(Pens.White, m);
        }

        private void panel1_MouseClick(object sender, MouseEventArgs e)
        {
            if (status == 2)
                return;

            if (status == 0)
                status = 1;

            int r = (int)e.Y / H + 1,
                c = (int)e.X / W + 1;

            int x = (c - 1) * W + 1,
                y = (r - 1) * H + 1;

            if (e.Button == MouseButtons.Left)
            {
                if (Pole[r, c] == 9)
                {
                    Pole[r, c] += 100;
                    status = 2;
                    this.panel1.Invalidate();
                    button1.Image = Properties.Resources.deadImage;
                    timer.Stop();
                    //MessageBox.Show("Вы проиграли!");
                    EndGameForm endGameForm = new EndGameForm(false, strTimer, nMin, NM, MC, MR);
                    endGameForm.ShowDialog();
                }
                else
                    if (Pole[r, c] < 9)
                        this.open(r, c);
            }

            if (e.Button == MouseButtons.Right)
            {
                if (Pole[r, c] <= 9)
                {
                    nFlag += 1;
                    if (Pole[r, c] == 9)
                        nMin += 1;

                    Pole[r, c] += 200;

                    if (nMin == NM && nFlag == NM)
                    {
                        this.kletka(g, r, c, status);
                        status = 2;
                        this.Invalidate();
                        button1.Image = Properties.Resources.winImage;
                        timer.Stop();
                        //MessageBox.Show("Вы победили!");
                        EndGameForm endGameForm = new EndGameForm(true, strTimer, nMin, NM, MC, MR);
                        endGameForm.ShowDialog();
                    }
                    else
                        this.kletka(g, r, c, status);
                }
                else
                {
                    if (Pole[r, c] >= 200)
                    {
                        nFlag -= 1;
                        if (Pole[r, c] == 209)
                            nMin -= 1;
                        Pole[r, c] -= 200;

                        this.kletka(g, r, c, status);
                    }
                }
            }
        }

        private void panel1_Paint(object sender, PaintEventArgs e)
        {
            showPole(g, status);
        }

        private void button1_Click(object sender, EventArgs e)
        {
            newGame();
            showPole(g, status);
        }

        private void newGameToolStripMenuItem_Click(object sender, EventArgs e)
        {
            newGame();
            showPole(g, status);
        }

        private void settingsToolStripMenuItem_Click(object sender, EventArgs e)
        {
            SettingsForm settingsForm = new SettingsForm();
            settingsForm.ShowDialog();
            if (settingsForm.isExit)
            {
                switch (Properties.Settings.Default.level)
                {
                    case "izi":
                        MR = 10;
                        MC = 10;
                        NM = 10;
                        break;
                    case "lover":
                        MR = 15;
                        MC = 15;
                        NM = 30;
                        break;
                    case "zadr":
                        MR = 20;
                        MC = 20;
                        NM = 57;
                        break;
                    default:
                        MR = 10;
                        MC = 10;
                        NM = 10;
                        break;
                }
                createWindow();
                newGame();
                showPole(g, status);
            }
        }

        private void exitToolStripMenuItem_Click(object sender, EventArgs e)
        {
            Application.Exit();
        }

        private void rulesToolStripMenuItem_Click(object sender, EventArgs e)
        {
            MessageForm messageForm = new MessageForm(true);
            messageForm.ShowDialog();
        }

        private void aboutToolStripMenuItem_Click(object sender, EventArgs e)
        {
            MessageForm messageForm = new MessageForm(false);
            messageForm.ShowDialog();
        }
    }
}