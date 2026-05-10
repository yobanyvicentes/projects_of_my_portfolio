//const {<nombre de las funciones a importar separadas por coma>} = require('<fuente>');
//importar la funcion getconnection de la carpeta db:
const {getConnection} = require('./connection/db-connection-mongo');
//ejecutar la funcion de conección a la base de datos:
getConnection();

//importar express para crear la app a partir de una instancia de express
const express = require('express');
//crear la app a partir de express:
const app = express();

//importar cors para para que el front pueda acceder al back desde un servidor distinto
const cors = require('cors');
app.use(cors());

//importar dotenv para las variables de entorno
require('dotenv').config();

app.use(express.json());

app.use('/marca', require('./router/marca'));
app.use('/estado-equipo', require('./router/estadoEquipo'));
app.use('/tipo-equipo', require('./router/tipoEquipo'));
app.use('/usuario', require('./router/usuario'));
app.use('/inventario', require('./router/inventario'));

// -----------------------------------------------------------
//puerto (que sea puerto en el archivo .env en el string PORT, y en caso de no estar definido, usar el 8080 por defecto)
const port = process.env.PORT || 8080;
//levantar la aplicación
app.listen(port, () => {
    console.log(`app listening on port ${port}`)
});
