//importar Router desde express
const {Router} = require('express');
const router = Router();
//importar funciones
const {getUsuario, getUsuarios, postUsuario, putUsuario} = require('../controllers/usuario')


router.get('/:usuarioId', getUsuario);
//listar
router.get('/', getUsuarios);
//------------------------------------------------------------------------------

//crear
router.post('/', postUsuario);
//------------------------------------------------------------------------------

//actualizar
router.put('/:usuarioId',  putUsuario);
//____________________________________________________________________________________________________________

module.exports = router;
