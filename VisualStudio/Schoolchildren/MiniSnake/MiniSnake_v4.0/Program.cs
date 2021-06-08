using System;

namespace MiniSnake_v4._0
{
    class Program
    {
        static void Main(string[] args)
        {
            //Настройки самой консоли
            Console.Title = "Змейка ~~~~~~~";
            Console.CursorVisible = false;

            //Создание игры с указанием размеров
            GameSnake game = new GameSnake(40, 20);
            game.ShowStartMenu();

            //Console.ReadKey();
        }
    }
}
