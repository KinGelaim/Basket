using UnityEngine;


namespace HungryCat
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
                    _animator.Play("Cat_Idle");
                    break;
                case CharacterState.Walk:
                    _animator.Play("Cat_Walk");
                    break;
                case CharacterState.Jump:
                    break;
                case CharacterState.Die:
                    _animator.Play("Cat_Dead");
                    break;
            }
        }

        #endregion
    }
}