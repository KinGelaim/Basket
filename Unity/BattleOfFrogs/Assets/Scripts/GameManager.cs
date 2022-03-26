using System;
using System.Collections;
using UnityEngine;
using UnityEngine.SceneManagement;
using UnityEngine.UI;

namespace BattleOfFrogs
{
    public class GameManager : MonoBehaviour
    {
        #region Fields

        public bool isMainGame = true;

        [Header("Menu")]
        [SerializeField] private GameObject _loadPrefab;
        [SerializeField] private GameObject _pauseMenu;
        private bool _isPlayGame = true;

        [SerializeField] private GameObject _winMenu;
        [SerializeField] private GameObject _playerText;
        [SerializeField] private GameObject _fightMenu;

        [Header("Player")]
        [SerializeField] private PlayerController _firstPlayer;
        [SerializeField] private PlayerController _secondPlayer;

        [Header("Fight")]
        [SerializeField] private int _timerFight = 10;
        [SerializeField] private Slider _slider;
        [SerializeField] private Text _timerText;
        [SerializeField] private Animator _animator;
        private float fightScore = 0;

        [Header("Tongue")]
        [SerializeField] private GameObject _beginTongueFirstPlayer;
        [SerializeField] private GameObject _beginTongueSecondPlayer;
        
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

        public void FightMenu()
        {
            _beginTongueFirstPlayer.SetActive(false);
            _beginTongueSecondPlayer.SetActive(false);

            isMainGame = false;
            fightScore = 0;
            _fightMenu.SetActive(true);

            _animator.SetBool("IsOpen", true);

            StartCoroutine("TimerFight");
        }

        public void AddFight(int index)
        {
            if (index == 1)
                fightScore = fightScore - 1 >= _slider.minValue ? fightScore - 1 : _slider.minValue;
            else if(index == 2)
                fightScore = fightScore + 1 <= _slider.maxValue ? fightScore + 1 : _slider.maxValue;
            _slider.value = fightScore;
        }

        IEnumerator TimerFight()
        {
            int timer = _timerFight;
            while (timer-- > 0)
            {
                _timerText.text = timer.ToString();
                yield return new WaitForSeconds(1);
            }
            EndFightMenu();
        }

        private void EndFightMenu()
        {
            if (fightScore < 0)
                _firstPlayer.AddScore(10);
            else if (fightScore > 0)
                _secondPlayer.AddScore(10);

            _animator.SetBool("IsOpen", false);

            _beginTongueFirstPlayer.SetActive(true);
            _beginTongueSecondPlayer.SetActive(true);

            isMainGame = true;
            fightScore = 0;

            _fightMenu.SetActive(false);
        }


        public void Win()
        {
            _isPlayGame = false;
            Time.timeScale = 0;
            int scoreFirst = Convert.ToInt32(_firstPlayer.Score);
            int scoreSecond = Convert.ToInt32(_secondPlayer.Score);
            if (scoreFirst > scoreSecond)
            {
                _playerText.GetComponent<Text>().text = "Player 1 has won!";
            }
            else if (scoreFirst < scoreSecond)
            {
                _playerText.GetComponent<Text>().text = "Player 2 has won!";
            }
            else
            {
                _playerText.GetComponent<Text>().text = "Draw!";
            }
            _winMenu.SetActive(true);
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

        public void ExitGame()
        {
            Application.Quit();
        }

        #endregion
    }
}