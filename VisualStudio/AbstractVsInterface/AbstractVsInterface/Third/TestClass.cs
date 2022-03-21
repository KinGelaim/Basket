using System;


namespace AbstractVsInterface.Third
{
    public class TestClass : MyAbstractClass, IMyInterface
    {
        public override void Bye()
        {
            Console.WriteLine("Пока");
        }

        public new string S
        {
            get
            {
                return "asd";
            }
        }

        public int B
        {
            get
            {
                return 4;
            }
        }


        public void Name()
        {
            Console.WriteLine("Имя");
        }
    }
}