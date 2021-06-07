using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace ARM
{
    public class ClassFactorys
    {
        private int id;
        private string code;
        private string name;
        private string adress;
        private string fio;
        private string inn;
        private string udal;
        private string send;

        public ClassFactorys() { }

        public void setId(int id) { this.id = id; }

        public int getId() { return id; }

        public void setCode(string code) { this.code = code; }
        public string getCode() { return code; }
        public void setName(string name) { this.name = name; }
        public string getName() { return name; }
        public void setAdress(string adress) { this.adress = adress; }
        public string getAdress() { return adress; }
        public void setFIO(string fio) { this.fio = fio; }
        public string getFIO() { return fio; }
        public void setINN(string inn) { this.inn = inn; }
        public string getINN() { return inn; }
        public void setUdal(string udal) { this.udal = udal; }
        public string getUdal() { return udal; }
        public void setSend(string send) { this.send = send; }
        public string getSend() { return send; }
    }
}
