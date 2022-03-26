using UnityEngine;


namespace BattleOfFrogs
{
    public class PlayerController : MonoBehaviour
    {
        #region Feilds

        [SerializeField] private GameManager _gameManager;
        [SerializeField] private int _indexPlayer = 1;

        [SerializeField] private Transform _tongue;

        [Header("Keys")]
        [SerializeField] private SettingsController _settingsController;
        private KeyCode _keyCodeFirstPosition = KeyCode.Q;
        private KeyCode _keyCodeSecondPosition = KeyCode.W;
        private KeyCode _keyCodeThirdPosition = KeyCode.E;
        private KeyCode _keyCodeLeft = KeyCode.A;
        private KeyCode _keyCodeRight = KeyCode.D;
        private KeyCode _keyCodeFight = KeyCode.S;

        [SerializeField] private Transform _leftTrigger;
        [SerializeField] private Transform _middleTrigger;
        [SerializeField] private Transform _rightTrigger;
        private Transform[] _allTrriger;
        private Vector3[] _allTrrigerVecotrs;

        [SerializeField] private float _speed = 2.0f;
        [SerializeField] private float _speedLeftRight = 2.0f;

        private Vector3 _beginTongue;

        private bool _isStartTongue = false;
        private bool _isForward = false;
        private Transform _endPosition;

        private bool _isEat = false;

        [SerializeField] private float _acceptChangePosition = 3.0f;
        private float _changePosition = 0.0f;

        private int _score = 0;

        #endregion


        #region Properties

        public Transform GetTongue => _tongue;

        public bool GetIsEat => _isEat;

        public bool GetIsStartTongue => _isStartTongue;

        public string Score => _score.ToString();

        #endregion


        private void Start()
        {
            _beginTongue = new Vector3(_tongue.position.x, _tongue.position.y, _tongue.position.z);

            _allTrriger = new Transform[] { _leftTrigger, _middleTrigger, _rightTrigger };
            _allTrrigerVecotrs = new Vector3[] { _leftTrigger.position, _middleTrigger.position, _rightTrigger.position };

            if (_indexPlayer == 1)
            {
                _keyCodeFirstPosition = _settingsController.KeyCodeLeftTopAttackPlayer1;
                _keyCodeSecondPosition = _settingsController.KeyCodeTopAttackPlayer1;
                _keyCodeThirdPosition = _settingsController.KeyCodeRightTopAttackPlayer1;
                _keyCodeLeft = _settingsController.KeyCodeLeftPlayer1;
                _keyCodeRight = _settingsController.KeyCodeRightPlayer1;
                _keyCodeFight = _settingsController.KeyCodeFightPlayer1;
            }
            else
            {
                _keyCodeFirstPosition = _settingsController.KeyCodeLeftTopAttackPlayer2;
                _keyCodeSecondPosition = _settingsController.KeyCodeTopAttackPlayer2;
                _keyCodeThirdPosition = _settingsController.KeyCodeRightTopAttackPlayer2;
                _keyCodeLeft = _settingsController.KeyCodeLeftPlayer2;
                _keyCodeRight = _settingsController.KeyCodeRightPlayer2;
                _keyCodeFight = _settingsController.KeyCodeFightPlayer2;
            }
        }


        private void Update()
        {
            if (!_gameManager.isMainGame)
            {
                if (Input.GetKeyDown(_keyCodeFight))
                {
                    _gameManager.AddFight(_indexPlayer);
                }
                return;
            }

            if (!_isStartTongue)
            {
                if (Input.GetKey(_keyCodeFirstPosition))
                {
                    _endPosition = _leftTrigger;
                    _isStartTongue = true;
                    _isForward = true;
                }
                else if (Input.GetKey(_keyCodeSecondPosition))
                {
                    _endPosition = _middleTrigger;
                    _isStartTongue = true;
                    _isForward = true;
                }
                else if (Input.GetKey(_keyCodeThirdPosition))
                {
                    _endPosition = _rightTrigger;
                    _isStartTongue = true;
                    _isForward = true;
                }
            }
            else
            {
                if (Input.GetKey(_keyCodeLeft))
                {
                    if (_changePosition >= -_acceptChangePosition)
                    {
                        _changePosition -= _speedLeftRight * Time.deltaTime;
                        for (int i = 0; i < _allTrriger.Length; i++)
                        {
                            _allTrriger[i].position = new Vector3(_allTrriger[i].position.x - _speedLeftRight * Time.deltaTime, _allTrriger[i].position.y);
                        }
                    }
                }
                if (Input.GetKey(_keyCodeRight))
                {
                    if (_changePosition <= _acceptChangePosition)
                    {
                        _changePosition += _speedLeftRight * Time.deltaTime;
                        for (int i = 0; i < _allTrriger.Length; i++)
                        {
                            _allTrriger[i].position = new Vector3(_allTrriger[i].position.x + _speedLeftRight * Time.deltaTime, _allTrriger[i].position.y);
                        }
                    }
                }
                if (_isForward)
                {
                    if (Vector3.Distance(_tongue.position, _endPosition.position) > 0.1f)
                    {
                        _tongue.LookAt(_endPosition.transform);
                        _tongue.Translate(new Vector3(0, 0, _speed * Time.deltaTime));
                    }
                    else
                        _isForward = false;
                }
                else
                {
                    if (Vector3.Distance(_tongue.position, _beginTongue) > 0.1f)
                    {
                        _tongue.LookAt(_beginTongue);
                        _tongue.Translate(new Vector3(0, 0, _speed * Time.deltaTime));
                    }
                    else
                    {
                        _isStartTongue = false;
                        for (int i = 0; i < _allTrriger.Length; i++)
                        {
                            _allTrriger[i].position = _allTrrigerVecotrs[i];
                        }
                        _changePosition = 0.0f;
                    }
                }
            }
        }

        public void Eat()
        {
            _isEat = true;
            _isForward = false;
        }

        public void AddScore(int count)
        {
            _score += count;
            _isEat = false;
        }
    }
}