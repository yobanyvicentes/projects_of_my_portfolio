const mongoose = require("mongoose");
const Category = require('../models/Category.js');
const Seller = require('../models/Seller.js');
const { validationResult } = require('express-validator');

const listAllCategory =  async (req, res) => {
    try {
        const tenantName = req.tenantName;
        const tenant = await Seller.findOne({name: tenantName});
        if (!tenant) {
            return res.status(400).json({message: "el tenant no es valido"});
        }
        const categories = await Category.find({seller: tenant});
        res.status(200).json(categories);
    } catch (error) {
        console.log(error)
        res.status(500).send('ocurrió un error al listar las categorias');
    }
}

const createCategory = async (req, res) => {
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
        const internalIdExist = await Category.findOne({internalId: req.body.internal_id, seller: tenant._id});
        if (internalIdExist) {
            return res.status(400).json({
                msg: `ya existe la categoria con el id interno: ${req.body.internal_id}`
            })
        }
        const {internal_id, name} = req.body;
        const category = new Category;
        category.internalId = internal_id;
        category.name = name;
        category.seller = tenant._id;
        category.createDate = new Date();
        category.updateDate = new Date();
        const categorySaved = await category.save();
        res.status(200).json(categorySaved);
    } catch (error) {
        console.log(error)
        res.status(500).send('ocurrió un error al crear la categoria');
    }
}

const getOneCategory = async (req, res) => {
    try {
        let isIdValid = mongoose.Types.ObjectId.isValid(req.params.categoryId);
        if (!isIdValid) {
            return res.status(400).send('la extensión o formato del id ingresado es invalido');
        };
        const tenantName = req.tenantName;
        const tenant = await Seller.findOne({name: tenantName});
        if (!tenant) {
            return res.status(400).json({message: "el tenant no es valido"});
        }
        let category= await Category.findById(req.params.categoryId).populate(
            {path:'seller', select: 'name description'}
        );
        if (!category) {
            return res.status(400).send('la categoria a consultar no existe');
        };
        if (category.seller.name !== tenant.name) {
            return res.status(400).json({message: "el tenant ingresado no corresponde con el tenant a consultar", category: category.seller._id, tenant: tenant._id});
        }
        res.status(200).json(category);
    } catch (error) {
        console.log(error)
        res.status(500).send('ocurrió un error al consultar la categoria');
    }
}

const deleteCategory = async (req, res) => {
    try {
        let isIdValid = mongoose.Types.ObjectId.isValid(req.params.categoryId);
        if (!isIdValid) {
            return res.status(400).send('la extensión o formato del id ingresado es invalido');
        };
        const tenantName = req.tenantName;
        const tenant = await Seller.findOne({name: tenantName});
        if (!tenant) {
            return res.status(400).json({message: "el tenant no es valido"});
        }
        let category= await Category.findById(req.params.categoryId);
        if (!category) {
            return res.status(400).send('la categoria a eliminar no existe');
        };
        const deletedCategory = await Category.deleteOne({_id: req.params.categoryId, seller: tenant})
        res.status(200).json({resume: deletedCategory, category: category});
    } catch (error) {
        console.log(error)
        res.status(500).send('ocurrió un error al borrar la categoria');
    }
}

const updateCategory = async (req, res) => {
    try {
        const errors = validationResult(req);
        if (!errors.isEmpty()) {
            return res.status(400).json({message: errors.array()})
        }
        let isIdValid = mongoose.Types.ObjectId.isValid(req.params.categoryId);
        if (!isIdValid) {
            return res.status(400).send('la extensión o formato del id ingresado es invalido');
        };
        const tenantName = req.tenantName;
        const tenant = await Seller.findOne({name: tenantName});
        if (!tenant) {
            return res.status(400).json({message: "el tenant no es valido"});
        }
        let category= await Category.findOne({_id: req.params.categoryId, seller: tenant});
        if (!category) {
            return res.status(400).send('la categoria a actualizar no existe');
        };
        const internalIdExist = await Category.findOne({_id: {$ne: category._id}, seller: tenant, internalId: req.body.internal_id});
        if(internalIdExist){
            return res.status(400).send('el id interno ingresado ya está asignado a otra categoria distinta a la que está intentando actualizar, en ese caso actualice el valor del inventario')
        }
        const {name, internal_id} = req.body;
        category.name = name;
        category.internalId = internal_id;
        category.updateDate = Date.now();

        category= await category.save()

        return res.status(200).json({message: "actualizado", category_saved: category});
    } catch (error) {
        res.status(500).send('ocurrió un error al actualizar la categoria');
        console.log(error);
    }
}

module.exports = {getOneCategory, createCategory, deleteCategory, listAllCategory, updateCategory}
