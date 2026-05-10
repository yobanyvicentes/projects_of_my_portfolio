//importar el modelo bd de Marca
const { getMarca, getMarcas, postMarca, putMarca } = require('../controllers/marca');
//importar Router desde express
const { Router } = require('express');
const router = Router();

router.get('/:idMarca', getMarca);

router.get('/', getMarcas);

router.post('/', postMarca);

router.put('/:idMarca', putMarca);

module.exports = router;
