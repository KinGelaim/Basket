using System;


namespace AbstractVsInterface.First
{
    public class FirstBegin
    {
        public void Start()
        {
            Console.WriteLine("Это первый тест!!!!!");

            Car car = new Car();
            Truck truck = new Truck();
            Dog dog = new Dog();

            IMovable[] moves = new IMovable[] { car, truck, dog };
            for (int i = 0; i < moves.Length; i++)
                moves[i].Move();

            car.SetName("Машинка");
            truck.SetName("Трактор");
            car.PrintName();
            truck.PrintName();

            car.TestAbstractMethod();
            truck.TestAbstractMethod();
        }
    }
}