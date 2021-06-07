using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using System.Windows.Forms;
using System.IO;
using System.Net;

namespace FTP
{
    public partial class Form1 : Form
    {
        Bitmap image;

        public Form1()
        {
            InitializeComponent();
        }

        struct FtpSetting
        {
            public string Server { get; set; }
            public string Username { get; set; }
            public string Password { get; set; }
            public string FileName { get; set; }
            public string FullName { get; set; }
        }

        FtpSetting _inputParameter;

        private void backgroundWorker1_DoWork(object sender, DoWorkEventArgs e)
        {
            string fileName = _inputParameter.FileName;
            string fullName = _inputParameter.FullName;
            string userName = _inputParameter.Username;
            string password = _inputParameter.Password;
            string server = _inputParameter.Server;
            if (File.Exists(fullName))
            {
                FtpWebRequest request = (FtpWebRequest)WebRequest.Create(new Uri(string.Format("{0}/{1}", server, fileName)));
                request.Method = WebRequestMethods.Ftp.UploadFile;
                request.Credentials = new NetworkCredential(userName, password);
                Stream ftpStream = request.GetRequestStream();
                FileStream fs = File.OpenRead(fullName);
                byte[] buffer = new byte[1024];
                double total = (double)fs.Length;
                int byteRead = 0;
                double read = 0;
                do
                {
                    if (!backgroundWorker1.CancellationPending)
                    {
                        byteRead = fs.Read(buffer, 0, 1024);
                        ftpStream.Write(buffer, 0, byteRead);
                        read += (double)byteRead;
                        double percentage = read / total * 100;
                        backgroundWorker1.ReportProgress((int)percentage);
                    }
                } while (byteRead != 0);
                fs.Close();
                ftpStream.Close();
            }else
                MessageBox.Show("Файл был перемещен или удален!", "Ошибка", MessageBoxButtons.OK, MessageBoxIcon.Error);
        }

        private void backgroundWorker1_ProgressChanged(object sender, ProgressChangedEventArgs e)
        {
            label4.Text = "Загружено " + e.ProgressPercentage + " %";
            progressBar1.Value = e.ProgressPercentage;
            progressBar1.Update();
        }

        private void backgroundWorker1_RunWorkerCompleted(object sender, RunWorkerCompletedEventArgs e)
        {
            label4.Text = "Загрузка завершена!";
        }

        private void button1_Click(object sender, EventArgs e)
        {
            if (pictureBox1.Image != null)
            {
                _inputParameter.Username = textBox2.Text;
                _inputParameter.Server = textBox1.Text;
                _inputParameter.Password = textBox3.Text;
                backgroundWorker1.RunWorkerAsync();
            }
            else
                MessageBox.Show("Сначала загрузите изображение!", "Ошибка", MessageBoxButtons.OK, MessageBoxIcon.Error);
        }

        private void pictureBox1_DoubleClick(object sender, EventArgs e)
        {
            using (OpenFileDialog ofd = new OpenFileDialog()
            {
                Multiselect = false,
                ValidateNames = true,
                Filter = "Image Files(*.BMP;*.JPG;*.GIF;*.PNG)|*.BMP;*JPG;*GIF;*.PNG|All files(*.*)|*.*"
            })
            {
                if (ofd.ShowDialog() == DialogResult.OK)
                {
                    try
                    {
                        FileStream fs = File.OpenRead(ofd.FileName);
                        image = new Bitmap(fs);
                        fs.Close();
                        pictureBox1.Image = image;
                        pictureBox1.Invalidate();
                        FileInfo fi = new FileInfo(ofd.FileName);
                        _inputParameter.FileName = fi.Name;
                        _inputParameter.FullName = fi.FullName;
                    }
                    catch
                    {
                        DialogResult rezult = MessageBox.Show("Невозможно открыть выбранный файл",
                        "Ошибка", MessageBoxButtons.OK, MessageBoxIcon.Error);
                    }

                }
            }
        }
    }
}
