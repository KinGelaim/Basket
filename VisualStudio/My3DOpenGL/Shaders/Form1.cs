using SharpGL;
using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Windows.Forms;

namespace Shaders
{
    public partial class Form1 : Form
    {
        const string vertexShaderSource = "#version 330 core\n" +
                                    "layout (location = 0) in vec3 aPos;\n" +
                                    "out vec4 vertexColor;\n" +
                                    "void main()\n" +
                                    "{\n" +
                                    "   gl_Position = vec4(aPos.x, aPos.y, aPos.z, 1.0);\n" +
                                    "   vertexColor = vec4(0.5, 0.0, 0.0, 1.0);\n" +
                                    "}\0";

        const string fragmentShaderSource = "#version 330 core\n" +
                                        "out vec4 FragColor;\n"+
                                        "in vec4 vertexColor;\n"+
                                        "void main()\n"+
                                        "{\n"+
                                        "   FragColor = vertexColor;\n"+
                                        "}\n\0";
        public Form1()
        {
            InitializeComponent();
        }

        private void openGLControl1_OpenGLDraw(object sender, SharpGL.RenderEventArgs args)
        {
            OpenGL gl = openGLControl1.OpenGL;

            gl.Clear(OpenGL.GL_COLOR_BUFFER_BIT | OpenGL.GL_DEPTH_BUFFER_BIT);

            gl.LoadIdentity();

            // Компиляция вертоксного шейдера
            uint vertexShader = gl.CreateShader(OpenGL.GL_VERTEX_SHADER);
            gl.ShaderSource(vertexShader, vertexShaderSource);
            gl.CompileShader(vertexShader);

            // Проверка на наличие ошибок компилирования вершинного шейдера
            int success;
            string infoLog = "";

            // Фрагментный шейдер
            uint fragmentShader = gl.CreateShader(OpenGL.GL_FRAGMENT_SHADER);
            gl.ShaderSource(fragmentShader, fragmentShaderSource);
            gl.CompileShader(fragmentShader);

            // Проверка на наличие ошибок компилирования фрагментного шейдера
            //
            //

            // Связывание шейдеров
            uint shaderProgram = gl.CreateProgram();
            gl.AttachShader(shaderProgram, vertexShader);
            gl.AttachShader(shaderProgram, fragmentShader);
            gl.LinkProgram(shaderProgram);

            // Проверка на наличие ошибок связывания шейдеров
            //
            //

            gl.DeleteShader(vertexShader);
            gl.DeleteShader(fragmentShader);

            // Указывание вершин (и буферов) и настройка вершинных атрибутов
            float[] vertices = {
                -0.5f, -0.5f, 0.0f, // левая вершина
                 0.5f, -0.5f, 0.0f, // правая вершина
                 0.0f,  0.5f, 0.0f  // верхняя вершина  
            };

            uint VBO = 0, VAO = 0;

            // Сначала связываем объект вершинного массива, затем связываем и устанавливаем вершинный буфер(ы), и затем конфигурируем вершинный атрибут(ы)
            gl.BindVertexArray(VAO);

            gl.BindBuffer(OpenGL.GL_ARRAY_BUFFER, VBO);
            gl.BufferData(OpenGL.GL_ARRAY_BUFFER, vertices, OpenGL.GL_STATIC_DRAW);

            gl.VertexAttribPointer(0, 3, OpenGL.GL_FLOAT, false, 3, new IntPtr(0));
            gl.EnableVertexAttribArray(0);

            gl.BindBuffer(OpenGL.GL_ARRAY_BUFFER, 0);

            gl.BindVertexArray(0);

            // Раскомментируйте строчку ниже для отрисовки полигонов в режиме каркаса
            gl.PolygonMode(OpenGL.GL_FRONT_AND_BACK, OpenGL.GL_LINE);

            // Рендеринг
            gl.ClearColor(0.2f, 0.3f, 0.3f, 1.0f);
            gl.Clear(OpenGL.GL_COLOR_BUFFER_BIT);

            // Рисуем треугольник
            gl.UseProgram(shaderProgram);
            gl.BindVertexArray(VAO);
            gl.DrawArrays(OpenGL.GL_TRIANGLES, 0, 3);

            gl.Flush();
        }
    }
}
