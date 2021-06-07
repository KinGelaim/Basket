using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

using System.Net;
using System.Net.Sockets;
using System.Net.NetworkInformation;

namespace CheckConnection
{
    class Program
    {
        static void Main(string[] args)
        {
            Console.Title = "Проверка компьютера в сети";
            //
            Console.Write("Введите IP адресс или доменное имя: ");
            //Имя хоста
            string checkName = Console.ReadLine();
            Console.WriteLine("Пингуем " + checkName + " " + CheckPing(checkName));

            //Получение имени компа в сети
            Console.WriteLine("Имя компьютера в сети:\n" + GetHostName(checkName));

            //Получаем списки IP
            List<string> listIP = GetIPv4(checkName);
            Console.WriteLine("Остальные адреса: ");
            foreach (string str in listIP)
            {
                Console.WriteLine(str);
            }

            Console.ReadKey();
        }

        //Проверяем наличие компа в сети
        static bool CheckPing(string hostName)
        {
            try
            {
                Ping ping = new Ping();
                PingReply status = ping.Send(hostName, 1000);
                if(status.Status == IPStatus.Success)
                    return true;
            }
            catch
            {

            }
            return false;
        }

        //Получаем список IP адресов, если их несколько
        static List<string> GetIPv4(string hostName)
        {
            List<string> strListIP = new List<string>();
            IPAddress[] listIP = { new IPAddress(new byte[] { 0, 0, 0, 0 }) };
            try
            {
                listIP = Dns.GetHostAddresses(hostName);
                foreach (IPAddress IP in listIP)
                {
                    if (IP.AddressFamily == AddressFamily.InterNetwork)
                    {
                        strListIP.Add(IP.ToString());
                    }
                }
            }
            catch
            {

            }
            return strListIP;
        }

        //Получаем имя хоста
        static string GetHostName(string hostName)
        {
            try
            {
                IPHostEntry hostEntry = Dns.GetHostEntry(hostName);
                return hostEntry.HostName.ToString();
            }
            catch
            {

            }
            return "";
        }
    }
}
