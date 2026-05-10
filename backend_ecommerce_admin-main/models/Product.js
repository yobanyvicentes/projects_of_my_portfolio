const { Schema, model } = require("mongoose");

const productSchema = Schema({
    internalId: {type: String, required: true},
    name: {type: String, required: true, minlength: 3},
    description: {type: String, required: true, minlength: 3},
    image: {type: String, required: true},
    price: {type: Number, required: true,
        validate:{
            validator:(value)=>{return value > 0;},
            message: "el precio debe ser mayor a cero"
        }
    },
    inventory: {type: Number, required: true,
        validate:{
            validator:(value)=>{return value > 0;},
            message: "el inventario debe ser mayor a cero"
        }
    },
    brand: {type: Schema.Types.ObjectId, ref: "Brand", required: true},
    category: {type: Schema.Types.ObjectId, ref: "Category", required: true},
    seller: {type: Schema.Types.ObjectId, ref: "Seller", required: false},
    createDate: {type: Date, required: true},
    updateDate: {type: Date, required: true},
});

module.exports = model('Product', productSchema);
