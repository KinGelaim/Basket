using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

namespace Save_Image_IN_BD
{
    public class ClassImage
    {
        public int id { get; set; }
        public string nameImage { get; set; }
        public byte[] byteImage { get; set; }
        public int byteImageLength { get; set; }

        public ClassImage(string nameImage, byte[] byteImage = null)
        {
            this.nameImage = nameImage;
            this.byteImage = byteImage;
            if (byteImage != null)
                this.byteImageLength = byteImage.Length;
            else
                this.byteImageLength = 0;
        }

        public ClassImage(int id, string nameImage, byte[] byteImage = null)
        {
            this.id = id;
            this.nameImage = nameImage;
            this.byteImage = byteImage;
            if (byteImage != null)
                this.byteImageLength = byteImage.Length;
            else
                this.byteImageLength = 0;
        }
    }
}
