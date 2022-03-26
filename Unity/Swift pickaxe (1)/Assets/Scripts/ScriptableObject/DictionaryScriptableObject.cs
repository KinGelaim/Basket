using System.Collections.Generic;
using UnityEngine;


namespace SwiftPickaxe
{
    [CreateAssetMenu(fileName = "Description Level", menuName = "Data Objects/Description Level")]
    public class DictionaryScriptableObject : ScriptableObject
    {
        #region Fields

        [SerializeField]
        private List<int> _keys = new List<int>();
        [SerializeField]
        private List<GameObject> _values = new List<GameObject>();

        #endregion


        #region Properties

        public List<int> Keys { get => _keys; set => _keys = value; }
        public List<GameObject> Values { get => _values; set => _values = value; }

        #endregion
    }
}