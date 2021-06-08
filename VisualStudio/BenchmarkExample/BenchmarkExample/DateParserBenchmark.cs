using BenchmarkDotNet.Attributes;
using BenchmarkDotNet.Order;


namespace BenchmarkExample
{
    [MemoryDiagnoser]
    [Orderer(SummaryOrderPolicy.FastestToSlowest)]
    [RankColumn]
    public class DateParserBenchmark
    {
        private const string DateTime = "2020-11-06T16:33:06Z";
        private static readonly DateParser parser = new DateParser();

        [Benchmark(Baseline = true)]
        public void GetYearFromDateTime()
        {
            parser.GetYearFromDateTime(DateTime);
        }

        [Benchmark]
        public void GetYearFromSplit()
        {
            parser.GetYearFromSplit(DateTime);
        }

        [Benchmark]
        public void GetYearFromSubstring()
        {
            parser.GetYearFromSubstring(DateTime);
        }

        [Benchmark]
        public void GetYearFromSpan()
        {
            parser.GetYearFromSpan(DateTime.ToCharArray());
        }

        [Benchmark]
        public void GetYearFromSpanWithManualConversion()
        {
            parser.GetYearFromSpanWithManualConversion(DateTime.ToCharArray());
        }
    }
}
