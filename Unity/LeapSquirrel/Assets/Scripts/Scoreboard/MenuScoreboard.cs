using System.Collections.Generic;
using UnityEngine;
using UnityEngine.UI;


namespace LeapSquirrel
{
    public class MenuScoreboard : MonoBehaviour
    {
        #region Fields

        [SerializeField] private GameObject _textName;
        [SerializeField] private GameObject _textTime;
        private Text _tableName;
        private Text _tableTime;

        private List<Score> _scoreboard;

        #endregion


        #region UnityMethods

        private void Awake()
        {
            _tableName = _textName.GetComponent<Text>();
            _tableTime = _textTime.GetComponent<Text>();
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
            foreach (Score score in _scoreboard)
            {
                _tableName.text += $"{score.name}\n";
                _tableTime.text += $"{score.time}\n";
            }
        }
    }
}