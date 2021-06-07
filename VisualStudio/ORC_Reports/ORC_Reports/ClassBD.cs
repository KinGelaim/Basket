using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Net;
using System.IO;
using Newtonsoft.Json;
using System.Windows.Forms;

namespace ORC_Reports
{
    static class ClassBD
    {
        public static string ServerName = "localhost";

        static public void LoadFullBD()
        {

        }

        static public List<string> GetSipViewContracts()
        {
            string command = "http://" + ServerName + "/orc_new/public/reports/view_sip_contracts";
            List<string> viewList = new List<string>();
            //Запрос
            dynamic json = null;
            WebRequest request = WebRequest.Create(command);
            WebResponse response = request.GetResponse();
            using (Stream stream = response.GetResponseStream())
            {
                using (StreamReader reader = new StreamReader(stream))
                {
                    json = JsonConvert.DeserializeObject(reader.ReadToEnd());
                }
            }
            foreach (dynamic d in json)
                viewList.Add(d["name_view_contract"].ToString());
            return viewList;
        }

        static public Dictionary<string, string> GetSipCounterparties()
        {
            string command = "http://" + ServerName + "/orc_new/public/reports/counterparties_sip";
            Dictionary<string, string> viewList = new Dictionary<string, string>();
            //Запрос
            dynamic json = null;
            WebRequest request = WebRequest.Create(command);
            WebResponse response = request.GetResponse();
            using (Stream stream = response.GetResponseStream())
            {
                using (StreamReader reader = new StreamReader(stream))
                {
                    json = JsonConvert.DeserializeObject(reader.ReadToEnd());
                }
            }
            foreach (dynamic d in json)
                viewList.Add(d["name"].ToString(), d["id"].ToString());
            return viewList;
        }

        static public Dictionary<string, string> GetDepartments()
        {
            string command = "http://" + ServerName + "/orc_new/public/reports/departments";
            Dictionary<string, string> departmentList = new Dictionary<string, string>();
            dynamic json = null;
            WebRequest request = WebRequest.Create(command);
            WebResponse response = request.GetResponse();
            using (Stream stream = response.GetResponseStream())
            {
                using (StreamReader reader = new StreamReader(stream))
                {
                    json = JsonConvert.DeserializeObject(reader.ReadToEnd());
                }
            }
            foreach (dynamic d in json)
                departmentList.Add(d["index_department"].ToString() + " " + d["name_department"].ToString(), d["id"].ToString());
            return departmentList;
        }
    }
}
