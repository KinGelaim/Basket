using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

namespace Mini3DGame
{
    class Program
    {
        const double PI = 3.14159;                  //Константа ПИ

        const double FOV = PI / 3;                  //Угол обзора
        const double visibleDepth = 30.0;           //Дальность видимости

        static int screenWidth = 80;      //Ширина экрана
        static int screenHeight = 40;                       //Высота экрана

        static double playerX = 1;                            //Позиция игрока
        static double playerY = 1;
        static double playerAngle = 0;                        //Угол поворота игрока

        static int mapHeight = 16;                         //Размер карты
        static int mapWidth = 16;

        static string map = "################"+
                            "#..............#"+
                            "#..............#"+
                            "#..............#"+
                            "#..............#"+
                            "#..............#"+
                            "#..............#"+
                            "#..............#"+
                            "#..............#"+
                            "#..............#"+
                            "#..............#"+
                            "#..............#"+
                            "#..............#"+
                            "#..............#"+
                            "#..............#"+
                            "################";

        static void Main(string[] args)
        {
            Console.BufferWidth = screenWidth;
            Console.BufferHeight = screenHeight;

            Console.WindowWidth = screenWidth;
            Console.WindowHeight = screenHeight;

            DateTime timeDuration1 = DateTime.Now;
            DateTime timeDuration2 = DateTime.Now;

            while (true)
            {
                //Находим ДельтаТайм
                /*timeDuration2 = DateTime.Now;
                TimeSpan deltaTime = timeDuration2 - timeDuration1;
                timeDuration1 = timeDuration2;*/
                double deltaTime = 0.1;

                //Считываем клавишу
                ConsoleKey key = Console.ReadKey(true).Key;
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
                        if(map[(int)playerX + (int)playerY*mapWidth] == '#')
                        {
                            playerX -= Math.Sin(playerAngle) * 5 * deltaTime;
                            playerY -= Math.Cos(playerAngle) * 5 * deltaTime;
                        }
                        break;
                    case ConsoleKey.S:
                        playerX -= Math.Sin(playerAngle) * 5 * deltaTime;
                        playerY -= Math.Cos(playerAngle) * 5 * deltaTime;
                        if(map[(int)playerX + (int)playerY*mapWidth] == '#')
                        {
                            playerX += Math.Sin(playerAngle) * 5 * deltaTime;
                            playerY += Math.Cos(playerAngle) * 5 * deltaTime;
                        }
                        break;
                    default: 
                        continue;
                }

                //Действия
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
                    while(!rayHitWall && distanceToWall < visibleDepth)
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
                            Console.CursorTop = y;
                            Console.CursorLeft = x;
                            Console.Write(' ');
                        }
                        else if (y > ceiling && y <= floor)
                        {
                            Console.CursorTop = y;
                            Console.CursorLeft = x;
                            Console.Write(shade);
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

                            Console.CursorTop = y;
                            Console.CursorLeft = x;
                            Console.Write(shade);
                        }
                    }
                }

                //Отрисовка краты и параметров
                Console.SetCursorPosition(0, 0);
                Console.Write("X=" + playerX + ", Y= " + playerY + ", A=" + playerAngle);
                Console.SetCursorPosition(0, 1);
                for (int i = 0; i < map.Length; i++)
                {
                    if (i != 0)
                        if (i % mapWidth == 0)
                            Console.Write('\n');
                    if (i == (int)playerX + (int)playerY * mapWidth)
                        Console.Write('P');
                    Console.Write(map[i]);
                }
            }
        }
    }
}
