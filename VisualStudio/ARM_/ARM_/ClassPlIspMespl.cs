using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace ARM
{
    public class ClassPlIspMespl
    {
        public int id { get; set; }
        public string codeElementPlIspMespl { get; set; }
        public string pictureElementPlIspMespl { get; set; }
        public string indexElementPlIspMespl { get; set; }
        public string nameElementPlIspMespl { get; set; }
        public string codeFactoryPlIspMespl { get; set; }
        public string nameFactoryPlIspMespl { get; set; }
        public string codeVidIspPlIspMespl { get; set; }
        public string nameVidIspPlIspMespl { get; set; }
        public string codeMCPlIspMespl { get; set; }
        public string nameMCPlIspMespl { get; set; }
        public string numberOfPartyPlIspMespl { get; set; }
        public string countShotPlIspMespl { get; set; }
        public string datePostPlIspMespl { get; set; }
        public string nPosPlPlIspMespl { get; set; }
        public string nPosSvPlIspMespl { get; set; }
        public string monthPlIspMespl { get; set; }
        public int yearPlIspMespl { get; set; }
        public string commentPlIspMespl { get; set; }
        public string namePregrad1PlIspMespl { get; set; }
        public string sizePregrad1PlIspMespl { get; set; }
        public string namePregrad2PlIspMespl { get; set; }
        public string sizePregrad2PlIspMespl { get; set; }

        public ClassPlIspMespl() { }

        public static ClassPlIspMespl Clone(ClassPlIspMespl pl)
        {
            ClassPlIspMespl newPl = new ClassPlIspMespl();
            newPl.id = pl.id;
            newPl.codeElementPlIspMespl = pl.codeElementPlIspMespl;
            newPl.pictureElementPlIspMespl = pl.pictureElementPlIspMespl;
            newPl.indexElementPlIspMespl = pl.indexElementPlIspMespl;
            newPl.nameElementPlIspMespl = pl.nameElementPlIspMespl;
            newPl.codeFactoryPlIspMespl = pl.codeFactoryPlIspMespl;
            newPl.nameFactoryPlIspMespl = pl.nameFactoryPlIspMespl;
            newPl.codeVidIspPlIspMespl = pl.codeVidIspPlIspMespl;
            newPl.nameVidIspPlIspMespl = pl.nameVidIspPlIspMespl;
            newPl.codeMCPlIspMespl = pl.codeMCPlIspMespl;
            newPl.nameMCPlIspMespl = pl.nameMCPlIspMespl;
            newPl.numberOfPartyPlIspMespl = pl.numberOfPartyPlIspMespl;
            newPl.countShotPlIspMespl = pl.countShotPlIspMespl;
            newPl.datePostPlIspMespl = pl.datePostPlIspMespl;
            newPl.nPosPlPlIspMespl = pl.nPosPlPlIspMespl;
            newPl.nPosSvPlIspMespl = pl.nPosSvPlIspMespl;
            newPl.monthPlIspMespl = pl.monthPlIspMespl;
            newPl.commentPlIspMespl = pl.commentPlIspMespl;
            newPl.namePregrad1PlIspMespl = pl.namePregrad1PlIspMespl;
            newPl.sizePregrad1PlIspMespl = pl.sizePregrad1PlIspMespl;
            newPl.namePregrad2PlIspMespl = pl.namePregrad2PlIspMespl;
            newPl.sizePregrad2PlIspMespl = pl.sizePregrad2PlIspMespl;
            return newPl;
        }
    }
}
