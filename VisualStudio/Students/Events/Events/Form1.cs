using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Windows.Forms;

namespace Events
{
    public partial class Form1 : Form
    {
        private int mousePosX, mousePosY;

        public Form1()
        {
            InitializeComponent();

            timer1.Interval = 1;
            timer1.Enabled = true;
        }

        private void button1_MouseMove(object sender, MouseEventArgs e)
        {
            Random rand = new Random();
            button1.Top = rand.Next(0, this.Height - button1.Height);
            button1.Left = rand.Next(0, this.Width - button1.Width);
        }

        private void timer1_Tick(object sender, EventArgs e)
        {
            if (button1.Left > mousePosX)
                button1.Left--;
            else if (button1.Left < mousePosX)
                button1.Left++;

            if (button1.Top > mousePosY)
                button1.Top--;
            else if (button1.Top < mousePosY)
                button1.Top++;
        }

        private void Form1_MouseMove(object sender, MouseEventArgs e)
        {
            mousePosX = e.X;
            mousePosY = e.Y;
        }
    }
}
