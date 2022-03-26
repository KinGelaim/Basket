using UnityEngine;


namespace HungryCat
{
    public class EnemyDanger : MonoBehaviour
    {
        private AudioSource _owl;

        [SerializeField] private Transform _playerTransform;
        private Vector3 _fixedPlayerTransform;
        private Vector3 _leftPosition;
        private Vector3 _rightPosition;
        private float _offsetX = 4.0f;
        private float _beginY = 10.25f;
        private float _speedOwl = 2.0f;

        [SerializeField] private static float _minTime = 7.0f;
        private static float _maxTime = 30.0f;
        private static float _stepMaxTime = 1.0f;
        [SerializeField] private static float _beginMaxTime = 30.0f;

        private bool isAtack = false;
        private bool isDown = true;

        private float _currentTime = 0.0f;

        private void Start()
        {
            NextTime();

            _owl = GetComponent<AudioSource>();

            _maxTime = _beginMaxTime;
        }

        private void Update()
        {
            if (isAtack)
            {
                if (isDown)
                {
                    transform.position = Vector3.Lerp(transform.position, _fixedPlayerTransform, _speedOwl * Time.deltaTime);

                    if (Vector3.Distance(transform.position, _fixedPlayerTransform) <= 0.2f)
                    {
                        isDown = false;
                    }
                }
                else
                {
                    transform.position = Vector3.Lerp(transform.position, _leftPosition, _speedOwl * Time.deltaTime);

                    if (Vector3.Distance(transform.position, _leftPosition) <= 0.2f)
                    {
                        isDown = true;
                        isAtack = false;
                    }
                }
            }
        }

        private void LateUpdate()
        {
            if (!isAtack)
                TickTimer(Time.deltaTime);
        }

        private void OnTriggerEnter2D(Collider2D collision)
        {
            if (collision.gameObject.CompareTag("Player"))
            {
                FindObjectOfType<GameManager>().Die();
            }
        }

        private void NextTime()
        {
            _currentTime = Random.Range(_minTime, _maxTime);
        }

        private void TickTimer(float tick)
        {
            _currentTime -= tick;

            if (_currentTime <= 0.0f)
            {
                _owl.Play();

                isAtack = true;

                _fixedPlayerTransform = new Vector3(_playerTransform.position.x, _playerTransform.position.y, _playerTransform.position.z);
                
                _leftPosition = _playerTransform.position;
                _leftPosition.x = _leftPosition.x - _offsetX;

                _rightPosition = _playerTransform.position;
                _rightPosition.x = _rightPosition.x + _offsetX;

                _leftPosition.y = _beginY;
                _rightPosition.y = _beginY;

                transform.position = _rightPosition;

                NextTime();
            }
        }

        public static void MaxTimeDown()
        {
            if (_maxTime - _stepMaxTime > _minTime)
                _maxTime -= _stepMaxTime;
        }
    }
}