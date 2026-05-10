import React, { useState, useEffect } from 'react'
import { EstadoEquipoCrear } from './EstadoEquipoCrear';
import { getEstadoEquipos } from '../../services/estadoEquipoService';
import Swal from 'sweetalert2';
import { Link } from 'react-router-dom';

export const EstadoEquipoView = () => {

  const [estadoEquipos, setEstadoEquipos] = useState([]);

  const listarEstadoEquipos = async () => {
    try {
      Swal.fire({
        allowOutsideClick: false,
        title: 'Cargando....',
        text: 'Por favor espere',
        timer: 1000//milisegundos
      });
      Swal.showLoading();
      const { data } = await getEstadoEquipos();
      setEstadoEquipos(data);
      Swal.close();
    } catch (error) {
      console.log('ocurrió un error')
      Swal.close();
    }
  };

  useEffect(() => {
    listarEstadoEquipos();
  }, []);

  return (
    <div className='container-fluid'>
      <div className='card mt-3 mb-2'>
        <div className='card-header'>
          <h5>EstadoEquipos</h5>
        </div>
        <div className='card-body'>
          <EstadoEquipoCrear listarEstadoEquipos={listarEstadoEquipos} />
          <div className='row mt-5'>
            <div className='col'>
              <table className="table">
                <thead>
                  <tr>
                    <th className="col-md-2">#</th>
                    <th className="col-md-2">Nombre</th>
                    <th className="col-md-1">Estado</th>
                    <th className="col-md-3">Fecha Creación</th>
                    <th className="col-md-3">Fecha Actualización</th>
                    <th className="col-md-1">Acciones</th>
                  </tr>
                </thead>
                <tbody>
                  {
                    estadoEquipos.map((estadoEquipo) => {
                      return (
                        <tr key={estadoEquipo._id}>
                          <th className="col-md-2" value='index'>{1 + estadoEquipos.indexOf(estadoEquipo)} </th>
                          <td className="col-md-2">{estadoEquipo.nombre}</td>
                          <td className="col-md-1">{estadoEquipo.estado}</td>
                          <td className="col-md-3">{estadoEquipo.fechaCreacion}</td>
                          <td className="col-md-3">{estadoEquipo.fechaActualizacion}</td>
                          <td className="col-md-1">
                            <Link to={`estadoEquipo/edit/${estadoEquipo._id}`}>
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


