using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Net.Sockets;
using System.Threading;
using System.IO;

namespace Server
{
    class Client
    {
        public int id { get; set; }
        private Thread threadInput;
        private NetworkStream nwStream;
        private StreamWriter stWriter;
        private StreamReader stReader;

        public Client(TcpClient tcpClient, int id)
        {
            nwStream = tcpClient.GetStream();
            Console.WriteLine("Клиент " + id + " подключен!");
            this.id = id;
            stWriter = new StreamWriter(nwStream, Encoding.UTF8)
            {
                AutoFlush = true
            };
            stReader = new StreamReader(nwStream, Encoding.UTF8);
            threadInput = new Thread(() =>
            {
                while (true)
                {
                    try
                    {
                        string input = stReader.ReadLine();
                        Console.WriteLine("Пользователь " + id + " написал: " + input);
                        Program.sendMessageClient(id, input);
                    }
                    catch (System.IO.IOException ex)
                    {
                        Console.WriteLine("Ошибка при получении данных от клиента!");
                        break;
                    }
                }
            });
            threadInput.Start();
        }

        public void SendMessage(string msg)
        {
            stWriter.WriteLine(msg);
        }
    }
}
