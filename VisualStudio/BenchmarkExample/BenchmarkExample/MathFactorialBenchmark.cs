using BenchmarkDotNet.Attributes;
using BenchmarkDotNet.Order;


namespace BenchmarkExample
{
    [MemoryDiagnoser]
    [Orderer(SummaryOrderPolicy.FastestToSlowest)]
    [RankColumn]
    public class MathFactorialBenchmark
    {
        private const int NUMBER = 10;
        private static readonly MathFactorial factorial = new MathFactorial();

        [Benchmark(Baseline = true)]
        public void CalcCycleForFactorial()
        {
            factorial.CalcCycleForFactorial(NUMBER);
        }

        [Benchmark]
        public void CalcRecursiveFactorial()
        {
            factorial.CalcRecursiveFactorial(NUMBER);
        }

        [Benchmark]
        public void FactTree()
        {
            factorial.FactTree(NUMBER);
        }

        [Benchmark]
        public void FactFactor()
        {
            factorial.FactFactor(NUMBER);
        }
    }
}
