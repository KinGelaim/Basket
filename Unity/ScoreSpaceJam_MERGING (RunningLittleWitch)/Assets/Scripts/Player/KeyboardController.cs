using UnityEngine;


namespace ScoreSpaceJam_MERGING
{
    public class KeyboardController : MonoBehaviour
    {

        #region Fields

        [SerializeField] private LayerMask _ground = default;

        [SerializeField] private float _speed = 3.0f;
        [SerializeField] private float _heightJump = 2.7f;
        [SerializeField] private float _catHeightJump = 4.0f;
        [SerializeField] private float _hamsterHeightJump = 1.8f;
        [SerializeField] private float _groundDistance = 0.838f;

        private PlayerController _player;
        private Animator _animator;
        private Rigidbody2D _rigidbody2D;

        private Vector2 _horizontalVelocity;

        private float _horizontalInput;
        private float _oldY = 0.0f;
        private float _oldHeightJump;

        private bool _isForward = true;
        private bool _onFloor = false;
        private bool _onDown = true;

        private bool _isEnableControl = true;

        #endregion


        #region Properties

        public float Speed => _speed;

        #endregion


        #region UnityMethods

        private void Start()
        {
            _rigidbody2D = gameObject.GetComponent<Rigidbody2D>();
            _player = gameObject.GetComponent<PlayerController>();
            _animator = gameObject.GetComponent<Animator>();

            _oldHeightJump = _heightJump;
        }

        private void Update()
        {
            if (_isEnableControl)
            {
                CheckGround();
                Move();
                Jump();
            }
            else
            {
                _player.State = CharacterState.Idle;
                _horizontalVelocity.Set(0, 0);
                _rigidbody2D.velocity = _horizontalVelocity;
            }
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

        public void EnableControl(bool isEnable)
        {
            _isEnableControl = isEnable;
        }

        private void Move()
        {
            if (_player.State != CharacterState.Die)
            {
                _horizontalInput = Input.GetAxisRaw("Horizontal");
                _horizontalVelocity.Set(_horizontalInput * _speed, _rigidbody2D.velocity.y);
                _rigidbody2D.velocity = _horizontalVelocity;

                if (_onFloor)
                {
                    if (_horizontalInput != 0)
                        _player.State = CharacterState.Run;
                    else
                        _player.State = CharacterState.Idle;
                }
                else
                    _player.State = CharacterState.Jump;
            }
        }

        private void Jump()
        {
            if ((Input.GetKeyDown(KeyCode.W) || Input.GetKeyDown(KeyCode.Space)) && _onFloor)
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
            //Debug.DrawRay(transform.position, Vector2.down, Color.red, _groundDistance);
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

        #region Ghosts

        public void CatUp()
        {
            _heightJump = _catHeightJump;
        }

        public void CatDown()
        {
            _heightJump = _oldHeightJump;
        }

        public void HamsterUp()
        {
            _heightJump = _hamsterHeightJump;
            transform.localScale = new Vector3(0.5f, 0.5f, 1);
        }

        public void HamsterDown()
        {
            _heightJump = _oldHeightJump;
            transform.localScale = new Vector3(1, 1, 1);
        }

        #endregion

        #endregion

    }
}