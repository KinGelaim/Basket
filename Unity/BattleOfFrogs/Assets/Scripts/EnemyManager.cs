using UnityEngine;


namespace BattleOfFrogs
{
    public class EnemyManager : MonoBehaviour
    {
        [SerializeField] private GameManager _gameManager;

        [SerializeField] private int _countOfEnemy = 10;

        [SerializeField] private Transform _leftTopSpawn;
        [SerializeField] private Transform _rightTopSpawn;
        [SerializeField] private Transform _rightBottomSpawn;
        [SerializeField] private Transform _leftBottomSpawn;

        [SerializeField] private PlayerController _playerFirst;
        [SerializeField] private PlayerController _playerSecond;

        private GameObject _beePrefab;
        private GameObject _flyPrefab;
        private GameObject _mosquitoPrefab;

        private EnemyFactory _enemyFactory;

        private void Start()
        {
            _beePrefab = Resources.Load<GameObject>("Enemy/Bee");
            _flyPrefab = Resources.Load<GameObject>("Enemy/Fly");
            _mosquitoPrefab = Resources.Load<GameObject>("Enemy/Mosquito");

            GameObject[] enemys = new GameObject[] { _beePrefab, _flyPrefab, _mosquitoPrefab };
            _enemyFactory = new EnemyFactory(enemys, _leftTopSpawn.position, _rightTopSpawn.position, _rightBottomSpawn.position, _leftBottomSpawn.position, _playerFirst, _playerSecond);
        }


        private void Update()
        {
            if (_gameManager.isMainGame)
            {
                if (_enemyFactory.LengthEnemyList < _countOfEnemy)
                {
                    _enemyFactory.CreateEnemy();
                }

                _enemyFactory.MoveEnemy();

                if (_playerFirst.GetIsEat && !_playerFirst.GetIsStartTongue || _playerSecond.GetIsEat && !_playerSecond.GetIsStartTongue)
                {
                    _enemyFactory.CheckEat();
                }
            }
        }
    }
}