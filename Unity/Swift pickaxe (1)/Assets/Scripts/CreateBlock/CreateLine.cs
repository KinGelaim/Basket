using System.Collections;
using System.Collections.Generic;
using UnityEngine;

namespace SwiftPickaxe
{
    public class CreateLine : MonoBehaviour
    {
        private CreateBlock[] _createBlocks;
        private CreateBedRock[] _createBedRocks;
        private TestFactory _blockFactory;



        private void Awake()
        {
            _createBlocks = FindObjectsOfType<CreateBlock>();
            _createBedRocks = FindObjectsOfType<CreateBedRock>();
            _blockFactory = new TestFactory();

            for (int i = 0; i < _createBlocks.Length; i++)
            {
                _createBlocks[i].SetFactory(_blockFactory);
            }
        }

        public void CreateBlocks()
        {
            for (int i = 0; i < _createBedRocks.Length; i++)
            {
                _createBedRocks[i].Create();
            }
            for (int i = 0; i < _createBlocks.Length; i++)
            {
                _createBlocks[i].Create();
            }
            this.transform.position = this.gameObject.transform.position + Vector3.down;
        }
    }
}
