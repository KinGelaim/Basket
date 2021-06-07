using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

namespace ARMNewViewReports
{
    [Serializable]
    public class DataSNS
    {
        public string code { get; set; }
        public string name { get; set; }

        public DataSNS() { }

        public DataSNS(string code, string name)
        {
            this.code = code;
            this.name = name;
        }
    }
}
