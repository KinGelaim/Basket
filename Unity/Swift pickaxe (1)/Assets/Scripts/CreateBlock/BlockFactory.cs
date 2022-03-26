using UnityEngine;
using static UnityEngine.Random;

namespace SwiftPickaxe
{
    public class BlockFactory 
    {
        private int _randomBlock;
        private GameObject _loadBlock;

        private GameObject _void;
        private GameObject _stoun;
        private GameObject _diamodStoun;
        private GameObject _block;


        public BlockFactory()
        {
            _void = Resources.Load<GameObject>("Prefabs/Void");
            _stoun = Resources.Load<GameObject>("Prefabs/Stoun");
            _diamodStoun = Resources.Load<GameObject>("Prefabs/DiamodStoun");
            _block = Resources.Load<GameObject>("Prefabs/Block");
        }

        public void Create(Transform spawnPoint)
        {
            _randomBlock = Range(1, 5);

            switch (_randomBlock)
            {
                case 1:
                    _loadBlock = _void;
                    break;
                case 2:
                    _loadBlock = _stoun;
                    break;
                case 3:
                    _loadBlock = _diamodStoun;
                    break;
                case 4:
                    _loadBlock = _block;
                    break;
            }

            Object.Instantiate(_loadBlock, spawnPoint.position, spawnPoint.rotation);
        }
    }
}
