using System;


namespace AbstractVsInterface.Third
{
    public abstract class MyAbstractClass
    {
        public string S = "asd";
        public int A;

        public void Hello()
        {
            Console.WriteLine("Hello!");
        }

        public abstract void Bye();
    }
}