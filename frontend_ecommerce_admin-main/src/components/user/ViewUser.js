import React, { useState, useEffect } from 'react'
import { CreateUser } from './CreateUser';
import { getUsers, deleteUserById } from '../../services/user';
import Swal from 'sweetalert2';
import { Link } from 'react-router-dom';

export const ViewUser = () => {

  const [users, setUsers] = useState([]);

  const listUsers = async () => {
    try {
      Swal.showLoading();
      const { data } = await getUsers();
      setUsers(data);
      Swal.close();
    } catch (error) {
      console.log('ocurrió un error', error)
    }
  };

  useEffect(() => {
    listUsers();
    console.log(listUsers())
  }, []);

  const deleteUser = async (userId) => {
    try {
      Swal.showLoading();
      const { data } = await deleteUserById(userId);
      console.log("user deleted: ", data);
      listUsers();
      Swal.close();
    } catch (error) {
      Swal.fire({
        allowOutsideClick: false, title: 'Error', text: 'Hubo un error al intentar eliminar'
      });
      console.log('ocurrió un error');
    }
  }

  //
  return (
    <div className='container-fluid'>
      <div className='card mt-1 mb-1'>
        <div className='card-header'>
          <h5>Usuarios</h5>
        </div>
        <div className='card-body'>
          <CreateUser listUsers={listUsers} />
          <div className='row mt-2'>
            <div className='col'>
              <table className="table">
                <thead>
                  <tr>
                    <th className="col-md-1">#</th>
                    <th className="col-md-2">Nombre</th>
                    <th className="col-md-2">ID</th>
                    <th className="col-md-2">Usuario</th>
                    <th className="col-md-1">Role</th>
                    <th className="col-md-1">Tenant</th>
                    <th className="col-md-2">Fecha de Actualización</th>
                    <th className="col-md-1">Acciones</th>
                  </tr>
                </thead>
                <tbody>
                  {
                    users.map((user) => {
                      return (
                        <tr key={user._id}>
                          <th className="col-md-1" value='index'>{1 + users.indexOf(user)} </th>
                          <td className="col-md-2">{user.name}</td>
                          <td className="col-md-2">{user.userId}</td>
                          <td className="col-md-2">{user.email}</td>
                          <td className="col-md-1">{user.role.name}</td>
                          <td className="col-md-1">{user.seller.name}</td>
                          <td className="col-md-2">{user.updateDate}</td>
                          <td className="col-md-1">
                            <Link to={`user/edit/${user._id}`}>
                              <i class="fa-regular fa-pen-to-square"></i>
                            </Link>
                            <Link to={`user`}>
                              <i class="fa-solid fa-trash" onClick={()=>{deleteUser(user._id)}}></i>
                            </Link>
                          </td>
                        </tr>
                      )
                    })
                  }
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  )
}
