using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Windows.Forms;
using System.IO;
using System.Threading;

namespace OnlineGame
{
    public partial class FormLoading : Form
    {
        Thread threadRead;
        Thread threadLoading;
        public string status = "";

        public FormLoading(StreamReader stReader)
        {
            InitializeComponent();

            threadRead = new Thread(() =>
            {
                while (status.Length == 0)
                {
                    try
                    {
                        status = stReader.ReadLine();
                        if(status.Split('~').Length >= 2)
                            if(status.Split('~')[1] == "200")
                                fullExitLoading("ok");
                    }
                    catch (System.IO.IOException ex)
                    {
                        MessageBox.Show("Ошибка при получении данных от сервера!", "Ошибка", MessageBoxButtons.OK, MessageBoxIcon.Error);
                        fullExitLoading("error");
                        break;
                    }
                }
            });

            threadLoading = new Thread(() =>
            {
                while (status.Length == 0)
                {
                    try
                    {
                        Thread.Sleep(1000);
                        label1.BeginInvoke((MethodInvoker)(() => this.label1.Text = "Ждем второго игрока."));
                        Thread.Sleep(1000);
                        label1.BeginInvoke((MethodInvoker)(() => this.label1.Text = "Ждем второго игрока.."));
                        Thread.Sleep(1000);
                        label1.BeginInvoke((MethodInvoker)(() => this.label1.Text = "Ждем второго игрока..."));
                        Thread.Sleep(1000);
                        label1.BeginInvoke((MethodInvoker)(() => this.label1.Text = "Ждем второго игрока...."));
                        Thread.Sleep(1000);
                        label1.BeginInvoke((MethodInvoker)(() => this.label1.Text = "Ждем второго игрока....."));
                        Thread.Sleep(1000);
                    }
                    catch (Exception e)
                    {
                        MessageBox.Show("Ошибка отображения загрузки!");
                    }
                }
            });

            threadRead.Start();
            threadLoading.Start();
        }

        private void button1_Click(object sender, EventArgs e)
        {
            fullExitLoading("abort");
        }

        private void fullExitLoading(string st)
        {
            try
            {
                status = st;
                threadLoading.Join();
                this.BeginInvoke((MethodInvoker)(() => this.Close()));
            }
            catch (Exception e)
            {
                MessageBox.Show("Ошибка при закрытие!");
            }
        }
    }
}
