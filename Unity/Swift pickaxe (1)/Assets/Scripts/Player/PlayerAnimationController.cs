using UnityEngine;


namespace SwiftPickaxe
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
            _playerController = _player.GetComponent<PlayerController>();
            switch (_playerController.TypePickaxe)
            {
                case TypeItem.None:
                    _playerController.GetComponent<Animator>().runtimeAnimatorController = Resources.Load<RuntimeAnimatorController>("Animators/miner_0");
                    break;
                case TypeItem.Gold:
                    _playerController.GetComponent<Animator>().runtimeAnimatorController = Resources.Load<RuntimeAnimatorController>("Animators/miner_gold");
                    break;
                case TypeItem.Diamond:
                    _playerController.GetComponent<Animator>().runtimeAnimatorController = Resources.Load<RuntimeAnimatorController>("Animators/miner_diamond");
                    break;
                case TypeItem.Sapphire:
                    _playerController.GetComponent<Animator>().runtimeAnimatorController = Resources.Load<RuntimeAnimatorController>("Animators/miner_sapphire");
                    break;
                case TypeItem.Emerald:
                    _playerController.GetComponent<Animator>().runtimeAnimatorController = Resources.Load<RuntimeAnimatorController>("Animators/miner_emerald");
                    break;
                case TypeItem.Ruby:
                    _playerController.GetComponent<Animator>().runtimeAnimatorController = Resources.Load<RuntimeAnimatorController>("Animators/miner_ruby");
                    break;
            }
        }

        private void Update()
        {
            string postfix = "";

            switch(_playerController.TypePickaxe)
            {
                case TypeItem.Diamond:
                    postfix = "Diamond";
                    break;
                case TypeItem.Emerald:
                    postfix = "Emerald";
                    break;
                case TypeItem.Gold:
                    postfix = "Gold";
                    break;
                case TypeItem.Ruby:
                    postfix = "Ruby";
                    break;
                case TypeItem.Sapphire:
                    postfix = "Sapphire";
                    break;
            }

            switch (_playerController.State)
            {
                case CharacterState.Idle:
                    _animator.Play("MinerIdle" + postfix);
                    break;
                case CharacterState.Dig:
                    _animator.Play("MinerDig" + postfix);
                    break;
                case CharacterState.Walk:
                    _animator.Play("MinerWalk" + postfix);
                    break;
                case CharacterState.Jump:
                    _animator.Play("MinerJump" + postfix);
                    break;
            }
        }

        #endregion
    }
}