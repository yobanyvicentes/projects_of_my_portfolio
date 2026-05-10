//importar el modelo Estado Equipo
const EstadoEquipo = require('../models/EstadoEquipo');

//importar request y response
const {request, response} = require('express');


const getEstado = async (req = request, res = response) => {
    try {
        let estadoEquipo = await EstadoEquipo.findById(req.params.estadoID);
        if (!estadoEquipo) {
            return res.status(400).send('el estadoEquipo a actualizar no existe').json;
        };
        res.send(estadoEquipo);
    } catch (error) {
        res.status(500).send('hubo un error');
    }
};

const getEstados = async (req = request ,res = response) => {
    try {
        const estadoEquipos = await EstadoEquipo.find();
        res.send(estadoEquipos);
    } catch (error) {
        res.status(500).send('hubo un error');
    }
};

const postEstado = async (req = request, res = response) => {
    try {
        const estadoExiste = await EstadoEquipo.findOne({nombre: req.body.nombre});
        if(estadoExiste){
            return res.status(400).send('estado de equipo existente, pruebe con otro');
        };

        let estadoEquipo = EstadoEquipo();
        estadoEquipo.nombre = req.body.nombre;
        estadoEquipo.estado = req.body.estado;
        estadoEquipo.fechaCreacion = new Date;
        estadoEquipo.fechaActualizacion = new Date;

        estadoEquipo = await estadoEquipo.save();

        res.send(estadoEquipo);
    } catch (error) {
        res.status(500).send('hubo un error');
    }
};

const putEstado = async (req = request, res = response) => {
    try {
        let estadoEquipo = await EstadoEquipo.findById(req.params.estadoID);
        if(!estadoEquipo){
            return res.status(400).send('el estado del Equipo a actualizar no existe');
        };

        const nombreExistente = await EstadoEquipo.findOne({nombre: req.body.nombre, _id:{$ne: estadoEquipo._id}});
        if(nombreExistente){
            return res.status(400).send('el nombre del estado Equipo ya existe en otro documento');
        };

        estadoEquipo.nombre = req.body.nombre;
        estadoEquipo.estado = req.body.estado;
        estadoEquipo.fechaActualizacion = new Date;

        estadoEquipo = await estadoEquipo.save();

        res.send(estadoEquipo);

    } catch (error) {
        res.status(500).send('hubo un error');
    }
};


module.exports = {getEstado, getEstados, postEstado, putEstado};
