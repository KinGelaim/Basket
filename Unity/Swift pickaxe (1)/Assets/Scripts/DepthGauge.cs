using UnityEngine;
using UnityEngine.UI;


namespace SwiftPickaxe
{
    public class DepthGauge : MonoBehaviour
    {
        #region Fields

        private PlayerController _player;

        private Text _text;

        private float _depth;

        #endregion


        #region Properties

        public string Depth
        {
            get
            {
                return $"{Mathf.Round(-_depth)}";
            }
        }

        #endregion


        #region UnityMethods

        private void Awake()
        {
            _player = FindObjectOfType<PlayerController>();
            _text = GetComponent<Text>();
        }

        private void FixedUpdate()
        {
            _depth = _player.transform.position.y;
            _text.text = Depth;
        }

        #endregion
    }
}