const { Router } = require('express');
const { body } = require('express-validator');
const {createUser, listAllUser, deleteUser, getOneUser, updateUser} = require('../controllers/user.js');
const { validarJWT } = require('../middleware/tokenJWT.js');
const { esAdmin } = require('../middleware/roleValidator.js');

const router = Router();

router.get('/', validarJWT, esAdmin,  listAllUser);
router.post('/', validarJWT, esAdmin,  [body(['user_id', 'name', 'email', 'password', 'role'], 'el campo descrito en el path está vacio, debe asignarle un valor').notEmpty(), body('email', 'debe ser un email con formato valido').isEmail()], createUser);
router.get('/:userId', validarJWT, esAdmin,  getOneUser);
router.put('/edit/:userId', validarJWT, esAdmin,  [body(['user_id', 'name', 'email', 'role'], 'el campo descrito en el path está vacio, debe asignarle un valor').notEmpty(), body('email', 'debe ser un email con formato valido').isEmail()], updateUser);
router.delete('/delete/:userId', validarJWT, esAdmin,  deleteUser);

module.exports = router
