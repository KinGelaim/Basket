using System.Collections;
using UnityEngine;

namespace SwiftPickaxe
{
    public class Sand: Block
    {
        [SerializeField]private GameObject _checkPoint;

        [SerializeField]private float waitTime = 1f;

        new void Awake()
        {
            base.Awake();
            _checkPoint = this.transform.Find("CheckPoint").gameObject;

            this.transform.GetComponent<SpriteRenderer>().maskInteraction = SpriteMaskInteraction.VisibleInsideMask;

            Invoke("ActiveCheck", waitTime);
        }

        private void ActiveCheck()
        {
            _checkPoint.SetActive(true);
        }
    }
}
