using System.Collections.Generic;
using UnityEngine;
using UnityEngine.SceneManagement;
using UnityEngine.UI;


namespace SwiftPickaxe
{
    public class MainMenu : MonoBehaviour
    {
        #region Fields

        [SerializeField] private GameObject _textName;
        [SerializeField] private GameObject _textScore;
        [SerializeField] private GameObject _textTime;
        [SerializeField] private GameObject _textDepth;
        private Text _tableName;
        private Text _tableScore;
        private Text _tableTime;
        private Text _tableDepth;

        private List<Score> _scoreboard;

        #endregion


        #region UnityMethods

        private void Awake()
        {
            _tableName = _textName.GetComponent<Text>();
            _tableScore = _textScore.GetComponent<Text>();
            _tableTime = _textTime.GetComponent<Text>();
            _tableDepth = _textDepth.GetComponent<Text>();
        }

        private void Start()
        {
            _scoreboard = new List<Score>();
        }

        public void GetScoreBoard()
        {
            _scoreboard = ScoreboardManager.Instance.GetHighScore();

            _tableName.text = "";
            _tableScore.text = "";
            _tableTime.text = "";
            _tableDepth.text = "";
            foreach (Score score in _scoreboard)
            {
                _tableName.text += $"{score.NameStr}\n";
                _tableScore.text += $"{score.ScoreStr}\n";
                _tableTime.text += $"{score.time}\n";
                _tableDepth.text += $"{score.depth}\n";
            }
        }

        #endregion


        #region Methods

        public void StartGame()
        {
            SceneManager.LoadScene(1);
        }

        public void ExitGame()
        {
            Application.Quit();
        }

        public void ClearLeaderboard()
        {
            ScoreboardManager.Instance.ClearLeaderBoard();
        }

        public void OnImage()
        {

        }

        #endregion
    }
}