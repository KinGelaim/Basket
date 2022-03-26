using System;


namespace LeapSquirrel
{
    public class Score
    {
        private float score;
        public string name;
        public string time;

        private int _beginScore = 10000;

        public string ScoreStr => score.ToString();

        public int GetScore()
        {
            return Convert.ToInt32(score);
        }

        public void SetScore(int score)
        {
            this.score = score;
        }

        public void SetScore(float hour, float minute, float second)
        {
            score = _beginScore - second - minute * 60 - hour * 60 * 60;
        }
    }
}