using UnityEngine;


namespace ScoreSpaceJam_MERGING
{
    public class CameraController : MonoBehaviour
    {

        #region Fields

        [SerializeField] private Transform playerTransform;

        [SerializeField] private float _offsetX = 0.0f;
        [SerializeField] private float _offsetY = 0.0f;
        [SerializeField] private float _dumpling = 1.5f;

        #endregion


        #region UnityMethods

        private void Start()
        {
            transform.position = new Vector3(playerTransform.position.x, playerTransform.position.y, transform.position.z);
        }

        private void Update()
        {
            Vector3 target;

            NewCameraTargetPosition(out target);

            Vector3 currentPosition = Vector3.Lerp(transform.position, target, _dumpling * Time.deltaTime);
            transform.position = currentPosition;
        }

        #endregion


        #region Methods

        private void NewCameraTargetPosition(out Vector3 target)
        {
            target = new Vector3(playerTransform.position.x + _offsetX, playerTransform.position.y + _offsetY, transform.position.z);
        }

        #endregion

    }
}