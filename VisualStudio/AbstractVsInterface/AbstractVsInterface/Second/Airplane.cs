using System;


namespace AbstractVsInterface.Second
{
    // Класс Airplane является Aircraft (самолёт является летательным аппаратом)
    // Класс Airplane может делать то, что тербует делать интерфейс IPassengers
    public class Airplane : Aircraft, IPassengers
    {
        public override string RegistrationCode
        {
            get
            {
                return "RE-45618/9";
            }
        }

        public override string Type
        {
            get
            {
                return "Пасажирский самолёт СУ";
            }
        }

        public override void GetAircraftInfo()
        {
            base.GetAircraftInfo();
            Console.WriteLine("Бортовой номер: " + RegistrationCode);
        }

        public int PassengersCapacity
        {
            get { throw new NotImplementedException(); }
        }
    }
}