using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading;

namespace PoleChudes
{
    class Program
    {
        static void Main(string[] args)
        {
            //Название приложения
            Console.Title = "Поле Чудес!";

            //Вводные параметры
            string[] word = new string[] {"ЗАМОК", "ЁЛОЧКА"};
            string[] question = new string[] {"Не лает, не кусает, а в дом не пускает! Что это?", "Зимой и летом одним цветом! Что это?"};
            for (int i = 0; i < word.Length; i++)
            {
                word[i] = word[i].ToUpper();
            }

            //Выводим вступительную инфу
            string[] incoming = new string[] { "--------------------------------------------------------",
                                               "-                                                      -",
                                               "-   #### ####   #   ####    #  #  #  #   #   #### #### -",
                                               "-   #  # #  #  # #  #       #  #   # #  # #  #    #    -",
                                               "-   #  # #  # #   # ####    ####    ##  # #  #### #    -",
                                               "-   #  # #  # #   # #          #     # ##### #    #    -",
                                               "-   #  # #### #   # ####       #     # #   # #### #### -",
                                               "-                                                      -",
                                               "--------------------------------------------------------"};

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
            Console.SetCursorPosition(0, incoming.Length);
            Console.WriteLine();

            //Нумерация того, какое слово сейчас будем использовать
            int k = 0;
            //Random rand = new Random();
            //int k = rand.Next(0, word.Length - 1);
            while (true)
            {
                StartGame(question[k], word[k]);
                Console.WriteLine("Желаете продолжить игру? (Да/Нет)");
                string mainAnswer = Console.ReadLine();
                if (mainAnswer == "Нет")
                    break;
                k++;
                if (k >= word.Length)
                    k = 0;

                //k = rand.Next(0, word.Length - 1);
                //TODO: проверка на то, чтобы после рандома они не повторялись
            }

            Console.ReadKey();
        }

        //Проверка на наличие буквы в слове
        static bool LetterInWord(char letter, string word)
        {
            bool isInWord = false;
            for (int i = 0; i < word.Length; i++)
            {
                if(letter == word[i])
                {
                    isInWord = true;
                }
            }
            return isInWord;
        }

        //Завершение игры
        static void EndGame(string word, int count, int numberPlayer)
        {
            Console.WriteLine();
            Console.Write("И ЭТО \"");
            for (int i = 0; i < word.Length; i++)
            {
                if (i != word.Length - 1)
                    Console.Write(word[i] + " ");
                else
                    Console.Write(word[i]);
            }
            Console.WriteLine('"');
            Console.Write("\nПОЗДРАВЛЯЮ! ПОЛЬЗОВАТЕЛЬ " + numberPlayer + " ВЫЙГРАЛ ");
            Console.ForegroundColor = ConsoleColor.Red;
            Console.Write("НИЧЕГО");
            Console.ForegroundColor = ConsoleColor.Gray;
            Console.WriteLine("!");
            Console.WriteLine("Общее число введенных букв: " + count);
        }

        static void StartGame(string question, string word)
        {
            //Количество игроков
            int countPlayer = 0;
            while (countPlayer <= 0)
            {
                Console.Write("Введите кол-во игроков: ");
                countPlayer = Convert.ToInt32(Console.ReadLine());
            }
            int currentPlayer = 1;

            //Переменная для хранения процента отгаданых букв, относительно всего слова
            double percentComplete = 0;

            //Массив для хранения введенных букв
            List<char> answersList = new List<char>();

            //Выводим вопрос
            Console.WriteLine("Внимание вопрос!\n" + question + " (" + word.Length + " букв)");

            while (true)
            {
                double countCompleteLetter = 0;
                bool isEndGame = true;
                //Выводим искомое слово
                for (int i = 0; i < word.Length; i++)   //Цикл идущий по слову
                {
                    bool proverka = false;
                    //Создаем цикл, который будет идти по введенным буквам
                    for (int j = 0; j < answersList.Count; j++)
                    {
                        if (word[i] == answersList[j])
                        {
                            proverka = true;
                        }
                    }
                    if (proverka)
                    {
                        Console.Write(word[i] + " ");
                        countCompleteLetter++;
                    }
                    else
                    {
                        Console.Write("_ ");
                        isEndGame = false;
                    }
                }
                percentComplete = countCompleteLetter / word.Length;
                Console.WriteLine();

                //Заканчиваем игру, если слово отгадано
                if (isEndGame)
                {
                    EndGame(word, answersList.Count, currentPlayer);
                    break;
                }

                //Спрашиваем у пользователя букву
                if (percentComplete >= 0.5)
                    Console.Write("Пользователь " + currentPlayer + ", Ваша буква или всё слово сразу же: ");
                else
                    Console.Write("Пользователь " + currentPlayer + ", Ваша буква: ");
                string letter = Console.ReadLine();

                if (letter.Length == 0)
                {
                    Console.WriteLine("Введите хоть что-нибудь!");
                    continue;
                }

                //Проверка на кол-во введенных букв
                if (letter.Length > 1)
                {
                    if (letter == "Вопрос")
                    {
                        Console.WriteLine(question);
                    }
                    else if (letter == word)
                    {
                        EndGame(word, answersList.Count, currentPlayer);
                        break;
                    }
                    else
                    {
                        Console.WriteLine("Извините, но введите одну букву!");
                    }
                    continue;
                }

                //Проверка на чей дальше ход
                if(currentPlayer + 1 <= countPlayer)
                {
                    currentPlayer++;
                }
                else
                {
                    currentPlayer = 1;
                }

                char prChar = Convert.ToChar(letter.ToUpper());
                //Записываем нашу букву в массив
                bool isInList = false;
                for (int i = 0; i < answersList.Count; i++)
                {
                    if (answersList[i] == prChar)
                        isInList = true;
                }
                if (!isInList)
                {
                    answersList.Add(prChar);
                    if (LetterInWord(prChar, word))
                    {
                        Console.WriteLine("Откройте букву " + letter);
                        Console.Beep(2000, 400);
                    }
                    else
                    {
                        Console.WriteLine("Увы, но такой буквы в слове нет!");
                        Console.Beep(200, 300);
                    }
                }
                else
                {
                    Console.WriteLine("Извините, но такая буква уже была!");
                }
            }
        }
    }
}
