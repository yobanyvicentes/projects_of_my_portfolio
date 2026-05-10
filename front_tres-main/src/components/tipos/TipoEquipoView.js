import React, { useState, useEffect } from 'react'
import { TipoEquipoCrear } from './TipoEquipoCrear';
import { getTipoEquipos } from '../../services/tipoEquipoService';
import Swal from 'sweetalert2';
import { Link } from 'react-router-dom';

export const TipoEquipoView = () => {

  const [tipoEquipos, setTipoEquipos] = useState([]);

  const listarTipoEquipos = async () => {
    try {
      Swal.fire({
        allowOutsideClick: false,
        title: 'Cargando....',
        text: 'Por favor espere',
        timer: 1000//milisegundos
      });
      Swal.showLoading();
      const { data } = await getTipoEquipos();
      setTipoEquipos(data);
      Swal.close();
    } catch (error) {
      console.log('ocurrió un error')
      Swal.close();
    }
  };

  useEffect(() => {
    listarTipoEquipos();
  }, []);

  return (
    <div className='container-fluid'>
      <div className='card mt-3 mb-2'>
        <div className='card-header'>
          <h5>TipoEquipos</h5>
        </div>
        <div className='card-body'>
          <TipoEquipoCrear listarTipoEquipos={listarTipoEquipos} />
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
                    tipoEquipos.map((tipoEquipo) => {
                      return (
                        <tr key={tipoEquipo._id}>
                          <th className="col-md-2" value='index'>{1 + tipoEquipos.indexOf(tipoEquipo)} </th>
                          <td className="col-md-2">{tipoEquipo.nombre}</td>
                          <td className="col-md-1">{tipoEquipo.estado}</td>
                          <td className="col-md-3">{tipoEquipo.fechaCreacion}</td>
                          <td className="col-md-3">{tipoEquipo.fechaActualizacion}</td>
                          <td className="col-md-1">
                            <Link to={`tipoEquipo/edit/${tipoEquipo._id}`}>
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




