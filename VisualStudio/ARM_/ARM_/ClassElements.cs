using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace ARM
{
    public class ClassElements
    {
        private int id;
        private string code;
        private string picture;
        private string index;
        private string name;
        private int sp;
        public string codeFactory { get; set; }
        public string nameFactory { get; set; }
        public string innFactory { get; set; }

        public ClassElements() { }

        public void setId(int id) {
            this.id = id;
        }
        public int getId()
        {
            return id;
        }
        public void setCode(string code)
        {
            this.code = code;
        }
        public string getCode()
        {
            return code;
        }
        public void setPicture(string picture)
        {
            this.picture = picture;
        }
        public string getPicture()
        {
            return picture;
        }
        public void setIndex(string index)
        {
            this.index = index;
        }
        public string getIndex()
        {
            return index;
        }
        public void setName(string name)
        {
            this.name = name;
        }
        public string getName()
        {
            return name;
        }
        public void setSp(int sp)
        {
            this.sp = sp;
        }
        public int getSp()
        {
            return sp;
        }
    }
}
