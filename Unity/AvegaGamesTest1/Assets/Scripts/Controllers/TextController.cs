using UnityEngine.UI;


namespace AvegaGamesTest1
{
    public sealed class TextController
    {
        #region Fields

        private Text _textAmmo;
        private Text _textHP;

        private Text _textRed;
        private Text _textYellow;
        private Text _textGreen;

        #endregion


        public TextController(Text textAmmo, Text textHP, Text textRed, Text textYellow, Text textGreen)
        {
            _textAmmo = textAmmo;
            _textHP = textHP;

            _textRed = textRed;
            _textYellow = textYellow;
            _textGreen = textGreen;
        }


        #region Methods

        public void SetAmmoText(int ammo)
        {
            _textAmmo.text = $"AMMO: {ammo.ToString()}";
        }

        public void SetHPText(int hp)
        {
            _textHP.text = $"HP: {hp}";
        }

        public void ChangeCountColor(int countRed, int countYellow, int countGreen)
        {
            _textRed.text = $"RED: {countRed}";
            _textYellow.text = $"RED: {countYellow}";
            _textGreen.text = $"RED: {countGreen}";
        }

        #endregion
    }
}