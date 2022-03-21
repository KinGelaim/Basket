using System;


namespace Huffman
{
    public class TreeNode : IComparable
    {
        private int weight;
        private char? content;

        public TreeNode LeftChild { get; set; }
        public TreeNode RightChild { get; set; }

        public int Weight
        {
            get
            {
                return weight;
            }
            set
            {
                if(weight > 0)
                    weight = value;
            }
        }

        public char? Content
        {
            get { return content; }
            set { content = value; }
        }

        public TreeNode(char? content, int weight)
        {
            this.content = content;
            Weight = weight;
        }

        public TreeNode(char? content, int weight, TreeNode leftChild, TreeNode rightChild) : this(content, weight)
        {
            LeftChild = leftChild;
            RightChild = rightChild;
        }

        public string GetCodeForCharacter(char ch, string parentPath)
        {
            if(content == ch)
            {
                return parentPath;
            }
            else
            {
                if(LeftChild != null)
                {
                    string path = LeftChild.GetCodeForCharacter(ch, parentPath + 0);
                    if (path != null)
                        return path;
                }
                if(RightChild != null)
                {
                    string path = RightChild.GetCodeForCharacter(ch, parentPath + 1);
                    if (path != null)
                        return path;
                }
            }
            return null;
        }

        public int CompareTo(object obj)
        {
            return ((TreeNode)obj).weight - weight;
        }
    }
}
