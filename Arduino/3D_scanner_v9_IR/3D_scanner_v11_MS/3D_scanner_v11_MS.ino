// Программа для определния дистанции до объектаи передачи полученной информации
// по последовательному порту.

//Версия с управлением от L298N
// 1 кннопка, 1 реле, 1 потенциометр для выбора направления вращения.

// Используемые библиотеки.
#include <Servo.h>
#include <avr/wdt.h>
#include <Button.h>
#include <MsTimer2.h>
#include <Led4Digits.h>
#include "GyverTM1637.h"

// Назначения выводов.
#define CONST_BUTTON_PIN  2
#define CONST_TRIG_PIN    11
#define CONST_ECHO_PIN    12
#define CONST_SERVO_PIN   3
#define CONST_DIR_MOTOR_PIN     4
#define CONST_SPEED_MOTOR_PIN   5
#define LED_PIN           10
#define CONST_RELAY_PIN   8
#define CONST_POT_PIN     A0
#define CONST_IR_PIN      A2
#define CLK 6
#define DIO 7
#define CONST_AtD 0.0048828125      // Шаг АЦП.

// Параметры
#define SET_MODE_TIME 200 // Вермя удержания кнопки для перехода на режим.
#define SLOW_TIME_FLASH_LED 200
#define VERY_SLOW_TIME_FLASH_LED 2000
//
Button button(CONST_BUTTON_PIN, 10);  // Создание объекта кнопки.
Servo rServo;                         // Создание объекта сервопривода.
GyverTM1637 disp(CLK, DIO);

byte mode;                    // Режим.
const byte averageFactor = 5; // коэффициент сглаживания показаний.

unsigned int commonTimer;     // таймер для разных целей.
unsigned int commonTimerInd;
unsigned int commonTimerButton;
unsigned int serialCount;     // счетчик времени передачи отладочных данных через UART
unsigned int ledFlashCount;   // счетчик мигания светодиода

bool ledState;
bool flagButton;              // Признак нажтия кнопки.
bool sensorMode = false;      // false - IR, true - UF;

float sonarVal, distance;     // переменные для значений УЗ дальномера и текущей дистанции.
float irValue, irDist;        // переменная для величины напряжения с IR дальномера и дистанции.
float corInValue;

const float timeEcho = 29.2;  // коэффициент времени скорости звука.

unsigned long timeMl;         // переменная хранения знчения таймера в милисекундах.
unsigned long timeMc;         // переменная хранения значений таймера в микросекундах.

int timeOne = 50;             // время формирования отсчетов по дистанции.
int timeTwo = 100;            // время формирования отсчетов для высоты.
int potVal;                   // Значения потенциометра.
int dataheight = 1;           // переменная для определения высоты датчика.
int maxDataHeight = 6;       // переменная для определения максимальной высоты фигуры.
int dotSize = 20;

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

  pinMode(CONST_TRIG_PIN, OUTPUT);
  pinMode(CONST_ECHO_PIN, INPUT);
  pinMode(CONST_BUTTON_PIN, INPUT);
  pinMode(LED_PIN, OUTPUT);
  pinMode(CONST_DIR_MOTOR_PIN, OUTPUT);
  pinMode(CONST_SPEED_MOTOR_PIN, OUTPUT);
  pinMode(CONST_RELAY_PIN, OUTPUT);
  pinMode(CONST_POT_PIN, INPUT);
  pinMode(CONST_IR_PIN, INPUT);

  rServo.attach(CONST_SERVO_PIN);

  disp.clear();
  disp.brightness(7);  // яркость, 0 - 7 (минимум - максимум)
  disp.point(true);

  MsTimer2::set(2, timerInterrupt); // период прерывания по таймеру 2 мс
  MsTimer2::start();                // разрешаем прерывание по таймеру
  wdt_enable(WDTO_15MS);            // разрешаем сторожевой таймер, тайм-аутом 15 мс
  flagButton = false;

}

void loop()
{

  if ( mode == 0 )
  {
    // -- Исходное состояние.  -- //

    MotorStop();
    ServoStop();
    ledState = false;


    // переход на режим передачи по точкам (коротокое нажатие кнопки)

    potVal = analogRead(CONST_POT_PIN);
    timeOne = map(potVal, 0, 1023, 1, 100);
    if (timeOne < 2)  MotorMotionDown();

    else if (timeOne > 60 && timeOne < 70)
    {
      IRDist();
      disp.point(true);
      disp.displayByte(0, _r);
      disp.display(1, 1);
      disp.displayByte(2, _empty);
      disp.displayByte(3, _empty);
      sensorMode = false;
    }

    else if (timeOne > 70 && timeOne < 80)
    {
      Sonar();
      disp.point(true);
      disp.displayByte(0, _r);
      disp.display(1, 2);
      disp.displayByte(2, _empty);
      disp.displayByte(3, _empty);
      sensorMode = true;
    }
    else if (timeOne > 98)   MotorMotionUp();

    else
    {
      MotorStop();
      disp.point(false);
      disp.displayByte(_i, _n, _i, _t);
    }

    if (button.flagPress == false && digitalRead(CONST_BUTTON_PIN)) flagButton = true;

    if ((flagButton == true) && (button.flagPress == true) && digitalRead(CONST_BUTTON_PIN))
    {
      // Переход на режим 1.
      commonTimer = 0;
      button.flagClick = false;
      mode = 1;
      count2 = 0;
      Serial.println("b"); // Инициализация начала события.
    }

    // переход на режим передчи по слоям (длительное удержание кнопоки)
    // если кнопка не нажата, то обнуляем commonTimer
    // как только commonTimer насчитывает 1 сек, то переходим на режим 2
    if (button.flagPress == true) commonTimer = 0;

    if (commonTimer > 1000)
    {
      // Переход на режим 3.
      commonTimer = 0;
      button.flagClick = false;
      mode = 3;
      count2 = 0;
      Serial.println("b"); // Инициализация начала события.
      //  Serial.println("3"); // Инициализация начала события.


    }
    else if (commonTimer > 3000)
    {
      // Переход на режим 2
      commonTimer = 0;
      button.flagClick = false;
      mode = 2;
      count2 = 0;
      Serial.println("b"); // Инициализация начала события.
      Serial.println("2"); // Инициализация начала события.
    }
  }

  else if ( mode == 1 ) {
    // -- РЕЖИМ 1: ПО ТОЧКАМ  -- //
    // -- Режим передачи информции по точкам.  -- //

    // светодиод мигает 1 раз в сек

    LedBlink(SLOW_TIME_FLASH_LED);

    ledState = ! ledState; // инверсия условия для формирования данных

    // Условие считывания значений по дистнции.

    if (ledState == true && count2 < maxDataHeight)
    {
      count++;
      if (sensorMode) Sonar();                            // Запуск и считывание значений с дальномера.
      else IRDist();                                      // Запуск и считывание значений с иф датчика.

      ServoMotion();
      MotorStop();
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
    if (count > 200 - 1)
    {
      count = 0;
      count2++;
      ServoStop();
      MotorMotionUp();

    }

    // Формирование строки окончания передачи данных.
    EndMode(count2, ledState);
  }
  // -- РЕЖИМ 2: ПО СЛОЯМ  -- //

  else if ( mode == 2 ) {
    // -- Режим передачи информции по слоям.  -- \\

    LedBlink(VERY_SLOW_TIME_FLASH_LED);


    // Условие формирование значений по слоям.

    ledState = ! ledState;               // инверсия условия для формирования данных

    if (ledState == true && count2 < maxDataHeight)
    {
      count++;
      if (sensorMode) Sonar();                            // Запуск и считывание значений с дальномера.
      else IRDist();                                      // Запуск и считывание значений с иф датчика.
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

        strDataDist = "";

        EndMode(count2, ledState);

      }
    }
  }

  // -- РЕЖИМ 3: ПО ШАГАМ  -- //

  else if ( mode == 3 ) {
    // -- Режим передачи информции по шагам.  -- \\

    LedBlink(VERY_SLOW_TIME_FLASH_LED);

    ledState = ! ledState; // инверсия условия для формирования данных

    // Условие считывания значений по дистнции.

    if (ledState == true && count2 < maxDataHeight)
    {
        if (sensorMode) Sonar();                            // Запуск и считывание значений с дальномера.
      else IRDist();                                      // Запуск и считывание значений с иф датчика.
      
      strDataDist = String(distance);
      strDataDist.replace('.', ',');



      // Формирование строки для высоты.
      if (count2 < maxDataHeight && count ==  0)
      {
        count++;
        strDataCount2 = String(count2);
        strDataHeight = strDataCount2;
        Serial.println(heightDot + strDataHeight + "r");
      }

      if (button.flagPress == false && digitalRead(CONST_BUTTON_PIN))
      {
        if ( commonTimerButton > 500 )
        {
          count++;
          commonTimerButton = 0;
          strDataDist = String(distance);
          Serial.println(distDot + strDataDist + "c");
        }
      }
    }
    //// Обнуление счетчика и индикация.
    if (count > dotSize)
    {
      count = 0;
      count2++;
    }

    // Формирование строки окончания передачи данных.
    EndMode(count2, ledState);
  }

  else mode = 0;

  //   Передача отладочных данных через UART
  //   каждые 250 мс
  // SerialData();

}

void LedBlink(int blinkTime)
{
  if ( ledFlashCount > blinkTime )
  {
    ledFlashCount = 0;
    digitalWrite(LED_PIN, !digitalRead(LED_PIN));  // инверсия светодиода
  }
}


void SerialData()
{
  if ( serialCount >= 250 && mode == 0)
  {
    // Режим
    Serial.print("Mode= "); Serial.print(mode); Serial.print(" Timer= "); Serial.print(commonTimer);
    // Значение дистанции.
    Serial.print(" irDist= "); Serial.print(irDist);

    if (digitalRead(LED_PIN)) Serial.println(" LED: ON");
    else  Serial.println(" LED:OFF");

    Serial.print(" Pot: "); Serial.print(timeOne);

    // Состояние кнопок.
    if ( button.flagPress == false ) Serial.print(" Btn ___ ");
    else Serial.print(" Btn _-_ ");
  }

}

void EndMode(int count2, bool ledState)
{
  if (count2 >= maxDataHeight && ledState == true)
  {
    count2 = 0;
    ledState = ! ledState;
    strDataDist = "";
    commonTimer = 0;
    button.flagClick = false;
    flagButton = false;
    timeTwo = millis();
    digitalWrite(LED_PIN, LOW);

    Serial.println("e");
    mode = 0;

  }
}

void Sonar()
{
  if (commonTimerInd > 100)
  {
    commonTimerInd = 0;
    digitalWrite(CONST_TRIG_PIN, LOW);      // Устанавливаем вывод передатчика в 0 для контроля точности.
    digitalWrite(13, LOW);
    delayMicroseconds(2);                   // Ждем 2 микросекунды, для исключения помех.
    digitalWrite(CONST_TRIG_PIN, HIGH);     // Устанавливаем вывод в 1 и ждём 10 мкс.
    digitalWrite(13, HIGH);
    delayMicroseconds(10);
    digitalWrite(CONST_TRIG_PIN, LOW);      // Устанавливаем вывод передатчика в 0.
    digitalWrite(13, LOW);

    //  if (averageFactor > 0) // Усреднение показаний.
    //  {
    //  sonarVal = pulseIn(CONST_ECHO_PIN, HIGH);
    //  int oldSonarVal = sonarVal;
    //  sonarVal = (oldSonarVal *(averageFactor -1) + sonarVal) / averageFactor;
    //  // Новое среднее = старое среднее * уменьшенный на 1 коэффициент + текущее значение / коэффициент.
    //  }
    //  // Если появляется отицательное значнеие - это шум.
    //  // Не учитывать при построении.

    corInValue = pulseIn(CONST_ECHO_PIN, HIGH); // Рассчитывм расстояние. pulseIn - функция определения
    //  длительности импульсов в мкс.
    if (corInValue > 1500)
    {
      corInValue = sonarVal;
    }
    else
    {
      sonarVal = corInValue;
      distance = sonarVal / (timeEcho * 2);     // Учёт преодоления дистанции до объекта и обратно.
      //   Serial.println(distance);
    }
  }

  disp.displayInt(round(distance));
}    // Формирование массива значений относительно времени измерений.


void IRDist()
{
  if (commonTimerInd > 100)
  {
    commonTimerInd = 0;
    corInValue = analogRead(CONST_IR_PIN) * CONST_AtD;    //

    if (corInValue < 0.6)
    {
      corInValue = irValue;
    }
    else
    {
      irValue = corInValue;
      distance = 65 * pow(irValue, -1.10);
      //    Serial.println(distance);
    }
  }
  disp.displayInt(round(distance));
}

void ServoMotion()
{
  rServo.write(95);    // Для сервопривода 360град, - полный ход в одном направлении.
  // rServo.write(88);  // Для сервопривода 360град, - полный ход в обратном направлении.
  digitalWrite(CONST_RELAY_PIN, HIGH);
}

void MotorMotionDown()
{
  if (commonTimerInd > 1000)
  {
  commonTimerInd = 0;
  byte down[4] = {_d, _n};
  disp.displayByte(down);
  digitalWrite(CONST_DIR_MOTOR_PIN , HIGH);
  analogWrite(CONST_SPEED_MOTOR_PIN, 0);
  }
}

void MotorMotionUp()
{
  if (commonTimerInd > 1000)
  {
  commonTimerInd = 0;
  byte up[4] = {_U, _P};
  disp.displayByte(up);
  digitalWrite(CONST_DIR_MOTOR_PIN, LOW);
  analogWrite(CONST_SPEED_MOTOR_PIN, 255);
  }
}

void MotorStop()
{
  digitalWrite(CONST_DIR_MOTOR_PIN, LOW);
  analogWrite(CONST_SPEED_MOTOR_PIN, 0);
}

void ServoStop()
{
  rServo.write(91);   // Для сервопривода 360град, - остановка сервопривода.
  digitalWrite(CONST_RELAY_PIN, LOW);
}

void  timerInterrupt()
{
  wdt_reset();  // сброс сторожевого таймера
  button.filterAvarage();  // вызов метода фильтрации сигнала кнопки
  commonTimer++;  // таймер
  commonTimerInd++;
  commonTimerButton++;
  serialCount++;  // счетчик времени передачи отладочных данных через UART
  ledFlashCount++; // счетчик мигания светодиода
}
