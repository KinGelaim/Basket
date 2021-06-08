using System;
using System.Collections.Generic;
using System.Windows.Forms;
using SharpGL;
using System.IO;
using Newtonsoft.Json;
using SharpGL.Enumerations;
using System.Drawing;
using System.Drawing.Printing;
using System.Linq;

namespace Scanner3D
{
    public partial class Form1 : Form
    {
        string strJson = "";    //Строковое представление JSON
        List<Point> pointList;  //Коллекция всех точек

        // Контекст
        OpenGL gl;

        //Переменные настроек
        bool isDrawPoint = false;
        bool isDrawLayer = true;
        bool isDrawWall = true;
        float alpha = 0.2f;

        //Поворот по осям
        double angleRotateX = 0;
        double angleRotateY = 0;
        double angleRotateZ = 0;

        //Сдвиг камеры
        double translateCameraX = 0;
        double translateCameraY = 0;

        //Постоянное вращение вдоль оси Y
        bool isRotateY = false;

        //Управление мышкой
        bool isLeftMouseDown = false;
        int beginRectX = 0;
        int beginRectY = 0;
        int endRectX = 0;
        int endRectY = 0;
        bool isRightMouseDown = false;
        int prevX = 0;
        int prevY = 0;

        //Дистанция камеры
        double viewDistance = -30;

        // Для отображения точек во время считывания
        private bool isFirstRecive = true;
        private bool isFullDraw = true;
        public Action<string, string> drawPointRecive = delegate { };
        private string oldL = "";
        private int countPointsOnLayer = 0;
        private int oldCountPointsOnLayer = 0;

        // Для печати
        Bitmap bmp;

        public Form1()
        {
            InitializeComponent();

            strJson = "";
            pointList = new List<Point>();

            this.MouseWheel += new MouseEventHandler(this.Form1_MouseWheel);

            this.drawPointRecive += AddPrPoint;

            gl = openGLControl1.OpenGL;
        }


        #region Верхнее меню

        private void beginScanningToolStripMenuItem_Click(object sender, EventArgs e)
        {
            bool prBool = isDrawPoint;
            isDrawPoint = true;
            isFirstRecive = true;
            isFullDraw = false;
            FormConnection fc = new FormConnection(drawPointRecive);
            fc.ShowDialog();
            isDrawPoint = prBool;
            isFullDraw = true;
            if (fc.isExit)
            {
                strJson = fc.reciveData;
                CreatePointFromJson();
            }
        }

        private void logToolStripMenuItem_Click(object sender, EventArgs e)
        {
            bool prPointBool = isDrawPoint;
            bool prWallBool = isDrawWall;
            bool prLayerBool = isDrawLayer;
            isDrawLayer = false;
            isDrawWall = false;
            isDrawPoint = true;
            FormLogs fl = new FormLogs(pointList);
            fl.ShowDialog();
            isDrawPoint = prPointBool;
            isDrawWall = prWallBool;
            isDrawLayer = prLayerBool;
        }

        private void openToolStripMenuItem_Click(object sender, EventArgs e)
        {
            OpenFileDialog ofd = new OpenFileDialog();
            ofd.FileName = "";
            ofd.DefaultExt = "txt";
            ofd.Filter = "Текст|*.txt";
            ofd.Title = "Открыть документ";
            ofd.Multiselect = false;
            if (ofd.ShowDialog() == System.Windows.Forms.DialogResult.OK)
            {
                if (File.Exists(ofd.FileName))
                {
                    try
                    {
                        using (StreamReader sr = new StreamReader(ofd.FileName))
                        {
                            strJson = sr.ReadToEnd();
                        }
                    }
                    catch (Exception ex)
                    {
                        MessageBox.Show("Не удалось загрузить файл!\n" + ex.Message, "Ошибка!", MessageBoxButtons.OK, MessageBoxIcon.Information);
                    }
                }
            }
            CreatePointFromJson();
        }

        private void saveJsonToolStripMenuItem_Click(object sender, EventArgs e)
        {
            if (!string.IsNullOrWhiteSpace(strJson))
            {
                SaveFileDialog sfd = new SaveFileDialog();
                sfd.FileName = "";
                sfd.DefaultExt = "txt";
                sfd.Filter = "Текст|*.txt";
                sfd.Title = "Сохранить документ";
                if (sfd.ShowDialog() == System.Windows.Forms.DialogResult.OK)
                {
                    try
                    {
                        using (StreamWriter sw = new StreamWriter(sfd.FileName))
                        {
                            sw.Write(strJson);
                        }
                        MessageBox.Show("Исходные данные сохранены");
                    }
                    catch (Exception ex)
                    {
                        MessageBox.Show("Не удалось сохранить исходные данные!\n" + ex.Message, "Ошибка!", MessageBoxButtons.OK, MessageBoxIcon.Information);
                    }
                }
            }
            else
                MessageBox.Show("Исходная строка JSON пустая!");
        }

        private void saveObjToolStripMenuItem_Click(object sender, EventArgs e)
        {
            if (pointList.Count > 0)
            {
                SaveFileDialog sfd = new SaveFileDialog();
                sfd.FileName = "";
                sfd.DefaultExt = "obj";
                sfd.Filter = "obj|*.obj";
                sfd.Title = "Сохранить 3d модель";
                if (sfd.ShowDialog() == System.Windows.Forms.DialogResult.OK)
                {
                    try
                    {
                        string model = CreateObjFromJson();
                        using (StreamWriter sw = new StreamWriter(sfd.FileName))
                        {
                            sw.Write(model);
                        }
                        MessageBox.Show("Модель сохранена");
                    }
                    catch (Exception ex)
                    {
                        MessageBox.Show("Не удалось сохранить модель!\n" + ex.Message, "Ошибка!", MessageBoxButtons.OK, MessageBoxIcon.Information);
                    }
                }
            }
            else
                MessageBox.Show("Коллекция точек пуста!");
        }

        private string CreateObjFromJson()
        {
            string model = "# " + pointList.Count + " vertices\n";
            model += "# n triangular faces\n\r";
            model += "mtllib\n\r";

            //Создаём вершины
            for (int i = 0; i < pointList.Count; i++)
            {
                model += "v " + Convert.ToString(Math.Round(pointList[i].x, 5)).Replace(",", ".") + " " + Convert.ToString(Math.Round(pointList[i].y, 5)).Replace(",", ".") + " " + Convert.ToString(Math.Round(pointList[i].z, 5)).Replace(",", ".") + "\n";
            }

            //Создаём фрагменты стен
            model += "\n";
            float firstZ = pointList[0].z;
            float secondZ = pointList.First(point => point.z != firstZ).z;
            int firstInd = 0;
            int secondInd = 0;
            for (int i = 0; i < pointList.Count; i++ )
            {
                if(pointList[i].z == secondZ)
                {
                    secondInd = i;
                    break;
                }
            }
            firstInd++;
            secondInd++;
            int oldFirstInd = firstInd;
            int oldSecondInd = secondInd;
            for (int i = 0; i < pointList.Count; i++)
            {
                if(secondInd <= pointList.Count)
                {
                    if (firstInd + 1 == oldSecondInd)
                    {
                        model += "f " + firstInd + " " + oldFirstInd + " " + oldSecondInd + "\n";
                        model += "f " + firstInd + " " + oldSecondInd + " " + secondInd + "\n";
                        oldFirstInd = firstInd + 1;
                        oldSecondInd = secondInd + 1;
                    }
                    else
                    {
                        model += "f " + firstInd + " " + (firstInd + 1) + " " + (secondInd + 1) + "\n";
                        model += "f " + firstInd + " " + (secondInd + 1) + " " + secondInd + "\n";
                    }
                    firstInd++;
                    secondInd++;
                }
            }

            //Создаём фрагменты низа (триангуляция)
            // Вытаскиваем вершины низа
            float downPositionZ = pointList[0].z;
            float upPositionZ = pointList[0].z;
            List<float> downPoints = new List<float>();
            for (int i = 0; i < pointList.Count; i++)
            {
                if(pointList[i].z == downPositionZ)
                {
                    downPoints.Add(pointList[i].x);
                    downPoints.Add(pointList[i].y);
                }
                if (pointList[i].z > upPositionZ)
                    upPositionZ = pointList[i].z;
            }
            // Триангуляция
            Polygon downPolygon = new Polygon(downPoints.ToArray(), false);
            if (downPolygon.getTriangles() != null)
            {
                Point[] downFragments = downPolygon.getFragmentTriangles();
                for (int i = 0; i < downFragments.Length; i++)
                {
                    model += "f " + downFragments[i].z + " " + downFragments[i].y + " " + downFragments[i].x + "\n";
                }
            }

            //Создаём фрагменты верха
            int? indexOffset = null;
            List<float> upPoints = new List<float>();
            for (int i = 0; i < pointList.Count; i++)
            {
                if (pointList[i].z == upPositionZ)
                {
                    upPoints.Add(pointList[i].x);
                    upPoints.Add(pointList[i].y);
                    if (indexOffset == null)
                        indexOffset = i;
                }
            }
            Polygon upPolygon = new Polygon(upPoints.ToArray(), false);
            if (upPolygon.getTriangles() != null)
            {
                Point[] upFragments = upPolygon.getFragmentTriangles();
                for (int i = 0; i < upFragments.Length; i++)
                {
                    model += "f " + (indexOffset + upFragments[i].x) + " " + (indexOffset + upFragments[i].y) + " " + (indexOffset + upFragments[i].z) + "\n";
                }
            }

            return model;
        }

        private void printToolStripMenuItem_Click(object sender, EventArgs e)
        {
            PrintDocument pDocument = new PrintDocument();
            pDocument.PrintPage += Document_PrintPage;

            System.Windows.Forms.PrintPreviewDialog ppd = new System.Windows.Forms.PrintPreviewDialog();

            ppd.Document = pDocument;

            bmp = new Bitmap((int)this.Width, (int)this.Height);
            Graphics mg = Graphics.FromImage(bmp);
            //btnPrint.Visibility = System.Windows.Visibility.Hidden;
            mg.CopyFromScreen((int)this.Left, (int)this.Top, 0, 0, new System.Drawing.Size((int)this.Width, (int)this.Height));
            //btnPrint.Visibility = System.Windows.Visibility.Visible;

            if (ppd.ShowDialog() == System.Windows.Forms.DialogResult.OK)
                pDocument.Print();

            bmp.Dispose();
        }

        private void Document_PrintPage(object sender, PrintPageEventArgs e)
        {
            e.Graphics.DrawImage(bmp, 0, 0);
        }

        private void exitToolStripMenuItem_Click(object sender, EventArgs e)
        {
            Application.Exit();
        }

        private void settingsToolStripMenuItem_Click(object sender, EventArgs e)
        {
            FormSettings fs = new FormSettings();
            fs.ShowDialog();
            if (fs.isExit)
                CreatePointFromJson();
        }

        private void aboutTheProgrammToolStripMenuItem_Click(object sender, EventArgs e)
        {
            FormAboutBox fab = new FormAboutBox();
            fab.ShowDialog();
        }

        #endregion


        private void CreatePointFromJson()
        {
            isFullDraw = true;
            double radius = Properties.Settings.Default.radius;
            pointList.Clear();
            if(!String.IsNullOrWhiteSpace(strJson))
            {
                var dataJson = JsonConvert.DeserializeObject<List<Dictionary<string, string>>>(strJson);
                foreach (Dictionary<string, string> pr in dataJson)
                {
                    float pointZ = 0;
                    foreach (KeyValuePair<string, string> keyValue in pr)
                    {
                        if (keyValue.Key == "l")
                            pointZ = (float)Convert.ToDouble(keyValue.Value);
                        if (keyValue.Key == "d")
                        {
                            string[] arrPr = keyValue.Value.Split(';');
                            double stepAlpha = 360.0 / Convert.ToDouble(arrPr.Length);
                            for (int i = 0; i < arrPr.Length; i++)
                            {
                                double alpha = stepAlpha * i;
                                Point newPoint = Point.NewPoint(radius, arrPr[i], alpha, pointZ, arrPr[i]);
                                pointList.Add(newPoint);
                            }
                        }
                    }
                }
            }
            //MessageBox.Show(pointList.Count.ToString());
        }

        private void openGLControl1_OpenGLDraw(object sender, RenderEventArgs args)
        {
            //gl = openGLControl1.OpenGL;

            gl.Clear(OpenGL.GL_COLOR_BUFFER_BIT | OpenGL.GL_DEPTH_BUFFER_BIT);

            gl.Perspective(45.0, openGLControl1.Width / openGLControl1.Height, 0.1, 300.0);

            gl.LoadIdentity();

            gl.Translate(translateCameraX, translateCameraY, viewDistance);
            
            gl.Rotate(angleRotateX, 1, 0, 0);
            gl.Rotate(angleRotateY, 0, 1, 0);
            gl.Rotate(angleRotateZ, 0, 0, 1);

            //Отрисовка JSON объекта
            DrawPoints(gl);

            //Отрисовка земли
            DrawEarth(gl, -3.5);

            //Отрисовка осей
            DrawAxies(gl, -3.4);

            DrawRect();

            gl.DrawText(0, openGLControl1.Height - 20, 1f, 1, 1, "Times New Roman", 20, "Steps: ");
            gl.DrawText(0, openGLControl1.Height - 40, 1f, 1, 1, "Times New Roman", 20, "Height: ");

            gl.Flush();

            if (isRotateY)
            {
                angleRotateY++;
                //sceneControl1.OpenGL.Rotate(1, 0, 0, 1);
            }
        }

        private void DrawPoints(OpenGL gl)
        {
            if (pointList.Count > 0)
            {
                List<List<Point>> layerPointList = new List<List<Point>>();
                List<Point> layerPoint = new List<Point>();
                //Плоскости
                float lastZ = pointList[0].z;

                gl.Begin(OpenGL.GL_POLYGON);
                gl.Color(0.7f, 0.1f, 0.1f, alpha);
                foreach (Point point in pointList.ToArray())
                {
                    if (lastZ != point.z)
                    {
                        gl.End();
                        gl.Begin(OpenGL.GL_POLYGON);
                        lastZ = point.z;
                        layerPointList.Add(layerPoint);
                        layerPoint = new List<Point>();
                    }
                    if (isDrawLayer && isFullDraw)
                        gl.Vertex(point.x, point.z, point.y);
                    layerPoint.Add(point);
                }
                gl.End();
                layerPointList.Add(layerPoint);

                //Точки
                if (isDrawPoint)
                {
                    for(int i = 0; i < pointList.Count; i++)
                    {
                        gl.PointSize(pointList[i].size);
                        gl.Begin(BeginMode.Points);
                        gl.Color(pointList[i].colorR, pointList[i].colorG, pointList[i].colorB, alpha);
                        gl.Vertex(pointList[i].x, pointList[i].z, pointList[i].y);
                        gl.End();
                    }
                }

                //Стенки
                if (isDrawWall && isFullDraw)
                {
                    int positionInLayerList = 0;
                    if (positionInLayerList < layerPointList.Count)
                    {
                        for (int j = 0; j < layerPointList.Count - 1; j++)
                        {
                            for (int i = 0; i < layerPointList[positionInLayerList].Count; i++)
                            {
                                gl.Begin(OpenGL.GL_QUADS);
                                gl.Color(0.0f, 0.9f, 0.0f, alpha);
                                if (i < layerPointList[positionInLayerList].Count - 1)
                                {
                                    gl.Vertex(layerPointList[positionInLayerList][i].x, layerPointList[positionInLayerList][i].z, layerPointList[positionInLayerList][i].y);
                                    gl.Vertex(layerPointList[positionInLayerList + 1][i].x, layerPointList[positionInLayerList + 1][i].z, layerPointList[positionInLayerList + 1][i].y);
                                    gl.Vertex(layerPointList[positionInLayerList + 1][i + 1].x, layerPointList[positionInLayerList + 1][i + 1].z, layerPointList[positionInLayerList + 1][i + 1].y);
                                    gl.Vertex(layerPointList[positionInLayerList][i + 1].x, layerPointList[positionInLayerList][i + 1].z, layerPointList[positionInLayerList][i + 1].y);
                                }
                                else
                                {
                                    gl.Vertex(layerPointList[positionInLayerList][i].x, layerPointList[positionInLayerList][i].z, layerPointList[positionInLayerList][i].y);
                                    gl.Vertex(layerPointList[positionInLayerList + 1][i].x, layerPointList[positionInLayerList + 1][i].z, layerPointList[positionInLayerList + 1][i].y);
                                    gl.Vertex(layerPointList[positionInLayerList + 1][0].x, layerPointList[positionInLayerList + 1][0].z, layerPointList[positionInLayerList + 1][0].y);
                                    gl.Vertex(layerPointList[positionInLayerList][0].x, layerPointList[positionInLayerList][0].z, layerPointList[positionInLayerList][0].y);
                                }
                                gl.End();
                            }
                            positionInLayerList++;
                        }
                    }
                }
            }
        }


        #region Отрисовка во время работы

        public void AddPrPoint(string l, string point)
        {
            if(isFirstRecive)
            {
                pointList.Clear();
                isFirstRecive = false;
                countPointsOnLayer = 0;
                oldCountPointsOnLayer = 0;
            }
            if(oldL != l)
            {
                if (countPointsOnLayer != oldCountPointsOnLayer)
                    ResizeAlphaPointList(countPointsOnLayer);
                oldCountPointsOnLayer = countPointsOnLayer;
                countPointsOnLayer = 0;
                oldL = l;
            }
            countPointsOnLayer++;
            float pointZ = (float)Convert.ToDouble(l);
            double alpha;
            if (oldCountPointsOnLayer < countPointsOnLayer)
                alpha = 360 / countPointsOnLayer;
            else
                alpha = 360 / oldCountPointsOnLayer * countPointsOnLayer;
            Point newPoint = Point.NewPoint(Properties.Settings.Default.radius, point, alpha, pointZ, point);
            pointList.Add(newPoint);
        }

        private void ResizeAlphaPointList(int allPointsInLayer)
        {
            if (pointList.Count > 0)
            {
                double alpha = 360 / allPointsInLayer;
                float lastZ = pointList[pointList.Count - 1].z;
                int k = 0;
                for(int i = 0; i < pointList.Count; i++)
                {
                    if (pointList[i].z == lastZ)
                    {
                        double stepAlpha = alpha * k;
                        pointList[i] = Point.NewPoint(Properties.Settings.Default.radius, pointList[i].distance, stepAlpha, pointList[i].z);
                        k++;
                    }
                }
            }
        }

        #endregion


        #region Земля

        private void DrawEarth(OpenGL gl, double y)
        {
            gl.LoadIdentity();

            gl.Translate(translateCameraX, translateCameraY, viewDistance);

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

        #endregion


        #region Оси

        private void DrawAxies(OpenGL gl, double y)
        {
            gl.LoadIdentity();

            gl.Translate(translateCameraX, translateCameraY, viewDistance);

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

        #endregion

        #region Выделение

        private void DrawRect()
        {
            if (isLeftMouseDown)
            {
                gl.LoadIdentity();

                gl.Translate(translateCameraX, translateCameraY, viewDistance);

                //gl.Rotate(angleRotateX, 1, 0, 0);
                //gl.Rotate(angleRotateY, 0, 1, 0);
                //gl.Rotate(angleRotateZ, 0, 0, 1);

                //gl.Begin(BeginMode.Polygon);

                gl.Color(0.1f, 1f, 1f);
                gl.Ortho2D(0, openGLControl1.Width, openGLControl1.Height, 0);
                gl.Rect(beginRectX, beginRectY, endRectX, endRectY);
                //gl.Rect(endRectX, beginRectY, beginRectX, endRectY);

                //gl.End();
            }
        }

        #endregion


        #region Мышка

        //Колёсико мышки
        private void Form1_MouseWheel(object sender, MouseEventArgs e)
        {
            if (e.Delta > 0)
                viewDistance++;
            else
                viewDistance--;
        }

        //Нажатие мышки
        private void openGLControl1_MouseDown(object sender, MouseEventArgs e)
        {
            if (e.Button == System.Windows.Forms.MouseButtons.Right)
            {
                isRightMouseDown = true;
                prevX = e.X;
                prevY = e.Y;
            }
            if (e.Button == System.Windows.Forms.MouseButtons.Left)
            {
                isLeftMouseDown = true;
                beginRectX = e.X;
                beginRectY = e.Y;
            }
        }

        //Движение мышки
        private void openGLControl1_MouseMove(object sender, MouseEventArgs e)
        {
            if (isRightMouseDown)
            {
                int prX = prevX - e.X;
                int prY = prevY - e.Y;

                angleRotateY += prX;
                angleRotateX += prY;

                prevX = e.X;
                prevY = e.Y;
            }
            if (isLeftMouseDown)
            {
                endRectX = e.X;
                endRectY = e.Y;
            }
        }

        //Подъем мышки
        private void openGLControl1_MouseUp(object sender, MouseEventArgs e)
        {
            if (e.Button == System.Windows.Forms.MouseButtons.Right)
                isRightMouseDown = false;
            if (e.Button == System.Windows.Forms.MouseButtons.Left)
                isLeftMouseDown = false;
        }

        #endregion


        #region Клавиатура
        
        private void openGLControl1_KeyPress(object sender, KeyPressEventArgs e)
        {
            if (e.KeyChar == ' ')
            {
                isRotateY = !isRotateY;
            }
            if (e.KeyChar == '1')
            {
                isDrawPoint = false;
                isDrawLayer = true;
                isDrawWall = false;
                alpha = 1.0f;

                gl.Disable(OpenGL.GL_BLEND);
                gl.Enable(OpenGL.GL_DEPTH);
            }
            if (e.KeyChar == '2')
            {
                isDrawPoint = false;
                isDrawLayer = false;
                isDrawWall = true;
                alpha = 1.0f;

                gl.Disable(OpenGL.GL_BLEND);
                gl.Enable(OpenGL.GL_DEPTH);
            }
            if (e.KeyChar == '3')
            {
                isDrawPoint = false;
                isDrawLayer = true;
                isDrawWall = true;
                alpha = 1.0f;

                gl.Disable(OpenGL.GL_BLEND);
                gl.Enable(OpenGL.GL_DEPTH);
            }
            if (e.KeyChar == '4')
            {
                isDrawPoint = false;
                isDrawLayer = true;
                isDrawWall = true;

                gl.BlendFunc(BlendingSourceFactor.SourceAlpha, BlendingDestinationFactor.One);
                gl.Enable(OpenGL.GL_BLEND);
                gl.Disable(OpenGL.GL_DEPTH);
            }
            if(e.KeyChar == '5')
            {
                isDrawPoint = true;
                isDrawLayer = false;
                isDrawWall = false;
            }
            if (e.KeyChar == 'a')
            {
                translateCameraX -= 0.1;
            }
            if(e.KeyChar == 'd')
            {
                translateCameraX += 0.1;
            }
            if (e.KeyChar == 'w')
            {
                translateCameraY += 0.1;
            }
            if (e.KeyChar == 's')
            {
                translateCameraY -= 0.1;
            }
        }

        #endregion
    }
}