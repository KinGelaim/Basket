namespace ScoreSpaceJam_MERGING
{
    public class Score
    {
        public int ScoreVal { get; set; }
        public string Name { get; set; }
        public string Time { get; set; }

        public string ScoreStr => ScoreVal.ToString();
    }
}