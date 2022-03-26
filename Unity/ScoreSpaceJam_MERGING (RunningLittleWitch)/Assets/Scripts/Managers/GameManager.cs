using UnityEngine;
using UnityEngine.SceneManagement;
using UnityEngine.UI;


namespace ScoreSpaceJam_MERGING
{
    public class GameManager : MonoBehaviour
    {
        #region Fields

        [Header("Menu")]
        [SerializeField] private GameObject _loadPrefab;
        [SerializeField] private GameObject _pauseMenu;
        private bool _isPlayGame = true;
        private bool _isEndGame = false;
        private bool _isSaveScore = false;

        [SerializeField] private GameObject _winMenu;
        [SerializeField] private Text _playerNameText;

        [Header("Timer")]
        [SerializeField] private Timer _timer;

        [Header("Score")]
        [SerializeField] private Text _textScore;
        private int _score = 0;


        #endregion


        #region Properties

        public bool IsPlayGame => _isPlayGame;

        #endregion


        #region UnityMethods

        private void Update()
        {
            if (Input.GetButtonDown("Cancel"))
            {
                if (!_isEndGame)
                {
                    if (_isPlayGame)
                        Pause();
                    else
                        Continue();
                }
            }
        }

        #endregion


        #region Methods

        public void RestartLevel()
        {
            Continue();
            _loadPrefab.SetActive(true);
            SceneLoading loading = _loadPrefab.GetComponent<SceneLoading>();
            loading.SetSceneID(SceneManager.GetActiveScene().buildIndex);
            loading.LoadScene();
        }

        public void LoadLevel(int indexLevel)
        {
            Continue();
            _loadPrefab.SetActive(true);
            SceneLoading loading = _loadPrefab.GetComponent<SceneLoading>();
            loading.SetSceneID(indexLevel);
            loading.LoadScene();
        }

        public void LoadNextLevel()
        {
            _loadPrefab.SetActive(true);
            SceneLoading loading = _loadPrefab.GetComponent<SceneLoading>();
            if (SceneManager.GetActiveScene().buildIndex + 1 < SceneManager.sceneCountInBuildSettings)
            {
                loading.SetSceneID(SceneManager.GetActiveScene().buildIndex + 1);
                loading.LoadScene();
            }
            else
            {
                _isPlayGame = false;
                Time.timeScale = 0;
            }
        }

        public void Die()
        {
            _isPlayGame = false;
            Time.timeScale = 0;
            _winMenu.SetActive(true);
            _isEndGame = true;
        }

        private void Pause()
        {
            _isPlayGame = false;
            Time.timeScale = 0;
            _pauseMenu.SetActive(true);
        }

        public void Continue()
        {
            _isPlayGame = true;
            Time.timeScale = 1;
            _pauseMenu.SetActive(false);
        }

        public void ExitInMenu()
        {
            _isPlayGame = false;
            Time.timeScale = 0;

            Continue();
            _loadPrefab.SetActive(true);
            SceneLoading loading = _loadPrefab.GetComponent<SceneLoading>();
            loading.SetSceneID(1);
            loading.LoadScene();
        }

        public void ExitInMenuAfterWin()
        {
            if (_playerNameText.text.Length > 0)
            {
                _isPlayGame = false;
                Time.timeScale = 0;

                _timer.Stop();
                if (!_isSaveScore)
                {
                    Score score = new Score();
                    _score = Mathf.RoundToInt(_timer.Second + _timer.Min * 60 + _timer.Hour * 60 * 60);
                    score.ScoreVal = _score;
                    if (ScoreboardManager.Instance.CheckNewScore(score.ScoreVal))
                    {
                        ScoreboardManager.Instance.SaveHighScore(_playerNameText.text, score.ScoreVal, _timer.ShortTime);
                    }
                    _isSaveScore = true;
                }

                Continue();
                _loadPrefab.SetActive(true);
                SceneLoading loading = _loadPrefab.GetComponent<SceneLoading>();
                loading.SetSceneID(1);
                loading.LoadScene();
            }
        }

        public void ExitGame()
        {
            Application.Quit();
        }

        #endregion
    }
}