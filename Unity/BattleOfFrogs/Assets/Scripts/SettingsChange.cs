using UnityEngine;


namespace BattleOfFrogs
{
    public class SettingsChange : MonoBehaviour
    {
        [SerializeField] private SettingsController _settingsController;

        private bool isLeftTopAttackPlayer1 = false;
        private bool isTopAttackPlayer1 = false;
        private bool isRightTopAttackPlayer1 = false;
        private bool isLeftPlayer1 = false;
        private bool isRightPlayer1 = false;
        private bool isFightPlayer1 = false;

        private bool isLeftTopAttackPlayer2 = false;
        private bool isTopAttackPlayer2 = false;
        private bool isRightTopAttackPlayer2 = false;
        private bool isLeftPlayer2 = false;
        private bool isRightPlayer2 = false;
        private bool isFightPlayer2 = false;

        private KeyCode[] _allKeys;

        private void Start()
        {
            _allKeys = new KeyCode[]
            {
                KeyCode.Q,
                KeyCode.W,
                KeyCode.E,
                KeyCode.R,
                KeyCode.T,
                KeyCode.Y,
                KeyCode.U,
                KeyCode.I,
                KeyCode.O,
                KeyCode.P,
                KeyCode.A,
                KeyCode.S,
                KeyCode.D,
                KeyCode.F,
                KeyCode.G,
                KeyCode.H,
                KeyCode.J,
                KeyCode.K,
                KeyCode.L,
                KeyCode.Z,
                KeyCode.X,
                KeyCode.C,
                KeyCode.V,
                KeyCode.B,
                KeyCode.N,
                KeyCode.M,
                KeyCode.Alpha1,
                KeyCode.Alpha2,
                KeyCode.Alpha3,
                KeyCode.Alpha4,
                KeyCode.Alpha5,
                KeyCode.Alpha6,
                KeyCode.Alpha7,
                KeyCode.Alpha8,
                KeyCode.Alpha9,
                KeyCode.Keypad0,
                KeyCode.Keypad1,
                KeyCode.Keypad2,
                KeyCode.Keypad3,
                KeyCode.Keypad4,
                KeyCode.Keypad5,
                KeyCode.Keypad6,
                KeyCode.Keypad7,
                KeyCode.Keypad8,
                KeyCode.Keypad9,
                KeyCode.JoystickButton0,
                KeyCode.JoystickButton1,
                KeyCode.JoystickButton2,
                KeyCode.JoystickButton3,
                KeyCode.JoystickButton4,
                KeyCode.JoystickButton5,
                KeyCode.JoystickButton6,
                KeyCode.JoystickButton7,
                KeyCode.JoystickButton8,
                KeyCode.JoystickButton9,
                KeyCode.JoystickButton10,
                KeyCode.JoystickButton11,
                KeyCode.JoystickButton12
            };
        }


        private void Update()
        {
            #region Player1
            if (isLeftTopAttackPlayer1)
            {
                KeyCode kc = GetKeyDownReturn();
                if (kc != KeyCode.None)
                {
                    _settingsController.KeyCodeLeftTopAttackPlayer1 = kc;
                    _settingsController.SeeAllConfig();
                    _settingsController.SaveAllConfig();
                    isLeftTopAttackPlayer1 = false;
                }
            }
            if (isTopAttackPlayer1)
            {
                KeyCode kc = GetKeyDownReturn();
                if (kc != KeyCode.None)
                {
                    _settingsController.KeyCodeTopAttackPlayer1 = kc;
                    _settingsController.SeeAllConfig();
                    _settingsController.SaveAllConfig();
                    isTopAttackPlayer1 = false;
                }
            }
            if (isRightTopAttackPlayer1)
            {
                KeyCode kc = GetKeyDownReturn();
                if (kc != KeyCode.None)
                {
                    _settingsController.KeyCodeRightTopAttackPlayer1 = kc;
                    _settingsController.SeeAllConfig();
                    _settingsController.SaveAllConfig();
                    isRightTopAttackPlayer1 = false;
                }
            }
            if (isLeftPlayer1)
            {
                KeyCode kc = GetKeyDownReturn();
                if (kc != KeyCode.None)
                {
                    _settingsController.KeyCodeLeftPlayer1 = kc;
                    _settingsController.SeeAllConfig();
                    _settingsController.SaveAllConfig();
                    isLeftPlayer1 = false;
                }
            }
            if (isRightPlayer1)
            {
                KeyCode kc = GetKeyDownReturn();
                if (kc != KeyCode.None)
                {
                    _settingsController.KeyCodeRightPlayer1 = kc;
                    _settingsController.SeeAllConfig();
                    _settingsController.SaveAllConfig();
                    isRightPlayer1 = false;
                }
            }
            if (isFightPlayer1)
            {
                KeyCode kc = GetKeyDownReturn();
                if (kc != KeyCode.None)
                {
                    _settingsController.KeyCodeFightPlayer1 = kc;
                    _settingsController.SeeAllConfig();
                    _settingsController.SaveAllConfig();
                    isFightPlayer1 = false;
                }
            }
            #endregion

            #region Player2
            if (isLeftTopAttackPlayer2)
            {
                KeyCode kc = GetKeyDownReturn();
                if (kc != KeyCode.None)
                {
                    _settingsController.KeyCodeLeftTopAttackPlayer2 = kc;
                    _settingsController.SeeAllConfig();
                    _settingsController.SaveAllConfig();
                    isLeftTopAttackPlayer2 = false;
                }
            }
            if (isTopAttackPlayer2)
            {
                KeyCode kc = GetKeyDownReturn();
                if (kc != KeyCode.None)
                {
                    _settingsController.KeyCodeTopAttackPlayer2 = kc;
                    _settingsController.SeeAllConfig();
                    _settingsController.SaveAllConfig();
                    isTopAttackPlayer2 = false;
                }
            }
            if (isRightTopAttackPlayer2)
            {
                KeyCode kc = GetKeyDownReturn();
                if (kc != KeyCode.None)
                {
                    _settingsController.KeyCodeRightTopAttackPlayer2 = kc;
                    _settingsController.SeeAllConfig();
                    _settingsController.SaveAllConfig();
                    isRightTopAttackPlayer2 = false;
                }
            }
            if (isLeftPlayer2)
            {
                KeyCode kc = GetKeyDownReturn();
                if (kc != KeyCode.None)
                {
                    _settingsController.KeyCodeLeftPlayer2 = kc;
                    _settingsController.SeeAllConfig();
                    _settingsController.SaveAllConfig();
                    isLeftPlayer2 = false;
                }
            }
            if (isRightPlayer2)
            {
                KeyCode kc = GetKeyDownReturn();
                if (kc != KeyCode.None)
                {
                    _settingsController.KeyCodeRightPlayer2 = kc;
                    _settingsController.SeeAllConfig();
                    _settingsController.SaveAllConfig();
                    isRightPlayer2 = false;
                }
            }
            if (isFightPlayer2)
            {
                KeyCode kc = GetKeyDownReturn();
                if (kc != KeyCode.None)
                {
                    _settingsController.KeyCodeFightPlayer2 = kc;
                    _settingsController.SeeAllConfig();
                    _settingsController.SaveAllConfig();
                    isFightPlayer2 = false;
                }
            }
            #endregion
        }

        private KeyCode GetKeyDownReturn()
        {
            for (int i = 0; i < _allKeys.Length; i++)
            {
                if (Input.GetKeyDown(_allKeys[i]))
                    return _allKeys[i];
            }
            return KeyCode.None;
        }

        #region Player1
        public void SetLeftTopAttackPlayer1()
        {
            isLeftTopAttackPlayer1 = true;
        }

        public void SetTopAttackPlayer1()
        {
            isTopAttackPlayer1 = true;
        }

        public void SetRightTopAttackPlayer1()
        {
            isRightTopAttackPlayer1 = true;
        }

        public void SetLeftPlayer1()
        {
            isLeftPlayer1 = true;
        }

        public void SetRightPlayer1()
        {
            isRightPlayer1 = true;
        }

        public void SetFightPlayer1()
        {
            isFightPlayer1 = true;
        }
        #endregion

        #region Player2
        public void SetLeftTopAttackPlayer2()
        {
            isLeftTopAttackPlayer2 = true;
        }

        public void SetTopAttackPlayer2()
        {
            isTopAttackPlayer2 = true;
        }

        public void SetRightTopAttackPlayer2()
        {
            isRightTopAttackPlayer2 = true;
        }

        public void SetLeftPlayer2()
        {
            isLeftPlayer2 = true;
        }

        public void SetRightPlayer2()
        {
            isRightPlayer2 = true;
        }

        public void SetFightPlayer2()
        {
            isFightPlayer2 = true;
        }
        #endregion
    }
}