using UnityEngine;


namespace LeapSquirrel
{
    public class WinTrigger : MonoBehaviour
    {
        [SerializeField] private GameManager _gameManager;

        private void OnTriggerEnter2D(Collider2D collision)
        {
            if (collision.gameObject.CompareTag("Player"))
            {
                _gameManager.Win();
            }
        }
    }
}