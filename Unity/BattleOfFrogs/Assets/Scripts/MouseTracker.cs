using UnityEngine;


namespace BattleOfFrogs
{
    public class MouseTracker : MonoBehaviour
    {
        [SerializeField] private float _radiusEye = 1.0f;

        [SerializeField] private Transform _transformCenterOfEye;

        private Camera _camera;

        private void Awake()
        {
            _camera = Camera.main;
        }

        private void FixedUpdate()
        {
            Vector3 screenMousePosition = Input.mousePosition;
            Vector3 worldMousePosition = _camera.ScreenToWorldPoint(screenMousePosition);

            transform.position = _transformCenterOfEye.position + (worldMousePosition - _transformCenterOfEye.position).normalized * _radiusEye;
        }
    }
}