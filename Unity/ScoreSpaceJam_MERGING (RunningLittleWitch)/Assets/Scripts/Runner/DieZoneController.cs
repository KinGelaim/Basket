using UnityEngine;


namespace ScoreSpaceJam_MERGING.Runner
{
    public class DieZoneController : MonoBehaviour
    {
        [SerializeField] private GameManager _gameManager;

        [SerializeField] private Transform _targetPosition;
        
        [SerializeField] private float _positionY;

        private void Update()
        {
            transform.position = new Vector3(_targetPosition.position.x, _positionY, _targetPosition.position.z);
        }

        private void OnTriggerEnter2D(Collider2D collision)
        {
            if (collision.CompareTag("Player"))
            {
                _gameManager.Die();
            }
        }
    }
}
