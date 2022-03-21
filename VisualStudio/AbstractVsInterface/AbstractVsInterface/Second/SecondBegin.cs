using System;


namespace AbstractVsInterface.Second
{
    public class SecondBegin
    {
        public void Start()
        {
            Console.WriteLine("Это второй тест!!!!!");
            Airplane a = new Airplane();
            a.GetAircraftInfo();
        }
    }
}