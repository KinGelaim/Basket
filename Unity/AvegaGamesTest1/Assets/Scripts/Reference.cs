using UnityEngine;


namespace AvegaGamesTest1
{
    public sealed class Reference
    {
        #region Fields

        private Camera _mainCamera;
        private GameObject _player;
        private GameObject _bullet;
        private GameObject _enemy;
        private GameObject _box;

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

        public GameObject Player
        {
            get
            {
                if (_player == null)
                {
                    _player = Resources.Load<GameObject>("MFPControllerPrefab");
                }
                return _player;
            }
        }

        public GameObject Bullet
        {
            get
            {
                if (_bullet == null)
                {
                    _bullet = Resources.Load<GameObject>("Bullet");
                }
                return _bullet;
            }
        }

        public GameObject Enemy
        {
            get
            {
                if (_enemy == null)
                {
                    _enemy = Resources.Load<GameObject>("Enemy");
                }
                return _enemy;
            }
        }

        public GameObject Box
        {
            get
            {
                if (_box == null)
                {
                    _box = Resources.Load<GameObject>("Box");
                }
                return _box;
            }
        }

        #endregion
    }
}