import speech_recognition as sr
import pyttsx3 as py


def registrar_paciente():
    palabras_clave = ["registrar paciente", "agregar paciente", "apuntar paciente"]
    return palabras_clave


reconocedor_audio = sr.Recognizer()
siri = py.init()

with sr.Microphone() as source:
    print("Hola, soy el asistente clinico")
    siri.say("Hola, soy el asistente clinico")
    siri.runAndWait()
    audio = reconocedor_audio.listen(source)

try:
    texto = reconocedor_audio.recognize_google(audio,language="es-ES")
    print("Dijiste:",texto.lower())

    if texto in registrar_paciente():
        print("Ok, vamos a registrar este paciente")
    else:
        print("No entendi")


except sr.UnknownValueError:
    print("No entendi loque dijiste")

except sr.RequestError:
    print("Error con la conexión a Internet")


