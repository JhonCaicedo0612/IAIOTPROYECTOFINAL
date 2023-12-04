from flask import Flask, jsonify, request
from flask_mysqldb import MySQL
from flask_mqtt import Mqtt
from dotenv import load_dotenv

import os
import json

app = Flask(__name__)

load_dotenv()
app.config['MYSQL_HOST'] = os.environ["MYSQL_HOST"]
app.config['MYSQL_USER'] = os.environ["MYSQL_USER"]
app.config['MYSQL_PASSWORD'] = os.environ["MYSQL_PASSWORD"]
app.config['MYSQL_DB'] = os.environ["MYSQL_DB"]
app.config['MQTT_BROKER_URL'] = os.environ["mqtt_broker"]
app.config['MQTT_BROKER_PORT'] = int(os.environ["mqtt_port"])
app.config['MQTT_USERNAME'] = os.environ["MQTT_USERNAME"]
app.config['MQTT_PASSWORD'] = os.environ["MQTT_PASSWORD"]
mqtt_topic = os.environ["mqtt_topic"]

conexion=MySQL(app)
mqtt = Mqtt(app)

@mqtt.on_connect()
def handle_connect(client, userdata, flags, rc):
    mqtt.subscribe(mqtt_topic)

@mqtt.on_message()
def on_message(client, userdata, msg):
    pylodad = msg.payload.decode("utf-8")
    data = json.loads(pylodad)

    idnodo = data["idnodo"]
    accx = data["accx"]
    accy = data["accy"]
    accz = data["accz"]
    rotx = data["rotx"]
    roty = data["roty"]
    rotz = data["rotz"]
    pred = data["pred"]

    with app.app_context():
        cursor = conexion.connection.cursor()
        sql = f"""INSERT INTO datos (idnodo, accx, accy, accz, rotx, roty, rotz, pred, fecha) 
            VALUES ({idnodo}, {accx}, {accy}, {accz}, {rotx}, {roty}, {rotz}, '{pred}', NOW())"""
        cursor.execute(sql)
        conexion.connection.commit()

@app.route("/")
def index():
    return "Welcome"

@app.route("/adduser", methods=['POST'])
def addUser():
    try:
        cursor = conexion.connection.cursor()
        sql = "SELECT * FROM users WHERE user = '{0}'".format(request.json['user'])
        cursor.execute(sql)
        datos = cursor.fetchone()
        if datos == None:
            sql="""INSERT INTO users (user,name,password,type)
            VALUES ('{0}','{1}','{2}',{3})""".format(request.json['user'], request.json['name'],request.json['password'],request.json['type'])
            cursor.execute(sql)
            conexion.connection.commit()
            return jsonify({'mensaje': "Usuario registrado"})
        else:
            return jsonify({'mensaje':"El Usuario no esta disponible"})
    except Exception as ex:
        return jsonify({'mensaje': 'Error'})

if __name__ == '__main__':
    app.run(debug=True)