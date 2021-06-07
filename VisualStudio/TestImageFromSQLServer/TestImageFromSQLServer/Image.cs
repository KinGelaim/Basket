using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

namespace TestImageFromSQLServer
{
    class Image
    {
        public int ID { get; private set; }
        public int ContractID { get; private set; }
        public byte[] Data { get; private set; }
        public int Znak { get; private set; }
        public Image(int id, int contractID, byte[] data, int znak)
        {
            ID = id;
            ContractID = contractID;
            Data = data;
            Znak = znak;
        }
    }
}
