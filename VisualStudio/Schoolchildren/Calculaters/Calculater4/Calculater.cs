using System;
using System.Linq;
using System.Threading;


namespace Calculater4
{
    public sealed class Calculater
    {
        private static Calculater calculater = null;

        private Calculater() { }

        public static Calculater CreateCalculater()
        {
            if (calculater == null)
                calculater = new Calculater();
            return calculater;
        }

        public void Start()
        {
            Hello();
            Help();
            Cycle();
            End();
        }

        private void Hello()
        {
            int positionCursor = Console.CursorTop;

            string[] incoming = new string[] { "\t                                                                   ",
                                               "\t                                                                   ",
                                               "\t-------------------------------------------------------------------",
                                               "\t- #   #   #     #   #    #   # #   #   #    ### ##### ##### ##### -",
                                               "\t- #  #   # #   # #  #    #  #   #  #  # #  #  #   #   #   # #   # -",
                                               "\t- # #   #   # #   # #    # #     ### #   #  ###   #   #   # ##### -",
                                               "\t- ##    ##### #   # #### ##        # #   #   ##   #   #   # #     -",
                                               "\t- # #   #   # #   # #  # # #       # #   #  # #   #   #   # #     -",
                                               "\t- #  #  #   # #   # #### #  #    ##  #   # #  #   #   ##### #     -",
                                               "\t-                                                                 -",
                                               "\t-------------------------------------------------------------------"};

            int callStr = 1;
            for (int i = 0; i < incoming.Length; i++)
            {
                for (int j = i; j >= 0; j--)
                {
                    Console.SetCursorPosition(0, j);
                    Console.Write(incoming[incoming.Length - callStr]);
                    callStr++;
                }
                callStr = 1;
                Thread.Sleep(500);
            }

            Console.SetCursorPosition(0, positionCursor + incoming.Length + 1);
        }

        private void Help()
        {
            Console.WriteLine("Введите выражение в формате: а + b.");
            Console.WriteLine("Где, а и b простые числа либо римские цифры.");
            Console.WriteLine("После чего нажмите ввод(клавиша - enter).");
            Console.WriteLine("Для выхода введите \"exit\".");
            Console.WriteLine("Для отображения подсказки введите \"help\".\n");
        }

        private void Cycle()
        {
            while (true)
            {
                string question = Console.ReadLine();

                question = question.ToUpper();

                if(question == "EXIT")
                {
                    return;
                }
                if(question == "HELP")
                {
                    Help();
                    continue;
                }

                try
                {
                    if (!CheckQuestion(question))
                    {
                        Console.WriteLine("Проверьте формат записи числа!");
                        continue;
                    }
                }
                catch (ArgumentNullException exception)
                {
                    Console.WriteLine(exception.Message);
                    continue;
                }
                catch (ArgumentException exception)
                {
                    Console.WriteLine(exception.Message);
                    continue;
                }
                catch (Exception exception)
                {
                    Console.WriteLine("Непредвиденная ошибка!\n" + exception.Message);
                    continue;
                }

                string separator = "";
                string firstNumber = "";
                string secondNumber = "";
                if (!SplitQuestion(question, ref separator, ref firstNumber, ref secondNumber))
                {
                    Console.WriteLine("Что-то пошло не так!");
                    continue;
                }

                ICalculated calc;
                if (RomanController.CheckNumber(firstNumber) && RomanController.CheckNumber(secondNumber))
                    calc = new RomanController();
                else if (ArabController.CheckNumber(firstNumber) && ArabController.CheckNumber(secondNumber))
                    calc = new ArabController();
                else
                {
                    Console.WriteLine("Не удалось преобразовать оба числа к одному виду!");
                    continue;
                }

                CalculateResult(separator, firstNumber, secondNumber, calc);
            }
        }

        private bool CheckQuestion(string question)
        {
            if (question == null)
                throw new ArgumentNullException("Вводная строка была равна null");

            if (string.IsNullOrWhiteSpace(question))
                throw new ArgumentException("Строка примера является пустой!");

            question = question.Replace(" ", "").Replace("\t", "").Replace("\n", "").Replace("\r", "");   //TODO: заменять на регулярку

            char[] chars = {'Q','W','E','R','T','Y','U','O','P','A','S','D','F','G','H','J','K','Z','B','N',',','.',';','\'','[',']','`','~','\''};  //TODO: заменять на регулярку
            for (int i = 0; i < chars.Length; i++ )
            {
                if(question.Contains(chars[i]))
                    return false;
            }

            return true;
        }

        private bool SplitQuestion(string question, ref string separate, ref string firstNumber, ref string secondNumber)
        {
            char[] separators = { '+', '-', '*', '/' };
            for(int i = 0; i < separators.Length; i++)
            {
                string[] pr = question.Split(separators[i]);
                if(pr.Length > 1)
                {
                    separate = Convert.ToString(separators[i]);
                    firstNumber = pr[0];
                    secondNumber = pr[1];
                    return true;
                }
            }
            return false;
        }

        private void CalculateResult(string separator, string firstNumber, string secondNumber, ICalculated calc)
        {
            string result = "";
            switch (separator)
            {
                case "+":
                    result = calc.Summ(firstNumber, secondNumber);
                    break;
                case "-":
                    result = calc.Minus(firstNumber, secondNumber);
                    break;
                case "*":
                    result = calc.Multiply(firstNumber, secondNumber);
                    break;
                case "/":
                    result = calc.Difference(firstNumber, secondNumber);
                    break;
            }
            Console.WriteLine("Результат: " + result);
        }

        private void End()
        {
            Console.WriteLine("До свидания!");
        }
    }
}