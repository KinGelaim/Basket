using UnityEngine;


namespace SwiftPickaxe
{
    public class SandCheck : MonoBehaviour
    {
        private void OnTriggerStay2D(Collider2D collision)
        {
            if (collision.CompareTag("Void"))
            {
                var sand = Resources.Load<GameObject>("Prefabs/Sand");
                Object.Instantiate(sand, collision.transform.position, collision.transform.rotation);
                collision.gameObject.GetComponent<Void>().DestroyBlock();
                this.gameObject.GetComponentInParent<Block>().DestroyBlock();
            }
        }
    }
}
