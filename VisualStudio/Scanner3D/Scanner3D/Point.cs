using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

namespace Scanner3D
{
    public class Point
    {
        public float x { get; set; }
        public float y { get; set; }
        public float z { get; set; }
        public string distance { get; set; }

        public float colorR = 1.0f;
        public float colorG = 1.0f;
        public float colorB = 1.0f;

        public float size = 1.0f;

        public Point() { }

        public static Point NewPoint(double radius, string point, double alpha, float pointZ, string distance = null)
        {
            point = point.Replace('.', ',');
            Point newPoint = new Point();
            if (alpha <= 90)
            {
                newPoint.x = (float)((radius - Convert.ToDouble(point)) * Math.Cos(alpha * Math.PI / 180));
                newPoint.y = (float)((radius - Convert.ToDouble(point)) * Math.Sin(alpha * Math.PI / 180));
            }
            else if (alpha > 90 && alpha <= 180)
            {
                newPoint.x = -1 * (float)((radius - Convert.ToDouble(point)) * Math.Cos((180 - alpha) * Math.PI / 180));
                newPoint.y = (float)((radius - Convert.ToDouble(point)) * Math.Sin((180 - alpha) * Math.PI / 180));
            }
            else if (alpha > 180 && alpha <= 270)
            {
                newPoint.x = -1 * (float)((radius - Convert.ToDouble(point)) * Math.Cos((alpha - 180) * Math.PI / 180));
                newPoint.y = -1 * (float)((radius - Convert.ToDouble(point)) * Math.Sin((alpha - 180) * Math.PI / 180));
            }
            else if (alpha > 270)
            {
                newPoint.x = (float)((radius - Convert.ToDouble(point)) * Math.Cos((360 - alpha) * Math.PI / 180));
                newPoint.y = -1 * (float)((radius - Convert.ToDouble(point)) * Math.Sin((360 - alpha) * Math.PI / 180));
            }
            newPoint.z = pointZ;
            newPoint.distance = distance;
            return newPoint;
        }
    }
}
