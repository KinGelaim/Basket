using System.Collections.Generic;
using UnityEngine;


namespace HungryCat
{
    public class EnemyFactory
    {
        #region Fields

        private GameObject[] _enemyPrefabs;
        private Vector3 _leftTopSpawn;
        private Vector3 _rightTopSpawn;
        private Vector3 _leftBottomSpawn;

        private List<Enemy> _enemyList;

        private Transform _parrentTransform;

        private GameManager _gameManager;

        #endregion


        #region Properties

        public int LengthEnemyList => _enemyList.Count;

        #endregion


        public EnemyFactory(GameObject[] enemyPrefabs, Transform parrentTransform, Vector3 leftTopSpawn, Vector3 rightTopSpawn, Vector3 leftBottomSpawn)
        {
            Enemy.SpeedRestart();

            _enemyPrefabs = enemyPrefabs;
            _leftTopSpawn = leftTopSpawn;
            _rightTopSpawn = rightTopSpawn;
            _leftBottomSpawn = leftBottomSpawn;

            _enemyList = new List<Enemy>();

            _parrentTransform = parrentTransform;
        }

        public void CreateEnemy()
        {
            if (Random.Range(1, 100) <= 40)
            {
                Vector3 currentSpawnPosition = new Vector3(Random.Range(_leftTopSpawn.x, _rightTopSpawn.x), Random.Range(_leftTopSpawn.y, _leftBottomSpawn.y), _leftTopSpawn.z);
                int indexEnemy = Random.Range(0, _enemyPrefabs.Length);
                Enemy enemy = new Enemy(GameObject.Instantiate(_enemyPrefabs[indexEnemy], currentSpawnPosition, new Quaternion(), _parrentTransform), _leftTopSpawn, _rightTopSpawn, _leftBottomSpawn);
                _enemyList.Add(enemy);
            }
        }

        public void MoveEnemy()
        {
            for (int i = 0; i < _enemyList.Count; i++)
            {
                if (!_enemyList[i].isNeedDestroy)
                {
                    _enemyList[i].Move();
                }
                else
                {
                    _enemyList[i].Destroy();
                    _enemyList.Remove(_enemyList[i]);
                }
            }
        }

        public void EatEnemy(GameObject gameObject)
        {
            for (int i = 0; i < _enemyList.Count; i++)
            {
                if (_enemyList[i].GetGameObject == gameObject)
                {
                    _enemyList[i].isNeedDestroy = true;
                }
            }
        }
    }
}