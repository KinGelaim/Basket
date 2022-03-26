using UnityEngine;

namespace BattleOfFrogs
{
    public class LineRenderTongue : MonoBehaviour
    {
        [SerializeField] private Transform _tongue;

        private LineRenderer _lr;

        private void Start()
        {
            _lr = GetComponent<LineRenderer>();
        }


        private void Update()
        {
            if (Vector3.Distance(transform.position, _tongue.position) > 0.1f)
            {
                _lr.enabled = true;
                _lr.SetPosition(0, transform.position);
                _lr.SetPosition(1, _tongue.position);
            }
            else
                _lr.enabled = false;
        }
    }
}