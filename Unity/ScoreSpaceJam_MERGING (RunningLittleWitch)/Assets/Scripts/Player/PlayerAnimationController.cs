using UnityEngine;


namespace ScoreSpaceJam_MERGING
{
    public class PlayerAnimationController : MonoBehaviour
    {

        #region Fields

        [SerializeField] private GameObject _player;
        [SerializeField] private Animator _animator;

        private PlayerController _playerController;

        #endregion


        #region UmutyMethods

        private void Start()
        {
            _playerController = gameObject.GetComponent<PlayerController>();
        }

        private void Update()
        {
            switch (_playerController.State)
            {
                case CharacterState.Idle:
                    //_animator.Play("LittleWitchIdle");
                    _animator.SetBool("IsJump", false);
                    _animator.SetBool("IsRun", false);
                    _animator.SetBool("IsSwitch", false);
                    break;
                case CharacterState.Run:
                    //_animator.Play("LittleWitchRun");
                    _animator.SetBool("IsRun", true);
                    _animator.SetBool("IsJump", false);
                    _animator.SetBool("IsSwitch", false);
                    break;
                case CharacterState.Jump:
                    //_animator.Play("LittleWitchJump");
                    _animator.SetBool("IsJump", true);
                    _animator.SetBool("IsRun", false);
                    _animator.SetBool("IsSwitch", false);
                    break;
                case CharacterState.Swap:
                    //_animator.Play("LittleWitchSwitch");
                    _animator.SetBool("IsSwitch", true);
                    break;
                case CharacterState.Die:
                    //_animator.Play("LittleWitchDeath");
                    _animator.SetBool("IsDeath", true);
                    break;
            }
        }

        #endregion
    }
}