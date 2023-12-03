from flask import Flask, jsonify, request
from flask_mysqldb import MySQL
import os

app = Flask(__name__)

app.config['MYSQL_HOST'] = os.environ["MYSQL_HOST"]
app.config['MYSQL_USER'] = os.environ["MYSQL_USER"]
app.config['MYSQL_PASSWORD'] = os.environ["MYSQL_PASSORD"]
app.config['MYSQL_DB'] = os.environ["MESQL_DB"]

conexion = MySQL(app)

mqtt_broker = os.environ["mqtt_broker"]
mqtt_topic = os.environ["mqtt_topic"]