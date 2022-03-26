using UnityEngine;


namespace TargemTest1
{
    public sealed class CameraController
    {
        private Camera _mainCamera;
        private GameObject _centerUniverse;

        public CameraController(Camera mainCamera, GameObject centerUniverse)
        {
            _mainCamera = mainCamera;
            _centerUniverse = centerUniverse;

            RotateCamera();
        }

        private void RotateCamera()
        {
            _mainCamera.transform.LookAt(_centerUniverse.transform);
        }
    }
}