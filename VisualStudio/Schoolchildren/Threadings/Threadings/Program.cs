using System;
using System.Threading;

namespace Threadings
{
    class Program
    {
        public static object locker = new object();

        static string btn = "as";

        static void Main(string[] args)
        {
            Thread threadFirst= new Thread(() =>
            {
                MethodForLocker(1);
            });
            Thread threadSecond = new Thread(() =>
            {
                MethodForLocker(10);
            });
            Thread threadThird = new Thread(() =>
            {
                MethodForLocker(100);
            });

            threadFirst.Start();
            threadSecond.Start();
            threadThird.Start();

            return;

            //Работа с потоками для маленьких
            Thread thread1 = new Thread(new ThreadStart(ThreadFunction));
            //Thread thread1 = new Thread(ThreadFunction);
            thread1.Start();
            for (int i = 0; i < 100; i++)
            {
                Console.WriteLine("Это внутри основного потока!");
            }

            if(thread1.IsAlive)
            {
                thread1.Abort();
                Console.WriteLine("Прервали дочерний поток!");
            }

            Thread.Sleep(2000);

            Console.WriteLine("Конец программы!");

            return;

            //Работа с параметрами
            Thread thread2 = new Thread(new ParameterizedThreadStart(ThreadFunction2));
            thread2.Start("Строка");

            //Работа с потоками через делегата
            new Thread(delegate()
            {
                Thread.Sleep(400);
                Console.WriteLine("Внутри дочернего через делегат!");
                Method("Параметр");
            }).Start();

            //Работа с потоками через "полуделегат"
            new Thread(() =>
            {
                Thread.Sleep(1000);
                Console.WriteLine("Внутри дочерней");
            }).Start();

            Console.WriteLine("Основная уже завершилась!");

            //Работа с потоками для постоянного считывания клавиш
            Thread threadForReadKey = new Thread(ReadKeyConsole);
            threadForReadKey.Start();

            for (int i = 0; i < 100; i++)
            {
                Console.WriteLine(btn);
                Thread.Sleep(500);
            }

            if (threadForReadKey.IsAlive)
            {
                threadForReadKey.Abort();
            }

            #region три потока
            Thread thread7 = new Thread(() => {
                ThreadingMethod(1);
            });
            Thread thread8 = new Thread(() =>
            {
                ThreadingMethod(10);
            });
            Thread thread9 = new Thread(() =>
            {
                ThreadingMethod(100);
            });
            thread7.Start();
            thread8.Start();
            thread9.Start();
            #endregion
        }

        static void MethodForLocker(int number)
        {
            Console.WriteLine("Сюда пришёл поток №" + number);
            lock(locker)
            {
                Console.WriteLine("Зашёл и закрыл поток №" + number);
                for (int i = 0; i < 10; i++)
                {
                    Console.WriteLine("Дочерний поток " + number);
                }
            }
            Console.WriteLine("Вышел и открыл поток №" + number);
        }

        static void ThreadingMethod(int number)
        {
            Console.WriteLine("Дочерний поток " + number);
        }

        static void Method(string s)
        {
            Console.WriteLine("Какой-то метод с параметром! " + s);
        }

        static void ReadKeyConsole()
        {
            while (true)
            {
                System.ConsoleKeyInfo key = Console.ReadKey();
                if (key.Key == ConsoleKey.UpArrow)
                {
                    btn = "Up";
                }
                else if (key.Key == ConsoleKey.DownArrow)
                {
                    btn = "Down";
                }
                else if (key.Key == ConsoleKey.LeftArrow)
                {
                    btn = "Left";
                }
                else if (key.Key == ConsoleKey.RightArrow)
                {
                    btn = "Right";
                }
            }
        }

        static void ThreadFunction()
        {
            for (int i = 0; i < 1000; i++)
            {
                Console.WriteLine("---Это внутри дочернего потока!" + i);
            }
        }

        static void ThreadFunction2(object s)
        {
            Console.WriteLine(s);
        }
    }
}
