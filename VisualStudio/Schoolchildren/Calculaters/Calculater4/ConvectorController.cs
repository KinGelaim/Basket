using System;
using System.Collections.Generic;


namespace Calculater4
{
    public static class ConvectorController
    {
        private static readonly int[] arrArab = { 1000, 900, 500, 400, 100, 90, 50, 40, 10, 9, 5, 4, 1 };
        private static readonly string[] arrRoman = { "M", "CM", "D", "CD", "C", "XC", "L", "XL", "X", "IX", "V", "IV", "I" };
        private static readonly Dictionary<char, int> romanDigits = new Dictionary<char, int>
        {
            {'I', 1},
            {'V', 5},
            {'X', 10},
            {'L', 50},
            {'C', 100},
            {'D', 500},
            {'M', 1000}
        };

        public static string ArabToRoman(int arabNumber)
        {
            int i = 0;
            string romanNumber = "";
            while (arabNumber > 0)
            {
                if (arrArab[i] <= arabNumber)
                {
                    arabNumber = arabNumber - arrArab[i];
                    romanNumber = romanNumber + arrRoman[i];
                }
                else
                    i++;
            }

            return romanNumber;
        }

        public static int RomanToArab(string romanNumber)
        {
            int total = 0;
            int prev = 0;
            int summPrev = 0;
            for (int i = 0; i < romanNumber.Length; i++)
            {
                int current = romanDigits[romanNumber[i]];
                if(i == romanNumber.Length - 1)
                {
                    if (current > prev)
                        total += current - summPrev;
                    else
                        total += current + summPrev;
                }
                else
                {
                    int next = romanDigits[romanNumber[i + 1]];
                    if(current > next)
                    {
                        if(current > prev)
                            total += current - summPrev;
                        else
                            total += current + summPrev;
                        summPrev = 0;
                    }
                    if(current <= next)
                    {
                        summPrev += current;
                    }
                }
                prev = current;
            }
            return total;
        }
    }
}