using System;
using System.Collections.Generic;
using System.Linq;
using System.Runtime.InteropServices;
using System.Text;

namespace ConsoleBuffer
{
    class Program
    {
        const int GENERIC_READ = unchecked((int)0x80000000);
        const int GENERIC_WRITE = 0x40000000;

        [DllImport("Kernel32.dll")]
        private static extern IntPtr CreateConsoleScreenBuffer(
            int dwDesiredAccess, int dwShareMode,
            IntPtr secutiryAttributes,
            UInt32 flags,
            IntPtr screenBufferData);

        [DllImport("kernel32.dll")]
        static extern IntPtr SetConsoleActiveScreenBuffer(IntPtr hConsoleOutput);
        [DllImport("kernel32.dll")]
        static extern bool WriteConsole(
            IntPtr hConsoleOutput, string lpBuffer,
            uint nNumberOfCharsToWrite, out uint lpNumberOfCharsWritten,
            IntPtr lpReserved);

        static void Main(string[] args)
        {
            //Размер консоли
            Console.WindowHeight = 30;
            Console.WindowWidth = 100;

            //Размер буфера
            Console.BufferHeight = 30;
            Console.BufferWidth = 100;

            //Создаём два буфера
            IntPtr ptr1 = CreateConsoleScreenBuffer(
                GENERIC_READ | GENERIC_WRITE, 0x3,
                IntPtr.Zero, 1, IntPtr.Zero);

            IntPtr ptr2 = CreateConsoleScreenBuffer(
                GENERIC_READ | GENERIC_WRITE, 0x3,
                IntPtr.Zero, 1, IntPtr.Zero);

            IntPtr ptr3 = CreateConsoleScreenBuffer(
                GENERIC_READ | GENERIC_WRITE, 0x3,
                IntPtr.Zero, 1, IntPtr.Zero);

            IntPtr ptr4 = CreateConsoleScreenBuffer(
                GENERIC_READ | GENERIC_WRITE, 0x3,
                IntPtr.Zero, 1, IntPtr.Zero);

            uint k = 0;
            for (int i = 0; i < Console.BufferWidth; i++)
                for (int j = 0; j < Console.BufferHeight; j++)
                    WriteConsole(ptr1, "A", 1, out k, IntPtr.Zero);

            for (int i = 0; i < Console.BufferWidth; i++)
                for (int j = 0; j < Console.BufferHeight; j++)
                    WriteConsole(ptr2, "D", 1, out k, IntPtr.Zero);

            for (int i = 0; i < Console.BufferWidth; i++)
                for (int j = 0; j < Console.BufferHeight; j++)
                    WriteConsole(ptr3, "W", 1, out k, IntPtr.Zero);

            for (int i = 0; i < Console.BufferWidth; i++)
                for (int j = 0; j < Console.BufferHeight; j++)
                    WriteConsole(ptr4, "S", 1, out k, IntPtr.Zero);

            //Вывод текста в зависимости от нажатой клавиши
            while (true)
            {
                ConsoleKey key = Console.ReadKey().Key;
                if (key == ConsoleKey.A)
                {
                    SetConsoleActiveScreenBuffer(ptr1);
                }
                if (key == ConsoleKey.D)
                {
                    SetConsoleActiveScreenBuffer(ptr2);
                }
                if (key == ConsoleKey.W)
                {
                    SetConsoleActiveScreenBuffer(ptr3);
                }
                if (key == ConsoleKey.S)
                {
                    SetConsoleActiveScreenBuffer(ptr4);
                }
            }
            Console.ReadKey();
        }
    }
}
