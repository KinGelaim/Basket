using UnityEngine;


namespace HungryCat
{
    public class EnemyCage
    {
        public GameObject gameObject;

        private float _timeLive = 0.0f;
        private int _secondAdd = 0;
        
        public int SecondAdd => _secondAdd;

        public EnemyCage(GameObject gameObject, float timeLive, int secondAdd = 0)
        {
            this.gameObject = gameObject;
            _timeLive = timeLive;
            _secondAdd = secondAdd;
        }

        public bool TickLive(float timeTick)
        {
            _timeLive -= timeTick;
            if (_timeLive <= 0)
                return true;
            return false;
        }
    }
}
