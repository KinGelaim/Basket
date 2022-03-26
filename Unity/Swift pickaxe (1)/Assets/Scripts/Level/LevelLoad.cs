using UnityEngine;


namespace SwiftPickaxe
{
    public class LevelLoad : MonoBehaviour
    {
        [System.Serializable]
        public struct RowData
        {
            public int[] row;
        }

        public RowData[] Map = new RowData[5];
    }
}