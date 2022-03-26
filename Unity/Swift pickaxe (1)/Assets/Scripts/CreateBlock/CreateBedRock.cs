using System.Collections;
using System.Collections.Generic;
using UnityEngine;

namespace SwiftPickaxe
{
    public class CreateBedRock : MonoBehaviour
    {
        private GameObject _bedRock;

        private void Awake()
        {
            _bedRock = Resources.Load<GameObject>("Prefabs/BedRock");
        }
        public void Create()
        {
            Instantiate(_bedRock, this.transform.position, this.transform.rotation);
        }
    }
}
