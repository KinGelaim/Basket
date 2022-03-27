using UnityEngine;


namespace AvegaGamesTest1
{
    public sealed class Box
    {
        #region Fields

        private GameObject _boxGameObject;

        private float _time = 0.0f;
        private float _amp = 0.25f;
        private float _freq = 7.0f;
        private float _offset = 0.0f;
        private Vector3 _startPos;

        private float _speedRotation = 0.01f;
        private Vector3 _vector = new Vector3(0.0f, 1.0f, 0.0f);
        private Quaternion _q;

        #endregion


        #region Properties

        public bool IsDie { get; private set; } = false;
        public BoxMaterial BoxMaterial { get; private set; }

        #endregion


        public Box(GameObject box, BoxMaterial boxMaterial, Vector3 enemyPosition)
        {
            _boxGameObject = GameObject.Instantiate(box, enemyPosition, new Quaternion());
            BoxMaterial = boxMaterial;

            MaterialController.ChaneMaterialForGameObject(_boxGameObject, BoxMaterial);

            _startPos = _boxGameObject.transform.position;
        }


        #region Methods

        public void Action()
        {
            if (_boxGameObject == null)
            {
                IsDie = true;
                return;
            }

            Fly();
            Rotate();
        }

        private void Fly()
        {
            // Up Adn Down
            _time += Time.deltaTime;
            _offset = _amp * Mathf.Sin(_time * _freq);
            _boxGameObject.transform.position = _startPos + new Vector3(0.0f, _offset);
        }

        private void Rotate()
        {
            // Rotate
            _q = new Quaternion(Mathf.Sin(_speedRotation / 2) * _vector.x, Mathf.Sin(_speedRotation / 2) * _vector.y, Mathf.Sin(_speedRotation / 2) * _vector.z, Mathf.Cos(_speedRotation / 2));
            _boxGameObject.transform.rotation = _boxGameObject.transform.rotation * _q;
        }

        #endregion
    }
}