using UnityEngine;


namespace HungryCat
{
    public class BezierTest : MonoBehaviour
    {
        [SerializeField] private Sprite[] _sprites;
        [SerializeField] private SpriteRenderer _spriteRenderer;

        private Transform _childrenTransform;

        [SerializeField] private Transform[] _points;

        [SerializeField, Range(0,1)] private float _t;

        private bool _isLeft = true;
        private bool _flipAgree = false;

        private void Start()
        {
            _childrenTransform = transform.gameObject.GetComponentsInChildren<Transform>()[1];
            _childrenTransform.gameObject.SetActive(false);
        }

        private void Update()
        {
            if (_isLeft)
            {
                _t = Mathf.Lerp(_t, 1, 0.3f * Time.deltaTime);
            }
            else
            {
                _t = Mathf.Lerp(_t, 0, 0.3f * Time.deltaTime);
            }
            transform.position = Bezier.GetPoint(_points[0].position, _points[1].position, _points[2].position, _points[3].position, _t);
            if ((_t >= (1-0.1) || _t <= 0 + 0.1) && _flipAgree)
            {
                Flip();
                _flipAgree = false;
            }
            if (_t >= 0.4 && _t <= 0.6)
                _flipAgree = true;
            //transform.rotation = Quaternion.LookRotation(Bezier.GetFirstDerivative(_points[0].position, _points[1].position, _points[2].position, _points[3].position, _t));

            if (_t < 0.4)
                _spriteRenderer.sprite = _sprites[0];
            else if (_t > 0.6)
                _spriteRenderer.sprite = _sprites[2];
            else
                _spriteRenderer.sprite = _sprites[1];
        }

        private void Flip()
        {
            _isLeft = !_isLeft;
            Vector3 vector = Vector3.zero;
            if (!_isLeft)
            {
                vector.y = 0;
            }
            else
            {
                vector.y = 180;
            }
            transform.rotation = Quaternion.Euler(vector);
            _childrenTransform.rotation = Quaternion.Euler(Vector3.zero);
        }
    }
}