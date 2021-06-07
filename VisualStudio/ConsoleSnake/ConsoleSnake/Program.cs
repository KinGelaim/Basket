using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading;

namespace ConsoleSnake
{
    class Program
    {
        /* 
         * Стандартная игра змейка со следующими условиями: 
         *                      1) Стены не проходятся!
         *                      2) Яблоко добавляет 10 очков
         *                      3) Без проверки на наезд на саму себя (пока что)
         * 
         * Игра будет реализоваться в трех вариациях:
         *                      1) Имеем массив змейки и перерисовываем массив змейки каждый раз
         *                         Недостатки: при увеличении змейки необходимо дольше пробегать по массиву; 
         *                      2) Имеем массив всего поля и перерисовываем все поле каждый раз
         *                         Плюсы: легкая генерация яблока и легкая проверка наезда змейки на змею или стену
         *                         Недостатки: даже при маленькой змейке все равно придется пробегать по большому массиву
         *                      3) Имеем координаты головы и кончика хвоста (смещаем голову и закрашиваем позицию хвоста, если не съели яблоко)
         *                         Плюсы метода: отрисовка идет только двух позиций змеи; не нужно пробегать по массиву
         *                         Недостаток: не так легко отследить наезд змеи самой на себя
         * 
         * */

        public static int menu = 0;
        public static bool isInMenu = true;
        public static int subMenu = 0;
        public static bool isInSubMenu = false;

        static void Main(string[] args)
        {
            AppDomain.CurrentDomain.ProcessExit += new EventHandler(CurrentDomain_ProcessExit);
            Console.Title = "Змейка :3";
            StartMenu();
            KeyPress.StartThreadMenu();
            //Game_1.StartGame();
            //Console.ReadKey();
        }

        static void StartMenu()
        {
            Console.ForegroundColor = ConsoleColor.Green;
            string[] str = new string[]{"", "", "",
                                        "\t##############################################################",
                                        "\t##      $$$$$  $$       $$ $$$$ $ $$ $ $  $       $         ##",
                                        "\t##     $     $ $ $     $ $ $    $   $$ $  $      $ $        ##",
                                        "\t##          $$ $  $   $  $ $$$$ $  $ $ $ $      $   $       ##",
                                        "\t##         $$  $   $ $   $ $    $ $  $ $$      $$$$$$$      ##",
                                        "\t##     $     $ $    $    $ $    $$   $ $ $    $       $     ##",
                                        "\t##      $$$$$  $         $ $$$$ $    $ $  $  $         $    ##",
                                        "\t##############################################################"};
            for (int i = 0; i < str.Length; i++)
            {
                Console.Clear();
                int k = i;
                for (int j = str.Length - 1; j >= str.Length - i - 1; j--)
                {
                    Console.SetCursorPosition(0, k);
                    Console.Write(str[j]);
                    k--;
                }
                Thread.Sleep(500);
            }
            
            CreateMenu();
        }

        public static void CreateMenu()
        {
            if(menu == 0)
                Console.ForegroundColor = ConsoleColor.Red;
            else
                Console.ForegroundColor = ConsoleColor.Gray;
            Console.SetCursorPosition(30, 15);
            Console.Write("СТАРТ");
            if (menu == 1)
                Console.ForegroundColor = ConsoleColor.Red;
            else
                Console.ForegroundColor = ConsoleColor.Gray;
            Console.SetCursorPosition(30, 17);
            Console.Write("ВЫХОД");
        }

        public static void CreateSubMenu()
        {
            string text = "Три режима реализации игры:\n1) Имеем массив ячеек змейки и сдвигаем каждую ячейку массива каждый раз\n2) Имеем массив всего поля и перерисовываем все поле каждый раз\n3) Имеем координаты головы и кончика хвоста (смещаем голову и закрашиваем позицию хвоста, если не съели яблоко)";
            Console.Clear();
            Console.ForegroundColor = ConsoleColor.Gray;
            Console.SetCursorPosition(0, 0);
            Console.WriteLine(text);
            if (subMenu == 0)
                Console.ForegroundColor = ConsoleColor.Red;
            else
                Console.ForegroundColor = ConsoleColor.Gray;
            Console.SetCursorPosition(30, 12);
            Console.Write("Режим 1");
            if (subMenu == 1)
                Console.ForegroundColor = ConsoleColor.Red;
            else
                Console.ForegroundColor = ConsoleColor.Gray;
            Console.SetCursorPosition(30, 14);
            Console.Write("Режим 2");
            if (subMenu == 2)
                Console.ForegroundColor = ConsoleColor.Red;
            else
                Console.ForegroundColor = ConsoleColor.Gray;
            Console.SetCursorPosition(30, 16);
            Console.Write("Режим 3");
            if (subMenu == 3)
                Console.ForegroundColor = ConsoleColor.Red;
            else
                Console.ForegroundColor = ConsoleColor.Gray;
            Console.SetCursorPosition(30, 18);
            Console.Write("Выход");
        }

        public static void StartGame(int level=1)
        {
            Console.Title = "Змейка :3 - Режим " + level;
            if (level == 1)
                Game_1.StartGame();
            if (level == 2)
                Game_2.StartGame();
            /*if (level == 3)
                Game_3.StartGame();*/
            isInMenu = false;
            isInSubMenu = false;
        }

        static void CurrentDomain_ProcessExit(Object sender, EventArgs e)
        {
            KeyPress.EndThread();
        }
    }
}
