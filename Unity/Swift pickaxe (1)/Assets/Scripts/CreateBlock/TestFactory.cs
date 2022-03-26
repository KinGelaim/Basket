using UnityEngine;
using static UnityEngine.Random;

namespace SwiftPickaxe
{
    public class TestFactory
    {
        private int _randomBlock;
        private Block _loadBlock;
        private string _blokType = "";

        public TestFactory()
        {
            ServiceLocator.SetService<BlockPool>(new BlockPool(2));
        }


        public void Create(Transform spawnPoint)
        {
            _randomBlock = Range(1, 256);

            if (_randomBlock >= 1 && _randomBlock <= 5)
            {
                _blokType = "Void";
            }

            if (_randomBlock >= 6 && _randomBlock <= 35)
            {
                _blokType = "Stoun";
            }

            if (_randomBlock >= 36 && _randomBlock <= 45)
            {
                _blokType = "DiamodStoun";
            }

            if (_randomBlock >= 46 && _randomBlock <= 85)
            {
                _blokType = "Earth";
            }

            if (_randomBlock >= 86 && _randomBlock <= 105)
            {
                _blokType = "BedRock";
            }

            if (_randomBlock >= 106 && _randomBlock <= 117)
            {
                _blokType = "GoldStone";
            }

            if (_randomBlock >= 118 && _randomBlock <= 125)
            {
                _blokType = "SapphireStone";
            }

            if (_randomBlock >= 126 && _randomBlock <= 145)
            {
                _blokType = "Andesite";
            }

            if (_randomBlock >= 146 && _randomBlock <= 165)
            {
                _blokType = "Marble";
            }

            if (_randomBlock >= 166 && _randomBlock <= 171)
            {
                _blokType = "EmeraldStone";
            }

            if (_randomBlock >= 172 && _randomBlock <= 196)
            {
                _blokType = "Sand";
            }

            if (_randomBlock >= 197 && _randomBlock <= 221)
            {
                _blokType = "Limestone";
            }

            if (_randomBlock >= 222 && _randomBlock <= 251)
            {
                _blokType = "Chalk";
            }

            if (_randomBlock >= 252 && _randomBlock <= 255)
            {
                _blokType = "RubyStone";
            }

            _loadBlock = ServiceLocator.Resolve<BlockPool>().GetBlock(_blokType);
            _loadBlock.GetComponent<Block>().RestarBlock();



            _loadBlock.transform.position = spawnPoint.position;
            _loadBlock.gameObject.SetActive(true);
        }
    }
}