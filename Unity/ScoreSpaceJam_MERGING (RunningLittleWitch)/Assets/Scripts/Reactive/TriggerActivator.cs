using UnityEngine;
using UnityEngine.Events;


namespace ScoreSpaceJam_MERGING
{
    public class TriggerActivator : MonoBehaviour
    {
        public UnityEvent OnTriggerActivation;
        public UnityEvent OnTriggerDisactivation;

        private void Start()
        {
            if (OnTriggerActivation == null)
                OnTriggerActivation = new UnityEvent();

            if (OnTriggerDisactivation == null)
                OnTriggerDisactivation = new UnityEvent();
        }

        private void OnTriggerEnter2D(Collider2D other)
        {
            OnTriggerActivation.Invoke();
        }

        private void OnTriggerExit2D(Collider2D other)
        {
            OnTriggerDisactivation.Invoke();
        }
    }
}