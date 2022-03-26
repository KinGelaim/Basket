using System.Collections;
using System.Collections.Generic;
using UnityEngine;

namespace SwiftPickaxe
{
    public class LavaLine : MonoBehaviour
    {
        private GameObject _lava;

        private void Awake()
        {
            _lava = Resources.Load<GameObject>("Prefabs/Lava");
        }


        private void OnTriggerEnter2D(Collider2D collision)
        {
            if (!collision.CompareTag("Lava"))
            {
                Instantiate(_lava, collision.transform.position, collision.transform.rotation);
                collision.gameObject.SetActive(false);
            }
        }
    }
}
