using System;


namespace MiniSnake_v5._0
{
    public class Program
    {
        private static void Main(string[] args)
        {
            // Settings Console
            Console.Title = "S N A K E ~~~~~~~";
            Console.CursorVisible = false;

            // Create game with size
            GameSnake game = new GameSnake(40, 20);
            game.ShowStartMenu();

            //Console.ReadKey();
        }
    }
}