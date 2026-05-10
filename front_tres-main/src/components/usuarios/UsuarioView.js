import React, { useState, useEffect } from 'react'
import { UsuarioCrear } from './UsuarioCrear';
import { getUsuarios } from '../../services/usuarioService';
import Swal from 'sweetalert2';
import { Link } from 'react-router-dom';

export const UsuarioView = () => {

  const [usuarios, setUsuarios] = useState([]);

  const listarUsuarios = async () => {
    try {
      Swal.fire({
        allowOutsideClick: false,
        title: 'Cargando....',
        text: 'Por favor espere',
        timer: 1000//milisegundos
      });
      Swal.showLoading();
      const { data } = await getUsuarios();
      setUsuarios(data);
      Swal.close();
    } catch (error) {
      console.log('ocurrió un error')
      Swal.close();
    }
  };

  useEffect(() => {
    listarUsuarios();
  }, []);

  return (
    <div className='container-fluid'>
      <div className='card mt-3 mb-2'>
        <div className='card-header'>
          <h5>Usuarios</h5>
        </div>
        <div className='card-body'>
          <UsuarioCrear listarUsuarios={listarUsuarios} />
          <div className='row mt-5'>
            <div className='col'>
              <table className="table">
                <thead>
                  <tr>
                    <th className="col-md-1">#</th>
                    <th className="col-md-2">Nombre</th>
                    <th className="col-md-2">Email</th>
                    <th className="col-md-1">Rol</th>
                    <th className="col-md-1">Estado</th>
                    <th className="col-md-2">Fecha Creación</th>
                    <th className="col-md-2">Fecha Actualización</th>
                    <th className="col-md-1">Acciones</th>
                  </tr>
                </thead>
                <tbody>
                  {
                    usuarios.map((usuario) => {
                      return (
                        <tr key={usuario._id}>
                          <th className="col-md-1" value='index'>{1 + usuarios.indexOf(usuario)} </th>
                          <td className="col-md-2">{usuario.nombre}</td>
                          <td className="col-md-2">{usuario.email}</td>
                          <td className="col-md-1">{usuario.rol}</td>
                          <td className="col-md-1">{usuario.estado}</td>
                          <td className="col-md-2">{usuario.fechaCreacion}</td>
                          <td className="col-md-2">{usuario.fechaActualizacion}</td>
                          <td className="col-md-1">
                            <Link to={`usuario/edit/${usuario._id}`}>
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
