using System.Collections;
using UnityEngine;


namespace SwiftPickaxe
{
    public class PickupItem : MonoBehaviour
    {
        [SerializeField] private TypeItem _typeItem = TypeItem.None;

        public TypeItem TypeItem => _typeItem;

        private void Awake()
        {
            GetComponent<SpriteRenderer>().maskInteraction = SpriteMaskInteraction.VisibleInsideMask;
        }

        private void OnTriggerEnter2D(Collider2D collision)
        {
            if(collision.gameObject.CompareTag("Player"))
            {
                collision.gameObject.GetComponent<PlayerController>().NewItem(_typeItem);
                GetComponent<Rigidbody2D>().simulated = false;
                GetComponent<BoxCollider2D>().enabled = false;
                StartCoroutine(PlayOnDestroy());
            }
        }

        private IEnumerator PlayOnDestroy()
        {
            gameObject.GetComponent<AudioSource>().Play();
            yield return new WaitForSeconds(gameObject.GetComponent<AudioSource>().clip.length);
            Destroy(gameObject);
        }
    }
}