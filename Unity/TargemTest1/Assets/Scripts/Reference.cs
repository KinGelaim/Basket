using UnityEngine;


namespace TargemTest1
{
    public sealed class Reference
    {
        #region Fields

        private Camera _mainCamera;
        private GameObject _centerUniverse;
        private GameObject _exampleCube;

        #endregion


        #region Properties

        public Camera MainCamera
        {
            get
            {
                if (_mainCamera == null)
                {
                    _mainCamera = Camera.main;
                }
                return _mainCamera;
            }
        }

        public GameObject CenterUniverse
        {
            get
            {
                if (_centerUniverse == null)
                {
                    _centerUniverse = Resources.Load<GameObject>("CenterUniverse");
                }
                return _centerUniverse;
            }
        }

        public GameObject ExampleCube
        {
            get
            {
                if (_exampleCube == null)
                {
                    _exampleCube = Resources.Load<GameObject>("ExampleCube");
                }
                return _exampleCube;
            }
        }

        #endregion
    }
}