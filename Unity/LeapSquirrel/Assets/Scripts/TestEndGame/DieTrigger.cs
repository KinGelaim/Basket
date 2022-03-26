using UnityEngine;


namespace LeapSquirrel
{
    public class DieTrigger : MonoBehaviour
    {
        [SerializeField] private GameManager _gameManager;

        private void OnTriggerEnter2D(Collider2D collision)
        {
            if (collision.gameObject.CompareTag("Player"))
            {
                _gameManager.Die();
            }
        }

        private void OnTriggerStay2D(Collider2D collision)
        {
            if (collision.gameObject.CompareTag("Player"))
            {
                _gameManager.Die();
            }
        }
    }
}