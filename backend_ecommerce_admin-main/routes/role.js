const { Router } = require('express');
const { body } = require('express-validator');
const {createRole, listAllRole, deleteRole, getOneRole, updateRole} = require('../controllers/role.js');
const { validarJWT } = require('../middleware/tokenJWT.js');
const { esAdmin, esSuperAdmin } = require('../middleware/roleValidator.js');

const router = Router();

router.get('/', validarJWT, esAdmin,  listAllRole);
router.post('/', validarJWT, esSuperAdmin,  [body(['internal_id', 'name'], 'el campo descrito en el path está vacio, debe asignarle un valor').notEmpty()], createRole);
router.get('/:roleId', validarJWT, esAdmin,  getOneRole);
router.put('/edit/:roleId', validarJWT, esSuperAdmin,  [body(['internal_id', 'name'], 'el campo descrito en el path está vacio, debe asignarle un valor').notEmpty()], updateRole);
router.delete('/delete/:roleId', validarJWT, esSuperAdmin,  deleteRole);

module.exports = router
