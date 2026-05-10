//importar el modelo bd de Marca
const Marca = require('../models/Marca');

//importar req y res
const { request, response } = require('express');

const getMarca = async (req = request, res = response) => {
    try {
        let marca = await Marca.findById(req.params.idMarca);
        if (!marca) {
            return res.status(400).send('la marca a actualizar no existe').json;
        };
        res.send(marca);
    } catch (error) {
        res.status(500).send('hubo un error');
    }
};

const getMarcas = async (req = request, res = response) => {
    try {
        const marcas = await Marca.find();
        res.send(marcas);
    } catch (error) {
        res.status(500).send('hay un error').json();
    }
};

const postMarca = async (req = request, res = response) => {
    try {
        //en la colección de marcas buscame por nombre aquel que coincida
        //con el parametro "nombre" que viene en el body de la request
        const marcaExistente = await Marca.findOne({ nombre: req.body.nombre });
        if (marcaExistente) {
            //se agrega el "return" para que no siga ejecutando las siguientes instrcciones y no vuelva a crear otro registro
            return res.status(400).send('marca existente, pruebe con otro nombre de marca')
        };

        let marca = new Marca();
        marca.nombre = req.body.nombre;
        marca.estado = req.body.estado;
        marca.fechaCreacion = new Date;
        marca.fechaActualizacion = new Date;

        //guardar en la bd
        marca = await marca.save();

        res.send(marca);
    } catch (error) {
        res.status(500).send('hubo un error');
    }
};

const putMarca =  async (req = request, res = response) => {
    try {
        let marca = await Marca.findById(req.params.idMarca);
        if (!marca) {
            return res.status(400).send('la marca a actualizar no existe');
        };

        const marcaExistente = await Marca.findOne({ nombre: req.body.nombre, _id: { $ne: marca._id } });
        if (marcaExistente) {
            return res.status(400).send('el nombre ya está asignado a otra marca distinta a la que está intentando actualizar')
        }

        marca.nombre = req.body.nombre;
        marca.estado = req.body.estado;
        marca.fechaActualizacion = new Date;

        marca = await marca.save();

        res.send(marca);
    } catch (error) {
        res.status(500).send('hubo un error');
    }
};



module.exports = {getMarca, getMarcas, postMarca, putMarca};
