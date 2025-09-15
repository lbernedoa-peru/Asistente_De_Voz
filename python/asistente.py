# python/asistente.py
# Ejemplo mínimo que imprime una respuesta (sería llamado desde PHP si quisieras)
import sys
texto = " ".join(sys.argv[1:]) if len(sys.argv)>1 else "Hola desde Python"
print("Python dice:", texto)
