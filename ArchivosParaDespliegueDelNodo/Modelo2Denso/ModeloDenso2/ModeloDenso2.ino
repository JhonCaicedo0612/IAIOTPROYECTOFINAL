#include <ArduinoJson.h>
#include <PubSubClient.h>
#include <WiFi.h>
#include <Adafruit_MPU6050.h>
#include <Adafruit_Sensor.h>
#include <ModeloDenso2IAOTFINAL_inferencing.h>
#define mqttUser ""
#define mqttPass ""
#define mqttPort 1883
#define Led 2
#define CONVERT_G_TO_MS2 1.0f
#define MAX_ACCEPTED_RANGE 25.0f
Adafruit_MPU6050 mpu;
int idnodo = 1;
const char* ssid = "ANDRES 2.4";
//ssid de la red inalambrica
const char* password = "1010151293";
//password para conectarse a la red
char mqttBroker[] = "broker.mqtt-dashboard.com";
//ip del servidor
char mqttClientId[] = "device1";
//cualquier nombre
char inTopic[] = "proyecto/PandorasActor1";
void callback(char* topic, byte* payload, unsigned int length) {
  String json = String((char*)payload);
  Serial.println();
  StaticJsonDocument<300> doc;
  DeserializationError error = deserializeJson(doc, json);
  if (error) { return; }
  int estado = doc["estado"];
  Serial.println(estado);
  if (estado == 1) {
    digitalWrite(Led, HIGH);
  } else {
    digitalWrite(Led, LOW);
  }
}
WiFiClient BClient;
PubSubClient client(BClient);
void reconnect() {
  // Loop until we're reconnected
  while (!client.connected()) {
    Serial.print("Attempting MQTT connection...");
    // Attempt to connect
    if (client.connect(mqttClientId, mqttUser, mqttPass)) {
      Serial.println("connected");
      client.subscribe("topico2");
    } else {
      Serial.print("failed, rc=");
      Serial.print(client.state());
      Serial.println(" try again in 5 seconds");
      // Wait 5 seconds before retrying
      delay(5000);
    }
  }
}

/* Private variables ------------------------------------------------------- */
static bool debug_nn = false;  // Set this to true to see e.g. features generated from the raw signal

/**
* @brief      Arduino setup function
*/

void setup() {
  pinMode(Led, OUTPUT);
  Serial.begin(115200);
  //Serial connection
  setup_wifi();  //WiFi connection
  client.setServer(mqttBroker, mqttPort);
  client.setCallback(callback);
  Serial.println("Setup done");
  delay(1500);
  Serial.println("Adafruit MPU6050 test!");  // Try to initialize!
  if (!mpu.begin()) {
    Serial.println("Failed to find MPU6050 chip");
    while (1) { delay(10); }
  }
  Serial.println("MPU6050 Found!");
  mpu.setAccelerometerRange(MPU6050_RANGE_8_G);
  Serial.print("Accelerometer range set to: ");
  switch (mpu.getAccelerometerRange()) {
    case MPU6050_RANGE_2_G:
      Serial.println("+-2G");
      break;
    case MPU6050_RANGE_4_G: Serial.println("+-4G"); break;
    case MPU6050_RANGE_8_G: Serial.println("+-8G"); break;
    case MPU6050_RANGE_16_G: Serial.println("+-16G"); break;
  }
  mpu.setGyroRange(MPU6050_RANGE_500_DEG);
  Serial.print("Gyro range set to: ");
  switch (mpu.getGyroRange()) {
    case MPU6050_RANGE_250_DEG: Serial.println("+- 250 deg/s"); break;
    case MPU6050_RANGE_500_DEG: Serial.println("+- 500 deg/s"); break;
    case MPU6050_RANGE_1000_DEG: Serial.println("+- 1000 deg/s"); break;
    case MPU6050_RANGE_2000_DEG: Serial.println("+- 2000 deg/s"); break;
  }
  mpu.setFilterBandwidth(MPU6050_BAND_21_HZ);
  Serial.print("Filter bandwidth set to: ");
  switch (mpu.getFilterBandwidth()) {
    case MPU6050_BAND_260_HZ: Serial.println("260 Hz"); break;
    case MPU6050_BAND_184_HZ: Serial.println("184 Hz"); break;
    case MPU6050_BAND_94_HZ: Serial.println("94 Hz"); break;
    case MPU6050_BAND_44_HZ: Serial.println("44 Hz"); break;
    case MPU6050_BAND_21_HZ: Serial.println("21 Hz"); break;
    case MPU6050_BAND_10_HZ:
      Serial.println("10 Hz");
      break;
    case MPU6050_BAND_5_HZ: Serial.println("5 Hz"); break;
  }
  Serial.println("");
  delay(100);
}

/**
 * @brief Return the sign of the number
 * 
 * @param number 
 * @return int 1 if positive (or 0) -1 if negative
 */

float ei_get_sign(float number) {
  return (number >= 0.0) ? 1.0 : -1.0;
}

/**
* @brief      Get data and run inferencing
*
* @param[in]  debug  Get debug info if true
*/


void setup_wifi() {
  delay(10);  // We start by connecting to a WiFi network
  Serial.println();
  Serial.print("Connecting to ");
  Serial.println(ssid);
  WiFi.begin(ssid, password);
  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }
  Serial.println("");
  Serial.println("WiFi connected");
  Serial.println("IP address: ");
  Serial.println(WiFi.localIP());
}
void loop() {
  if (!client.connected()) { reconnect(); }
  client.loop();  //aquí va el código asociado al sensor y generación del json

  float buffer[EI_CLASSIFIER_DSP_INPUT_FRAME_SIZE] = { 0 };

  for (size_t ix = 0; ix < EI_CLASSIFIER_DSP_INPUT_FRAME_SIZE; ix += 6) {
    // Determine the next tick (and then sleep later)
    uint64_t next_tick = micros() + (EI_CLASSIFIER_INTERVAL_MS * 1000);

    //        IMU.readAcceleration(buffer[ix], buffer[ix + 1], buffer[ix + 2]);

    sensors_event_t a, g, temp;
    mpu.getEvent(&a, &g, &temp);

    buffer[ix] = a.acceleration.x;
    buffer[ix + 1] = a.acceleration.y;
    buffer[ix + 2] = a.acceleration.z;

    buffer[ix + 3] = g.gyro.x;
    buffer[ix + 4] = g.gyro.y;
    buffer[ix + 5] = g.gyro.z;




    for (int i = 0; i < 6; i++) {
      if (fabs(buffer[ix + i]) > MAX_ACCEPTED_RANGE) {
        buffer[ix + i] = ei_get_sign(buffer[ix + i]) * MAX_ACCEPTED_RANGE;
      }
    }

    buffer[ix + 0] *= CONVERT_G_TO_MS2;
    buffer[ix + 1] *= CONVERT_G_TO_MS2;
    buffer[ix + 2] *= CONVERT_G_TO_MS2;
    buffer[ix + 3] *= CONVERT_G_TO_MS2;
    buffer[ix + 4] *= CONVERT_G_TO_MS2;
    buffer[ix + 5] *= CONVERT_G_TO_MS2;

    delayMicroseconds(next_tick - micros());
  }

  // Turn the raw buffer in a signal which we can the classify
  signal_t signal;
  int err = numpy::signal_from_buffer(buffer, EI_CLASSIFIER_DSP_INPUT_FRAME_SIZE, &signal);
  if (err != 0) {
    ei_printf("Failed to create signal from buffer (%d)\n", err);
    return;
  }

  // Run the classifier
  ei_impulse_result_t result = { 0 };

  err = run_classifier(&signal, &result, debug_nn);
  if (err != EI_IMPULSE_OK) {
    ei_printf("ERR: Failed to run classifier (%d)\n", err);
    return;
  }

  // print the predictions
  ei_printf("Predictions ");
  ei_printf("(DSP: %d ms., Classification: %d ms., Anomaly: %d ms.)",
            result.timing.dsp, result.timing.classification, result.timing.anomaly);
  ei_printf(": \n");
  size_t max_index = 0;
  float max_value = result.classification[0].value;

  // Encuentra el índice con el valor máximo
  for (size_t ix = 1; ix < EI_CLASSIFIER_LABEL_COUNT; ix++) {
    if (result.classification[ix].value > max_value) {
      max_index = ix;
      max_value = result.classification[ix].value;
    }
  }
  sensors_event_t a, g, temp;
  mpu.getEvent(&a, &g, &temp);
  String json;
  StaticJsonDocument<300> doc;
  doc["idnodo"] = idnodo;
  doc["accx"] = a.acceleration.x;
  doc["accy"] = a.acceleration.y;
  doc["accz"] = a.acceleration.z;
  doc["rotx"] = g.gyro.x;
  doc["roty"] = g.gyro.y;
  doc["rotz"] = g.gyro.z;
  doc["pred"] = result.classification[max_index].label;
  serializeJson(doc, json);
  Serial.println(json);
  int lon = json.length() + 1;
  char datojson[lon];
  json.toCharArray(datojson, lon);
  client.publish(inTopic, datojson);
  delay(5000);

  #if EI_CLASSIFIER_HAS_ANOMALY == 1
  ei_printf("    anomaly score: %.3f\n", result.anomaly);
#endif

}

#if !defined(EI_CLASSIFIER_SENSOR) || EI_CLASSIFIER_SENSOR != EI_CLASSIFIER_SENSOR_FUSION
#error "Invalid model for current sensor"
#endif