using System;


namespace AbstractVsInterface.First
{
    public class Truck : Vehicle, IMovable
    {
        public override void PrintName()
        {
            Console.Write("Имя: ");
            base.PrintName();
        }

        public void Move()
        {
            Console.WriteLine("Перемещаем трактор!");
        }

        public override void TestAbstractMethod()
        {
            Console.WriteLine("Абстрактный метод трактора!");
        }
    }
}