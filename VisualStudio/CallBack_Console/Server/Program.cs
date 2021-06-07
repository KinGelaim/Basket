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
        static TcpListener tcpListener;
        static List<Client> clients;
        const int countClients = 3;

        static void Main(string[] args)
        {
            clients = new List<Client>();
            Console.Write("Введите порт для прослушивания: ");
            int port = Convert.ToInt32(Console.ReadLine());
            tcpListener = new TcpListener(IPAddress.Any, port);
            tcpListener.Start(countClients);
            Console.WriteLine("Сервер запущен!");
            listenerNewClient();
        }

        static public void listenerNewClient()
        {
            new Thread(() =>
            {
                while (clients.Count < countClients)
                {
                    Console.WriteLine("Ожидается подключение нового клиента!");
                    TcpClient tcpClient = tcpListener.AcceptTcpClient();
                    Client newClient = new Client(tcpClient, clients.Count + 1);
                    clients.Add(newClient);
                }
            }).Start();
        }

        static public void sendMessageClient(int id, string msg)
        {
            foreach (Client client in clients)
                if(client.id != id)
                    client.SendMessage("Пользователь " + id + " написал " + msg);
        }
    }
}
