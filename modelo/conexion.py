import mysql.connector

conexion = mysql.connector.connect(
    host= "localhost",
    password="",
    user="root",
    database="clinica_web"
)

if conexion.is_connected():
    print("Conexion exitosa")
else:
    print("No se puedo conectar a la base de datos")

conexion.close()