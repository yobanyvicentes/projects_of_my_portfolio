const {getInventarios,getInventario,postInventario,putInventario} = require('../controllers/inventario')

const {Router} = require('express');
const router = Router();

router.get('/', getInventarios);

router.get('/:inventarioId', getInventario);

router.post('/', postInventario);

router.put('/:inventarioId', putInventario);

module.exports = router;
