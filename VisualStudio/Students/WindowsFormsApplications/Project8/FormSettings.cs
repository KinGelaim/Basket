using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Windows.Forms;

namespace Project8
{
    public partial class FormSettings : Form
    {
        public bool isExit = false;
        public Color currentColor;

        public FormSettings(Color color, int size)
        {
            InitializeComponent();

            currentColor = color;
            trackBar1.Value = size;

            InitPicture();
        }

        private void pictureBox1_Click(object sender, EventArgs e)
        {
            colorDialog1.Color = currentColor;
            colorDialog1.ShowDialog();
            currentColor = colorDialog1.Color;
            InitPicture();
        }

        private void button1_Click(object sender, EventArgs e)
        {
            isExit = true;
            this.Close();
        }

        private void InitPicture()
        {
            Bitmap bitmap = new Bitmap(pictureBox1.Width, pictureBox1.Height);
            Graphics g = Graphics.FromImage(bitmap);
            g.Clear(currentColor);
            pictureBox1.Image = bitmap;
        }
    }
}
