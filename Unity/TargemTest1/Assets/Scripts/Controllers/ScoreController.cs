using UnityEngine.UI;


namespace TargemTest1
{
    public sealed class ScoreController
    {
        #region Fields

        public delegate void ScoreDellegate(int score);
        public ScoreDellegate OnScoreDellegate;

        private static int _totalScore = 0;
        private static Text _scoreText;

        #endregion


        #region LifeCycleClass

        public ScoreController(Text scoreText)
        {
            _scoreText = scoreText;
            OnScoreDellegate = AddScore;
            RedrawScore();
        }

        #endregion


        #region Methods

        private void AddScore(int score)
        {
            _totalScore += score;
            RedrawScore();
        }

        private void RedrawScore()
        {
            _scoreText.text = _totalScore.ToString();
        }

        public void ClearScore()
        {
            _totalScore = 0;
            RedrawScore();
        }

        #endregion
    }
}