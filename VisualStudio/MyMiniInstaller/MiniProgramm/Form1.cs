using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Windows.Forms;

namespace MiniProgramm
{
    public partial class Form1 : Form
    {
        private Bitmap bitmap;

        public Form1()
        {
            InitializeComponent();
        }

        private void Form1_Click(object sender, EventArgs e)
        {
            Random rand = new Random();
            Color color = Color.FromArgb(rand.Next(0, 255), rand.Next(0, 255), rand.Next(0, 255));
            bitmap = new Bitmap(this.Width, this.Height);
            Graphics g = Graphics.FromImage(bitmap);
            g.FillEllipse(new SolidBrush(color), 5, 5, this.Width - 25, this.Height - 50);
            this.BackgroundImage = bitmap;
        }
    }
}
