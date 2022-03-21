using System;


namespace AbstractVsInterface.Second
{
    public abstract class Aircraft
    {
        public abstract string RegistrationCode { get; }
        public abstract string Type { get; }

        public virtual void GetAircraftInfo()
        {
            Console.WriteLine("Тип ЛА: " + Type);
        }
    }
}