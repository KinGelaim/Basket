using UnityEngine;


namespace BattleOfFrogs
{
    public class ObjectTracker : MonoBehaviour
    {
        [SerializeField] private float _radiusEye = 1.0f;

        [SerializeField] private Transform _target;
        [SerializeField] private Transform _transformCenterOfEye;

        private void FixedUpdate()
        {
            transform.position = _transformCenterOfEye.position + (_target.position - _transformCenterOfEye.position).normalized * _radiusEye;
        }
    }
}
