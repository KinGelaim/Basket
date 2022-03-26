using UnityEngine;


namespace LeapSquirrel
{
    public class PlayerController : MonoBehaviour
    {

        #region Fields

        private CharacterState _state = CharacterState.Idle;

        #endregion

        public CharacterState State
        {
            get
            {
                return _state;
            }
            set
            {
                _state = value;
            }
        }
    }
}