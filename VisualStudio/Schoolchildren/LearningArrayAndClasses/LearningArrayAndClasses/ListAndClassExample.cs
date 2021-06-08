using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;


namespace LearningArrayAndClasses
{
    public sealed class ListAndClassExample
    {
        public void Start()
        {
            Console.ForegroundColor = ConsoleColor.White;

            Area area = Area.CreateArea();
            area.Start();
        }
    }

    private sealed class Area
    {
        private static Area _area;

        private Area()
        {
        }

        public static Area CreateArea()
        {
            return _area ?? (_area = new Area());
        }

        private List<Storage> _storages = new List<Storage>();

        private string _userItem;
        private static string _index;
        private string _userData;

    }
}
