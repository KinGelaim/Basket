using UnityEngine;


namespace AvegaGamesTest1
{
    public static class PlayerFactory
    {
        public static GameObject CreatePlayer(GameObject prefabPlayer, GameObject parent)
        {
            GameObject player = GameObject.Instantiate(prefabPlayer, parent.transform);
            return player;
        }
    }
}