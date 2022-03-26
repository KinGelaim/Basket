using UnityEngine;
using UnityEngine.SceneManagement;
using UnityEngine.UI;


namespace SwiftPickaxe
{
    public class GameManager : MonoBehaviour
    {
        #region Fields

        [Header("Menu")]
        [SerializeField] private GameObject _loadPrefab;
        [SerializeField] private GameObject _pauseMenu;
        private bool _isPlayGame = true;

        [SerializeField] private GameObject _dieMenu;
        [SerializeField] private GameObject _textScoreGameObject;
        [SerializeField] private GameObject _inputNameGameObject;
        [SerializeField] private GameObject _winMenu;
        [SerializeField] private GameObject _star1;
        [SerializeField] private GameObject _star2;
        [SerializeField] private GameObject _star3;
        private Text _textScore;
        private InputField _fieldName;

        [SerializeField] private GameObject _allWinMenu;
        [SerializeField] private GameObject _textScoreWinGameObject;
        [SerializeField] private GameObject _inputNameWinGameObject;
        private Text _textScoreWin;
        private InputField _fieldNameWin;

        [Header("Count score")]
        [SerializeField] private int _scoreFirst = 1;
        [SerializeField] private int _scoreSecond = 2;
        [SerializeField] private int _scoreThird = 3;

        [Header("Player UI")]
        [SerializeField] private GameObject _timerUI;
        [SerializeField] private GameObject _depthUI;
        [SerializeField] private GameObject _player;
        [SerializeField] private GameObject _goldText;
        [SerializeField] private GameObject _diamondText;
        [SerializeField] private GameObject _emeraldText;
        [SerializeField] private GameObject _rubyText;
        [SerializeField] private GameObject _sapphireText;
        private Text _textTimer;
        private Text _textDepth;
        private PlayerController _playerController;
        private Text _textGold;
        private Text _textDiamond;
        private Text _textEmerald;
        private Text _textRuby;
        private Text _textSapphire;

        private AudioSource _audioSource;

        #endregion


        #region Properties

        public string Depth => _textDepth.text;

        #endregion


        #region UnityMethods

        private void Awake()
        {
            _textScore = _textScoreGameObject.GetComponent<Text>();
            _fieldName = _inputNameGameObject.GetComponent<InputField>();

            if (_textScoreWinGameObject)
            {
                _textScoreWin = _textScoreWinGameObject.GetComponent<Text>();
                _fieldNameWin = _inputNameWinGameObject.GetComponent<InputField>();
            }

            _textTimer = _timerUI.GetComponent<Text>();
            _textDepth = _depthUI.GetComponent<Text>();
            _playerController = _player.GetComponent<PlayerController>();
            _textGold = _goldText.GetComponent<Text>();
            _textDiamond = _diamondText.GetComponent<Text>();
            _textEmerald = _emeraldText.GetComponent<Text>();
            _textRuby = _rubyText.GetComponent<Text>();
            _textSapphire = _sapphireText.GetComponent<Text>();

            _audioSource = GetComponent<AudioSource>();
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

        private void FixedUpdate()
        {
            UpdateItems();
        }

        #endregion


        #region Methods

        private void UpdateItems()
        {
            _textGold.text = _playerController[TypeItem.Gold].ToString();
            _textDiamond.text = _playerController[TypeItem.Diamond].ToString();
            _textEmerald.text = _playerController[TypeItem.Emerald].ToString();
            _textRuby.text = _playerController[TypeItem.Ruby].ToString();
            _textSapphire.text = _playerController[TypeItem.Sapphire].ToString();
        }

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
            Continue();
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
                _allWinMenu.SetActive(true);
                _textScoreWin.text = $"You scored: {_playerController.Score} points ";
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

        public void Die(int score = 0)
        {
            _audioSource.Play();
            _isPlayGame = false;
            Time.timeScale = 0;
            _dieMenu.SetActive(true);
            _textScore.text = $"You scored: {score} points ";
        }

        public void MainMenuAfterDie()
        {
            if (_playerController.Score != 0)
            {
                if (ScoreboardManager.Instance.CheckNewScore(_playerController.Score))
                {
                    ScoreboardManager.Instance.SaveHighScore(_fieldName.text, _playerController.Score, _textTimer.text, _textDepth.text);
                }
            }

            _playerController.ClearDictionaryItem();

            Continue();
            _loadPrefab.SetActive(true);
            SceneLoading loading = _loadPrefab.GetComponent<SceneLoading>();
            loading.SetSceneID(0);
            loading.LoadScene();
        }

        public void RestartLevelAfterDie()
        {
            if (_playerController.Score != 0)
            {
                if (ScoreboardManager.Instance.CheckNewScore(_playerController.Score))
                {
                    ScoreboardManager.Instance.SaveHighScore(_fieldName.text, _playerController.Score, _textTimer.text, _textDepth.text);
                }
            }

            _playerController.BeginDictionaryItem();

            Continue();
            _loadPrefab.SetActive(true);
            SceneLoading loading = _loadPrefab.GetComponent<SceneLoading>();
            loading.SetSceneID(SceneManager.GetActiveScene().buildIndex);
            loading.LoadScene();
        }

        public void Win(int score)
        {
            _isPlayGame = false;
            Time.timeScale = 0;
            if (score >= _scoreFirst && score < _scoreSecond)
            {
                _star1.SetActive(true);
            }
            else if(score >= _scoreSecond && score < _scoreThird)
            {
                _star1.SetActive(true);
                _star2.SetActive(true);
            }
            else if(score >= _scoreThird)
            {
                _star1.SetActive(true);
                _star2.SetActive(true);
                _star3.SetActive(true);
            }
            _winMenu.SetActive(true);
        }

        public void MainMenuAfterWin()
        {
            if (_playerController.Score != 0)
            {
                if (ScoreboardManager.Instance.CheckNewScore(_playerController.Score))
                {
                    ScoreboardManager.Instance.SaveHighScore(_fieldNameWin.text, _playerController.Score, _textTimer.text, _textDepth.text);
                }
            }

            _playerController.ClearDictionaryItem();

            Continue();
            _loadPrefab.SetActive(true);
            SceneLoading loading = _loadPrefab.GetComponent<SceneLoading>();
            loading.SetSceneID(0);
            loading.LoadScene();
        }

        public void ExitInMenu()
        {
            _isPlayGame = false;
            Time.timeScale = 0;
            _playerController.ClearDictionaryItem();

            Continue();
            _loadPrefab.SetActive(true);
            SceneLoading loading = _loadPrefab.GetComponent<SceneLoading>();
            loading.SetSceneID(0);
            loading.LoadScene();
        }

        #endregion
    }
}