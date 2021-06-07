using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Data.Common;
using System.Data.SQLite;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using System.Windows.Forms;
using System.IO;

namespace ARM
{
    public partial class Form22 : Form
    {
        public int typeSettings;
        SQLiteConnection connection;
        string databaseName = Properties.Settings.Default.SettingPatch + @"BD\DB_ARM.db";
        SQLiteDataReader reader = null;
        bool authAdmin;

        int positionUser = -1;
        List<ClassUsers> usersList = new List<ClassUsers>();
        bool spUserEdit = false;

        public Form22(bool authAdmin)
        {
            InitializeComponent();
            typeSettings = 0;
            this.authAdmin = authAdmin;
        }

        //------------------------------ПОСЕЩАЕМОСТЬ------------------------------
        private void button1_Click(object sender, EventArgs e)
        {
            button1.Visible = false;
            button2.Visible = false;
            button3.Visible = false;
            button4.Visible = false;
            listBox1.Visible = true;
            listBox1.Items.Clear();
            connection = new SQLiteConnection(string.Format("Data Source={0};", databaseName));
            connection.Open();
            reader = null;
            SQLiteCommand command = new SQLiteCommand("SELECT Users.userFamilly, Users.userName, Users.userPatronymic, UsersHistoryAuth.dateAuth FROM UsersHistoryAuth LEFT JOIN Users ON UsersHistoryAuth.idUser=Users.id ORDER BY UsersHistoryAuth.id DESC", connection);
            try
            {
                reader = command.ExecuteReader();
                while (reader.Read())
                {
                    string prFammilyUser = Convert.ToString(reader["userFamilly"]);
                    if (prFammilyUser.Length > 0)
                        listBox1.Items.Add(reader["userFamilly"] + "   " + reader["userName"] + "   " + reader["userPatronymic"] + "   " + reader["dateAuth"]);
                    else
                        listBox1.Items.Add("Вход не удался!!!   " + reader["dateAuth"]);
                }
                reader.Close();
            }
            catch (Exception ex)
            {
                MessageBox.Show(ex.Message.ToString(), ex.Source.ToString(), MessageBoxButtons.OK, MessageBoxIcon.Error);
            }
            finally
            {
                if (reader != null)
                    reader.Close();
                if (connection != null && connection.State != ConnectionState.Closed)
                    connection.Close();
            }
        }

        private void button2_Click(object sender, EventArgs e)
        {
            if (authAdmin)
            {
                button1.Visible = false;
                button2.Visible = false;
                button3.Visible = false;
                button4.Visible = false;
                panel1.Visible = true;
                functionQueryUsers();
                positionUser = 0;
                functionShowUser(positionUser);
            }
            else
                MessageBox.Show("Недостаточность прав!\nОбратитесь к системному администратору!", "Ошибка!", MessageBoxButtons.OK, MessageBoxIcon.Error);
        }

        private void button3_Click(object sender, EventArgs e)
        {
            button1.Visible = false;
            button2.Visible = false;
            button3.Visible = false;
            button4.Visible = false;
            button5.Visible = true;
            button6.Visible = true;
            button7.Visible = true;
            button15.Visible = true;
            if (authAdmin)
            {
                button5.Enabled = true;
                button6.Enabled = true;
                button7.Enabled = true;
            }else
                MessageBox.Show("Недостаточность прав!\nОбратитесь к системному администратору!", "Предупреждение!", MessageBoxButtons.OK, MessageBoxIcon.Information);
        }

        private void button4_Click(object sender, EventArgs e)
        {
            typeSettings = 4;
            this.Close();
        }

        //Создание базы данных и таблиц
        private void button15_Click(object sender, EventArgs e)
        {
            try
            {
                if (!File.Exists(databaseName))
                {
                    SQLiteConnection.CreateFile(databaseName);
                    connection = new SQLiteConnection(string.Format("Data Source={0};", databaseName));
                    connection.Open();
                    SQLiteCommand command = new SQLiteCommand("CREATE TABLE [EdIzm] ( [id] INTEGER PRIMARY KEY AUTOINCREMENT, [codeEdIzm2] NVARCHAR (2) NULL, [cpdeEdIzm3] NVARCHAR (3) NULL, [nameFull] NVARCHAR (10) NOT NULL, [nameSocr] NVARCHAR (10) NULL);" +
                        "CREATE TABLE [Factorys] ( [id] INTEGER PRIMARY KEY AUTOINCREMENT, [codeFactory] NVARCHAR (6) NOT NULL, [nameFactory] NTEXT NOT NULL, [adress] NTEXT NOT NULL, [FIO] NTEXT NULL, [INN] NVARCHAR (10) NULL, [udal] NVARCHAR (10) NULL, [send] NTEXT NULL);" +
                        "CREATE TABLE [ElementsSpravochnik] ( [id] INTEGER PRIMARY KEY AUTOINCREMENT, [nameSp] NVARCHAR (10) NOT NULL);" +
                        "CREATE TABLE [Elements] ( [id] INTEGER PRIMARY KEY AUTOINCREMENT, [codeElement] NVARCHAR (10) NOT NULL, [picture] NVARCHAR (21) NULL, [indexElement] NVARCHAR (21) NULL, [nameElement] NTEXT NOT NULL, [sp] INT NOT NULL, [idFactoryPostElements] INT NULL, FOREIGN KEY ([idFactoryPostElements]) REFERENCES [Factorys] ([id]) ON DELETE SET NULL, FOREIGN KEY ([sp]) REFERENCES [ElementsSpravochnik] ([id]) ON DELETE CASCADE );" +
                        "CREATE TABLE [MatChast] ( [id] INTEGER PRIMARY KEY AUTOINCREMENT, [codeMC3] NVARCHAR (3) NOT NULL, [codeMC5] NVARCHAR (5) NOT NULL, [codeMVN] NVARCHAR (10) NULL, [socrNameSistem] NTEXT NULL, [nameSistem] NTEXT NULL, [fullNameSistem] NTEXT NULL, [codeFactoryMC] INT NULL, [cenaMC] DECIMAL (12, 2) NULL, FOREIGN KEY ([codeFactoryMC]) REFERENCES [Factorys] ([id]) ON DELETE SET NULL );" +
                        "CREATE TABLE [CenaKE] ( [id] INTEGER PRIMARY KEY AUTOINCREMENT, [idElementCenaKE] INT NOT NULL, [idEdIzmCenzKE] INT NOT NULL, [cenaCenaKE] DECIMAL (12, 2) NOT NULL, [numberPozCenaKE] NVARCHAR (7) NULL, [commentCenaKE] NTEXT NULL, FOREIGN KEY ([idElementCenaKE]) REFERENCES [Elements] ([id]) ON DELETE CASCADE, FOREIGN KEY ([idEdIzmCenzKE]) REFERENCES [EdIzm] ([id]) ON DELETE CASCADE );" +
                        "CREATE TABLE [Poligons] ( [id] INTEGER PRIMARY KEY AUTOINCREMENT, [codeFactoryPl] INT NULL, [numberPl] NVARCHAR (7) NULL, [namePoligon] NTEXT NULL, FOREIGN KEY ([codeFactoryPl]) REFERENCES [Factorys] ([id]) ON DELETE SET NULL );" +
                        "CREATE TABLE [PregradName] ( [id] INTEGER PRIMARY KEY AUTOINCREMENT, [namePregrad] NVARCHAR (14) NOT NULL);" +
                        "CREATE TABLE [PregradInfo] ( [id] INTEGER PRIMARY KEY AUTOINCREMENT, [codePregradi] NVARCHAR (10) NOT NULL, [sizePregradi] NTEXT NOT NULL, [vesPregradi] DECIMAL (9, 2) NULL, [cenaPregradi] DECIMAL (12, 2) NULL, [namePregradi] INT NOT NULL, FOREIGN KEY ([namePregradi]) REFERENCES [PregradName] ([id]) ON DELETE CASCADE );" +
                        "CREATE TABLE [VidIsp] ( [id] INTEGER PRIMARY KEY AUTOINCREMENT, [codeVida] NVARCHAR (3) NOT NULL, [nameVida] NVARCHAR (21) NOT NULL);" +
                        "CREATE TABLE [CenaVidIsp] ( [id] INTEGER PRIMARY KEY AUTOINCREMENT, [poligonCenaVidIsp] NVARCHAR (7) NULL, [idElementCenaVidIsp] INT NOT NULL, [idVidIspCenaVidIsp] INT NOT NULL, [mcCenaVidIsp] INT NULL, [cenaCenaVidIsp] DECIMAL (12, 2) NOT NULL, FOREIGN KEY ([idElementCenaVidIsp]) REFERENCES [Elements] ([id]) ON DELETE CASCADE, FOREIGN KEY ([idVidIspCenaVidIsp]) REFERENCES [VidIsp] ([id]) ON DELETE CASCADE, FOREIGN KEY ([mcCenaVidIsp]) REFERENCES [MatChast] ([id]) ON DELETE CASCADE );" +
                        "CREATE TABLE [FIMespl] ( [id] INTEGER PRIMARY KEY AUTOINCREMENT, [codeElementFIMespl] INT NOT NULL, [vidIspFIMespl] INT NOT NULL, [codeSysFIMespl] INT NULL, [codeUsIspMespl] NVARCHAR (10) NULL, [distanceFIMespl] NVARCHAR (10) NULL, [sizePartFIMespl] NVARCHAR (10) NULL, [lgotFIMespl] NVARCHAR (10) NOT NULL, [countShotPartFIMespl] NVARCHAR (10) NULL, [countReShotFIMespl] NVARCHAR (10) NULL, [countPodShotFIMespl] NVARCHAR (10) NULL, [uslCountPartYearFIMespl] NVARCHAR (10) NULL, [codePregradiOneFIMespl] INT NULL, [livePregradiOneFIMespl] NVARCHAR (10) NULL, [codePregradiTwoFIMespl] INT NULL, [livePregradiTwoFIMespl] NVARCHAR (10) NULL, [liveSystemFIMespl] NVARCHAR (10) NULL, [prevLiveSystemFIMespl] NVARCHAR (10) NULL, [codeNameStFIMespl] INT NULL, [liveStFIMespl] NVARCHAR (10) NULL, [prevLiveStFIMespl] NVARCHAR (10) NULL, [codeNameStandFIMespl] INT NULL, [liveStandFIMespl] NVARCHAR (10) NULL, [prevLiveStandFIMespl] NVARCHAR (10) NULL, [koefAmorGilzFIMespl] NVARCHAR (10) NULL, [koefPrivedZarFIMespl] NVARCHAR (10) NULL, [koefPrivShotFIMespl] NVARCHAR (10) NULL, [uslCountZvFIMespl] NVARCHAR (10) NULL, [codeEdIzmFIMespl] INT NOT NULL, FOREIGN KEY ([codeNameStFIMespl]) REFERENCES [MatChast] ([id]), FOREIGN KEY ([codePregradiTwoFIMespl]) REFERENCES [PregradInfo] ([id]), FOREIGN KEY ([codeSysFIMespl]) REFERENCES [MatChast] ([id]), FOREIGN KEY ([codeElementFIMespl]) REFERENCES [Elements] ([id]) ON DELETE CASCADE, FOREIGN KEY ([vidIspFIMespl]) REFERENCES [VidIsp] ([id]) ON DELETE CASCADE, FOREIGN KEY ([codeEdIzmFIMespl]) REFERENCES [EdIzm] ([id]) ON DELETE CASCADE, FOREIGN KEY ([codeNameStandFIMespl]) REFERENCES [MatChast] ([id]) ON DELETE SET NULL, FOREIGN KEY ([codePregradiOneFIMespl]) REFERENCES [PregradInfo] ([id]) ON DELETE SET NULL );" +
                        "CREATE TABLE [noneVK] ( [id] INTEGER PRIMARY KEY AUTOINCREMENT, [codeElementVK] INT NOT NULL, [vidIspVK] INT NOT NULL, FOREIGN KEY ([vidIspVK]) REFERENCES [VidIsp] ([id]) ON DELETE CASCADE, FOREIGN KEY ([codeElementVK]) REFERENCES [Elements] ([id]) ON DELETE CASCADE );" +
                        "CREATE TABLE [NormTimeIsp] ( [id] INTEGER PRIMARY KEY AUTOINCREMENT, [idElementNormTimeIsp] INT NOT NULL, [idVidIspNormTimeIsp] INT NOT NULL, [firstCehNormTimeIsp] DECIMAL (7, 2) NULL, [firstCehVPNormTimeIsp] DECIMAL (7, 2) NULL, [secondCehNormTimeIsp] DECIMAL (7, 2) NULL, [secondCehVPNormTimeIsp] DECIMAL (7, 2) NULL, [temperNormTimeIsp] DECIMAL (7, 2) NULL, [temperVPNormTimeIsp] DECIMAL (7, 2) NULL, [otkNormTimeIsp] DECIMAL (7, 2) NULL, [otkVPNormTimeIsp] DECIMAL (7, 2) NULL, [twoNormTimeIsp] DECIMAL (7, 2) NULL, [twoVPNormTimeIsp] DECIMAL (7, 2) NULL, [nineNormTimeIsp] DECIMAL (7, 2) NULL, [nineVPNormTimeIsp] DECIMAL (7, 2) NULL, [grLuchNormTimeIsp] DECIMAL (7, 2) NULL, [grLuchVPNormTimeIsp] DECIMAL (7, 2) NULL, [grCrecerNormTimeIsp] DECIMAL (7, 2) NULL, [grCrecerVPNormTimeIsp] DECIMAL (7, 2) NULL, [grMeteoNormTimeIsp] DECIMAL (7, 2) NULL, [grMeteoVPNormTimeIsp] DECIMAL (7, 2) NULL, [grKamaNormTimeIsp] DECIMAL (7, 2) NULL, [grKamaVPNormTimeIsp] DECIMAL (7, 2) NULL, [grSolenoidsNormTimeIsp]  DECIMAL (7, 2) NULL, [grSolenoidsVPNormTimeIsp]  DECIMAL (7,2) NULL, FOREIGN KEY ([idElementNormTimeIsp]) REFERENCES [Elements] ([id]) ON DELETE CASCADE, FOREIGN KEY ([idVidIspNormTimeIsp]) REFERENCES [VidIsp] ([id]) ON DELETE CASCADE );" +
                        "CREATE TABLE [PlIndKontrol] ( [id] INTEGER PRIMARY KEY AUTOINCREMENT, [idElementPlIndKontrol] INT NOT NULL, [idFactoryPlIndKontrol] INT NOT NULL, [idEdIzmPlIndKontrol] INT NOT NULL, [vOnePlIndKontrol] NVARCHAR (10) NULL, [vTwoPlIndKontrol] NVARCHAR (10) NULL, [vThrePlIndKontrol] NVARCHAR (10) NULL, [vThourPlIndKontrol] NVARCHAR (10) NULL, [yearPlIndKontrol] INT NOT NULL, FOREIGN KEY ([idElementPlIndKontrol]) REFERENCES [Elements] ([id]) ON DELETE CASCADE, FOREIGN KEY ([idFactoryPlIndKontrol]) REFERENCES [Factorys] ([id]) ON DELETE CASCADE, FOREIGN KEY ([idEdIzmPlIndKontrol]) REFERENCES [EdIzm] ([id]) ON DELETE CASCADE );" +
                        "CREATE TABLE [PlIndResult] ( [id] INTEGER PRIMARY KEY AUTOINCREMENT, [idElementPlIndResult] INT NOT NULL, [idFactoryPlIndResult] INT NOT NULL, [idEdIzmPlIndResult] INT NOT NULL, [vOnePlIndResult] NVARCHAR (10) NULL, [vTwoPlIndResult] NVARCHAR (10) NULL, [vThrePlIndResult] NVARCHAR (10) NULL, [vThourPlIndResult] NVARCHAR (10) NULL, [yearPlIndResult] INT NOT NULL, FOREIGN KEY ([idElementPlIndResult]) REFERENCES [Elements] ([id]) ON DELETE CASCADE, FOREIGN KEY ([idFactoryPlIndResult]) REFERENCES [Factorys] ([id]) ON DELETE CASCADE, FOREIGN KEY ([idEdIzmPlIndResult]) REFERENCES [EdIzm] ([id]) ON DELETE CASCADE );" +
                        "CREATE TABLE [PlIspKontrol] ( [id] INTEGER PRIMARY KEY AUTOINCREMENT, [idElementPlIspKontrol] INT NOT NULL, [idFactoryPlIspKontrol] INT NOT NULL, [idPoligonPlIspKontrol] INT NOT NULL, [idVidIspPlIspKontrol] INT NOT NULL, [idSystemPlIspKontrol] INT NULL, [idStvolPlIspKontrol] INT NULL, [idStandPlIspKontrol] INT NULL, [vOnePlIspKontrol] NVARCHAR (10) NULL, [vTwoPlIspKontrol] NVARCHAR (10) NULL, [vThrePlIspKontrol] NVARCHAR (10) NULL, [vThourPlIspKontrol] NVARCHAR (10) NULL, [nPosPlPlIspKontrol] NVARCHAR (10) NULL, [nPosSvPlIspKontrol] NVARCHAR (10) NULL, [commentPlIspKontrol] NTEXT NULL, [yearPlIspKontrol] INT NOT NULL, FOREIGN KEY ([idElementPlIspKontrol]) REFERENCES [Elements] ([id]) ON DELETE CASCADE, FOREIGN KEY ([idFactoryPlIspKontrol]) REFERENCES [Factorys] ([id]) ON DELETE CASCADE, FOREIGN KEY ([idPoligonPlIspKontrol]) REFERENCES [Poligons] ([id]) ON DELETE CASCADE, FOREIGN KEY ([idVidIspPlIspKontrol]) REFERENCES [VidIsp] ([id]) ON DELETE CASCADE );" +
                        "CREATE TABLE [PlIspMespl] ( [id] INTEGER PRIMARY KEY AUTOINCREMENT, [codeElementPlIspmespl] INT NOT NULL, [codeFactoryPlIspMespl] INT NOT NULL, [codeVidIspPlIspmespl] INT NOT NULL, [numberOfPartyPlIspmespl] NVARCHAR (10) NULL, [countShotPlIspmespl] NVARCHAR (10) NULL, [datePostPlIspmespl] NVARCHAR (10) NULL, [nPosPlPlIspmespl] NVARCHAR (10) NULL, [nPosSvPlIspmespl] NVARCHAR (10) NULL, [monthPlIspMespl] NVARCHAR (8) NOT NULL, [yearPlIspMespl] INT NOT NULL, [commentPlIspmespl] NTEXT NULL, FOREIGN KEY ([codeElementPlIspmespl]) REFERENCES [Elements] ([id]) ON DELETE CASCADE, FOREIGN KEY ([codeFactoryPlIspMespl]) REFERENCES [Factorys] ([id]) ON DELETE CASCADE, FOREIGN KEY ([codeVidIspPlIspmespl]) REFERENCES [VidIsp] ([id]) ON DELETE CASCADE );" +
                        "CREATE TABLE [PlIspResult] ( [id] INTEGER PRIMARY KEY AUTOINCREMENT, [idElementPlIspResult] INT NOT NULL, [idFactoryPlIspResult] INT NOT NULL, [idPoligonPlIspResult] INT NOT NULL, [idVidIspPlIspResult] INT NOT NULL, [idSystemPlIspResult] INT NULL, [idStvolPlIspResult] INT NULL, [idStandPlIspResult] INT NULL, [vOnePlIspResult] NVARCHAR (10) NULL, [vTwoPlIspResult] NVARCHAR (10) NULL, [vThrePlIspResult] NVARCHAR (10) NULL, [vThourPlIspResult] NVARCHAR (10) NULL, [nPosPlPlIspResult] NVARCHAR (10) NULL, [nPosSvPlIspResult] NVARCHAR (10) NULL, [commentPlIspResult] NTEXT NULL, [yearPlIspResult] INT NOT NULL, FOREIGN KEY ([idElementPlIspResult]) REFERENCES [Elements] ([id]) ON DELETE CASCADE, FOREIGN KEY ([idFactoryPlIspResult]) REFERENCES [Factorys] ([id]) ON DELETE CASCADE, FOREIGN KEY ([idPoligonPlIspResult]) REFERENCES [Poligons] ([id]) ON DELETE CASCADE, FOREIGN KEY ([idVidIspPlIspResult]) REFERENCES [VidIsp] ([id]) ON DELETE CASCADE );" +
                        "CREATE TABLE [PotrVKIResult] ( [id] INTEGER PRIMARY KEY AUTOINCREMENT, [idFactoryPotrVKIResult] INT NOT NULL, [numberPotrVKIResult] NVARCHAR (10) NULL, [idElementPotrVKIResult] INT NOT NULL, [idEdIzmPotrVKIResult] INT NOT NULL, [vOnePotrVKIResult] NVARCHAR (10) NULL, [vTwoPotrVKIResult] NVARCHAR (10) NULL, [vThrePotrVKIResult] NVARCHAR (10) NULL, [vThourPotrVKIResult] NVARCHAR (10) NULL, [yearPotrVKIResult] INT NOT NULL, FOREIGN KEY ([idElementPotrVKIResult]) REFERENCES [Elements] ([id]) ON DELETE CASCADE, FOREIGN KEY ([idFactoryPotrVKIResult]) REFERENCES [Factorys] ([id]) ON DELETE CASCADE, FOREIGN KEY ([idEdIzmPotrVKIResult]) REFERENCES [EdIzm] ([id]) ON DELETE CASCADE );" +
                        "CREATE TABLE [Users] ( [id] INTEGER PRIMARY KEY AUTOINCREMENT, [userLogin] NVARCHAR (10) NOT NULL, [userPassword] NVARCHAR (10) NOT NULL, [userName] NVARCHAR (21) NOT NULL, [userFamilly] NVARCHAR (21) NOT NULL, [userPatronymic] NVARCHAR (21) NOT NULL, [userComp] NVARCHAR (10) NOT NULL, [userAdmin] BIT NOT NULL);" +
                        "CREATE TABLE [UsersHistoryAuth] ( [id] INTEGER PRIMARY KEY AUTOINCREMENT, [idUser] INT NULL, [loginUser] NVARCHAR (21) NOT NULL, [passwordUser] NVARCHAR (21) NOT NULL, [authCheck] BIT NOT NULL, [dateAuth] NVARCHAR (21) NOT NULL, FOREIGN KEY ([idUser]) REFERENCES [Users] ([id]) ON DELETE SET NULL );" +
                        "CREATE TABLE [VIspMespl] ( [id] INTEGER PRIMARY KEY AUTOINCREMENT, [codeElementVIspMespl] INT NOT NULL, [codeFactoryVIspMespl] INT NOT NULL, [codeVidIspVIspMespl] INT NOT NULL, [codeMCVIspMespl] INT NULL, [vOneVIspMespl] NVARCHAR (10) NULL, [vTwoVIspMespl] NVARCHAR (10) NULL, [vThreVIspMespl] NVARCHAR (10) NULL, [vThourVIspMespl] NVARCHAR (10) NULL, [nPosPlVIspMespl] NVARCHAR (10) NULL, [nPosSvVIspMespl] NVARCHAR (10) NULL, [commentVIspMespl] NTEXT NULL, [yearVIspMespl] INT NOT NULL, FOREIGN KEY ([codeElementVIspMespl]) REFERENCES [Elements] ([id]) ON DELETE CASCADE, FOREIGN KEY ([codeFactoryVIspMespl]) REFERENCES [Factorys] ([id]) ON DELETE CASCADE, FOREIGN KEY ([codeVidIspVIspMespl]) REFERENCES [VidIsp] ([id]) ON DELETE CASCADE, FOREIGN KEY ([codeMCVIspMespl]) REFERENCES [MatChast] ([id]) ON DELETE SET NULL );" +
                        "CREATE TABLE [VKMespl] ( [id] INTEGER PRIMARY KEY AUTOINCREMENT, [idFIMespl] INT NOT NULL, [idElementVKMespl] INT NOT NULL, [edIzmVKMespl] INT NOT NULL, [countVKMespl] DECIMAL (12, 2) NOT NULL, FOREIGN KEY ([idElementVKMespl]) REFERENCES [Elements] ([id]) ON DELETE CASCADE, FOREIGN KEY ([idFIMespl]) REFERENCES [FIMespl] ([id]), FOREIGN KEY ([edIzmVKMespl]) REFERENCES [EdIzm] ([id]) ON DELETE CASCADE );"+
                        "INSERT INTO [Users] ('userLogin','userPassword','userName','userFamilly','userPatronymic','userComp','userAdmin') VALUES ('123','','Михаил','Котков','Александрович','1',1)", connection);
                    command.ExecuteNonQuery();
                    connection.Close();
                    MessageBox.Show("База данных создана!");
                }
                else
                    MessageBox.Show("База данных уже существует!");
            }
            catch (Exception ex)
            {
                MessageBox.Show(ex.Message.ToString(), ex.Source.ToString(), MessageBoxButtons.OK, MessageBoxIcon.Error);
            }
            finally
            {
                if (connection != null && connection.State != ConnectionState.Closed)
                    connection.Close();
            }
        }

        //Сохранение БД
        private void button5_Click(object sender, EventArgs e)
        {
            if (DialogResult.Yes == MessageBox.Show("В случае сохранения базы данных, уже сохраненная информация будет утерена!\nЖелаете продолжить?", "Подтверждение", MessageBoxButtons.YesNo, MessageBoxIcon.Question))
            {
                string resultInformation = "";
                //Справочник цен на комплектующие изделия (CenaKE)
                resultInformation += functionQueryAllBD("CenaKE");
                //Справочник цен видов испытаний (CenaVidIsp)
                resultInformation += functionQueryAllBD("CenaVidIsp");
                //Справочник единиц измерений (EdIzm)
                resultInformation += functionQueryAllBD("EdIzm");
                //Справочник элементов (Elements)
                resultInformation += functionQueryAllBD("Elements");
                //Справочник каталогов элементов (ElementsSpravochnik)
                resultInformation += functionQueryAllBD("ElementsSpravochnik");
                //Справочник предприятий (Factorys)
                resultInformation += functionQueryAllBD("Factorys");
                //Форматки испытаний (FIMespl)
                resultInformation += functionQueryAllBD("FIMespl");
                //Справочник мат. части (MatChast)
                resultInformation += functionQueryAllBD("MatChast");
                //Справочник ненужных ведомости комплектаций (noneVK)
                resultInformation += functionQueryAllBD("noneVK");
                //Справочник нормы проведения испытаний (NormTimeIsp)
                resultInformation += functionQueryAllBD("NormTimeIsp");
                //Годовой план производства (PlIndKontrol)
                resultInformation += functionQueryAllBD("PlIndKontrol");
                //Результ план производства (PlIndResult)
                resultInformation += functionQueryAllBD("PlIndResult");
                //Годовой план испытаний (PlIspKontrol)
                resultInformation += functionQueryAllBD("PlIspKontrol");
                //Месячный план испытаний (PlIspMespl)
                resultInformation += functionQueryAllBD("PlIspMespl");
                //Результ план испытаний (PlIspResult)
                resultInformation += functionQueryAllBD("PlIspResult");
                //Справочник полигонов (Poligons)
                resultInformation += functionQueryAllBD("Poligons");
                //Потребность в крешерном имуществе (PotrVKIResult)
                resultInformation += functionQueryAllBD("PotrVKIResult");
                //Справочник преград (PregradInfo)
                resultInformation += functionQueryAllBD("PregradInfo");
                //Справочник наименований преград (PregradName)
                resultInformation += functionQueryAllBD("PregradName");
                //Справочник пользователей (Users)
                resultInformation += functionQueryAllBD("Users");
                //Посещаемость (UsersHistoryAuth)
                resultInformation += functionQueryAllBD("UsersHistoryAuth");
                //Справочник видов испытаний (VidIsp)
                resultInformation += functionQueryAllBD("VidIsp");
                //Месячный объем испытаний (VIspMespl)
                resultInformation += functionQueryAllBD("VIspMespl");
                //Ведомость комплектаций (VKMespl)
                resultInformation += functionQueryAllBD("VKMespl");
                MessageBox.Show(resultInformation, "Информация", MessageBoxButtons.OK, MessageBoxIcon.Information);
            }

        }

        //Загрузка БД
        private void button6_Click(object sender, EventArgs e)
        {
            /*if (DialogResult.Yes == MessageBox.Show("Вы желаете продолжить добавление новых данных в БД?", "Подтверждение", MessageBoxButtons.YesNo, MessageBoxIcon.Question))
            {
                string resultInformation = "";
                //Справочник цен на комплектующие изделия (CenaKE)
                resultInformation += functionLoadAllBD("CenaKE");
                //Справочник цен видов испытаний (CenaVidIsp)
                resultInformation += functionLoadAllBD("CenaVidIsp");
                //Справочник единиц измерений (EdIzm)
                resultInformation += functionLoadAllBD("EdIzm");
                //Справочник элементов (Elements)
                resultInformation += functionLoadAllBD("Elements");
                //Справочник каталогов элементов (ElementsSpravochnik)
                //resultInformation += functionLoadAllBD("ElementsSpravochnik");
                //Справочник предприятий (Factorys)
                resultInformation += functionLoadAllBD("Factorys");
                //Форматки испытаний (FIMespl)
                resultInformation += functionLoadAllBD("FIMespl");
                //Справочник мат. части (MatChast)
                resultInformation += functionLoadAllBD("MatChast");
                //Справочник ненужных ведомости комплектаций (noneVK)
                resultInformation += functionLoadAllBD("noneVK");
                //Справочник нормы проведения испытаний (NormTimeIsp)
                resultInformation += functionLoadAllBD("NormTimeIsp");
                //Годовой план производства (PlIndKontrol)
                resultInformation += functionLoadAllBD("PlIndKontrol");
                //Результ план производства (PlIndResult)
                resultInformation += functionLoadAllBD("PlIndResult");
                //Годовой план испытаний (PlIspKontrol)
                resultInformation += functionLoadAllBD("PlIspKontrol");
                //Месячный план испытаний (PlIspMespl)
                resultInformation += functionLoadAllBD("PlIspMespl");
                //Результ план испытаний (PlIspResult)
                resultInformation += functionLoadAllBD("PlIspResult");
                //Справочник полигонов (Poligons)
                resultInformation += functionLoadAllBD("Poligons");
                //Потребность в крешерном имуществе (PotrVKIResult)
                resultInformation += functionLoadAllBD("PotrVKIResult");
                //Справочник преград (PregradInfo)
                resultInformation += functionLoadAllBD("PregradInfo");
                //Справочник наименований преград (PregradName)
                resultInformation += functionLoadAllBD("PregradName");
                //Справочник пользователей (Users)
                resultInformation += functionLoadAllBD("Users");
                //Посещаемость (UsersHistoryAuth)
                resultInformation += functionLoadAllBD("UsersHistoryAuth");
                //Справочник видов испытаний (VidIsp)
                resultInformation += functionLoadAllBD("VidIsp");
                //Месячный объем испытаний (VIspMespl)
                resultInformation += functionLoadAllBD("VIspMespl");
                //Ведомость комплектаций (VKMespl)
                resultInformation += functionLoadAllBD("VKMespl");
                MessageBox.Show(resultInformation, "Информация", MessageBoxButtons.OK, MessageBoxIcon.Information);
            }*/
        }

        //Очистка БД
        private void button7_Click(object sender, EventArgs e)
        {
            if (DialogResult.Yes == MessageBox.Show("База данных будет полностью очищена!\nВы уверены, что желаете продолжить?", "Подтверждение", MessageBoxButtons.YesNo, MessageBoxIcon.Question))
                if (DialogResult.Yes == MessageBox.Show("Очистка база данных! Продолжить?", "Подтверждение", MessageBoxButtons.YesNo, MessageBoxIcon.Question))
                    functionClearAllBD();
        }

        private void functionShowUser(int pos)
        {
            if (!spUserEdit)
            {
                if (pos >= 0 && usersList.Count > 0)
                {
                    textBox1.Text = Convert.ToString(usersList[pos].familly);
                    textBox3.Text = Convert.ToString(usersList[pos].name);
                    textBox2.Text = Convert.ToString(usersList[pos].patronymic);
                    textBox4.Text = Convert.ToString(usersList[pos].login);
                    textBox5.Text = Convert.ToString(usersList[pos].password);
                    textBox6.Text = Convert.ToString(usersList[pos].comp);
                    textBox7.Text = Convert.ToString(usersList[pos].id);
                }
                else
                {
                    textBox1.Text = "";
                    textBox2.Text = "";
                    textBox3.Text = "";
                    textBox4.Text = "";
                    textBox5.Text = "";
                    textBox6.Text = "";
                    textBox7.Text = "";
                }
            }
        }

        //Назад
        private void button8_Click(object sender, EventArgs e)
        {
            createPositionUser(0);
        }

        //Вперед
        private void button10_Click(object sender, EventArgs e)
        {
            createPositionUser(1);
        }

        //Позиция пользователя
        private void createPositionUser(int s)
        {
            if (s == 0)
            {
                if (positionUser - 1 >= 0)
                    positionUser--;
            }
            else
            {
                if (positionUser + 1 < usersList.Count)
                    positionUser++;
            }
            functionShowUser(positionUser);
        }

        //Добавить
        private void button11_Click(object sender, EventArgs e)
        {
            functionShowUser(-1);
            spUserEdit = true;
            textBox1.ReadOnly = false;
            textBox2.ReadOnly = false;
            textBox3.ReadOnly = false;
            textBox4.ReadOnly = false;
            textBox5.ReadOnly = false;
            textBox6.ReadOnly = false;
            textBox7.ReadOnly = false;
            button8.Visible = false;
            button9.Visible = false;
            button10.Visible = false;
            button11.Visible = false;
            button12.Visible = true;
            button13.Visible = true;
            textBox1.Focus();
        }

        //Редактировать
        private void button9_Click(object sender, EventArgs e)
        {
            spUserEdit = true;
            textBox1.ReadOnly = false;
            textBox2.ReadOnly = false;
            textBox3.ReadOnly = false;
            textBox4.ReadOnly = false;
            textBox5.ReadOnly = false;
            textBox6.ReadOnly = false;
            textBox7.ReadOnly = false;
            button8.Visible = false;
            button9.Visible = false;
            button10.Visible = false;
            button11.Visible = false;
            button12.Visible = true;
            button14.Visible = true;
            textBox1.Focus();
        }

        private void Form22_FormClosing(object sender, FormClosingEventArgs e)
        {
            usersList.Clear();
            if (connection != null && connection.State != ConnectionState.Closed)
                connection.Close();
        }

        //Кнопка отмены добавления и редактирования
        private void button12_Click(object sender, EventArgs e)
        {
            functionCancelAddNewUser();
        }

        private void functionCancelAddNewUser()
        {
            spUserEdit = false;
            textBox1.ReadOnly = true;
            textBox2.ReadOnly = true;
            textBox3.ReadOnly = true;
            textBox4.ReadOnly = true;
            textBox5.ReadOnly = true;
            textBox6.ReadOnly = true;
            textBox7.ReadOnly = true;
            button8.Visible = true;
            button9.Visible = true;
            button10.Visible = true;
            button11.Visible = true;
            button12.Visible = false;
            button13.Visible = false;
            button14.Visible = false;
            functionShowUser(positionUser);
        }

        //Подтверждение добавления
        private void button13_Click(object sender, EventArgs e)
        {
            if (!string.IsNullOrEmpty(textBox1.Text) && !string.IsNullOrWhiteSpace(textBox1.Text) && !string.IsNullOrEmpty(textBox2.Text) && !string.IsNullOrWhiteSpace(textBox2.Text) && !string.IsNullOrEmpty(textBox3.Text) && !string.IsNullOrWhiteSpace(textBox3.Text) && !string.IsNullOrEmpty(textBox6.Text) && !string.IsNullOrWhiteSpace(textBox6.Text))
            {
                bool proverkaNewUser = true;
                foreach (ClassUsers user in usersList)
                    if (user.login == textBox4.Text)
                        proverkaNewUser = false;
                if (proverkaNewUser)
                {
                    try
                    {
                        connection = new SQLiteConnection(string.Format("Data Source={0};", databaseName));
                        connection.Open();
                        SQLiteCommand command = new SQLiteCommand("INSERT INTO [Users] ('userLogin','userPassword','userName','userFamilly','userPatronymic','userComp','userAdmin') VALUES (@userLogin,@userPassword,@userName,@userFamilly,@userPatronymic,@userComp,@userAdmin)", connection);
                        command.Parameters.AddWithValue("userLogin", textBox4.Text);
                        command.Parameters.AddWithValue("userPassword", textBox5.Text);
                        command.Parameters.AddWithValue("userName", textBox3.Text);
                        command.Parameters.AddWithValue("userFamilly", textBox1.Text);
                        command.Parameters.AddWithValue("userPatronymic", textBox2.Text);
                        command.Parameters.AddWithValue("userComp", textBox6.Text);
                        command.Parameters.AddWithValue("userAdmin", 0);
                        command.ExecuteNonQuery();
                        functionQueryUsers();
                        functionCancelAddNewUser();
                        positionUser = usersList.Count - 1;
                        functionShowUser(positionUser);
                        connection.Close();
                    }
                    catch (Exception ex)
                    {
                        MessageBox.Show(ex.Message.ToString(), ex.Source.ToString(), MessageBoxButtons.OK, MessageBoxIcon.Error);
                    }
                    finally
                    {
                        if (reader != null)
                            reader.Close();
                        if (connection != null && connection.State != ConnectionState.Closed)
                            connection.Close();
                    }
                }
                else
                {
                    MessageBox.Show("Пользователь с таким логином уже существует!", "Внимение!", MessageBoxButtons.OK, MessageBoxIcon.Error);
                }
            }
            else
            {
                MessageBox.Show("Поля 'Фамилия','Имя','Отчество' и 'Номер компьютера' должны быть заполнены!", "Внимение!", MessageBoxButtons.OK, MessageBoxIcon.Error);
            }
        }

        //Подтверждение сохранения
        private void button14_Click(object sender, EventArgs e)
        {
            if (!string.IsNullOrEmpty(textBox1.Text) && !string.IsNullOrWhiteSpace(textBox1.Text) && !string.IsNullOrEmpty(textBox2.Text) && !string.IsNullOrWhiteSpace(textBox2.Text) && !string.IsNullOrEmpty(textBox3.Text) && !string.IsNullOrWhiteSpace(textBox3.Text) && !string.IsNullOrEmpty(textBox6.Text) && !string.IsNullOrWhiteSpace(textBox6.Text))
            {
                connection = new SQLiteConnection(string.Format("Data Source={0};", databaseName));
                connection.Open();
                SQLiteCommand command = new SQLiteCommand("UPDATE [Users] SET [userLogin]=@userLogin,[userPassword]=@userPassword,[userName]=@userName,[userFamilly]=@userFamilly,[userPatronymic]=@userPatronymic,[userComp]=@userComp WHERE [id]=@id", connection);
                command.Parameters.AddWithValue("id", textBox7.Text);
                command.Parameters.AddWithValue("userLogin", textBox4.Text);
                command.Parameters.AddWithValue("userPassword", textBox5.Text);
                command.Parameters.AddWithValue("userName", textBox3.Text);
                command.Parameters.AddWithValue("userFamilly", textBox1.Text);
                command.Parameters.AddWithValue("userPatronymic", textBox2.Text);
                command.Parameters.AddWithValue("userComp", textBox6.Text);
                command.ExecuteNonQuery();
                connection.Close();
                functionCancelAddNewUser();
                functionQueryUsers();
                functionShowUser(positionUser);
            }
            else
            {
                MessageBox.Show("Поля 'Фамилия','Имя','Отчество' и 'Номер компьютера' должны быть заполнены!", "Внимение!", MessageBoxButtons.OK, MessageBoxIcon.Error);
            }
        }

        //------------------------------БАЗА ДАННЫХ------------------------------
        //Пользователи
        private void functionQueryUsers()
        {
            connection = new SQLiteConnection(string.Format("Data Source={0};", databaseName));
            connection.Open();
            reader = null;
            SQLiteCommand command = new SQLiteCommand("SELECT * FROM Users ORDER BY id ASC", connection);
            try
            {
                usersList.Clear();
                reader = command.ExecuteReader();
                while (reader.Read())
                {
                    ClassUsers user = new ClassUsers();
                    user.id = Convert.ToInt32(reader["id"]);
                    //Элемент
                    user.familly = Convert.ToString(reader["userFamilly"]);
                    user.name = Convert.ToString(reader["userName"]);
                    user.patronymic = Convert.ToString(reader["userPatronymic"]);
                    user.login = Convert.ToString(reader["userLogin"]);
                    user.password = Convert.ToString(reader["userPassword"]);
                    user.comp = Convert.ToString(reader["userComp"]);
                    usersList.Add(user);
                }
            }
            catch (Exception ex)
            {
                MessageBox.Show(ex.Message.ToString(), ex.Source.ToString(), MessageBoxButtons.OK, MessageBoxIcon.Error);
            }
            finally
            {
                if (reader != null)
                    reader.Close();
                if (connection != null && connection.State != ConnectionState.Closed)
                    connection.Close();
            }
        }

        //Выгрузка из БД
        private string functionQueryAllBD(string nameTable)
        {
            string pathForNewFile = Properties.Settings.Default.SettingPatch + @"BD\backup\"+nameTable+".txt";
            string resultInformation = "";
            List<string> strAllTable = new List<string>();
            connection = new SQLiteConnection(string.Format("Data Source={0};", databaseName));
            connection.Open();
            reader = null;
            SQLiteCommand command = new SQLiteCommand("SELECT * FROM " + nameTable + " ORDER BY id ASC", connection);
            try
            {
                reader = command.ExecuteReader();
                while (reader.Read())
                {
                    string strRowsTable = "";
                    switch (nameTable)
                    {
                        case "CenaKE":
                            strRowsTable = reader["id"] + ";" + reader["idElementCenaKE"] + ";" + reader["idEdIzmCenzKE"] + ";" + reader["cenaCenaKE"] + ";" + reader["numberPozCenaKE"] + ";" + reader["commentCenaKE"];
                            strAllTable.Add(strRowsTable);
                            break;
                        case "CenaVidIsp":
                            strRowsTable = reader["id"] + ";" + reader["poligonCenaVidIsp"] + ";" + reader["idElementCenaVidIsp"] + ";" + reader["idVidIspCenaVidIsp"] + ";" + reader["mcCenaVidIsp"] + ";" + reader["cenaCenaVidIsp"];
                            strAllTable.Add(strRowsTable);
                            break;
                        case "EdIzm":
                            strRowsTable = reader["id"] + ";" + reader["codeEdIzm2"] + ";" + reader["cpdeEdIzm3"] + ";" + reader["nameFull"] + ";" + reader["nameSocr"];
                            strAllTable.Add(strRowsTable);
                            break;
                        case "Elements":
                            strRowsTable = reader["id"] + ";" + reader["codeElement"] + ";" + reader["picture"] + ";" + reader["indexElement"] + ";" + reader["nameElement"] + ";" + reader["sp"] + ";" + reader["idFactoryPostElements"];
                            strAllTable.Add(strRowsTable);
                            break;
                        case "ElementsSpravochnik":
                            strRowsTable = reader["id"] + ";" + reader["nameSp"];
                            strAllTable.Add(strRowsTable);
                            break;
                        case "Factorys":
                            strRowsTable = reader["id"] + ";" + reader["codeFactory"] + ";" + reader["nameFactory"] + ";" + reader["adress"] + ";" + reader["FIO"] + ";" + reader["INN"] + ";" + reader["udal"] + ";" + reader["send"];
                            strAllTable.Add(strRowsTable);
                            break;
                        case "FIMespl":
                            strRowsTable = reader["id"] + ";" + reader["codeElementFIMespl"] + ";" + reader["vidIspFIMespl"] + ";" + reader["codeSysFIMespl"] + ";" + reader["codeUsIspMespl"] + ";" + reader["distanceFIMespl"] + ";" + reader["sizePartFIMespl"] + ";" + reader["lgotFIMespl"] + ";" + reader["countShotPartFIMespl"] + ";" + reader["countReShotFIMespl"] + ";" + reader["countPodShotFIMespl"] + ";" + reader["uslCountPartYearFIMespl"] + ";" + reader["codePregradiOneFIMespl"] + ";" + reader["livePregradiOneFIMespl"] + ";" + reader["codePregradiTwoFIMespl"] + ";" + reader["livePregradiTwoFIMespl"] + ";" + reader["liveSystemFIMespl"] + ";" + reader["prevLiveSystemFIMespl"] + ";" + reader["codeNameStFIMespl"] + ";" + reader["liveStFIMespl"] + ";" + reader["prevLiveStFIMespl"] + ";" + reader["codeNameStandFIMespl"] + ";" + reader["liveStandFIMespl"] + ";" + reader["prevLiveStandFIMespl"] + ";" + reader["koefAmorGilzFIMespl"] + ";" + reader["koefPrivedZarFIMespl"] + ";" + reader["koefPrivShotFIMespl"] + ";" + reader["uslCountZvFIMespl"] + ";" + reader["codeEdIzmFIMespl"];
                            strAllTable.Add(strRowsTable);
                            break;
                        case "MatChast":
                            strRowsTable = reader["id"] + ";" + reader["codeMC3"] + ";" + reader["codeMC5"] + ";" + reader["codeMVN"] + ";" + reader["socrNameSistem"] + ";" + reader["nameSistem"] + ";" + reader["fullNameSistem"] + ";" + reader["codeFactoryMC"] + ";" + reader["cenaMC"];
                            strAllTable.Add(strRowsTable);
                            break;
                        case "noneVK":
                            strRowsTable = reader["id"] + ";" + reader["codeElementVK"] + ";" + reader["vidIspVK"];
                            strAllTable.Add(strRowsTable);
                            break;
                        case "NormTimeIsp":
                            strRowsTable = reader["id"] + ";" + reader["idElementNormTimeIsp"] + ";" + reader["idVidIspNormTimeIsp"] + ";" + reader["firstCehNormTimeIsp"] + ";" + reader["firstCehVPNormTimeIsp"] + ";" + reader["secondCehNormTimeIsp"] + ";" + reader["secondCehVPNormTimeIsp"] + ";" + reader["temperNormTimeIsp"] + ";" + reader["temperVPNormTimeIsp"] + ";" + reader["otkNormTimeIsp"] + ";" + reader["otkVPNormTimeIsp"] + ";" + reader["twoNormTimeIsp"] + ";" + reader["twoVPNormTimeIsp"] + ";" + reader["nineNormTimeIsp"] + ";" + reader["nineVPNormTimeIsp"] + ";" + reader["grLuchNormTimeIsp"] + ";" + reader["grLuchVPNormTimeIsp"] + ";" + reader["grCrecerNormTimeIsp"] + ";" + reader["grCrecerVPNormTimeIsp"] + ";" + reader["grMeteoNormTimeIsp"] + ";" + reader["grMeteoVPNormTimeIsp"] + ";" + reader["grKamaNormTimeIsp"] + ";" + reader["grKamaVPNormTimeIsp"] + ";" + reader["grSolenoidsNormTimeIsp"] + ";" + reader["grSolenoidsVPNormTimeIsp"];
                            strAllTable.Add(strRowsTable);
                            break;
                        case "PlIndKontrol":
                            strRowsTable = reader["id"] + ";" + reader["idElementPlIndKontrol"] + ";" + reader["idFactoryPlIndKontrol"] + ";" + reader["idEdIzmPlIndKontrol"] + ";" + reader["vOnePlIndKontrol"] + ";" + reader["vTwoPlIndKontrol"] + ";" + reader["vThrePlIndKontrol"] + ";" + reader["vThourPlIndKontrol"] + ";" + reader["yearPlIndKontrol"];
                            strAllTable.Add(strRowsTable);
                            break;
                        case "PlIndResult":
                            strRowsTable = reader["id"] + ";" + reader["idElementPlIndResult"] + ";" + reader["idFactoryPlIndResult"] + ";" + reader["idEdIzmPlIndResult"] + ";" + reader["vOnePlIndResult"] + ";" + reader["vTwoPlIndResult"] + ";" + reader["vThrePlIndResult"] + ";" + reader["vThourPlIndResult"] + ";" + reader["yearPlIndResult"];
                            strAllTable.Add(strRowsTable);
                            break;
                        case "PlIspKontrol":
                            strRowsTable = reader["id"] + ";" + reader["idElementPlIspKontrol"] + ";" + reader["idFactoryPlIspKontrol"] + ";" + reader["idPoligonPlIspKontrol"] + ";" + reader["idVidIspPlIspKontrol"] + ";" + reader["idSystemPlIspKontrol"] + ";" + reader["idStvolPlIspKontrol"] + ";" + reader["idStandPlIspKontrol"] + ";" + reader["vOnePlIspKontrol"] + ";" + reader["vTwoPlIspKontrol"] + ";" + reader["vThrePlIspKontrol"] + ";" + reader["vThourPlIspKontrol"] + ";" + reader["nPosPlPlIspKontrol"] + ";" + reader["nPosSvPlIspKontrol"] + ";" + reader["commentPlIspKontrol"] + ";" + reader["yearPlIspKontrol"];
                            strAllTable.Add(strRowsTable);
                            break;
                        case "PlIspMespl":
                            strRowsTable = reader["id"] + ";" + reader["codeElementPlIspmespl"] + ";" + reader["codeFactoryPlIspMespl"] + ";" + reader["codeVidIspPlIspmespl"] + ";" + reader["numberOfPartyPlIspmespl"] + ";" + reader["countShotPlIspmespl"] + ";" + reader["datePostPlIspmespl"] + ";" + reader["nPosPlPlIspmespl"] + ";" + reader["nPosSvPlIspmespl"] + ";" + reader["monthPlIspMespl"] + ";" + reader["yearPlIspMespl"] + ";" + reader["commentPlIspmespl"];
                            strAllTable.Add(strRowsTable);
                            break;
                        case "PlIspResult":
                            strRowsTable = reader["id"] + ";" + reader["idElementPlIspResult"] + ";" + reader["idFactoryPlIspResult"] + ";" + reader["idPoligonPlIspResult"] + ";" + reader["idVidIspPlIspResult"] + ";" + reader["idSystemPlIspResult"] + ";" + reader["idStvolPlIspResult"] + ";" + reader["idStandPlIspResult"] + ";" + reader["vOnePlIspResult"] + ";" + reader["vTwoPlIspResult"] + ";" + reader["vThrePlIspResult"] + ";" + reader["vThourPlIspResult"] + ";" + reader["nPosPlPlIspResult"] + ";" + reader["nPosSvPlIspResult"] + ";" + reader["commentPlIspResult"] + ";" + reader["yearPlIspResult"];
                            strAllTable.Add(strRowsTable);
                            break;
                        case "Poligons":
                            strRowsTable = reader["id"] + ";" + reader["codeFactoryPl"] + ";" + reader["numberPl"] + ";" + reader["namePoligon"];
                            strAllTable.Add(strRowsTable);
                            break;
                        case "PotrVKIResult":
                            strRowsTable = reader["id"] + ";" + reader["idFactoryPotrVKIResult"] + ";" + reader["numberPotrVKIResult"] + ";" + reader["idElementPotrVKIResult"] + ";" + reader["idEdIzmPotrVKIResult"] + ";" + reader["vOnePotrVKIResult"] + ";" + reader["vTwoPotrVKIResult"] + ";" + reader["vThrePotrVKIResult"] + ";" + reader["vThourPotrVKIResult"] + ";" + reader["yearPotrVKIResult"];
                            strAllTable.Add(strRowsTable);
                            break;
                        case "PregradInfo":
                            strRowsTable = reader["id"] + ";" + reader["codePregradi"] + ";" + reader["sizePregradi"] + ";" + reader["vesPregradi"] + ";" + reader["cenaPregradi"] + ";" + reader["namePregradi"];
                            strAllTable.Add(strRowsTable);
                            break;
                        case "PregradName":
                            strRowsTable = reader["id"] + ";" + reader["namePregrad"];
                            strAllTable.Add(strRowsTable);
                            break;
                        case "Users":
                            strRowsTable = reader["id"] + ";" + reader["userLogin"] + ";" + reader["userPassword"] + ";" + reader["userName"] + ";" + reader["userFamilly"] + ";" + reader["userPatronymic"] + ";" + reader["userComp"] + ";" + reader["userAdmin"];
                            strAllTable.Add(strRowsTable);
                            break;
                        case "UsersHistoryAuth":
                            strRowsTable = reader["id"] + ";" + reader["idUser"] + ";" + reader["loginUser"] + ";" + reader["passwordUser"] + ";" + reader["authCheck"] + ";" + reader["dateAuth"];
                            strAllTable.Add(strRowsTable);
                            break;
                        case "VidIsp":
                            strRowsTable = reader["id"] + ";" + reader["codeVida"] + ";" + reader["nameVida"];
                            strAllTable.Add(strRowsTable);
                            break;
                        case "VIspMespl":
                            strRowsTable = reader["id"] + ";" + reader["codeElementVIspMespl"] + ";" + reader["codeFactoryVIspMespl"] + ";" + reader["codeVidIspVIspMespl"] + ";" + reader["codeMCVIspMespl"] + ";" + reader["vOneVIspMespl"] + ";" + reader["vTwoVIspMespl"] + ";" + reader["vThreVIspMespl"] + ";" + reader["vThourVIspMespl"] + ";" + reader["nPosPlVIspMespl"] + ";" + reader["nPosSvVIspMespl"] + ";" + reader["commentVIspMespl"] + ";" + reader["yearVIspMespl"];
                            strAllTable.Add(strRowsTable);
                            break;
                        case "VKMespl":
                            strRowsTable = reader["id"] + ";" + reader["idFIMespl"] + ";" + reader["idElementVKMespl"] + ";" + reader["edIzmVKMespl"] + ";" + reader["countVKMespl"];
                            strAllTable.Add(strRowsTable);
                            break;
                        default:
                            break;
                    }
                }
            }
            catch (Exception ex)
            {
                MessageBox.Show(ex.Message.ToString(), ex.Source.ToString(), MessageBoxButtons.OK, MessageBoxIcon.Error);
            }
            finally
            {
                if (reader != null)
                    reader.Close();
                connection.Close();
            }
            if (strAllTable.Count > 0)
            {
                if (File.Exists(pathForNewFile))
                    File.Delete(pathForNewFile);
                File.WriteAllLines(pathForNewFile, strAllTable);
                resultInformation += "Таблица " + nameTable + "     сохранена!\n";
            }
            else
            {
                resultInformation += "Таблица " + nameTable + "     пуста!\n";
            }
            return resultInformation;
        }

        //Очистка БД
        private void functionClearAllBD()
        {
            try
            {
                connection = new SQLiteConnection(string.Format("Data Source={0};", databaseName));
                connection.Open();
                SQLiteCommand command = new SQLiteCommand("SELECT name FROM sqlite_master WHERE type='table' ORDER BY name;", connection);
                reader = command.ExecuteReader();
                string strCommandDrop = "";
                progressBar1.Visible = true;
                int valueProgressDelete = 0;
                foreach (DbDataRecord record in reader)
                {
                    switch (Convert.ToString(record["name"]))
                    {
                        case "EdIzm":
                            strCommandDrop += "DROP TABLE EdIzm;";
                            break;
                        case "Factorys":
                            strCommandDrop += "DROP TABLE Factorys;";
                            break;
                        case "Elements":
                            strCommandDrop += "DROP TABLE Elements;";
                            break;
                        case "ElementsSpravochnik":
                            strCommandDrop += "DROP TABLE ElementsSpravochnik;";
                            break;
                        case "MatChast":
                            strCommandDrop += "DROP TABLE MatChast;";
                            break;
                        case "CenaKE":
                            strCommandDrop += "DROP TABLE CenaKE;";
                            break;
                        case "Poligons":
                            strCommandDrop += "DROP TABLE Poligons;";
                            break;
                        case "PregradName":
                            strCommandDrop += "DROP TABLE PregradName;";
                            break;
                        case "PregradInfo":
                            strCommandDrop += "DROP TABLE PregradInfo;";
                            break;
                        case "VidIsp":
                            strCommandDrop += "DROP TABLE VidIsp;";
                            break;
                        case "CenaVidIsp":
                            strCommandDrop += "DROP TABLE CenaVidIsp;";
                            break;
                        case "FIMespl":
                            strCommandDrop += "DROP TABLE FIMespl;";
                            break;
                        case "noneVK":
                            strCommandDrop += "DROP TABLE noneVK;";
                            break;
                        case "NormTimeIsp":
                            strCommandDrop += "DROP TABLE NormTimeIsp;";
                            break;
                        case "PlIndKontrol":
                            strCommandDrop += "DROP TABLE PlIndKontrol;";
                            break;
                        case "PlIndResult":
                            strCommandDrop += "DROP TABLE PlIndResult;";
                            break;
                        case "PlIspKontrol":
                            strCommandDrop += "DROP TABLE PlIspKontrol;";
                            break;
                        case "PlIspMespl":
                            strCommandDrop += "DROP TABLE PlIspMespl;";
                            break;
                        case "PlIspResult":
                            strCommandDrop += "DROP TABLE PlIspResult;";
                            break;
                        case "PotrVKIResult":
                            strCommandDrop += "DROP TABLE PotrVKIResult;";
                            break;
                        case "Users":
                            strCommandDrop += "DROP TABLE Users;";
                            break;
                        case "UsersHistoryAuth":
                            strCommandDrop += "DROP TABLE UsersHistoryAuth;";
                            break;
                        case "VIspMespl":
                            strCommandDrop += "DROP TABLE VIspMespl;";
                            break;
                        case "VKMespl":
                            strCommandDrop += "DROP TABLE VKMespl;";
                            break;
                        default:
                            break;
                    }
                    valueProgressDelete += 1;
                    progressBar1.Value = valueProgressDelete;
                    progressBar1.Invalidate();
                }
                command = new SQLiteCommand(strCommandDrop +
                    "CREATE TABLE [EdIzm] ( [id] INTEGER PRIMARY KEY AUTOINCREMENT, [codeEdIzm2] NVARCHAR (2) NULL, [cpdeEdIzm3] NVARCHAR (3) NULL, [nameFull] NVARCHAR (10) NOT NULL, [nameSocr] NVARCHAR (10) NULL);" +
                    "CREATE TABLE [Factorys] ( [id] INTEGER PRIMARY KEY AUTOINCREMENT, [codeFactory] NVARCHAR (6) NOT NULL, [nameFactory] NTEXT NOT NULL, [adress] NTEXT NOT NULL, [FIO] NTEXT NULL, [INN] NVARCHAR (10) NULL, [udal] NVARCHAR (10) NULL, [send] NTEXT NULL);" +
                    "CREATE TABLE [Elements] ( [id] INTEGER PRIMARY KEY AUTOINCREMENT, [codeElement] NVARCHAR (10) NOT NULL, [picture] NVARCHAR (21) NULL, [indexElement] NVARCHAR (21) NULL, [nameElement] NTEXT NOT NULL, [sp] INT NOT NULL, [idFactoryPostElements] INT NULL, FOREIGN KEY ([idFactoryPostElements]) REFERENCES [Factorys] ([id]) ON DELETE SET NULL, FOREIGN KEY ([sp]) REFERENCES [ElementsSpravochnik] ([id]) ON DELETE CASCADE );" +
                    "CREATE TABLE [ElementsSpravochnik] ( [id] INTEGER PRIMARY KEY AUTOINCREMENT, [nameSp] NVARCHAR (10) NOT NULL);" +
                    "CREATE TABLE [MatChast] ( [id] INTEGER PRIMARY KEY AUTOINCREMENT, [codeMC3] NVARCHAR (3) NOT NULL, [codeMC5] NVARCHAR (5) NOT NULL, [codeMVN] NVARCHAR (10) NULL, [socrNameSistem] NTEXT NULL, [nameSistem] NTEXT NULL, [fullNameSistem] NTEXT NULL, [codeFactoryMC] INT NULL, [cenaMC] DECIMAL (12, 2) NULL, FOREIGN KEY ([codeFactoryMC]) REFERENCES [Factorys] ([id]) ON DELETE SET NULL );" +
                    "CREATE TABLE [CenaKE] ( [id] INTEGER PRIMARY KEY AUTOINCREMENT, [idElementCenaKE] INT NOT NULL, [idEdIzmCenzKE] INT NOT NULL, [cenaCenaKE] DECIMAL (12, 2) NOT NULL, [numberPozCenaKE] NVARCHAR (7) NULL, [commentCenaKE] NTEXT NULL, FOREIGN KEY ([idElementCenaKE]) REFERENCES [Elements] ([id]) ON DELETE CASCADE, FOREIGN KEY ([idEdIzmCenzKE]) REFERENCES [EdIzm] ([id]) ON DELETE CASCADE );" +
                    "CREATE TABLE [Poligons] ( [id] INTEGER PRIMARY KEY AUTOINCREMENT, [codeFactoryPl] INT NULL, [numberPl] NVARCHAR (7) NULL, [namePoligon] NTEXT NULL, FOREIGN KEY ([codeFactoryPl]) REFERENCES [Factorys] ([id]) ON DELETE SET NULL );" +
                    "CREATE TABLE [PregradName] ( [id] INTEGER PRIMARY KEY AUTOINCREMENT, [namePregrad] NVARCHAR (14) NOT NULL);" +
                    "CREATE TABLE [PregradInfo] ( [id] INTEGER PRIMARY KEY AUTOINCREMENT, [codePregradi] NVARCHAR (10) NOT NULL, [sizePregradi] NTEXT NOT NULL, [vesPregradi] DECIMAL (9, 2) NULL, [cenaPregradi] DECIMAL (12, 2) NULL, [namePregradi] INT NOT NULL, FOREIGN KEY ([namePregradi]) REFERENCES [PregradName] ([id]) ON DELETE CASCADE );" +
                    "CREATE TABLE [VidIsp] ( [id] INTEGER PRIMARY KEY AUTOINCREMENT, [codeVida] NVARCHAR (3) NOT NULL, [nameVida] NVARCHAR (21) NOT NULL);" +
                    "CREATE TABLE [CenaVidIsp] ( [id] INTEGER PRIMARY KEY AUTOINCREMENT, [poligonCenaVidIsp] NVARCHAR (7) NULL, [idElementCenaVidIsp] INT NOT NULL, [idVidIspCenaVidIsp] INT NOT NULL, [mcCenaVidIsp] INT NULL, [cenaCenaVidIsp] DECIMAL (12, 2) NOT NULL, FOREIGN KEY ([idElementCenaVidIsp]) REFERENCES [Elements] ([id]) ON DELETE CASCADE, FOREIGN KEY ([idVidIspCenaVidIsp]) REFERENCES [VidIsp] ([id]) ON DELETE CASCADE, FOREIGN KEY ([mcCenaVidIsp]) REFERENCES [MatChast] ([id]) ON DELETE CASCADE );" +
                    "CREATE TABLE [FIMespl] ( [id] INTEGER PRIMARY KEY AUTOINCREMENT, [codeElementFIMespl] INT NOT NULL, [vidIspFIMespl] INT NOT NULL, [codeSysFIMespl] INT NULL, [codeUsIspMespl] NVARCHAR (10) NULL, [distanceFIMespl] NVARCHAR (10) NULL, [sizePartFIMespl] NVARCHAR (10) NULL, [lgotFIMespl] NVARCHAR (10) NOT NULL, [countShotPartFIMespl] NVARCHAR (10) NULL, [countReShotFIMespl] NVARCHAR (10) NULL, [countPodShotFIMespl] NVARCHAR (10) NULL, [uslCountPartYearFIMespl] NVARCHAR (10) NULL, [codePregradiOneFIMespl] INT NULL, [livePregradiOneFIMespl] NVARCHAR (10) NULL, [codePregradiTwoFIMespl] INT NULL, [livePregradiTwoFIMespl] NVARCHAR (10) NULL, [liveSystemFIMespl] NVARCHAR (10) NULL, [prevLiveSystemFIMespl] NVARCHAR (10) NULL, [codeNameStFIMespl] INT NULL, [liveStFIMespl] NVARCHAR (10) NULL, [prevLiveStFIMespl] NVARCHAR (10) NULL, [codeNameStandFIMespl] INT NULL, [liveStandFIMespl] NVARCHAR (10) NULL, [prevLiveStandFIMespl] NVARCHAR (10) NULL, [koefAmorGilzFIMespl] NVARCHAR (10) NULL, [koefPrivedZarFIMespl] NVARCHAR (10) NULL, [koefPrivShotFIMespl] NVARCHAR (10) NULL, [uslCountZvFIMespl] NVARCHAR (10) NULL, [codeEdIzmFIMespl] INT NOT NULL, FOREIGN KEY ([codeNameStFIMespl]) REFERENCES [MatChast] ([id]), FOREIGN KEY ([codePregradiTwoFIMespl]) REFERENCES [PregradInfo] ([id]), FOREIGN KEY ([codeSysFIMespl]) REFERENCES [MatChast] ([id]), FOREIGN KEY ([codeElementFIMespl]) REFERENCES [Elements] ([id]) ON DELETE CASCADE, FOREIGN KEY ([vidIspFIMespl]) REFERENCES [VidIsp] ([id]) ON DELETE CASCADE, FOREIGN KEY ([codeEdIzmFIMespl]) REFERENCES [EdIzm] ([id]) ON DELETE CASCADE, FOREIGN KEY ([codeNameStandFIMespl]) REFERENCES [MatChast] ([id]) ON DELETE SET NULL, FOREIGN KEY ([codePregradiOneFIMespl]) REFERENCES [PregradInfo] ([id]) ON DELETE SET NULL );" +
                    "CREATE TABLE [noneVK] ( [id] INTEGER PRIMARY KEY AUTOINCREMENT, [codeElementVK] INT NOT NULL, [vidIspVK] INT NOT NULL, FOREIGN KEY ([vidIspVK]) REFERENCES [VidIsp] ([id]) ON DELETE CASCADE, FOREIGN KEY ([codeElementVK]) REFERENCES [Elements] ([id]) ON DELETE CASCADE );" +
                    "CREATE TABLE [NormTimeIsp] ( [id] INTEGER PRIMARY KEY AUTOINCREMENT, [idElementNormTimeIsp] INT NOT NULL, [idVidIspNormTimeIsp] INT NOT NULL, [firstCehNormTimeIsp] DECIMAL (7, 2) NULL, [firstCehVPNormTimeIsp] DECIMAL (7, 2) NULL, [secondCehNormTimeIsp] DECIMAL (7, 2) NULL, [secondCehVPNormTimeIsp] DECIMAL (7, 2) NULL, [temperNormTimeIsp] DECIMAL (7, 2) NULL, [temperVPNormTimeIsp] DECIMAL (7, 2) NULL, [otkNormTimeIsp] DECIMAL (7, 2) NULL, [otkVPNormTimeIsp] DECIMAL (7, 2) NULL, [twoNormTimeIsp] DECIMAL (7, 2) NULL, [twoVPNormTimeIsp] DECIMAL (7, 2) NULL, [nineNormTimeIsp] DECIMAL (7, 2) NULL, [nineVPNormTimeIsp] DECIMAL (7, 2) NULL, [grLuchNormTimeIsp] DECIMAL (7, 2) NULL, [grLuchVPNormTimeIsp] DECIMAL (7, 2) NULL, [grCrecerNormTimeIsp] DECIMAL (7, 2) NULL, [grCrecerVPNormTimeIsp] DECIMAL (7, 2) NULL, [grMeteoNormTimeIsp] DECIMAL (7, 2) NULL, [grMeteoVPNormTimeIsp] DECIMAL (7, 2) NULL, [grKamaNormTimeIsp] DECIMAL (7, 2) NULL, [grKamaVPNormTimeIsp] DECIMAL (7, 2) NULL, [grSolenoidsNormTimeIsp] DECIMAL (7, 2) NULL, [grSolenoidsVPNormTimeIsp] DECIMAL (7, 2) NULL, FOREIGN KEY ([idElementNormTimeIsp]) REFERENCES [Elements] ([id]) ON DELETE CASCADE, FOREIGN KEY ([idVidIspNormTimeIsp]) REFERENCES [VidIsp] ([id]) ON DELETE CASCADE );" +
                    "CREATE TABLE [PlIndKontrol] ( [id] INTEGER PRIMARY KEY AUTOINCREMENT, [idElementPlIndKontrol] INT NOT NULL, [idFactoryPlIndKontrol] INT NOT NULL, [idEdIzmPlIndKontrol] INT NOT NULL, [vOnePlIndKontrol] NVARCHAR (10) NULL, [vTwoPlIndKontrol] NVARCHAR (10) NULL, [vThrePlIndKontrol] NVARCHAR (10) NULL, [vThourPlIndKontrol] NVARCHAR (10) NULL, [yearPlIndKontrol] INT NOT NULL, FOREIGN KEY ([idElementPlIndKontrol]) REFERENCES [Elements] ([id]) ON DELETE CASCADE, FOREIGN KEY ([idFactoryPlIndKontrol]) REFERENCES [Factorys] ([id]) ON DELETE CASCADE, FOREIGN KEY ([idEdIzmPlIndKontrol]) REFERENCES [EdIzm] ([id]) ON DELETE CASCADE );" +
                    "CREATE TABLE [PlIndResult] ( [id] INTEGER PRIMARY KEY AUTOINCREMENT, [idElementPlIndResult] INT NOT NULL, [idFactoryPlIndResult] INT NOT NULL, [idEdIzmPlIndResult] INT NOT NULL, [vOnePlIndResult] NVARCHAR (10) NULL, [vTwoPlIndResult] NVARCHAR (10) NULL, [vThrePlIndResult] NVARCHAR (10) NULL, [vThourPlIndResult] NVARCHAR (10) NULL, [yearPlIndResult] INT NOT NULL, FOREIGN KEY ([idElementPlIndResult]) REFERENCES [Elements] ([id]) ON DELETE CASCADE, FOREIGN KEY ([idFactoryPlIndResult]) REFERENCES [Factorys] ([id]) ON DELETE CASCADE, FOREIGN KEY ([idEdIzmPlIndResult]) REFERENCES [EdIzm] ([id]) ON DELETE CASCADE );" +
                    "CREATE TABLE [PlIspKontrol] ( [id] INTEGER PRIMARY KEY AUTOINCREMENT, [idElementPlIspKontrol] INT NOT NULL, [idFactoryPlIspKontrol] INT NOT NULL, [idPoligonPlIspKontrol] INT NOT NULL, [idVidIspPlIspKontrol] INT NOT NULL, [idSystemPlIspKontrol] INT NULL, [idStvolPlIspKontrol] INT NULL, [idStandPlIspKontrol] INT NULL, [vOnePlIspKontrol] NVARCHAR (10) NULL, [vTwoPlIspKontrol] NVARCHAR (10) NULL, [vThrePlIspKontrol] NVARCHAR (10) NULL, [vThourPlIspKontrol] NVARCHAR (10) NULL, [nPosPlPlIspKontrol] NVARCHAR (10) NULL, [nPosSvPlIspKontrol] NVARCHAR (10) NULL, [commentPlIspKontrol] NTEXT NULL, [yearPlIspKontrol] INT NOT NULL, FOREIGN KEY ([idElementPlIspKontrol]) REFERENCES [Elements] ([id]) ON DELETE CASCADE, FOREIGN KEY ([idFactoryPlIspKontrol]) REFERENCES [Factorys] ([id]) ON DELETE CASCADE, FOREIGN KEY ([idPoligonPlIspKontrol]) REFERENCES [Poligons] ([id]) ON DELETE CASCADE, FOREIGN KEY ([idVidIspPlIspKontrol]) REFERENCES [VidIsp] ([id]) ON DELETE CASCADE );" +
                    "CREATE TABLE [PlIspMespl] ( [id] INTEGER PRIMARY KEY AUTOINCREMENT, [codeElementPlIspmespl] INT NOT NULL, [codeFactoryPlIspMespl] INT NOT NULL, [codeVidIspPlIspmespl] INT NOT NULL, [numberOfPartyPlIspmespl] NVARCHAR (10) NULL, [countShotPlIspmespl] NVARCHAR (10) NULL, [datePostPlIspmespl] NVARCHAR (10) NULL, [nPosPlPlIspmespl] NVARCHAR (10) NULL, [nPosSvPlIspmespl] NVARCHAR (10) NULL, [monthPlIspMespl] NVARCHAR (8) NOT NULL, [yearPlIspMespl] INT NOT NULL, [commentPlIspmespl] NTEXT NULL, FOREIGN KEY ([codeElementPlIspmespl]) REFERENCES [Elements] ([id]) ON DELETE CASCADE, FOREIGN KEY ([codeFactoryPlIspMespl]) REFERENCES [Factorys] ([id]) ON DELETE CASCADE, FOREIGN KEY ([codeVidIspPlIspmespl]) REFERENCES [VidIsp] ([id]) ON DELETE CASCADE );" +
                    "CREATE TABLE [PlIspResult] ( [id] INTEGER PRIMARY KEY AUTOINCREMENT, [idElementPlIspResult] INT NOT NULL, [idFactoryPlIspResult] INT NOT NULL, [idPoligonPlIspResult] INT NOT NULL, [idVidIspPlIspResult] INT NOT NULL, [idSystemPlIspResult] INT NULL, [idStvolPlIspResult] INT NULL, [idStandPlIspResult] INT NULL, [vOnePlIspResult] NVARCHAR (10) NULL, [vTwoPlIspResult] NVARCHAR (10) NULL, [vThrePlIspResult] NVARCHAR (10) NULL, [vThourPlIspResult] NVARCHAR (10) NULL, [nPosPlPlIspResult] NVARCHAR (10) NULL, [nPosSvPlIspResult] NVARCHAR (10) NULL, [commentPlIspResult] NTEXT NULL, [numberPlIspResult] NTEXT NULL, [yearPlIspResult] INT NOT NULL, FOREIGN KEY ([idElementPlIspResult]) REFERENCES [Elements] ([id]) ON DELETE CASCADE, FOREIGN KEY ([idFactoryPlIspResult]) REFERENCES [Factorys] ([id]) ON DELETE CASCADE, FOREIGN KEY ([idPoligonPlIspResult]) REFERENCES [Poligons] ([id]) ON DELETE CASCADE, FOREIGN KEY ([idVidIspPlIspResult]) REFERENCES [VidIsp] ([id]) ON DELETE CASCADE );" +
                    "CREATE TABLE [PotrVKIResult] ( [id] INTEGER PRIMARY KEY AUTOINCREMENT, [idFactoryPotrVKIResult] INT NOT NULL, [numberPotrVKIResult] NVARCHAR (10) NULL, [idElementPotrVKIResult] INT NOT NULL, [idEdIzmPotrVKIResult] INT NOT NULL, [vOnePotrVKIResult] NVARCHAR (10) NULL, [vTwoPotrVKIResult] NVARCHAR (10) NULL, [vThrePotrVKIResult] NVARCHAR (10) NULL, [vThourPotrVKIResult] NVARCHAR (10) NULL, [yearPotrVKIResult] INT NOT NULL, FOREIGN KEY ([idElementPotrVKIResult]) REFERENCES [Elements] ([id]) ON DELETE CASCADE, FOREIGN KEY ([idFactoryPotrVKIResult]) REFERENCES [Factorys] ([id]) ON DELETE CASCADE, FOREIGN KEY ([idEdIzmPotrVKIResult]) REFERENCES [EdIzm] ([id]) ON DELETE CASCADE );" +
                    "CREATE TABLE [Users] ( [id] INTEGER PRIMARY KEY AUTOINCREMENT, [userLogin] NVARCHAR (10) NOT NULL, [userPassword] NVARCHAR (10) NOT NULL, [userName] NVARCHAR (21) NOT NULL, [userFamilly] NVARCHAR (21) NOT NULL, [userPatronymic] NVARCHAR (21) NOT NULL, [userComp] NVARCHAR (10) NOT NULL, [userAdmin] BIT NOT NULL);" +
                    "CREATE TABLE [UsersHistoryAuth] ( [id] INTEGER PRIMARY KEY AUTOINCREMENT, [idUser] INT NULL, [loginUser] NVARCHAR (21) NOT NULL, [passwordUser] NVARCHAR (21) NOT NULL, [authCheck] BIT NOT NULL, [dateAuth] NVARCHAR (21) NOT NULL, FOREIGN KEY ([idUser]) REFERENCES [Users] ([id]) ON DELETE SET NULL );" +
                    "CREATE TABLE [VIspMespl] ( [id] INTEGER PRIMARY KEY AUTOINCREMENT, [codeElementVIspMespl] INT NOT NULL, [codeFactoryVIspMespl] INT NOT NULL, [codeVidIspVIspMespl] INT NOT NULL, [codeMCVIspMespl] INT NULL, [vOneVIspMespl] NVARCHAR (10) NULL, [vTwoVIspMespl] NVARCHAR (10) NULL, [vThreVIspMespl] NVARCHAR (10) NULL, [vThourVIspMespl] NVARCHAR (10) NULL, [nPosPlVIspMespl] NVARCHAR (10) NULL, [nPosSvVIspMespl] NVARCHAR (10) NULL, [commentVIspMespl] NTEXT NULL, [yearVIspMespl] INT NOT NULL, FOREIGN KEY ([codeElementVIspMespl]) REFERENCES [Elements] ([id]) ON DELETE CASCADE, FOREIGN KEY ([codeFactoryVIspMespl]) REFERENCES [Factorys] ([id]) ON DELETE CASCADE, FOREIGN KEY ([codeVidIspVIspMespl]) REFERENCES [VidIsp] ([id]) ON DELETE CASCADE, FOREIGN KEY ([codeMCVIspMespl]) REFERENCES [MatChast] ([id]) ON DELETE SET NULL );" +
                    "CREATE TABLE [VKMespl] ( [id] INTEGER PRIMARY KEY AUTOINCREMENT, [idFIMespl] INT NOT NULL, [idElementVKMespl] INT NOT NULL, [edIzmVKMespl] INT NOT NULL, [countVKMespl] DECIMAL (12, 2) NOT NULL, FOREIGN KEY ([idElementVKMespl]) REFERENCES [Elements] ([id]) ON DELETE CASCADE, FOREIGN KEY ([idFIMespl]) REFERENCES [FIMespl] ([id]), FOREIGN KEY ([edIzmVKMespl]) REFERENCES [EdIzm] ([id]) ON DELETE CASCADE );" +
                    "INSERT INTO [ElementsSpravochnik] ('nameSp') VALUES ('НТИИМ');INSERT INTO [ElementsSpravochnik] ('nameSp') VALUES ('ГВЦ');" +
                    "INSERT INTO [PregradName] ('namePregrad') VALUES ('Бронеплита');" +
                    "INSERT INTO [PregradName] ('namePregrad') VALUES ('Стальной лист');" +
                    "INSERT INTO [PregradName] ('namePregrad') VALUES ('Дюралевый лист');" +
                    "INSERT INTO [PregradName] ('namePregrad') VALUES ('Дощатый щит');" +
                    "INSERT INTO [PregradName] ('namePregrad') VALUES ('Фанерный щит');" +
                    "INSERT INTO [PregradName] ('namePregrad') VALUES ('Картонный щит');" +
                    "INSERT INTO [Users] ('userLogin','userPassword','userName','userFamilly','userPatronymic','userComp','userAdmin') VALUES ('123','','Михаил','Котков','Александрович','1',1)", connection);
                command.ExecuteNonQuery();
                connection.Close();
                MessageBox.Show("База данных очищена!");
                progressBar1.Visible = false;
            }
            catch (Exception ex)
            {
                MessageBox.Show(ex.Message.ToString(), ex.Source.ToString(), MessageBoxButtons.OK, MessageBoxIcon.Error);
            }
        }

        //Загрузка в БД
        private string functionLoadAllBD(string nameTable)//, List<string> strTableLoad)
        {
            string pathForOldFile = Properties.Settings.Default.SettingPatch + @"BD\backup\" + nameTable + ".txt";
            string resultInformation = "";
            if (File.Exists(pathForOldFile))
            {
                FileStream file = new FileStream(pathForOldFile, FileMode.Open);
                StreamReader readerFile = new StreamReader(file, Encoding.UTF8);
                string[] strLoad = readerFile.ReadToEnd().Split('\n');
                readerFile.Close();
                connection = new SQLiteConnection(string.Format("Data Source={0};", databaseName));
                connection.Open();
                reader = null;
                try
                {
                    SQLiteCommand command;
                    foreach (string strInTable in strLoad)
                    {
                        string[] arrTable = strInTable.Split(';');
                        if (arrTable.Length > 1)
                        {
                            switch (nameTable)
                            {
                                case "CenaKE":
                                    command = new SQLiteCommand("INSERT [CenaKE] (idElementCenaKE,idEdIzmCenzKE,cenaCenaKE,numberPozCenaKE,commentCenaKE) VALUES (@value1,@value2,@value3,@value4,@value5)", connection);
                                    command.Parameters.AddWithValue("value1", Convert.ToInt32(arrTable[1]));
                                    command.Parameters.AddWithValue("value2", Convert.ToInt32(arrTable[2]));
                                    command.Parameters.AddWithValue("value3", Convert.ToDouble(arrTable[3]));
                                    command.Parameters.AddWithValue("value4", arrTable[4]);
                                    command.Parameters.AddWithValue("value5", arrTable[5]);
                                    reader = command.ExecuteReader();
                                    break;
                                case "EdIzm":
                                    command = new SQLiteCommand("INSERT [EdIzm] (codeEdIzm2,cpdeEdIzm3,nameFull,nameSocr) VALUES (@value1,@value2,@value3,@value4)", connection);
                                    command.Parameters.AddWithValue("value1", arrTable[1]);
                                    command.Parameters.AddWithValue("value2", arrTable[2]);
                                    command.Parameters.AddWithValue("value3", arrTable[3]);
                                    command.Parameters.AddWithValue("value4", arrTable[4]);
                                    reader = command.ExecuteReader();
                                    break;
                                case "ElementsSpravochnik":
                                    command = new SQLiteCommand("INSERT [ElementsSpravochnik] (nameSp) VALUES (@value1)", connection);
                                    command.Parameters.AddWithValue("value1", arrTable[1]);
                                    reader = command.ExecuteReader();
                                    break;
                                case "Factorys":
                                    command = new SQLiteCommand("INSERT [Factorys] (codeFactory,nameFactory,adress,FIO,INN,udal,send) VALUES (@value1,@value2,@value3,@value4,@value5,@value6,@value7)", connection);
                                    command.Parameters.AddWithValue("value1", arrTable[1]);
                                    command.Parameters.AddWithValue("value2", arrTable[2]);
                                    command.Parameters.AddWithValue("value3", arrTable[3]);
                                    command.Parameters.AddWithValue("value4", arrTable[4]);
                                    command.Parameters.AddWithValue("value5", arrTable[5]);
                                    command.Parameters.AddWithValue("value6", arrTable[6]);
                                    command.Parameters.AddWithValue("value7", arrTable[7]);
                                    reader = command.ExecuteReader();
                                    break;
                                /*case "CenaVidIsp":
                                    if (arrTable[4].Length > 0)
                                        command = new SqlCommand("INSERT [CenaVidIsp] (poligonCenaVidIsp,idElementCenaVidIsp,idVidIspCenaVidIsp,mcCenaVidIsp,cenaCenaVidIsp) VALUES (@value1,@value2,@value3,@value4,@value5)", sqlConnection);
                                    else
                                        command = new SqlCommand("INSERT [CenaVidIsp] (poligonCenaVidIsp,idElementCenaVidIsp,idVidIspCenaVidIsp,cenaCenaVidIsp) VALUES (@value1,@value2,@value3,@value5)", sqlConnection);
                                    command.Parameters.AddWithValue("value1", arrTable[1]);
                                    command.Parameters.AddWithValue("value2", Convert.ToInt32(arrTable[2]));
                                    command.Parameters.AddWithValue("value3", Convert.ToInt32(arrTable[3]));
                                    command.Parameters.AddWithValue("value4", Convert.ToInt32(arrTable[4]));
                                    command.Parameters.AddWithValue("value5", Convert.ToDouble(arrTable[5]));
                                    sqlReader = command.ExecuteReader();
                                    break;*/
                                /*case "Elements":
                                    if (arrTable[6].Length > 0)
                                        command = new SqlCommand("INSERT [Elements] (codeElement,picture,indexElement,nameElement,sp,idFactoryPostElements) VALUES (@value1,@value2,@value3,@value4,@value5,@value6)", sqlConnection);
                                    else
                                        command = new SqlCommand("INSERT [Elements] (codeElement,picture,indexElement,nameElement,sp) VALUES (@value1,@value2,@value3,@value4,@value5)", sqlConnection);
                                    command.Parameters.AddWithValue("value1", arrTable[1]);
                                    command.Parameters.AddWithValue("value2", arrTable[2]);
                                    command.Parameters.AddWithValue("value3", arrTable[3]);
                                    command.Parameters.AddWithValue("value4", arrTable[4]);
                                    command.Parameters.AddWithValue("value5", arrTable[5]);
                                    command.Parameters.AddWithValue("value6", arrTable[6]);
                                    sqlReader = command.ExecuteReader();
                                    break;*/
                                /*case "FIMespl":
                                    string polInBD = "";
                                    string valInBD = "";
                                    if (arrTable[3] != "000")
                                    {
                                        polInBD += ",codeSysFIMespl";
                                        valInBD += ",@value3";
                                    }
                                    if (arrTable[4].Length > 0)
                                    {
                                        polInBD += ",codeUsIspMespl";
                                        valInBD += ",@value4";
                                    }
                                    if (arrTable[5].Length > 0)
                                    {
                                        polInBD += ",distanceFIMespl";
                                        valInBD += ",@value5";
                                    }
                                    if (arrTable[6].Length > 0)
                                    {
                                        polInBD += ",sizePartFIMespl";
                                        valInBD += ",@value6";
                                    }
                                    if (arrTable[7].Length > 0)
                                    {
                                        polInBD += ",lgotFIMespl";
                                        valInBD += ",@value7";
                                    }
                                    if (arrTable[8].Length > 0)
                                    {
                                        polInBD += ",countShotPartFIMespl";
                                        valInBD += ",@value8";
                                    }
                                    if (arrTable[9].Length > 0)
                                    {
                                        polInBD += ",countReShotFIMespl";
                                        valInBD += ",@value9";
                                    }
                                    if (arrTable[10].Length > 0)
                                    {
                                        polInBD += ",countPodShotFIMespl";
                                        valInBD += ",@value10";
                                    }
                                    if (arrTable[11].Length > 0)
                                    {
                                        polInBD += ",uslCountPartYearFIMespl";
                                        valInBD += ",@value11";
                                    }
                                    if (arrTable[12] != "000")
                                    {
                                        polInBD += ",codePregradiOneFIMespl";
                                        valInBD += ",@value12";
                                    }
                                    if (arrTable[13].Length > 0)
                                    {
                                        polInBD += ",livePregradiOneFIMespl";
                                        valInBD += ",@value13";
                                    }
                                    if (arrTable[14] != "000")
                                    {
                                        polInBD += ",codePregradiTwoFIMespl";
                                        valInBD += ",@value14";
                                    }
                                    if (arrTable[15].Length > 0)
                                    {
                                        polInBD += ",livePregradiTwoFIMespl";
                                        valInBD += ",@value15";
                                    }
                                    if (arrTable[16].Length > 0)
                                    {
                                        polInBD += ",liveSystemFIMespl";
                                        valInBD += ",@value16";
                                    }
                                    if (arrTable[17].Length > 0)
                                    {
                                        polInBD += ",prevLiveSystemFIMespl";
                                        valInBD += ",@value17";
                                    }
                                    if (arrTable[18] != "000")
                                    {
                                        polInBD += ",codeNameStFIMespl";
                                        valInBD += ",@value18";
                                    }
                                    if (arrTable[19].Length > 0)
                                    {
                                        polInBD += ",liveStFIMespl";
                                        valInBD += ",@value19";
                                    }
                                    if (arrTable[20].Length > 0)
                                    {
                                        polInBD += ",prevLiveStFIMespl";
                                        valInBD += ",@value20";
                                    }
                                    if (arrTable[21] != "000")
                                    {
                                        polInBD += ",codeNameStandFIMespl";
                                        valInBD += ",@value21";
                                    }
                                    if (arrTable[22].Length > 0)
                                    {
                                        polInBD += ",liveStandFIMespl";
                                        valInBD += ",@value22";
                                    }
                                    if (arrTable[23].Length > 0)
                                    {
                                        polInBD += ",prevLiveStandFIMespl";
                                        valInBD += ",@value23";
                                    }
                                    if (arrTable[24].Length > 0)
                                    {
                                        polInBD += ",koefAmorGilzFIMespl";
                                        valInBD += ",@value24";
                                    }
                                    if (arrTable[25].Length > 0)
                                    {
                                        polInBD += ",koefPrivedZarFIMespl";
                                        valInBD += ",@value25";
                                    }
                                    if (arrTable[26].Length > 0)
                                    {
                                        polInBD += ",koefPrivShotFIMespl";
                                        valInBD += ",@value26";
                                    }
                                    if (arrTable[27].Length > 0)
                                    {
                                        polInBD += ",uslCountZvFIMespl";
                                        valInBD += ",@value27";
                                    }
                                    if (arrTable[28].Length > 0)
                                    {
                                        polInBD += ",codeEdIzmFIMespl";
                                        valInBD += ",@value28";
                                    }
                                    MessageBox.Show(arrTable[28]);
                                    command = new SqlCommand("INSERT [FIMespl] (codeElementFIMespl,vidIspFIMespl" + polInBD + ") VALUES (@value1,@value2" + valInBD + ")", sqlConnection);
                                    command.Parameters.AddWithValue("value1", arrTable[1]);
                                    command.Parameters.AddWithValue("value2", arrTable[2]);
                                    command.Parameters.AddWithValue("value3", arrTable[3]);
                                    command.Parameters.AddWithValue("value4", arrTable[4]);
                                    command.Parameters.AddWithValue("value5", arrTable[5]);
                                    command.Parameters.AddWithValue("value6", arrTable[6]);
                                    command.Parameters.AddWithValue("value7", arrTable[7]);
                                    command.Parameters.AddWithValue("value8", arrTable[8]);
                                    command.Parameters.AddWithValue("value9", arrTable[9]);
                                    command.Parameters.AddWithValue("value10", arrTable[10]);
                                    command.Parameters.AddWithValue("value11", arrTable[11]);
                                    command.Parameters.AddWithValue("value12", arrTable[12]);
                                    command.Parameters.AddWithValue("value13", arrTable[13]);
                                    command.Parameters.AddWithValue("value14", arrTable[14]);
                                    command.Parameters.AddWithValue("value15", arrTable[15]);
                                    command.Parameters.AddWithValue("value16", arrTable[16]);
                                    command.Parameters.AddWithValue("value17", arrTable[17]);
                                    command.Parameters.AddWithValue("value18", arrTable[18]);
                                    command.Parameters.AddWithValue("value19", arrTable[19]);
                                    command.Parameters.AddWithValue("value20", arrTable[20]);
                                    command.Parameters.AddWithValue("value21", arrTable[21]);
                                    command.Parameters.AddWithValue("value22", arrTable[22]);
                                    command.Parameters.AddWithValue("value23", arrTable[23]);
                                    command.Parameters.AddWithValue("value24", arrTable[24]);
                                    command.Parameters.AddWithValue("value25", arrTable[25]);
                                    command.Parameters.AddWithValue("value26", arrTable[26]);
                                    command.Parameters.AddWithValue("value27", arrTable[27]);
                                    command.Parameters.AddWithValue("value28", arrTable[28]);
                                    sqlReader = command.ExecuteReader();
                                    break;*/
                                default:
                                    break;
                            }
                        }
                        if (reader != null)
                            reader.Close();
                    }
                    resultInformation += "Таблица " + nameTable + "     восстановлена!\n";
                }
                catch (Exception ex)
                {
                    MessageBox.Show(ex.Message.ToString(), ex.Source.ToString(), MessageBoxButtons.OK, MessageBoxIcon.Error);
                }
                finally
                {
                    if (reader != null)
                        reader.Close();
                }
            }
            else
                resultInformation += "Не найден файл с " + nameTable + "     таблицей!\n";
            return resultInformation;
        }

        private void Form22_KeyPress(object sender, KeyPressEventArgs e)
        {
            if (Convert.ToInt32(e.KeyChar) == 27)
            {
                usersList.Clear();
                if (reader != null)
                    reader.Close();
                if (connection != null && connection.State != ConnectionState.Closed)
                    connection.Close();
                this.Close();
            }
        }
    }
}
