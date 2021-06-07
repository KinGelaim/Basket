using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Drawing.Drawing2D;
using System.Linq;
using System.Text;
using System.Windows.Forms;

namespace Project8
{
    public partial class Form1 : Form
    {
        enum instrument
        {
            none,
            brush,
            line,
            lines,
            rectangle
        }

        private instrument currentInstrument = instrument.none;
        private bool isMouseDown = false;
        private int mouseX, mouseY;

        private Graphics g;
        private Bitmap bitmap;
        //private Bitmap oldBitmap;

        private Brush mainBrush;
        private Pen mainPen;
        private Color mainColor;
        private int mainSize;

        public Form1()
        {
            InitializeComponent();

            pictureBox1.Image = new Bitmap(Properties.Resources.brush);
            pictureBox2.Image = new Bitmap(Properties.Resources.line);
            pictureBox3.Image = new Bitmap(Properties.Resources.rectangle);
            pictureBox4.Image = new Bitmap(Properties.Resources.lines);

            bitmap = new Bitmap(mainPicture.Width, mainPicture.Height);
            g = Graphics.FromImage(bitmap);

            g.Clear(Color.White);
            mainPicture.Image = bitmap;

            mainColor = Color.Black;
            mainBrush = Brushes.Black;
            //mainPen = Pens.Black;
            mainPen = new Pen(Color.Black);

            mainSize = 8;

            //Справка
            helpProvider1.HelpNamespace = "npv.chm";
            helpProvider1.SetHelpNavigator(this, HelpNavigator.Topic);
            helpProvider1.SetShowHelp(this, true);

            //MessageBox.Show(instrument.line.ToString());

            //Изменяем начало и конец карандашка
            mainPen.StartCap = mainPen.EndCap = LineCap.Round;
        }

        private void pictureBox1_Click(object sender, EventArgs e)
        {
            changeInstrument(instrument.brush, sender);
        }

        private void pictureBox2_Click(object sender, EventArgs e)
        {
            changeInstrument(instrument.line, sender);
        }

        private void pictureBox3_Click(object sender, EventArgs e)
        {
            changeInstrument(instrument.rectangle, sender);
        }

        private void pictureBox4_Click(object sender, EventArgs e)
        {
            changeInstrument(instrument.lines, sender);
        }

        //Функция для смены инструмена
        private void changeInstrument(instrument inst, object sender = null)
        {
            if (currentInstrument == inst)
                currentInstrument = instrument.none;
            else
                currentInstrument = inst;

            pictureBox1.BorderStyle = BorderStyle.None;
            pictureBox2.BorderStyle = BorderStyle.None;
            pictureBox3.BorderStyle = BorderStyle.None;
            pictureBox4.BorderStyle = BorderStyle.None;
            if (sender != null)
                ((PictureBox)sender).BorderStyle = BorderStyle.FixedSingle;
        }

        private void mainPicture_MouseDown(object sender, MouseEventArgs e)
        {
            isMouseDown = true;
            mouseX = e.Location.X;
            mouseY = e.Location.Y;
            //oldBitmap = (Bitmap)bitmap.Clone();
        }

        private void mainPicture_MouseMove(object sender, MouseEventArgs e)
        {
            if (isMouseDown)
            {
                switch (currentInstrument)
                {
                    case instrument.brush:
                        g.FillEllipse(mainBrush, e.Location.X - mainSize/2, e.Location.Y - mainSize / 2, mainSize, mainSize);
                        break;
                    case instrument.line:
                        //g.DrawLine(mainPen, mouseX, mouseY, e.Location.X, e.Location.Y);
                        //mainPicture.Image = bitmap;
                        break;
                    case instrument.rectangle:
                        break;
                    case instrument.lines:
                        g.DrawLine(mainPen, mouseX, mouseY, e.Location.X, e.Location.Y);
                        //mainPicture.Image = bitmap;
                        break;
                    default: 
                        break;
                }
                mainPicture.Image = bitmap;
            }
        }

        private void mainPicture_MouseUp(object sender, MouseEventArgs e)
        {
            if (isMouseDown)
            {
                switch (currentInstrument)
                {
                    case instrument.brush:
                        break;
                    case instrument.line:
                        g.DrawLine(mainPen, mouseX, mouseY, e.Location.X, e.Location.Y);
                        break;
                    case instrument.rectangle:
                        g.DrawRectangle(mainPen, mouseX, mouseY, e.Location.X - mouseX, e.Location.Y - mouseY);
                        break;
                    default:
                        break;
                }
                mainPicture.Image = bitmap;
                isMouseDown = false;
            }
        }

        //Правая кнопка мыши
        private void settingsToolStripMenuItem_Click(object sender, EventArgs e)
        {
            FormSettings formSett = new FormSettings(mainColor, mainSize);
            formSett.ShowDialog();
            if (formSett.isExit)
            {
                mainColor = formSett.currentColor;
                mainBrush = new SolidBrush(mainColor);
                mainPen = new Pen(mainBrush);
                mainSize = formSett.trackBar1.Value;
            }
        }

        private void clearToolStripMenuItem_Click(object sender, EventArgs e)
        {
            g.Clear(Color.White);
            mainPicture.Image = bitmap;
        }

        //Верхнее меню
        private void newToolStripMenuItem_Click(object sender, EventArgs e)
        {
            changeInstrument(instrument.none);
            g.Clear(Color.White);
            mainPicture.Image = bitmap;
        }

        private void openToolStripMenuItem_Click(object sender, EventArgs e)
        {
            OpenFileDialog ofd = new OpenFileDialog();
            ofd.DefaultExt = "png";
            ofd.Filter = "Изображение|*.png";
            ofd.Title = "Открыть изображение";
            if (ofd.ShowDialog() == DialogResult.OK)
            {
                System.IO.FileStream st = new System.IO.FileStream(ofd.FileName, System.IO.FileMode.Open);
                bitmap = new Bitmap(st);
                st.Close();
                g = Graphics.FromImage(bitmap);
                mainPicture.Image = bitmap;
                changeInstrument(instrument.none);
            }
        }

        private void saveToolStripMenuItem_Click(object sender, EventArgs e)
        {
            SaveFileDialog sfd = new SaveFileDialog();
            sfd.DefaultExt = "png";
            sfd.Filter = "Изображение|*.png";
            sfd.Title = "Открыть изображение";
            if (sfd.ShowDialog() == DialogResult.OK)
            {
                mainPicture.Image.Save(sfd.FileName, System.Drawing.Imaging.ImageFormat.Png);
            }
        }   

        private void exitToolStripMenuItem_Click(object sender, EventArgs e)
        {
            Application.Exit();
        }

        private void helpToolStripMenuItem_Click(object sender, EventArgs e)
        {

        }

        private void aboutTheProgrammToolStripMenuItem_Click(object sender, EventArgs e)
        {
            AboutBox newAboutBox = new AboutBox();
            newAboutBox.ShowDialog();
        }

        //Изменение размера формы
        private void Form1_Resize(object sender, EventArgs e)
        {
            bitmap = new Bitmap(bitmap, mainPicture.Width, mainPicture.Height);
            g = Graphics.FromImage(bitmap);
            mainPicture.Image = bitmap;
        }
    }
}
