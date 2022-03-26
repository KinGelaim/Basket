using UnityEngine;
using UnityEditor;


namespace SwiftPickaxe
{
    public class WindowGenerateMap : EditorWindow
    {
        public static DictionaryScriptableObject Dictionary;
        public static GameObject Level;

        private void OnEnable()
        {
            if (EditorPrefs.HasKey("ObjectPath"))
            {
                string objectPath = EditorPrefs.GetString("ObjectPath");
                Dictionary = AssetDatabase.LoadAssetAtPath(objectPath, typeof(DictionaryScriptableObject)) as DictionaryScriptableObject;
            }
        }

        private void OnGUI()
        {
            Dictionary = EditorGUILayout.ObjectField("Словарик", Dictionary, typeof(DictionaryScriptableObject), true) as DictionaryScriptableObject;
            Level = EditorGUILayout.ObjectField("Карта", Level, typeof(GameObject), true) as GameObject;

            var button = GUILayout.Button("Создать объекты");
            if (button)
            {
                if (Dictionary)
                {
                    if (Level)
                    {
                        GameObject root = new GameObject("RootGround");
                        var ll = Level.GetComponent<LevelLoad>().Map;
                        float positionY = -4.0f;
                        float positionX = -6.0f;
                        int n = 0;
                        foreach (var row in ll)
                        {
                            foreach (var cell in row.row)
                            {
                                Vector3 pos = new Vector3(positionX, positionY, 0);
                                int indexValue = 0;
                                for (int i = 0; i < Dictionary.Keys.Count; i++)
                                    if (Dictionary.Keys[i] == cell)
                                        indexValue = i;

                                GameObject temp = Instantiate(Dictionary.Values[indexValue], pos, Quaternion.identity);
                                temp.name = "ground(" + n + ")";
                                temp.transform.parent = root.transform;
                                positionX += 1.0f;
                                n++;
                            }
                            positionY -= 1.0f;
                            positionX = -6.0f;
                        }
                    }
                }
            }
        }
    }
}