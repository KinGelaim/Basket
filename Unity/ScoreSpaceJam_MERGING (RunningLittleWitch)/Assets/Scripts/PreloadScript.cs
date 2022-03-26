using UnityEngine;


namespace ScoreSpaceJam_MERGING
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