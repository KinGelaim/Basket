using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Windows.Forms;
using SharpGL;
using SharpGL.SceneGraph.Assets;

namespace My3DOpenGL
{
    public partial class Form1 : Form
    {
        double angleTri = 0;
        double angleQuad = 0;

        public Form1()
        {
            InitializeComponent();
        }

        private void Form1_Load(object sender, EventArgs e)
        {
            
        }

        //Перерисовка OpenGlContol
        private void openGLControl1_OpenGLDraw(object sender, RenderEventArgs args)
        {
            //Получаем объект наподобие грапхикс
            OpenGL gl = openGLControl1.OpenGL;

            //Очищаем цветовой буфер и буфер глубины (для 3д графики)
            gl.Clear(OpenGL.GL_COLOR_BUFFER_BIT | OpenGL.GL_DEPTH_BUFFER_BIT);

            //Обновляем видовую матрицу
            gl.LoadIdentity();

            //Сдвигаем перо (x,y,z)
            gl.Translate(-1.5, 0, -6);

            //Вращение треугольника (угол и ось вокруг которой происходит вращение)
            gl.Rotate(angleTri, 0, 1, 0);

            //Начинаем рисование
            gl.Begin(OpenGL.GL_TRIANGLES);

            gl.Color(1f, 0f, 0f);      //Красный
            gl.Vertex(0, 1, 0);     //Вершины точки
            gl.Color(0f, 1f, 0f);      //Зелёный
            gl.Vertex(-1, -1, 1);
            gl.Color(0f, 0f, 1f);      //Синий
            gl.Vertex(1, -1, 1);

            gl.Color(1f, 0f, 0f);
            gl.Vertex(0, 1, 0);
            gl.Color(0f, 0f, 1f);
            gl.Vertex(1, -1, 1);
            gl.Color(0f, 1f, 0f);
            gl.Vertex(1, -1, -1);

            gl.Color(1f, 0f, 0f);
            gl.Vertex(0, 1, 0);
            gl.Color(0f, 1f, 0f);
            gl.Vertex(1, -1, -1);
            gl.Color(0f, 0f, 1f);
            gl.Vertex(-1, -1, -1);

            gl.Color(1f, 0f, 0f);
            gl.Vertex(0, 1, 0);
            gl.Color(0f, 0f, 1f);
            gl.Vertex(-1, -1, -1);
            gl.Color(0f, 1f, 0f);
            gl.Vertex(-1, -1, 1);

            //Закончили создание фигуры
            gl.End();

            gl.LoadIdentity();
            gl.Translate(1.5, 0, -7);
            gl.Rotate(angleQuad, 1, 1, 1);

            gl.Begin(OpenGL.GL_QUADS);

            gl.Color(0f, 1f, 0f);
            gl.Vertex(1, 1, -1);
            gl.Vertex(-1, 1, -1);
            gl.Vertex(-1, 1, 1);
            gl.Vertex(1, 1, 1);

            gl.Color(1f, 0.5f, 0f);
            gl.Vertex(1, -1, 1);
            gl.Vertex(-1, -1, 1);
            gl.Vertex(-1, -1, -1);
            gl.Vertex(1, -1, -1);

            gl.Color(1f, 0f, 0f);
            gl.Vertex(1, 1, 1);
            gl.Vertex(-1, 1, 1);
            gl.Vertex(-1, -1, 1);
            gl.Vertex(1, -1, 1);

            gl.Color(1f, 1f, 0f);
            gl.Vertex(1, -1, -1);
            gl.Vertex(-1, -1, -1);
            gl.Vertex(-1, 1, -1);
            gl.Vertex(1, 1, -1);

            gl.Color(0f, 0f, 1f);
            gl.Vertex(-1, 1, 1);
            gl.Vertex(-1, 1, -1);
            gl.Vertex(-1, -1, -1);
            gl.Vertex(-1, -1, 1);

            gl.Color(1f, 0f, 1f);
            gl.Vertex(1, 1, -1);
            gl.Vertex(1, 1, 1);
            gl.Vertex(1, -1, 1);
            gl.Vertex(1, -1, -1);

            gl.End();

            //Необходимо отрисовать всё, что было до этого, прежде чем сменить буфер
            gl.Flush();

            angleTri += 3;
            angleQuad += 3;
        }
    }
}
