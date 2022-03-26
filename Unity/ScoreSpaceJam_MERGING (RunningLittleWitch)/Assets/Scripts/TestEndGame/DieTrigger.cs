using UnityEngine;


namespace ScoreSpaceJam_MERGING
{
    public class DieTrigger : MonoBehaviour
    {
        [SerializeField] private GameManager _gameManager;

        public GameManager SetGameManager
        {
            set
            {
                _gameManager = value;
            }
        }

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