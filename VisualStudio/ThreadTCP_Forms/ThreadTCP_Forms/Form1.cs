using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Windows.Forms;
using System.Net;
using System.Net.Sockets;
using System.IO;
using System.Threading;

namespace ThreadTCP_Forms
{
    public partial class Form1 : Form
    {
        static Thread threadInput;
        static Thread threadSend;
        static TcpListener tcpListener;
        static NetworkStream nwStream;
        static StreamWriter stWriter;

        public Form1()
        {
            InitializeComponent();
        }

        //Создать сервер
        private void button1_Click(object sender, EventArgs e)
        {
            tcpListener = new TcpListener(IPAddress.Any, Convert.ToInt32(numericUpDown2.Value));
            tcpListener.Start();
            Console.WriteLine("Сервер запущен!");
            TcpClient tcpClient = tcpListener.AcceptTcpClient();
            nwStream = tcpClient.GetStream();
            Console.WriteLine("Клиент подключен!");
            stWriter = new StreamWriter(nwStream, Encoding.UTF8)
            {
                AutoFlush = true
            };
            StreamReader stReader = new StreamReader(nwStream, Encoding.UTF8);
            threadInput = new Thread(() =>
            {
                while (true)
                {
                    try
                    {
                        string input = stReader.ReadLine();
                        MessageBox.Show("Клиент написал: " + input);
                    }
                    catch (System.IO.IOException ex)
                    {
                        MessageBox.Show("Ошибка при получении данных от клиента!", "Ошибка", MessageBoxButtons.OK, MessageBoxIcon.Error);
                        break;
                    }
                }
            });
            threadInput.Start();
        }

        //Подключиться
        private void button2_Click(object sender, EventArgs e)
        {
            Console.WriteLine("Подключение к серверу...");
            try
            {
                int k = 5 / Convert.ToInt32(numericUpDown1.Value);
                TcpClient client = new TcpClient(textBox1.Text, Convert.ToInt32(numericUpDown1.Value));
                nwStream = client.GetStream();
                Console.WriteLine("Подключение удалось!");
                StreamReader stReader = new StreamReader(nwStream);
                stWriter = new StreamWriter(nwStream) { AutoFlush = true };
                new Thread(() =>
                {
                    while (true)
                    {
                        try
                        {
                            string input = stReader.ReadLine();
                            MessageBox.Show("С сервера пришло: " + input);
                        }
                        catch (System.IO.IOException ex)
                        {
                            MessageBox.Show("Ошибка при получении данных от сервера!", "Ошибка", MessageBoxButtons.OK, MessageBoxIcon.Error);
                            break;
                        }
                    }
                }).Start();
            }
            catch (System.Net.Sockets.SocketException ex)
            {
                MessageBox.Show("Не удалось установить соединение, повторите попытку позже!", "Ошибка", MessageBoxButtons.OK, MessageBoxIcon.Error);
            }
        }

        //Отправка сообщения
        private void button3_Click(object sender, EventArgs e)
        {
            if (textBox2.Text.Length > 0)
            {
                stWriter.WriteLine(textBox2.Text);
                textBox2.Text = "";
            }
        }
    }
}
