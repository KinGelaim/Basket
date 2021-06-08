using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

namespace MiniSnake_v2._1
{
    class Program
    {
        static void Main(string[] args)
        {
            //Настройки самой консоли
            Console.Title = "Змейка ~~~~~~~";
            Console.CursorVisible = true;

            //Создание игры с указанием размеров
            GameSnake game = new GameSnake(40, 20);
            game.ShowStartMenu();

            //Console.ReadKey();
        }
    }
}
