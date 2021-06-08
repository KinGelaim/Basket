using System;
using System.Collections.Generic;
using System.Linq;
using System.Net;
using System.Net.Sockets;
using System.Text;

namespace ServerUDP
{
    class Program
    {
        static void Main(string[] args)
        {
            const string ip = "127.0.0.1";
            const int port = 8081;

            IPEndPoint udpEndPoint = new IPEndPoint(IPAddress.Parse(ip), port);

            Socket udpSocket = new Socket(AddressFamily.InterNetwork, SocketType.Dgram, ProtocolType.Udp);

            udpSocket.Bind(udpEndPoint);

            while (true)
            {
                byte[] buffer = new byte[256];
                int size = 0;
                StringBuilder data = new StringBuilder();

                EndPoint clientEndPoint = new IPEndPoint(IPAddress.Any, 0);
                do
                {
                    size = udpSocket.ReceiveFrom(buffer, ref clientEndPoint);
                    data.Append(Encoding.UTF8.GetString(buffer));
                }
                while (udpSocket.Available > 0);

                //udpSocket.SendTo(Encoding.UTF8.GetBytes("Сообщение пришло!"), clientEndPoint);

                Console.WriteLine(data);

                //TODO: выход из цикла
            }

            udpSocket.Shutdown(SocketShutdown.Both);
            udpSocket.Close();

            Console.ReadKey();
        }
    }
}
