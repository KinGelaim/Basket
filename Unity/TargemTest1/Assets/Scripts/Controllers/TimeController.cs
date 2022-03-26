using UnityEngine;
using UnityEngine.UI;


namespace TargemTest1
{
    public sealed class TimeController : IExecute
    {
        #region Fields

        private float _totalTime = 0.0f;
        private Text _secondText;

        #endregion


        #region LifeCycleClass

        public TimeController(Text secondText)
        {
            _secondText = secondText;
            RedrawTime();
        }

        #endregion


        #region Methods

        private void RedrawTime()
        {
            _secondText.text = Mathf.Round(_totalTime).ToString();
        }

        public void ClearTime()
        {
            _totalTime = 0.0f;
            RedrawTime();
        }

        #endregion


        #region IExecute

        public void Execute()
        {
            _totalTime += Time.deltaTime;
            RedrawTime();
        }

        #endregion
    }
}