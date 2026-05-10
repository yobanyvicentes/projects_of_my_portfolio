const mongoose = require("mongoose");
const Brand = require('../models/Brand.js');
const Seller = require('../models/Seller.js');
const { validationResult } = require('express-validator');

const listAllBrand =  async (req, res) => {
    try {
        const tenantName = req.tenantName;
        const tenant = await Seller.findOne({name: tenantName});
        if (!tenant) {
            return res.status(400).json({message: "el tenant no es valido"});
        }
        const brands = await Brand.find({seller: tenant});
        res.status(200).json(brands);
    } catch (error) {
        console.log(error)
        res.status(500).send('ocurrió un error al listar las marcas');
    }
}

const createBrand = async (req, res) => {
    try {
        const errors = validationResult(req);
        if (!errors.isEmpty()) {
            return res.status(400).json({message: errors.array()})
        }
        const tenantName = req.tenantName;
        const tenant = await Seller.findOne({name: tenantName});
        if (!tenant) {
            return res.status(400).json({message: "el tenant no es valido"});
        }
        const internalIdExist = await Brand.findOne({internalId: req.body.internal_id, seller: tenant._id});
        if (internalIdExist) {
            return res.status(400).json({
                msg: `ya existe la marca con el id interno: ${req.body.internal_id}`
            })
        }
        const {internal_id, name} = req.body;
        const brand = new Brand;
        brand.internalId = internal_id;
        brand.name = name;
        brand.seller = tenant._id;
        brand.createDate = new Date();
        brand.updateDate = new Date();
        const brandSaved = await brand.save();
        console.log(brand)
        res.status(200).json(brandSaved);
    } catch (error) {
        console.log(error)
        res.status(500).send('ocurrió un error al crear la marca');
    }
}

const getOneBrand = async (req, res) => {
    try {
        let isIdValid = mongoose.Types.ObjectId.isValid(req.params.brandId);
        if (!isIdValid) {
            return res.status(400).send('la extensión o formato del id ingresado es invalido');
        };
        const tenantName = req.tenantName;
        const tenant = await Seller.findOne({name: tenantName});
        if (!tenant) {
            return res.status(400).json({message: "el tenant no es valido"});
        }
        let brand = await Brand.findById(req.params.brandId).populate({path:'seller', select: 'name description'});
        if (!brand) {
            return res.status(400).send('la marca a consultar no existe');
        };
        if (brand.seller.name !== tenant.name) {
            return res.status(400).json({message: "el tenant ingresado no corresponde con el tenant a consultar", brand: brand.seller._id, tenant: tenant._id});
        }
        res.status(200).json(brand);
    } catch (error) {
        console.log(error)
        res.status(500).send('ocurrió un error al consultar la marca');
    }
}

const deleteBrand = async (req, res) => {
    try {
        let isIdValid = mongoose.Types.ObjectId.isValid(req.params.brandId);
        if (!isIdValid) {
            return res.status(400).send('la extensión o formato del id ingresado es invalido');
        };
        const tenantName = req.tenantName;
        const tenant = await Seller.findOne({name: tenantName});
        if (!tenant) {
            return res.status(400).json({message: "el tenant no es valido"});
        }
        let brand = await Brand.findById(req.params.brandId);
        if (!brand) {
            return res.status(400).send('la marca a eliminar no existe');
        };
        const deletedBrand = await Brand.deleteOne({_id: req.params.brandId, seller: tenant})
        res.status(200).json({resume: deletedBrand, brand: brand});
    } catch (error) {
        console.log(error)
        res.status(500).send('ocurrió un error al borrar la marca');
    }
}

const updateBrand = async (req, res) => {
    try {
        const errors = validationResult(req);
        if (!errors.isEmpty()) {
            return res.status(400).json({message: errors.array()})
        }
        let isIdValid = mongoose.Types.ObjectId.isValid(req.params.brandId);
        if (!isIdValid) {
            return res.status(400).send('la extensión o formato del id ingresado es invalido');
        };
        const tenantName = req.tenantName;
        const tenant = await Seller.findOne({name: tenantName});
        if (!tenant) {
            return res.status(400).json({message: "el tenant no es valido"});
        }
        let brand = await Brand.findOne({_id: req.params.brandId, seller: tenant._id});
        if (!brand) {
            return res.status(400).send('la marca a actualizar no existe');
        };
        const internalIdExist = await Brand.findOne({_id: {$ne: brand._id}, seller: tenant._id, internalId: req.body.internal_id});
        if(internalIdExist){
            return res.status(400).send('el id interno ingresado ya está asignado a otra marca distinta a la que está intentando actualizar, en ese caso actualice el valor del inventario')
        }
        const {name, internal_id} = req.body;
        brand.name = name;
        brand.internalId = internal_id;
        brand.updateDate = Date.now();

        brand = await brand.save()

        return res.status(200).json({message: "actualizado", brand_saved: brand});
    } catch (error) {
        res.status(500).send('ocurrió un error al actualizar la marca');
        console.log(error);
    }
}

module.exports = {getOneBrand, createBrand, deleteBrand, listAllBrand, updateBrand}
