using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Windows.Forms;
using SharpGL;

namespace BallZond
{
    public partial class Form1 : Form
    {
        //Координаты точек
        double[] pointX = new double[] { -0.00078, 713.8455, 1245.827, 1918.787, 2532.932, 3010.686, 4103.241, 4504.18, 5293.363, 5783.272, 6772.128 };
        double[] pointY = new double[] { 154.1183, 632.9423, 991.8237, 1169.047, 1587.571, 1903.147, 2554.62, 2881.53, 3212.708, 3517.775, 3810.268 };
        double[] pointZ = new double[] { -292.931, -466.533, -875.037, -1406.91, -1929.57, -2545.72, -3850.94, -4570.15, -5029.05, -5861.16, -6315.09};

        //Поворот по осям
        double angleRotateX = 0;
        double angleRotateY = 0;
        double angleRotateZ = 0;

        //Постоянное вращение вдоль оси Y
        bool isRotateY = false;

        //Управление мышкой
        bool isMouseDown = false;
        int prevX = 0;
        int prevY = 0;

        //Дистанция камеры
        double viewDistance = -10;

        public Form1()
        {
            InitializeComponent();

            this.MouseWheel += new MouseEventHandler(this.Form1_MouseWheel);
        }

        private void openGLControl1_OpenGLDraw(object sender, SharpGL.RenderEventArgs args)
        {
            OpenGL gl = openGLControl1.OpenGL;

            gl.Clear(OpenGL.GL_COLOR_BUFFER_BIT | OpenGL.GL_DEPTH_BUFFER_BIT);

            gl.LoadIdentity();

            gl.Translate(0, 0, viewDistance);

            gl.Rotate(angleRotateX, 1, 0, 0);
            gl.Rotate(angleRotateY, 0, 1, 0);
            gl.Rotate(angleRotateZ, 0, 0, 1);

            for (int i = 0; i < pointY.Length; i++)
            {
                double sizeX = SearchSize(pointX[i], pointX.Min(), pointX.Max(), -4, 4);
                double sizeY = SearchSize(pointY[i], pointY.Min(), pointY.Max(), -3.5, 3.5);
                double sizeZ = SearchSize(pointZ[i], pointZ.Min(), pointZ.Max(), -3, 3);
                DrawQuad(gl, new Position(sizeX, sizeY, sizeZ), 0.2);
            }

            DrawEarth(gl, -3.5);

            DrawAxies(gl, -3.4);

            gl.DrawText(0, openGLControl1.Height - 20, 1f, 1, 1, "Times New Roman", 20, "X{" + pointX.Min() + ":" + pointX.Max()+"}");
            gl.DrawText(0, openGLControl1.Height - 40, 1f, 1, 1, "Times New Roman", 20, "Y{" + pointY.Min() + ":" + pointY.Max() + "}");
            gl.DrawText(0, openGLControl1.Height - 60, 1f, 1, 1, "Times New Roman", 20, "Z{" + pointZ.Min() + ":" + pointZ.Max() + "}");

            gl.Flush();

            if (isRotateY)
            {
                angleRotateY++;
                sceneControl1.OpenGL.Rotate(1, 0, 0, 1);
            }
        }

        private void DrawQuad(OpenGL gl, Position pos, double size)
        {
            gl.Begin(OpenGL.GL_QUADS);

            gl.Color(0f, 1f, 0f);
            gl.Vertex(pos.x + size, pos.y + size, pos.z - size);
            gl.Vertex(pos.x - size, pos.y + size, pos.z - size);
            gl.Vertex(pos.x - size, pos.y + size, pos.z + size);
            gl.Vertex(pos.x + size, pos.y + size, pos.z + size);

            gl.Color(1f, 0.5f, 0f);
            gl.Vertex(pos.x + size, pos.y - size, pos.z + size);
            gl.Vertex(pos.x - size, pos.y - size, pos.z + size);
            gl.Vertex(pos.x - size, pos.y - size, pos.z - size);
            gl.Vertex(pos.x + size, pos.y - size, pos.z - size);

            gl.Color(1f, 0f, 0f);
            gl.Vertex(pos.x + size, pos.y + size, pos.z + size);
            gl.Vertex(pos.x - size, pos.y + size, pos.z + size);
            gl.Vertex(pos.x - size, pos.y - size, pos.z + size);
            gl.Vertex(pos.x + size, pos.y - size, pos.z + size);

            gl.Color(1f, 1f, 0f);
            gl.Vertex(pos.x + size, pos.y - size, pos.z - size);
            gl.Vertex(pos.x - size, pos.y - size, pos.z - size);
            gl.Vertex(pos.x - size, pos.y + size, pos.z - size);
            gl.Vertex(pos.x + size, pos.y + size, pos.z - size);

            gl.Color(0f, 0f, 1f);
            gl.Vertex(pos.x - size, pos.y + size, pos.z + size);
            gl.Vertex(pos.x - size, pos.y + size, pos.z - size);
            gl.Vertex(pos.x - size, pos.y - size, pos.z - size);
            gl.Vertex(pos.x - size, pos.y - size, pos.z + size);

            gl.Color(1f, 0f, 1f);
            gl.Vertex(pos.x + size, pos.y + size, pos.z - size);
            gl.Vertex(pos.x + size, pos.y + size, pos.z + size);
            gl.Vertex(pos.x + size, pos.y - size, pos.z + size);
            gl.Vertex(pos.x + size, pos.y - size, pos.z - size);

            gl.End();
        }

        private void DrawEarth(OpenGL gl, double y)
        {
            gl.LoadIdentity();

            gl.Translate(0, 0, viewDistance);

            gl.Rotate(angleRotateX, 1, 0, 0);
            gl.Rotate(angleRotateY, 0, 1, 0);
            gl.Rotate(angleRotateZ, 0, 0, 1);

            gl.Begin(OpenGL.GL_POLYGON);

            gl.Color(0.1f, 0.4f, 0.1f);
            gl.Vertex(-1, y, -1);
            gl.Vertex(-1, y, 1);
            gl.Vertex(1, y, 1);
            gl.Vertex(1, y, -1);

            gl.End();
        }

        private void DrawAxies(OpenGL gl, double y)
        {
            gl.LoadIdentity();

            gl.Translate(0, 0, viewDistance);

            gl.Rotate(angleRotateX, 1, 0, 0);
            gl.Rotate(angleRotateY, 0, 1, 0);
            gl.Rotate(angleRotateZ, 0, 0, 1);

            gl.PushAttrib(OpenGL.GL_CURRENT_BIT | OpenGL.GL_ENABLE_BIT |
                            OpenGL.GL_LINE_BIT | OpenGL.GL_DEPTH_BUFFER_BIT);
            gl.Disable(OpenGL.GL_LIGHTING);
            gl.Disable(OpenGL.GL_TEXTURE_2D);
            gl.DepthFunc(OpenGL.GL_ALWAYS);

            //  Set a nice fat line width.
            gl.LineWidth(2f);

            //  Draw the axies.
            gl.Begin(OpenGL.GL_LINES);
            gl.Color(1f, 0f, 0f, 1f);
            gl.Vertex(0, y, 0);
            gl.Vertex(3, y, 0);
            gl.Color(0f, 1f, 0f, 1f);
            gl.Vertex(0, y, 0);
            gl.Vertex(0, y + 3, 0);
            gl.Color(0f, 0f, 1f, 1f);
            gl.Vertex(0, y, 0);
            gl.Vertex(0, y, 3);
            gl.End();

            //  Restore attributes.
            gl.PopAttrib();
        }

        private double SearchSize(double value, double fromLow, double fromHigh, double toLow, double toHigh)
        {
            return (value - fromLow) * (toHigh - toLow) / (fromHigh - fromLow) + toLow;
        }

        //На пробел начинать вращение в доль оси Y
        private void openGLControl1_KeyPress(object sender, KeyPressEventArgs e)
        {
            if (e.KeyChar == ' ')
            {
                isRotateY = !isRotateY;
            }
        }

        //Мышка нажата
        private void openGLControl1_MouseDown(object sender, MouseEventArgs e)
        {
            if (e.Button == System.Windows.Forms.MouseButtons.Left)
            {
                isMouseDown = true;
                prevX = e.X;
                prevY = e.Y;
            }
        }

        //Ведём мышкой
        private void openGLControl1_MouseMove(object sender, MouseEventArgs e)
        {
            if (isMouseDown)
            {
                int prX = prevX - e.X;
                int prY = prevY - e.Y;

                angleRotateY += prX;
                angleRotateX += prY;

                prevX = e.X;
                prevY = e.Y;
            }
        }

        //Мышку отпустили
        private void openGLControl1_MouseUp(object sender, MouseEventArgs e)
        {
            if (e.Button == System.Windows.Forms.MouseButtons.Left)
                isMouseDown = false;
        }

        //Колёсико мышки
        private void Form1_MouseWheel(object sender, MouseEventArgs e)
        {
            if (e.Delta > 0)
                viewDistance++;
            else
                viewDistance--;
        }
    }
}
