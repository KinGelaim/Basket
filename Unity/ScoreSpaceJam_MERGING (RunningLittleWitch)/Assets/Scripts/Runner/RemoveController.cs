using UnityEngine;


namespace ScoreSpaceJam_MERGING.Runner
{
    public class RemoveController : MonoBehaviour
    {
        public bool IsActivate = false;

        [SerializeField] private GameManager _gameManager;

        [SerializeField] private Transform _targetPosition;
        [SerializeField] private KeyboardController _keyboardController;

        [SerializeField] private float _positionX;

        private float _oldPositionZ;

        #region UnityMethods

        private void Start()
        {
            _oldPositionZ = transform.position.z;
        }

        private void Update()
        {
            if (IsActivate)
            {
                transform.position = new Vector3((transform.position.x + _keyboardController.Speed * Time.deltaTime), _targetPosition.position.y, _oldPositionZ);
            }
            else
            {
                transform.position = new Vector3(_targetPosition.position.x + _positionX, _targetPosition.position.y, _oldPositionZ);
            }
        }

        private void OnTriggerEnter2D(Collider2D collision)
        {
            if (!collision.CompareTag("Player"))
            {
                Destroy(collision.gameObject);
            }
            else
            {
                _gameManager.Die();
                gameObject.SetActive(false);
            }
        }

        #endregion
    }
}