using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Windows.Forms;

namespace SearchPathVisual
{
    public partial class Form1 : Form
    {
        // Для рисования
        private Graphics g;

        // Карта
        int[][] map;

        // Параметры сетки
        int countRow = 30;
        int countCell = 25;

        // Параметры квадратика
        int heightSquare = 0;
        int widthSquare = 0;

        // Граф карты
        Dictionary<string, string[]> graph;

        // Старт
        int[] startI;
        string start;

        // Конец
        int[] endI;
        string end;

        // Для алгоритма (A)
        Dictionary<string, string> visited;

        // Переключение между алгоритмами
        bool isStarsAlgorithm = false;

        public Form1()
        {
            InitializeComponent();

            // Настройки
            DoubleBuffered = true;

            StartAll();
        }

        #region A

        // Генерация карты, создания графа, отрисовка карты
        private void StartAll()
        {
            // Начальные переменные
            CreateMap();
            CreateGraph();

            startI = new int[] { 0, 0 };
            start = "0,0";
            endI = new int[] { 0, 0 };
            end = "0,0";

            // Отрисовка карты
            PaintMap();
        }

        // Создание матрицы карты
        private void CreateMap()
        {
            // Создаём матрицу
            Random rand = new Random();
            widthSquare = Convert.ToInt32(ClientSize.Width / countCell);
            heightSquare = Convert.ToInt32(ClientSize.Height / countRow);
            map = new int[countCell][];
            for (int i = 0; i < countCell; i++)
            {
                map[i] = new int[countRow];
                for (int j = 0; j < countRow; j++)
                {
                    map[i][j] = rand.Next(1, 100) < 80 ? 0 : 1;
                }
            }
            map[0][0] = 0;
        }

        // Отрисовка матрицы карты
        private void PaintMap()
        {
            // Создаём изображение из фона
            BackgroundImage = new Bitmap(this.Width, this.Height);
            g = Graphics.FromImage(BackgroundImage);

            // Очищаем
            g.Clear(Color.White);

            // Рисуем всю карту
            for (int i = 0; i < map.Length; i++)
            {
                for (int j = 0; j < map[i].Length; j++)
                {
                    if (map[i][j] == 1)
                    {
                        g.FillRectangle(Brushes.DarkRed, new Rectangle(i * widthSquare, j * heightSquare, widthSquare, heightSquare));
                    }
                }
            }

            // Рисуем путь
            if (visited != null)
            {
                string current = end;
                while (current != start)
                {
                    current = visited[current];
                    string[] currentS = current.Split(',');
                    g.FillRectangle(Brushes.DarkOrchid, new Rectangle(Convert.ToInt32(currentS[0]) * widthSquare, Convert.ToInt32(currentS[1]) * heightSquare, widthSquare, heightSquare));
                }
            }

            // Рисуем старт
            g.FillRectangle(Brushes.Blue, new Rectangle(startI[0] * widthSquare, startI[1] * heightSquare, widthSquare, heightSquare));

            // Рисуем конец
            g.FillRectangle(Brushes.Pink, new Rectangle(endI[0] * widthSquare, endI[1] * heightSquare, widthSquare, heightSquare));
        }

        // Создание графа из карты
        private void CreateGraph()
        {
            graph = new Dictionary<string, string[]>();
            for (int i = 0; i < map.Length; i++)
            {
                for (int j = 0; j < map[i].Length; j++)
                {
                    graph[i + "," + j] = GetNextNodes(i, j);
                }
            }
        }

        // Взятие следующих узлов относительно точки
        private string[] GetNextNodes(int i, int j)
        {
            List<string> points = new List<string>();
            if (i - 1 >= 0)
                if (map[i - 1][j] != 1)
                    points.Add((i - 1) + "," + j);
            if (j - 1 >= 0)
                if (map[i][j - 1] != 1)
                    points.Add(i + "," + (j - 1));
            if (j + 1 < countRow)
                if (map[i][j + 1] != 1)
                    points.Add(i + "," + (j + 1));
            if (i + 1 < countCell)
                if (map[i + 1][j] != 1)
                    points.Add((i + 1) + "," + j);
            return points.ToArray();
        }

        // Работа алгоритма поиска пути
        private Dictionary<char, char> StartAlgorithm(Dictionary<char, char[]> graph, char start, char end)
        {
            Queue<Char> queue = new Queue<char>();
            queue.Enqueue(start);
            
            Dictionary<char, char> visited = new Dictionary<char, char>();

            while (queue.Count > 0)
            {
                char current = queue.Dequeue();
                if (current == end)
                    break;

                char[] nodes = graph[current];
                foreach (char next in nodes)
                {
                    if (!visited.ContainsKey(next))
                    {
                        queue.Enqueue(next);
                        visited[next] = current;
                    }
                }
            }

            return visited;
        }

        // Алгоритм поиска пути
        private void StartAlgorithm()
        {
            Queue<string> queue = new Queue<string>();
            queue.Enqueue(start);

            visited = new Dictionary<string, string>();

            while (queue.Count > 0)
            {
                string current = queue.Dequeue();
                if (current == end)
                    break;

                string[] nodes = graph[current];
                foreach (string next in nodes)
                {
                    if (!visited.ContainsKey(next))
                    {
                        queue.Enqueue(next);
                        visited[next] = current;
                    }
                }
            }
        }

        #endregion

        #region A*

        private void StartAllStar()
        {
            // Начальные переменные
            startI = new int[] { 0, 0 };
            start = "0,0";
            endI = new int[] { 0, 0 };
            end = "0,0";

            // Генерация карты и создание графа
            CreateMapStar();
            CreateGraphStar();

            // Отрисовка карты
            PaintMapStar();
        }

        // Создание матрицы карты
        private void CreateMapStar()
        {
            // Создаём матрицу
            Random rand = new Random();
            widthSquare = Convert.ToInt32(ClientSize.Width / countCell);
            heightSquare = Convert.ToInt32(ClientSize.Height / countRow);
            map = new int[countCell][];
            for (int i = 0; i < countCell; i++)
            {
                map[i] = new int[countRow];
                for (int j = 0; j < countRow; j++)
                {
                    int randomValue = rand.Next(1, 100);
                    if (randomValue < 40)
                        map[i][j] = 0;
                    else if (randomValue < 60)
                        map[i][j] = 1;
                    else if (randomValue < 80)
                        map[i][j] = 2;
                    else
                        map[i][j] = 3;
                }
            }
            map[0][0] = 0;
        }

        // Создание графа из карты
        private void CreateGraphStar()
        {
            graph = new Dictionary<string, string[]>();
            for (int i = 0; i < map.Length; i++)
            {
                for (int j = 0; j < map[i].Length; j++)
                {
                    graph[i + "," + j] = GetNextNodesStar(i, j);
                }
            }
        }

        // Взятие следующих узлов относительно точки
        private string[] GetNextNodesStar(int i, int j)
        {
            List<string> points = new List<string>();
            if (i - 1 >= 0)
                points.Add((i - 1) + "," + j);
            if (j - 1 >= 0)
                points.Add(i + "," + (j - 1));
            if (j + 1 < countRow)
                points.Add(i + "," + (j + 1));
            if (i + 1 < countCell)
                points.Add((i + 1) + "," + j);
            return points.ToArray();
        }

        // Алгоритм поиска пути (Дейкстра, A*)
        private void StartAlgorithmStar()
        {
            Queue<string> queue = new Queue<string>();
            queue.Enqueue(start);

            visited = new Dictionary<string, string>();

            while (queue.Count > 0)
            {
                string current = queue.Dequeue();
                if (current == end)
                    break;

                string[] nodes = graph[current];
                foreach (string next in nodes)
                {
                    if (!visited.ContainsKey(next))
                    {
                        queue.Enqueue(next);
                        visited[next] = current;
                    }
                }
            }
        }

        #endregion

        // Строим путь до выбранной точки
        private void Form1_MouseClick(object sender, MouseEventArgs e)
        {
            if (e.Button == System.Windows.Forms.MouseButtons.Left)
            {
                end = e.X / widthSquare + "," + e.Y / heightSquare;
                endI = new int[] { e.X / widthSquare, e.Y / heightSquare };

                if ((endI[0] != 0 && endI[1] != 0 || (endI[0] > 0 || endI[1] > 0)) && endI[0] < countCell && endI[1] < countRow)
                {
                    if (!isStarsAlgorithm)
                    {
                        if (map[endI[0]][endI[1]] != 1)
                        {
                            StartAlgorithm();
                            PaintMap();
                        }
                    }
                    else
                    {
                        StartAlgorithmStar();
                        PaintMapStar();
                    }
                }
            }
            else if(e.Button == System.Windows.Forms.MouseButtons.Right)
            {
                if (!isStarsAlgorithm)
                    StartAll();
                else
                    StartAllStar();
            }
            else
            {
                isStarsAlgorithm = !isStarsAlgorithm;
            }
        }
    }
}
