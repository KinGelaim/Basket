using UnityEngine;


namespace TargemTest1
{
    public static class CenterUniverseFactory
    {
        public static GameObject CreateCenter(GameObject prefabCenterUniverse, GameObject parent)
        {
            GameObject centerUniverse = GameObject.Instantiate(prefabCenterUniverse, parent.transform);
            return centerUniverse;
        }
    }
}