using System.Collections;
using UnityEngine;


namespace SwiftPickaxe 
{
    public class FogOfWar : MonoBehaviour
    {
        [SerializeField] private float _beginFog = 2.5f;
        [SerializeField] private float _endFog = 5.0f;
        [SerializeField] private float _timeToEndFog = 7.0f;
        [SerializeField] private float _ratioFog = 10.0f;
        private bool _isStart = false;
        private bool _isChangeFog = false;
        private float _currentFog = 2.5f;

        private void OnTriggerStay2D(Collider2D collision)
        {
            if (collision.CompareTag("CreateLine"))
            {
                collision.gameObject.GetComponent<CreateLine>().CreateBlocks();
            }
        }

        public void StartToEndFog()
        {
            if (!_isStart)
            {
                _isStart = true;
                StartCoroutine("TimeFog");
                StartCoroutine("ChangeFog");
            }
        }

        IEnumerator TimeFog()
        {
            _isChangeFog = true;
            yield return new WaitForSeconds(_timeToEndFog);
            _isChangeFog = false;
            StartCoroutine("ChangeFog");
        }

        IEnumerator ChangeFog()
        {
            if (_isChangeFog)
            {
                while (_currentFog < _endFog && _isChangeFog)
                {
                    _currentFog += Time.deltaTime * _ratioFog;
                    gameObject.transform.localScale = new Vector3(_currentFog, _currentFog, 1);
                    yield return new WaitForSeconds(0.1f);
                }
                gameObject.transform.localScale = new Vector3(_endFog, _endFog, 1);
            }
            else
            {
                while (_currentFog > _beginFog)
                {
                    _currentFog -= Time.deltaTime * _ratioFog;
                    gameObject.transform.localScale = new Vector3(_currentFog, _currentFog, 1);
                    yield return new WaitForSeconds(0.1f);
                }
                gameObject.transform.localScale = new Vector3(_beginFog, _beginFog, 1);
                _isStart = false;
            }
        }
    } 
}
