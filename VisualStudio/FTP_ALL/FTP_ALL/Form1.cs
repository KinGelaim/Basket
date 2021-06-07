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

namespace FTP_ALL
{
    public partial class Form1 : Form
    {
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

        //Загрузка на сервер
        private void button1_Click(object sender, EventArgs e)
        {
            _inputParameter.Username = textBox2.Text;
            _inputParameter.Server = textBox1.Text;
            _inputParameter.Password = textBox3.Text;
            using (OpenFileDialog ofd = new OpenFileDialog()
            {
                Multiselect = false,
                ValidateNames = true,
                Filter = "All files(*.*)|*.*"
            })
            {
                if (ofd.ShowDialog() == DialogResult.OK)
                {
                    FileInfo fi = new FileInfo(ofd.FileName);
                    string fullName = fi.FullName;
                    string fileName = fi.Name;
                    try
                    {
                        if (File.Exists(fullName))
                        {
                            FtpWebRequest request = (FtpWebRequest)WebRequest.Create(new Uri(string.Format("{0}/{1}", textBox1.Text, fileName)));
                            request.Method = WebRequestMethods.Ftp.UploadFile;
                            request.Credentials = new NetworkCredential(textBox2.Text, textBox3.Text);
                            Stream ftpStream = request.GetRequestStream();
                            FileStream fs = File.OpenRead(fullName);
                            byte[] buffer = new byte[1024];
                            double total = (double)fs.Length;
                            int byteRead = 0;
                            do
                            {
                                byteRead = fs.Read(buffer, 0, 1024);
                                ftpStream.Write(buffer, 0, byteRead);
                            } while (byteRead != 0);
                            fs.Close();
                            ftpStream.Close();
                            MessageBox.Show("Файл был успешно загружен на сервер!", "Информация", MessageBoxButtons.OK, MessageBoxIcon.Information);
                        }
                        else
                            MessageBox.Show("Файл был перемещен или удален!", "Ошибка", MessageBoxButtons.OK, MessageBoxIcon.Error);
                    }
                    catch
                    {
                        DialogResult rezult = MessageBox.Show("Невозможно открыть выбранный файл",
                        "Ошибка", MessageBoxButtons.OK, MessageBoxIcon.Error);
                    }

                }
            }
        }

        //Список файлов на сервере
        private void button3_Click(object sender, EventArgs e)
        {
            try
            {
                FtpWebRequest request = (FtpWebRequest)WebRequest.Create(textBox1.Text);
                request.Method = WebRequestMethods.Ftp.ListDirectory;
                request.Credentials = new NetworkCredential(textBox2.Text, textBox3.Text);
                FtpWebResponse response = (FtpWebResponse)request.GetResponse();

                Stream responseStream = response.GetResponseStream();
                StreamReader reader = new StreamReader(responseStream);

                listBox1.Items.Clear();
                try
                {
                    while (reader != null)
                        listBox1.Items.Add(reader.ReadLine());
                }
                catch { }

                reader.Close();
                responseStream.Close();
                response.Close();
            }
            catch (Exception ex)
            {
                MessageBox.Show(ex.Message.ToString(), "Ошибка", MessageBoxButtons.OK, MessageBoxIcon.Error);
            }
        }

        private void listBox1_SelectedIndexChanged(object sender, EventArgs e)
        {
            if(listBox1.SelectedIndex >= 0)
                textBox4.Text = Convert.ToString(listBox1.Items[listBox1.SelectedIndex]);
        }

        //Загрузка с сервера
        private void button2_Click(object sender, EventArgs e)
        {
            try
            {
                using (SaveFileDialog sfd = new SaveFileDialog()
                {
                    FileName = textBox4.Text,
                    OverwritePrompt = true,
                    ValidateNames = true,
                    Filter = "All files(*.*)|*.*"
                })
                {
                    if (sfd.ShowDialog() == DialogResult.OK)
                    {
                        WebClient wc = new WebClient();
                        wc.BaseAddress = textBox1.Text;
                        wc.Credentials = new NetworkCredential(textBox2.Text, textBox3.Text);
                        bool flag = true;
                        do
                        {
                            if (!wc.IsBusy)
                            {
                                wc.DownloadFile(new Uri(textBox1.Text + @"/" + textBox4.Text), sfd.FileName);
                                flag = false;
                            }
                        } while (flag);
                        if (DialogResult.Yes == MessageBox.Show("Файл успешно загружен! Вы желаете открыть файл?", "Информация", MessageBoxButtons.YesNo, MessageBoxIcon.Question))
                        {
                            if (File.Exists(sfd.FileName))
                            {
                                System.Diagnostics.Process.Start(sfd.FileName);
                            }
                        }
                    }
                }
            }
            catch (Exception ex)
            {
                MessageBox.Show(ex.Message.ToString(), "Ошибка", MessageBoxButtons.OK, MessageBoxIcon.Error);
            }
        }

        //Удалить файл
        private void button4_Click(object sender, EventArgs e)
        {
            if (DialogResult.Yes == MessageBox.Show("Вы уверены, что желаете удалить файл с сервера?", "Подтверждение", MessageBoxButtons.YesNo, MessageBoxIcon.Question))
            {
                try
                {
                    FtpWebRequest request = (FtpWebRequest)WebRequest.Create(new Uri(string.Format("{0}/{1}", textBox1.Text, textBox4.Text)));
                    request.Method = WebRequestMethods.Ftp.DeleteFile;
                    request.Credentials = new NetworkCredential(textBox2.Text, textBox3.Text);
                    request.GetResponse();
                    textBox4.Text = "";
                    listBox1.Items.Remove(listBox1.Items[listBox1.SelectedIndex]);
                    MessageBox.Show("Файл удален с сервера!");
                }
                catch (Exception ex)
                {
                    MessageBox.Show(ex.Message.ToString(), ex.Source.ToString(), MessageBoxButtons.OK, MessageBoxIcon.Error);
                }
            }
        }
    }
}
