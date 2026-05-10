const mongoose = require("mongoose");
const Product = require('../models/Product.js');
const Seller = require('../models/Seller.js');
const { validationResult } = require('express-validator');

const listAllProduct =  async (req, res) => {
    try {
        const tenantName = req.tenantName;
        const tenant = await Seller.findOne({name: tenantName});
        if (!tenant) {
            return res.status(400).json({message: "el tenant no es valido"});
        }
        const products = await Product.find({seller: tenant._id})
        .populate([
            {path:'brand', select: 'name'},
            {path:'category', select: 'name'},
        ]);
        res.status(200).json(products);
    } catch (error) {
        console.log(error)
        res.status(500).send('ocurrió un error al listar las productos');
    }
}

const createProduct = async (req, res) => {
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
        const internalIdExist = await Product.findOne({internalId: req.body.internal_id, seller: tenant._id});
        if (internalIdExist) {
            return res.status(400).json({
                msg: `ya existe el producto con el id interno: ${req.body.internal_id}`
            })
        }
        const {internal_id, name, description, image, price, inventory, brand, category} = req.body;
        const product = new Product;
        product.internalId = internal_id;
        product.name = name;
        product.description = description;
        product.image = image;
        product.price = price;
        product.inventory = inventory;
        product.brand = brand;
        product.category = category;
        product.seller = tenant._id;
        product.createDate = new Date();
        product.updateDate = new Date();
        const productSaved = await product.save();
        console.log(product)
        res.status(200).json(productSaved);
    } catch (error) {
        console.log(error)
        res.status(500).send('ocurrió un error al crear el producto');
    }
}

const getOneProduct = async (req, res) => {
    try {
        let isIdValid = mongoose.Types.ObjectId.isValid(req.params.productId);
        if (!isIdValid) {
            return res.status(400).send('la extensión o formato del id ingresado es invalido');
        };
        const tenantName = req.tenantName;
        const tenant = await Seller.findOne({name: tenantName})
        if (!tenant) {
            return res.status(400).json({message: "el tenant no es valido"});
        }
        let product = await Product.findById(req.params.productId).populate([
            {path:'brand', select: 'name'},
            {path:'category', select: 'name'},
            {path:'seller', select: 'name'},
        ]);
        if (!product) {
            return res.status(400).send('el producto a consultar no existe');
        };
        if (product.seller.name !== tenant.name) {
            return res.status(400).json({message: "el tenant ingresado no corresponde con el tenant a consultar", product: product.seller._id, tenant: tenant._id});
        }
        res.status(200).json(product);
    } catch (error) {
        console.log(error)
        res.status(500).send('ocurrió un error al consultar el producto');
    }
}

const deleteProduct = async (req, res) => {
    try {
        let isIdValid = mongoose.Types.ObjectId.isValid(req.params.productId);
        if (!isIdValid) {
            return res.status(400).send('la extensión o formato del id ingresado es invalido');
        };
        const tenantName = req.tenantName;
        const tenant = await Seller.findOne({name: tenantName});
        if (!tenant) {
            return res.status(400).json({message: "el tenant no es valido"});
        }
        let product = await Product.findById(req.params.productId);
        if (!product) {
            return res.status(400).send('el producto a eliminar no existe');
        };
        const deletedProduct = await Product.deleteOne({_id: req.params.productId, seller: tenant})
        res.status(200).json({resume: deletedProduct, product: product});
    } catch (error) {
        console.log(error)
        res.status(500).send('ocurrió un error al borrar el producto');
    }
}

const updateProduct = async (req, res) => {
    try {
        const errors = validationResult(req);
        if (!errors.isEmpty()) {
            return res.status(400).json({message: errors.array()})
        }
        let isIdValid = mongoose.Types.ObjectId.isValid(req.params.productId);
        if (!isIdValid) {
            return res.status(400).send('la extensión o formato del id ingresado es invalido');
        };
        const tenantName = req.tenantName;
        const tenant = await Seller.findOne({name: tenantName});
        if (!tenant) {
            return res.status(400).json({message: "el tenant no es valido"});
        }
        let product = await Product.findOne({_id: req.params.productId, seller: tenant});
        if (!product) {
            return res.status(400).send('el producto a actualizar no existe');
        };
        const internalIdExist = await Product.findOne({_id: {$ne: product._id}, seller: tenant, internalId: req.body.internal_id});
        if(internalIdExist){
            return res.status(400).send('el id interno ingresado ya está asignado a otro producto distinto al que está intentando actualizar')
        }
        const {internal_id, name, description, image, price, inventory, brand, category} = req.body;
        product.internalId = internal_id;
        product.name = name;
        product.description = description;
        product.image = image;
        product.price = price;
        product.inventory = inventory;
        product.brand = brand;
        product.category = category;
        product.seller = tenant._id;
        product.updateDate = Date.now();

        product = await product.save()

        return res.status(200).json({message: "actualizado", product_saved: product});
    } catch (error) {
        res.status(500).send('ocurrió un error al actualizar el producto');
        console.log(error);
    }
}

module.exports = {getOneProduct, createProduct, deleteProduct, listAllProduct, updateProduct}
