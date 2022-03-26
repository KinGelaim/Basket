using UnityEngine;


namespace ScoreSpaceJam_MERGING.Parallax
{
    public class Scrolling : MonoBehaviour
    {
        #region Fields

        [SerializeField] private bool _scrolling = true;
        [SerializeField] private bool _parallax = true;

        [SerializeField] private float _backgroundSize;
        [SerializeField] private float _parallaxSpeed;

        private Transform _cameraTransform;
        private Transform[] _layers;

        private float _viewZone = 10;
        private float _lastCameraX;

        private int _leftIndex;
        private int _rightIndex;

        #endregion


        #region UnityMethods

        private void Start()
        {
            _cameraTransform = Camera.main.transform;
            _lastCameraX = _cameraTransform.position.x;

            _layers = new Transform[transform.childCount];
            for(int i = 0; i < _layers.Length; i++)
            {
                _layers[i] = transform.GetChild(i);
            }

            _leftIndex = 0;
            _rightIndex = _layers.Length - 1;
        }

        private void Update()
        {
            if (_parallax)
            {
                float deltaX = _cameraTransform.position.x - _lastCameraX;
                transform.position += Vector3.right * (deltaX * _parallaxSpeed);
            }
            
            _lastCameraX = _cameraTransform.position.x;

            if (_scrolling)
            {
                if (_cameraTransform.position.x < (_layers[_leftIndex].transform.position.x + _viewZone))
                {
                    ScrollLeft();
                }
                if (_cameraTransform.position.x > (_layers[_rightIndex].transform.position.x - _viewZone))
                {
                    ScrollRight();
                }
            }
        }

        #endregion


        #region Methods

        private void ScrollLeft()
        {
            int lastRight = _rightIndex;
            _layers[_rightIndex].position = new Vector3(_layers[_leftIndex].position.x - _backgroundSize, transform.position.y, transform.position.z);
            _leftIndex = _rightIndex;
            _rightIndex--;

            if (_rightIndex < 0)
            {
                _rightIndex = _layers.Length - 1;
            }
        }

        private void ScrollRight()
        {
            int lastLeft = _leftIndex;
            _layers[_leftIndex].position = new Vector3(_layers[_rightIndex].position.x + _backgroundSize, transform.position.y, transform.position.z);
            _rightIndex = _leftIndex;
            _leftIndex++;

            if (_leftIndex == _layers.Length)
            {
                _leftIndex = 0;
            }
        }

        #endregion
    }
}