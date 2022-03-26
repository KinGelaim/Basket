using UnityEngine;


namespace HungryCat
{
    public class EnemyManager : MonoBehaviour
    {
        [SerializeField] private int _countOfEnemy = 20;

        [SerializeField] private Transform _leftTopSpawn;
        [SerializeField] private Transform _rightTopSpawn;
        [SerializeField] private Transform _leftBottomSpawn;

        private GameObject _mousePrefabFirst;
        private GameObject _mousePrefabSecond;
        private GameObject _mousePrefabThird;
        private GameObject _ballOfWoolPrefab;

        private EnemyFactory _enemyFactory;

        private string nameParentEnemyTransform = "Enemys";

        private void Start()
        {
            GameObject parrentTransform = new GameObject(nameParentEnemyTransform);

            _mousePrefabFirst = Resources.Load<GameObject>("Enemy/Mouse1");
            _mousePrefabSecond = Resources.Load<GameObject>("Enemy/Mouse2");
            _mousePrefabThird = Resources.Load<GameObject>("Enemy/Mouse3");
            _ballOfWoolPrefab = Resources.Load<GameObject>("Enemy/BallOfWool");

            GameObject[] enemys = new GameObject[] { _mousePrefabFirst, _mousePrefabSecond, _mousePrefabThird, _ballOfWoolPrefab };
            _enemyFactory = new EnemyFactory(enemys, parrentTransform.transform, _leftTopSpawn.position, _rightTopSpawn.position, _leftBottomSpawn.position);
        }


        private void Update()
        {
            if (_enemyFactory.LengthEnemyList < _countOfEnemy)
            {
                _enemyFactory.CreateEnemy();
            }

            _enemyFactory.MoveEnemy();
        }

        public void EatEnemy(GameObject gameObject)
        {
            _enemyFactory.EatEnemy(gameObject);
        }
    }
}