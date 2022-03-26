using UnityEngine;
using UnityEngine.SceneManagement;
using UnityEngine.UI;


namespace BattleOfFrogs
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

        #endregion


        #region UnityMethods

        private void Awake()
        {
            _tableName = _textName.GetComponent<Text>();
            _tableScore = _textScore.GetComponent<Text>();
            _tableTime = _textTime.GetComponent<Text>();
            _tableDepth = _textDepth.GetComponent<Text>();
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

        #endregion
    }
}