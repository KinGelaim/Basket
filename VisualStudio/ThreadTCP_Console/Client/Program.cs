using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Net;
using System.Net.Sockets;
using System.IO;
using System.Threading;

namespace Client
{
    class Program
    {
        static Thread threadInput;
        static Thread threadSend;
        static NetworkStream nwStream;

        static void Main(string[] args)
        {
            Console.WriteLine("Введите ip: ");
            string ip = Console.ReadLine();
            Console.WriteLine("Подключение к серверу...");
            TcpClient client = new TcpClient(ip, 4000);
            nwStream = client.GetStream();
            Console.WriteLine("Подключение удалось!");
            StreamReader stReader = new StreamReader(nwStream);
            StreamWriter stWriter = new StreamWriter(nwStream) { AutoFlush = true };
            new Thread(() => {
                while (true)
                {
                    Console.WriteLine("Введите сообщение: ");
                    string send = Console.ReadLine();
                    stWriter.WriteLine(send);
                    Console.WriteLine("Сообщение отправлено!");
                }
            }).Start();
            new Thread(() =>
            {
                while (true)
                {
                    string input = stReader.ReadLine();
                    Console.ForegroundColor = ConsoleColor.Green;
                    Console.WriteLine("С сервера пришло: " + input);
                    Console.ForegroundColor = ConsoleColor.Gray;
                }
            }).Start();
        }

        static void CurrentDomain_ProcessExit(object sender, EventArgs e)
        {
            threadInput.Start();
            threadSend.Start();
            if (threadInput.IsAlive)
                threadSend.Abort();
            if (threadSend.IsAlive)
                threadSend.Abort();
            nwStream.Close();
        }
    }
}
