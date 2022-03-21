using System;


namespace AbstractVsInterface.Third
{
    public class ThirdBegin
    {
        public void Start()
        {
            Console.WriteLine("Это третий тест!!!!!");
            TestClass tc = new TestClass();
            tc.Hello();
            Console.WriteLine(tc.S);
            Console.WriteLine(tc.B);
            tc.Name();
            tc.Bye();
        }
    }
}