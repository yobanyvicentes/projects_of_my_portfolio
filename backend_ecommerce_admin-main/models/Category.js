const { Schema, model } = require("mongoose");

const CategorySchema = Schema({
    internalId: {type: String, required: true},
    name: {type: String, required: true},
    seller: {type: Schema.Types.ObjectId, ref: "Seller", required: true},
    createDate: {type: Date, required: true,},
    updateDate: {type: Date, required: true,},
});

module.exports = model('Category', CategorySchema);

