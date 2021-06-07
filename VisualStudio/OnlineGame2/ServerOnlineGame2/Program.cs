using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.IO;
using System.Net;
using System.Net.Sockets;
using System.Threading;
using Newtonsoft.Json;

namespace ServerOnlineGame2
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

        static Thread threadLife;
        static bool isFirstW = false;
        static bool isFirstS = false;
        static bool isFirstA = false;
        static bool isFirstD = false;

        static bool isSecondW = false;
        static bool isSecondS = false;
        static bool isSecondA = false;
        static bool isSecondD = false;

        static void Main(string[] args)
        {
            AppDomain.CurrentDomain.ProcessExit += new EventHandler(ProcessExit);
            Console.ForegroundColor = ConsoleColor.Red;
            Console.WriteLine("Добро пожаловать на сервер ЛЮТЫХ СРАЖЕНИЙ ПРОТИВ ЗОМБАКОВ!");
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
            ClassLibraryOnlineGame2.Hero firstHero = new ClassLibraryOnlineGame2.Hero(100,300);
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
            ClassLibraryOnlineGame2.Hero secondHero = new ClassLibraryOnlineGame2.Hero(700, 300);
            threadSendInput2 = new Thread(() =>
            {
                try
                {
                    while (true)
                    {
                        string[] input = stReader2.ReadLine().Split('~');
                        if (input.Length >= 2)
                        {
                            if (input[0] == "KeyUp")
                            {
                                switch (input[1])
                                {
                                    case "87":
                                        isSecondW = false;
                                        break;
                                    case "65":
                                        isSecondA = false;
                                        break;
                                    case "83":
                                        isSecondS = false;
                                        break;
                                    case "68":
                                        isSecondD = false;
                                        break;
                                    case "32":
                                        char kPr = 'L';
                                        if (isSecondW && isSecondA)
                                            kPr = 'Q';
                                        else if (isSecondA && isSecondS)
                                            kPr = 'Z';
                                        else if (isSecondS && isSecondD)
                                            kPr = 'C';
                                        else if (isSecondD && isSecondW)
                                            kPr = 'E';
                                        else if (isSecondW)
                                            kPr = 'U';
                                        else if (isSecondD)
                                            kPr = 'R';
                                        else if (isSecondS)
                                            kPr = 'D';
                                        ClassLibraryOnlineGame2.Bullet newBullet = new ClassLibraryOnlineGame2.Bullet(secondHero.posX, secondHero.posY, kPr, 'R');
                                        ClassLibraryOnlineGame2.Bullet.bulletsList.Add(newBullet);
                                        break;
                                    case "Left":
                                        if (input.Length >= 4)
                                        {
                                            ClassLibraryOnlineGame2.Bullet newBulletEndPosLeft = new ClassLibraryOnlineGame2.Bullet(secondHero.posX, secondHero.posY, Convert.ToInt32(input[2]), Convert.ToInt32(input[3]), 'R', true);
                                            ClassLibraryOnlineGame2.Bullet.bulletsList.Add(newBulletEndPosLeft);
                                        }
                                        break;
                                    case "Right":
                                        if (input.Length >= 4)
                                        {
                                            ClassLibraryOnlineGame2.Bullet newBulletEndPosRight = new ClassLibraryOnlineGame2.Bullet(secondHero.posX, secondHero.posY, Convert.ToInt32(input[2]), Convert.ToInt32(input[3]), 'R');
                                            ClassLibraryOnlineGame2.Bullet.bulletsList.Add(newBulletEndPosRight);
                                        }
                                        break;
                                    default:
                                        break;
                                }
                            }
                            if (input[0] == "KeyDown")
                            {
                                switch (input[1])
                                {
                                    case "87":
                                        isSecondW = true;
                                        break;
                                    case "65":
                                        isSecondA = true;
                                        break;
                                    case "83":
                                        isSecondS = true;
                                        break;
                                    case "68":
                                        isSecondD = true;
                                        break;
                                    default:
                                        break;
                                }
                            }
                            if (input.Length >= 4)
                                Console.WriteLine("Клиент 2: " + input[0] + " " + input[1] + " " + input[2] + " " + input[3]);
                            else
                                Console.WriteLine("Клиент 2: " + input[0] + " " + input[1]);
                        }
                    }
                }
                catch (Exception e)
                {
                    Console.WriteLine("Ошибка связи с клиентом 2: " + e.Message);
                }
            });
            threadSendInput1 = new Thread(() =>
            {
                try
                {
                    while (true)
                    {
                        string[] input = stReader1.ReadLine().Split('~');
                        if (input.Length >= 2)
                        {
                            if (input[0] == "KeyUp")
                            {
                                switch (input[1])
                                {
                                    case "87":
                                        isFirstW = false;
                                        break;
                                    case "65":
                                        isFirstA = false;
                                        break;
                                    case "83":
                                        isFirstS = false;
                                        break;
                                    case "68":
                                        isFirstD = false;
                                        break;
                                    case "32":
                                        char kPr = 'R';
                                        if (isFirstW && isFirstA)
                                            kPr = 'Q';
                                        else if (isFirstA && isFirstS)
                                            kPr = 'Z';
                                        else if (isFirstS && isFirstD)
                                            kPr = 'C';
                                        else if (isFirstD && isFirstW)
                                            kPr = 'E';
                                        else if (isFirstW)
                                            kPr = 'U';
                                        else if (isFirstA)
                                            kPr = 'L';
                                        else if (isFirstS)
                                            kPr = 'D';
                                        ClassLibraryOnlineGame2.Bullet newBullet = new ClassLibraryOnlineGame2.Bullet(firstHero.posX, firstHero.posY, kPr, 'I');
                                        ClassLibraryOnlineGame2.Bullet.bulletsList.Add(newBullet);
                                        break;
                                    case "Left":
                                        if (input.Length >= 4)
                                        {
                                            ClassLibraryOnlineGame2.Bullet newBulletEndPosLeft = new ClassLibraryOnlineGame2.Bullet(firstHero.posX, firstHero.posY, Convert.ToInt32(input[2]), Convert.ToInt32(input[3]), 'I', true);
                                            ClassLibraryOnlineGame2.Bullet.bulletsList.Add(newBulletEndPosLeft);
                                        }
                                        break;
                                    case "Right":
                                        if (input.Length >= 4)
                                        {
                                            ClassLibraryOnlineGame2.Bullet newBulletEndPosRight = new ClassLibraryOnlineGame2.Bullet(firstHero.posX, firstHero.posY, Convert.ToInt32(input[2]), Convert.ToInt32(input[3]), 'I');
                                            ClassLibraryOnlineGame2.Bullet.bulletsList.Add(newBulletEndPosRight);
                                        }
                                        break;
                                    default:
                                        break;
                                }
                            }
                            if (input[0] == "KeyDown")
                            {
                                switch (input[1])
                                {
                                    case "87":
                                        isFirstW = true;
                                        break;
                                    case "65":
                                        isFirstA = true;
                                        break;
                                    case "83":
                                        isFirstS = true;
                                        break;
                                    case "68":
                                        isFirstD = true;
                                        break;
                                    default:
                                        break;
                                }
                            }
                            if (input.Length >= 4)
                                Console.WriteLine("Клиент 1: " + input[0] + " " + input[1] + " " + input[2] + " " + input[3]);
                            else
                                Console.WriteLine("Клиент 1: " + input[0] + " " + input[1]);
                        }
                    }
                }
                catch (Exception e)
                {
                    Console.WriteLine("Ошибка связи с клиентом 1: " + e.Message);
                }
            });
            threadSendInput1.Start();
            threadSendInput2.Start();
            stWriter1.WriteLine("Status~200");
            stWriter2.WriteLine("Status~200");
            Console.WriteLine("Отправлены статусы 200");
            threadLife = new Thread(() =>
            {
                Random rand = new Random();
                while (true)
                {
                    if (isFirstW)
                        if (firstHero.posY - 3 > 0)
                            firstHero.posY -= 3;
                    if (isFirstA)
                        if (firstHero.posX - 3 > 0)
                            firstHero.posX -= 3;
                    if (isFirstS)
                        if (firstHero.posY + 3 < 412)
                            firstHero.posY += 3;
                    if (isFirstD)
                        if (firstHero.posX + 3 < 1037)
                            firstHero.posX += 3;
                    if (isSecondW)
                        if (secondHero.posY - 3 > 0)
                            secondHero.posY -= 3;
                    if (isSecondA)
                        if (secondHero.posX - 3 > 0)
                            secondHero.posX -= 3;
                    if (isSecondS)
                        if (secondHero.posY + 3 < 412)
                            secondHero.posY += 3;
                    if (isSecondD)
                        if (secondHero.posX + 3 < 1037)
                            secondHero.posX += 3;
                    foreach (ClassLibraryOnlineGame2.Bullet bullet in ClassLibraryOnlineGame2.Bullet.bulletsList.ToArray())
                        if (!bullet.translateBullet())
                            ClassLibraryOnlineGame2.Bullet.bulletsList.Remove(bullet);
                        else
                            ClassLibraryOnlineGame2.EnemyAndBullet.checkShot(firstHero, secondHero);
                    if (rand.Next(100) > 95)
                    {
                        int posEnemy = rand.Next(0,100);
                        ClassLibraryOnlineGame2.Enemy newEnemy = new ClassLibraryOnlineGame2.Enemy(0, 0, 1);
                        if(posEnemy <= 25)
                            newEnemy = new ClassLibraryOnlineGame2.Enemy(rand.Next(0, 1037), 0, rand.Next(1,4), rand.Next(1,5));
                        else if (posEnemy <= 50)
                            newEnemy = new ClassLibraryOnlineGame2.Enemy(rand.Next(0, 1037), 412, rand.Next(1, 4), rand.Next(1, 5));
                        else if (posEnemy <= 75)
                            newEnemy = new ClassLibraryOnlineGame2.Enemy(0, rand.Next(0, 412), rand.Next(1, 4), rand.Next(1, 5));
                        else if (posEnemy <= 100)
                            newEnemy = new ClassLibraryOnlineGame2.Enemy(1037, rand.Next(0, 412), rand.Next(1, 4), rand.Next(1, 5));
                        ClassLibraryOnlineGame2.Enemy.enemyList.Add(newEnemy);
                    }
                    foreach (ClassLibraryOnlineGame2.Enemy enemy in ClassLibraryOnlineGame2.Enemy.enemyList)
                        enemy.translateEnemy(firstHero, secondHero);
                    try
                    {
                        ClassLibraryOnlineGame2.MainGame mainGame = new ClassLibraryOnlineGame2.MainGame(firstHero, secondHero);
                        mainGame.bulletsList = ClassLibraryOnlineGame2.Bullet.bulletsList;
                        mainGame.enemyList = ClassLibraryOnlineGame2.Enemy.enemyList;
                        try
                        {
                            stWriter1.WriteLine("MainGame~" + JsonConvert.SerializeObject(mainGame));
                        }
                        catch (System.IO.IOException)
                        {
                            Console.WriteLine("Ошибка при передачи данных клиенту 1");
                            stWriter1.Close();
                            Console.WriteLine("Соединение 1 закрыто");
                        }
                        try
                        {
                            stWriter2.WriteLine("MainGame~" + JsonConvert.SerializeObject(mainGame));
                        }
                        catch (System.IO.IOException)
                        {
                            Console.WriteLine("Ошибка при передачи данных клиенту 2");
                            stWriter2.Close();
                            Console.WriteLine("Соединение 2 закрыто");
                        }
                    }
                    catch (System.InvalidOperationException exception)
                    {
                        Console.WriteLine("Ошибка при серилизации (коллекция): " + exception.Message);
                    }
                    Thread.Sleep(70);
                }
            });
            threadLife.Start();
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