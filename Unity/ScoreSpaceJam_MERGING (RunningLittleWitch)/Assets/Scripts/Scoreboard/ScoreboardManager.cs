using UnityEngine;
using System.Collections.Generic;


namespace ScoreSpaceJam_MERGING
{
    public class ScoreboardManager : MonoBehaviour
    {
        #region Fields

        private const int ScoreboardLength = 10;

        private static ScoreboardManager _scoreboardManager;

        #endregion


        #region Properties

        public static ScoreboardManager Instance
        {
            get
            {
                if (_scoreboardManager == null)
                {
                    _scoreboardManager = new GameObject("ScoreboardManager").AddComponent<ScoreboardManager>();
                }
                return _scoreboardManager;
            }
        }

        #endregion


        #region UnityMethods

        private void Awake()
        {
            if (_scoreboardManager == null)
            {
                _scoreboardManager = this;
            }
            else if (_scoreboardManager != this)
                Destroy(gameObject);

            DontDestroyOnLoad(gameObject);
        }

        #endregion


        #region Methods

        public void SaveHighScore(string name, int score, string time)
        {
            List<Score> HighScores = new List<Score>();

            int i = 1;
            while (i <= ScoreboardLength && PlayerPrefs.HasKey("HighScore" + i + "score"))
            {
                Score temp = new Score();
                temp.ScoreVal = PlayerPrefs.GetInt("HighScore" + i + "score");
                temp.Name = PlayerPrefs.GetString("HighScore" + i + "name");
                temp.Time = PlayerPrefs.GetString("HighScore" + i + "time");
                HighScores.Add(temp);
                i++;
            }
            if (HighScores.Count == 0)
            {
                Score temp = new Score();
                temp.Name = name;
                temp.ScoreVal = score;
                temp.Time = time;
                HighScores.Add(temp);
            }
            else
            {
                for (i = 1; i <= HighScores.Count && i <= ScoreboardLength; i++)
                {
                    if (score > HighScores[i - 1].ScoreVal)
                    {
                        Score temp = new Score();
                        temp.Name = name;
                        temp.ScoreVal = score;
                        temp.Time = time;
                        HighScores.Insert(i - 1, temp);
                        break;
                    }
                    if (i == HighScores.Count && i < ScoreboardLength)
                    {
                        Score temp = new Score();
                        temp.Name = name;
                        temp.ScoreVal = score;
                        temp.Time = time;
                        HighScores.Add(temp);
                        break;
                    }
                }
            }

            i = 1;
            while (i <= ScoreboardLength && i <= HighScores.Count)
            {
                PlayerPrefs.SetString("HighScore" + i + "name", HighScores[i - 1].Name);
                PlayerPrefs.SetInt("HighScore" + i + "score", HighScores[i - 1].ScoreVal);
                PlayerPrefs.SetString("HighScore" + i + "time", HighScores[i - 1].Time);
                i++;
            }
        }

        public List<Score> GetHighScore()
        {
            List<Score> HighScores = new List<Score>();

            int i = 1;
            while (i <= ScoreboardLength && PlayerPrefs.HasKey("HighScore" + i + "score"))
            {
                Score temp = new Score();
                temp.ScoreVal = PlayerPrefs.GetInt("HighScore" + i + "score");
                temp.Name = PlayerPrefs.GetString("HighScore" + i + "name");
                temp.Time = PlayerPrefs.GetString("HighScore" + i + "time");
                HighScores.Add(temp);
                i++;
            }

            return HighScores;
        }

        public bool CheckNewScore(int score)
        {
            int i = 1;
            while(i <= ScoreboardLength && PlayerPrefs.HasKey("HighScore" + i + "score"))
            {
                if (PlayerPrefs.GetInt("HighScore" + i + "score") < score)
                    return true;
                i++;
            }
            if (i > ScoreboardLength)
                return false;
            return true;
        }

        public void ClearLeaderBoard()
        {
            List<Score> HighScores = GetHighScore();

            for (int i = 1; i <= HighScores.Count; i++)
            {
                PlayerPrefs.DeleteKey("HighScore" + i + "name");
                PlayerPrefs.DeleteKey("HighScore" + i + "score");
                PlayerPrefs.DeleteKey("HighScore" + i + "time");
            }
        }

        private void OnApplicationQuit()
        {
            PlayerPrefs.Save();
        }

        #endregion
    }
}
