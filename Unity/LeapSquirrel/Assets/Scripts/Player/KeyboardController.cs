using UnityEngine;


namespace LeapSquirrel
{
    public class KeyboardController : MonoBehaviour
    {

        #region Fields

        [SerializeField] private LayerMask _ground = default;
        [SerializeField] private float _groundDistance = 0.1f;
        [SerializeField] private Transform _groundCheck;
        [SerializeField] private Transform _upCheck;
        [SerializeField] private Transform _leftCheck;
        [SerializeField] private Transform _rightCheck;
        private bool _onGround = false;
        private bool _onLeft = false;
        private bool _onRight = false;
        private bool _onUp = false;

        [SerializeField] private float _speed = 3.0f;

        private PlayerController _player;
        private Rigidbody2D _rigidbody2D;

        private Vector2 _horizontalVelocity;
        private Vector3 _movement;

        private float _horizontalInput;
        
        private bool _isForward = true;

        #endregion


        #region UnityMethods

        private void Start()
        {
            _rigidbody2D = gameObject.GetComponent<Rigidbody2D>();
            _player = gameObject.GetComponent<PlayerController>();
        }

        private void Update()
        {
            Jump();
            Move();
            CheckGround();
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
            if (_onGround && !_onLeft && !_onRight && !_onUp)
            {
                //_rigidbody2D.simulated = true;

                _horizontalInput = Input.GetAxisRaw("Horizontal");
                _horizontalVelocity.Set(_horizontalInput * _speed, _rigidbody2D.velocity.y);
                _rigidbody2D.velocity = _horizontalVelocity;

                if (_horizontalInput != 0)
                    _player.State = CharacterState.Run;
                else
                    _player.State = CharacterState.Idle;
            }
            else
            {
                //_horizontalVelocity.Set(Mathf.Lerp(transform.position.x, _movement.x, _speed), Mathf.Lerp(transform.position.y, _movement.y, _speed));
                //_rigidbody2D.velocity = _horizontalVelocity;
                //Debug.Log(_movement);
                //transform.position = new Vector2(Mathf.Lerp(transform.position.x, _movement.x, 0.01f), Mathf.Lerp(transform.position.y, _movement.y, 0.1f));

                if (_player.State == CharacterState.Jump)
                    if (Vector3.Distance(transform.position, _movement) > 0.2f)
                    {
                        transform.position = Vector3.Lerp(transform.position, _movement, _speed * Time.deltaTime);
                        //_horizontalVelocity = (transform.position - _movement).normalized * _speed * Time.deltaTime;
                        //_rigidbody2D.velocity = _horizontalVelocity;
                    }
                    else
                    {
                        //_rigidbody2D.simulated = true;
                        _rigidbody2D.gravityScale = 1.0f;
                        _player.State = CharacterState.Idle;
                    }
            }
        }

        public void Jump()
        {
            if (Input.GetKeyDown(KeyCode.Mouse0) && (_onGround || _player.State == CharacterState.Left || _player.State == CharacterState.Right || _player.State == CharacterState.Up))
            {
                _onGround = false;
                _player.State = CharacterState.Jump;

                _movement = Camera.main.ScreenToWorldPoint(Input.mousePosition);// - transform.position;
                _movement.z = 0;

                //_rigidbody2D.simulated = false;
                _rigidbody2D.gravityScale = 0.0f;
            }
            #if UNITY_ANDROID && !UNITY_EDITOR
            foreach (Touch touch in Input.touches)
            {
                if (touch.phase == TouchPhase.Began && (_onGround || _player.State == CharacterState.Left || _player.State == CharacterState.Right || _player.State == CharacterState.Up))
                {
                    _onGround = false;
                    _player.State = CharacterState.Jump;

                    _movement = Camera.main.ScreenToWorldPoint(touch.position);// - transform.position;
                    _movement.z = 0;

                    //_rigidbody2D.simulated = false;
                    _rigidbody2D.gravityScale = 0.0f;
                }
            }
            #endif
        }

        private void CheckGround()
        {
            Collider2D hit = Physics2D.OverlapCircle(_groundCheck.position, _groundDistance, _ground);
            _onGround = hit;
            hit = Physics2D.OverlapCircle(_leftCheck.position, _groundDistance, _ground);
            _onLeft = hit;
            if (_onLeft)
            {
                //_rigidbody2D.simulated = false;
                _rigidbody2D.gravityScale = 0.0f;
                _player.State = CharacterState.Left;
            }
            hit = Physics2D.OverlapCircle(_rightCheck.position, _groundDistance, _ground);
            _onRight = hit;
            if (_onRight)
            {
                //_rigidbody2D.simulated = false;
                _rigidbody2D.gravityScale = 0.0f;
                _player.State = CharacterState.Right;
            }
            hit = Physics2D.OverlapCircle(_upCheck.position, _groundDistance, _ground);
            _onUp = hit;
            if (_onUp)
            {
                //_rigidbody2D.simulated = false;
                _rigidbody2D.gravityScale = 0.0f;
                _player.State = CharacterState.Up;
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

        public void Restart()
        {
            _onGround = true;
            _player.State = CharacterState.Idle;
            _rigidbody2D.gravityScale = 1.0f;
        }

#endregion

    }
}