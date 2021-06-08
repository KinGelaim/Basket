using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading;

namespace ExampleRandom
{
    class Program
    {
        static void Main(string[] args)
        {
            #region myRandom

            Console.WriteLine("---------MyRandom---------");

            double seed = DateTime.Now.Second;
            Console.WriteLine(seed);

            double max = 10;
            double min = 3;

            double random = seed / (59 / (max - min)) + min;
            
            Console.WriteLine(random);

            #endregion


            #region SeedRandom

            Console.WriteLine("---------SeedRandom---------");

            Random seedRandom = new Random(10);

            Console.WriteLine(seedRandom.Next());
            Console.WriteLine(seedRandom.Next());
            Console.WriteLine(seedRandom.Next());

            #endregion


            #region TrueSeedRandom

            Console.WriteLine("---------TrueSeedRandom---------");

            long trueSeed = DateTime.Now.Ticks;
            Console.WriteLine(trueSeed.ToString());

            Random trueSeedRandom = new Random((int)trueSeed);
            Console.WriteLine(trueSeedRandom.Next());
            Console.WriteLine(trueSeedRandom.Next());
            Console.WriteLine(trueSeedRandom.Next());

            #endregion


            #region ErrorRandom

            Console.WriteLine("---------ErrorRandom---------");
            for(int i = 0; i < 10; i++)
            {
                Random erroRand = new Random();
                Console.WriteLine(erroRand.Next());
                //Thread.Sleep(100);
            }

            #endregion


            #region TrueRandom

            Console.WriteLine("---------TrueRandom---------");
            Random trueRand = new Random();
            for (int i = 0; i < 10; i++)
            {
                Console.WriteLine(trueRand.Next());
                if (i % 100000 == 0)
                    trueRand = new Random();
            }

            #endregion
        }
    }
}
