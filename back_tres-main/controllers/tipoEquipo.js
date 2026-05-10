//importar el modelo Tipo Equipo
const TipoEquipo = require('../models/TipoEquipo');

//importar request y response
const {request, response} = require('express');

const getTipo = async (req = request, res = response) => {
    try {
        let tipoEquipo = await TipoEquipo.findById(req.params.TipoID);
        if(!tipoEquipo){
            return res.status(400).send('el Tipo del Equipo a aactualizar no existe')
        };

        res.send(tipoEquipo);

    } catch (error) {
        res.status(500).send('hubo un error');
    }
};

const getTipos = async (req = request , res = response) => {
    try {
        const tipoEquipos = await TipoEquipo.find();
        res.send(tipoEquipos);
    } catch (error) {
        res.status(500).send('hubo un error');
    }
};

const postTipo = async (req = request , res = response) => {
    try {
        const tipoExiste = await TipoEquipo.findOne({nombre: req.body.nombre});
        if(tipoExiste){
            return res.status(400).send('Tipo de equipo existente, pruebe con otro');
        };

        let tipoEquipo = TipoEquipo();
        tipoEquipo.nombre = req.body.nombre;
        tipoEquipo.estado = req.body.estado;
        tipoEquipo.fechaCreacion = new Date;
        tipoEquipo.fechaActualizacion = new Date;

        tipoEquipo = await tipoEquipo.save();

        res.send(tipoEquipo);
    } catch (error) {
        res.status(500).send('hubo un error');
    }
};

const putTipo = async (req = request, res = response) => {
    try {
        let tipoEquipo = await TipoEquipo.findById(req.params.TipoID);
        if(!tipoEquipo){
            return res.status(400).send('el Tipo del Equipo a aactualizar no existe')
        };

        const nombreExistente = await TipoEquipo.findOne({nombre: req.body.nombre, _id:{$ne: tipoEquipo._id}});
        if(nombreExistente){
            return res.status(400).send('el nombre del Tipo Equipo ya existe en otro documento');
        };

        tipoEquipo.nombre = req.body.nombre;
        tipoEquipo.estado = req.body.estado;
        tipoEquipo.fechaActualizacion = new Date;

        tipoEquipo = await tipoEquipo.save();

        res.send(tipoEquipo);

    } catch (error) {
        res.status(500).send('hubo un error');
    }
};

module.exports = {getTipo, getTipos, postTipo, putTipo}
