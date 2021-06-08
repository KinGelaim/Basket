using System;
using System.Collections.Generic;
using System.Linq;
using System.Net;
using System.Net.Sockets;
using System.Text;

namespace ClientUDP
{
    class Program
    {
        static void Main(string[] args)
        {
            const string ip = "127.0.0.1";
            const int port = 8082;

            IPEndPoint udpEndPoint = new IPEndPoint(IPAddress.Parse(ip), port);

            Socket udpSocket = new Socket(AddressFamily.InterNetwork, SocketType.Dgram, ProtocolType.Udp);
            udpSocket.Bind(udpEndPoint);

            while (true)
            {
                Console.Write("Введите сообщение:");
                string message = Console.ReadLine();

                IPEndPoint serverEndPoint = new IPEndPoint(IPAddress.Parse(ip), 8081);
                udpSocket.SendTo(Encoding.UTF8.GetBytes(message), serverEndPoint);
            }
        }
    }
}
