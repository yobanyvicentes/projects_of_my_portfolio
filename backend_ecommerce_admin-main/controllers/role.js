const mongoose = require("mongoose");
const Role = require('../models/Role.js');
const { validationResult } = require('express-validator');

const listAllRole =  async (req, res) => {
    try {
        const roles = await Role.find();
        res.status(200).json(roles);
    } catch (error) {
        console.log(error)
        res.status(500).send('ocurrió un error al listar las roles');
    }
}

const createRole = async (req, res) => {
    try {
        const errors = validationResult(req);
        if (!errors.isEmpty()) {
            return res.status(400).json({message: errors.array()})
        }
        const internalIdExist = await Role.findOne({internalId: req.body.internal_id});
        if (internalIdExist) {
            return res.status(400).json({
                msg: `ya existe el rol con el id interno: ${req.body.internal_id}`
            })
        }
        const {internal_id, name} = req.body;
        const role = new Role;
        role.internalId = internal_id;
        role.name = name;
        role.createDate = new Date();
        role.updateDate = new Date();
        const roleSaved = await role.save();
        console.log(role)
        res.status(200).json(roleSaved);
    } catch (error) {
        console.log(error)
        res.status(500).send('ocurrió un error al crear el rol');
    }
}

const getOneRole = async (req = request, res = response) => {
    try {
        let isIdValid = mongoose.Types.ObjectId.isValid(req.params.roleId);
        if (!isIdValid) {
            return res.status(400).send('la extensión o formato del id ingresado es invalido');
        };
        let role = await Role.findById(req.params.roleId);
        if (!role) {
            return res.status(400).send('el rol a consultar no existe');
        };
        res.status(200).json(role);
    } catch (error) {
        console.log(error)
        res.status(500).send('ocurrió un error al consultar el rol');
    }
}

const deleteRole = async (req, res) => {
    try {
        let isIdValid = mongoose.Types.ObjectId.isValid(req.params.roleId);
        if (!isIdValid) {
            return res.status(400).send('la extensión o formato del id ingresado es invalido');
        };
        let role = await Role.findById(req.params.roleId);
        if (!role) {
            return res.status(400).send('el rol a eliminar no existe');
        };
        const deletedRole = await Role.deleteOne({_id: req.params.roleId})
        res.status(200).json({resume: deletedRole, role: role});
    } catch (error) {
        console.log(error)
        res.status(500).send('ocurrió un error al borrar el rol');
    }
}

const updateRole = async (req, res) => {
    try {
        const errors = validationResult(req);
        if (!errors.isEmpty()) {
            return res.status(400).json({message: errors.array()})
        }
        let isIdValid = mongoose.Types.ObjectId.isValid(req.params.roleId);
        if (!isIdValid) {
            return res.status(400).send('la extensión o formato del id ingresado es invalido');
        };
        let role = await Role.findById(req.params.roleId);
        if (!role) {
            return res.status(400).send('el rol a actualizar no existe');
        };
        const internalIdExist = await Role.findOne({internalId: req.body.internal_id, _id:{ $ne: role._id}});
        if(internalIdExist){
            return res.status(400).send('el id interno ingresado ya está asignado a otra rol distinta a la que está intentando actualizar')
        }
        const {name, internal_id} = req.body;
        role.name = name;
        role.internalId = internal_id;
        role.updateDate = Date.now();

        role = await role.save()

        return res.status(200).json({message: "actualizado", role_saved: role});
    } catch (error) {
        res.status(500).send('ocurrió un error al actualizar el rol');
        console.log(error);
    }
}

module.exports = {getOneRole, createRole, deleteRole, listAllRole, updateRole}
