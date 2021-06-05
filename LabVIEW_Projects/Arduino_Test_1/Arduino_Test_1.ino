#define PIN_LED_1 13
#define PIN_LED_2 8
#define PIN_LED_3 7
#define PIN_IN_A A0

int cmd = 0;
bool isNeedRead = false;

void setup() {
  pinMode(PIN_LED_1, OUTPUT);
  pinMode(PIN_LED_2, OUTPUT);
  pinMode(PIN_LED_3, OUTPUT);
  pinMode(PIN_IN_A, INPUT);
  Serial.begin(9600);
}

void loop() {
  if (Serial.available() > 0){
    cmd = Serial.read();
    // 0
    if (cmd == 48){
      digitalWrite(PIN_LED_1, LOW);
      digitalWrite(PIN_LED_2, LOW);
      digitalWrite(PIN_LED_3, LOW);
    }
    // 1
    if (cmd == 49){
      digitalWrite(PIN_LED_1, HIGH);
    }
    // 2
    if (cmd == 50){
      digitalWrite(PIN_LED_2, HIGH);
    }
    // 3
    if (cmd == 51){
      digitalWrite(PIN_LED_3, HIGH);
    }
    
    // 7
    if (cmd == 55){
      isNeedRead = true;
    }
    // 8
    if (cmd == 56){
      isNeedRead = false;
    }
  }

  if (isNeedRead == true)
  {
    Serial.print(analogRead(PIN_IN_A));
    Serial.println();
    delay(50);
  }
}
