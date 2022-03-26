using System.Collections;
using UnityEngine;
using UnityEngine.UI;


namespace SwiftPickaxe
{
    public class Timer : MonoBehaviour
    {
        #region Fields

        private bool isStart = true;

        private float _second;
        private float _min;
        private float _hour;

        private Text _tomerText;

        #endregion


        #region Properties

        public string ShortTime
        {
            get
            {
                return $"{_hour.ToString("00")}:{_min.ToString("00")}:{_second.ToString("00")}";
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

        public float Hour
        {
            get
            {
                return _hour;
            }
        }

        public string Time
        {
            get
            {
                return $"Time: {_hour}:{_min}:{_second}";
            }
        }

        #endregion


        #region UnityMethods

        private void Awake()
        {
            _tomerText = this.transform.GetComponent<Text>();
        }

        private void FixedUpdate()
        {
            _tomerText.text = ShortTime;
        }

        private void Start()
        {
            isStart = true;
            StartCoroutine("DoCheck");
        }

        #endregion


        #region Methods

        IEnumerator DoCheck()
        {
            while (isStart)
            {
                _second++;
                if (_second >= 60)
                {
                    _min++;
                    _second = 0;
                }
                if (_min >= 60)
                {
                    _hour++;
                    _min = 0;
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
            _hour = 0.0f;
            _min = 0.0f;
            _second = 0.0f;
            isStart = true;
            StartCoroutine("DoCheck");
        }
        
        #endregion

    }
}