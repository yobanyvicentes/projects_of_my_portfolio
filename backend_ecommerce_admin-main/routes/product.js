const { Router } = require('express');
const { body } = require('express-validator');
const {createProduct, listAllProduct, deleteProduct, getOneProduct, updateProduct} = require('../controllers/product.js');
const { validarJWT } = require('../middleware/tokenJWT.js');
const { esAdmin } = require('../middleware/roleValidator.js');

const router = Router();

router.get('/', validarJWT, esAdmin,  listAllProduct);
router.post('/', validarJWT, esAdmin,  [body(['internal_id', 'name', 'description', 'image', 'price', 'inventory',  'brand', 'category'], 'el campo descrito en el path está vacio, debe asignarle un valor').notEmpty()], createProduct);
router.get('/:productId', validarJWT, esAdmin,  getOneProduct);
router.put('/edit/:productId', validarJWT, esAdmin,  [body(['internal_id', 'name', 'description', 'image', 'price', 'inventory', 'brand', 'category'], 'el campo descrito en el path está vacio, debe asignarle un valor').notEmpty()], updateProduct);
router.delete('/delete/:productId', validarJWT, esAdmin,  deleteProduct);

module.exports = router
