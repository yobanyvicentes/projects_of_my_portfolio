const mongoose = require('mongoose');
require('dotenv').config();

const getConnection = async () => {
    try {
        const url = process.env.DATABASE_URL;
        await mongoose.connect(url);
        console.log('conexión exitosa');
    } catch (error) {
        console.log('error en la conexión, revisa el archivo db-connect-mongo.js');
    }
}

module.exports = {getConnection}
