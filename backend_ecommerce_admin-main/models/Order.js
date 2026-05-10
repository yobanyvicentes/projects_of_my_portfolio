const { Schema, model } = require("mongoose");

const OrderSchema = new Schema({
    internalId: {type: String, required: true},
    email: {type: String, required: true, unique: true},
    total: {type: Number, required: true},
    address: {type: String, required: true},
    state: {type: String, required: true, enum: ["En espera", "En camino", "Entregado"]},
    deliverDate: {type: Date, required: false},
    products: [{type: Schema.Types.ObjectId, ref: "Product", required: true}],
    seller: {type: Schema.Types.ObjectId, ref: "Seller", required: true},
    createDate: {type: Date, required: true},
    updateDate: {type: Date, required: true},
});

module.exports = model('Order', OrderSchema);
