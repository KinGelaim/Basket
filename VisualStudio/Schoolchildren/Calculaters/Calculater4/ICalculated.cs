namespace Calculater4
{
    public interface ICalculated
    {
        string Summ(string firstNumber, string secondNumber);
        string Minus(string firstNumber, string secondNumber);
        string Multiply(string firstNumber, string secondNumber);
        string Difference(string firstNumber, string secondNumber);
    }
}