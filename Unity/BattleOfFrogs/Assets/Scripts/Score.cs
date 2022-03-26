using UnityEngine;
using UnityEngine.UI;


namespace BattleOfFrogs
{
    public class Score : MonoBehaviour
    {
        [SerializeField] private PlayerController _playerController;

        private Text _scoreText;

        private void Start()
        {
            _scoreText = transform.GetComponent<Text>();
        }


        private void Update()
        {
            _scoreText.text = _playerController.Score;
        }
    }
}