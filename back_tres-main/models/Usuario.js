const {Schema, model} = require('mongoose')
//crear el modelo UsuariosSchema:
const UsuarioSchema = Schema({

    //crear los atributos:
    nombre:{
        type: String,
        required: true,
    },
    email:{
        type: String,
        required: true,
        unique: true,
    },
    rol:{
        type: String,
        enum:['admin',
            'normal'],
    },
    estado:{
        type: String,
        required: true,
        enum:['Activo',
            'Inactivo'],
    },
    fechaCreacion:{
        type: Date,
        required: true,
    },
    fechaActualizacion:{
        type: Date,
        required: true,
    },
});

//exportar el modelo
module.exports = model('Usuario', UsuarioSchema);
