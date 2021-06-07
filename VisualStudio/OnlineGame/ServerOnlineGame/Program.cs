using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading;
using System.IO;
using System.Net;
using System.Net.Sockets;
using Newtonsoft.Json;

namespace ServerOnlineGame
{
    class Program
    {
        static Thread threadSendInput1;
        static Thread threadSendInput2;
        static TcpListener tcpListener;
        static NetworkStream nwStream1;
        static NetworkStream nwStream2;
        static StreamWriter stWriter1;
        static StreamWriter stWriter2;
        static Thread threadNewMob;
        static Thread threadMobs;

        static void Main(string[] args)
        {
            AppDomain.CurrentDomain.ProcessExit += new EventHandler(ProcessExit);
            Console.ForegroundColor = ConsoleColor.Red;
            Console.WriteLine("Добро пожаловать на сервер САМОЛЕТИКОВ!");
            Console.ResetColor();
            Console.Write("Введите порт для настройки сервера: ");
            int port = Convert.ToInt32(Console.ReadLine());
            tcpListener = new TcpListener(IPAddress.Any, port);
            tcpListener.Start();
            Console.WriteLine("Сервер запущен! Порт: " + port);
            //Клиент 1
            Console.WriteLine("Ожидаем подключения клиента 1...");
            TcpClient tcpClient1 = tcpListener.AcceptTcpClient();
            nwStream1 = tcpClient1.GetStream();
            Console.WriteLine("Клиент 1 подключен!");
            stWriter1 = new StreamWriter(nwStream1, Encoding.UTF8)
            {
                AutoFlush = true
            };
            StreamReader stReader1 = new StreamReader(nwStream1, Encoding.UTF8);
            //Клиент 2
            Console.WriteLine("Ожидаем подключения клиента 2...");
            TcpClient tcpClient2 = tcpListener.AcceptTcpClient();
            nwStream2 = tcpClient2.GetStream();
            Console.WriteLine("Клиент 2 подключен!");
            stWriter2 = new StreamWriter(nwStream2, Encoding.UTF8)
            {
                AutoFlush = true
            };
            StreamReader stReader2 = new StreamReader(nwStream2, Encoding.UTF8);
            threadSendInput2 = new Thread(() =>
            {
                while (true)
                {
                    string[] input = stReader2.ReadLine().Split('~');
                    if (input.Length >= 2)
                    {
                        if (input[0] == "Client")
                        {
                            Console.WriteLine("Клиент 2 написал: " + input[1]);
                            stWriter1.WriteLine("Client~" + input[1]);
                            if (FlyingMob.allMob.Count > 0)
                            {
                                FlyingHero clientHero = JsonConvert.DeserializeObject<FlyingHero>(input[1]);
                                foreach (FlyingMob fl in FlyingMob.allMob.ToArray())
                                {
                                    foreach (Bullet bl in clientHero.bulletsList.ToArray())
                                        if (bl.posX + 5 > fl.posX - 10 && bl.posY + 5 > fl.posY - 10 && bl.posX + 5 < fl.posX + 10 && bl.posY + 5 < fl.posY + 10
                                            || bl.posX < fl.posX + 10 && bl.posY + 5 > fl.posY - 10 && bl.posX > fl.posX - 10 && bl.posY + 5 < fl.posY + 10
                                            || bl.posX > fl.posX - 10 && bl.posX < fl.posX + 10 && bl.posY > fl.posY - 10 && bl.posY < fl.posY + 10
                                            || bl.posX + 5 > fl.posX - 10 && bl.posX + 5 < fl.posX + 10 && bl.posY > fl.posY - 10 && bl.posY < fl.posY + 10)
                                        {
                                            fl.life--;
                                            if (fl.life <= 0)
                                            {
                                                FlyingMob.allMob.Remove(fl);
                                                clientHero.score += 10;
                                                stWriter2.WriteLine("Answer~" + JsonConvert.SerializeObject(clientHero.score));
                                            }
                                            clientHero.bulletsList.Remove(bl);
                                            stWriter2.WriteLine("AnswerB~" + JsonConvert.SerializeObject(clientHero.bulletsList));
                                        }
                                }
                            }
                        }
                        else if (input[0] == "AnswerMob")
                        {
                            FlyingMob.allMob = JsonConvert.DeserializeObject<List<FlyingMob>>(input[1]);
                        }
                    }
                }
            });
            threadSendInput1 = new Thread(() =>
            {
                while (true)
                {
                    string[] input = stReader1.ReadLine().Split('~');
                    if (input.Length >= 2)
                    {
                        if (input[0] == "Client")
                        {
                            Console.WriteLine("Клиент 1 написал: " + input[1]);
                            stWriter2.WriteLine("Client~" + input[1]);
                            if (FlyingMob.allMob.Count > 0)
                            {
                                FlyingHero clientHero = JsonConvert.DeserializeObject<FlyingHero>(input[1]);
                                foreach (FlyingMob fl in FlyingMob.allMob.ToArray())
                                {
                                    foreach (Bullet bl in clientHero.bulletsList.ToArray())
                                        if (bl.posX + 5 > fl.posX - 10 && bl.posY + 5 > fl.posY - 10 && bl.posX + 5 < fl.posX + 10 && bl.posY + 5 < fl.posY + 10
                                            || bl.posX < fl.posX + 10 && bl.posY + 5 > fl.posY - 10 && bl.posX > fl.posX - 10 && bl.posY + 5 < fl.posY + 10
                                            || bl.posX > fl.posX - 10 && bl.posX < fl.posX + 10 && bl.posY > fl.posY - 10 && bl.posY < fl.posY + 10
                                            || bl.posX + 5 > fl.posX - 10 && bl.posX + 5 < fl.posX + 10 && bl.posY > fl.posY - 10 && bl.posY < fl.posY + 10)
                                        {
                                            fl.life--;
                                            if (fl.life <= 0)
                                            {
                                                FlyingMob.allMob.Remove(fl);
                                                clientHero.score += 10;
                                                stWriter1.WriteLine("Answer~" + JsonConvert.SerializeObject(clientHero.score));
                                            }
                                            clientHero.bulletsList.Remove(bl);
                                            stWriter1.WriteLine("AnswerB~" + JsonConvert.SerializeObject(clientHero.bulletsList));
                                        }
                                }
                            }
                        }
                        else if (input[0] == "AnswerMob")
                        {
                            FlyingMob.allMob = JsonConvert.DeserializeObject<List<FlyingMob>>(input[1]);
                        }
                    }
                }
            });
            threadSendInput1.Start();
            threadSendInput2.Start();
            stWriter1.WriteLine("Status~200");
            stWriter2.WriteLine("Status~200");
            Console.WriteLine("Отправлены статусы 200");

            threadNewMob = new Thread(() =>
            {
                Random rand = new Random();
                while (true)
                {
                    if (FlyingMob.allMob.Count < 8)
                    {
                        if(rand.Next(100) > 50)
                        {
                            Console.WriteLine("Создание моба!");
                            FlyingMob newFlyingMob = new FlyingMob(rand.Next(1,8), 800, rand.Next(100, 300));
                            FlyingMob.allMob.Add(newFlyingMob);
                            Console.WriteLine("Моб создан: " + JsonConvert.SerializeObject(FlyingMob.allMob));
                        }
                    }
                    Thread.Sleep(rand.Next(1000, 7000));
                }
            });
            threadNewMob.Start();

            threadMobs = new Thread(() =>
            {
                Random rand = new Random();
                while (true)
                {
                    if (FlyingMob.allMob.Count > 0)
                    {
                        foreach(FlyingMob fl in FlyingMob.allMob)
                        {
                            if (fl.posX > 500)
                                if (rand.Next(100) > 30)
                                    fl.posX -= 5;
                            if (rand.Next(100) > 50)
                                fl.posY += 5;
                            else
                                fl.posY -= 5;
                            if(fl.bulletsList.Count < 5)
                                if (rand.Next(100) > 90)
                                {
                                    Bullet bullet = new Bullet(fl.posX, fl.posY);
                                    fl.bulletsList.Add(bullet);
                                }
                            foreach (Bullet bullet in fl.bulletsList)
                                bullet.posX -= 12;
                        }
                    }
                    string sending = JsonConvert.SerializeObject(FlyingMob.allMob);
                    stWriter1.WriteLine("Server~" + sending);
                    stWriter2.WriteLine("Server~" + sending);
                    Console.WriteLine("Отправили мобов!");
                    Thread.Sleep(500);
                }
            });
            threadMobs.Start();
        }

        static void ProcessExit(object sender, EventArgs e)
        {
            try
            {
                if (threadSendInput1.IsAlive)
                    threadSendInput1.Abort();
                if (threadSendInput2.IsAlive)
                    threadSendInput2.Abort();
            }
            catch (Exception ex)
            {
                Console.WriteLine("Ошибка при отключении потоков");
            }
            nwStream1.Close();
            nwStream2.Close();
            tcpListener.Stop();
        }
    }
}
