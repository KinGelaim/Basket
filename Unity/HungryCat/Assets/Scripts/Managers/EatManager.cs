using System.Collections.Generic;
using UnityEngine;


namespace HungryCat
{
    public class EatManager : MonoBehaviour
    {
        [SerializeField] private Timer _timer;

        [SerializeField] private Transform _spawnPosition;

        private GameObject _mousePrefabFirst;
        private GameObject _mousePrefabSecond;
        private GameObject _mousePrefabThird;
        private GameObject _ballOfWoolPrefab;

        private List<EnemyCage> _enemysInCage;

        [SerializeField] private AudioClip _eat;
        [SerializeField] private AudioClip _hiss;
        private AudioSource _audioSource;

        private void Start()
        {
            _mousePrefabFirst = Resources.Load<GameObject>("Enemy/Mouse1");
            _mousePrefabSecond = Resources.Load<GameObject>("Enemy/Mouse2");
            _mousePrefabThird = Resources.Load<GameObject>("Enemy/Mouse3");
            _ballOfWoolPrefab = Resources.Load<GameObject>("Enemy/BallOfWool");

            _enemysInCage = new List<EnemyCage>();

            _audioSource = GetComponent<AudioSource>();
        }

        private void Update()
        {
            for (int i = 0; i < _enemysInCage.Count; i++)
            {
                if (_enemysInCage[i].gameObject.transform.position.y <= -300)
                {
                    Destroy(_enemysInCage[i].gameObject);
                    _enemysInCage.Remove(_enemysInCage[i]);
                }
                else if (_enemysInCage[i].TickLive(Time.deltaTime))
                {
                    Destroy(_enemysInCage[i].gameObject);
                    _timer.AddSecond(_enemysInCage[i].SecondAdd);                   
                    _enemysInCage.Remove(_enemysInCage[i]);
                }                
            }
        }

        public void Eat(EnemyType type)
        {
            EnemyCage enemyCage = null;
            switch (type)
            {
                case EnemyType.mouseFirst:
                    enemyCage = new EnemyCage(Instantiate(_mousePrefabFirst, _spawnPosition), 5.0f, 5);
                    _audioSource.PlayOneShot(_eat);
                    break;
                case EnemyType.mouseSecond:
                    enemyCage = new EnemyCage(Instantiate(_mousePrefabSecond, _spawnPosition), 5.0f, 5);
                    _audioSource.PlayOneShot(_eat);
                    break;
                case EnemyType.mouseThird:
                    enemyCage = new EnemyCage(Instantiate(_mousePrefabThird, _spawnPosition), 10.0f, 10);
                    _audioSource.PlayOneShot(_eat);
                    break;
                case EnemyType.ballOfWool:
                    enemyCage = new EnemyCage(Instantiate(_ballOfWoolPrefab, _spawnPosition), 50.0f);
                    _audioSource.PlayOneShot(_hiss);
                    break;
            }
            enemyCage.gameObject.transform.localScale = new Vector3(enemyCage.gameObject.transform.localScale.x * 100, enemyCage.gameObject.transform.localScale.y * 100, enemyCage.gameObject.transform.localScale.z);
            enemyCage.gameObject.layer = 5;
            enemyCage.gameObject.GetComponent<SpriteRenderer>().sortingOrder = 17;
            enemyCage.gameObject.AddComponent<Rigidbody2D>();
            Destroy(enemyCage.gameObject.GetComponent<EnemyClick>());


            _enemysInCage.Add(enemyCage);
        }
    }
}
