using System.Collections;
using UnityEngine;


namespace SwiftPickaxe
{
    public class Lava : MonoBehaviour
    {
        private GameObject _upCheckPoint;
        private GameObject _downCheckPoint;
        private GameObject _leftRightCheckPoint;

        [SerializeField]private float _upWaitTime = 10;
        [SerializeField] private float _downWaitTime = 3;
        [SerializeField] private float _leftRightWaitTime = 5;

        private Timer _timer;

        private float _timeBuff;

        public int bufSpeed = 100;

        private void Awake()
        {
            _timer = FindObjectOfType<Timer>();
            _timeBuff = _timer.Second + _timer.Min * 60 + _timer.Hour * 60 * 60;
            _downWaitTime -= _timeBuff/ bufSpeed;
            _upWaitTime -= _timeBuff / bufSpeed;
            _leftRightWaitTime -= _timeBuff / bufSpeed;

            _upCheckPoint = this.transform.Find("UpCheckPoint").gameObject;
            _downCheckPoint = this.transform.Find("DownCheckPoint").gameObject;
            _leftRightCheckPoint = this.transform.Find("LeftRightCheckPoint").gameObject;

            this.transform.GetComponent<SpriteRenderer>().maskInteraction = SpriteMaskInteraction.VisibleInsideMask;

            StartCoroutine("DownWait");
            StartCoroutine("UpWait");
            StartCoroutine("LeftRightWait");


        }

        IEnumerator DownWait()
        {
            yield return new WaitForSeconds(_downWaitTime);
            _downCheckPoint.SetActive(true);
        }

        IEnumerator UpWait()
        {
            yield return new WaitForSeconds(_upWaitTime);
            _upCheckPoint.SetActive(true);
        }

        IEnumerator LeftRightWait()
        {
            yield return new WaitForSeconds(_leftRightWaitTime);
            _leftRightCheckPoint.SetActive(true);
        }

        private void OnTriggerEnter2D(Collider2D collision)
        {
            if (collision.CompareTag("Player"))
            {
                collision.gameObject.GetComponent<PlayerController>().Die();
            }
        }
    }
}
