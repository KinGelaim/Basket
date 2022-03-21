using System;


namespace AbstractVsInterface.First
{
    public abstract class Vehicle
    {
        private readonly int BEGIN_SPEED = 3;

        public int Weight { get; set; }

        protected string name;

        public void SetName(string name)
        {
            this.name = name; 
        }

        public virtual void PrintName()
        {
            Console.WriteLine(name);
        }

        protected double GetCurrentSpeed()
        {
            return BEGIN_SPEED * Math.PI;
        }

        public abstract void TestAbstractMethod();
    }
}