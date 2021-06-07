using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Windows.Forms;
using System.IO;
using System.Net;

namespace WindowsFormsApplication1
{
    public partial class Form1 : Form
    {
        Bitmap image;
        string server = "";
        string userName = "";
        string password = "";
        string fileName = "";
        MemoryStream ms = new MemoryStream();

        public Form1()
        {
            InitializeComponent();
        }

        private void button1_Click(object sender, EventArgs e)
        {
            server = textBox1.Text;
            userName = textBox2.Text;
            password = textBox3.Text;
            fileName = textBox4.Text;
            if (server.Length > 0 && fileName.Length > 0)
            {
                backgroundWorker1.RunWorkerAsync();
            }
        }

        private void backgroundWorker1_DoWork(object sender, DoWorkEventArgs e)
        {
            try
            {
                FtpWebRequest request = FtpWebRequest.Create(new Uri(server + @"/" + fileName)) as FtpWebRequest;
                request.UseBinary = true;
                request.KeepAlive = false;
                request.Method = WebRequestMethods.Ftp.DownloadFile;
                request.Credentials = new NetworkCredential(userName, password);

                FtpWebResponse response = request.GetResponse() as FtpWebResponse;

                Stream responseStream = response.GetResponseStream();


                int bufferSize = 1024;
                byte[] buffer = new byte[bufferSize];
                //double total = (double)responseStream.Position;
                int byteRead = 0;
                double read = 0;

                do
                {

                    //if (!backgroundWorker1.CancellationPending)
                    {
                        byteRead = responseStream.Read(buffer, 0, 1024);
                        ms.Write(buffer, 0, byteRead);
                        read += (double)byteRead;
                        //double percentage = read / total * 100;
                        //backgroundWorker1.ReportProgress((int)percentage);
                    }
                } while (byteRead != 0);
                responseStream.Close();
                response.Close();
                MessageBox.Show("chto-to est");
                image = new Bitmap(ms);
                pictureBox1.Image = image;
                pictureBox1.Invalidate();
            }
            catch (Exception ex)
            {
                throw new Exception("Error download");
            }
        }

        private void backgroundWorker1_ProgressChanged(object sender, ProgressChangedEventArgs e)
        {
            label4.Text = "Скачено " + e.ProgressPercentage + " %";
            progressBar1.Value = e.ProgressPercentage;
            progressBar1.Update();
        }

        private void backgroundWorker1_RunWorkerCompleted(object sender, RunWorkerCompletedEventArgs e)
        {
            label4.Text = "Закачка завершена!";
        }

        private void button2_Click(object sender, EventArgs e)
        {
            WebClient wc = new WebClient();
            wc.BaseAddress = server;
            wc.Credentials = new NetworkCredential(userName, password);
            bool flag = true;
            do
            {
                if (!wc.IsBusy)
                {
                    wc.DownloadFile(new Uri(server + @"/" + fileName), @"C:/download.bmp");
                    flag = false;
                }
            } while (flag);
            MessageBox.Show("Oki");
        }
    }
}
