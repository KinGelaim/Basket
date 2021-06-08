using System;
using System.Numerics;
using BenchmarkDotNet.Running;


namespace BenchmarkExample
{
    public class Program
    {
        private static void Main(string[] args)
        {
            Console.WindowWidth = 170;
            Console.BufferWidth = 170;
            //BenchmarkRunner.Run<DateParserBenchmark>();
            BenchmarkRunner.Run<MathFactorialBenchmark>();
        }
    }
}
