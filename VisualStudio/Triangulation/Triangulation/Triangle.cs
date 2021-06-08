using System.Drawing;

namespace Triangulation
{
    public sealed class Triangle
    {
        private PointF a, b, c;

        public Triangle(PointF a, PointF b, PointF c)
        {
            this.a = a;
            this.b = b;
            this.c = c;
        }

        public PointF getA()
        {
            return a;
        }

        public PointF getB()
        {
            return b;
        }

        public PointF getC()
        {
            return c;
        }
    }
}
