using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

namespace ExamplePoly
{
    public class Program
    {
        private static void Main(string[] args)
        {
            #region Сокрытие

            Console.WriteLine("---------");
            A a = new A();
            B b = new B();
            C c = new C();
            a.Print();
            b.Print();
            c.Print();

            #endregion

            #region Polym

            Console.WriteLine("---------");
            A a2 = new A();
            B b2 = new B();
            A c2 = new C();
            a2.PrintN();
            b2.PrintN();
            c2.PrintN();

            #endregion

            #region Ситуация

            Console.WriteLine("---------");
            A[] arrA = { a };
            B[] arrB = { b };   // Приводятся к типу
            C[] arrC = { c };

            for (int i = 0; i < arrA.Length; i++)
            {
                arrA[i].PrintN();
            }
            for (int i = 0; i < arrB.Length; i++)
            {
                arrB[i].PrintN();
            }
            for (int i = 0; i < arrC.Length; i++)
            {
                arrC[i].PrintN();
            }

            Console.WriteLine("---------");
            A[] arrAll = { a, b, c };
            for (int i = 0; i < arrAll.Length; i++)
            {
                arrAll[i].PrintN();
            }

            Console.WriteLine("---------");
            for (int i = 0; i < arrAll.Length; i++)
            {
                arrAll[i].PrintV();
            }

            #endregion

            #region interface

            D d = new D();
            E e = new E();
            F f = new F();
            IExample[] ex = { d, e, f };
            for(int i = 0; i < ex.Length; i++)
            {
                ex[i].Print();
            }

            #endregion
        }
    }

    #region Class
    public class A
    {
        public void Print()
        {
            Console.WriteLine("Это Print из A");
        }

        public void PrintN()
        {
            Console.WriteLine("Это PrintN из A");
        }

        public virtual void PrintV()
        {
            Console.WriteLine("Это PrintV из A");
        }
    }

    public class B : A
    {
        public new void PrintN()
        {
            Console.WriteLine("Это PrintN из B");
        }

        public override void PrintV()
        {
            Console.WriteLine("Это PrintV из B");
        }
    }

    public class C : A
    {
        public void PrintN()
        {
            Console.WriteLine("Это PrintN из C");
        }

        public override void PrintV()
        {
            Console.WriteLine("Это PrintV из C");
        }
    }
    #endregion

    #region Interface

    interface IExample
    {
        void Print();
    }

    class D : IExample
    {
        public void Print()
        {
            Console.WriteLine("D");
        }
    }

    class E : IExample
    {
        public void Print()
        {
            Console.WriteLine("E");
        }
    }

    class F : IExample
    {
        /**/
        public void Print()
        {
            Console.WriteLine("F");
        }
    }

    #endregion
}
