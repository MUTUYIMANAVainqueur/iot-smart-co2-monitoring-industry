#include <WiFi.h>
#include <HTTPClient.h>
#include <DHT.h>

#define DHTPIN 4
#define DHTTYPE DHT11
#define MQ135_PIN 34
#define BUZZER_PIN 5
#define LED_PIN 2

DHT dht(DHTPIN, DHTTYPE);

const char* ssid = "YOUR_WIFI_NAME";
const char* password = "YOUR_WIFI_PASSWORD";
const char* serverURL = "http://your-server.com/insert.php";
const int CO2_THRESHOLD = 1000;

void setup() {
  Serial.begin(115200);
  pinMode(BUZZER_PIN, OUTPUT);
  pinMode(LED_PIN, OUTPUT);
  dht.begin();
  WiFi.begin(ssid, password);
  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }
  Serial.println("WiFi Connected");
}

void loop() {
  int sensorValue = analogRead(MQ135_PIN);
  float co2 = map(sensorValue, 0, 4095, 400, 5000);
  float temp = dht.readTemperature();
  float hum = dht.readHumidity();

  Serial.print("CO2: "); Serial.print(co2);
  Serial.print(" Temp: "); Serial.print(temp);
  Serial.print(" Hum: "); Serial.println(hum);

  if (co2 > CO2_THRESHOLD) {
    digitalWrite(BUZZER_PIN, HIGH);
    digitalWrite(LED_PIN, HIGH);
  } else {
    digitalWrite(BUZZER_PIN, LOW);
    digitalWrite(LED_PIN, LOW);
  }

  if (WiFi.status() == WL_CONNECTED) {
    HTTPClient http;
    http.begin(serverURL);
    http.addHeader("Content-Type", "application/x-www-form-urlencoded");
    String data = "co2=" + String(co2) + "&temp=" + String(temp) + "&hum=" + String(hum);
    http.POST(data);
    http.end();
  }
  delay(5000);
}
