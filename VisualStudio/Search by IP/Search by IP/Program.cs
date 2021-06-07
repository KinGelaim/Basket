using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading;

namespace Search_by_IP
{
    class Program
    {
        static List<string> arrMatrix = new List<string>();
        //static int[,] arrIntSize = new int[10000, 10000];
 
        static void Main(string[] args)
        {
            /*for (int i = 0; i < 10000; i++)
                for (int j = 0; j < 10000; j++)
                    arrIntSize[i, j] = i + j;
            new Thread(() => {
                while (true)
                {
                    for (int i = 0; i < 10000; i++)
                        for (int j = 0; j < 10000; j++)
                        {
                            if(j + 1 < 10000)
                                if(arrIntSize[i, j] > arrIntSize[i, j + 1])
                                {
                                    int tmp = arrIntSize[i, j];
                                    arrIntSize[i, j] = arrIntSize[i, j + 1];
                                    arrIntSize[i, j + 1] = tmp;
                                }

                        }
                    for (int i = 0; i < 10000; i++)
                        for (int j = 0; j < 10000; j++)
                        {
                            if (j + 1 < 10000)
                                if (arrIntSize[i, j] < arrIntSize[i, j + 1])
                                {
                                    int tmp = arrIntSize[i, j];
                                    arrIntSize[i, j] = arrIntSize[i, j + 1];
                                    arrIntSize[i, j + 1] = tmp;
                                }

                        }
                }
            });*/
            Console.CursorVisible = false;

            Console.Title = "Search by IP";

            Console.WriteLine("Loading... ");
            Console.WriteLine();
            Print("Loading System...", 400);
            Print("Loading PodSystem...", 300);
            Print("Loading Core...", 500);
            Print("Loading Humans BD", 700);

            int pos = Console.CursorTop;
            Console.SetCursorPosition(11, 0);
            Console.Write("OK");
            Console.SetCursorPosition(0, pos);

            Console.WriteLine();

            System.Net.IPAddress adress;

            string ip = "";
            while (ip != "0")
            {
                Console.Write("Input IP adress (input 0 from exit): ");
                ip = Console.ReadLine();
                if (ip == "0")
                    continue;
                if (!System.Net.IPAddress.TryParse(ip, out adress))
                {
                    Console.WriteLine("Incorrect IP adress!");
                    continue;
                }
                Console.WriteLine("Search human with IP adress: " + adress + " ? (Y/N)");
                string answer = "";
                answer = Console.ReadLine();
                if (answer == "Y")
                {
                    Print("Loading FBI BD", 1200);

                    Console.Clear();
                    SearchProcess(adress.ToString());
                    answer = "";
                    while (answer != "N" && answer != "Y")
                    {
                        Console.Write("Do you want to refresh search? (Y/N): ");
                        answer = Console.ReadLine();
                        if (answer == "N")
                            ip = "0";
                    }
                }
            }
        }

        static void Print(string message, int delay)
        {
            Console.WriteLine(message);
            var rand = new Random();

            for (int i = 0; i <= 100; i++)
            {
                if (i < 25)
                    Console.ForegroundColor = ConsoleColor.Magenta;
                else if (i < 50)
                    Console.ForegroundColor = ConsoleColor.Red;
                else if (i < 75)
                    Console.ForegroundColor = ConsoleColor.Cyan;
                else if (i < 100)
                    Console.ForegroundColor = ConsoleColor.Yellow;
                else
                    Console.ForegroundColor = ConsoleColor.Green;

                string pct = string.Format("{0, -30} {1,3}%", new string((char)0x2592, i * 30 / 100), i);
                Console.CursorLeft = 0;
                Console.Write(pct);
                Thread.Sleep(rand.Next(0, delay));
            }
            Console.WriteLine();
            Console.ResetColor();
        }

        static void SearchProcess(string ip)
        {
            //ДВА ПОТОКА (Один поток красит экран в матрицу, второй поток мигает сообщением поиска)
            Thread threadMessage = new Thread(() =>
            {
                while (true)
                {
                    /*int pos = Console.CursorTop;
                    Console.ResetColor();
                    //Console.SetCursorPosition(0, Console.WindowHeight);
                    Console.Write("Search human with IP adress: " + ip);
                    Console.WriteLine();
                    Console.CursorTop = pos;
                    //Thread.Sleep(1700);
                    //Console.SetCursorPosition(0, Console.WindowHeight);
                    //Console.Clear();
                    Thread.Sleep(50);*/
                    Console.Title = "Search human with IP adress: " + ip;
                    Thread.Sleep(1700);
                    Console.Title = "";
                    Thread.Sleep(70);
                }
            });

            Thread threadMessageEnd = new Thread(() =>
            {
                while (true)
                {
                    /*int pos = Console.CursorTop;
                    Console.ResetColor();
                    //Console.SetCursorPosition(0, Console.WindowHeight);
                    Console.Write("Search human with IP adress: " + ip);
                    Console.WriteLine();
                    Console.CursorTop = pos;
                    //Thread.Sleep(1700);
                    //Console.SetCursorPosition(0, Console.WindowHeight);
                    //Console.Clear();
                    Thread.Sleep(50);*/
                    Console.Title = "User is FIND!!!";
                    Thread.Sleep(1700);
                    Console.Title = "";
                    Thread.Sleep(70);
                }
            });

            threadMessage.Start();

            //Thread threadCreateMatrix = new Thread(() =>
            {
                Random rand = new Random();
                for (int c = 0; c < rand.Next(700, 1000); c++)
                {
                    string k = "";
                    for (int i = 0; i <= Console.LargestWindowWidth / 3 - 1; i++)
                    {
                        k += rand.Next(0, 2);
                    }
                    //arrMatrix.Add(k);
                    //if (Console.CursorTop - 1 > 0)
                        //Console.CursorTop = Console.CursorTop - 1;
                    Console.ForegroundColor = ConsoleColor.Green;
                    Console.Write(k);
                    Thread.Sleep(30);
                }
                Console.Beep();
                Console.Beep();
                Console.Beep();
                Thread.Sleep(3000);
                if (threadMessage.IsAlive)
                    threadMessage.Abort();
                threadMessageEnd.Start();
                Console.WriteLine();
                Thread.Sleep(70);
                Console.WriteLine();
                Thread.Sleep(70);
                printInMatrix("                      " + rand.Next(0, 2) + rand.Next(0, 2) + rand.Next(0, 2) + rand.Next(0, 2) + rand.Next(0, 2) + rand.Next(0, 2) + rand.Next(0, 2) + rand.Next(0, 2) + rand.Next(0, 2) + rand.Next(0, 2) + rand.Next(0, 2) + rand.Next(0, 2) + "        " + rand.Next(0, 2) + rand.Next(0, 2) + "          " + rand.Next(0, 2) + rand.Next(0, 2), 30);
                printInMatrix("                      " + rand.Next(0,2) + rand.Next(0, 2) + rand.Next(0, 2) + rand.Next(0, 2) + rand.Next(0, 2) + rand.Next(0, 2) + rand.Next(0, 2) + rand.Next(0, 2) + rand.Next(0, 2) + rand.Next(0, 2) + rand.Next(0, 2) + rand.Next(0,2) + "        " + rand.Next(0, 2) + rand.Next(0, 2) + "          " + rand.Next(0, 2) + rand.Next(0, 2),30);
                printInMatrix("                           " + rand.Next(0, 2) + rand.Next(0, 2) + "             " + rand.Next(0, 2) + rand.Next(0, 2) + rand.Next(0, 2) + rand.Next(0, 2) + rand.Next(0, 2) + rand.Next(0, 2) + rand.Next(0, 2) + rand.Next(0, 2) + rand.Next(0, 2) + "   " + rand.Next(0, 2) + rand.Next(0, 2),30);
                printInMatrix("                           " + rand.Next(0, 2) + rand.Next(0, 2) + "             " + rand.Next(0, 2) + rand.Next(0, 2) + rand.Next(0, 2) + rand.Next(0, 2) + rand.Next(0, 2) + rand.Next(0, 2) + rand.Next(0, 2) + rand.Next(0, 2) + rand.Next(0, 2) + "   " + rand.Next(0, 2) + rand.Next(0, 2),30);
                printInMatrix("                           " + rand.Next(0, 2) + rand.Next(0, 2) + "             " + rand.Next(0, 2) + rand.Next(0, 2) + "     " + rand.Next(0, 2) + rand.Next(0, 2) + "   " + rand.Next(0, 2) + rand.Next(0, 2), 30);
                printInMatrix("                           " + rand.Next(0, 2) + rand.Next(0, 2) + "             " + rand.Next(0, 2) + rand.Next(0, 2) + "     " + rand.Next(0, 2) + rand.Next(0, 2) + "   " + rand.Next(0, 2) + rand.Next(0, 2), 30);
                printInMatrix("                           " + rand.Next(0, 2) + rand.Next(0, 2) + "             " + rand.Next(0, 2) + rand.Next(0, 2) + "     " + rand.Next(0, 2) + rand.Next(0, 2) + "   " + rand.Next(0, 2) + rand.Next(0, 2), 30);
                printInMatrix("                           " + rand.Next(0, 2) + rand.Next(0, 2) + "             " + rand.Next(0, 2) + rand.Next(0, 2) + rand.Next(0, 2) + rand.Next(0, 2) + rand.Next(0, 2) + rand.Next(0, 2) + rand.Next(0, 2) + rand.Next(0, 2) + rand.Next(0, 2) + "   " + rand.Next(0, 2) + rand.Next(0, 2), 30);
                printInMatrix("                           " + rand.Next(0, 2) + rand.Next(0, 2) + "             " + rand.Next(0, 2) + rand.Next(0, 2) + rand.Next(0, 2) + rand.Next(0, 2) + rand.Next(0, 2) + rand.Next(0, 2) + rand.Next(0, 2) + rand.Next(0, 2) + rand.Next(0, 2) + "   " + rand.Next(0, 2) + rand.Next(0, 2), 30);
                Console.WriteLine();
                Thread.Sleep(30);
                Console.WriteLine();
                Thread.Sleep(30);
                printInMatrix("      " + rand.Next(0, 2) + rand.Next(0, 2) + rand.Next(0, 2) + rand.Next(0, 2) + rand.Next(0, 2) + rand.Next(0, 2) + rand.Next(0, 2) + rand.Next(0, 2) + rand.Next(0, 2) + rand.Next(0, 2) + rand.Next(0, 2) + rand.Next(0, 2) + "    " + rand.Next(0, 2) + rand.Next(0, 2) + "       " + rand.Next(0, 2) + rand.Next(0, 2) + "       " + rand.Next(0, 2) + rand.Next(0, 2) + rand.Next(0, 2) + rand.Next(0, 2) + rand.Next(0, 2) + rand.Next(0, 2) + rand.Next(0, 2) + rand.Next(0, 2) + rand.Next(0, 2) + rand.Next(0, 2) + rand.Next(0, 2) + rand.Next(0, 2) + "       " + rand.Next(0, 2) + rand.Next(0, 2) + rand.Next(0, 2) + rand.Next(0, 2) + rand.Next(0, 2) + rand.Next(0, 2) + rand.Next(0, 2) + rand.Next(0, 2) + rand.Next(0, 2) + "    " + rand.Next(0, 2) + rand.Next(0, 2) + rand.Next(0, 2), 30);
                printInMatrix("      " + rand.Next(0, 2) + rand.Next(0, 2) + rand.Next(0, 2) + rand.Next(0, 2) + rand.Next(0, 2) + rand.Next(0, 2) + rand.Next(0, 2) + rand.Next(0, 2) + rand.Next(0, 2) + rand.Next(0, 2) + rand.Next(0, 2) + rand.Next(0, 2) + "    " + rand.Next(0, 2) + rand.Next(0, 2) + "      " + rand.Next(0, 2) + rand.Next(0, 2) + rand.Next(0, 2) + "      " + rand.Next(0, 2) + rand.Next(0, 2) + rand.Next(0, 2) + rand.Next(0, 2) + rand.Next(0, 2) + rand.Next(0, 2) + rand.Next(0, 2) + rand.Next(0, 2) + rand.Next(0, 2) + rand.Next(0, 2) + rand.Next(0, 2) + rand.Next(0, 2) + rand.Next(0, 2) + rand.Next(0, 2) + "      " + rand.Next(0, 2) + rand.Next(0, 2) + rand.Next(0, 2) + rand.Next(0, 2) + rand.Next(0, 2) + rand.Next(0, 2) + rand.Next(0, 2) + rand.Next(0, 2) + rand.Next(0, 2) + "    " + rand.Next(0, 2) + rand.Next(0, 2) + rand.Next(0, 2), 30);
                printInMatrix("      " + rand.Next(0, 2) + rand.Next(0, 2) + "        " + rand.Next(0, 2) + rand.Next(0, 2) + "    " + rand.Next(0, 2) + rand.Next(0, 2) + "     " + rand.Next(0, 2) + rand.Next(0, 2) + rand.Next(0, 2) + rand.Next(0, 2) + "      " + rand.Next(0, 2) + rand.Next(0, 2) + "          " + rand.Next(0, 2) + rand.Next(0, 2) + "      " + rand.Next(0, 2) + rand.Next(0, 2) + "     " + rand.Next(0, 2) + rand.Next(0, 2) + "    " + rand.Next(0, 2) + rand.Next(0, 2) + rand.Next(0, 2), 30);
                printInMatrix("      " + rand.Next(0, 2) + rand.Next(0, 2) + "        " + rand.Next(0, 2) + rand.Next(0, 2) + "    " + rand.Next(0, 2) + rand.Next(0, 2) + "    " + rand.Next(0, 2) + rand.Next(0, 2) + " " + rand.Next(0, 2) + rand.Next(0, 2) + "      " + rand.Next(0, 2) + rand.Next(0, 2) + "          " + rand.Next(0, 2) + rand.Next(0, 2) + "      " + rand.Next(0, 2) + rand.Next(0, 2) + "     " + rand.Next(0, 2) + rand.Next(0, 2) + "    " + rand.Next(0, 2) + rand.Next(0, 2) + rand.Next(0, 2), 30);
                printInMatrix("      " + rand.Next(0, 2) + rand.Next(0, 2) + "        " + rand.Next(0, 2) + rand.Next(0, 2) + "    " + rand.Next(0, 2) + rand.Next(0, 2) + "   " + rand.Next(0, 2) + rand.Next(0, 2) + "  " + rand.Next(0, 2) + rand.Next(0, 2) + "      " + rand.Next(0, 2) + rand.Next(0, 2) + "          " + rand.Next(0, 2) + rand.Next(0, 2) + "      " + rand.Next(0, 2) + rand.Next(0, 2) + "     " + rand.Next(0, 2) + rand.Next(0, 2) + "    " + rand.Next(0, 2) + rand.Next(0, 2) + rand.Next(0, 2), 30);
                printInMatrix("      " + rand.Next(0, 2) + rand.Next(0, 2) + "        " + rand.Next(0, 2) + rand.Next(0, 2) + "    " + rand.Next(0, 2) + rand.Next(0, 2) + "  " + rand.Next(0, 2) + rand.Next(0, 2) + "   " + rand.Next(0, 2) + rand.Next(0, 2) + "      " + rand.Next(0, 2) + rand.Next(0, 2) + "          " + rand.Next(0, 2) + rand.Next(0, 2) + "      " + rand.Next(0, 2) + rand.Next(0, 2) + rand.Next(0, 2) + rand.Next(0, 2) + rand.Next(0, 2) + rand.Next(0, 2) + rand.Next(0, 2) + rand.Next(0, 2) + rand.Next(0, 2) + "    " + rand.Next(0, 2) + rand.Next(0, 2) + rand.Next(0, 2), 30);
                printInMatrix("      " + rand.Next(0, 2) + rand.Next(0, 2) + "        " + rand.Next(0, 2) + rand.Next(0, 2) + "    " + rand.Next(0, 2) + rand.Next(0, 2) + " " + rand.Next(0, 2) + rand.Next(0, 2) + "    " + rand.Next(0, 2) + rand.Next(0, 2) + "    " + rand.Next(0, 2) + rand.Next(0, 2) + rand.Next(0, 2) + rand.Next(0, 2) + rand.Next(0, 2) + rand.Next(0, 2) + rand.Next(0, 2) + rand.Next(0, 2) + rand.Next(0, 2) + rand.Next(0, 2) + rand.Next(0, 2) + rand.Next(0, 2) + rand.Next(0, 2) + rand.Next(0, 2) + rand.Next(0, 2) + rand.Next(0, 2) + rand.Next(0, 2) + rand.Next(0, 2) + "    " + rand.Next(0, 2) + rand.Next(0, 2) + rand.Next(0, 2) + rand.Next(0, 2) + rand.Next(0, 2) + rand.Next(0, 2) + rand.Next(0, 2) + rand.Next(0, 2) + rand.Next(0, 2) + "    " + rand.Next(0, 2) + rand.Next(0, 2) + rand.Next(0, 2), 30);
                printInMatrix("      " + rand.Next(0, 2) + rand.Next(0, 2) + "        " + rand.Next(0, 2) + rand.Next(0, 2) + "    " + rand.Next(0, 2) + rand.Next(0, 2) + rand.Next(0, 2) + rand.Next(0, 2) + "     " + rand.Next(0, 2) + rand.Next(0, 2) + "    " + rand.Next(0, 2) + rand.Next(0, 2) + rand.Next(0, 2) + rand.Next(0, 2) + rand.Next(0, 2) + rand.Next(0, 2) + rand.Next(0, 2) + rand.Next(0, 2) + rand.Next(0, 2) + rand.Next(0, 2) + rand.Next(0, 2) + rand.Next(0, 2) + rand.Next(0, 2) + rand.Next(0, 2) + rand.Next(0, 2) + rand.Next(0, 2) + rand.Next(0, 2) + rand.Next(0, 2) + "    " + rand.Next(0, 2) + rand.Next(0, 2), 30);
                printInMatrix("      " + rand.Next(0, 2) + rand.Next(0, 2) + "        " + rand.Next(0, 2) + rand.Next(0, 2) + "    " + rand.Next(0, 2) + rand.Next(0, 2) + rand.Next(0, 2) + "      " + rand.Next(0, 2) + rand.Next(0, 2) + "    " + rand.Next(0, 2) + rand.Next(0, 2) + "              " + rand.Next(0, 2) + rand.Next(0, 2) + "    " + rand.Next(0, 2) + rand.Next(0, 2) + "           " + rand.Next(0, 2) + rand.Next(0, 2) + rand.Next(0, 2), 30);
                printInMatrix("      " + rand.Next(0, 2) + rand.Next(0, 2) + "        " + rand.Next(0, 2) + rand.Next(0, 2) + "    " + rand.Next(0, 2) + rand.Next(0, 2) + "       " + rand.Next(0, 2) + rand.Next(0, 2) + "    " + rand.Next(0, 2) + rand.Next(0, 2) + "              " + rand.Next(0, 2) + rand.Next(0, 2) + "    " + rand.Next(0, 2) + rand.Next(0, 2) + "           " + rand.Next(0, 2) + rand.Next(0, 2) + rand.Next(0, 2), 30);
                Console.WriteLine();
                Thread.Sleep(7000);
                if (threadMessageEnd.IsAlive)
                    threadMessageEnd.Abort();
                Console.Title = "LUL! KEK!";
            }//);

            Thread threadPrintMatrix = new Thread(() =>
            {
                while(true)
                {
                    Console.SetCursorPosition(0, 0);
                    for (int i = arrMatrix.Count - 1; i > 0; i--)
                    {
                        for (int j = 0; j <= arrMatrix[i].Length - 1; j++)
                        {
                            Console.ForegroundColor = ConsoleColor.Green;
                            Console.Write(arrMatrix[i][j]);
                        }
                    }
                }
            });
           
            //Thread.Sleep(10000);
            //threadMessage.Abort();
            //threadCreateMatrix.Start();
            //threadPrintMatrix.Start();

        }

        static void printInMatrix(string message, int delay)
        {
            Console.WriteLine(message);
            Thread.Sleep(delay);
        }
    }
}
