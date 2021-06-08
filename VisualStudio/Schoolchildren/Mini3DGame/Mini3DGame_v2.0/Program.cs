using System;
using System.Collections.Generic;
using System.Linq;
using System.Runtime.InteropServices;
using System.Text;
using System.Threading;

namespace Mini3DGame_v2._0
{
    class Program
    {
        const double PI = 3.14159;                  //Константа ПИ

        const double FOV = PI / 3;                  //Угол обзора
        const double visibleDepth = 30.0;           //Дальность видимости

        static int screenWidth = 120;      //Ширина экрана
        static int screenHeight = 40;                       //Высота экрана

        static double playerX = 1;                            //Позиция игрока
        static double playerY = 1;
        static double playerAngle = 0;                        //Угол поворота игрока

        static int mapHeight = 16;                         //Размер карты
        static int mapWidth = 16;

        static string map = "################" +
                            "#..............#" +
                            "#..............#" +
                            "#..............#" +
                            "#..............#" +
                            "#..............#" +
                            "#..............#" +
                            "#..............#" +
                            "#..............#" +
                            "#..............#" +
                            "#..............#" +
                            "#..............#" +
                            "#..............#" +
                            "#..............#" +
                            "#..............#" +
                            "################";

        //Для буферов
        static bool isFree1 = true;
        static bool isFree2 = true;
        static bool isFree3 = true;
        static bool isFree4 = true;

        static char[,] bufferChar = new char[screenWidth,screenHeight];

        //Для самих буфов
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
        [DllImport("kernel32.dll")]
        static extern bool SetConsoleCursorPosition(IntPtr hConsoleOutput, Coord dwCursorPosition);

        struct Coord
        {
            short X;
            short Y;

            public Coord(short X, short Y)
            {
                this.X = X;
                this.Y = Y;
            }
        }

        static void Main(string[] args)
        {
            //Задаю размер консоли и основного буфера
            Console.BufferWidth = screenWidth;
            Console.BufferHeight = screenHeight;

            Console.WindowWidth = screenWidth;
            Console.WindowHeight = screenHeight;

            //Создаю буферы
            IntPtr ptr0 = CreateConsoleScreenBuffer(
                GENERIC_READ | GENERIC_WRITE, 0x3,
                IntPtr.Zero, 1, IntPtr.Zero);

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

            IntPtr ptr5 = CreateConsoleScreenBuffer(
                GENERIC_READ | GENERIC_WRITE, 0x3,
                IntPtr.Zero, 1, IntPtr.Zero);

            IntPtr ptr6 = CreateConsoleScreenBuffer(
                GENERIC_READ | GENERIC_WRITE, 0x3,
                IntPtr.Zero, 1, IntPtr.Zero);

            IntPtr ptr7 = CreateConsoleScreenBuffer(
                GENERIC_READ | GENERIC_WRITE, 0x3,
                IntPtr.Zero, 1, IntPtr.Zero);

            IntPtr ptr8 = CreateConsoleScreenBuffer(
                GENERIC_READ | GENERIC_WRITE, 0x3,
                IntPtr.Zero, 1, IntPtr.Zero);

            FillBuffer(ptr0, playerAngle, playerX, playerY);
            FillBuffer(ptr1, playerAngle, playerX, playerY);
            FillBuffer(ptr2, playerAngle, playerX, playerY);
            FillBuffer(ptr3, playerAngle, playerX, playerY);
            FillBuffer(ptr4, playerAngle, playerX, playerY);
            FillBuffer(ptr5, playerAngle, playerX, playerY);
            FillBuffer(ptr6, playerAngle, playerX, playerY);
            FillBuffer(ptr7, playerAngle, playerX, playerY);
            FillBuffer(ptr8, playerAngle, playerX, playerY);
            SetConsoleActiveScreenBuffer(ptr0);

            //Игровой цикл
            while (true)
            {
                double deltaTime = 0.1;

                ConsoleKey key = Console.ReadKey().Key;
                if (key == ConsoleKey.A)
                {
                    if (isFree1)
                        SetConsoleActiveScreenBuffer(ptr1);
                    else
                        SetConsoleActiveScreenBuffer(ptr5);
                    isFree1 = !isFree1;
                }
                if (key == ConsoleKey.D)
                {
                    if (isFree2)
                        SetConsoleActiveScreenBuffer(ptr2);
                    else
                        SetConsoleActiveScreenBuffer(ptr6);
                    isFree2 = !isFree2;
                }
                if (key == ConsoleKey.W)
                {
                    if (isFree3)
                        SetConsoleActiveScreenBuffer(ptr3);
                    else
                        SetConsoleActiveScreenBuffer(ptr7);
                    isFree3 = !isFree3;
                }
                if (key == ConsoleKey.S)
                {
                    if (isFree4)
                        SetConsoleActiveScreenBuffer(ptr4);
                    else
                        SetConsoleActiveScreenBuffer(ptr8);
                    isFree4 = !isFree4;
                }

                switch (key)
                {
                    case ConsoleKey.A:
                        playerAngle -= 1.5 * deltaTime;
                        break;
                    case ConsoleKey.D:
                        playerAngle += 1.5 * deltaTime;
                        break;
                    case ConsoleKey.W:
                        playerX += Math.Sin(playerAngle) * 5 * deltaTime;
                        playerY += Math.Cos(playerAngle) * 5 * deltaTime;
                        if (map[(int)playerX + (int)playerY * mapWidth] == '#')
                        {
                            playerX -= Math.Sin(playerAngle) * 5 * deltaTime;
                            playerY -= Math.Cos(playerAngle) * 5 * deltaTime;
                        }
                        break;
                    case ConsoleKey.S:
                        playerX -= Math.Sin(playerAngle) * 5 * deltaTime;
                        playerY -= Math.Cos(playerAngle) * 5 * deltaTime;
                        if (map[(int)playerX + (int)playerY * mapWidth] == '#')
                        {
                            playerX += Math.Sin(playerAngle) * 5 * deltaTime;
                            playerY += Math.Cos(playerAngle) * 5 * deltaTime;
                        }
                        break;
                    default:
                        continue;
                }

                FillBuffers(deltaTime, ptr0, ptr1, ptr2, ptr3, ptr4, ptr5, ptr6, ptr7, ptr8);
            }
        }

        static void FillBuffers(double deltaTime, IntPtr ptr0, IntPtr ptr1, IntPtr ptr2, IntPtr ptr3, IntPtr ptr4, IntPtr ptr5, IntPtr ptr6, IntPtr ptr7, IntPtr ptr8)
        {
            //ptr1, ptr5 - A
            //ptr2, ptr6 - D
            //ptr3, ptr7 - W
            //ptr4 - S
            if (isFree1)
                FillBuffer(ptr1, playerAngle - 1.5 * deltaTime, playerX, playerY);
            else
                FillBuffer(ptr5, playerAngle - 1.5 * deltaTime, playerX, playerY);
            if (isFree2)
                FillBuffer(ptr2, playerAngle + 1.5 * deltaTime, playerX, playerY);
            else
                FillBuffer(ptr6, playerAngle + 1.5 * deltaTime, playerX, playerY);
            if (isFree3)
                FillBuffer(ptr3, playerAngle, playerX + Math.Sin(playerAngle) * 5 * deltaTime, playerY + Math.Cos(playerAngle) * 5 * deltaTime);
            else
                FillBuffer(ptr7, playerAngle, playerX + Math.Sin(playerAngle) * 5 * deltaTime, playerY + Math.Cos(playerAngle) * 5 * deltaTime);
            if (isFree4)
                FillBuffer(ptr4, playerAngle, playerX - Math.Sin(playerAngle) * 5 * deltaTime, playerY - Math.Cos(playerAngle) * 5 * deltaTime);
            else
                FillBuffer(ptr8, playerAngle, playerX - Math.Sin(playerAngle) * 5 * deltaTime, playerY - Math.Cos(playerAngle) * 5 * deltaTime);
        }

        //Функция для заполнения буфера
        static void FillBuffer(IntPtr buffer, double playerAngle, double playerX, double playerY)
        {
            uint k = 0;
            //Заполнение буферов
            for (int x = 0; x < screenWidth; x++)
            {
                double rayAngle = (playerAngle - FOV / 2) + ((double)x / (double)screenWidth) * FOV;    //Направление луча

                //Расстояние до стены по лучу
                double distanceToWall = 0;
                bool rayHitWall = false;

                //Координаты единичного вектора луча
                double eyeX = Math.Sin(rayAngle);
                double eyeY = Math.Cos(rayAngle);

                //Цикл на пока не столкнулись со стеной или не вышли за радиус видимости
                while (!rayHitWall && distanceToWall < visibleDepth)
                {
                    distanceToWall += 0.1;

                    int tmpX = (int)(playerX + eyeX * distanceToWall);
                    int tmpY = (int)(playerX + eyeX * distanceToWall);

                    //Проверка на выход за зону
                    if (tmpX < 0 || tmpY < 0 || tmpX > mapWidth || tmpY > mapHeight)
                    {
                        rayHitWall = true;
                        distanceToWall = visibleDepth;
                    }
                    else if (map[tmpY * mapWidth + tmpX] == '#')
                        rayHitWall = true;
                }

                //Вычисляем координаты начала и конца стенки по формулам
                int ceiling = (int)((double)(screenHeight / 2.0) - (double)screenHeight / distanceToWall);
                int floor = screenHeight - ceiling;

                char shade;
                if (distanceToWall <= visibleDepth / 3.0f)
                    shade = Convert.ToChar(0x2588); // Если стенка близко, то рисуем 
                else if (distanceToWall < visibleDepth / 2.0f)
                    shade = Convert.ToChar(0x2593); // светлую полоску
                else if (distanceToWall < visibleDepth / 1.5f)
                    shade = Convert.ToChar(0x2592); // Для отдалённых участков 
                else if (distanceToWall < visibleDepth)
                    shade = Convert.ToChar(0x2591); // рисуем более темную
                else
                    shade = ' ';

                //Отрисовка по ОСИ y
                for (int y = 0; y < screenHeight; y++)
                {
                    if (y <= ceiling)
                    {
                        //SetConsoleCursorPosition(buffer, new Coord((short)x, (short)y));
                        //WriteConsole(buffer, " ", 1, out k, IntPtr.Zero);
                        bufferChar[(short)x, (short)y] = ' ';
                    }
                    else if (y > ceiling && y <= floor)
                    {
                        //SetConsoleCursorPosition(buffer, new Coord((short)x, (short)y));
                        //WriteConsole(buffer, shade.ToString(), 1, out k, IntPtr.Zero);
                        bufferChar[(short)x, (short)y] = shade;
                    }
                    else
                    {
                        // То же самое с полом - более близкие части рисуем более заметными символами
                        double b = 1.0 - ((double)y - screenHeight / 2.0) / ((double)screenHeight / 2.0);
                        if (b < 0.25)
                            shade = '#';
                        else if (b < 0.5)
                            shade = 'x';
                        else if (b < 0.75)
                            shade = '~';
                        else if (b < 0.9)
                            shade = '-';
                        else
                            shade = ' ';

                        //SetConsoleCursorPosition(buffer, new Coord((short)x, (short)y));
                        //WriteConsole(buffer, shade.ToString(), 1, out k, IntPtr.Zero);
                        bufferChar[(short)x, (short)y] = shade;
                    }
                }
            }

            //Отрисовки
            for (int y = 0; y < screenHeight; y++)
            {
                for (int x = 0; x < screenWidth; x++)
                {
                    WriteConsole(buffer, bufferChar[(short)x, (short)y].ToString(), 1, out k, IntPtr.Zero);
                }
            }

            //Отрисовка краты и параметров
            /*string stats = "X=" + playerX + ", Y= " + playerY + ", A=" + playerAngle;
            SetConsoleCursorPosition(buffer, new Coord(0, 0));
            WriteConsole(buffer, stats, (uint)stats.Length, out k, IntPtr.Zero);*/

            /*Console.SetCursorPosition(0, 1);
            for (int i = 0; i < map.Length; i++)
            {
                if (i != 0)
                    if (i % mapWidth == 0)
                        Console.Write('\n');
                if (i == (int)playerX + (int)playerY * mapWidth)
                    Console.Write('P');
                Console.Write(map[i]);
            }*/
        }
    }
}
