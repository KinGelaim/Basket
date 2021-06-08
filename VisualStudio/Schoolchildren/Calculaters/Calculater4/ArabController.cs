using System;


namespace Calculater4
{
    public sealed class ArabController : ICalculated
    {
        public static bool CheckNumber(string number)
        {
            int r = 0;
            return Int32.TryParse(number, out r);
        }

        #region ICalculated

        public string Summ(string firstNumber, string secondNumber)
        {
            int a = Int32.Parse(firstNumber);
            int b = Int32.Parse(secondNumber);
            return (a + b).ToString();
        }

        public string Minus(string firstNumber, string secondNumber)
        {
            int a = Int32.Parse(firstNumber);
            int b = Int32.Parse(secondNumber);
            return (a - b).ToString();
        }

        public string Multiply(string firstNumber, string secondNumber)
        {
            int a = Int32.Parse(firstNumber);
            int b = Int32.Parse(secondNumber);
            return (a * b).ToString();
        }

        public string Difference(string firstNumber, string secondNumber)
        {
            int a = Int32.Parse(firstNumber);
            int b = Int32.Parse(secondNumber);
            return (a / b).ToString();
        }

        #endregion
    }
}