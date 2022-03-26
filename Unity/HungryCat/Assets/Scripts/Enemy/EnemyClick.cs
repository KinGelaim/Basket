using UnityEngine;


namespace HungryCat
{
    public class EnemyClick : MonoBehaviour
    {
        [SerializeField] private EnemyType _type;

        private void OnMouseDown()
        {
            GameManager gameManager = FindObjectOfType<GameManager>();
            if (gameManager.IsPlayGame)
            {
                FindObjectOfType<GameManager>().Grab(_type);
                FindObjectOfType<EnemyManager>().EatEnemy(gameObject);
            }
        }
    }
}