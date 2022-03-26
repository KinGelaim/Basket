using System.Collections;
using UnityEngine;
using UnityEngine.UI;


namespace BattleOfFrogs
{
    public class Timer : MonoBehaviour
    {
        #region Fields

        [SerializeField] private GameManager _gameManager;

        [SerializeField] private float _beginMin = 3.0f;
        [SerializeField] private float _beginSecond = 0.0f;

        private bool isStart = true;

        private float _min;
        private float _second;

        private Text _timerText;

        #endregion


        #region Properties

        public string ShortTime
        {
            get
            {
                return $"{_min.ToString("00")}:{_second.ToString("00")}";
            }
        }

        public float Second
        {
            get
            {
                return _second;
            }
        }

        public float Min
        {
            get
            {
                return _min;
            }
        }

        public string Time
        {
            get
            {
                return $"Time: {_min}:{_second}";
            }
        }

        #endregion


        #region UnityMethods

        private void Awake()
        {
            _timerText = transform.GetComponent<Text>();
        }

        private void Start()
        {
            Restart();
        }

        private void FixedUpdate()
        {
            _timerText.text = ShortTime;
        }

        #endregion


        #region Methods

        IEnumerator DoCheck()
        {
            while (isStart)
            {
                if (_gameManager.isMainGame)
                {
                    _second--;

                    if (_second == 0 && _min == 0)
                    {
                        _gameManager.Win();
                        isStart = false;
                    }
                    else if (_second < 0)
                    {
                        _min--;
                        _second = 59;
                    }
                    yield return new WaitForSeconds(1);
                }
                yield return new WaitForSeconds(1);
            }
        }

        public void Stop()
        {
            isStart = false;
        }

        public void Restart()
        {
            _min = _beginMin;
            _second = _beginSecond;
            isStart = true;
            StartCoroutine("DoCheck");
        }
        
        #endregion

    }
}