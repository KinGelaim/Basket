using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Windows;
using System.Windows.Controls;
using System.Windows.Data;
using System.Windows.Documents;
using System.Windows.Input;
using System.Windows.Media;
using System.Windows.Media.Imaging;
using System.Windows.Shapes;
using SharpGL;
using System.Drawing.Printing;
using System.Drawing;

namespace BallisticWind
{
    /// <summary>
    /// Логика взаимодействия для ModelingWindow.xaml
    /// </summary>
    public partial class ModelingWindow : Window
    {
        //Координаты точек
        List<SecondaryRashet> arr;

        //Поворот по осям
        double angleRotateX = 0;
        double angleRotateY = 0;
        double angleRotateZ = 0;

        //Постоянное вращение вдоль оси Y
        bool isRotateY = false;

        //Управление мышкой
        bool isMouseDown = false;
        double prevX = 0;
        double prevY = 0;

        //Дистанция камеры
        double viewDistance = -10;

        //Для печати
        Bitmap bmp;

        //Цвет фона
        float backColor = 255f;
        //Цвет текста
        float textColor = 0f;

        public ModelingWindow(List<SecondaryRashet> arr)
        {
            InitializeComponent();

            this.arr = arr;
        }

        private void OpenGLControl_OpenGLDraw(object sender, SharpGL.SceneGraph.OpenGLEventArgs args)
        {
            OpenGL gl = openGLControl1.OpenGL;

            
            gl.Clear(OpenGL.GL_COLOR_BUFFER_BIT | OpenGL.GL_DEPTH_BUFFER_BIT);
            gl.ClearColor(backColor, backColor, backColor, 0);

            gl.LoadIdentity();

            gl.Translate(0, 0, viewDistance);

            gl.Rotate(angleRotateX, 1, 0, 0);
            gl.Rotate(angleRotateY, 0, 1, 0);
            gl.Rotate(angleRotateZ, 0, 0, 1);

            for (int i = 0; i < arr.Count; i++)
            {
                double sizeX = SearchSize(arr[i].x, arr.Min(pos => pos.x), arr.Max(pos => pos.x), -4, 4);
                double sizeY = SearchSize(arr[i].y, arr.Min(pos => pos.y), arr.Max(pos => pos.y), -3.5, 3.5);
                double sizeZ = SearchSize(arr[i].z, arr.Min(pos => pos.z), arr.Max(pos => pos.z), -3, 3);
                DrawQuad(gl, new Position(sizeX, sizeY, sizeZ), 0.2);
            }

            DrawEarth(gl, -3.5);

            DrawAxies(gl, -3.4);

            gl.DrawText(0, (int)this.Height - 60, textColor, textColor, textColor, "Times New Roman", 20, "X{" + arr.Min(pos => pos.x) + ":" + arr.Max(pos => pos.x) + "}");
            gl.DrawText(0, (int)this.Height - 80, textColor, textColor, textColor, "Times New Roman", 20, "Y{" + arr.Min(pos => pos.y) + ":" + arr.Max(pos => pos.y) + "}");
            gl.DrawText(0, (int)this.Height - 100, textColor, textColor, textColor, "Times New Roman", 20, "Z{" + arr.Min(pos => pos.z) + ":" + arr.Max(pos => pos.z) + "}");

            gl.Flush();

            if (isRotateY)
                angleRotateY++;
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

        private void Window_MouseWheel(object sender, MouseWheelEventArgs e)
        {
            if (e.Delta > 0)
                viewDistance++;
            else
                viewDistance--;
        }

        private void openGLControl1_MouseDown(object sender, MouseButtonEventArgs e)
        {
            if (e.LeftButton == MouseButtonState.Pressed)
            {
                isMouseDown = true;
                prevX = e.GetPosition(openGLControl1).X;
                prevY = e.GetPosition(openGLControl1).Y;
            }
            if (e.RightButton == MouseButtonState.Pressed)
                if (backColor == 255f)
                {
                    backColor = 0f;
                    textColor = 1f;
                }
                else
                {
                    backColor = 255f;
                    textColor = 0f;
                }
        }

        private void openGLControl1_MouseMove(object sender, MouseEventArgs e)
        {
            if (isMouseDown)
            {
                double prX = prevX - e.GetPosition(openGLControl1).X;
                double prY = prevY - e.GetPosition(openGLControl1).Y;

                angleRotateY += prX;
                angleRotateX += prY;

                prevX = e.GetPosition(openGLControl1).X;
                prevY = e.GetPosition(openGLControl1).Y;
            }
        }

        private void openGLControl1_MouseUp(object sender, MouseButtonEventArgs e)
        {
            if (e.LeftButton == MouseButtonState.Released)
                isMouseDown = false;
        }

        private void btnPrint_Click(object sender, RoutedEventArgs e)
        {
            //PrintDialog pd = new PrintDialog();
            PrintDocument pDocument = new PrintDocument();
            pDocument.PrintPage += Document_PrintPage;

            System.Windows.Forms.PrintPreviewDialog ppd = new System.Windows.Forms.PrintPreviewDialog();

            ppd.Document = pDocument;

            bmp = new Bitmap((int)this.Width, (int)this.Height);
            Graphics mg = Graphics.FromImage(bmp);
            //btnPrint.Visibility = System.Windows.Visibility.Hidden;
            mg.CopyFromScreen((int)this.Left, (int)this.Top, 0, 0, new System.Drawing.Size((int)this.Width, (int)this.Height));
            //btnPrint.Visibility = System.Windows.Visibility.Visible;

            if(ppd.ShowDialog() == System.Windows.Forms.DialogResult.OK)
                pDocument.Print();

            bmp.Dispose();
        }

        private void Document_PrintPage(object sender, PrintPageEventArgs e)
        {
            e.Graphics.DrawImage(bmp, 0, 0);
        }

        //Обработка клавиш для автоматического вращения
        private void Window_KeyUp(object sender, KeyEventArgs e)
        {
            if (e.Key == Key.Home)
                isRotateY = !isRotateY;
        }
    }
}
