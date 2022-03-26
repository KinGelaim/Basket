using System.Collections.Generic;
using UnityEngine;


namespace BattleOfFrogs
{
    public class EnemyFactory
    {
        #region Fields

        private GameObject[] _enemyPrefabs;
        private Vector3 _leftTopSpawn;
        private Vector3 _rightTopSpawn;
        private Vector3 _rightBottomSpawn;
        private Vector3 _leftBottomSpawn;

        private List<Enemy> _enemyList;

        private PlayerController _playerFirstController;
        private PlayerController _playerSecondController;

        #endregion


        #region Properties

        public int LengthEnemyList => _enemyList.Count;

        #endregion


        public EnemyFactory(GameObject[] enemyPrefabs, Vector3 leftTopSpawn, Vector3 rightTopSpawn, Vector3 rightBottomSpawn, Vector3 leftBottomSpawn, PlayerController playerFirstController, PlayerController playerSecondController)
        {
            _enemyPrefabs = enemyPrefabs;
            _leftTopSpawn = leftTopSpawn;
            _rightTopSpawn = rightTopSpawn;
            _rightBottomSpawn = rightBottomSpawn;
            _leftBottomSpawn = leftBottomSpawn;

            _enemyList = new List<Enemy>();

            _playerFirstController = playerFirstController;
            _playerSecondController = playerSecondController;
        }

        public void CreateEnemy()
        {
            if (Random.Range(1, 100) <= 40)
            {
                Vector3 currentSpawnPosition = new Vector3(Random.Range(_leftTopSpawn.x, _rightTopSpawn.x), Random.Range(_leftTopSpawn.y, _leftBottomSpawn.y), _leftTopSpawn.z);
                int indexEnemy = Random.Range(0, _enemyPrefabs.Length);
                Enemy enemy = new Enemy(GameObject.Instantiate(_enemyPrefabs[indexEnemy], currentSpawnPosition, new Quaternion()), _leftTopSpawn, _rightTopSpawn, _leftBottomSpawn, _playerFirstController, _playerSecondController);
                _enemyList.Add(enemy);
            }
        }

        public void MoveEnemy()
        {
            for (int i = 0; i < _enemyList.Count; i++)
            {
                if (!_enemyList[i].isNeedDestroy)
                {
                    _enemyList[i].CheckTongue();
                    _enemyList[i].Move();
                }
                else
                {
                    _enemyList[i].Destroy();
                    _enemyList.Remove(_enemyList[i]);
                }
            }
        }

        public void CheckEat()
        {
            for (int i = 0; i < _enemyList.Count; i++)
            {
                if (_enemyList[i].isHaveTongue)
                {
                    _enemyList[i].GetPlayerControllerEat.AddScore(1);
                    _enemyList[i].Destroy();
                    _enemyList.Remove(_enemyList[i]);
                }
            }
        }
    }
}