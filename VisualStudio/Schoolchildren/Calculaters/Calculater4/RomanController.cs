using System;
using System.Linq;


namespace Calculater4
{
    public sealed class RomanController : ICalculated
    {
        public static bool CheckNumber(string number)
        {
            char[] chars = { 'M', 'D', 'C', 'L', 'X', 'V', 'I' };
            for (int i = 0; i < number.Length; i++)
            {
                if (!chars.Contains(number[i]))
                    return false;
            }
            return true;
        }

        #region ICalculated

        public string Summ(string firstNumber, string secondNumber)
        {
            int a = ConvectorController.RomanToArab(firstNumber);
            int b = ConvectorController.RomanToArab(secondNumber);
            //Console.WriteLine(a.ToString());
            //Console.WriteLine(b.ToString());
            int r = a + b;
            return ConvectorController.ArabToRoman(r);
        }

        public string Minus(string firstNumber, string secondNumber)
        {
            int a = ConvectorController.RomanToArab(firstNumber);
            int b = ConvectorController.RomanToArab(secondNumber);
            int r = a - b;
            return ConvectorController.ArabToRoman(r);
        }

        public string Multiply(string firstNumber, string secondNumber)
        {
            int a = ConvectorController.RomanToArab(firstNumber);
            int b = ConvectorController.RomanToArab(secondNumber);
            int r = a * b;
            return ConvectorController.ArabToRoman(r);
        }

        public string Difference(string firstNumber, string secondNumber)
        {
            int a = ConvectorController.RomanToArab(firstNumber);
            int b = ConvectorController.RomanToArab(secondNumber);
            int r = a / b;
            return ConvectorController.ArabToRoman(r);
        }

        #endregion
    }
}