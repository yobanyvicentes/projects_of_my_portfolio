//importar el modelo User
require('dotenv').config();
const User = require('../models/User');
const bcrypt = require('bcryptjs');
const jwt = require('jsonwebtoken');

//metodo login
const login = async (req, res, next) => {
    try {
        const { email, password } = req.body;
        //validar existencia user
        const user = await User.findOne({ email }).populate([
            {path: 'role' , select: 'name'},
            {path: 'seller' , select: 'name'},
        ]);
        if (!user) {
            return res.status(404).json({
                msg: "el usuario ingresado no existe"
            })
        };
        //validar que la contraseña sea correcta
        const esPassword = await bcrypt.compare(password, user.password);
        if (!esPassword) {
            return res.status(404).json({
                message: "contraseña incorrecta"
            })
        };
        //generar token
        const payload = {
            user: user.email,
            name: user.nombre,
            role: user.role.name,
            tenantName: user.seller.name,
        };
        const token = jwt.sign(
            payload,
            process.env.SECRET_KEY,
            { expiresIn: '1h'}
        );
        next();
        res.json({ token });
    } catch (error) {
        console.log(error);
        res.status(500).send('hay un error');
    }
}

module.exports = { login };
