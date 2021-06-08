using System;

static public class MainStaticClass
{
    static public void PrintHW(string text = "")
    {
        Console.WriteLine("-------------------------------------------------");
        Console.WriteLine("|                                               |");
        Console.WriteLine("|\t\t" + text + "\t\t|");
        Console.WriteLine("|                                               |");
        Console.WriteLine("-------------------------------------------------");
    }

    static public void PrintQuest(int number)
    {
        Console.WriteLine("--------- Задание № " + number + " ---------");
    }
}
