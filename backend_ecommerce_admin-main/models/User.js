const { Schema, model } = require("mongoose");

const UserSchema = new Schema({
    userId: {type: String, required: true},
    name: {type: String, required: true, minlength: 6},
    email: {type: String, required: true, unique: true},
    password: {type: String, required: true, minlength: 6},
    role: {type: Schema.Types.ObjectId, ref: "Role", required: true},
    seller: {type: Schema.Types.ObjectId, ref: "Seller", required: true},
    createDate: {type: Date, required: true},
    updateDate: {type: Date, required: true},
});

module.exports = model('User', UserSchema);
