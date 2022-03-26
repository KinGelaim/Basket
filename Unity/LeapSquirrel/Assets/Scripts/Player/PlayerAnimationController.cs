using UnityEngine;


namespace LeapSquirrel
{
    public class PlayerAnimationController : MonoBehaviour
    {

        #region Fields

        private Animator _animator;
        private PlayerController _playerController;

        #endregion


        #region UmutyMethods

        private void Start()
        {
            _playerController = GetComponent<PlayerController>();
            _animator = GetComponent<Animator>();
        }

        private void Update()
        {
            switch (_playerController.State)
            {
                case CharacterState.Idle:
                    _animator.Play("Squirrel_Idle");
                    break;
                case CharacterState.Run:
                    _animator.Play("Squirrel_Run");
                    break;
                case CharacterState.Jump:
                    _animator.Play("Squirrel_Jump");
                    break;
                case CharacterState.Left:
                    _animator.Play("Squirrel_Left");
                    break;
                case CharacterState.Right:
                    _animator.Play("Squirrel_Right");
                    break;
                case CharacterState.Up:
                    _animator.Play("Squirrel_Up");
                    break;
            }
        }

        #endregion
    }
}