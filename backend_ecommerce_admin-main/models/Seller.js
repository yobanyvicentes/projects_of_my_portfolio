const { Schema, model } = require("mongoose");

const SellerSchema = Schema({
    internalId: {type: String, required: true},
    name: {type: String, required: true, minlength: 6, unique: true},
    description: {type: String, required: true, minlength: 6},
    createDate: {type: Date, required: true,},
    updateDate: {type: Date, required: true,},
    brands: {type: Number}
});


module.exports = model('Seller', SellerSchema);

