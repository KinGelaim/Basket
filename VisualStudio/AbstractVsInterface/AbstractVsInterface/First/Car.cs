using System;


namespace AbstractVsInterface.First
{
    public class Car : Vehicle, IMovable
    {
        public void Move()
        {
            Console.WriteLine("Перемещаем машинку со скоростью: " + base.GetCurrentSpeed());
        }

        public override void TestAbstractMethod()
        {
            Console.WriteLine("Абстрактный метод машинки!");
        }
    }
}