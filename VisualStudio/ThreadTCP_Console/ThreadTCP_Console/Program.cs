using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Net;
using System.Net.Sockets;
using System.IO;
using System.Threading;

namespace Server
{
    class Program
    {
        static Thread threadInput;
        static Thread threadSend;
        static TcpListener tcpListener;
        static NetworkStream nwStream;

        static void Main(string[] args)
        {
            AppDomain.CurrentDomain.ProcessExit += new EventHandler(ProcessExit);
            tcpListener = new TcpListener(IPAddress.Any, 4000);
            tcpListener.Start();
            Console.WriteLine("Сервер запущен!");
            TcpClient tcpClient = tcpListener.AcceptTcpClient();
            nwStream = tcpClient.GetStream();
            Console.WriteLine("Клиент подключен!");
            StreamWriter stWriter = new StreamWriter(nwStream, Encoding.UTF8) {
                AutoFlush = true
            };
            StreamReader stReader = new StreamReader(nwStream, Encoding.UTF8);
            threadInput = new Thread(() =>
            {
                while (true)
                {
                    string input = stReader.ReadLine();
                    Console.WriteLine("Клиент написал: " + input);
                    stWriter.WriteLine("Сервер получил сообщение равное: " + input.Length);
                }
            });
            threadSend = new Thread(() =>
            {
                while (true)
                {
                    string send = Console.ReadLine();
                    stWriter.WriteLine(send);
                }
            });
            threadInput.Start();
            threadSend.Start();
        }

        static void ProcessExit(object sender, EventArgs e)
        {
            if (threadInput.IsAlive)
                threadSend.Abort();
            if (threadSend.IsAlive)
                threadSend.Abort();
            nwStream.Close();
            tcpListener.Stop();
        }
    }
}
