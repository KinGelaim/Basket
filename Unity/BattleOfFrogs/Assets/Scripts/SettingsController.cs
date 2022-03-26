using UnityEngine;
using UnityEngine.UI;


namespace BattleOfFrogs
{
    public sealed class SettingsController : MonoBehaviour
    {
        #region Fields

        [SerializeField] private Text _textKeyCodeLeftTopAttackPlayer1;
        [SerializeField] private Text _textKeyCodeTopAttackPlayer1;
        [SerializeField] private Text _textKeyCodeRightTopAttackPlayer1;
        [SerializeField] private Text _textKeyCodeLeftPlayer1;
        [SerializeField] private Text _textKeyCodeRightPlayer1;
        [SerializeField] private Text _textKeyCodeFightPlayer1;

        [SerializeField] private Text _textKeyCodeLeftTopAttackPlayer2;
        [SerializeField] private Text _textKeyCodeTopAttackPlayer2;
        [SerializeField] private Text _textKeyCodeRightTopAttackPlayer2;
        [SerializeField] private Text _textKeyCodeLeftPlayer2;
        [SerializeField] private Text _textKeyCodeRightPlayer2;
        [SerializeField] private Text _textKeyCodeFightPlayer2;

        #endregion


        #region Properties

        public KeyCode KeyCodeLeftTopAttackPlayer1 { get; set; }
        public KeyCode KeyCodeTopAttackPlayer1 { get; set; }
        public KeyCode KeyCodeRightTopAttackPlayer1 { get; set; }
        public KeyCode KeyCodeLeftPlayer1 { get; set; }
        public KeyCode KeyCodeRightPlayer1 { get; set; }
        public KeyCode KeyCodeFightPlayer1 { get; set; }

        public KeyCode KeyCodeLeftTopAttackPlayer2 { get; set; }
        public KeyCode KeyCodeTopAttackPlayer2 { get; set; }
        public KeyCode KeyCodeRightTopAttackPlayer2 { get; set; }
        public KeyCode KeyCodeLeftPlayer2 { get; set; }
        public KeyCode KeyCodeRightPlayer2 { get; set; }
        public KeyCode KeyCodeFightPlayer2 { get; set; }

        #endregion


        private void Start()
        {
            GetAllConfig();
            SeeAllConfig();
        }

        private void GetAllConfig()
        {
            #region Player1

            KeyCodeLeftTopAttackPlayer1 = (KeyCode)PlayerPrefs.GetInt("KeyCodeLeftTopAttackPlayer1");
            if (KeyCodeLeftTopAttackPlayer1 == KeyCode.None)
                KeyCodeLeftTopAttackPlayer1 = KeyCode.Q;

            KeyCodeTopAttackPlayer1 = (KeyCode)PlayerPrefs.GetInt("KeyCodeTopAttackPlayer1");
            if (KeyCodeTopAttackPlayer1 == KeyCode.None)
                KeyCodeTopAttackPlayer1 = KeyCode.W;

            KeyCodeRightTopAttackPlayer1 = (KeyCode)PlayerPrefs.GetInt("KeyCodeRightTopAttackPlayer1");
            if (KeyCodeRightTopAttackPlayer1 == KeyCode.None)
                KeyCodeRightTopAttackPlayer1 = KeyCode.E;

            KeyCodeLeftPlayer1 = (KeyCode)PlayerPrefs.GetInt("KeyCodeLeftPlayer1");
            if (KeyCodeLeftPlayer1 == KeyCode.None)
                KeyCodeLeftPlayer1 = KeyCode.A;

            KeyCodeRightPlayer1 = (KeyCode)PlayerPrefs.GetInt("KeyCodeRightPlayer1");
            if (KeyCodeRightPlayer1 == KeyCode.None)
                KeyCodeRightPlayer1 = KeyCode.D;

            KeyCodeFightPlayer1 = (KeyCode)PlayerPrefs.GetInt("KeyCodeFightPlayer1");
            if (KeyCodeFightPlayer1 == KeyCode.None)
                KeyCodeFightPlayer1 = KeyCode.S;

            #endregion


            #region Player2

            KeyCodeLeftTopAttackPlayer2 = (KeyCode)PlayerPrefs.GetInt("KeyCodeLeftTopAttackPlayer2");
            if (KeyCodeLeftTopAttackPlayer2 == KeyCode.None)
                KeyCodeLeftTopAttackPlayer2 = KeyCode.Keypad7;

            KeyCodeTopAttackPlayer2 = (KeyCode)PlayerPrefs.GetInt("KeyCodeTopAttackPlayer2");
            if (KeyCodeTopAttackPlayer2 == KeyCode.None)
                KeyCodeTopAttackPlayer2 = KeyCode.Keypad8;

            KeyCodeRightTopAttackPlayer2 = (KeyCode)PlayerPrefs.GetInt("KeyCodeRightTopAttackPlayer2");
            if (KeyCodeRightTopAttackPlayer2 == KeyCode.None)
                KeyCodeRightTopAttackPlayer2 = KeyCode.Keypad9;

            KeyCodeLeftPlayer2 = (KeyCode)PlayerPrefs.GetInt("KeyCodeLeftPlayer2");
            if (KeyCodeLeftPlayer2 == KeyCode.None)
                KeyCodeLeftPlayer2 = KeyCode.Keypad4;

            KeyCodeRightPlayer2 = (KeyCode)PlayerPrefs.GetInt("KeyCodeRightPlayer2");
            if (KeyCodeRightPlayer2 == KeyCode.None)
                KeyCodeRightPlayer2 = KeyCode.Keypad6;

            KeyCodeFightPlayer2 = (KeyCode)PlayerPrefs.GetInt("KeyCodeFightPlayer2");
            if (KeyCodeFightPlayer2 == KeyCode.None)
                KeyCodeFightPlayer2 = KeyCode.Keypad5;

            #endregion
        }

        public void SeeAllConfig()
        {
            if (_textKeyCodeLeftTopAttackPlayer1 != null)
                _textKeyCodeLeftTopAttackPlayer1.text = KeyCodeLeftTopAttackPlayer1.ToString();
            if (_textKeyCodeTopAttackPlayer1 != null)
                _textKeyCodeTopAttackPlayer1.text = KeyCodeTopAttackPlayer1.ToString();
            if (_textKeyCodeRightTopAttackPlayer1 != null)
                _textKeyCodeRightTopAttackPlayer1.text = KeyCodeRightTopAttackPlayer1.ToString();
            if (_textKeyCodeLeftPlayer1 != null)
                _textKeyCodeLeftPlayer1.text = KeyCodeLeftPlayer1.ToString();
            if (_textKeyCodeRightPlayer1 != null)
                _textKeyCodeRightPlayer1.text = KeyCodeRightPlayer1.ToString();
            if (_textKeyCodeFightPlayer1 != null)
                _textKeyCodeFightPlayer1.text = KeyCodeFightPlayer1.ToString();

            if (_textKeyCodeLeftTopAttackPlayer2 != null)
                _textKeyCodeLeftTopAttackPlayer2.text = KeyCodeLeftTopAttackPlayer2.ToString();
            if (_textKeyCodeTopAttackPlayer2 != null)
                _textKeyCodeTopAttackPlayer2.text = KeyCodeTopAttackPlayer2.ToString();
            if (_textKeyCodeRightTopAttackPlayer2 != null)
                _textKeyCodeRightTopAttackPlayer2.text = KeyCodeRightTopAttackPlayer2.ToString();
            if (_textKeyCodeLeftPlayer2 != null)
                _textKeyCodeLeftPlayer2.text = KeyCodeLeftPlayer2.ToString();
            if (_textKeyCodeRightPlayer2 != null)
                _textKeyCodeRightPlayer2.text = KeyCodeRightPlayer2.ToString();
            if (_textKeyCodeFightPlayer2 != null)
                _textKeyCodeFightPlayer2.text = KeyCodeFightPlayer2.ToString();
        }

        public void SaveAllConfig()
        {
            PlayerPrefs.SetInt("KeyCodeLeftTopAttackPlayer1", (int)KeyCodeLeftTopAttackPlayer1);
            PlayerPrefs.SetInt("KeyCodeTopAttackPlayer1", (int)KeyCodeTopAttackPlayer1);
            PlayerPrefs.SetInt("KeyCodeRightTopAttackPlayer1", (int)KeyCodeRightTopAttackPlayer1);
            PlayerPrefs.SetInt("KeyCodeLeftPlayer1", (int)KeyCodeLeftPlayer1);
            PlayerPrefs.SetInt("KeyCodeRightPlayer1", (int)KeyCodeRightPlayer1);
            PlayerPrefs.SetInt("KeyCodeFightPlayer1", (int)KeyCodeFightPlayer1);

            PlayerPrefs.SetInt("KeyCodeLeftTopAttackPlayer2", (int)KeyCodeLeftTopAttackPlayer2);
            PlayerPrefs.SetInt("KeyCodeTopAttackPlayer2", (int)KeyCodeTopAttackPlayer2);
            PlayerPrefs.SetInt("KeyCodeRightTopAttackPlayer2", (int)KeyCodeRightTopAttackPlayer2);
            PlayerPrefs.SetInt("KeyCodeLeftPlayer2", (int)KeyCodeLeftPlayer2);
            PlayerPrefs.SetInt("KeyCodeRightPlayer2", (int)KeyCodeRightPlayer2);
            PlayerPrefs.SetInt("KeyCodeFightPlayer2", (int)KeyCodeFightPlayer2);
        }
    }
}