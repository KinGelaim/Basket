using System;


namespace AbstractVsInterface.First
{
    public class Dog : IMovable
    {
        public void Move()
        {
            Console.WriteLine("Перемещаем собачку!");
        }
    }
}