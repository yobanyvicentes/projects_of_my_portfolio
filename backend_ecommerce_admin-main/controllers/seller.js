const mongoose = require("mongoose");
const Seller = require('../models/Seller.js');
const { validationResult } = require('express-validator');

const listAllSeller =  async (req, res) => {
    try {
        const sellers = await Seller.find();
        res.status(200).json(sellers);
    } catch (error) {
        console.log(error)
        res.status(500).send('ocurrió un error al listar las vendedores');
    }
}

const createSeller = async (req, res) => {
    try {
        const errors = validationResult(req);
        if (!errors.isEmpty()) {
            return res.status(400).json({message: errors.array()})
        }
        const internalIdExist = await Seller.findOne({internalId: req.body.internal_id});
        if (internalIdExist) {
            return res.status(400).json({
                msg: `ya existe el vendedor con el id interno: ${req.body.internal_id}`
            })
        }
        const {internal_id, name, description} = req.body;
        const seller = new Seller;
        seller.internalId = internal_id;
        seller.name = name;
        seller.description = description;
        seller.createDate = new Date();
        seller.updateDate = new Date();
        const sellerSaved = await seller.save();
        console.log(seller)
        res.status(200).json(sellerSaved);
    } catch (error) {
        console.log(error)
        res.status(500).send('ocurrió un error al crear el vendedor');
    }
}

const getOneSeller = async (req, res) => {
    try {
        let isIdValid = mongoose.Types.ObjectId.isValid(req.params.sellerId);
        if (!isIdValid) {
            return res.status(400).send('la extensión o formato del id ingresado es invalido');
        };
        let seller = await Seller.findById(req.params.sellerId);
        if (!seller) {
            return res.status(400).send('el vendedor a consultar no existe');
        };
        res.status(200).json(seller);
    } catch (error) {
        console.log(error)
        res.status(500).send('ocurrió un error al consultar el vendedor');
    }
}

const deleteSeller = async (req, res) => {
    try {
        let isIdValid = mongoose.Types.ObjectId.isValid(req.params.sellerId);
        if (!isIdValid) {
            return res.status(400).send('la extensión o formato del id ingresado es invalido');
        };
        let seller = await Seller.findById(req.params.sellerId);
        if (!seller) {
            return res.status(400).send('el vendedor a eliminar no existe');
        };
        const deletedSeller = await Seller.deleteOne({_id: req.params.sellerId})
        res.status(200).json({resume: deletedSeller, seller: seller});
    } catch (error) {
        console.log(error)
        res.status(500).send('ocurrió un error al borrar el vendedor');
    }
}

const updateSeller = async (req, res) => {
    try {
        const errors = validationResult(req);
        if (!errors.isEmpty()) {
            return res.status(400).json({message: errors.array()})
        }
        let isIdValid = mongoose.Types.ObjectId.isValid(req.params.sellerId);
        if (!isIdValid) {
            return res.status(400).send('la extensión o formato del id ingresado es invalido');
        };
        let seller = await Seller.findById(req.params.sellerId);
        if (!seller) {
            return res.status(400).send('el vendedor a actualizar no existe');
        };
        const internalIdExist = await Seller.findOne({internalId: req.body.internal_id, _id:{ $ne: seller._id}});
        if(internalIdExist){
            return res.status(400).send('el id interno ingresado ya está asignado a otra vendedor distinta a la que está intentando actualizar')
        }
        const {name, internal_id, description} = req.body;
        seller.name = name;
        seller.internalId = internal_id;
        seller.description = description;
        seller.updateDate = Date.now();

        seller = await seller.save()

        return res.status(200).json({message: "actualizado", seller_saved: seller});
    } catch (error) {
        res.status(500).send('ocurrió un error al actualizar el vendedor');
        console.log(error);
    }
}

module.exports = {getOneSeller, createSeller, deleteSeller, listAllSeller, updateSeller}

