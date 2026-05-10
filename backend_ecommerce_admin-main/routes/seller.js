const { Router } = require('express');
const { body } = require('express-validator');
const {createSeller, listAllSeller, deleteSeller, getOneSeller, updateSeller} = require('../controllers/seller.js');
const { validarJWT } = require('../middleware/tokenJWT.js');
const { esAdmin } = require('../middleware/roleValidator.js');

const router = Router();

router.get('/', validarJWT, esAdmin,  listAllSeller);
router.post('/', validarJWT, esAdmin,  [body(['internal_id', 'name', 'description'], 'el campo descrito en el path está vacio, debe asignarle un valor').notEmpty()], createSeller);
router.get('/:sellerId', validarJWT, esAdmin,  getOneSeller);
router.put('/edit/:sellerId', validarJWT, esAdmin,  [body(['internal_id', 'name', 'description'], 'el campo descrito en el path está vacio, debe asignarle un valor').notEmpty()], updateSeller);
router.delete('/delete/:sellerId', validarJWT, esAdmin, deleteSeller);

module.exports = router
