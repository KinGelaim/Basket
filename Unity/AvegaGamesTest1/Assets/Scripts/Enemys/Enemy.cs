using UnityEngine;


namespace AvegaGamesTest1
{
    public sealed class Enemy
    {
        #region Fields

        private GameObject _enemy;

        private float _speedEnemy = 1.0f;
        private float _sleepTarget = 1.5f;

        private float _time = 0.0f;
        private float _delay = 1.0f;

        private int _damage = 5;

        #endregion


        #region Properties

        public bool IsDie { get; private set; } = false;
        public Vector3 LastPosition { get; set; }

        #endregion


        public Enemy(GameObject enemy)
        {
            _enemy = enemy;
        }


        #region Methods

        public void Action(GameObject target, PlayerController playerController)
        {
            if (_enemy == null)
            {
                EnemyDie();
                return;
            }

            if ((_enemy.transform.position - target.transform.position).sqrMagnitude > _sleepTarget)
            {
                Move(target);
                LastPosition = _enemy.transform.position;
            }
            else
            {
                Attack(playerController);
            }
        }

        private void Move(GameObject target)
        {
            _enemy.transform.LookAt(target.transform);
            _enemy.transform.position += (target.transform.position - _enemy.transform.position).normalized * _speedEnemy * Time.deltaTime;
        }

        private void Attack(PlayerController playerController)
        {
            _time += Time.deltaTime;

            if (_time > _delay)
            {
                playerController.ChangeHP(-_damage);
                _time = 0.0f;
            }
        }

        private void EnemyDie()
        {
            IsDie = true;
        }

        #endregion
    }
}