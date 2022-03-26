using UnityEngine;
using UnityEditor;
using System;


namespace SwiftPickaxe
{
    public class WindowLoadLevelFromFile : EditorWindow
    {
        public static GameObject Level;
        public string NameObject = "";

        private char separator = '	';

        private void OnGUI()
        {

            Level = EditorGUILayout.ObjectField("Карта", Level, typeof(GameObject), true) as GameObject;
            NameObject = EditorGUILayout.TextField("Имя карты", NameObject);

            var button = GUILayout.Button("Загрузить карту");
            if (button)
            {
                if (NameObject != "")
                {
                    if (Level)
                    {
                        TextAsset level = Resources.Load<TextAsset>("Levels/" + NameObject);

                        string map = level.text;
                        string[] strRow = map.Split('\n');

                        LevelLoad ll = new LevelLoad();
                        ll.Map = new LevelLoad.RowData[strRow.Length];

                        int r = 0;
                        foreach (string row in strRow)
                        {
                            string[] cells = row.Split(separator);
                            ll.Map[r].row = new int[cells.Length];
                            int c = 0;
                            foreach (string cell in cells)
                            {
                                ll.Map[r].row[c] = Convert.ToInt32(cell);
                                c++;
                            }
                            r++;
                        }

                        Level.GetComponent<LevelLoad>().Map = ll.Map;
                    }
                }
            }
        }
    }
}
