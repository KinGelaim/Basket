using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

namespace ClassLibraryOnlineGame2
{
    public class MainGame
    {
        public Hero firstHero { get; set; }
        public Hero secondHero { get; set; }

        public List<Bullet> bulletsList = new List<Bullet>();

        public List<Enemy> enemyList = new List<Enemy>();

        public MainGame(Hero firstHero, Hero secondHero)
        {
            this.firstHero = firstHero;
            this.secondHero = secondHero;
        }
    }

    public class Mob
    {
        public int posX { get; set; }
        public int posY { get; set; }
        public int life { get; set; }
    }

    public class Hero : Mob
    {
        public int score { get; set; }
        public Hero(int posX, int posY)
        {
            this.posX = posX;
            this.posY = posY;
            this.life = 3;
            this.score = 0;
        }
    }

    public class Enemy : Mob
    {
        public int speed { get; set; }

        public static List<Enemy> enemyList = new List<Enemy>();

        public Enemy(int posX, int posY, int life, int speed = 1)
        {
            this.posX = posX;
            this.posY = posY;
            this.life = life;
            this.speed = speed;
        }

        public void translateEnemy(Hero firstHero, Hero secondHero)
        {
            if (Math.Sqrt(Math.Pow(posX - firstHero.posX, 2) + Math.Pow(posY - firstHero.posY, 2)) >= Math.Sqrt(Math.Pow(posX - secondHero.posX, 2) + Math.Pow(posY - secondHero.posY, 2)))
            {
                //Второй ближе, чем первый (двигаемся ко второму)
                if (posX > secondHero.posX)
                    posX -= speed;
                else if (posX < secondHero.posX)
                    posX += speed;
                if (posY > secondHero.posY)
                    posY -= speed;
                else if (posY < secondHero.posY)
                    posY += speed;
                if (posX == secondHero.posX && posY + 24 >= secondHero.posY && posY < secondHero.posY)
                    if (secondHero.posY + 20 < 412)
                        secondHero.posY += 20;
                    else if (secondHero.posX - 20 > 0)
                        secondHero.posX -= 20;
                    else
                        secondHero.posX += 20;
                if (posX - 4 <= secondHero.posX + 20 && posY == secondHero.posY && posX > secondHero.posX + 20)
                    if (secondHero.posX - 20 > 0)
                        secondHero.posX -= 20;
                    else if (secondHero.posY - 20 > 0)
                        secondHero.posY -= 20;
                    else
                        secondHero.posY += 20;
                if (posX == secondHero.posX && posY - 4 <= secondHero.posY + 20 && posY > secondHero.posY + 20)
                    if (secondHero.posY - 20 > 0)
                        secondHero.posY -= 20;
                    else if (secondHero.posX + 20 < 1037)
                        secondHero.posX += 20;
                    else
                        secondHero.posX -= 20;
                if (posX + 24 >= secondHero.posX && posY == secondHero.posY && posX + 20 < secondHero.posX)
                    if (secondHero.posX + 20 < 1037)
                        secondHero.posX += 20;
                    else if (secondHero.posY + 20 < 412)
                        secondHero.posY += 20;
                    else
                        secondHero.posY -= 20;
            }
            else
            {
                //Первый ближе
                if (posX > firstHero.posX)
                    posX -= speed;
                else if (posX < firstHero.posX)
                    posX += speed;
                if (posY > firstHero.posY)
                    posY -= speed;
                else if (posY < firstHero.posY)
                    posY += speed;
                if (posX == firstHero.posX && posY + 24 >= firstHero.posY && posY < firstHero.posY)
                    if (firstHero.posY + 20 < 412)
                        firstHero.posY += 20;
                    else if (firstHero.posX - 20 > 0)
                        firstHero.posX -= 20;
                    else
                        firstHero.posX += 20;
                if (posX - 4 <= firstHero.posX + 20 && posY == firstHero.posY && posX > firstHero.posX + 20)
                    if (firstHero.posX - 20 > 0)
                        firstHero.posX -= 20;
                    else if (firstHero.posY - 20 > 0)
                        firstHero.posY -= 20;
                    else
                        firstHero.posY += 20;
                if (posX == firstHero.posX && posY - 4 <= firstHero.posY + 20 && posY > firstHero.posY + 20)
                    if (firstHero.posY - 20 > 0)
                        firstHero.posY -= 20;
                    else if (firstHero.posX + 20 < 1037)
                        firstHero.posX += 20;
                    else
                        firstHero.posX -= 20;
                if (posX + 24 >= firstHero.posX && posY == firstHero.posY && posX + 20 < firstHero.posX)
                    if (firstHero.posX + 20 < 1037)
                        firstHero.posX += 20;
                    else if (firstHero.posY + 20 < 412)
                        firstHero.posY += 20;
                    else
                        firstHero.posY -= 20;
            }
        }
    }

    public static class EnemyAndBullet
    {
        public static void checkShot(Hero firstHero, Hero secondHero)
        {
            foreach(Bullet bullet in Bullet.bulletsList.ToArray())
                foreach(Enemy enemy in Enemy.enemyList.ToArray())
                    if (bullet.posX + 5 > enemy.posX && bullet.posX + 5 < enemy.posX + 20 && bullet.posY + 5 > enemy.posY && bullet.posY + 5 < enemy.posY + 20
                        || bullet.posX > enemy.posX && bullet.posX < enemy.posX + 20 && bullet.posY + 5 > enemy.posY && bullet.posY + 5 < enemy.posY + 20
                        || bullet.posX + 5 > enemy.posX && bullet.posX + 5 < enemy.posX + 20 && bullet.posY > enemy.posY && bullet.posY < enemy.posY + 20
                        || bullet.posX > enemy.posX && bullet.posX < enemy.posX + 20 && bullet.posY > enemy.posY && bullet.posY < enemy.posY + 20)
                    {
                        if (bullet.color == 'R')
                            secondHero.score += 10;
                        else
                            firstHero.score += 10;
                        enemy.life--;
                        if (enemy.life <= 0)
                        {
                            Enemy.enemyList.Remove(enemy);
                        }
                        Bullet.bulletsList.Remove(bullet);
                    }
        }
    }

    public class Bullet
    {
        public int posX { get; set; }
        public int posY { get; set; }
        public char napr { get; set; }
        public char color { get; set; }

        public static List<Bullet> bulletsList = new List<Bullet>();

        public int endPosX { get; set; }
        public int endPosY { get; set; }
        public bool isLeft { get; set; }

        public Bullet()
        {

        }

        public Bullet(int posX, int posY, char napr, char color)
        {
            this.posX = posX;
            this.posY = posY;
            this.napr = napr;
            this.color = color;
        }

        public Bullet(int posX, int posY, int endPosX, int endPosY, char color, bool isLeft = false)
        {
            this.posX = posX;
            this.posY = posY;
            this.endPosX = endPosX;
            this.endPosY = endPosY;
            this.color = color;
            this.isLeft = isLeft;
        }

        public Bullet(int posX, int posY, char napr, char color, int endPosX, int endPosY, bool isLeft = false)
        {
            this.posX = posX;
            this.posY = posY;
            this.napr = napr;
            this.color = color;
            this.endPosX = endPosX;
            this.endPosY = endPosY;
            this.isLeft = isLeft;
        }

        public bool translateBullet()
        {
            if (endPosX != 0 && endPosY != 0)
            {
                if (isLeft)
                {
                    //Находим конечную точку (край экрана) и далее будем к ней просто смещаться (как в райт)
                    int prX = 1;
                    int prY = 1;
                    if (endPosX < posX && endPosY < posY)
                    {
                        prX = 1;
                        prY = 1;
                    }
                    else if (endPosX > posX && endPosY < posY)
                    {
                        prY = 1;
                        prX = 1040;
                    }
                    else if (endPosX > posX && endPosY > posY)
                    {
                        prY = 420;
                        prX = 1040;
                    }
                    else if (endPosX < posX && endPosY > posY)
                    {
                        prY = 420;
                        prX = 1;
                    }
                    double prEndX = (prY - posY) * (endPosX - posX) / (endPosY - posY) + posX;
                    double prEndY = (prX - posX) * (endPosY - posY) / (endPosX - posX) + posY;
                    if (prEndX > 0)
                    {
                        endPosY = prY;
                        endPosX = Convert.ToInt32(Math.Ceiling(prEndX));
                    }
                    else
                    {
                        endPosX = prX;
                        endPosY = Convert.ToInt32(Math.Ceiling(prEndY));
                    }
                    //Console.WriteLine("Конец: " + endPosX + " " + endPosY);
                    isLeft = false;
                }
                if (posX > endPosX)
                    posX -= 5;
                else if (posX < endPosX)
                    posX += 5;
                if (posY > endPosY)
                    posY -= 5;
                else if (posY < endPosY)
                    posY += 5;
                if (posX + 4 >= endPosX && posX - 4 <= endPosX && posY + 4 >= endPosY && posY - 4 <= endPosY)
                    return false;
                else
                    return true;
            }
            else
                switch (napr)
                {
                    case 'U':
                        if (posY - 5 > 0)
                        {
                            posY -= 5;
                            return true;
                        }
                        break;
                    case 'D':
                        if (posY + 5 < 412)
                        {
                            posY += 5;
                            return true;
                        }
                        break;
                    case 'L':
                        if (posX - 5 > 0)
                        {
                            posX -= 5;
                            return true;
                        }
                        break;
                    case 'R':
                        if (posX + 5 < 1037)
                        {
                            posX += 5;
                            return true;
                        }
                        break;
                    case 'Q':
                        if (posY - 5 > 0 && posX - 5 > 0)
                        {
                            posY -= 4;
                            posX -= 4;
                            return true;
                        }
                        break;
                    case 'E':
                        if (posY - 5 > 0 && posX + 5 <1037)
                        {
                            posY -= 4;
                            posX += 4;
                            return true;
                        }
                        break;
                    case 'Z':
                        if (posY + 5 < 412 && posX - 5 > 0)
                        {
                            posY += 4;
                            posX -= 4;
                            return true;
                        }
                        break;
                    case 'C':
                        if (posY + 5 < 412 && posX + 5 < 1037)
                        {
                            posY += 4;
                            posX += 4;
                            return true;
                        }
                        break;
                    default: break;
                }
            return false;
        }
    }
}