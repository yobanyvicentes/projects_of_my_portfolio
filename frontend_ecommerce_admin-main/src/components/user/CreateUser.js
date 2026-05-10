import React, { useState, useEffect } from "react";
import { postUser } from "../../services/user";
import { getRoles } from "../../services/role";
import Swal from "sweetalert2";

export const CreateUser = ({ listUsers }) => {
  const [roles, setRoles] = useState([]);

  const listRoles = async () => {
    try {
      const { data } = await getRoles();
      setRoles(data);
      console.log("roles: ", data)
    } catch (error) {
      console.log(error);
    }
  };
  useEffect(() => {
    listRoles();
  }, []);

  const [formValues, setFormValues] = useState({});
  const {
    name = "",
    user_id = "",
    email = "",
    password = "",
    role = "",
  } = formValues;

  const handleOnSubmit = async (e) => {
    e.preventDefault();
    const userModel = { name, user_id, email, password, role };
    console.log(1, userModel);
    try {
      console.log("data2: ");
      Swal.showLoading();
      console.log("data3: ");
      const { data } = await postUser(userModel);
      console.log("data4: ", data);
      listUsers();
      setFormValues("");
      Swal.close();
    } catch (error) {
      Swal.fire("Error", "hubo un error al crear el usuario", "error");
      console.log("error al crear el usuario");
    }
  };

  const handleOnChange = ({ target }) => {
    const { name, value } = target;
    setFormValues({ ...formValues, [name]: value });
  };

  return (
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
                  Email
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
            <div className="col-md-3">
              <div className="mb-1">
                <label className="form-label" for="passwordId">
                  Contraseña
                </label>
                <input
                  className="form-control"
                  name="password"
                  type="password"
                  value={password}
                  id="passwordId"
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
  );
};
