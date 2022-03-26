using UnityEngine;


namespace HungryCat
{
    public class PlayerController : MonoBehaviour
    {

        #region Fields

        private CharacterState _state = CharacterState.Idle;

        #endregion


        #region Properties

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

        #endregion
    }
}