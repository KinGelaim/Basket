using System.Collections;
using System.Collections.Generic;
using UnityEngine;

namespace SwiftPickaxe
{
    public class CreateBlock : MonoBehaviour
    {
        private TestFactory _blockFactory;

        public void Create()
        {
            _blockFactory.Create(this.transform);
        }

        public void SetFactory(TestFactory blockFactory)
        {
            _blockFactory = blockFactory;
        }
    }
}
