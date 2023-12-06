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

@app.route("/validaruser", methods=['POST'])

def validaruser():
    try:
        cursor = conexion.connection.cursor()
        sql = "SELECT * FROM users WHERE user = '{0}' AND password = '{1}'".format(request.json['user'],request.json['password'])
        cursor.execute(sql)
        datossql = cursor
        datosop = []
        if datossql != None:
            for fila in datossql:
                datos = {"user":fila[0],"nombre":fila[1],"password":fila[2], "type":fila[3]}
                datosop.append(datos)
            return jsonify(datosop)


    except Exception as ex:
        print(ex)
        return jsonify({'mensaje': 'Error'})

@app.route("/filtrousuario", methods=['GET'])

def filtrousuario():
    try:
        args = request.args
        user = args.get("user")
        cursor = conexion.connection.cursor()
        sql = "SELECT * FROM users WHERE user = %s"
        cursor.execute(sql, (user,))
        fila = cursor.fetchone()
        datosop = []

        if fila is not None:
            datos = {'user':fila[0],'name':fila[1],'password':fila[2],'type':fila[3]}
            datosop.append(datos)

        return jsonify(datosop)
    except Exception as ex:
        return jsonify({'mensaje': 'Error'})

@app.route("/consultarusers", methods=['GET'])

def consultarUsers():
    try:
        cursor = conexion.connection.cursor()
        sql = "SELECT * FROM users"
        cursor.execute(sql)
        datossql = cursor
        datosop = []
        if datossql != None:
            for fila in datossql:
                datos = {'user':fila[0],'name':fila[1],'password':fila[2],'type':fila[3]}
                datosop.append(datos)
        return jsonify(datosop)
    except Exception as ex:
        print(ex)
        return jsonify({'mensaje':'Error'})

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
        print(ex)
        return jsonify({'mensaje': 'Error'})


@app.route("/deleteuser", methods=['DELETE'])
def deleteuser():
    try:
        cursor = conexion.connection.cursor()
        sql = "DELETE FROM users WHERE user = '{0}'".format(request.json['user'])
        cursor.execute(sql)
        conexion.connection.commit()

        return jsonify({'mensaje': 'Usuario eliminado correctamente'})

    except Exception as ex:
        print(ex)
        return jsonify({'mensaje': 'Error al eliminar el usuario'})


@app.route("/updateuser", methods=['PUT'])
def updateuser():
    try:
        cursor = conexion.connection.cursor()
        sql = "UPDATE users SET name = '{0}', password='{1}', type={2} WHERE user='{3}'".format(request.json['name'],request.json['password'],request.json['type'],request.json['user'])
        cursor.execute(sql)
        conexion.connection.commit()

        return jsonify({'mensaje': 'Usuario Modificado'})
    except Exception as ex:
        print(ex)
        return jsonify({'mensaje': 'Error al modificar el usuario'})


@app.route("/datos", methods=['GET'])
def datos():
    try:
        cursor = conexion.connection.cursor()
        sql = "SELECT * FROM datos"
        cursor.execute(sql)
        datossql = cursor
        datosop = []
        if datossql != None:
            for fila in datossql:
                datos = {"id":fila[0],"idnodo":fila[1],"accx":fila[2], "accy":fila[3], "accz":fila[4], "rotx":fila[5], "roty":fila[6], "rotz":fila[7], "pred":fila[8], "fecha":fila[9]}
                datosop.append(datos)
        return jsonify(datosop)
    except Exception as ex:
        print(ex)
        return jsonify({'mensaje': 'Error traer los datos'})


@app.route("/datosidnodo", methods=['GET'])

def datosidnodo():
    try:
        args = request.args
        idnodo = args.get("idnodo")
        cursor = conexion.connection.cursor()
        sql = f"SELECT * FROM datos WHERE idnodo = {idnodo}"
        cursor.execute(sql)
        datossql = cursor
        datosop = []
        if datossql != None:
            for fila in datossql:
                datos = {"id":fila[0],"idnodo":fila[1],"accx":fila[2], "accy":fila[3], "accz":fila[4], "rotx":fila[5], "roty":fila[6], "rotz":fila[7], "pred":fila[8], "fecha":fila[9]}
                datosop.append(datos)
        return jsonify(datosop)
    except Exception as ex:
        return jsonify({'mensaje':'Error'})

@app.route("/datosidenodoultimo", methods=['GET'])
def datosidnodoultimo():
    try:
        args = request.args
        idnodo = args.get("idnodo")
        cursor = conexion.connection.cursor()
        sql = "SELECT * FROM datos WHERE idnodo = %s ORDER BY id DESC LIMIT 1"
        cursor.execute(sql, (idnodo,))
        fila = cursor.fetchone()
        datosop = []

        if fila is not None:
            datos = {"id": fila[0], "idnodo": fila[1], "accx": fila[2], "accy": fila[3], "accz": fila[4],
                    "rotx": fila[5], "roty": fila[6], "rotz": fila[7], "pred": fila[8], "fecha": fila[9]}
            datosop.append(datos)

        return jsonify(datosop)
    except Exception as ex:
        return jsonify({'mensaje': 'Error'})

@app.route("/crearnodo", methods=['POST'])

def crearnodo():
    try:
        cursor = conexion.connection.cursor()
        sql = "SELECT * FROM nodos WHERE idnodo = {0}".format(request.json['idnodo'])
        cursor.execute(sql)
        datos = cursor.fetchone()
        if datos == None:
            cursor = conexion.connection.cursor()
            sql = """INSERT INTO nodos (idnodo, nombre, ubicacion, user, estado) VALUES ({0}, '{1}','{2}','{3}',{4})""".format(request.json['idnodo'], request.json['nombre'], request.json['ubicacion'], request.json['user'], request.json['estado'])
            cursor.execute(sql)
            conexion.connection.commit()
            return jsonify({'mensaje': "Nodo Agregado"})
        else:
            return jsonify({'mensaje':"El idnodo no esta disponible"})
    except Exception as ex:
        print(ex)
        return jsonify({'mensaje':'Error'})

@app.route("/eliminarnodo", methods=['DELETE'])

def eliminarnodo():
    try:
        cursor = conexion.connection.cursor()
        sql = "DELETE FROM nodos WHERE idnodo = {0}".format(request.json['idnodo'])
        cursor.execute(sql)
        conexion.connection.commit()

        return jsonify({'mensaje': 'Exito'})
    except Exception as ex:
        return jsonify({'mensaje': 'Error'})


@app.route("/modificarnodo", methods=['PUT'])

def modificarnodo():
    try:
        cursor = conexion.connection.cursor()
        sql = "UPDATE nodos SET nombre = '{0}', ubicacion ='{1}', user='{2}', estado ='{3}' WHERE idnodo={4}".format(request.json['nombre'],request.json['ubicacion'],request.json['user'],request.json['estado'],request.json['idnodo'])
        cursor.execute(sql)
        conexion.connection.commit()

        return jsonify({'mensaje': 'Exito'})
    except Exception as ex:
        print(ex)
        return jsonify({'mensaje': 'Error'})

if __name__ == '__main__':
    app.run(debug=True)