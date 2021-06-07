using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Windows.Forms;

namespace Project10
{
    public partial class Form1 : Form
    {
        private Bitmap background;
        private Bitmap plane;

        private Graphics g;

        private int speed;

        Random rand;

        Rectangle objPlane; //Расположение самолетика

        private int lvlDrink = 0;
        private bool isDie = false;

        public Form1()
        {
            InitializeComponent();

            rand = new Random();

            try
            {
                //Загрузка изображений
                plane = Properties.Resources.plane;
                //Прозрачный фон у самолетика
                plane.MakeTransparent();

                background = Properties.Resources.background;
                
                this.BackgroundImage = new Bitmap(this.Width, this.Height);
                g = Graphics.FromImage(BackgroundImage);
                this.DoubleBuffered = true;

                isDie = false;
                objPlane.X = -70;
                objPlane.Y = 30 + rand.Next(this.Height - 70);
                objPlane.Width = plane.Width;
                objPlane.Height = plane.Height;

                speed = 3;

                timer1.Interval = 1;
                timer1.Enabled = true;
            }
            catch (Exception e)
            {
                MessageBox.Show("Какие-то проблемы!\n" + e.Message + "\nПроблема " + e.StackTrace);
                this.Close();
                return;
            }
        }

        private void timer1_Tick(object sender, EventArgs e)
        {
            g.Clear(Color.White);
            g.DrawImage(background, 0,0);

            if (objPlane.X < this.Width)
                objPlane.X += speed;
            else
            {
                objPlane.X = -70;
                objPlane.Y = 30 + rand.Next(this.Height - 70);
                speed = 3 + rand.Next(-2, 7);
                isDie = false;
            }

            //MessageBox.Show(objPlane.X.ToString() + " " + objPlane.Y + " " + this.Height);

            g.DrawImage(plane, objPlane.X, objPlane.Y);

            if (!isDie)
                if(lvlDrink > 0)
                {
                    if (lvlDrink < 200)
                        objPlane.Y += rand.Next(-10, 10);
                    else
                    {
                        objPlane.Y += rand.Next(10, 20);
                        if (objPlane.Y > this.Height)
                        {
                            isDie = true;
                            lvlDrink = 0;
                            objPlane.X = this.Width - 40;
                            MessageBox.Show("LOL!!! YOU DIE!!!");
                        }
                    }
                    lvlDrink--;
                }

            //Перерисовываем
            this.Refresh();
            //this.Invalidate(objPlane);
        }

        private void Form1_Click(object sender, EventArgs e)
        {
            if (MessageBox.Show("Выпить немного?", "Подтверждение", MessageBoxButtons.YesNo, MessageBoxIcon.Question) == DialogResult.Yes)
                lvlDrink += 100;
        }
    }
}
