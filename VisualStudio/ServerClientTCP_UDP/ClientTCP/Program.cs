using System;
using System.Collections.Generic;
using System.Linq;
using System.Net;
using System.Net.Sockets;
using System.Text;

namespace ClientTCP
{
    class Program
    {
        static void Main(string[] args)
        {
            const string ip = "127.0.0.1";
            const int port = 8080;

            IPEndPoint tcpEndPoint = new IPEndPoint(IPAddress.Parse(ip), port);

            Socket tcpSocket = new Socket(AddressFamily.InterNetwork, SocketType.Stream, ProtocolType.Tcp);

            Console.Write("Введите сообщение: ");
            string message = Console.ReadLine();

            byte[] data = Encoding.UTF8.GetBytes(message);
            tcpSocket.Connect(tcpEndPoint);
            tcpSocket.Send(data);

            byte[] buffer = new byte[256];
            int size = 0;
            StringBuilder answer = new StringBuilder();

            do
            {
                size = tcpSocket.Receive(buffer);
                answer.Append(Encoding.UTF8.GetString(buffer, 0, size));

            }
            while (tcpSocket.Available > 0);

            Console.WriteLine(answer);

            tcpSocket.Shutdown(SocketShutdown.Both);
            tcpSocket.Close();

            Console.ReadKey();
        }
    }
}
