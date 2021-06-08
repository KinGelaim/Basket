using System;
using System.Collections.Generic;
using System.Drawing;
using System.Drawing.Drawing2D;
using System.Windows.Forms;

namespace Triangulation
{
    public partial class Form1 : Form
    {
        private Pen polygonPen;         //Для рисования контура многоугольника
        private SolidBrush trianBrush;  //Для рисования треугольников

        private Graphics g;
        private Bitmap bitmap;

        private Polygon polygon;    // Многоугольник
        private int drawCount = 0;  // Количество нарисованных треугольников в данный момент
        private Color[] colors;     // Цвета треугольников

        private float translateX, translateY, scale;
        private float mousePositionX, mousePositionY;
        private bool isMiddleMouseDown = false, isClear = false;

        private List<float> vertex = new List<float>();

        public Form1()
        {
            InitializeComponent();
        }

        private void Form1_Load(object sender, EventArgs e)
        {
            polygonPen = new Pen(Color.Black, 1);
            trianBrush = new SolidBrush(Color.Red);

            CreatePolygon();

            ScalePoints();

            draw();
        }

        private void CreatePolygon(bool isLeftVectors = true)
        {
            try
            {

                if (vertex.Count > 0)
                {
                    // Вершины многоугольника против часовой стрелки
                    polygon = new Polygon(vertex.ToArray(), isLeftVectors);
                    colors = null;
                    if (polygon.getTriangles() != null) //если триангуляция была проведена успешно
                    {
                        Triangle[] trians = polygon.getTriangles();
                        colors = new Color[trians.Length];

                        Random rand = new Random();
                        for (int i = 0; i < colors.Length; i++) //заполняем палитру случайными цветами
                            colors[i] = Color.FromArgb(rand.Next(25, 225), rand.Next(25, 225), rand.Next(25, 225));
                        PrintMessage("Триангуляция выполнена!" + Environment.NewLine +
                            "Вершин: " + polygon.getPoints().Length + Environment.NewLine +
                            "Треугольников: " + trians.Length + Environment.NewLine +
                            "Закрашено: " + drawCount);
                    }
                    else
                        PrintMessage("Триангуляция провалилась!");
                }
            }
            catch (Exception ex)
            {
                PrintMessage("Ошибка! " + ex.Message);
            }
        }

        private void ScalePoints()
        {
            if (polygon != null)
            {
                PointF[] points = polygon.getPoints();

                float minX = int.MaxValue, minY = int.MaxValue;
                float maxX = int.MinValue, maxY = int.MinValue;
                foreach (PointF p in points)
                {
                    minX = Math.Min(minX, p.X);
                    minY = Math.Min(minY, p.Y);
                    maxX = Math.Max(maxX, p.X);
                    maxY = Math.Max(maxY, p.Y);
                }

                float width = maxX - minX;
                float height = maxY - minY;

                float scaleX = pictureBox1.Width / width;
                float scaleY = pictureBox1.Height / height;
                scale = Math.Min(scaleX, scaleY) * 0.95f; //коэффициент масштабирования

                //центрирование многоугольника
                translateX = (pictureBox1.Width - width * scale) / 2 - minX * scale;
                translateY = (pictureBox1.Height - height * scale) / 2 - minY * scale;
            }
        }

        private void draw()
        {
            if (polygon != null)
            {
                bitmap = new Bitmap(pictureBox1.Width, pictureBox1.Height);
                g = Graphics.FromImage(bitmap);
                g.SmoothingMode = SmoothingMode.AntiAlias;

                drawPolygon();

                pictureBox1.Image = bitmap;
            }
        }

        private void drawPolygon() //рисует многоугольник
        {
            g.TranslateTransform(translateX, translateY); //центрируем
            g.ScaleTransform(scale, scale); //масштабируем

            //рисуем контур
            PointF[] points = polygon.getPoints();
            g.DrawLines(polygonPen, points);
            g.DrawLine(polygonPen, points[0], points[points.Length - 1]);

            GraphicsPath p = new GraphicsPath();
            Triangle[] trians = polygon.getTriangles();

            if (trians == null)
                return;

            //рисуем drawCount треугольников
            for (int i = 0; i < drawCount; i++)
            {
                Triangle t = trians[i];

                p.Reset();
                p.AddLine(t.getA(), t.getB());
                p.AddLine(t.getB(), t.getC());
                p.AddLine(t.getC(), t.getA());

                trianBrush.Color = colors[i];
                g.FillPath(trianBrush, p);
            }
        }

        private void pictureBox1_MouseClick(object sender, MouseEventArgs e)
        {
            if(e.Button == System.Windows.Forms.MouseButtons.Left)
            {
                if(isClear)
                {
                    vertex.Add(e.X);
                    vertex.Add(e.Y);
                    drawCount = 0;
                    CreatePolygon(radioButton1.Checked);
                    //ScalePoints();
                    scale = 1;
                    translateX = 1;
                    translateY = 1;
                    draw();
                }
            }
            else if (e.Button == System.Windows.Forms.MouseButtons.Right)
            {
                if (colors != null) //если триангуляция прошла успешно
                {
                    drawCount++; //увеличиваем количество отображаемых треугольников
                    if (drawCount > polygon.getTriangles().Length)
                        drawCount = 0;
                    draw();
                }
            }
        }

        private void pictureBox1_MouseDown(object sender, MouseEventArgs e)
        {
            if (e.Button == System.Windows.Forms.MouseButtons.Middle)
            {
                isMiddleMouseDown = true;
                mousePositionX = e.X;
                mousePositionY = e.Y;
            }
        }

        private void pictureBox1_MouseUp(object sender, MouseEventArgs e)
        {
            if(isMiddleMouseDown && e.Button == System.Windows.Forms.MouseButtons.Middle)
            {
                scale += (mousePositionY - e.Y) / (pictureBox1.Height / 20);
                draw();
            }
        }

        private void radioButton1_CheckedChanged(object sender, EventArgs e)
        {
            if (polygon != null)
            {
                if (radioButton1.Checked)
                {
                    polygon.StartTriangulate(true);
                }
                else if (radioButton2.Checked)
                {
                    polygon.StartTriangulate(false);
                }

                CreatePolygon(radioButton1.Checked);
            }
        }

        private void PrintMessage(string message)
        {
            textBox1.Clear();
            textBox1.Text += message;
        }

        private void button1_Click(object sender, EventArgs e)
        {
            float[] vertex1 = new float[]
            {
                248.65465f, 99.79547f,
                59.30233f, 437.65973f,
                335.534f, 321.62216f,
                83.8835f, 767.274f,
                378.47433f, 619.3895f,
                287.60056f, 1020.07654f,
                634.11926f, 1001.0913f,
                658.086f, 417.54724f,
                249.65326f, 596.4075f,
                613.14844f, 233.69086f,
                287.60056f, 228.69476f,
                622.1359f, 58.827484f,
                59.40804f, 71.653946f,
                30.957005f, 313.62842f
            };
            vertex.Clear();
            vertex.AddRange(vertex1);
            drawCount = 0;
            CreatePolygon(radioButton1.Checked);
            ScalePoints();
            draw();
            isClear = false;
        }

        private void button2_Click(object sender, EventArgs e)
        {
            // Левые вектора
            float[] vertex2 = new float[]
            {
                5.86899f, -5.86899f,
                0f, -6f,
                -5.86899f, -5.86899f,
                -6f, 0f,
                -5.86899f, 5.86899f,
                0f, 6f,
                5.86899f, 5.86899f,
                6f, 0f
            };
            vertex.Clear();
            vertex.AddRange(vertex2);
            drawCount = 0;
            CreatePolygon(radioButton1.Checked);
            ScalePoints();
            draw();
            isClear = false;
        }

        private void button3_Click(object sender, EventArgs e)
        {
            // Правые вектора
            float[] vertex3 = new float[]
            {
                6f, 0f,
                5.86899f, 5.86899f,
                0f, 6f,
                -5.86899f, 5.86899f,
                -6f, 0f,
                -5.86899f, -5.86899f,
                0f, -6f,
                5.86899f, -5.86899f
            };
            vertex.Clear();
            vertex.AddRange(vertex3);
            drawCount = 0;
            CreatePolygon(radioButton1.Checked);
            ScalePoints();
            draw();
            isClear = false;
        }

        private void button4_Click(object sender, EventArgs e)
        {
            vertex.Clear();
            polygon = null;
            colors = null;
            drawCount = 0;
            isClear = true;
        }
    }
}
