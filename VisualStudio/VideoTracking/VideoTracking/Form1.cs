using System;
using System.Drawing;
using System.Threading.Tasks;
using System.Windows.Forms;
using Accord.Video.FFMPEG;
using ImageComparison;


namespace VideoTracking
{
    public partial class Form1 : Form
    {
        private Bitmap oldImage = null;
        private Bitmap newImage = null;
        private Bitmap trackingImage = null;

        private ListPointOfDifference list;

        public Form1()
        {
            InitializeComponent();
        }

        private void button1_Click(object sender, EventArgs e)
        {
            OpenFileDialog ofd = new OpenFileDialog();
            if (ofd.ShowDialog() == DialogResult.OK)
            {
                var outer = Task.Factory.StartNew(() =>
                {
                    VideoFileReader reader = new VideoFileReader();
                    reader.Open(ofd.FileName);
                    Task.Delay(1000);
                    for (int i = 0; i < reader.FrameCount; i++)
                    {
                        Bitmap videoFrame = reader.ReadVideoFrame();
                        if (videoFrame != null)
                        {
                            newImage = (Bitmap)videoFrame.Clone();
                            
                            if (i % trackBar1.Value == 0)
                            {
                                if (oldImage != null)
                                {
                                    // Расчёт разницы изображений
                                    byte[][] difference = newImage.BrainCompareTo(oldImage, trackBar2.Value);

                                    // Построение прямоугольников
                                    list = new ListPointOfDifference(difference);
                                    for (int j = 0; j < difference.GetLength(0); j++)
                                    {
                                        //label1.Invoke(new MethodInvoker(() => label1.Text = j.ToString()));
                                        for (int k = 0; k < difference[j].GetLength(0); k++)
                                        {
                                            //label2.Invoke(new MethodInvoker(() => label2.Text = k.ToString()));
                                            if (difference[j][k] == 1)
                                            {
                                                list.NewPoint(j, k);
                                            }
                                        }
                                    }
                                }

                                oldImage = (Bitmap)newImage.Clone();
                            }

                            // Отображение разницы
                            if (list != null)
                                trackingImage = newImage.SeeDifference(list);

                            // Отрисовка изображений на форме
                            if (trackingImage != null)
                                pictureBox1.Invoke(new MethodInvoker(() => pictureBox1.Image = trackingImage));
                            else
                                pictureBox1.Invoke(new MethodInvoker(() => pictureBox1.Image = newImage));
                            pictureBox1.Invoke(new MethodInvoker(() => pictureBox1.Refresh()));

                            videoFrame.Dispose();
                        }
                        else
                            continue;

                        Task.Delay(1000);
                    }
                    reader.Close();
                });
                
            }
        }
    }
}