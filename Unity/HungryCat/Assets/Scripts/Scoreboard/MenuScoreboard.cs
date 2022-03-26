using System.Collections.Generic;
using UnityEngine;
using UnityEngine.UI;


namespace HungryCat
{
    public class MenuScoreboard : MonoBehaviour
    {
        #region Fields

        [SerializeField] private GameObject _textName;
        [SerializeField] private GameObject _textTime;
        [SerializeField] private GameObject _textScore;
        private Text _tableName;
        private Text _tableTime;
        private Text _tableScore;

        private List<Score> _scoreboard;

        #endregion


        #region UnityMethods

        private void Awake()
        {
            _tableName = _textName.GetComponent<Text>();
            _tableTime = _textTime.GetComponent<Text>();
            _tableScore = _textScore.GetComponent<Text>();
        }

        private void Start()
        {
            _scoreboard = new List<Score>();
        }

        #endregion


        public void GetScoreBoard()
        {
            _scoreboard = ScoreboardManager.Instance.GetHighScore();

            _tableName.text = "";
            _tableTime.text = "";
            _tableScore.text = "";
            foreach (Score score in _scoreboard)
            {
                _tableName.text += $"{score.Name}\n";
                _tableTime.text += $"{score.Time}\n";
                _tableScore.text += $"{score.ScoreStr}\n";
            }
        }
    }
}