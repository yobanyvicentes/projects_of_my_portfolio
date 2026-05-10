const { Router } = require('express');
const { body } = require('express-validator');
const {createBrand, listAllBrand, deleteBrand, getOneBrand, updateBrand} = require('../controllers/brand.js');
const { validarJWT } = require('../middleware/tokenJWT.js');
const { esAdmin } = require('../middleware/roleValidator.js');

const router = Router();

router.get('/', validarJWT, esAdmin, listAllBrand);
router.post('/', validarJWT, esAdmin,  [body(['internal_id', 'name'], 'el campo descrito en el path está vacio, debe asignarle un valor').notEmpty()], createBrand);
router.get('/:brandId', validarJWT, esAdmin,  getOneBrand);
router.put('/edit/:brandId', validarJWT, esAdmin,  [body(['internal_id', 'name'], 'el campo descrito en el path está vacio, debe asignarle un valor').notEmpty()], updateBrand);
router.delete('/delete/:brandId', validarJWT, esAdmin,  deleteBrand);

module.exports = router
