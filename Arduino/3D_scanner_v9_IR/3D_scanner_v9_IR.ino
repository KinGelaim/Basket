// Программа для определния дистанции до объекта и передачи полученной информации
// по последовательному порту.

// Используемые библиотеки.
#include <Servo.h>
#include <avr/wdt.h>
#include <Button.h>
#include <MsTimer2.h>
#include <Led4Digits.h>
#include "GyverTM1637.h"

// Назначения выводов.
#define CONST_BUTTON_PIN  8
#define CONST_TRIG_PIN    12
#define CONST_ECHO_PIN    13
#define CONST_SERVO_PIN   3
#define LED_PIN           11
#define CONST_POT_PIN     A0
#define CONST_IR_PIN      A1
#define CLK 2
#define DIO 3
#define CONST_AtD 0.0048828125      // Шаг АЦП.

// Параметры
#define SET_MODE_TIME 300        // Вермя удержания кнопки для перехода на режим.
#define SLOW_TIME_FLASH_LED 200  // Вермя мигания светодиода в режиме по точкам.
//
Button button(CONST_BUTTON_PIN, 10);  // Создание объекта кнопки.
Servo rServo;                         // Создание объекта сервопривода.
GyverTM1637 disp(CLK, DIO);           // Создание объекта дисплея.

byte mode;                    // Режим.
const byte averageFactor = 5; // коэффициент сглаживания показаний.

unsigned int commonTimer;     // таймер для разных целей.
unsigned int serialCount;     // счетчик времени передачи отладочных данных через UART.
unsigned int ledFlashCount;   // счетчик мигания светодиода.

bool ledState;
bool flagButton;              // Признак нажтия кнопки.

float sonarVal, distance;     // переменные для значений УЗ дальномера и текущей дистанции.
float irValue, irDist;        // переменная для величины напряжения с IR дальномера и дистанции.

const float timeEcho = 29.2;  // коэффициент времени скорости звука.

unsigned long timeMl;         // переменная хранения знчения таймера в милисекундах.
unsigned long timeMc;         // переменная хранения значений таймера в микросекундах.

int timeOne = 50;             // время формирования отсчетов по дистанции.
int timeTwo = 100;            // время формирования отсчетов для высоты.
int potVal;                   // Значения потенциометра.
int dataheight = 1;           // переменная для определения высоты датчика.
int maxDataHeight = 20;       // переменная для определения максимальной высоты датчика.

unsigned int endCount = timeOne;       // параметр обнуления счетчика.
unsigned int count = 0;                // счетчик.
unsigned int count2 = 1;               // счетчик 2.

String strOutData, strOutData2, strDataHeight, strDataDist, strDataCount, strDataCount2;
String delimiter = " ";                // Разделитель.
String heightLayer = "{\"l\":";        // переменная для построения по слоям.
String distLayer = "\"d\":\"";         // переменная для построения по слоям.
String heightDot = "l";                // переменная для построения по точкам.
String distDot = "d";                  // переменная для построения по точкам.




void setup()
{
  Serial.begin(9600);

  pinMode(CONST_TRIG_PIN, OUTPUT);  // Устанавливаем TRIG вывод как выход.
  pinMode(CONST_ECHO_PIN, INPUT);   // Устанавливаем ECHO вывод как вход.
  pinMode(CONST_BUTTON_PIN, INPUT);   // Устанавливаем вывод кнопки как вход.
  pinMode(LED_PIN, OUTPUT);
  pinMode(CONST_POT_PIN, INPUT);
  pinMode(CONST_IR_PIN, INPUT);
  rServo.attach(CONST_SERVO_PIN);
  disp.clear();
  disp.brightness(7);  // яркость, 0 - 7 (минимум - максимум)

  MsTimer2::set(2, timerInterrupt); // период прерывания по таймеру 2 мс
  MsTimer2::start();                // разрешаем прерывание по таймеру
  wdt_enable(WDTO_15MS); // разрешаем сторожевой таймер, тайм-аутом 15 мс
  flagButton = false;
}

void loop()
{
  //Serial.println(flagButton);
  if ( mode == 0 )
  {
    // -- Исходное состояние.  -- //
    IRDist();

    // переход на режим передачи по точкам (коротокое нажатие кнопки)

    potVal = analogRead(CONST_POT_PIN);
    timeOne = map(potVal, 0, 1023, 1, 100);

    if (millis() - timeTwo > 100)
    {
      timeTwo = millis();
      disp.displayInt(timeOne);

    }
    if (button.flagPress == false && digitalRead(CONST_BUTTON_PIN)) flagButton = true;

    if ((flagButton == true) && (button.flagPress == true) && digitalRead(CONST_BUTTON_PIN))
    {
      // Переход на режим 1.
      commonTimer = 0;
      button.flagClick = false;
      mode = 1;

      Serial.println("b"); // Инициализация начала события.

    }

    // переход на режим передчи по слоям (длительное удержание кнопоки)
    // если кнопка не нажата, то обнуляем commonTimer
    // как только commonTimer насчитывает 1 сек, то переходим на режим 2
    if (button.flagPress == true) commonTimer = 0;
    if (commonTimer > SET_MODE_TIME)
    {
      // Переход на режим 2
      commonTimer = 0;
      button.flagClick = false;
      mode = 2;
      Serial.println("b"); // Инициализация начала события.
    }
  }

  else if ( mode == 1 ) {
    // -- РЕЖИМ 1: ПО ТОЧКАМ  -- //


    // -- Режим передачи информции по точкам.  -- //
    // светодиод мигает 1 раз в сек
    if ( ledFlashCount > SLOW_TIME_FLASH_LED )
    {
      ledFlashCount = 0;
      digitalWrite(LED_PIN, !digitalRead(LED_PIN));  // инверсия светодиода
    }

    ledState = ! ledState;               // инверсия состояния светодиода

    // Условие считывания значений по дистнции.

    if (ledState == true && count2 < maxDataHeight)
    {
      count++;
      //      Sonar();                            // Запуск и считывание значений с дальномера.
      IRDist();                           // Запуск и считывание значений с ультразвуковг

      //  Формирование массива значений по дистанции.

      strDataDist = String(distance);
      strDataDist.replace('.', ',');
      // Формирование строки для высоты.
      if (count2 < maxDataHeight && count ==  1)
      {
        strDataCount2 = String(count2);
        strDataHeight = strDataCount2;
        Serial.println(heightDot + strDataHeight + "r");
      }

      Serial.println(distDot + strDataDist + "c");
      strDataCount = String(count);
    }

    //// Обнуление счетчика и индикация.
    if (count > timeOne - 1)
    {
      count = 0;
      count2++;
    }

    // Формирование строки окончания передачи данных.
    if (count2 >= maxDataHeight && ledState == true)
    {
      count2 = 0;
      ledState = !ledState;

      // переход на режим 0 (Исходное состояние)
      commonTimer = 0;
      strDataDist = "";
      button.flagClick = false;
      flagButton = false;
      mode = 0;
      if (digitalRead(LED_PIN) == HIGH)  digitalWrite(LED_PIN, LOW);
      timeTwo = millis();

      Serial.println("e");
    }
  }

  // -- РЕЖИМ 2: ПО СЛОЯМ  -- //

  else if ( mode == 2 ) {
    // -- Режим передачи информции по слоям.  -- \\

    if ( ledFlashCount >= 0 ) {
      ledFlashCount = 0;
      digitalWrite(LED_PIN, HIGH);
    }

    // Условие формирование значений по слоям.

    ledState = ! ledState;               // инверсия состояния светодиода

    if (ledState == true && count2 < maxDataHeight)
    {
      count++;
      //      Sonar();                            // Запуск и считывание знчений с дальномера.
      IRDist();                           // Запуск и считывание значений с ультразвуковг
      //Формирование массива значений по дистанции.

      strDataDist += String(distance);
      if (count < endCount) strDataDist += ";";
      strDataDist.replace('.', ',');


      //// Обнуление счетчика и индикация.
      if (count > timeOne - 1)
      {
        count = 0;
        count2++;
        strDataCount2 = String(count2);
        strDataHeight = strDataCount2;
        strOutData = "f" + heightLayer + strDataHeight + "," + distLayer + strDataDist + "\"}" + "r";
        delay(100);

        Serial.println(strOutData);





        //         Serial.println("c1: " + strDataCount + " c2: " + strDataCount2 + " l: " + strDataDist);

        //        Serial.println("[" + strOutData + "]");
        strDataDist = "";

        if (count2 >= maxDataHeight && ledState == true)
        {
          count2 = 0;
          ledState = ! ledState;
          //          Serial.println("[" + strOutData2 + "]");
          // переход на режим 0 (Исходное состояние)
          strDataDist = "";
          commonTimer = 0;
          button.flagClick = false;
          flagButton = false;
          mode = 0;
          timeTwo = millis();

          Serial.println("e");
          if (digitalRead(LED_PIN) == HIGH)  digitalWrite(LED_PIN, LOW);
        }
      }
    }
  }

  else mode = 0;

  ////   Передача отладочных данных через UART
  ////   каждые 500 мс
  //
  //  if ( serialCount >= 250 && mode == 0)
  //  {
  //    // Режим
  //    Serial.print("Mode= "); Serial.print(mode); Serial.print(" Timer= "); Serial.print(commonTimer);
  //      // Значение дистанции.
  //    Serial.print(" irDist= "); Serial.print(irDist);
  //
  //    if (digitalRead(LED_PIN)) Serial.println(" LED: ON");
  //    else  Serial.println(" LED:OFF");
  //
  //    // Состояние кнопок.
  //    if ( button.flagPress == false ) Serial.print(" Btn -_- ");
  //    else Serial.print(" Btn _-_ ");
  //  }

}


//void Sonar()            // Формирование данных для ультразвукового дальномера.
//{
//  digitalWrite(CONST_TRIG_PIN, LOW);      // Устанавливаем вывод передатчика в 0 для контроля точности.
//  digitalWrite(13, LOW);
//  delayMicroseconds(2);                   // Ждем 2 микросекунды, для исключения помех.
//  digitalWrite(CONST_TRIG_PIN, HIGH);     // Устанавливаем вывод в 1 и ждём 10 мкс.
//  digitalWrite(13, HIGH);
//  delayMicroseconds(10);
//  digitalWrite(CONST_TRIG_PIN, LOW);      // Устанавливаем вывод передатчика в 0.
//  digitalWrite(13, LOW);
//
//  //  if (averageFactor > 0) // Усреднение показаний.
//  //  {
//  //  sonarVal = pulseIn(CONST_ECHO_PIN, HIGH);
//  //  int oldSonarVal = sonarVal;
//  //  sonarVal = (oldSonarVal *(averageFactor -1) + sonarVal) / averageFactor;
//  //  // Новое среднее = старое среднее * уменьшенный на 1 коэффициент + текущее значение / коэффициент.
//  //  }
//  //  // Если появляется отицательное значнеие - это шум.
//  //  // Не учитывать при построении.
//
//  sonarVal = pulseIn(CONST_ECHO_PIN, HIGH); // Рассчитывм расстояние. pulseIn - функция определения
//  //  длительности импульсов в мкс.
//  distance = sonarVal / (timeEcho * 2);     // Учёт преодоления дистанции до объекта и обратно.
//
//  // Формирование массива значений относительно времени измерений.
//}

void IRDist()         // Формирование данных для инфракрасного дальномера.
{
  irValue = analogRead(CONST_IR_PIN) * CONST_AtD;    //
  distance = 65 * pow(irValue, -1.10);
}

void ServoMotion()
{
  rServo.write(0);    // Для сервопривода 360град, - полный ход в одном направлении.
  // rServo.write(180);  // Для сервопривода 360град, - полный ход в обратном направлении.
}

void ServoStop()
{
  rServo.write(90);   // Для сервопривода 360град, - остановка сервопривода.
}

void  timerInterrupt()
{
  wdt_reset();  // сброс сторожевого таймера
  button.filterAvarage();  // вызов метода фильтрации сигнала кнопки
  commonTimer++;  // таймер
  serialCount++;  // счетчик времени передачи отладочных данных через UART
  ledFlashCount++; // счетчик мигания светодиода
}
