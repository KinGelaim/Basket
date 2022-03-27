using UnityEngine;


namespace AvegaGamesTest1
{
    public sealed class BoxCollision : MonoBehaviour
    {
        public void OnTriggerEnter(Collider other)
        {
            if (other.CompareTag("Player"))
                Destroy(gameObject);
        }
    }
}