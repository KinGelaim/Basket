using System;
using System.Collections.Generic;
using System.Numerics;


namespace BenchmarkExample
{
    public class MathFactorial
    {
        public BigInteger CalcCycleForFactorial(int number)
        {
            BigInteger result = 1;
            for (int i = 1; i <= number; i++)
            {
                result *= i;
            }
            return result;
        }

        public BigInteger CalcRecursiveFactorial(BigInteger number)
        {
            BigInteger result = number;
            if (result > 1)
            {
                result *= CalcRecursiveFactorial(--result);
            }
            return result;
        }

        private BigInteger ProdTree(int l, int r)
        {
            if (l > r)
                return 1;
            if (l == r)
                return l;
            if (r - l == 1)
                return (long)l * r;
            int m = (l + r) / 2;
            return ProdTree(l, m) * ProdTree(m + 1, r);
        }

        public BigInteger FactTree(int n)
        {
            if (n < 0)
                return 0;
            if (n == 0)
                return 1;
            if (n == 1 || n == 2)
                return n;
            return ProdTree(2, n);
        }

        public BigInteger FactFactor(int n)
        {
            if (n < 0)
                return 0;
            if (n == 0)
                return 1;
            if (n == 1 || n == 2)
                return n;
            bool[] u = new bool[n + 1]; // маркеры для решета Эратосфена
            List<Tuple<int, int>> p = new List<Tuple<int, int>>(); // множители и их показатели степеней
            for (int i = 2; i <= n; ++i)
                if (!u[i]) // если i - очередное простое число
                {
                    // считаем показатель степени в разложении
                    int k = n / i;
                    int c = 0;
                    while (k > 0)
                    {
                        c += k;
                        k /= i;
                    }
                    // запоминаем множитель и его показатель степени
                    p.Add(new Tuple<int, int>(i, c));
                    // просеиваем составные числа через решето               
                    int j = 2;
                    while (i * j <= n)
                    {
                        u[i * j] = true;
                        ++j;
                    }
                }
            // вычисляем факториал
            BigInteger r = 1;
            for (int i = p.Count - 1; i >= 0; --i)
                r *= BigInteger.Pow(p[i].Item1, p[i].Item2);
            return r;
        }
    }
}
