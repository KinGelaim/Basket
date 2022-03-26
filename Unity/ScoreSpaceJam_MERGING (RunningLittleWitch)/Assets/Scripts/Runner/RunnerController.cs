using UnityEngine;


namespace ScoreSpaceJam_MERGING.Runner
{
    public class RunnerController : MonoBehaviour
    {
        [SerializeField] private Transform _pointPosition;
        [SerializeField] private float _targetPositionX;
        [SerializeField] private float _targetPositionY;
        [SerializeField] private float _trapPositionY;

        [SerializeField] private GameManager _gameManager;

        [SerializeField] private GameObject _ground;
        [SerializeField] private GameObject _trap;

        [SerializeField] private int minRangeSpace = 1;
        [SerializeField] private int maxRangeSpace = 4;
        [SerializeField] private int minLenGround = 1;
        [SerializeField] private int maxLenGround = 21;

        private Transform _parentTransform;

        private Vector3 _lastGeneration;

        private float _widthGround;

        private int _lenGround;

        private bool _isNeedGeneration = false;


        #region UnityMethods

        private void Start()
        {
            _widthGround = _ground.GetComponent<BoxCollider2D>().size.x;

            _lastGeneration.x = _pointPosition.position.x + _targetPositionX;
            _lastGeneration.y = _targetPositionY;

            _parentTransform = new GameObject("GenerateGround").transform;

            _trap.GetComponent<DieTrigger>().SetGameManager = _gameManager;
        }

        private void Update()
        {
            CheckGeneration();
            GenerationSpace();
            GenerationGroundAndTrap();
        }

        #endregion


        #region Methods

        private void CheckGeneration()
        {
            if (_pointPosition.transform.position.x >= _lastGeneration.x - _targetPositionX)
                _isNeedGeneration = true;
            else
                _isNeedGeneration = false;
        }

        private void GenerationSpace()
        {
            if (_isNeedGeneration)
                _lastGeneration.x += Vector3.right.x * Random.Range(minRangeSpace, maxRangeSpace);
        }

        private void GenerationGroundAndTrap()
        {
            if (_isNeedGeneration)
            {
                _lenGround = Random.Range(minLenGround, maxLenGround);
                for (float i = 1; i < _lenGround + _widthGround; i += _widthGround)
                {
                    if (i > 2 && i < _lenGround - 1)
                    {
                        float chance = Random.Range(0f, 1f);

                        if (chance >= 0.7f)
                        {
                            Instantiate(_trap, new Vector3(_lastGeneration.x, _trapPositionY, _lastGeneration.z), _trap.transform.rotation, _parentTransform);
                        }
                    }

                    Instantiate(_ground, _lastGeneration + Vector3.right * _widthGround, _ground.transform.rotation, _parentTransform);
                    _lastGeneration = _lastGeneration + Vector3.right * _widthGround;
                }
            }
        }

        #endregion
    }
}