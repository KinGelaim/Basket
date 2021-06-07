using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

namespace TypeConvertor
{
    class Program
    {
        static void Main(string[] args)
        {
            int i;
            byte b;

            i = 257;

            b = (byte)i; //Конвертируется, но не правильно

            try
            {
                b = Convert.ToByte(i); //Выведет ошибку при конвертации
            }
            catch
            {
                Console.WriteLine("Ошибка при конвертации!");
            }
            Console.WriteLine("Байт: " + b);

            Console.ReadKey();
        }
    }
}
