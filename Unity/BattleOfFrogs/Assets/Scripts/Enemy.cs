using UnityEngine;


namespace BattleOfFrogs
{
    public class Enemy
    {
        public bool isNeedDestroy = false;
        public bool isHaveTongue = false;

        private GameObject _gameObject;
        private Vector3 _leftTopSpawn;
        private Vector3 _rightTopSpawn;
        private Vector3 _leftBottomSpawn;

        private PlayerController _playerFirstController;
        private PlayerController _playerSecondController;
        private PlayerController _playerEat;

        private Vector3 _nextPosition;
        private float _radiusNextPosition = 3.0f;
        private int _countNextPosition = 7;
        private int _currentNextPosition = 0;

        private float _speed = 2.0f;

        public PlayerController GetPlayerControllerEat => _playerEat;

        public Enemy(GameObject gameObject, Vector3 leftTopSpawn, Vector3 rightTopSpawn, Vector3 leftBottomSpawn, PlayerController playerFirstController, PlayerController playerSecondController)
        {
            _gameObject = gameObject;
            _leftTopSpawn = leftTopSpawn;
            _rightTopSpawn = rightTopSpawn;
            _leftBottomSpawn = leftBottomSpawn;

            _playerFirstController = playerFirstController;
            _playerSecondController = playerSecondController;

            _nextPosition = _gameObject.transform.position;
        }

        public void Move()
        {
            if (!isHaveTongue)
            {
                if (_currentNextPosition < _countNextPosition)
                {
                    if (Vector3.Distance(_gameObject.transform.position, _nextPosition) < 0.1f)
                    {
                        float spawnX = Random.Range(_gameObject.transform.position.x - _radiusNextPosition, _gameObject.transform.position.x + _radiusNextPosition);

                        if (spawnX < _leftTopSpawn.x || spawnX > _rightTopSpawn.x)
                            spawnX = -spawnX;

                        float spawnY = Random.Range(_gameObject.transform.position.y - _radiusNextPosition, _gameObject.transform.position.y + _radiusNextPosition);

                        if (spawnY > _leftTopSpawn.y || spawnY < _leftBottomSpawn.y)
                            spawnY = -spawnY;

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
            else
                _gameObject.transform.position = _playerEat.GetTongue.position;
        }

        public void CheckTongue()
        {
            if (!isHaveTongue)
            {
                if (Vector3.Distance(_gameObject.transform.position, _playerFirstController.GetTongue.position) < 1f)
                {
                    isHaveTongue = true;
                    _playerFirstController.Eat();
                    _playerEat = _playerFirstController;
                }
                if (Vector3.Distance(_gameObject.transform.position, _playerSecondController.GetTongue.position) < 1f)
                {
                    isHaveTongue = true;
                    _playerSecondController.Eat();
                    _playerEat = _playerSecondController;
                }
            }
        }

        public void Destroy()
        {
            GameObject.Destroy(_gameObject);
        }
    }
}
