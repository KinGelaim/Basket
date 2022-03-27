using System;


namespace AvegaGamesTest1
{
    public sealed class PlayerController
    {
        #region Fields

        public Action DiePlayerAction;

        private readonly TextController _textController;

        private int _maxHealth = 100;
        private int _minHealth = 0;
        private int _health = 0;

        private int _countRed = 0;
        private int _countYellow = 0;
        private int _countGreen = 0;

        #endregion


        public PlayerController(TextController textController)
        {
            _textController = textController;

            DiePlayerAction = () => { };
            _health = _maxHealth;

            _textController.SetHPText(_health);
        }


        #region Methods

        public void ChangeHP(int count)
        {
            _health += count;
            _textController.SetHPText(_health);

            if (_health <= _minHealth)
                DiePlayerAction?.Invoke();
        }

        public void ChangeCountBoxes(BoxMaterial boxMaterial)
        {
            switch (boxMaterial)
            {
                case BoxMaterial.Red:
                    _countRed++;
                    break;
                case BoxMaterial.Yellow:
                    _countYellow++;
                    break;
                case BoxMaterial.Green:
                    _countGreen++;
                    break;
                default:
                    break;
            }
            _textController.ChangeCountColor(_countRed, _countYellow, _countGreen);
        }

        #endregion
    }
}