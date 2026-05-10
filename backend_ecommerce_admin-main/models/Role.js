const { Schema, model } = require("mongoose");

const RoleSchema = Schema({
    internalId: {type: String, required: true, unique: true, enum: ["operator", "admin"]},
    name: {type: String, required: true,},
    createDate: {type: Date, required: true,},
    updateDate: {type: Date, required: true,},
});

module.exports = model('Role', RoleSchema);
