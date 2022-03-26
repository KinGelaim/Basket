using UnityEditor;


namespace SwiftPickaxe
{
	public class MenuItems
	{
		[MenuItem("SwiftPickaxe/Генерация карты %#a")]
		private static void GenerateLevel()
		{
			EditorWindow.GetWindow(typeof(WindowGenerateMap), false, "SwiftPickaxe");
		}

		[MenuItem("SwiftPickaxe/Загрузка карты из файла %#b")]
		private static void LoadLevelFromFile()
		{
			EditorWindow.GetWindow(typeof(WindowLoadLevelFromFile), false, "SwiftPickaxe");
		}
	}
}