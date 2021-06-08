using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Windows.Forms;
using SharpGL;

namespace Finger
{
    public partial class Form1 : Form
    {
        private double angleRotateY = -70;
        private double angleRotateX = 0;

        private bool isAngleFinger = false;

        private bool isFourFingers = false;
        private bool isHand = false;
        private bool isMainFinger = false;

        private bool isMouseDown = false;
        private int prevX = 0;
        private int prevY = 0;

        public Form1()
        {
            InitializeComponent();
        }

        private void openGLControl1_OpenGLDraw(object sender, SharpGL.RenderEventArgs args)
        {
            OpenGL gl = openGLControl1.OpenGL;

            gl.Clear(OpenGL.GL_COLOR_BUFFER_BIT | OpenGL.GL_DEPTH_BUFFER_BIT);

            gl.LoadIdentity();

            gl.Translate(-10, 17, -80);

            gl.Rotate(angleRotateY, 0, 1, 0);
            //gl.Rotate(angleRotateX, 1, 0, 0);
            gl.Rotate(-40, 1, 0, 0);

            if (isHand)
                DrawHand(gl, -7, 14);

            if (isMainFinger)
            {
                DrawFinger(gl, 7);
                DrawAngleFinger(gl, -7);
                DrawAngleFinger(gl, 0);
                DrawAngleFinger(gl, 14);
            }
            else
            {
                if (!isAngleFinger)
                {
                    DrawFinger(gl, -7);
                    if (isFourFingers)
                    {
                        DrawFinger(gl, 0);
                        DrawFinger(gl, 7);
                        DrawFinger(gl, 14);
                    }
                }
                else
                {
                    DrawAngleFinger(gl, -7);
                    if (isFourFingers)
                    {
                        DrawAngleFinger(gl, 0);
                        DrawAngleFinger(gl, 7);
                        DrawAngleFinger(gl, 14);
                    }
                }
            }

            gl.Flush();

            angleRotateY--;
        }

        private void DrawHand(OpenGL gl, double minXFinger, double maxXFinger)
        {
            gl.Begin(OpenGL.GL_QUADS);

            gl.Color(1f, 0.5f, 1f);
            gl.Vertex(maxXFinger + 3, 3, -22);
            gl.Vertex(minXFinger - 3, 3, -22);
            gl.Vertex(minXFinger - 3, 3, -50);
            gl.Vertex(maxXFinger + 3, 3, -50);

            gl.Vertex(maxXFinger + 3, 3, -22);
            gl.Vertex(maxXFinger + 3, 3, -50);
            gl.Vertex(maxXFinger + 3, -3, -50);
            gl.Vertex(maxXFinger + 3, -3, -22);

            gl.Vertex(minXFinger - 3, 3, -22);
            gl.Vertex(minXFinger - 3, 3, -50);
            gl.Vertex(minXFinger - 3, -3, -50);
            gl.Vertex(minXFinger - 3, -3, -22);

            gl.Vertex(maxXFinger + 3, -3, -22);
            gl.Vertex(minXFinger - 3, -3, -22);
            gl.Vertex(minXFinger - 3, -3, -50);
            gl.Vertex(maxXFinger + 3, -3, -50);

            gl.End();
        }

        private void DrawFinger(OpenGL gl, double xFinger)
        {
            gl.Begin(OpenGL.GL_QUADS);

            //Передняя часть первой фаланги
            gl.Color(1f, 0f, 0f);
            gl.Vertex(xFinger - 2, - 2, 2);
            gl.Vertex(xFinger + 2, -2, 2);
            gl.Vertex(xFinger + 2, 2, 2);
            gl.Vertex(xFinger - 2, 2, 2);

            //Верх
            gl.Color(0f, 0f, 1f);
            gl.Vertex(xFinger - 2, 2, 2);
            gl.Vertex(xFinger - 2, 2, -4);
            gl.Vertex(xFinger+2, 2, -4);
            gl.Vertex(xFinger+2, 2, 2);

            //Правая часть
            gl.Vertex(xFinger+2, 2, 2);
            gl.Vertex(xFinger+2, 2, -4);
            gl.Vertex(xFinger+2, -2, -4);
            gl.Vertex(xFinger+2, -2, 2);
            
            //Нижняя часть
            gl.Vertex(xFinger+2, -2, 2);
            gl.Vertex(xFinger+2, -2, -4);
            gl.Vertex(xFinger - 2, -2, -4);
            gl.Vertex(xFinger - 2, -2, 2);

            //Левая часть
            gl.Vertex(xFinger - 2, -2, 2);
            gl.Vertex(xFinger - 2, -2, -4);
            gl.Vertex(xFinger - 2, 2, -4);
            gl.Vertex(xFinger - 2, 2, 2);

            //Вторая фаланга верх
            gl.Color(0f, 1f, 0f);
            gl.Vertex(xFinger - 2, 2, -4);
            gl.Vertex(xFinger - 3, 3, -12);
            gl.Vertex(xFinger+3, 3, -12);
            gl.Vertex(xFinger+2, 2, -4);

            //Правая часть
            gl.Vertex(xFinger+2, 2, -4);
            gl.Vertex(xFinger + 3, 3, -12);
            gl.Vertex(xFinger + 3, -3, -12);
            gl.Vertex(xFinger + 2, -2, -4);

            //Нижняя часть
            gl.Vertex(xFinger + 2, -2, -4);
            gl.Vertex(xFinger + 3, -3, -12);
            gl.Vertex(xFinger -3, -3, -12);
            gl.Vertex(xFinger -2, -2, -4);

            //Левая часть
            gl.Vertex(xFinger - 2, -2, -4);
            gl.Vertex(xFinger - 3, -3, -12);
            gl.Vertex(xFinger - 3, 3, -12);
            gl.Vertex(xFinger - 2, 2, -4);

            //Третья фаланга верх
            gl.Color(1f, 0f, 1f);
            gl.Vertex(xFinger - 3, 3, -12);
            gl.Vertex(xFinger - 3, 3, -22);
            gl.Vertex(xFinger + 3, 3, -22);
            gl.Vertex(xFinger + 3, 3, -12);

            //Правая часть
            gl.Vertex(xFinger + 3, 3, -12);
            gl.Vertex(xFinger + 3, 3, -22);
            gl.Vertex(xFinger + 3, -3, -22);
            gl.Vertex(xFinger + 3, -3, -12);

            //Нижняя часть
            gl.Vertex(xFinger + 3, -3, -12);
            gl.Vertex(xFinger + 3, -3, -22);
            gl.Vertex(xFinger - 3, -3, -22);
            gl.Vertex(xFinger - 3, -3, -12);

            //Левая часть
            gl.Vertex(xFinger - 3, -3, -12);
            gl.Vertex(xFinger - 3, -3, -22);
            gl.Vertex(xFinger - 3, 3, -22);
            gl.Vertex(xFinger - 3, 3, -12);

            gl.End();
        }

        private void DrawAngleFinger(OpenGL gl, double xFinger)
        {
            gl.LoadIdentity();
            gl.Translate(-10, -16, -80);
            gl.Rotate(angleRotateY, 0, 1, 0);
            gl.Rotate(45, 1, 0, 0);
            gl.Begin(OpenGL.GL_QUADS);
            //Передняя часть пальца
            gl.Color(1f, 0f, 0f);
            gl.Vertex(xFinger+2, -11, -22);
            gl.Vertex(xFinger+2, -7, -22);
            gl.Vertex(xFinger - 2, -7, -22);
            gl.Vertex(xFinger - 2, -11, -22);

            //Первая фланга верх
            gl.Color(0f, 0f, 1f);
            gl.Vertex(xFinger - 2, -11, -13);
            gl.Vertex(xFinger+2, -11, -13);
            gl.Vertex(xFinger+2, -11, -22);
            gl.Vertex(xFinger - 2, -11, -22);

            //Правая часть
            gl.Vertex(xFinger+2, -11, -13);
            gl.Vertex(xFinger + 2, -7, -13);
            gl.Vertex(xFinger + 2, -7, -22);
            gl.Vertex(xFinger + 2, -11, -22);

            //Нижняя часть
            gl.Vertex(xFinger + 2, -7, -17);
            gl.Vertex(xFinger + 2, -7, -22);
            gl.Vertex(xFinger - 2, -7, -22);
            gl.Vertex(xFinger - 2, -7, -17);

            //Левая часть
            gl.Vertex(xFinger - 2, -7, -17);
            gl.Vertex(xFinger - 2, -11, -17);
            gl.Vertex(xFinger - 2, -11, -22);
            gl.Vertex(xFinger - 2, -7, -22);

            //Вторая фаланга верх
            gl.Color(0f, 1f, 0f);
            gl.Vertex(xFinger - 3, 3, -12);
            gl.Vertex(xFinger + 3, 3, -12);
            gl.Vertex(xFinger + 3, -3, -12);
            gl.Vertex(xFinger - 3, -3, -12);

            gl.Vertex(xFinger + 3, -3, -12);
            gl.Vertex(xFinger - 3, -3, -12);
            gl.Vertex(xFinger - 2, -11, -13);
            gl.Vertex(xFinger + 2, -11, -13);

            //Правая часть
            gl.Vertex(xFinger + 3, -3, -12);
            gl.Vertex(xFinger + 3, -3, -18);
            gl.Vertex(xFinger + 2, -11, -17);
            gl.Vertex(xFinger + 2, -11, -13);

            //Нижняя часть
            gl.Vertex(xFinger + 3, -3, -18);
            gl.Vertex(xFinger - 3, -3, -18);
            gl.Vertex(xFinger - 2, -11, -17);
            gl.Vertex(xFinger + 2, -11, -17);

            //Левая часть
            gl.Vertex(xFinger - 3, -3, -12);
            gl.Vertex(xFinger - 3, -3, -18);
            gl.Vertex(xFinger - 2, -11, -17);
            gl.Vertex(xFinger - 2, -11, -13);

            //Третья фаланга верх
            gl.Color(1f, 0f, 1f);
            gl.Vertex(xFinger - 3, 3, -12);
            gl.Vertex(xFinger - 3, 3, -22);
            gl.Vertex(xFinger + 3, 3, -22);
            gl.Vertex(xFinger + 3, 3, -12);

            //Правая часть
            gl.Vertex(xFinger + 3, 3, -12);
            gl.Vertex(xFinger + 3, 3, -22);
            gl.Vertex(xFinger + 3, -3, -22);
            gl.Vertex(xFinger + 3, -3, -12);

            //Нижняя часть
            gl.Vertex(xFinger + 3, -3, -12);
            gl.Vertex(xFinger + 3, -3, -22);
            gl.Vertex(xFinger - 3, -3, -22);
            gl.Vertex(xFinger - 3, -3, -12);

            //Левая часть
            gl.Vertex(xFinger - 3, -3, -12);
            gl.Vertex(xFinger - 3, -3, -22);
            gl.Vertex(xFinger - 3, 3, -22);
            gl.Vertex(xFinger - 3, 3, -12);

            gl.End();
        }

        private void openGLControl1_KeyUp(object sender, KeyEventArgs e)
        {
            if (e.KeyCode == Keys.Space)
            {
                isAngleFinger = !isAngleFinger;
            }
            if (e.KeyCode == Keys.D1)
            {
                isFourFingers = false;
                isHand = false;
                isMainFinger = false;
            }
            if (e.KeyCode == Keys.D2)
            {
                isFourFingers = true;
                isHand = false;
                isMainFinger = false;
            }
            if (e.KeyCode == Keys.D3)
            {
                isFourFingers = true;
                isHand = true;
                isMainFinger = false;
            }
            if (e.KeyCode == Keys.D7)
            {
                isFourFingers = false;
                isHand = true;
                isMainFinger = true;
            }
        }

        private void openGLControl1_MouseDown(object sender, MouseEventArgs e)
        {
            if (e.Button == System.Windows.Forms.MouseButtons.Left)
            {
                isMouseDown = true;
                prevX = e.X;
                prevY = e.Y;
            }
        }

        private void openGLControl1_MouseMove(object sender, MouseEventArgs e)
        {
            if (isMouseDown)
            {
                int prX = prevX - e.X;
                angleRotateY += prX;
                int prY = prevX - e.X;
                angleRotateX += prY;
            }
        }

        private void openGLControl1_MouseUp(object sender, MouseEventArgs e)
        {
            if (e.Button == System.Windows.Forms.MouseButtons.Left)
            {
                isMouseDown = false;
            }
        }
    }
}
