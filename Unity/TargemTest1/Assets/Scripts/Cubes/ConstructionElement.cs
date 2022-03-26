using UnityEngine;


namespace TargemTest1
{
    public sealed class ConstructionElement : MonoBehaviour
    {
        #region Fields

        public delegate void ColliderDelegate(int ID, Vector3 oldDirection);
        public ColliderDelegate OnColliderDelegate;

        private Renderer _renderer;

        #endregion


        #region Properties

        public int ID { get; set; }
        public Construction parent { get; set; }

        #endregion


        #region UnityMethods

        private void Start()
        {
            _renderer = GetComponent<Renderer>();
        }

        private void OnTriggerEnter(Collider other)
        {
            ConstructionElement ce = other.GetComponent<ConstructionElement>();
            if (ce != null)
            {
                if (ID != ce.ID)
                {
                    ChangeColor();
                    OnColliderDelegate?.Invoke(ID, ce.parent.OldDirection);
                }
            }
        }

        #endregion


        #region Methods

        private void ChangeColor()
        {
            _renderer.material.color = Color.red;
        }

        #endregion
    }
}