using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading;

namespace MiniPinPong
{
    class Program
    {
        static int topHeight;   //Высота верхней части игрового поля
        static int bottomHeight;

        static int topPosY1;    //Верхняя позиция платформы 1
        static int topPosY2;    //Верхняя позиция платформы 2

        static int posBallX;    //Позиция мяча по X
        static int posBallY;    //Позиция мяча по Y

        static int dxPosBall;
        static int dyPosBall;

        static int speedBall = 100;     //Скорость мяча
        static int sizePlatform = 7;    //Размер платформы

        static bool isEndGame = false;  //Игра окончена

        //Переменная для фиксации отображения мяча, чтобы не прерывать его отрисовку
        static bool isPaintBall = false;

        static bool isPaintPlatform = false;

        static void Main(string[] args)
        {
            //Наименование приложения
            Console.Title = "Пин Понг";

            //Скрываем курсор
            Console.CursorVisible = false;

            //Размер буфера
            Console.SetBufferSize(120, 50);

            //Делаем максимальное значение открытым
            Console.SetWindowSize(120, 50);

            //Задаём параметры для игры
            topHeight = 3;                          
            bottomHeight = 40;

            posBallX = Console.BufferWidth / 2;
            posBallY = Console.BufferHeight / 2;

            topPosY1 = Console.BufferHeight / 3;
            topPosY2 = Console.BufferHeight / 3;

            //Рандомно задаём приращение (направление) мячика
            Random rand = new Random();
            dxPosBall = rand.Next(-1,1);
            dyPosBall = rand.Next(-1,1);
            if (dxPosBall == 0)
                dxPosBall = 1;
            if (dyPosBall == 0)
                dyPosBall = 1;

            //Отрисовка полей
            Console.SetCursorPosition(0, topHeight);
            for (int i = 0; i < Console.BufferWidth; i++)
            {
                Console.Write("♦");
            }
            Console.SetCursorPosition(0, bottomHeight);
            for (int i = 0; i < Console.BufferWidth; i++)
            {
                Console.Write("♦");
            }

            //Поток для управления платформами
            Thread thread = new Thread(() =>
            {
                ReadKeyConsole();
            });
            thread.Start();

            //Поток для управления мячиком
            Thread thread2 = new Thread(() =>
            {
                MoveBall();
            });
            thread2.Start();

            //Отрисуем поле, мяч и игроков
            while (true)
            {
                if (isPaintBall)
                    continue;
                isPaintPlatform = true;
                //Console.Clear();
                for (int i = 1; i < bottomHeight - 3; i++)
                {
                    Console.SetCursorPosition(0, topHeight + i);
                    Console.Write(" ");
                    Console.SetCursorPosition(Console.BufferWidth - 1, topHeight + i);
                    Console.Write(" ");
                }

                //Отрисовка плаформ
                if (topPosY1 <= topHeight)
                    topPosY1 = topHeight + 1;
                if (topPosY1 + sizePlatform >= bottomHeight)
                    topPosY1 = bottomHeight - sizePlatform;
                Console.SetCursorPosition(0, topPosY1);
                for (int i = 0; i < sizePlatform; i++)
                {
                    Console.WriteLine("♦");
                }

                if (topPosY2 <= topHeight)
                    topPosY2 = topHeight + 1;
                if (topPosY2 + sizePlatform >= bottomHeight)
                    topPosY2 = bottomHeight - sizePlatform;
                for (int i = 0; i < sizePlatform; i++)
                {
                    Console.SetCursorPosition(Console.BufferWidth - 1, topPosY2 + i);
                    Console.Write("♦");
                }

                if (isEndGame) { break; }

                Console.SetCursorPosition(0, bottomHeight + 2);
                isPaintPlatform = false;
                Thread.Sleep(50);
            }

            if(thread.IsAlive)
            {
                thread.Abort();
            }
            if (thread2.IsAlive)
            {
                thread2.Abort();
            }

            Console.SetCursorPosition(0, bottomHeight + 2);
            Console.WriteLine();
        }

        static void MoveBall()
        {
            while (true)
            {
                if (isPaintPlatform)
                    continue;
                isPaintBall = true;

                //Запомнили старую позицию консоли
                int oldConsolePosX = Console.CursorLeft;
                int oldConsolePosY = Console.CursorTop;

                //Запомнили старую позицию мячика
                int oldPosBallX = posBallX;
                int oldPosBallY = posBallY;

                //Сдвинули мяч
                posBallX += dxPosBall;
                posBallY += dyPosBall;

                //Закрасили старую позицию
                Console.SetCursorPosition(oldPosBallX, oldPosBallY);
                Console.Write(" ");

                //Отрисовка мяча
                Console.SetCursorPosition(posBallX, posBallY);
                Console.Write("o");

                //Вернули каретку консоли на место
                Console.SetCursorPosition(oldConsolePosX, oldConsolePosY);

                isPaintBall = false;

                //Проверка на удар об потолок или пол
                if (posBallY - 1 <= topHeight || posBallY + 1 >= bottomHeight)
                {
                    dyPosBall = -dyPosBall;
                }

                //Проверка на удар об платформу или же забитый гол
                if (posBallX <= 0)
                {
                    //Следующая ячейка пустата или платформа?
                    if (topPosY1 < posBallY && topPosY1 + sizePlatform > posBallY)
                    {
                        dxPosBall = -1 * dxPosBall;
                    }
                    else
                    {
                        isEndGame = true;
                        break;
                    }
                }
                if (posBallX >= Console.BufferWidth - 1)
                {
                    if (topPosY2 < posBallY && topPosY2 + sizePlatform > posBallY)
                    {
                        dxPosBall = -1 * dxPosBall;
                    }
                    else
                    {
                        isEndGame = true;
                        break;
                    }
                }
                Thread.Sleep(speedBall);
            }
        }
        
        static void ReadKeyConsole()
        {
            while (true)
            {
                ConsoleKeyInfo key = Console.ReadKey(true);
                switch (key.Key)
                {
                    case ConsoleKey.W:
                        topPosY1--;
                        break;
                    case ConsoleKey.S:
                        topPosY1++;
                        break;
                    case ConsoleKey.UpArrow:
                        topPosY2--;
                        break;
                    case ConsoleKey.DownArrow:
                        topPosY2++;
                        break;
                    default:
                        break;
                }
            }
        }
    }
}
