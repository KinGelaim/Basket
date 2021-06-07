using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.IO;
using System.Diagnostics;
using System.Threading;
using IWshRuntimeLibrary;
//using System.Runtime.InteropServices;
//using System.Runtime.InteropServices.ComTypes;

namespace Updater
{
    class Program
    {
        static void Main(string[] args)
        {
            //Отдельный лаунчер для обновления программы
            //  1) Закрываем старую программу (в случае, если она еще открыта)
            //  2) Удалияем старую программу
            //  3) Переименовываем скаченную
            //  4) В случае, если пришли дополнительные ключи
            //      4.1) Закинет ярлык на рабочий стол                  (-y)
            //      4.2) Запустит новый процесс с новой программой      (-s)
            //      4.3) Закроет лаунчер                                (-e)

            try
            {
                if (args.Length > 1)
                {
                    string process = args[1].Replace(".exe", "");

                    Console.WriteLine("Процесс обновления запущен!");
                    while (Process.GetProcessesByName(process).Length > 0)
                    {
                        Process[] myProcesses2 = Process.GetProcessesByName(process);
                        for (int i = 1; i < myProcesses2.Length; i++) { myProcesses2[i].Kill(); }
                        Console.WriteLine("Старая программа еще запущена!");
                        Thread.Sleep(300);
                    }

                    //Удаляем старую прогу
                    Console.WriteLine("Удаляем старую прогу!");
                    if (System.IO.File.Exists(args[1])) { System.IO.File.Delete(args[1]); }

                    //Переименовываем
                    Console.WriteLine("Переименовываем!");
                    System.IO.File.Move(args[0], args[1]);

                    //Проверяем ключи
                    bool isExit = false;
                    for (int i = 2; i < args.Length; i++)
                    {
                        //Создаем ярлык на рабочем столе
                        if (args[i] == "-y")
                        {
                            Console.WriteLine("Создаем ярлык " + args[1]);
                            object shDesktop = (object)"Desktop";
                            WshShell shell = new WshShell();
                            string shortcutAddress = (string)shell.SpecialFolders.Item(ref shDesktop) + '\\' + args[1].Replace(".exe", "") + ".lnk";
                            IWshShortcut shortcut = (IWshShortcut)shell.CreateShortcut(shortcutAddress);
                            shortcut.Description = "Ярлык для " + args[1];
                            shortcut.Hotkey = "Ctrl+Shift+N";
                            shortcut.TargetPath = System.IO.Directory.GetCurrentDirectory() + @"\" + args[1];
                            shortcut.Save();
                        }
                        //Запускаем программу
                        if (args[i] == "-s")
                        {
                            Console.WriteLine("Запускаем " + args[1]);
                            Process.Start(args[1]);
                        }
                        //Выходим из лаунчера после обновления
                        if (args[i] == "-e")
                            isExit = true;
                    }

                    Console.WriteLine("Работа завершена!");
                    if (!isExit)
                        Console.ReadKey();
                }
                else
                {
                    Console.WriteLine("Не хватает аргументов!\nАргумент 1 - новая программа, аргумент 2 - старая!");
                    Console.ReadKey();
                }
            }
            catch (Exception e) {
                Console.WriteLine("Какая-то ошибка!\n" + e.Message);
                Console.ReadKey();
            }
        }
    }
}
