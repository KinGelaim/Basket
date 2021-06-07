using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace ARM
{
    public class ClassPregrads
    {
        private int id;
        private string code;
        private string name;
        private string size;
        private double ves;
        private double cena;

        public ClassPregrads() { }

        public void setId(int id) { this.id = id; }
        public int getId() { return id; }
        public void setCode(string code) { this.code = code; }
        public string getCode() { return code; }
        public void setName(string name) { this.name = name; }
        public string getName() { return name; }
        public void setSize(string size) { this.size = size; }
        public string getSize() { return size; }
        public void setVes(double ves) { this.ves = ves; }
        public double getVes() { return ves; }
        public void setCena(double cena) { this.cena = cena; }
        public double getCena() { return cena; }
    }
}
