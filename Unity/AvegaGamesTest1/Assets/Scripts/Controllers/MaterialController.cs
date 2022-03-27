using UnityEngine;


namespace AvegaGamesTest1
{
    public static class MaterialController
    {
        public static void ChaneMaterialForGameObject(GameObject gameObject, BoxMaterial boxMaterial)
        {
            switch (boxMaterial)
            {
                case BoxMaterial.Red:
                    gameObject.GetComponent<MeshRenderer>().material.color = Color.red;
                    break;
                case BoxMaterial.Yellow:
                    gameObject.GetComponent<MeshRenderer>().material.color = Color.yellow;
                    break;
                case BoxMaterial.Green:
                    gameObject.GetComponent<MeshRenderer>().material.color = Color.green;
                    break;
                default:
                    break;
            }
        }
    }
}