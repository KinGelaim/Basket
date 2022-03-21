using System;


namespace AbstractVsInterface
{
    public class Program
    {
        private static void Main(string[] args)
        {
            // First
            First.FirstBegin fb = new First.FirstBegin();
            fb.Start();
            Console.WriteLine();

            // Second
            Second.SecondBegin sb = new Second.SecondBegin();
            sb.Start();
            Console.WriteLine();

            // Third
            Third.ThirdBegin tb = new Third.ThirdBegin();
            tb.Start();
            Console.WriteLine();

            // Thour

            

        }
    }
}