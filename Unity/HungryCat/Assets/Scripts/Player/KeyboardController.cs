using UnityEngine;


namespace HungryCat
{
    public class KeyboardController : MonoBehaviour
    {

        #region Fields

        [SerializeField] private float _speed = 3.0f;

        private PlayerController _player;
        private Animator _animator;
        private Rigidbody2D _rigidbody2D;

        private Vector2 _horizontalVelocity;

        private float _horizontalInput;

        private bool _isForward = true;

        #endregion


        #region UnityMethods

        private void Start()
        {
            _rigidbody2D = gameObject.GetComponent<Rigidbody2D>();
            _player = gameObject.GetComponent<PlayerController>();
            _animator = gameObject.GetComponent<Animator>();
        }

        private void Update()
        {
            Move();
        }

        private void FixedUpdate()
        {
            if (_horizontalInput > 0 && !_isForward)
                Flip();
            else if (_horizontalInput < 0 && _isForward)
                Flip();
        }

        #endregion


        #region Methods

        private void Move()
        {
            if (_player.State != CharacterState.Die)
            {
                _horizontalInput = Input.GetAxisRaw("Horizontal");
                _horizontalVelocity.Set(_horizontalInput * _speed, _rigidbody2D.velocity.y);
                _rigidbody2D.velocity = _horizontalVelocity;

                if (_horizontalInput != 0)
                    _player.State = CharacterState.Walk;
                else
                    _player.State = CharacterState.Idle;
            }
        }

        private void Flip()
        {
            _isForward = !_isForward;
            Vector3 vector = Vector3.zero;
            if (_isForward)
                vector.y = 0;
            else
                vector.y = 180;
            transform.rotation = Quaternion.Euler(vector);
        }

        #endregion

    }
}