# VaultSEC
Aplicación para el almacenamiento seguro en la nube.

## Requisitos mínimos
- Entorno XAMPP.
- MySQL/MariaDB (versión 10)
- PHP (versión 7)

## Credenciales
- user : user123 (Usuario normal con contenido de ejemplo)
- test : test123 (Usuario nuevo)
- admin : admin123 (Administrador)

## Configuración
- Archivo "db.ini": credenciales para la base de datos.
- Archivo "settings.php": parámetros para la aplicación.
- Archivo "vaultsec.sql" contiene el script SQL necesario para importar la base de datos completa. Es posible que sea necesario crear primero la base de datos "vaultsec" y después realizar la importación. Es importante asegurarse que el cotejamiento sea "utf8_spanish_ci" para evitar cualquier problema de codificación.

## Uso
En caso de querer añadir más tipos de archivos permitidos debemos hacerlo manualmente:
1) En "settings.php" añadimos la extensión a la lista blanca.
2) En la tabla "vs_mimetypes" de la base de datos añadimos el MIME correspondiente. Para más información nos podemos ayudar de la tabla oficial https://www.iana.org/assignments/media-types/media-types.xhtml

Si queremos añadir un usuario nuevo debemos introducirlo manualmente en la base de datos proporcionando un uid (nick), un displayname (nombre), un rid (id del rol correspondiente en la tabla "vs_roles") y una contraseña. Para este último campo debemos pasar su hash BCRYPT, podemos ayudarnos de alguna herramienta online como https://bcrypt-generator.com