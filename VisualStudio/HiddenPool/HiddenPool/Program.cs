using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;


namespace HiddenPool
{
    public class Program
    {
        private static void Main(string[] args)
        {
            ArglistAndParams();

            RefKeywords();
            RefKeywordsUnsafe();
        }

        #region arglist

        private static void ArglistAndParams()
        {
            WithArglist(__arglist(2, 3, true, "string"));
            WithParams(2, 3, true, "string");
        }        

        // __arglist - указатель, где лежат параметры
        private static void WithArglist(__arglist)
        {
            //Console.WriteLine(nameof(WithArglist));

            var argIterator = new ArgIterator(__arglist);
            DumpArgs(argIterator);

            Console.WriteLine();
        }

        private static void DumpArgs(ArgIterator args)
        {
            while(args.GetRemainingCount() > 0)
            {
                TypedReference tr = args.GetNextArg();
                var obj = TypedReference.ToObject(tr);
                var type = TypedReference.GetTargetType(tr);
                Console.WriteLine(obj  + " / " + type);
            }
        }

        // params - массив!!!
        private static void WithParams(params object[] args)
        {
            //Console.WriteLine(nameof(WithParams));
            foreach (var arg in args)
            {
                Console.WriteLine(arg + " / " + arg.GetType());
            }
            Console.WriteLine();
        }

        #endregion

        #region makeref

        private static void RefKeywords()
        {
            double value = 10;
            TypedReference tr = __makeref(value);   // tr = &value;

            Console.WriteLine( __refvalue(tr, double));

            __refvalue(tr, double) = 11;    // *tr = 11;
            Console.WriteLine( __refvalue(tr, double));

            Type type = __reftype(tr);  // value.GetType();
            Console.WriteLine(type.Name);

            Console.WriteLine();
        }

        unsafe private static void RefKeywordsUnsafe()
        {
            double value = 10;
            double* tr = &value;

            Console.WriteLine(*tr);

            *tr = 11;
            Console.WriteLine(*tr);

            Console.WriteLine();

            Console.WriteLine();
        }

        #endregion

    }
}
