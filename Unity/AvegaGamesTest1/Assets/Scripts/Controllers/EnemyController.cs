using System.Collections.Generic;
using UnityEngine;


namespace AvegaGamesTest1
{
    public sealed class EnemyController : IExecute
    {
        #region Fields

        private readonly Camera _camera;
        private readonly GameObject _player;
        private readonly PlayerController _playerController;
        private readonly BoxController _boxController;

        private List<Enemy> enemies = new List<Enemy>();

        private GameObject _enemyPrefab;

        private float _time = 0.0f;
        private float _delay = 3.0f;

        #endregion


        public EnemyController(GameObject enemy, Camera camera, GameObject player, PlayerController playerController, BoxController boxController)
        {
            _enemyPrefab = enemy;
            _camera = camera;
            _player = player;
            _playerController = playerController;
            _boxController = boxController;
        }


        #region Methods

        private void SpawnEnemy()
        {
            float width = _camera.orthographicSize * _camera.aspect + 1;
            Vector3 v3Pos = Camera.main.ViewportToWorldPoint(new Vector3(1.1f, 0.5f, 10.0f));

            var go = GameObject.Instantiate(_enemyPrefab, 
                new Vector3(-v3Pos.x + Random.Range(-width, width), 
                1.1f,
                -v3Pos.z + Random.Range(5, 5)), 
                Quaternion.identity);

            var enemy = new Enemy(go);

            enemies.Add(enemy);
        }

        #endregion


        #region IExecute

        public void Execute()
        {
            _time += Time.deltaTime;
            if (_time >= _delay)
            {
                _time = 0.0f;
                SpawnEnemy();
            }

            foreach (var enemy in enemies.ToArray())
            {
                enemy.Action(_player, _playerController);
                if (enemy.IsDie)
                {
                    _boxController.CreateBox(enemy.LastPosition);
                    enemies.Remove(enemy);
                }
            }
        }

        #endregion
    }
}