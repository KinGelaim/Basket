using UnityEngine;


namespace SwiftPickaxe
{
    public class PointerOfDig : MonoBehaviour
    {
        private void OnTriggerEnter2D(Collider2D collision)
        {
            Block block = collision.GetComponent<Block>();
            if (block)
            {
                block.OnPointerEnter();
            }
        }

        private void OnTriggerExit2D(Collider2D collision)
        {
            Block block = collision.GetComponent<Block>();
            if (block)
            {
                block.OnPointerExit();
            }
        }
    }
}