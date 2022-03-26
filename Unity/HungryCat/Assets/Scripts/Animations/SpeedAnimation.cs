using UnityEngine;


namespace HungryCat
{
    public class SpeedAnimation : MonoBehaviour
    {
        [SerializeField] private float _minSpeed = 0.3f;
        [SerializeField] private float _maxSpeed = 2.7f;

        private void Start()
        {
            GetComponent<Animator>().speed = Random.Range(_minSpeed, _maxSpeed);
        }
    }
}