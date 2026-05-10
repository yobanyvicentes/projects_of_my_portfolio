import React, { useState, useEffect } from 'react'
import { CreateRole } from './CreateRole';
import { getRoles } from '../../services/role';
import Swal from 'sweetalert2';
import { Link } from 'react-router-dom';

export const ViewRole = () => {

  const [roles, setRoles] = useState([]);

  const listRoles = async () => {
    try {
      Swal.fire({
        allowOutsideClick: false,
        title: '....',
        text: 'Por favor espere',
      });
      const { data } = await getRoles();
      setRoles(data);
      Swal.close();
    } catch (error) {
      console.log('ocurrió un error')
      Swal.close();
    }
  };

  useEffect(() => {
    listRoles();
  }, []);

  return (
    <div className='container-fluid'>
      <div className='card mt-3 mb-2'>
        <div className='card-header'>
          <h5>Marcas</h5>
        </div>
        <div className='card-body'>
          <CreateRole listRoles={listRoles} />
          <div className='row mt-5'>
            <div className='col'>
              <table className="table">
                <thead>
                  <tr>
                    <th className="col-md-1">#</th>
                    <th className="col-md-3">Nombre</th>
                    <th className="col-md-1">ID</th>
                    <th className="col-md-3">Fecha Creación</th>
                    <th className="col-md-3">Fecha Actualización</th>
                    <th className="col-md-1">Acciones</th>
                  </tr>
                </thead>
                <tbody>
                  {
                    roles.map((role) => {
                      return (
                        <tr key={role._id}>
                          <th className="col-md-1" value='index'>{1 + roles.indexOf(role)} </th>
                          <td className="col-md-3">{role.name}</td>
                          <td className="col-md-1">{role.internalId}</td>
                          <td className="col-md-3">{role.createDate}</td>
                          <td className="col-md-3">{role.updateDate}</td>
                          <td className="col-md-1">
                            <Link to={`role/edit/${role._id}`}>
                              <button className='btn btn-success'>
                                editar
                              </button>
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
