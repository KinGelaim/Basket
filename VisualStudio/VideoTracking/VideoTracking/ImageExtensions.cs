using System.Drawing;


namespace ImageComparison
{
    public static class ImageExtensions
    {
        public static byte[][] CompareTo(this Bitmap image1, Bitmap image2)
        {
            byte[][] arr = new byte[image1.Width][];
            for (int i = 0; i < image1.Width; i++)
            {
                arr[i] = new byte[image1.Height];
                for (int j = 0; j < image1.Height; j++)
                {
                    if (image1.GetPixel(i, j) != image2.GetPixel(i, j))
                        arr[i][j] = 1;
                    else
                        arr[i][j] = 0;
                }
            }
            return arr;
        }

        public static byte[][] BrainCompareTo(this Bitmap image1, Bitmap image2, int lapse = 0)
        {
            byte[][] arr = new byte[image1.Width][];
            for (int i = 0; i < image1.Width; i++)
            {
                arr[i] = new byte[image1.Height];
                for (int j = 0; j < image1.Height; j++)
                {
                    Color cl1 = image1.GetPixel(i, j);
                    int val1 = cl1.A + cl1.R + cl1.G + cl1.B;
                    Color cl2 = image2.GetPixel(i, j);
                    int val2 = cl2.A + cl2.R + cl2.G + cl2.B;

                    if (System.Math.Abs(val1 - val2) > lapse)
                        arr[i][j] = 1;
                    else
                        arr[i][j] = 0;
                }
            }
            return arr;
        }

        public static Bitmap SeeDifference(this Bitmap image1, ListPointOfDifference list)
        {
            Bitmap image2 = (Bitmap)image1.Clone();
            for (int i = 0; i < list.Count; i++)
            {
                for (int j = list[i].Left; j < list[i].Right; j++)
                {
                    image2.SetPixel(j, list[i].Top, Color.Red);
                    image2.SetPixel(j, list[i].Bottom, Color.Red);
                }
                for (int j = list[i].Top; j < list[i].Bottom; j++)
                {
                    image2.SetPixel(list[i].Left, j, Color.Red);
                    image2.SetPixel(list[i].Right, j, Color.Red);
                }
            }
            return image2;
        }
    }
}