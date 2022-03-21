using System;
using System.Collections.Generic;


namespace ImageComparison
{
    public class ListPointOfDifference
    {
        private List<PointOfDifference> list;
        private List<string> path;
        private Queue<string> queue;
        private byte[][] difference = null;

        public int Count
        {
            get
            {
                return list.Count;
            }
        }

        public PointOfDifference this[int index]
        {
            get
            {
                return list[index];
            }
        }

        public ListPointOfDifference(byte[][] difference)
        {
            this.difference = difference;
            list = new List<PointOfDifference>();
            path = new List<string>();
            queue = new Queue<string>();
        }

        public void NewPoint(int x, int y)
        {
            if (list.Count > 0)
            {
                bool check = false;
                for (int i = 0; i < list.Count; i++)
                {
                    if (list[i].CheckPoint(x, y))
                    {
                        check = true;
                    }
                }
                if (!check)
                {
                    PointOfDifference pod = new PointOfDifference(x, y);
                    path.Clear();
                    queue.Clear();
                    AddPointBorder(pod, x, y);
                    list.Add(pod);
                }
            }
            else
            {
                PointOfDifference pod = new PointOfDifference(x, y);
                path.Clear();
                queue.Clear();
                AddPointBorder(pod, x, y);
                list.Add(pod);
            }
        }

        // Через определение границ (только если это граница, то добавляем в очередь) - движение по косой присутствует
        private void AddPointBorder(PointOfDifference pod, int x, int y)
        {
            if (x >= 1215)
                System.Console.WriteLine("Asd");
            pod.AddPoint(x, y);
            path.Add(x + " " + y);

            CheckPointQueueBorder(x, y);
            int k = 0;
            while (queue.Count > 0)
            {
                k++;
                if (k >= 95)
                    Console.WriteLine("asd");
                string[] q = queue.Dequeue().Split(' ');
                path.Add(q[0] + " " + q[1]);
                CheckPointQueueBorder(Convert.ToInt32(q[0]), Convert.ToInt32(q[1]));
                pod.AddPoint(Convert.ToInt32(q[0]), Convert.ToInt32(q[1]));
            }
        }

        private void CheckPointQueueBorder(int x, int y)
        {
            // Прямые
            if (x - 1 >= 0)
                if (difference[x - 1][y] == 1)
                    if (!path.Contains((x - 1) + " " + y))
                        if (!queue.Contains((x - 1) + " " + y))
                            if (CheckBorder(x - 1, y))
                                queue.Enqueue((x - 1) + " " + y);
            if (y - 1 >= 0)
                if (difference[x][y - 1] == 1)
                    if (!path.Contains(x + " " + (y - 1)))
                        if (!queue.Contains(x + " " + (y - 1)))
                            if (CheckBorder(x, y - 1))
                                queue.Enqueue(x + " " + (y - 1));
            if (x + 1 < difference.GetLength(0))
                if (difference[x + 1][y] == 1)
                    if (!path.Contains((x + 1) + " " + y))
                        if (!queue.Contains((x + 1) + " " + y))
                            if (CheckBorder(x + 1, y))
                                queue.Enqueue((x + 1) + " " + y);
            if (y + 1 < difference[x].GetLength(0))
                if (difference[x][y + 1] == 1)
                    if (!path.Contains(x + " " + (y + 1)))
                        if (!queue.Contains(x + " " + (y + 1)))
                            if (CheckBorder(x, y + 1))
                                queue.Enqueue(x + " " + (y + 1));
            // Косые
            if (x - 1 >= 0 && y - 1 >= 0)
                if (difference[x - 1][y - 1] == 1)
                    if (!path.Contains((x - 1) + " " + (y - 1)))
                        if (!queue.Contains((x - 1) + " " + (y - 1)))
                            if (CheckBorder(x - 1, y - 1))
                                queue.Enqueue((x - 1) + " " + (y - 1));
            if (x + 1 < difference.GetLength(0) && y - 1 >= 0)
                if (difference[x + 1][y - 1] == 1)
                    if (!path.Contains((x + 1) + " " + (y - 1)))
                        if (!queue.Contains((x + 1) + " " + (y - 1)))
                            if (CheckBorder(x + 1, y - 1))
                                queue.Enqueue((x + 1) + " " + (y - 1));
            if (x - 1 >= 0 && y + 1 < difference[x - 1].GetLength(0))
                if (difference[x - 1][y + 1] == 1)
                    if (!path.Contains((x - 1) + " " + (y + 1)))
                        if (!queue.Contains((x - 1) + " " + (y + 1)))
                            if (CheckBorder(x - 1, y + 1))
                                queue.Enqueue((x - 1) + " " + (y + 1));
            if (x + 1 < difference.GetLength(0) && y + 1 < difference[x + 1].GetLength(0))
                if (difference[x + 1][y + 1] == 1)
                    if (!path.Contains((x + 1) + " " + (y + 1)))
                        if (!queue.Contains((x + 1) + " " + (y + 1)))
                            if (CheckBorder(x + 1, y + 1))
                                queue.Enqueue((x + 1) + " " + (y + 1));
        }

        private bool CheckBorder(int x, int y)
        {
            // Прямые
            if (x - 1 >= 0)
                if (difference[x - 1][y] == 0)
                    return true;
            if (y - 1 >= 0)
                if (difference[x][y - 1] == 0)
                    return true;
            if (x + 1 < difference.GetLength(0))
                if (difference[x + 1][y] == 0)
                    return true;
            if (y + 1 < difference[x].GetLength(0))
                if (difference[x][y + 1] == 0)
                    return true;
            // Косые
            if (x - 1 >= 0 && y - 1 >= 0)
                if (difference[x - 1][y - 1] == 0)
                    return true;
            if (x - 1 >= 0 && y + 1 < difference[x - 1].GetLength(0))
                if (difference[x - 1][y + 1] == 0)
                    return true;
            if (x + 1 < difference.GetLength(0) && y - 1 >= 0)
                if (difference[x + 1][y - 1] == 0)
                    return true;
            if (x + 1 < difference.GetLength(0) && y + 1 < difference[x + 1].GetLength(0))
                if (difference[x + 1][y + 1] == 0)
                    return true;
            return false;
        }

        // Через очередь и вайл (скорость такая себе) - движение только по прямой
        private void AddPoint(PointOfDifference pod, int x, int y)
        {
            pod.AddPoint(x, y);
            path.Add(x + " " + y);

            CheckPointQueue(x, y);
            while (queue.Count > 0)
            {
                string[] q = queue.Dequeue().Split(' ');
                path.Add(q[0] + " " + q[1]);
                CheckPointQueue(Convert.ToInt32(q[0]), Convert.ToInt32(q[1]));
                pod.AddPoint(Convert.ToInt32(q[0]), Convert.ToInt32(q[1]));
            }
        }

        private void CheckPointQueue(int x, int y)
        {
            if (x - 1 >= 0)
                if (difference[x - 1][y] == 1)
                    if (!path.Contains((x - 1) + " " + y))
                        if (!queue.Contains((x - 1) + " " + y))
                            queue.Enqueue((x - 1) + " " + y);
            if (y - 1 >= 0)
                if (difference[x][y - 1] == 1)
                    if (!path.Contains(x + " " + (y - 1)))
                        if (!queue.Contains(x + " " + (y - 1)))
                            queue.Enqueue(x + " " + (y - 1));
            if (x + 1 < difference.GetLength(0))
                if (difference[x + 1][y] == 1)
                    if (!path.Contains((x + 1) + " " + y))
                        if (!queue.Contains((x + 1) + " " + y))
                            queue.Enqueue((x + 1) + " " + y);
            if (y + 1 < difference[x].GetLength(0))
                if (difference[x][y + 1] == 1)
                    if (!path.Contains(x + " " + (y + 1)))
                        if (!queue.Contains(x + " " + (y + 1)))
                            queue.Enqueue(x + " " + (y + 1));
        }
    }
}