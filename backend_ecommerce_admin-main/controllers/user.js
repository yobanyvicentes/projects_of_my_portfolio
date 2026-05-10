const mongoose = require("mongoose");
const User = require('../models/User.js');
const Seller = require('../models/Seller.js');
const bcrypt = require('bcryptjs');
const { validationResult } = require('express-validator');

const listAllUser =  async (req, res) => {
    try {
        const tenantName = req.tenantName;
        const tenant = await Seller.findOne({name: tenantName});
        if (!tenant) {
            return res.status(400).json({message: "el tenant no es valido"});
        }
        const users = await User.find({seller: tenant}).populate([
            {path:'role', select: 'name'},
            {path:'seller', select: 'name description'}
        ]);
        res.status(200).json(users);
    } catch (error) {
        console.log(error)
        res.status(500).send('ocurrió un error al listar las usuarios');
    }
}

const createUser = async (req, res) => {
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
        const emailExist = await User.findOne({email: req.body.email}).populate([
            {path:'seller', select: 'name description'},
        ]);
        if (emailExist) {
            return res.status(400).json({
                message: `ya existe un usuario con el email: ${req.body.email}, reporatdo en la empresa ${emailExist.seller.name}`
            })
        }
        const {user_id, name, email, password, role} = req.body;
        const salt = await bcrypt.genSalt(10);
        const passwordEnc = await bcrypt.hash(password, salt);

        const user = new User;
        user.userId = user_id;
        user.name = name;
        user.email = email;
        user.password = passwordEnc;
        user.role = role;
        user.seller = tenant._id;
        user.createDate = new Date();
        user.updateDate = new Date();
        const userSaved = await user.save();
        console.log(user)
        res.status(200).json(userSaved);
    } catch (error) {
        console.log(error)
        res.status(500).send('ocurrió un error al crear el usuario');
    }
}

const getOneUser = async (req, res) => {
    try {
        let isIdValid = mongoose.Types.ObjectId.isValid(req.params.userId);
        if (!isIdValid) {
            return res.status(400).send('la extensión o formato del id ingresado es invalido');
        };
        const tenantName = req.tenantName;
        const tenant = await Seller.findOne({name: tenantName});
        if (!tenant) {
            return res.status(400).json({message: "el tenant no es valido"});
        }
        let user = await User.findById(req.params.userId).populate([
            {path:'role', select: 'name'},
            {path:'seller', select: 'name description'}
        ]);
        if (!user) {
            return res.status(400).send('el usuario a consultar no existe');
        };
        if (user.seller.name !== tenant.name) {
            return res.status(400).json({message: "el tenant ingresado no corresponde con el tenant a consultar", user: user.seller._id, tenant: tenant._id});
        }
        res.status(200).json(user);
    } catch (error) {
        console.log(error)
        res.status(500).send('ocurrió un error al consultar el usuario');
    }
}

const deleteUser = async (req, res) => {
    try {
        let isIdValid = mongoose.Types.ObjectId.isValid(req.params.userId);
        if (!isIdValid) {
            return res.status(400).send('la extensión o formato del id ingresado es invalido');
        };
        const tenantName = req.tenantName;
        const tenant = await Seller.findOne({name: tenantName});
        if (!tenant) {
            return res.status(400).json({message: "el tenant no es valido"});
        };
        let user = await User.findById(req.params.userId);
        if (!user) {
            return res.status(400).send('el usuario a eliminar no existe');
        };
        const deletedUser = await User.deleteOne({_id: req.params.userId, seller: tenant})
        res.status(200).json({resume: deletedUser, user: user});
    } catch (error) {
        console.log(error)
        res.status(500).send('ocurrió un error al borrar el usuario');
    }
}

const updateUser = async (req, res) => {
    try {
        const errors = validationResult(req);
        if (!errors.isEmpty()) {
            return res.status(400).json({message: errors.array()})
        }
        let isIdValid = mongoose.Types.ObjectId.isValid(req.params.userId);
        if (!isIdValid) {
            return res.status(400).send('la extensión o formato del id ingresado es invalido');
        };
        const tenantName = req.tenantName;
        const tenant = await Seller.findOne({name: tenantName});
        if (!tenant) {
            return res.status(400).json({message: "el tenant no es valido"});
        };
        let user = await User.findOne({_id: req.params.userId, seller: tenant});
        if (!user) {
            return res.status(400).send('el usuario a actualizar no existe');
        };
        const userExist = await User.findOne({_id:{$ne: user._id}, email: req.body.email});
        if(userExist){
            return res.status(400).send('el email ingresado ya está asignado a otro usuario distinto al que está intentando actualizar')
        }
        const {user_id, name, email, password, role} = req.body;
        user.userId = user_id;
        user.name = name;
        user.email = email;
        user.role = role;
        user.updateDate = Date.now();

        user = await user.save()

        return res.status(200).json({message: "actualizado", user_saved: user});
    } catch (error) {
        res.status(500).send('ocurrió un error al actualizar el usuario');
        console.log(error);
    }
}

module.exports = {getOneUser, createUser, deleteUser, listAllUser, updateUser}
