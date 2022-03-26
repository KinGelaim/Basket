using UnityEngine;
using UnityEngine.SceneManagement;
using UnityEngine.UI;


namespace LeapSquirrel
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
        [SerializeField] private Timer _timer;

        [Header("Player")]
        [SerializeField] private GameObject _player;
        private KeyboardController _keyboardController;

        [Header("Spawn")]
        [SerializeField] private Transform _spawnPoint;

        #endregion


        #region UnityMethods

        private void Start()
        {
            if (_player)
                _keyboardController = _player.GetComponent<KeyboardController>();
        }

        private void Update()
        {
            if (Input.GetButtonDown("Cancel"))
            {
                if (_isPlayGame)
                    Pause();
                else
                    Continue();
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

        public void Win()
        {
            _isPlayGame = false;
            Time.timeScale = 0;

            _winMenu.SetActive(true);
        }

        public void Die()
        {
            _player.transform.position = _spawnPoint.position;
            _keyboardController.Restart();
        }

        public void ExitInMenu()
        {
            _isPlayGame = false;
            Time.timeScale = 0;

            Continue();
            _loadPrefab.SetActive(true);
            SceneLoading loading = _loadPrefab.GetComponent<SceneLoading>();
            loading.SetSceneID(0);
            loading.LoadScene();
        }

        public void ExitInMenuAfterWin()
        {
            _isPlayGame = false;
            Time.timeScale = 0;

            _timer.Stop();
            Score score = new Score();
            score.SetScore(_timer.Hour, _timer.Min, _timer.Second);
            if (ScoreboardManager.Instance.CheckNewScore(score.GetScore()))
            {
                ScoreboardManager.Instance.SaveHighScore(_playerNameText.text, score.GetScore(), _timer.ShortTime);
            }

            Continue();
            _loadPrefab.SetActive(true);
            SceneLoading loading = _loadPrefab.GetComponent<SceneLoading>();
            loading.SetSceneID(0);
            loading.LoadScene();
        }

        public void ExitGame()
        {
            Application.Quit();
        }

        #endregion
    }
}