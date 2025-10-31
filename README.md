# Gestor de Tareas en PHP — Actividad Obligatoria DWES

Este proyecto es una **aplicación de consola desarrollada en PHP** que permite gestionar una lista de tareas desde **PowerShell** o **CMD**.  
El sistema guarda la información en un archivo `data.json`, que se actualiza automáticamente después de cada operación.

Con esta herramienta podrás **añadir**, **editar**, **listar**, **eliminar** y **marcar como completadas** tus tareas directamente desde la terminal.

---

## Descripción del Proyecto

El **Gestor de Tareas** es una aplicación sencilla basada en línea de comandos.  
Cada tarea contiene los siguientes campos:

- `id`: Identificador único numérico.  
- `title`: Título breve de la tarea.  
- `description`: Descripción detallada.  
- `due_date`: Fecha límite.  
- `completed`: Estado booleano (`true` o `false`).

Toda la información se almacena en el archivo `data.json`, que actúa como base de datos local.

---

## Requisitos Previos

Antes de ejecutar el proyecto, asegúrate de tener instalado:

- **PHP 8.0 o superior**  
- **PowerShell** o **CMD** (en Windows)  
- Archivos `TaskManager.php` y `data.json` en el mismo directorio

El archivo `data.json` debe tener formato JSON válido.  
Ejemplo de contenido inicial:

```json
[
    {
        "id": 1,
        "title": "Ejemplo",
        "description": "Primera tarea",
        "due_date": "2025-10-31",
        "completed": false
    }
]
```
--- 
## Ejecución del Programa

Desde la terminal o PowerShell, navega hasta el directorio del proyecto y ejecuta los comandos con:

| Comando | Descripción | Ejemplo |
|---------|-------------|---------|
| `add <title> <description> <due_date>` | Añade una nueva tarea. | `php TaskManager.php add "Hacer la compra" "Frutas y verduras" "2025-11-02"` |
| `edit <id> <title> <description> <due_date>` | Edita una tarea existente. | `php TaskManager.php edit 1 "Comprar material" "Papelería y tinta" "2025-11-03"` |
| `delete <id>` | Elimina una tarea por ID. | `php TaskManager.php delete 2` |
| `delete all` | Elimina todas las tareas. | `php TaskManager.php delete all` |
| `list` | Muestra todas las tareas. | `php TaskManager.php list` |
| `list <id>` | Muestra una tarea específica. | `php TaskManager.php list 3` |
| `list --completed` | Muestra solo las tareas completadas. | `php TaskManager.php list --completed` |
| `complete <id>` | Marca una tarea como completada. | `php TaskManager.php complete 1` |
| `help` | Muestra la ayuda con todos los comandos. | `php TaskManager.php help` |