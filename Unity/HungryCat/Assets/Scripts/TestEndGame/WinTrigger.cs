using UnityEngine;


namespace HungryCat
{
    public class WinTrigger : MonoBehaviour
    {
        private void OnTriggerEnter2D(Collider2D collision)
        {
            if (collision.gameObject.CompareTag("Player"))
            {
                FindObjectOfType<GameManager>().Die();
            }
        }
    }
}