from flask import Flask, jsonify, request
from flask_mysqldb import MySQL

app = Flask(__name__)

conexion = MySQL(app)
