using UnityEngine;


namespace SwiftPickaxe
{
    public class KeyboardController : MonoBehaviour
    {

        #region Fields

        [SerializeField] private Joystick _joystickMove;
        [SerializeField] private Joystick _joystickDig;
        [SerializeField] private Transform _pointerOfDig;

        [SerializeField] private LayerMask _ground = default;

        [SerializeField] private float _speed = 3.0f;
        [SerializeField] private float _heightJump = 2.7f;
        [SerializeField] private float _groundDistance = 0.838f;

        private PlayerController _player;
        private Animator _animator;
        private Rigidbody2D _rigidbody2D;

        private Vector2 _horizontalVelocity;

        private float _horizontalInput;
        private float _oldY = 0.0f;

        private bool _onFloor = false;
        private bool _onDown = true;
        private bool _isForward = true;

        #endregion


        #region UnityMethods

        private void Start()
        {
            _rigidbody2D = gameObject.GetComponent<Rigidbody2D>();
            _player = gameObject.GetComponent<PlayerController>();
            _animator = gameObject.GetComponent<Animator>();

            _joystickMove.gameObject.SetActive(false);
            _joystickDig.gameObject.SetActive(false);

            #if UNITY_EDITOR || UNITY_ANDROID
                _joystickMove.gameObject.SetActive(true);
                _joystickDig.gameObject.SetActive(true);
            #endif
        }

        private void Update()
        {
            CheckGround();
            Move();
            Jump();
            Dig();
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
            _horizontalInput = Input.GetAxisRaw("Horizontal");
            
            #if UNITY_EDITOR || UNITY_ANDROID
                _horizontalInput = _joystickMove.Horizontal;
            #endif

            _horizontalVelocity.Set(_horizontalInput * _speed, _rigidbody2D.velocity.y);
            _rigidbody2D.velocity = _horizontalVelocity;

            if (_onFloor)
                if (_horizontalInput != 0)
                    _player.State = CharacterState.Walk;

            if (_player.State != CharacterState.Dig)
            {
                if (_onFloor)
                {
                    if (_horizontalInput != 0)
                        _player.State = CharacterState.Walk;
                    else
                        _player.State = CharacterState.Idle;
                }
                else
                    _player.State = CharacterState.Jump;
            }
        }

        private void Dig()
        {
            float pointerY = transform.position.y + Mathf.Clamp(_joystickDig.Vertical, -1.05f, 0.5f);
            float pointerX = transform.position.x + Mathf.Clamp(_joystickDig.Horizontal, -0.5f, 0.5f);
            _pointerOfDig.position = new Vector3(pointerX, pointerY, 0.0f);
        }

        private void Jump()
        {
            if (((Input.GetKeyDown(KeyCode.W) || Input.GetKeyDown(KeyCode.Space)) && _onFloor) || (_joystickMove.Vertical > 0.7f && _onFloor && _onDown))
            {
                _oldY = transform.position.y;
                _onDown = false;
                _onFloor = false;
                _rigidbody2D.AddForce(new Vector2(0, _heightJump), ForceMode2D.Impulse);
                _player.State = CharacterState.Jump;
            }
            if (!_onDown)
            {
                if (_oldY < transform.position.y)
                {
                    _onDown = true;
                    _oldY = 0.0f;
                }
            }
        }

        private void CheckGround()
        {
            Debug.DrawRay(transform.position, Vector2.down, Color.red, _groundDistance);
            RaycastHit2D hit = Physics2D.Raycast(transform.position, Vector2.down, _groundDistance, _ground);
            if (hit != false)
            {
                _onFloor = true;
            }
            else
                _onFloor = false;
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

        private bool IsAnimationPlaying(string animationName)
        {
            // берем информацию о состоянии
            var animatorStateInfo = _animator.GetCurrentAnimatorStateInfo(0);
            // смотрим, есть ли в нем имя какой-то анимации, то возвращаем true
            if (animatorStateInfo.IsName(animationName))
                return true;

            return false;
        }

        #endregion

    }
}