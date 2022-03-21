namespace ImageComparison
{
    public class PointOfDifference
    {
        public int Top { get; set; }
        public int Left { get; set; }
        public int Bottom { get; set; }
        public int Right { get; set; }

        public PointOfDifference()
        {
            Top = -1;
            Left = -1;
            Bottom = -1;
            Right = -1;
        }

        public PointOfDifference(int x, int y)
        {
            Top = y;
            Left = x;
            Bottom = y;
            Right = x;
        }

        public void AddPoint(int x, int y)
        {
            if (Top == -1 || Top > y)
                Top = y;
            if (Bottom == -1 || Bottom < y)
                Bottom = y;
            if (Left == -1 || Left > x)
                Left = x;
            if (Right == -1 || Right < x)
                Right = x;
        }

        public bool CheckPoint(int x, int y)
        {
            if (y >= Top && y <= Bottom && x >= Left && x <= Right)
            {
                return true;
            }
            return false;
        }
    }
}