namespace MainModule
{
    public static class StringUtil
    {
        public static int GetSumNumber(string text)
        {
            int sum = 0;
            for (int i = 0; i < text.Length; i++)
            {
                if(char.IsDigit(text[i]))
                {
                    sum += int.Parse(text[i].ToString());
                }
            }
            return sum;
        }
    }
}
