import tkinter as tk

"""Esto es todo lo que conlleva la ventana que visualiza el usuario"""

# Crear la ventana principal
ventana_principal = tk.Tk()
#Titulo de mi ventana
ventana_principal.title("Asistente de Voz")
#Defino el largo y ancho de mi ventana
ventana_principal.geometry("300x400")
#Evito que se pueda modificar el tamaño de la ventana
ventana_principal.resizable(False, False)
#Color de fondo de mi ventana
ventana_principal.config(bg="#E5DDD5")
"""/.Esto es todo lo que conlleva la ventana que visualiza el usuario"""


"""Esto es todo lo que conlleva el marco principal"""
frame_principal = tk.Frame(ventana_principal, bg="#E5DDD5")

frame_principal.pack(fill="both", expand=True, padx=10, pady=10)

# Etiqueta con texto
etiqueta = tk.Label(frame_principal, text="¡Hola! Bienvenido al asistente de voz", 
                    bg="#7289DA", fg="white", font=("Calibri", 12))
etiqueta.pack(pady=20)
"""/.Esto es todo lo que conlleva el marco principal"""




ventana_principal.mainloop()