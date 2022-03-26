namespace SwiftPickaxe
{
    public class Score
    {
        public int score;
        public string name;
        public string time;
        public string depth;

        public string ScoreStr => score.ToString();

        public string NameStr
        {
            get
            {
                return name.PadRight(18, ' ');
            }
        }
    }
}