using UnityEngine;


namespace HungryCat
{
    public class Enemy
    {
        public bool isNeedDestroy = false;

        private GameObject _gameObject;
        private Vector3 _leftTopSpawn;
        private Vector3 _rightTopSpawn;
        private Vector3 _leftBottomSpawn;

        private Vector3 _nextPosition;
        private float _radiusNextPosition = 3.0f;
        private int _countNextPosition = 7;
        private int _currentNextPosition = 0;

        private static float _speed = 2.0f;
        private static float _beginSpeed = 2.0f;

        public GameObject GetGameObject => _gameObject;

        public Enemy(GameObject gameObject, Vector3 leftTopSpawn, Vector3 rightTopSpawn, Vector3 leftBottomSpawn)
        {
            _gameObject = gameObject;
            _leftTopSpawn = leftTopSpawn;
            _rightTopSpawn = rightTopSpawn;
            _leftBottomSpawn = leftBottomSpawn;

            _nextPosition = _gameObject.transform.position;
        }

        public static void SpeedRestart()
        {
            _speed = _beginSpeed;
        }

        public static void SpeedUp()
        {
            _speed += 0.1f;
        }

        public void Move()
        {
            if (_currentNextPosition < _countNextPosition)
            {
                if (Vector3.Distance(_gameObject.transform.position, _nextPosition) < 0.1f)
                {
                    float spawnX = Random.Range(_gameObject.transform.position.x - _radiusNextPosition, _gameObject.transform.position.x + _radiusNextPosition);

                    if (spawnX < _leftTopSpawn.x)
                        spawnX = _leftTopSpawn.x;
                    if (spawnX > _rightTopSpawn.x)
                        spawnX = _rightTopSpawn.x;

                    float spawnY = Random.Range(_gameObject.transform.position.y - _radiusNextPosition, _gameObject.transform.position.y + _radiusNextPosition);

                    if (spawnY > _leftTopSpawn.y)
                        spawnY = _leftTopSpawn.y;
                    if (spawnY < _leftBottomSpawn.y)
                        spawnY = _leftBottomSpawn.y;

                    _nextPosition = new Vector3(spawnX, spawnY, _gameObject.transform.position.z);
                    _currentNextPosition++;
                }
                else
                {
                    _gameObject.transform.position = Vector3.Lerp(_gameObject.transform.position, _nextPosition, _speed * Time.deltaTime);
                }
            }
            else
                isNeedDestroy = true;
        }

        public void Destroy()
        {
            GameObject.Destroy(_gameObject);
        }
    }
}
