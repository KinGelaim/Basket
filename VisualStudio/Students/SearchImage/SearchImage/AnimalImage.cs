using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Drawing;

namespace SearchImage
{
    class AnimalImage
    {
        public enum TypeAnimal
        {
            raccoon,
            cat,
            dog,
            frog,
            rabbit,
            bee,
            bird,
            owl
        }

        public int posX { get; set; }
        public int posY { get; set; }
        public Bitmap bitmap { get; set; }
        public bool isHide { get; set; }
        public TypeAnimal typeAnimal { get; set; }

        public AnimalImage()
        {
            isHide = true;
        }

        public AnimalImage(Bitmap bitmap, TypeAnimal typeAnimal) : this()
        {
            this.bitmap = new Bitmap(bitmap);
            this.typeAnimal = typeAnimal;
        }

        public AnimalImage(int posX, int posY, Bitmap bitmap, TypeAnimal typeAnimal) : this(bitmap, typeAnimal)
        {
            this.posX = posX;
            this.posY = posY;
        }
    }
}
