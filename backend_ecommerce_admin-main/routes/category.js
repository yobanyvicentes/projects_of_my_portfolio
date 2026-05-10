const { Router } = require('express');
const { body } = require('express-validator');
const {createCategory, listAllCategory, deleteCategory, getOneCategory, updateCategory} = require('../controllers/category.js');
const { validarJWT } = require('../middleware/tokenJWT.js');
const { esAdmin } = require('../middleware/roleValidator.js');

const router = Router();

router.get('/',  validarJWT, esAdmin, listAllCategory);
router.post('/',  validarJWT, esAdmin, [body(['internal_id', 'name'], 'el campo descrito en el path está vacio, debe asignarle un valor').notEmpty()], createCategory);
router.get('/:categoryId',  validarJWT, esAdmin, getOneCategory);
router.put('/edit/:categoryId', validarJWT, esAdmin,  [body(['internal_id', 'name'], 'el campo descrito en el path está vacio, debe asignarle un valor').notEmpty()], updateCategory);
router.delete('/delete/:categoryId', validarJWT, esAdmin,  deleteCategory);

module.exports = router
