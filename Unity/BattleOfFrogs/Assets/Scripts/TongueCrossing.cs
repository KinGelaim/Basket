using UnityEngine;


namespace BattleOfFrogs
{
    public class TongueCrossing : MonoBehaviour
    {
        [SerializeField] private GameManager _gameManager;

        [Header("Tongue1")]
        [SerializeField] private Transform _beginTongueOne; // x1, y1
        [SerializeField] private Transform _endTongueOne;   // x2, y2

        [Header("Tongue2")]
        [SerializeField] private Transform _beginTongueTwo; // x3, y3
        [SerializeField] private Transform _endTongueTwo;   // x4, y4

        private bool isCrossing = false;

        private void Update()
        {
            if (IsCrossing())
            {
                if (!isCrossing)
                {
                    _gameManager.FightMenu();
                    isCrossing = true;
                }
            }
            else
                isCrossing = false;
        }

        private bool IsCrossing()
        {
            float x1 = _beginTongueOne.position.x;
            float y1 = _beginTongueOne.position.y;
            float x2 = _endTongueOne.position.x;
            float y2 = _endTongueOne.position.y;

            float x3 = _beginTongueTwo.position.x;
            float y3 = _beginTongueTwo.position.y;
            float x4 = _endTongueTwo.position.x;
            float y4 = _endTongueTwo.position.y;

            float x = ((x1 * y2 - x2 * y1) * (x4 - x3) - (x3 * y4 - x4 * y3) * (x2 - x1)) / ((y1 - y2) * (x4 - x3) - (y3 - y4) * (x2 - x1));
            float y = ((y3 - y4) * x - (x3 * y4 - x4 * y3)) / (x4 - x3);

            if((x1 <= x && x2 >= x && x3 <= x && x4 >=x) || (y1 <= y && y2 >= y && y3 <= y && y4 >= y))
            {
                return true;
            }
            return false;
        }
    }
}
