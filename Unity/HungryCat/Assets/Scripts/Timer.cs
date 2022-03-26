using System.Collections;
using UnityEngine;
using UnityEngine.UI;


namespace HungryCat
{
    public class Timer : MonoBehaviour
    {
        #region Fields

        [SerializeField] private GameManager _gameManager;

        [SerializeField] private float _beginMin = 0.0f;
        [SerializeField] private float _beginSecond = 30.0f;

        private bool isStart = true;

        private float _min;
        private float _second;

        private float _minUp;
        private float _secondUp;

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

        public string TimeUp
        {
            get
            {
                return $"{_minUp.ToString("000")}:{_secondUp.ToString("00")}";
            }
        }

        public float AllSecondUP
        {
            get
            {
                return _minUp * 60 + _secondUp;
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
                _second--;

                if (_second == 0 && _min == 0)
                {
                    _gameManager.Die();
                    isStart = false;
                }
                else if (_second < 0)
                {
                    _min--;
                    _second = 59;
                }

                _secondUp++;
                if (_secondUp >= 60)
                {
                    _minUp++;
                    _secondUp = 0;
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
            _minUp = 0.0f;
            _secondUp = 0.0f;
            isStart = true;
            StartCoroutine("DoCheck");
        }

        public void AddSecond(int second)
        {
            _second += second;
            if (_second >= 60)
            {
                _min++;
                _second -= 60;
            }
        }
        
        #endregion

    }
}