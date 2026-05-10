const jwt = require('jsonwebtoken');
require('dotenv').config();

const validarJWT = (req, res, next) => {
    const token = req.header('access-token');

    if (! token) {
        return res.status(401).json({
            msg: "inicie sesión primero"
        })
    }
    try {
        const payload = jwt.verify(token, process.env.SECRET_KEY);
        //preparar el request para el siguiente middleware
        req.user = payload;
        req.tenantName = payload.tenantName;
        //Dar paso al siguiente middleware
        next();
    } catch (error) {
        return res.status(401).json({
            msg: "token invalido",
            msgStatus: false,
            error
        });
    }
}

module.exports = {validarJWT};
