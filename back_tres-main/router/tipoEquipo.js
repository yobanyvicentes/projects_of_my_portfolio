//importar funciones
const {getTipo, getTipos, postTipo, putTipo} = require('../controllers/tipoEquipo');
//importar Router desde express
const {Router} = require('express');
const router = Router();

router.get('/:TipoID', getTipo);

router.get('/',  getTipos);

router.post('/', postTipo);

router.put('/:TipoID', putTipo);

module.exports = router;
