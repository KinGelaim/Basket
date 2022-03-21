using System;
using System.Collections.Generic;
using System.Text;


namespace Huffman
{
    class Program
    {
        /*
         * Принцип работы алгоритма Хаффмана:
         * У нас имеется строка aabaacca и мы хотим её закодировать с помощью двоичных кодов
         * Согласно таблицы ASCII получаем: 01000001 01000001 01000010 01000001 01000001 01000011 01000011 01000001
         * Теперь ставим перед собой задачу сжать информацию
         * В сообщению присутствуют всего три значения. Для кодирования каждого значения используется целый байт.
         * Чтобы закодировать всего три значения достаточно двух бит. Следовательно представление является избыточным.
         * Задаём: a - 01, b - 11, c - 10
         * 01 01 11 01 01 10 10 01 стока стала занимать 16 бит, вместо 64 - уже сжали в 4 раза без потери информации
         * Двумя битами можно описать 4 разных значения, а у нас 3, следовательно можно ещё оптимизировать
         * Для оптимизации можно использовать коды не одинаковой длины, а разной.
         * Задаём: a - 0, b - 11, c - 10
         * Получаем: 0 0 11 0 0 10 10 0 // Уже 11 бит, вместо 16
         * Логично, что чем чаще встречается буква, тем меньше должен быть её код.
         * При этом не стоит забывать, что процесс раскодирования должен быть однозначным. Например, нельзя так: a - 1 b - 11 c - 10 (11 - это две a или одна b)
         * Для этого код каждого кодируемого элемента не должен являться началом (префексом) для кода другого элемента.
         * В этом и заключается алгоритм Хаффмана. Для его использования строится кодовое древо.
         * Кодовое дерево - это бинарное дерево, где листьями являются символы, которые нужно закодировать, а на ребрах дерева пишется 0 и 1.
         * Для построения оптимального кодового дерева мы генерируем список листов дерева, в каждом листе пишем символ и сколько он встречается.
         * Далее сортируем по убыванию. Берём два самых редко встречаемых листа, связываем их пустым узлом и вписываем в него сумму частот связуемых листьев.
         * Теперь добавляем наш созданный узел в список всех узлов и снова повторяем операцию. Пока в списке не останется один узел.
         * Получаем коды оптимальные для сжатия.
         * 
         * */

        static void Main(string[] args)
        {
            // Исходная строка
            string text = "My name is Misha";

            // Частота повторения каждого символа
            Dictionary<char, int> frequency = CountFrequency(text);

            // Исходное дерево
            List<TreeNode> tree = new List<TreeNode>();
            foreach(KeyValuePair<char, int> ch in frequency)
            {
                tree.Add(new TreeNode(ch.Key, ch.Value));
            }

            // Кодовое дерево созданное по алгоритму Хаффмана
            TreeNode codeTree = Huffman(tree);

            // Словарик для пар символ и его код
            Dictionary<char, string> codes = new Dictionary<char, string>();

            // Извлечение кода каждого символа из кодового дерева
            foreach (char ch in frequency.Keys)
            {
                codes.Add(ch, codeTree.GetCodeForCharacter(ch, ""));
            }

            // Вывод таблицы на экран
            foreach (KeyValuePair<char, string> keyValue in codes)
            {
                Console.WriteLine("Символ: " + keyValue.Key + "\tКод:" + keyValue.Value);
            }

            // Кодирование строки
            StringBuilder encoded = new StringBuilder();
            for(int i = 0; i < text.Length; i++)
            {
                string code = null;
                codes.TryGetValue(text[i], out code);
                encoded.Append(code);
            }

            // Результаты кодирования
            Console.WriteLine("Исходная строка: " + text);
            Console.WriteLine("Размер исходной строки: " + text.Length * 8 + " бит");
            Console.WriteLine("Размер сжатой строки: " + encoded.Length + " бит");
            Console.WriteLine("Сжатая строка: " + encoded);

            // Раскодирование строки
            string decoded = HuffmanDecode(encoded.ToString(), codeTree);

            Console.WriteLine("Расшифрованная строка: " + decoded);
        }

        // Метод для подсчёта частоты повторения символа в строке
        private static Dictionary<char, int> CountFrequency(string text)
        {
            Dictionary<char, int> dic = new Dictionary<char, int>();
            for(int i = 0; i < text.Length; i++)
            {
                char ch = text[i];
                int count;
                if (dic.TryGetValue(ch, out count))
                    dic[ch] = count + 1;
                else
                    dic.Add(ch, 1);
            }
            return dic;
        }

        // Алгоритм Хаффмана
        private static TreeNode Huffman(List<TreeNode> tree)
        {
            while(tree.Count > 1)
            {
                tree.Sort();
                TreeNode left = tree[tree.Count - 1];
                tree.Remove(left);
                TreeNode right = tree[tree.Count - 1];
                tree.Remove(right);

                TreeNode parent = new TreeNode(null, left.Weight + right.Weight, left, right);
                tree.Add(parent);
            }
            return tree[0];
        }

        // Метод для обратного декодирования
        private static string HuffmanDecode(string encoded, TreeNode tree)
        {
            StringBuilder decoded = new StringBuilder();

            TreeNode node = tree;
            for(int i = 0; i < encoded.Length; i++)
            {
                node = encoded[i] == '0' ? node.LeftChild : node.RightChild;
                if(node.Content != null)
                {
                    decoded.Append(node.Content);
                    node = tree;
                }
            }

            return decoded.ToString();
        }
    }
}
