using UnityEngine;
using UnityEngine.SceneManagement;
using UnityEngine.UI;


namespace HungryCat
{
    public class GameManager : MonoBehaviour
    {
        #region Fields

        [Header("Menu")]
        [SerializeField] private GameObject _loadPrefab;
        [SerializeField] private GameObject _pauseMenu;
        private bool _isPlayGame = true;

        [SerializeField] private GameObject _winMenu;
        [SerializeField] private Text _playerNameText;

        [Header("EatManager")]
        [SerializeField] private EatManager _eatManager;

        [Header("Score")]
        [SerializeField] private Text _textScore;
        private int _score = 0;

        [SerializeField] private Timer _timer;
        private float _oldSecond = 0.0f;
        private float _timeStep = 10.0f;

        [Header("Player")]
        [SerializeField] private PlayerController _playerController;


        #endregion


        #region Properties

        public bool IsPlayGame => _isPlayGame;

        #endregion


        #region UnityMethods

        private void Update()
        {
            if (Input.GetButtonDown("Cancel"))
            {
                if (_isPlayGame)
                    Pause();
                else
                    Continue();
            }

            if (_timer != null)
                CheckTimer();
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

        private void CheckTimer()
        {
            if (_timer.AllSecondUP - _timeStep >= _oldSecond)
            {
                _oldSecond = _timer.AllSecondUP;
                Enemy.SpeedUp();
                EnemyDanger.MaxTimeDown();
            }
        }

        public void Grab(EnemyType indexEnemy)
        {
            _score++;
            _textScore.text = _score.ToString();

            _eatManager.Eat(indexEnemy);
        }

        public void Die()
        {
            _playerController.State = CharacterState.Die;

            _isPlayGame = false;
            Time.timeScale = 0;
            
            _winMenu.SetActive(true);
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
            _isPlayGame = false;
            Time.timeScale = 0;

            _timer.Stop();
            Score score = new Score();
            score.ScoreVal = _score;
            if (ScoreboardManager.Instance.CheckNewScore(score.ScoreVal))
            {
                ScoreboardManager.Instance.SaveHighScore(_playerNameText.text, score.ScoreVal, _timer.TimeUp);
            }

            Continue();
            _loadPrefab.SetActive(true);
            SceneLoading loading = _loadPrefab.GetComponent<SceneLoading>();
            loading.SetSceneID(1);
            loading.LoadScene();
        }

        public void ExitGame()
        {
            Application.Quit();
        }

        #endregion
    }
}