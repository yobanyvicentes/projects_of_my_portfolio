const { Router } = require('express');
const { body } = require('express-validator');
const { login } = require('../controllers/auth.js');

const router = Router();

router.post('/login', [body(['email', 'password'], 'el campo descrito en el path está vacio, debe asignarle un valor').notEmpty()], login);

module.exports = router
