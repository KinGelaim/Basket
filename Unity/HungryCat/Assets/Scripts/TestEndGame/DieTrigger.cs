using UnityEngine;


namespace HungryCat
{
    public class DieTrigger : MonoBehaviour
    {
        private void OnTriggerEnter2D(Collider2D collision)
        {
            if (collision.gameObject.CompareTag("Player"))
            {
                FindObjectOfType<GameManager>().Die();
            }
            else
                Destroy(collision.gameObject);
        }
    }
}