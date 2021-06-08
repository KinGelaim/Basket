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
using SharpGL.Enumerations;

namespace Textures
{
    public partial class Form1 : Form
    {
        double angleQuad = 0;

        Texture texture = new Texture();

        public Form1()
        {
            InitializeComponent();

            SharpGL.OpenGL gl = openGLControl1.OpenGL;

            //Включаем режим 2Д текстур
            gl.Enable(OpenGL.GL_TEXTURE_2D);

            //Текстура
            texture.Create(gl, "wall.png");
        }

        private void openGLControl1_OpenGLDraw(object sender, RenderEventArgs args)
        {
            SharpGL.OpenGL gl = openGLControl1.OpenGL;

            gl.Clear(OpenGL.GL_COLOR_BUFFER_BIT | OpenGL.GL_DEPTH_BUFFER_BIT);

            gl.LoadIdentity();

            gl.Translate(0, 0, -6);

            gl.Rotate(angleQuad, 0, 1, 0);

            texture.Bind(gl);

            gl.Begin(OpenGL.GL_QUADS);

            gl.TexCoord(0, 0);
            gl.Vertex(-1, -1, 1);
            gl.TexCoord(1, 0);
            gl.Vertex(1, -1, 1);
            gl.TexCoord(1, 1);
            gl.Vertex(1, 1, 1);
            gl.TexCoord(0, 1);
            gl.Vertex(-1, 1, 1);

            gl.TexCoord(1, 0);
            gl.Vertex(-1, -1, -1);
            gl.TexCoord(1, 1);
            gl.Vertex(-1, 1, -1);
            gl.TexCoord(0, 1);
            gl.Vertex(1, 1, -1);
            gl.TexCoord(0, 0);
            gl.Vertex(1, -1, -1);

            gl.TexCoord(0, 1);
            gl.Vertex(-1, 1, -1);
            gl.TexCoord(0, 0);
            gl.Vertex(-1, 1, 1);
            gl.TexCoord(1, 0);
            gl.Vertex(1, 1, 1);
            gl.TexCoord(1, 1);
            gl.Vertex(1, 1, -1);

            gl.End();

            gl.Flush();

            angleQuad += 3;
        }
    }
}
