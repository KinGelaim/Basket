using UnityEngine;
using UnityEditor;


namespace SwiftPickaxe
{
    public class MakeScriptableObject : MonoBehaviour
    {
        [MenuItem("Assets/Create/My Scriptable Object")]
        public static void CreateMyAsset()
        {
            DictionaryScriptableObject asset = ScriptableObject.CreateInstance<DictionaryScriptableObject>();

            AssetDatabase.CreateAsset(asset, "Assets/NewScripableObject.asset");
            AssetDatabase.SaveAssets();

            EditorUtility.FocusProjectWindow();

            Selection.activeObject = asset;
        }
    }
}