using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.IO;
using System.Threading;

namespace FDD
{
    class Program
    {
        private static string path = @"A:\";

        static void Main(string[] args)
        {
            string[] musics = { "Похоронка", "Имперский марш!", "Собачий вальс" };
            Console.WriteLine("Добро пожаловать в бар Мюзикс!\n");
            Console.Write("Какой режим Вы заказываете (beep/ffd): ");
            string form = Console.ReadLine();
            if (form != "beep" && form != "ffd")
            {
                Console.WriteLine("Упс!");
            }
            else
            {
                Console.WriteLine("Какую песню закажите:");
                for (int i = 0; i < musics.Length; i++)
                {
                    Console.WriteLine((i + 1) + " - " + musics[i]);
                }
                string key = Console.ReadLine();
                switch (key)
                {
                    case "1":
                        Console.WriteLine("Проигрываем музыку похоронки");
                        play(form);
                        break;
                    case "2":
                        break;
                    case "3":
                        break;
                    default:
                        Console.WriteLine("Увы, такой песни у нас нет");
                        break;
                }
            }
        }

        private static void play(string form)
        {
            if (form == "beep")
            {
                Console.Beep(200, 900);
                Console.Beep(200, 900);
                Console.Beep(400, 600);
                Console.Beep(170, 900);
                Console.Beep(200, 900);
                Console.Beep(400, 600);
                Console.Beep(170, 900);
                Console.Beep(400, 600);
                Console.Beep(170, 900);
                Console.Beep(400, 600);
                Console.Beep(170, 900);
            }
            if (form == "ffd")
            {
                playSound(30, 200);
                playSound(30, 200);
                playSound(10, 100);
                playSound(30, 200);
                playSound(20, 200);
                playSound(10, 100);
                playSound(20, 200);
                playSound(10, 100);
                playSound(20, 200);
                playSound(10, 100);
                playSound(20, 200);
            }
        }

        private static void playSound(int count, int delay = 0)
        {
            for (int i = 0; i < count; i++)
                try
                {
                    using (FileStream fs = new FileStream(path, FileMode.Open))
                    {

                    }
                }
                catch (Exception e)
                {
                    //Console.WriteLine(e.Message);
                }
                finally
                {

                }
            Thread.Sleep(delay);
        }
    }
}
