using System.Collections;
using System.Collections.Generic;
using UnityEngine;

namespace SwiftPickaxe
{
    public class LavaCheck : MonoBehaviour
    {
        private void OnTriggerEnter2D(Collider2D collision)
        {
            if (collision.CompareTag("Void"))
            {
                var lava = Resources.Load<GameObject>("Prefabs/Lava");
                Object.Instantiate(lava, collision.transform.position, collision.transform.rotation);
                collision.gameObject.GetComponent<Void>().DestroyBlock();
            }

            if (collision.CompareTag("Lava"))
            {
                this.gameObject.SetActive(false);
            }
        }
    }
}
