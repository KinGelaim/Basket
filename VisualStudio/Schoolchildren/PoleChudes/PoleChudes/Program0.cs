using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

namespace PoleChudes
{
    class Program0
    {
        static void Main(string[] args)
        {
            Console.Title = "Поле Чудес!";

            string word = "Замок";
            string question = "Не лает, не кусает, а в дом не пускает! Что это?";
            word = word.ToUpper();

            List<char> answerList = new List<char>();

            Console.WriteLine("Внимание вопрос!\n" + question + " (" + word.Length + " букв)");

            while (true)
            {
                bool isEndGame = true;
                for (int i = 0; i < word.Length; i++)
                {
                    bool proverka = false;
                    for (int j = 0; j < answerList.Count; j++)
                    {
                        if (word[i] == answerList[j])
                        {
                            proverka = true;
                        }
                    }
                    if (proverka)
                    {
                        Console.Write(word[i] + " ");
                    }
                    else
                    {
                        Console.Write("_ ");
                        isEndGame = false;
                    }
                }
                Console.WriteLine();

                if (isEndGame)
                {
                    Console.Write("\nПОЗДРАВЛЯЮ! ВЫ ВЫЙГРАЛИ ");
                    Console.ForegroundColor = ConsoleColor.Red;
                    Console.Write("НИЧЕГО");
                    Console.ForegroundColor = ConsoleColor.Gray;
                    Console.WriteLine("!");
                    Console.WriteLine();
                    break;
                }

                Console.Write("Ваша буква: ");
                string letter = Console.ReadLine();

                if (letter.Length > 1)
                {
                    if (letter == "Вопрос")
                    {
                        Console.WriteLine(question);
                    }
                    else
                    {
                        Console.WriteLine("Извините, но введите одну букву!");
                    }
                    continue;
                }

                char prChar = Convert.ToChar(letter.ToUpper());
                bool isInList = false;
                for (int i = 0; i < answerList.Count; i++)
                {
                    if(answerList[i] == prChar)
                    {
                        isInList = true;
                    }
                }
                if (!isInList)
                {
                    answerList.Add(prChar);
                }
                else
                {
                    Console.WriteLine("Извините, но такая буква уже была!");
                }
            }
        }
    }
}