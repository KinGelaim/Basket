using UnityEngine;


namespace SwiftPickaxe
{
    public class WinTrigger : MonoBehaviour
    {
        private void OnTriggerEnter2D(Collider2D collision)
        {
            if (collision.gameObject.CompareTag("Player"))
            {
                collision.gameObject.GetComponent<PlayerController>().Win();
            }
        }
    }
}