using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

namespace InfiniteMilitaryService_v1._0
{
    class Screen
    {
        public string centerText { get; set; }
        public string author { get; set; }
        public string mainText { get; set; }
        public string pathForImage { get; set; }
        public string pathForSound { get; set; }
        public bool isChose { get; set; }

        public Screen(string centerText = "", string author = "", string mainText = "", string pathForImage = "", string pathForSound = "", bool isChose = false)
        {
            if (centerText == "" && mainText == "" && pathForImage == "" && pathForSound == "")
                throw new Exception("При инициализации экрана пришёл пустой запрос");
            this.centerText = centerText;
            this.author = author;
            this.mainText = mainText;
            this.pathForImage = pathForImage;
            this.pathForSound = pathForSound;
            this.isChose = isChose;
        }
    }
}
