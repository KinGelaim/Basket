using UnityEngine;


namespace SwiftPickaxe
{
    public class Unit : MonoBehaviour
    {

        #region Methods

        public virtual void Hurt(int value)
        {
            Die();
        }

        public virtual void Die()
        {
            Destroy(gameObject);
        }

        #endregion

    }
}