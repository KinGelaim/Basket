using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Windows.Forms;
using SharpGL;
using SharpGL.Enumerations;

namespace Stars
{
    public partial class Form1 : Form
    {
        OpenGL gl;
        float camera = 0;
        static int nPoint = 10000;
        int[] pointX = new int[nPoint];
        int[] pointY = new int[nPoint];
        int[] pointZ = new int[nPoint];

        double[] colorR = new double[nPoint];
        double[] colorG = new double[nPoint];
        double[] colorB = new double[nPoint];

        public Form1()
        {
            InitializeComponent();

            gl = openGLControl1.OpenGL;
            Random rand = new Random();
            for (int i = 0; i < nPoint; i++)
            {
                pointX[i] = rand.Next(-50, 50);
                pointY[i] = rand.Next(-50, 50);
                pointZ[i] = rand.Next(0, 5000);

                colorR[i] = rand.NextDouble() / 2 + 0.5;
                colorG[i] = rand.NextDouble() / 2 + 0.5;
                colorB[i] = rand.NextDouble() / 2 + 0.5;
            }
        }

        private void openGLControl1_OpenGLDraw(object sender, SharpGL.RenderEventArgs args)
        {
            gl.Clear(OpenGL.GL_COLOR_BUFFER_BIT | OpenGL.GL_DEPTH_BUFFER_BIT);

            gl.LoadIdentity();

            gl.LookAt(0, 0, camera++, 0, 0, camera + 10, 0, 1, 0);  //Три точки камеры, три точки куда смотрим и три точки координаты верха

            gl.PointSize(2);
            gl.Begin(BeginMode.Points);

            gl.Color(1f, 1f, 1f);
            for (int i = 0; i < nPoint; i++)
            {
                gl.Color(colorR[i], colorG[i], colorB[i]);
                gl.Vertex(pointX[i], pointY[i], pointZ[i]);
            }
            gl.End();

            gl.Flush();
        }
    }
}
