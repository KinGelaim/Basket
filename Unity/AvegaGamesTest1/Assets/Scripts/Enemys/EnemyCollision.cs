using UnityEngine;


namespace AvegaGamesTest1
{
    public sealed class EnemyCollision : MonoBehaviour
    {
        private int _maxCountHits = 2;
        private int _countHits = 0;

        public void OnCollisionEnter(Collision collision)
        {
            if (collision.gameObject.CompareTag("Bullet"))
            {
                if (_maxCountHits <= ++_countHits)
                {
                    GameObject.Destroy(gameObject);
                }
                GameObject.Destroy(collision.gameObject);
            }
        }
    }
}