using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

using System.Net;
using System.Net.Sockets;

using System.IO;
using System.Reflection;
using System.Diagnostics;

namespace ClientParentControll
{
    class Program
    {
        //Текущее состояние
        static string stateMyComputer = "access";

        //Сокет для соединения
        static Socket socket = new Socket(AddressFamily.InterNetwork, SocketType.Stream, ProtocolType.Tcp);

        static void Main(string[] args)
        {
            //Отображать ли главное окно?
            bool isVisible = false;
            foreach (string str in args)
            {
                if(str == "-v")
                {
                    isVisible = true;
                }
            }

            //Вводная информация
            Console.WriteLine("---------Запускаем клиентскую часть родительского контроля---------");

            //При включении разблокируем доступ в интернет
            UnblockTheInternet();

            Console.ReadKey();

            Console.WriteLine("Блочим интернет! Оставляем лан!");
            BlockTheInternet();

            return;

            while (true)
            {
                socket = new Socket(AddressFamily.InterNetwork, SocketType.Stream, ProtocolType.Tcp);

                //Указываем, кто может подключаться
                socket.Bind(new IPEndPoint(IPAddress.Any, 904));

                //Включаем слушатель
                socket.Listen(1);
                Console.WriteLine("Слушаем подключение");

                //Принимаем пользователя
                Socket parentSocket = socket.Accept();
                Console.WriteLine("Сервер подключился");

                //Принимаем данные
                byte[] buffer = new byte[1024];
                parentSocket.Receive(buffer);
                Console.WriteLine("Данные приняты");

                //Обрабатываем
                string command = Encoding.ASCII.GetString(buffer);
                command = command.Trim().Remove('\n');
                Console.WriteLine("Обрабатываем команду: " + command);
                string[] arrCommands = command.Split(' ');
                if (arrCommands.Length > 1)
                {
                    if (arrCommands[0] == "com")
                    {
                        if (arrCommands[1] == "0")
                        {
                            //Команда для проверки текущего состояние (ничего не делаем)
                        }
                        else if (arrCommands[1] == "1")
                        {
                            //Команда для блокировки компьютера
                            BlockTheInternet();
                        }
                        else if (arrCommands[1] == "2")
                        {
                            //Команда для разблокировки компьютера
                            UnblockTheInternet();
                        }
                    }
                }

                //Отправляем ответ
                string message = "otv " + stateMyComputer + " end";
                Console.WriteLine("Отправляем ответ: " + message);
                byte[] buffer2 = new byte[1024];
                buffer2 = Encoding.ASCII.GetBytes(message);
                parentSocket.Send(buffer2);
                Console.WriteLine("Ответ отправлен");

                //Отключаемся
                parentSocket.Close();
                socket.Close();
                Console.WriteLine("Отключились");

                //Повторно включаем слушатель
            }
        }

        //TODO: непосредственная блокировка и разблокировка интернета

        //Функция для блокировки
        static bool BlockTheInternet()
        {
            Console.WriteLine("Блокируем интернет");
            //try
            {
                System.ServiceProcess.ServiceController scPAServ = new System.ServiceProcess.ServiceController("PolicyAgent"); //IPSec
                
                if (scPAServ.Status != System.ServiceProcess.ServiceControllerStatus.Running)
                {
                    Console.WriteLine("Стартуем");
                    scPAServ.Start(); //Start If Not Running
                }

                string[] strCommands =  { @"-w REG -p ""Firewall"" -r ""Block All"" -f *:*:*+*:*:* -n BLOCK -x" /*,
                                @"-w REG -p ""Firewall"" -r ""Allow LAN"" -f 0:*:*+192.168.10.*:*:* -n PASS -x" ,
                                @"-w REG -p ""Firewall"" -r ""DNS"" -f 0:*:UDP+223.211.190.23:53:UDP 0:*:UDP+223.211.190.24:53:UDP 0:*:TCP+223.211.190.23:53:TCP 0:*:TCP+223.211.190.24:53:TCP -n PASS -x" ,
                                @"-w REG -p ""Firewall"" -r ""POP3"" -f 0:*:TCP+*:110:TCP -n PASS -x" ,
                                @"-w REG -p ""Firewall"" -r ""POP3S"" -f 0:*:TCP+*:995:TCP -n PASS -x" ,
                                @"-w REG -p ""Firewall"" -r ""FTP Control"" -f 0:*:TCP+*:21:TCP -n PASS -x" ,
                                @"-w REG -p ""Firewall"" -r ""FTP Data"" -f 0:*:TCP+*:20:TCP -n PASS -x" ,
                                @"-w REG -p ""Firewall"" -r ""IMAP"" -f 0:*:TCP+*:143:TCP -n PASS -x" ,
                                @"-w REG -p ""Firewall"" -r ""HTTP"" -f 0:*:TCP+*:80:TCP -n PASS -x" ,
                                @"-w REG -p ""Firewall"" -r ""HTTPS"" -f 0:*:TCP+*:443:TCP -n BLOCK -x" ,
                                @"-w REG -p ""Firewall"" -r ""PROXY"" -f 0:*:TCP+*:8080:TCP 0:*:TCP+*:3128:TCP 0:*:TCP+*:8081:*:TCP 0:*:TCP+*:8000:TCP -n BLOCK -x"*/};

                for (int i = 0; i < strCommands.Length; i++) //Loop through each Command String
                {
                    ProcessStartInfo psiStart = new ProcessStartInfo(); //Process To Start

                    //psiStart.CreateNoWindow = true; //Invisible
                    psiStart.CreateNoWindow = false;

                    psiStart.FileName = Directory.GetParent(Assembly.GetExecutingAssembly().Location) + "\\ipseccmd.exe"; //IPSEC
                    Console.WriteLine(Directory.GetParent(Assembly.GetExecutingAssembly().Location) + "\\ipseccmd.exe");

                    psiStart.Arguments = strCommands[i]; //Break Command Strings Apart

                    psiStart.WindowStyle = ProcessWindowStyle.Normal; //Visible

                    Process p = System.Diagnostics.Process.Start(psiStart); //Start Process To Block Internet Connection
                }

                stateMyComputer = "block";
                return true;
            }
            //catch
            {
                Console.WriteLine("Какая-то ерор");
            }
            return false;
        }

        //Функция для разблокировки
        static bool UnblockTheInternet()
        {
            Console.WriteLine("Разблокируем интернет");
            try
            {

                stateMyComputer = "access";
                return true;
            }
            catch
            {

            }
            return false;
        }
    }
}
