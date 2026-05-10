import React, { useState, useEffect } from "react";
import Swal from "sweetalert2";
import { getRoles } from "../../services/role";
import { useParams, useHistory } from "react-router-dom";
import { getUserById, putUser } from "../../services/user";

export const UpdateUser = () => {
const history = useHistory();

const [roles, setRoles] = useState([]);

const listRoles = async () => {
    try {
    const { data } = await getRoles();
    setRoles(data);
    } catch (error) {
    console.log(error);
    }
};
useEffect(() => {
    listRoles();
}, []);

const { userId = "" } = useParams();
const [user, setUser] = useState({});

const [formValues, setFormValues] = useState({});
  const {
    name = "",
    user_id = "",
    email = "",
    role = "",
  } = formValues;

const getUser = async () => {
    try {
    Swal.showLoading();
    Swal.close();
    const { data } = await getUserById(userId);
    let { role } = data;
    let roleId = role._id;
    data.role = roleId;
    setUser(data);
    } catch (error) {
    Swal.close();
    console.log(error);
    }
};

useEffect(() => {
    getUser();
}, [userId]);

useEffect(() => {
    setFormValues({
    name: user.name,
    user_id: user.userId,
    email: user.email,
    role: user.role,
    });
}, [user]);

const handleOnSubmit = async (e) => {
    e.preventDefault();
    const userModel = { name, user_id, email, role: { _id: role } };
    try {
    console.log("model:", userModel)
    Swal.fire({
        allowOutsideClick: false,
        title: "Cargando....",
        text: "Por favor espere",
    });
    const { data } = await putUser(userId, userModel);
    console.log(data);
    Swal.close();
    setFormValues("");
    history.push("/user");
    } catch (error) {
    Swal.fire("Error", "hubo un error al intentar actualizar", "error");
    console.log("error al actualizar la usuario");
    }
};

const handleOnChange = ({ target }) => {
    const { name, value } = target;
    setFormValues({ ...formValues, [name]: value });
};

return (
    <div className="container-fluid">
    <div className="card mt-3 mb-2">
        <div className="card-header">
        <h5>Usuarios</h5>
        </div>
        <div className="card-body">
        <div className="row">
            <div className="col">
            <form
                className="form"
                onSubmit={(e) => {
                handleOnSubmit(e);
                }}
            >
                <div className="row" te>
            <h5>Crear Usuario</h5>
          </div>
          <div className="row">
            <div className="col-md-3">
              <div className="mb-1">
                <label className="form-label" for="nameId">
                  Nombre
                </label>
                <input
                  className="form-control"
                  type="text"
                  name="name"
                  value={name}
                  id="nameId"
                  required
                  onChange={(e) => {
                    handleOnChange(e);
                  }}
                />
              </div>
            </div>
            <div className="col-md-3">
              <div className="mb-1">
                <label className="form-label" for="userId">
                  ID
                </label>
                <input
                  className="form-control"
                  name="user_id"
                  value={user_id}
                  id="userId"
                  required
                  onChange={(e) => {
                    handleOnChange(e);
                  }}
                />
              </div>
            </div>
            <div className="col-md-3">
              <div className="mb-1">
                <label className="form-label" for="emailId">
                  Email (usuario)
                </label>
                <input
                  className="form-control"
                  type="email"
                  name="email"
                  value={email}
                  id="emailId"
                  required
                  onChange={(e) => {
                    handleOnChange(e);
                  }}
                />
              </div>
            </div>
            <div className="col-md-2">
              <div className="mb-1">
                <label className="form-label" for="roleId">
                  Roles
                </label>
                <select
                  className="form-control"
                  name="role"
                  value={role}
                  id="roleId"
                  required
                  onChange={(e) => {
                    handleOnChange(e);
                  }}
                >
                  <option selected> -seleccionar-</option>
                  {roles.map((role) => {
                    return (
                      <option key={role._id} value={role._id}>
                        {role.name}
                      </option>
                    );
                  })}
                </select>
              </div>
            </div>
            <div className="col-md-2">
              <div className="mb-1 mt-2">
                <button className="btn btn-primary mt-4" type="onSubmit">
                  Guardar
                </button>
              </div>
            </div>
          </div>
            </form>
            </div>
        </div>
        </div>
    </div>
    </div>
);
};
