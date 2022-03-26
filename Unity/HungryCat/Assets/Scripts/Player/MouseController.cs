using UnityEngine;


namespace HungryCat
{
    public class MouseController : MonoBehaviour
    {
        [SerializeField] private SpriteMask _mask;

        private Vector3 _movement;

        private void Start()
        {

        }

        private void Update()
        {
            MoveMask();
        }

        private void MoveMask()
        {
            _movement = Camera.main.ScreenToWorldPoint(Input.mousePosition);
            _movement.z = 0;
            _mask.transform.position = _movement;
        }
    }
}