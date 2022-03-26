using UnityEngine;


namespace HungryCat
{
    public class PreloadScript : MonoBehaviour
    {
        [SerializeField] private GameManager _gameManager;

        private void Start()
        {
            _gameManager.LoadNextLevel();
        }
    }
}