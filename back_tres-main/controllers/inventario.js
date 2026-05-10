const Inventario = require('../models/Inventario');
const { request, response } = require('express');

const getInventarios = async (req = request, res= response) => {
    try {
        const inventarios = await Inventario.find().populate([
            {
                path: 'usuario', select: 'nombre email estado'
            },
            {
                path: 'marca', select: 'nombre estado'
            },
            {
                path: 'estadoEquipo', select: 'nombre estado'
            },
            {
                path: 'tipoEquipo', select: 'nombre estado'
            }
        ]);
        res.send(inventarios);
    } catch (error) {
        res.status(500).send('hubo un error');
    }
};

const getInventario = async (req = request, res= response) => {
    try {
        let inventario = await Inventario.findById(req.params.inventarioId).populate([
            {
                path: 'usuario', select: 'nombre email estado'
            },
            {
                path: 'marca', select: 'nombre estado'
            },
            {
                path: 'estadoEquipo', select: 'nombre estado'
            },
            {
                path: 'tipoEquipo', select: 'nombre estado'
            }
        ]);
        if(!inventario){
            return res.status(400).send('el id del inventario a actualizar no existe');
        };
        res.send(inventario);
    } catch (error) {
        res.status(500).send('hubo un error');
    }
};

const postInventario = async (req = request, res= response) => {
    try {
        const serialExistente = await Inventario.findOne({serial: req.body.serial});
        if(serialExistente){
            return res.status(400).send('el serial ingresado está asignado a otro equipo');
        }

        let inventario = new Inventario();
        inventario.serial = req.body.serial;
        inventario.modelo = req.body.modelo;
        inventario.descripcion = req.body.descripcion;
        inventario.color = req.body.color;
        inventario.foto = req.body.foto;
        inventario.fechaCompra = req.body.fechaCompra;
        inventario.precio = req.body.precio;
        inventario.usuario = req.body.usuario._id;
        inventario.marca = req.body.marca._id;
        inventario.estadoEquipo = req.body.estadoEquipo._id;
        inventario.tipoEquipo = req.body.tipoEquipo._id;
        inventario.fechaCreacion = new Date();
        inventario.fechaActualizacion = new Date();

        inventario = await inventario.save();

        res.send(inventario);
    } catch (error) {
        res.status(500).send('hubo un error');
        console.log(error);
    }
};

const putInventario = async (req = request, res= response) => {
    try {
        let inventario = await Inventario.findById(req.params.inventarioId);
        if(!inventario){
            return res.status(400).send('el id del inventario a actualizar no existe');
        }

        const serialExistente = await Inventario.findOne({serial: req.body.serial, _id:{$ne: inventario._id}});
        if(serialExistente){
            return res.status(400).send('el serial ingresado está asignado a otro equipo');
        }

        inventario.serial = req.body.serial;
        inventario.modelo = req.body.modelo;
        inventario.descripcion = req.body.descripcion;
        inventario.color = req.body.color;
        inventario.foto = req.body.foto;
        inventario.fechaCompra = req.body.fechaCompra;
        inventario.precio = req.body.precio;
        inventario.usuario = req.body.usuario._id;
        inventario.marca = req.body.marca._id;
        inventario.estadoEquipo = req.body.estadoEquipo._id;
        inventario.tipoEquipo = req.body.tipoEquipo._id;
        inventario.fechaActualizacion = new Date();

        inventario = await inventario.save();

        res.send(inventario);
    } catch (error) {
        res.status(500).send('hubo un error');
        console.log.apply(error);
    }
};

module.exports = {getInventarios, getInventario, postInventario, putInventario};
