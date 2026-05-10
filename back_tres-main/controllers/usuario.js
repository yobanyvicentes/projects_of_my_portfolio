//importar request y response desde express
const {request, response} = require('express')
//importar modelo
const Usuario = require('../models/Usuario');


const getUsuario = async (req = request, res = response) => {
    try {
        //se valida si el usuario no existe
        let usuario = await Usuario.findById(req.params.usuarioId);
        if(!usuario){
            return res.status(400).send('usuario no existe').json();
        }

        res.send(usuario);
    } catch (error) {
        res.status(500).send('error');
    }
};

//listar usuarios
const getUsuarios = async (req = request, res = response) => {
    try {
        const usuarios = await Usuario.find();
        res.status(200).send(usuarios);
    } catch (error) {
        res.status(500).send('hubo un error');
    }
};

//crear usuario
const postUsuario = async (req = request, res = response) => {
    try {
        //validar existencia usuario
        const usuarioBD = await Usuario.findOne({email: req.body.email});
        if (usuarioBD) {
            return res.status(400).json({
                msg: `ya existe el ususario ${usuarioBD.email}`
            })
        }
        //crear modelo de usuario
        const usuario = new Usuario(req.body);
        usuario.fechaCreacion = new Date;
        usuario.fechaActualizacion = new Date;
        //------------------------------------------------------------------------
        //guardar en bd el usuario
        const usuarioSaved = await usuario.save();
        //retornar respuesta
        return res.status(201).json(usuarioSaved);
    } catch (error) {
        console.log(error)
        return res.status(500).send("error en la creación del usuario").json();
    }
}

//editar usuario
const putUsuario = async (req = request, res = response) => {
    try {
        //se valida si el usuario no existe
        let usuario = await Usuario.findById(req.params.usuarioId);
        if(!usuario){
            return res.status(400).send('usuario no existe');
        }

        //si no entró en lo anterior, entonces si existe
        //entonces se valida que el nuevo email a poner (en caso de que lo modifique), no lo tenga otro usuario
        const emailExistente = await Usuario.findOne({email: req.body.email, _id:{ $ne: usuario._id}});
        if(emailExistente){
            return res.status(400).send('el email ya está asignado a otro usuario distinto al que está intentando actualizar')
        }

        //setear los parámetros de la request a
        usuario.nombre = req.body.nombre;
        usuario.email = req.body.email;
        usuario.rol = req.body.rol;
        usuario.estado = req.body.estado;
        usuario.fechaActualizacion = new Date;
        //guardar en bd
        usuario = await usuario.save();

        res.send(usuario);
    } catch (error) {
        res.status(500).send('error')
    }
};


module.exports = {getUsuario, getUsuarios, postUsuario, putUsuario};
