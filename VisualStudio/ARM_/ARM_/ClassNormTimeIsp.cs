using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace ARM
{
    public class ClassNormTimeIsp
    {
        public int id { get; set; }
        public string codeElementNormTimeIsp { get; set; }
        public string pictureElementNormTimeIsp { get; set; }
        public string indexElementNormTimeIsp { get; set; }
        public string nameElementNormTimeIsp { get; set; }
        public string codeVidIspNormTimeIsp { get; set; }
        public string nameVidIspNormTimeIsp { get; set; }
        public string firstCehNormTimeIsp { get; set; }
        public string firstCehVPNormTimeIsp { get; set; }
        public string secondCehNormTimeIsp { get; set; }
        public string secondCehVPNormTimeIsp { get; set; }
        public string thirdCehNormTimeIsp { get; set; }
        public string thirdCehVPNormTimeIsp { get; set; }
        public string temperNormTimeIsp { get; set; }
        public string temperVPNormTimeIsp { get; set; }
        public string otkNormTimeIsp { get; set; }
        public string otkVPNormTimeIsp { get; set; }
        public string twoNormTimeIsp { get; set; }
        public string twoVPNormTimeIsp { get; set; }
        public string nineNormTimeIsp { get; set; }
        public string nineVPNormTimeIsp { get; set; }
        public string fiveNormTimeIsp { get; set; }
        public string fiveVPNormTimeIsp { get; set; }
        public string grLuchNormTimeIsp { get; set; }
        public string grLuchVPNormTimeIsp { get; set; }
        public string grCrecerNormTimeIsp { get; set; }
        public string grCrecerVPNormTimeIsp { get; set; }
        public string grMeteoNormTimeIsp { get; set; }
        public string grMeteoVPNormTimeIsp { get; set; }
        public string grKamaNormTimeIsp { get; set; }
        public string grKamaVPNormTimeIsp { get; set; }
        public string grSolenoidsNormTimeIsp { get; set; }
        public string grSolenoidsVPNormTimeIsp { get; set; }
        public string grGDINormTimeIsp { get; set; }
        public string grGDIVPNormTimeIsp { get; set; }
        public string grSVKNormTimeIsp { get; set; }
        public string grSVKVPNormTimeIsp { get; set; }
        public string grVeterNormTimeIsp { get; set; }
        public string grVeterVPNormTimeIsp { get; set; }
        public string grZummerNormTimeIsp { get; set; }
        public string grZummerVPNormTimeIsp { get; set; }
        public string summOnVidNormTimeIsp { get; set; }
        public string summOnVidVPNormTimeIsp { get; set; }
        public string summOnElementNormTimeIsp { get; set; }
        public string summOnElementVPNormTimeIsp { get; set; }

        public ClassNormTimeIsp()
        {
            //Нормы времени
            firstCehNormTimeIsp = "0";
            secondCehNormTimeIsp = "0";
            thirdCehNormTimeIsp = "0";
            temperNormTimeIsp = "0";
            otkNormTimeIsp = "0";
            twoNormTimeIsp = "0";
            fiveNormTimeIsp = "0";
            nineNormTimeIsp = "0";
            grLuchNormTimeIsp = "0";
            grCrecerNormTimeIsp = "0";
            grMeteoNormTimeIsp = "0";
            grKamaNormTimeIsp = "0";
            grSolenoidsNormTimeIsp = "0";
            grGDINormTimeIsp = "0";
            grSVKNormTimeIsp = "0";
            grVeterNormTimeIsp = "0";
            grZummerNormTimeIsp = "0";
            //ВП
            firstCehVPNormTimeIsp = "0";
            secondCehVPNormTimeIsp = "0";
            thirdCehVPNormTimeIsp = "0";
            temperVPNormTimeIsp = "0";
            otkVPNormTimeIsp = "0";
            twoVPNormTimeIsp = "0";
            fiveVPNormTimeIsp = "0";
            nineVPNormTimeIsp = "0";
            grLuchVPNormTimeIsp = "0";
            grCrecerVPNormTimeIsp = "0";
            grMeteoVPNormTimeIsp = "0";
            grKamaVPNormTimeIsp = "0";
            grSolenoidsVPNormTimeIsp = "0";
            grGDIVPNormTimeIsp = "0";
            grSVKVPNormTimeIsp = "0";
            grVeterVPNormTimeIsp = "0";
            grZummerVPNormTimeIsp = "0";
        }

        public string strNameNormTimeIsp()
        {
            string strNormTime = "";
            strNormTime += "1 цех\n";
            strNormTime += "2 цех\n";
            strNormTime += thirdCehNormTimeIsp != "0" ? "3 цех\n" : "";
            strNormTime += "Темперирование\n";
            strNormTime += "ОТК\n";
            strNormTime += "2 отдел\n";
            strNormTime += fiveNormTimeIsp != "0" ? "15 отдел\n" : "";
            strNormTime += "93 отдел:\n";
            strNormTime += "   гр.Луч\n";
            strNormTime += "   гр.Крешер\n";
            strNormTime += "   гр.Метео\n";
            strNormTime += "   гр.Кама\n";
            strNormTime += grSolenoidsNormTimeIsp != "0" ? "   гр.Соленоиды\n" : "";
            strNormTime += grGDINormTimeIsp != "0" ? "   гр.ГДИ\n" : "";
            strNormTime += grSVKNormTimeIsp != "0" ? "   гр.СВК\n" : "";
            strNormTime += grVeterNormTimeIsp != "0" ? "   гр.Ветер\n" : "";
            strNormTime += grZummerNormTimeIsp != "0" ? "   гр.Зуммер" : "";
            return strNormTime;
        }

        public string strNormTimeIsp()
        {
            string strNormTime = "";
            strNormTime += firstCehNormTimeIsp != "0" ? String.Format("{0:0.00}", Convert.ToDouble(firstCehNormTimeIsp)) : "";
            strNormTime += "\n";
            strNormTime += secondCehNormTimeIsp != "0" ? String.Format("{0:0.00}", Convert.ToDouble(secondCehNormTimeIsp)) : "";
            strNormTime += "\n";
            strNormTime += thirdCehNormTimeIsp != "0" ? String.Format("{0:0.00}", Convert.ToDouble(thirdCehNormTimeIsp)) : "";
            strNormTime += thirdCehNormTimeIsp != "0" ? "\n" : "";
            strNormTime += temperNormTimeIsp != "0" ? String.Format("{0:0.00}", Convert.ToDouble(temperNormTimeIsp)) : "";
            strNormTime += "\n";
            strNormTime += otkNormTimeIsp != "0" ? String.Format("{0:0.00}", Convert.ToDouble(otkNormTimeIsp)) : "";
            strNormTime += "\n";
            strNormTime += twoNormTimeIsp != "0" ? String.Format("{0:0.00}", Convert.ToDouble(twoNormTimeIsp)) : "";
            strNormTime += "\n";
            strNormTime += fiveNormTimeIsp != "0" ? String.Format("{0:0.00}", Convert.ToDouble(fiveNormTimeIsp)) : "";
            strNormTime += fiveNormTimeIsp != "0" ? "\n\n" : "\n";
            strNormTime += grLuchNormTimeIsp != "0" ? String.Format("{0:0.00}", Convert.ToDouble(grLuchNormTimeIsp)) : "";
            strNormTime += "\n";
            strNormTime += grCrecerNormTimeIsp != "0" ? String.Format("{0:0.00}", Convert.ToDouble(grCrecerNormTimeIsp)) : "";
            strNormTime += "\n";
            strNormTime += grMeteoNormTimeIsp != "0" ? String.Format("{0:0.00}", Convert.ToDouble(grMeteoNormTimeIsp)) : "";
            strNormTime += "\n";
            strNormTime += grKamaNormTimeIsp != "0" ? String.Format("{0:0.00}", Convert.ToDouble(grKamaNormTimeIsp)) : "";
            strNormTime += "\n";
            strNormTime += grSolenoidsNormTimeIsp != "0" ? String.Format("{0:0.00}", Convert.ToDouble(grSolenoidsNormTimeIsp)) : "";
            strNormTime += grSolenoidsNormTimeIsp != "0" ? "\n" : "";
            strNormTime += grGDINormTimeIsp != "0" ? String.Format("{0:0.00}", Convert.ToDouble(grGDINormTimeIsp)) : "";
            strNormTime += grGDINormTimeIsp != "0" ? "\n" : "";
            strNormTime += grSVKNormTimeIsp != "0" ? String.Format("{0:0.00}", Convert.ToDouble(grSVKNormTimeIsp)) : "";
            strNormTime += grSVKNormTimeIsp != "0" ? "\n" : "";
            strNormTime += grVeterNormTimeIsp != "0" ? String.Format("{0:0.00}", Convert.ToDouble(grVeterNormTimeIsp)) : "";
            strNormTime += grVeterNormTimeIsp != "0" ? "\n" : "";
            strNormTime += grZummerNormTimeIsp != "0" ? String.Format("{0:0.00}", Convert.ToDouble(grZummerNormTimeIsp)) : "";
            return strNormTime;
        }
    }
}
