using System;
using System.Collections.Generic;
using System.IO;
using System.Linq;
using System.Text;
using Newtonsoft.Json;

namespace ARMNewViewReports
{
    [Serializable]
    public class DataBaseSNS
    {
        //Поля
        private string fileName;
        private List<DataSNS> dataBase;

        //Свойства
        public int Count
        {
            get
            {
                return dataBase.Count;
            }
        }

        //Индексаторы
        public DataSNS this[string code]
        {
            get
            {
                foreach(DataSNS data in dataBase)
                    if(data.code == code)
                        return data;
                return null;
            }
        }

        public DataSNS this[int index]
        {
            get
            {
                return dataBase[index];
            }
        }

        //Конструктор
        public DataBaseSNS(string fileName = "data.json")
        {
            this.fileName = fileName;
            dataBase = new List<DataSNS>();
        }

        //Методы
        public bool Add(string code, string value)
        {
            if (dataBase.Exists(x => x.code == code))
                return false;
            dataBase.Add(new DataSNS(code, value));
            return true;
        }

        public bool Update(string code, string value)
        {
            if (!dataBase.Exists(x => x.code == code))
                return false;
            foreach(DataSNS data in dataBase)
                if(code == data.code)
                    data.name = value;
            return true;
        }

        public bool Update(string oldCode, string code, string value)
        {
            if (!dataBase.Exists(x => x.code == oldCode))
                return false;
            foreach (DataSNS data in dataBase)
                if (oldCode == data.code)
                {
                    data.code = code;
                    data.name = value;
                }
            return true;
        }

        public bool Delete(string code)
        {
            if (!dataBase.Exists(x => x.code == code))
                return false;
            foreach (DataSNS data in dataBase.ToArray())
                if (code == data.code)
                    dataBase.Remove(data);
            return true;
        }

        //Получение всего списка ключей
        //public Dictionary<string, string>.KeyCollection GetKeys()
        //{
        //   return dataBase.Keys;
        //}
        
        //Методы сериализации и де-//-
        public bool Save()
        {
            try
            {
                File.WriteAllText(fileName, JsonConvert.SerializeObject(dataBase));
                return true;
            }
            catch (Exception ex)
            {
                return false;
            }
        }

        public bool Load()
        {
            try
            {
                string loadText = File.ReadAllText(fileName);
                dataBase = JsonConvert.DeserializeObject<List<DataSNS>>(loadText);
                return true;
            }
            catch (Exception ex)
            {
                return false;
            }
        }

        public bool Sort()
        {
            try
            {
                var sortedDataBase = dataBase.OrderBy(x => x.code);
                List<DataSNS> sortedListData = new List<DataSNS>();
                foreach (var k in sortedDataBase)
                {
                    DataSNS sortedData = new DataSNS(k.code, k.name);
                    sortedListData.Add(sortedData);
                }
                dataBase.Clear();
                dataBase = sortedListData;
                return true;
            }
            catch (Exception ex)
            {
                return false;
            }
        }
    }
}
